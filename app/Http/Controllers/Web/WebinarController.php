<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AdvertisingBanner;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\File;
use App\Models\QuizzesResult;
use App\Models\RewardAccounting;
use App\Models\Sale;
use App\Models\TextLesson;
use App\Models\CourseLearning;
use App\Models\WebinarChapter;
use App\Models\WebinarReport;
use App\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebinarController extends Controller
{
    public function course($slug, $justReturnData = false)
    {
        $user = null;

        if (auth()->check()) {
            $user = auth()->user();
        } else if (getFeaturesSettings('webinar_private_content_status')) {
            $data = [
                'pageTitle' => trans('update.private_content'),
                'pageRobot' => getPageRobotNoIndex(),
            ];

            return view('web.default.course.private_content', $data);
        }

        if (!empty($user) and !$user->access_content) {
            $data = [
                'pageTitle' => trans('update.not_access_to_content'),
                'pageRobot' => getPageRobotNoIndex(),
                'userNotAccess' => true
            ];

            return view('web.default.course.private_content', $data);
        }

        $course = Webinar::where('slug', $slug)
            ->with([
                'quizzes' => function ($query) {
                    $query->where('status', 'active')
                        ->with(['quizResults', 'quizQuestions']);
                },
                'tags',
                'prerequisites' => function ($query) {
                    $query->with(['prerequisiteWebinar' => function ($query) {
                        $query->with(['teacher' => function ($qu) {
                            $qu->select('id', 'full_name', 'avatar');
                        }]);
                    }]);
                    $query->orderBy('order', 'asc');
                },
                'faqs' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'webinarExtraDescription' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'chapters' => function ($query) use ($user) {
                    $query->where('status', WebinarChapter::$chapterActive);
                    $query->orderBy('order', 'asc');

                    $query->with([
                        'chapterItems' => function ($query) {
                            $query->orderBy('order', 'asc');
                        }
                    ]);
                },
                'files' => function ($query) use ($user) {
                    $query->join('webinar_chapters', 'webinar_chapters.id', '=', 'files.chapter_id')
                        ->select('files.*', DB::raw('webinar_chapters.order as chapterOrder'))
                        ->where('files.status', WebinarChapter::$chapterActive)
                        ->orderBy('chapterOrder', 'asc')
                        ->orderBy('files.order', 'asc')
                        ->with([
                            'learningStatus' => function ($query) use ($user) {
                                $query->where('user_id', !empty($user) ? $user->id : null);
                            }
                        ]);
                },
                'textLessons' => function ($query) use ($user) {
                    $query->where('status', WebinarChapter::$chapterActive)
                        ->withCount(['attachments'])
                        ->orderBy('order', 'asc')
                        ->with([
                            'learningStatus' => function ($query) use ($user) {
                                $query->where('user_id', !empty($user) ? $user->id : null);
                            }
                        ]);
                },
                'sessions' => function ($query) use ($user) {
                    $query->where('status', WebinarChapter::$chapterActive)
                        ->orderBy('order', 'asc')
                        ->with([
                            'learningStatus' => function ($query) use ($user) {
                                $query->where('user_id', !empty($user) ? $user->id : null);
                            }
                        ]);
                },
                'assignments' => function ($query) {
                    $query->where('status', WebinarChapter::$chapterActive);
                },
                'tickets' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'filterOptions',
                'category',
                'teacher',
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                    $query->with([
                        'comments' => function ($query) {
                            $query->where('status', 'active');
                        },
                        'creator' => function ($qu) {
                            $qu->select('id', 'full_name', 'avatar');
                        }
                    ]);
                },
                'comments' => function ($query) {
                    $query->where('status', 'active');
                    $query->whereNull('reply_id');
                    $query->with([
                        'user' => function ($query) {
                            $query->select('id', 'full_name', 'role_name', 'role_id', 'avatar');
                        },
                        'replies' => function ($query) {
                            $query->where('status', 'active');
                            $query->with([
                                'user' => function ($query) {
                                    $query->select('id', 'full_name', 'role_name', 'role_id', 'avatar');
                                }
                            ]);
                        }
                    ]);
                    $query->orderBy('created_at', 'desc');
                },
            ])
            ->withCount([
                'sales' => function ($query) {
                    $query->whereNull('refund_at');
                },
                'noticeboards'
            ])
            ->where('status', 'active')
            ->first();

        if (empty($course)) {
            return $justReturnData ? false : back();
        }

        $isPrivate = $course->private;
        if (!empty($user) and ($user->id == $course->creator_id or $user->organ_id == $course->creator_id or $user->isAdmin())) {
            $isPrivate = false;
        }

        if ($isPrivate) {
            return $justReturnData ? false : back();
        }

        $isFavorite = false;

        if (!empty($user)) {
            $isFavorite = Favorite::where('webinar_id', $course->id)
                ->where('user_id', $user->id)
                ->first();
        }

        $hasBought = $course->checkUserHasBought($user);

        $webinarContentCount = 0;
        if (!empty($course->sessions)) {
            $webinarContentCount += $course->sessions->count();
        }
        if (!empty($course->files)) {
            $webinarContentCount += $course->files->count();
        }
        if (!empty($course->textLessons)) {
            $webinarContentCount += $course->textLessons->count();
        }
        if (!empty($course->quizzes)) {
            $webinarContentCount += $course->quizzes->count();
        }
        if (!empty($course->assignments)) {
            $webinarContentCount += $course->assignments->count();
        }

        $advertisingBanners = AdvertisingBanner::where('published', true)
            ->whereIn('position', ['course', 'course_sidebar'])
            ->get();

        $sessionsWithoutChapter = $course->sessions->whereNull('chapter_id');

        $filesWithoutChapter = $course->files->whereNull('chapter_id');

        $textLessonsWithoutChapter = $course->textLessons->whereNull('chapter_id');

        $quizzes = $course->quizzes->whereNull('chapter_id');

        if ($user) {
            $quizzes = $this->checkQuizzesResults($user, $quizzes);

            if (!empty($course->chapters) and count($course->chapters)) {
                foreach ($course->chapters as $chapter) {
                    if (!empty($chapter->chapterItems) and count($chapter->chapterItems)) {
                        foreach ($chapter->chapterItems as $chapterItem) {
                            if (!empty($chapterItem->quiz)) {
                                $chapterItem->quiz = $this->checkQuizResults($user, $chapterItem->quiz);
                            }
                        }
                    }
                }
            }

            if (!empty($course->quizzes) and count($course->quizzes)) {
                $course->quizzes = $this->checkQuizzesResults($user, $course->quizzes);
            }
        }

        $pageRobot = getPageRobot('course_show'); // index

        $data = [
            'pageTitle' => $course->title,
            'pageDescription' => $course->seo_description,
            'pageRobot' => $pageRobot,
            'course' => $course,
            'isFavorite' => $isFavorite,
            'hasBought' => $hasBought,
            'user' => $user,
            'webinarContentCount' => $webinarContentCount,
            'advertisingBanners' => $advertisingBanners->where('position', 'course'),
            'advertisingBannersSidebar' => $advertisingBanners->where('position', 'course_sidebar'),
            'activeSpecialOffer' => $course->activeSpecialOffer(),
            'sessionsWithoutChapter' => $sessionsWithoutChapter,
            'filesWithoutChapter' => $filesWithoutChapter,
            'textLessonsWithoutChapter' => $textLessonsWithoutChapter,
            'quizzes' => $quizzes,
        ];

        if ($justReturnData) {
            return $data;
        }

        return view('web.default.course.index', $data);
    }

    private function checkQuizzesResults($user, $quizzes)
    {
        $canDownloadCertificate = false;

        foreach ($quizzes as $quiz) {
            $quiz = $this->checkQuizResults($user, $quiz);
        }

        return $quizzes;
    }

    private function checkQuizResults($user, $quiz)
    {
        $canDownloadCertificate = false;

        $canTryAgainQuiz = false;
        $userQuizDone = QuizzesResult::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        if (count($userQuizDone)) {
            $quiz->user_grade = $userQuizDone->first()->user_grade;
            $quiz->result_count = $userQuizDone->count();
            $quiz->result = $userQuizDone->first();

            $status_pass = false;
            foreach ($userQuizDone as $result) {
                if ($result->status == QuizzesResult::$passed) {
                    $status_pass = true;
                }
            }

            $quiz->result_status = $status_pass ? QuizzesResult::$passed : $userQuizDone->first()->status;

            if ($quiz->certificate and $quiz->result_status == QuizzesResult::$passed) {
                $canDownloadCertificate = true;
            }
        }

        if (!isset($quiz->attempt) or (count($userQuizDone) < $quiz->attempt and $quiz->result_status !== QuizzesResult::$passed)) {
            $canTryAgainQuiz = true;
        }

        $quiz->can_try = $canTryAgainQuiz;
        $quiz->can_download_certificate = $canDownloadCertificate;

        return $quiz;
    }

    private function checkCanAccessToPrivateCourse($course, $user = null): bool
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        $canAccess = !$course->private;

        if (!empty($user) and ($user->id == $course->creator_id or $user->organ_id == $course->creator_id or $user->isAdmin())) {
            $canAccess = true;
        }

        return $canAccess;
    }

    public function downloadFile($slug, $file_id)
    {
        $webinar = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($webinar) and $this->checkCanAccessToPrivateCourse($webinar)) {
            $file = File::where('webinar_id', $webinar->id)
                ->where('id', $file_id)
                ->first();

            if (!empty($file) and $file->downloadable) {
                $canAccess = true;

                if ($file->accessibility == 'paid') {
                    $canAccess = $webinar->checkUserHasBought();
                }

                if ($canAccess) {
                    if (in_array($file->storage, ['s3', 'external_link'])) {
                        return redirect($file->file);
                    }

                    $filePath = public_path($file->file);

                    if (file_exists($filePath)) {
                        $fileName = str_replace(' ', '-', $file->title);
                        $fileName = str_replace('.', '-', $fileName);
                        $fileName .= '.' . $file->file_type;

                        $headers = array(
                            'Content-Type: application/' . $file->file_type,
                        );

                        return response()->download($filePath, $fileName, $headers);
                    }
                } else {
                    $toastData = [
                        'title' => trans('public.not_access_toast_lang'),
                        'msg' => trans('public.not_access_toast_msg_lang'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }
            }
        }

        return back();
    }

    public function showHtmlFile($slug, $file_id)
    {
        $webinar = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($webinar) and $this->checkCanAccessToPrivateCourse($webinar)) {
            $file = File::where('webinar_id', $webinar->id)
                ->where('id', $file_id)
                ->first();

            if (!empty($file)) {
                $canAccess = true;

                if ($file->accessibility == 'paid') {
                    $canAccess = $webinar->checkUserHasBought();
                }

                if ($canAccess) {
                    $filePath = $file->interactive_file_path;

                    if (\Illuminate\Support\Facades\File::exists(public_path($filePath))) {
                        $data = [
                            'pageTitle' => $file->title,
                            'path' => url($filePath)
                        ];
                        return view('web.default.course.learningPage.interactive_file', $data);
                    }

                    abort(404);
                } else {
                    $toastData = [
                        'title' => trans('public.not_access_toast_lang'),
                        'msg' => trans('public.not_access_toast_msg_lang'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }
            }
        }

        abort(403);
    }

    public function getFilePath(Request $request)
    {
        $this->validate($request, [
            'file_id' => 'required'
        ]);

        $file_id = $request->get('file_id');

        $file = File::where('id', $file_id)
            ->first();

        if (!empty($file)) {
            $webinar = Webinar::where('id', $file->webinar_id)
                ->where('status', 'active')
                ->with([
                    'files' => function ($query) {
                        $query->select('id', 'webinar_id', 'file_type')
                            ->where('status', 'active')
                            ->orderBy('order', 'asc');
                    }
                ])
                ->first();

            if (!empty($webinar)) {
                $canAccess = true;

                if ($file->accessibility == 'paid') {
                    $canAccess = $webinar->checkUserHasBought();
                }

                if ($canAccess) {
                    $path = $file->file;

                    if ($file->storage == 'upload') {
                        $path = url("/course/$webinar->slug/file/$file->id/play");
                    } elseif ($file->storage == 'upload_archive') {
                        $path = url("/course/$webinar->slug/file/$file->id/showHtml");
                    }

                    return response()->json([
                        'code' => 200,
                        'storage' => $file->storage,
                        'path' => $path,
                        'storageService' => $file->storage
                    ], 200);
                }
            }
        }

        abort(403);
    }

    public function playFile($slug, $file_id)
    {
        // this methode linked from video modal for play local video
        // and linked from file.blade for show google_drive,dropbox,iframe

        $webinar = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($webinar) and $this->checkCanAccessToPrivateCourse($webinar)) {
            $file = File::where('webinar_id', $webinar->id)
                ->where('id', $file_id)
                ->first();

            if (!empty($file)) {
                $canAccess = true;

                if ($file->accessibility == 'paid') {
                    $canAccess = $webinar->checkUserHasBought();
                }

                if ($canAccess) {
                    $notVideoSource = ['iframe', 'google_drive', 'dropbox'];

                    if (in_array($file->storage, $notVideoSource)) {
                        $data = [
                            'pageTitle' => $file->title,
                            'iframe' => $file->file
                        ];

                        return view('web.default.course.learningPage.interactive_file', $data);
                    } else if ($file->isVideo()) {
                        return response()->file(public_path($file->file));
                    }
                }
            }
        }

        abort(403);
    }

    public function getLesson(Request $request, $slug, $lesson_id)
    {
        $user = null;

        if (auth()->check()) {
            $user = auth()->user();
        }

        $course = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->with(['teacher', 'textLessons' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->first();

        if (!empty($course) and $this->checkCanAccessToPrivateCourse($course)) {
            $textLesson = TextLesson::where('id', $lesson_id)
                ->where('webinar_id', $course->id)
                ->where('status', WebinarChapter::$chapterActive)
                ->with([
                    'attachments' => function ($query) {
                        $query->with('file');
                    },
                    'learningStatus' => function ($query) use ($user) {
                        $query->where('user_id', !empty($user) ? $user->id : null);
                    }
                ])
                ->first();

            if (!empty($textLesson)) {
                $canAccess = $course->checkUserHasBought();

                if ($textLesson->accessibility == 'paid' and !$canAccess) {
                    $toastData = [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('cart.you_not_purchased_this_course'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $checkSequenceContent = $textLesson->checkSequenceContent();
                $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

                if (!empty($checkSequenceContent) and $sequenceContentHasError) {
                    $toastData = [
                        'title' => trans('public.request_failed'),
                        'msg' => ($checkSequenceContent['all_passed_items_error'] ? $checkSequenceContent['all_passed_items_error'] . ' - ' : '') . ($checkSequenceContent['access_after_day_error'] ?? ''),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $nextLesson = null;
                $previousLesson = null;
                if (!empty($course->textLessons) and count($course->textLessons)) {
                    $nextLesson = $course->textLessons->where('order', '>', $textLesson->order)->first();
                    $previousLesson = $course->textLessons->where('order', '<', $textLesson->order)->first();
                }

                if (!empty($nextLesson)) {
                    $nextLesson->not_purchased = ($nextLesson->accessibility == 'paid' and !$canAccess);
                }


                $data = [
                    'pageTitle' => $textLesson->title,
                    'textLesson' => $textLesson,
                    'course' => $course,
                    'nextLesson' => $nextLesson,
                    'previousLesson' => $previousLesson,
                ];

                return view(getTemplate() . '.course.text_lesson', $data);
            }
        }

        abort(404);
    }

    public function free(Request $request, $slug)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $course = Webinar::where('slug', $slug)
                ->where('status', 'active')
                ->first();

            if (!empty($course)) {
                $checkCourseForSale = checkCourseForSale($course, $user);

                if ($checkCourseForSale != 'ok') {
                    return $checkCourseForSale;
                }

                if (!empty($course->price) and $course->price > 0) {
                    $toastData = [
                        'title' => trans('cart.fail_purchase'),
                        'msg' => trans('cart.course_not_free'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                Sale::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $course->creator_id,
                    'webinar_id' => $course->id,
                    'type' => Sale::$webinar,
                    'payment_method' => Sale::$credit,
                    'amount' => 0,
                    'total_amount' => 0,
                    'created_at' => time(),
                ]);

                $toastData = [
                    'title' => '',
                    'msg' => trans('cart.success_pay_msg_for_free_course'),
                    'status' => 'success'
                ];
                return back()->with(['toast' => $toastData]);
            }

            abort(404);
        } else {
            return redirect('/login');
        }
    }

    public function reportWebinar(Request $request, $id)
    {
        if (auth()->check()) {
            $user_id = auth()->id();

            $this->validate($request, [
                'reason' => 'required|string',
                'message' => 'required|string',
            ]);

            $data = $request->all();

            $webinar = Webinar::select('id', 'status')
                ->where('id', $id)
                ->where('status', 'active')
                ->first();

            if (!empty($webinar)) {
                WebinarReport::create([
                    'user_id' => $user_id,
                    'webinar_id' => $webinar->id,
                    'reason' => $data['reason'],
                    'message' => $data['message'],
                    'created_at' => time()
                ]);

                return response()->json([
                    'code' => 200
                ], 200);
            }
        }

        return response()->json([
            'code' => 401
        ], 200);
    }

    public function learningStatus(Request $request, $id)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $course = Webinar::where('id', $id)->first();

            if (!empty($course) and $course->checkUserHasBought($user)) {
                $data = $request->all();

                $item = $data['item'];
                $item_id = $data['item_id'];
                $status = $data['status'];

                CourseLearning::where('user_id', $user->id)
                    ->where($item, $item_id)
                    ->delete();

                if ($status and $status == "true") {
                    CourseLearning::create([
                        'user_id' => $user->id,
                        $item => $item_id,
                        'created_at' => time()
                    ]);
                }

                return response()->json([], 200);
            }
        }

        abort(403);
    }

    public function buyWithPoint($slug)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $course = Webinar::where('slug', $slug)
                ->where('status', 'active')
                ->first();

            if (!empty($course)) {
                if (empty($course->points)) {
                    $toastData = [
                        'title' => '',
                        'msg' => trans('update.can_not_buy_this_course_with_point'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $availablePoints = $user->getRewardPoints();

                if ($availablePoints < $course->points) {
                    $toastData = [
                        'title' => '',
                        'msg' => trans('update.you_have_no_enough_points_for_this_course'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $checkCourseForSale = checkCourseForSale($course, $user);

                if ($checkCourseForSale != 'ok') {
                    return $checkCourseForSale;
                }

                Sale::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $course->creator_id,
                    'webinar_id' => $course->id,
                    'type' => Sale::$webinar,
                    'payment_method' => Sale::$credit,
                    'amount' => 0,
                    'total_amount' => 0,
                    'created_at' => time(),
                ]);

                RewardAccounting::makeRewardAccounting($user->id, $course->points, 'withdraw', null, false, RewardAccounting::DEDUCTION);

                $toastData = [
                    'title' => '',
                    'msg' => trans('update.success_pay_course_with_point_msg'),
                    'status' => 'success'
                ];
                return back()->with(['toast' => $toastData]);
            }

            abort(404);
        } else {
            return redirect('/login');
        }
    }

    public function directPayment(Request $request)
    {
        $user = auth()->user();

        if (!empty($user) and !empty(getFeaturesSettings('direct_classes_payment_button_status'))) {
            $this->validate($request, [
                'item_id' => 'required',
                'item_name' => 'nullable',
            ]);

            $data = $request->except('_token');

            $webinarId = $data['item_id'];
            $ticketId = $data['ticket_id'] ?? null;

            $webinar = Webinar::where('id', $webinarId)
                ->where('private', false)
                ->where('status', 'active')
                ->first();

            if (!empty($webinar)) {
                $checkCourseForSale = checkCourseForSale($webinar, $user);

                if ($checkCourseForSale != 'ok') {
                    return $checkCourseForSale;
                }

                $fakeCarts = collect();

                $fakeCart = new Cart();
                $fakeCart->creator_id = $user->id;
                $fakeCart->webinar_id = $webinarId;
                $fakeCart->ticket_id = $ticketId;
                $fakeCart->special_offer_id = null;
                $fakeCart->created_at = time();

                $fakeCarts->add($fakeCart);

                $cartController = new CartController();

                return $cartController->checkout(new Request(), $fakeCarts);
            }
        }

        abort(404);
    }
}
