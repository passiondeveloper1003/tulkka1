<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FeatureWebinar;
use App\Models\Sale;
use App\Models\Ticket;
use App\Models\Translation\CategoryTranslation;
use App\Models\Webinar;
use App\Models\WebinarFilterOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Role;

class CategoriesController extends Controller
{
    public function index(Request $request, $categoryTitle, $subCategoryTitle = null)
    {

        if (!empty($categoryTitle)) {

            $categoryTranslation = CategoryTranslation::where('title', str_replace('-', ' ', $categoryTitle))->first();

            $subCategoryTranslation = null;

            if (!empty($subCategoryTitle)) {
                $subCategoryTranslation = CategoryTranslation::where('title', str_replace('-', ' ', $subCategoryTitle))->get();
            }

            if (!empty($categoryTranslation)) {
                $category = Category::where(function ($query) use ($categoryTranslation, $subCategoryTranslation) {
                    if (!empty($subCategoryTranslation)) {
                        $query->whereIn('id', $subCategoryTranslation->pluck('category_id')->toArray());
                        $query->where('parent_id', $categoryTranslation->category_id);
                    } else {
                        $query->where('id', $categoryTranslation->category_id);
                    }
                })->withCount('webinars')
                    ->with(['filters' => function ($query) {
                        $query->with('options');
                    }])
                    ->first();
            }

            if (!empty($category)) {
                $featureWebinars = FeatureWebinar::whereIn('page', ['categories', 'home_categories'])
                    ->where('status', 'publish')
                    ->whereHas('webinar', function ($q) use ($category) {
                        $q->where('status', Webinar::$active);
                        $q->whereHas('category', function ($q) use ($category) {
                            $q->where('id', $category->id);
                        });
                    })
                    ->with(['webinar' => function ($query) {
                        $query->with(['teacher' => function ($qu) {
                            $qu->select('id', 'full_name', 'avatar');
                        }, 'reviews', 'tickets', 'feature']);
                    }])
                    ->orderBy('updated_at', 'desc')
                    ->get();


                $webinarsQuery = Webinar::where('webinars.status', 'active')
                    ->where('private', false)
                    ->where('category_id', $category->id);

                $classesController = new ClassesController();
                $webinarsQuery = $classesController->handleFilters($request, $webinarsQuery);

                $sort = $request->get('sort', null);

                if (empty($sort)) {
                    $webinarsQuery = $webinarsQuery->orderBy('webinars.created_at', 'desc')
                        ->orderBy('webinars.updated_at', 'desc');
                }

                $webinars = $webinarsQuery->with(['tickets', 'feature'])
                    ->paginate(6);

                $instructorsQuery = User::where('users.status', 'active')
                    ->where(function ($query) {
                        $query->where('users.ban', false)
                            ->orWhere(function ($query) {
                                $query->whereNotNull('users.ban_end_at')
                                    ->orWhere('users.ban_end_at', '<', time());
                            });
                    })
                    ->with([
                        'meeting' => function ($query) {
                            $query->with('meetingTimes');
                            $query->withCount('meetingTimes');
                        },
                        'occupations'
                    ]);

                    $instructorsQuery = $this->handleFilters($instructorsQuery, $request);
                    $instructors = deepClone($instructorsQuery)->paginate(6);
                    foreach ($instructors as $instructor) {
                      $instructor->location = $instructor->userLocation;
                  }

                  if ($request->ajax()) {
                      return $this->handleLoadMoreHtml($instructors);
                  }


                $seoSettings = getSeoMetas('categories');
                $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.categories_page_title');
                $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.categories_page_title');
                $pageRobot = getPageRobot('categories');

                $data = [
                    'pageTitle' => $pageTitle,
                    'pageDescription' => $pageDescription,
                    'pageRobot' => $pageRobot,
                    'category' => $category,
                    'webinars' => $webinars,
                    'instructors' => $instructors,
                    'featureWebinars' => $featureWebinars,
                    'webinarsCount' => $webinars->total(),
                    'sortFormAction' => $category->getUrl(),
                ];

                return view(getTemplate() . '.pages.categories', $data);
            }
        }

        abort(404);
    }
    private function handleLoadMoreHtml($instructors)
    {

        $html = null;

        foreach ($instructors as $instructor) {
            $html .= (string)view()->make('web.default.instructorFinder.components.instructor_card', ['instructor' => $instructor]);
        }

        return response()->json([
            'html' => $html,
            'last_page' => $instructors->lastPage(),
        ], 200);
    }
    private function handleFilters($query, Request $request)
    {
        $categoryId = $request->get('category_id', null);
        $levelOfTraining = $request->get('level_of_training', null);
        $gender = $request->get('gender', null);
        $meetingSupport = $request->get('meeting_type', null);
        $population = $request->get('population', null);
        $countryId = $request->get('country_id', null);
        $provinceId = $request->get('province_id', null);
        $cityId = $request->get('city_id', null);
        $districtId = $request->get('district_id', null);
        $sort = $request->get('sort', null);
        $availableForMeetings = $request->get('available_for_meetings', null);
        $hasFreeMeetings = $request->get('free_meetings', null);
        $withDiscount = $request->get('discount', null);

        if (empty($request->get('role', null))) {
            $role = [Role::$organization, Role::$teacher];
        } else {
            $role = [$request->get('role')];
        }

        $query->whereIn('users.role_name', $role);

        if (!empty($categoryId)) {
            $userIds = UserOccupation::where('category_id', $categoryId)->pluck('user_id')->toArray();

            $query->whereIn('users.id', $userIds);
        }

        if (!empty($levelOfTraining) and in_array($levelOfTraining, UserLevelOfTraining::$levelOfTraining)) {
            $levelBit = (new UserLevelOfTraining())->getValue($levelOfTraining);

            $query->whereRaw('users.level_of_training & ? > 0', [$levelBit]);
        }

        if (!empty($gender)) {
            $userIds = UserMeta::where('name', 'gender')
                ->where('value', $gender)
                ->pluck('user_id')
                ->toArray();

            $query->whereIn('users.id', $userIds);
        }

        if (!empty($meetingSupport) and $meetingSupport != 'all') {
            $query->where('users.meeting_type', $meetingSupport);
        }

        if (!empty($population) and in_array($population, ['single', 'group'])) {
            $query->whereHas('meeting', function ($query) use ($population) {
                if ($population == 'single') {
                    $query->where('group_meeting', false);
                } elseif ($population == 'group') {
                    $query->where('group_meeting', true);
                }
            });
        }

        $query = $this->handlePriceFilter($query, $request);


        $query = $this->handleAgeFilter($query, $request);


        if (!empty($countryId)) {
            $query->where('country_id', $countryId);
        }
        if (!empty($provinceId)) {
            $query->where('province_id', $provinceId);
        }
        if (!empty($cityId)) {
            $query->where('city_id', $cityId);
        }
        if (!empty($districtId)) {
            $query->where('district_id', $districtId);
        }

        $query = $this->handleDaysAndTimeFilter($query, $request);

        if (!empty($availableForMeetings) and $availableForMeetings == 'on') {
            $query = $this->handleAvailableForMeetings($query);
        }

        if (!empty($hasFreeMeetings) and $hasFreeMeetings == 'on') {
            $query = $this->handleHasFreeMeetings($query);
        }

        if (!empty($withDiscount) and $withDiscount == 'on') {
            $query = $this->handleWithDiscount($query);
        }

        if (!empty($sort)) {
            if ($sort == 'top_rate') {
                $roleForSort = ($request->get('role') == Role::$organization) ? Role::$organization : Role::$teacher;

                $query = $this->getBestRateUsers($query, $roleForSort);
            }

            if ($sort == 'top_sale') {
                $query = $this->getTopSalesUsers($query);
            }
        } else {
            // order by meetings
            $query->leftJoin('meetings', 'meetings.creator_id', '=', 'users.id')
                ->select('users.*', DB::raw('count(meetings.id) as meetingCounts'))
                ->groupBy('users.id')
                ->orderBy('meetingCounts', 'desc')
                ->orderBy('users.id', 'desc');
        }

        return $query;
    }
    private function handlePriceFilter($query, Request $request)
    {
        $minPrice = $request->get('min_price', null);
        $maxPrice = $request->get('max_price', null);

        if (!empty($minPrice) or !empty($maxPrice)) {
            $userIds = Meeting::where('disabled', false)
                ->where(function ($query) use ($minPrice, $maxPrice) {
                    if (!empty($minPrice)) {
                        $query->where('amount', '>=', $minPrice);

                        if (!empty($maxPrice)) {
                            $query->where('amount', '<=', $maxPrice);
                        }
                    } else {
                        $query->whereNull('amount');
                        $query->orWhere('amount', '=', '0');
                    }
                })
                ->pluck('creator_id')
                ->toArray();

            $query->whereIn('users.id', $userIds);
        }

        return $query;
    }

    private function handleAgeFilter($query, Request $request)
    {
        $minAge = $request->get('min_age', null);
        $maxAge = $request->get('max_age', null);

        if (!empty($minAge) or !empty($maxAge)) {
            $userAgeQuery = UserMeta::where('name', 'age');

            if (!empty($minAge)) {
                $userAgeQuery->whereRaw('value >= ' . $minAge);
            }

            if (!empty($maxAge)) {
                $userAgeQuery->whereRaw('value <= ' . $maxAge);
            }

            $userIds = $userAgeQuery->pluck('user_id')->toArray();

            $query->whereIn('users.id', $userIds);
        }

        return $query;
    }

    private function handleDaysAndTimeFilter($query, Request $request)
    {
        $days = $request->get('day');
        $minTime = $request->get('min_time');
        $maxTime = $request->get('max_time');

        if ($minTime < 0) {
            $minTime = 0;
        }

        if ($maxTime > 23) {
            $maxTime = 23;
        }

        if ($maxTime == 23) {
            $maxTime = '23:59';
        }

        if (!empty($minTime) and !empty($maxTime)) {
            $minTimeFilter = Carbon::createFromTimeString($minTime);
            $maxTimeFilter = Carbon::createFromTimeString($maxTime);

            $meetingsTimes = null;

            if (!empty($days) and is_array($days)) {
                $meetingsTimes = MeetingTime::whereIn('meeting_times.day_label', $days)
                    ->get();
            } else if ($minTime != '1' and $maxTime != '23:59') {
                $meetingsTimes = MeetingTime::query()->get();
            }

            if (!empty($meetingsTimes)) {
                $meetingsIds = [];

                foreach ($meetingsTimes as $meetingsTime) {
                    $time = explode('-', $meetingsTime->time);

                    $startTime = Carbon::createFromTimeString($time[0]);
                    $endTime = Carbon::createFromTimeString($time[1]);

                    if ($minTimeFilter <= $startTime and $maxTimeFilter >= $endTime) {
                        $meetingsIds[] = $meetingsTime->meeting_id;
                    }
                }

                $userIds = Meeting::whereIn('id', $meetingsIds)
                    ->where('disabled', false)
                    ->pluck('creator_id')
                    ->toArray();

                $query->whereIn('users.id', $userIds);
            }
        }

        return $query;
    }

}
