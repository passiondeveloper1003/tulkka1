<?php

namespace App\Http\Controllers\Panel;

use App\Bitwise\UserLevelOfTraining;
use App\Http\Controllers\Controller;
use App\Mixins\RegistrationPackage\UserPackage;
use App\Models\Category;
use App\Models\DeleteAccountRequest;
use App\Models\Meeting;
use App\Models\Newsletter;
use App\Models\Region;
use App\Models\ReserveMeeting;
use App\Models\Reward;
use App\Models\RewardAccounting;
use App\Models\Role;
use App\Models\UserMeta;
use App\Models\UserOccupation;
use App\Models\UserZoomApi;
use App\User;
use App\UserGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Pawlox\VideoThumbnail\Facade\VideoThumbnail;

class UserController extends Controller
{
    public function setting($step = 1)
    {
        $user = auth()->user();

        if (!empty($user->location)) {
            $user->location = \Geo::getST_AsTextFromBinary($user->location);

            $user->location = \Geo::get_geo_array($user->location);
        }

        $categories = Category::where('parent_id', null)
            ->with('subCategories')
            ->get();


        $userMetas = $user->userMetas;

        if (!empty($userMetas)) {
            foreach ($userMetas as $meta) {
                $user->{$meta->name} = $meta->value;
            }
        }

        $occupations = $user->occupations->toArray();
        foreach ($occupations as $occupation) {
            foreach ($categories as $category) {
                if (isset($category->type) && $occupation['category_id'] == $category->id) {
                    $category->type2 = true;
                }
                if ($occupation['category_id'] == $category->id) {
                    $category->selected = true;
                    $type = $occupation['type'];
                    $category->type = $occupation['type'];
                }
            }
        }
        $userLanguages = getGeneralSettings('user_languages');
        if (!empty($userLanguages) and is_array($userLanguages)) {
            $userLanguages = getLanguages($userLanguages);
        } else {
            $userLanguages = [];
        }

        $countries = null;
        $provinces = null;
        $cities = null;
        $districts = null;
        if ($step == 9) {
            $countries = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
                ->where('type', Region::$country)
                ->get();

            if (!empty($user->country_id)) {
                $provinces = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
                    ->where('type', Region::$province)
                    ->where('country_id', $user->country_id)
                    ->get();
            }

            if (!empty($user->province_id)) {
                $cities = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
                    ->where('type', Region::$city)
                    ->where('province_id', $user->province_id)
                    ->get();
            }

            if (!empty($user->city_id)) {
                $districts = Region::select(DB::raw('*, ST_AsText(geo_center) as geo_center'))
                    ->where('type', Region::$district)
                    ->where('city_id', $user->city_id)
                    ->get();
            }
        }

        $data = [
            'pageTitle' => trans('panel.settings'),
            'user' => $user,
            'categories' => $categories,
            'educations' => $userMetas->where('name', 'education'),
            'experiences' => $userMetas->where('name', 'experience'),
            'occupations' => $occupations,
            'userLanguages' => $userLanguages,
            'currentStep' => $step,
            'countries' => $countries,
            'provinces' => $provinces,
            'cities' => $cities,
            'districts' => $districts,
        ];

        return view(getTemplate() . '.panel.setting.index', $data);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        $organization = null;
        if (!empty($data['organization_id']) and !empty($data['user_id'])) {
            $organization = auth()->user();
            $user = User::where('id', $data['user_id'])
                ->where('organ_id', $organization->id)
                ->first();
        } else {
            $user = auth()->user();
        }

        $step = $data['step'] ?? 1;
        $nextStep = (!empty($data['next_step']) and $data['next_step'] == '1') ?? false;

        $rules = [
            'iban' => 'required_with:account_type',
            'account_id' => 'required_with:account_type',
            'identity_scan' => 'required_with:account_type',
            'bio' => 'nullable|string|min:3|max:48',
        ];

        if ($step == 1) {
            $rules = array_merge($rules, [
                'full_name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'mobile' => 'required|numeric|unique:users,mobile,' . $user->id,
            ]);
        }

        $this->validate($request, $rules);

        if (!empty($user)) {
            if (!empty($data['password'])) {
                $this->validate($request, [
                    'password' => 'required|confirmed|min:6',
                ]);

                $user->update([
                    'password' => User::generatePassword($data['password'])
                ]);
            }

            $updateData = [];

            if ($step == 1) {
                $joinNewsletter = (!empty($data['join_newsletter']) and $data['join_newsletter'] == 'on');

                $updateData = [
                    'email' => $data['email'],
                    'full_name' => $data['full_name'],
                    'mobile' => $data['mobile'],
                    'language' => $data['language'],
                    'timezone' => $data['timezone'] ?? null,
                    'newsletter' => $joinNewsletter,
                    'public_message' => (!empty($data['public_messages']) and $data['public_messages'] == 'on'),
                ];

                $this->handleNewsletter($data['email'], $user->id, $joinNewsletter);
            } elseif ($step == 2) {

                    $updateData = [
                        'cover_img' => $data['cover_img'] ?? '',
                    ];

                if (!empty($data['profile_image'])) {
                    $profileImage = $this->createImage($user, $data['profile_image']);
                    $updateData['avatar'] = $profileImage;
                }
                if ($request->file('video_demo')) {
                    $video = $request->file('video_demo');
                    $folderPath = "/" . $user->id.'/demo_videos';

                    $stored_path = $video->storeAs($folderPath, $user->id.'.mp4', ['disk' =>'public']);

                    $thumbnail = VideoThumbnail::createThumbnail(
                        public_path('/store/'.$stored_path),
                        public_path('/store'.$folderPath),
                        $user->id.'_video_demo_thumb.jpg',
                        1,
                        1920,
                        1080
                    );
                    $updateData['video_demo'] = 'store/'.$stored_path;
                    $updateData['video_demo_thumb'] = $folderPath.'/'.$user->id.'_video_demo_thumb.jpg';
                }
            } elseif ($step == 3) {
                $updateData = [
                    'about' => $data['about'],
                    'bio' => $data['bio'],
                ];
            } elseif ($step == 6) {
                UserOccupation::where('user_id', $user->id)->delete();
                if (!empty($data['languages'])) {
                    foreach ($data['languages'] as $category_id) {
                        $splt = explode('.', $category_id);
                        if ($splt[1] == 'also_speaking') {
                            continue;
                        }
                        UserOccupation::create([
                            'user_id' => $user->id,
                            'type' => 'language',
                            'category_id' => $splt[0]
                        ]);
                    }
                }
                if (!empty($data['also_speaking'])) {
                    foreach ($data['also_speaking'] as $category_id) {
                        $splt = explode('.', $category_id);
                        if ($splt[1] == 'language') {
                            continue;
                        }
                        UserOccupation::create([
                            'user_id' => $user->id,
                            'type' => 'also_speaking',
                            'category_id' => $splt[0]
                        ]);
                    }
                }
            } elseif ($step == 7) {
                $updateData = [
                    'account_type' => $data['account_type'] ?? '',
                    'iban' => $data['iban'] ?? '',
                    'account_id' => $data['account_id'] ?? '',
                    'identity_scan' => $data['identity_scan'] ?? '',
                    'certificate' => $data['certificate'] ?? '',
                    'address' => $data['address'] ?? '',
                ];
            } elseif ($step == 8) {
                if (!$user->isUser()) {
                    if (!empty($data['zoom_jwt_token'])) {
                        UserZoomApi::updateOrCreate(
                            [
                                'user_id' => $user->id,
                            ],
                            [
                                'jwt_token' => $data['zoom_jwt_token'],
                                'created_at' => time()
                            ]
                        );
                    } else {
                        UserZoomApi::where('user_id', $user->id)->delete();
                    }
                }
            } elseif ($step == 9) {
                $updateData = [
                    "level_of_training" => !empty($data['level_of_training']) ? (new UserLevelOfTraining())->getValue($data['level_of_training']) : null,
                    "meeting_type" => $data['meeting_type'] ?? null,
                    "group_meeting" => (!empty($data['group_meeting']) and $data['group_meeting'] == 'on'),
                    "country_id" => $data['country_id'] ?? null,
                    "province_id" => $data['province_id'] ?? null,
                    "city_id" => $data['city_id'] ?? null,
                    "district_id" => $data['district_id'] ?? null,
                    "location" => (!empty($data['latitude']) and !empty($data['longitude'])) ? DB::raw("POINT(" . $data['latitude'] . "," . $data['longitude'] . ")") : null,
                ];

                $updateUserMeta = [
                    "gender" => $data['gender'] ?? null,
                    "age" => $data['age'] ?? null,
                    "address" => $data['address'] ?? null,
                ];

                foreach ($updateUserMeta as $name => $value) {
                    $checkMeta = UserMeta::where('user_id', $user->id)
                        ->where('name', $name)
                        ->first();

                    if (!empty($checkMeta)) {
                        if (!empty($value)) {
                            $checkMeta->update([
                                'value' => $value
                            ]);
                        } else {
                            $checkMeta->delete();
                        }
                    } elseif (!empty($value)) {
                        UserMeta::create([
                            'user_id' => $user->id,
                            'name' => $name,
                            'value' => $value
                        ]);
                    }
                }
            }

            if (!empty($updateData)) {
                if (isset($data['goals']) && $data['goals'] != '') {
                    $goals = $data['goals'];
                    foreach ($goals as $goal) {
                        UserGoal::where('user_id', $user->id)->delete();
                    }
                    foreach ($goals as $goal) {
                        $user_goal = UserGoal::create([
                          'user_id' => $user->id,
                          'goal_name' => $goal
                        ]);
                    }
                }

                $user->update($updateData);
            }

            $url = '/panel/setting';
            if (!empty($organization)) {
                $url = '/panel/manage/instructors/' . $user->id . '/edit';
            }

            if ($step <= 9) {
                if ($nextStep) {
                    $step = $step + 1;
                }

                $url .= '/step/' . (($step <= 8) ? $step : 9);
            }

            $toastData = [
                'title' => trans('public.request_success'),
                'msg' => trans('panel.user_setting_success'),
                'status' => 'success'
            ];
            return redirect($url)->with(['toast' => $toastData]);
        }
        abort(404);
    }

    private function handleNewsletter($email, $user_id, $joinNewsletter)
    {
        $check = Newsletter::where('email', $email)->first();

        if ($joinNewsletter) {
            if (empty($check)) {
                Newsletter::create([
                    'user_id' => $user_id,
                    'email' => $email,
                    'created_at' => time()
                ]);
            } else {
                $check->update([
                    'user_id' => $user_id,
                ]);
            }

            $newsletterReward = RewardAccounting::calculateScore(Reward::NEWSLETTERS);
            RewardAccounting::makeRewardAccounting($user_id, $newsletterReward, Reward::NEWSLETTERS, $user_id, true);
        } elseif (!empty($check)) {
            $reward = RewardAccounting::where('user_id', $user_id)
                ->where('item_id', $user_id)
                ->where('type', Reward::NEWSLETTERS)
                ->where('status', RewardAccounting::ADDICTION)
                ->first();

            if (!empty($reward)) {
                $reward->delete();
            }

            $check->delete();
        }
    }

    public function createImage($user, $img)
    {
        $folderPath = "/" . $user->id . '/avatar/';

        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = uniqid() . '.' . $image_type;

        Storage::disk('public')->put($folderPath . $file, $image_base64);

        return Storage::disk('public')->url($folderPath . $file);
    }

    public function createVideo($user, $video_file)
    {
        $folderPath = "/" . $user->id.'/';
        $video_type = $video_file->getClientOriginalExtension();
        $file = uniqid() . '.' . $video_type;
        $fileName = $folderPath . $file;

        $video_file->store($fileName, ['disk' =>'public']);


        return Storage::disk('public')->url($folderPath . $file);
    }

    public function storeMetas(Request $request)
    {
        $data = $request->all();

        if (!empty($data['name']) and !empty($data['value'])) {
            if (!empty($data['user_id'])) {
                $organization = auth()->user();
                $user = User::where('id', $data['user_id'])
                    ->where('organ_id', $organization->id)
                    ->first();
            } else {
                $user = auth()->user();
            }

            UserMeta::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'value' => $data['value'],
            ]);

            return response()->json([
                'code' => 200
            ], 200);
        }

        return response()->json([], 422);
    }

    public function updateMeta(Request $request, $meta_id)
    {
        $data = $request->all();
        $user = auth()->user();

        if (!empty($data['user_id'])) {
            $checkUser = User::find($data['user_id']);

            if ((!empty($checkUser) and ($data['user_id'] == $user->id) or $checkUser->organ_id == $user->id)) {
                $meta = UserMeta::where('id', $meta_id)
                    ->where('user_id', $data['user_id'])
                    ->where('name', $data['name'])
                    ->first();

                if (!empty($meta)) {
                    $meta->update([
                        'value' => $data['value']
                    ]);

                    return response()->json([
                        'code' => 200
                    ], 200);
                }

                return response()->json([
                    'code' => 403
                ], 200);
            }
        }

        return response()->json([], 422);
    }

    public function deleteMeta(Request $request, $meta_id)
    {
        $data = $request->all();
        $user = auth()->user();

        if (!empty($data['user_id'])) {
            $checkUser = User::find($data['user_id']);

            if (!empty($checkUser) and ($data['user_id'] == $user->id or $checkUser->organ_id == $user->id)) {
                $meta = UserMeta::where('id', $meta_id)
                    ->where('user_id', $data['user_id'])
                    ->first();

                $meta->delete();

                return response()->json([
                    'code' => 200
                ], 200);
            }
        }

        return response()->json([], 422);
    }

    public function manageUsers(Request $request, $user_type)
    {
        $valid_type = ['instructors', 'students'];
        $organization = auth()->user();

        if ($organization->isOrganization() and in_array($user_type, $valid_type)) {
            if ($user_type == 'instructors') {
                $query = $organization->getOrganizationTeachers();
            } else {
                $query = $organization->getOrganizationStudents();
            }

            $activeCount = deepClone($query)->where('status', 'active')->count();
            $verifiedCount = deepClone($query)->where('verified', true)->count();
            $inActiveCount = deepClone($query)->where('status', 'inactive')->count();

            $from = $request->get('from', null);
            $to = $request->get('to', null);
            $name = $request->get('name', null);
            $email = $request->get('email', null);
            $type = request()->get('type', null);

            if (!empty($from) and !empty($to)) {
                $from = strtotime($from);
                $to = strtotime($to);

                $query->whereBetween('created_at', [$from, $to]);
            } else {
                if (!empty($from)) {
                    $from = strtotime($from);

                    $query->where('created_at', '>=', $from);
                }

                if (!empty($to)) {
                    $to = strtotime($to);

                    $query->where('created_at', '<', $to);
                }
            }

            if (!empty($name)) {
                $query->where('full_name', 'like', "%$name%");
            }

            if (!empty($email)) {
                $query->where('email', $email);
            }

            if (!empty($type)) {
                if (in_array($type, ['active', 'inactive'])) {
                    $query->where('status', $type);
                } elseif ($type == 'verified') {
                    $query->where('verified', true);
                }
            }

            $users = $query->orderBy('created_at', 'desc')
                ->paginate(10);

            $data = [
                'pageTitle' => trans('public.' . $user_type),
                'user_type' => $user_type,
                'organization' => $organization,
                'users' => $users,
                'activeCount' => $activeCount,
                'inActiveCount' => $inActiveCount,
                'verifiedCount' => $verifiedCount,
            ];

            return view(getTemplate() . '.panel.manage.' . $user_type, $data);
        }

        abort(404);
    }

    public function createUser($user_type)
    {
        $valid_type = ['instructors', 'students'];
        $organization = auth()->user();

        if ($organization->isOrganization() and in_array($user_type, $valid_type)) {
            $packageType = $user_type == 'instructors' ? 'instructors_count' : 'students_count';
            $userPackage = new UserPackage();
            $userAccountLimited = $userPackage->checkPackageLimit($packageType);

            if ($userAccountLimited) {
                session()->put('registration_package_limited', $userAccountLimited);

                return redirect()->back();
            }

            $categories = Category::where('parent_id', null)
                ->with('subCategories')
                ->get();

            $userLanguages = getGeneralSettings('user_languages');
            if (!empty($userLanguages) and is_array($userLanguages)) {
                $userLanguages = getLanguages($userLanguages);
            }

            $data = [
                'pageTitle' => trans('public.new') . ' ' . trans('quiz.' . $user_type),
                'new_user' => true,
                'user_type' => $user_type,
                'user' => $organization,
                'categories' => $categories,
                'organization_id' => $organization->id,
                'userLanguages' => $userLanguages,
                'currentStep' => 1,
            ];

            return view(getTemplate() . '.panel.setting.index', $data);
        }

        abort(404);
    }

    public function storeUser(Request $request, $user_type)
    {
        $valid_type = ['instructors', 'students'];
        $organization = auth()->user();

        if ($organization->isOrganization() and in_array($user_type, $valid_type)) {
            $this->validate($request, [
                'email' => 'required|string|email|max:255|unique:users',
                'full_name' => 'required|string',
                'mobile' => 'required|numeric|unique:users',
                'password' => 'required|confirmed|min:6',
            ]);

            $data = $request->all();
            $role_name = ($user_type == 'instructors') ? Role::$teacher : Role::$user;
            $role_id = ($user_type == 'instructors') ? Role::getTeacherRoleId() : Role::getUserRoleId();

            $referralSettings = getReferralSettings();
            $usersAffiliateStatus = (!empty($referralSettings) and !empty($referralSettings['users_affiliate_status']));

            $user = User::create([
                'role_name' => $role_name,
                'role_id' => $role_id,
                'email' => $data['email'],
                'organ_id' => $organization->id,
                'password' => Hash::make($data['password']),
                'full_name' => $data['full_name'],
                'mobile' => $data['mobile'],
                'language' => $data['language'],
                'affiliate' => $usersAffiliateStatus,
                'newsletter' => (!empty($data['join_newsletter']) and $data['join_newsletter'] == 'on'),
                'public_message' => (!empty($data['public_messages']) and $data['public_messages'] == 'on'),
                'created_at' => time()
            ]);

            return redirect('/panel/manage/' . $user_type . '/' . $user->id . '/edit');
        }

        abort(404);
    }

    public function editUser($user_type, $user_id, $step = 1)
    {
        $valid_type = ['instructors', 'students'];
        $organization = auth()->user();

        if ($organization->isOrganization() and in_array($user_type, $valid_type)) {
            $user = User::where('id', $user_id)
                ->where('organ_id', $organization->id)
                ->first();

            if (!empty($user)) {
                $categories = Category::where('parent_id', null)
                    ->with('subCategories')
                    ->get();
                $userMetas = $user->userMetas;

                $occupations = $user->occupations->pluck('category_id')->toArray();

                $userLanguages = getGeneralSettings('user_languages');
                if (!empty($userLanguages) and is_array($userLanguages)) {
                    $userLanguages = getLanguages($userLanguages);
                }

                $data = [
                    'organization_id' => $organization->id,
                    'edit_new_user' => true,
                    'user' => $user,
                    'user_type' => $user_type,
                    'categories' => $categories,
                    'educations' => $userMetas->where('name', 'education'),
                    'experiences' => $userMetas->where('name', 'experience'),
                    'pageTitle' => trans('panel.settings'),
                    'occupations' => $occupations,
                    'userLanguages' => $userLanguages,
                    'currentStep' => $step,
                ];

                return view(getTemplate() . '.panel.setting.index', $data);
            }
        }

        abort(404);
    }

    public function deleteUser($user_type, $user_id)
    {
        $valid_type = ['instructors', 'students'];
        $organization = auth()->user();

        if ($organization->isOrganization() and in_array($user_type, $valid_type)) {
            $user = User::where('id', $user_id)
                ->where('organ_id', $organization->id)
                ->first();

            if (!empty($user)) {
                $user->delete();

                return response()->json([
                    'code' => 200
                ]);
            }
        }

        return response()->json([], 422);
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        $option = $request->get('option', null);
        $user = auth()->user();

        if (!empty($term)) {
            $query = User::select('id', 'full_name')
                ->where(function ($query) use ($term) {
                    $query->where('full_name', 'like', '%' . $term . '%');
                    $query->orWhere('email', 'like', '%' . $term . '%');
                    $query->orWhere('mobile', 'like', '%' . $term . '%');
                })
                ->where('id', '<>', $user->id)
                ->whereNotIn('role_name', ['admin']);

            if (!empty($option) and $option == 'just_teachers') {
                $query->where('role_name', 'teacher');
            }

            if ($option == "just_student_role") {
                $query->where('role_name', Role::$user);
            }

            $users = $query->get();

            return response()->json($users, 200);
        }

        return response('', 422);
    }

    public function contactInfo(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'user_type' => 'required|in:student,instructor',
        ]);

        $user = User::find($request->get('user_id'));

        if (!empty($user)) {
            $itemId = $request->get('item_id');
            $userType = $request->get('user_type');
            $description = null;
            $location = null;

            if (!empty($itemId)) {
                $reserve = ReserveMeeting::where('id', $itemId)
                    ->where(function ($query) use ($user) {
                        $query->where('user_id', $user->id);

                        if (!empty($user->meeting)) {
                            $query->orWhere('meeting_id', $user->meeting->id);
                        }
                    })->first();

                if (!empty($reserve)) {
                    if ($userType == 'student') {
                        $description = $reserve->description;
                    } elseif (!empty($reserve->meetingTime)) {
                        $description = $reserve->meetingTime->description;
                    }

                    if ($reserve->meeting_type == 'in_person') {
                        $userMetas = $user->userMetas;

                        if (!empty($userMetas)) {
                            foreach ($userMetas as $meta) {
                                if ($meta->name == 'address') {
                                    $location = $meta->value;
                                }
                            }
                        }
                    }
                }
            }

            return response()->json([
                'code' => 200,
                'avatar' => $user->getAvatar(),
                'name' => $user->full_name,
                'email' => !empty($user->email) ? $user->email : '-',
                'phone' => !empty($user->mobile) ? $user->mobile : '-',
                'description' => $description,
                'location' => $location,
            ], 200);
        }

        return response()->json([], 422);
    }

    public function offlineToggle(Request $request)
    {
        $user = auth()->user();

        $message = $request->get('message');
        $toggle = $request->get('toggle');
        $toggle = (!empty($toggle) and $toggle == 'true');

        $user->offline = $toggle;
        $user->offline_message = $message;

        $user->save();

        return response()->json([
            'code' => 200
        ], 200);
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user();

        if (!empty($user)) {
            DeleteAccountRequest::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'created_at' => time()
            ]);

            return response()->json([
                'code' => 200,
                'title' => trans('public.request_success'),
                'text' => trans('update.delete_account_request_stored_msg'),
                'dont_reload' => true
            ]);
        }

        abort(403);
    }
}
