<?php

namespace App\Http\Controllers\Admin;

use App\Exports\QuizResultsExport;
use App\Exports\QuizzesAdminExport;
use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizzesResult;
use App\Models\Translation\QuizTranslation;
use App\Models\Webinar;
use App\Models\WebinarChapter;
use App\Models\WebinarChapterItem;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin_quizzes_list');

        removeContentLocale();

        $query = Quiz::query();

        $totalQuizzes = deepClone($query)->count();
        $totalActiveQuizzes = deepClone($query)->where('status', 'active')->count();
        $totalStudents = QuizzesResult::groupBy('user_id')->count();
        $totalPassedStudents = QuizzesResult::where('status', 'passed')->groupBy('user_id')->count();

        $query = $this->filters($query, $request);

        $quizzes = $query->with([
            'webinar',
            'teacher',
            'quizQuestions',
            'quizResults',
        ])->paginate(10);

        $data = [
            'pageTitle' => trans('admin/pages/quiz.admin_quizzes_list'),
            'quizzes' => $quizzes,
            'totalQuizzes' => $totalQuizzes,
            'totalActiveQuizzes' => $totalActiveQuizzes,
            'totalStudents' => $totalStudents,
            'totalPassedStudents' => $totalPassedStudents,
        ];

        $teacher_ids = $request->get('teacher_ids');
        $webinar_ids = $request->get('webinar_ids');

        if (!empty($teacher_ids)) {
            $data['teachers'] = User::select('id', 'full_name')
                ->whereIn('id', $teacher_ids)->get();
        }

        if (!empty($webinar_ids)) {
            $data['webinars'] = Webinar::select('id')
                ->whereIn('id', $webinar_ids)->get();
        }

        return view('admin.quizzes.lists', $data);
    }

    private function filters($query, $request)
    {
        $from = $request->get('from', null);
        $to = $request->get('to', null);
        $title = $request->get('title', null);
        $sort = $request->get('sort', null);
        $teacher_ids = $request->get('teacher_ids', null);
        $webinar_ids = $request->get('webinar_ids', null);
        $status = $request->get('status', null);

        $query = fromAndToDateFilter($from, $to, $query, 'created_at');

        if (!empty($title)) {
            $query->whereTranslationLike('title', '%' . $title . '%');
        }

        if (!empty($sort)) {
            switch ($sort) {
                case 'have_certificate':
                    $query->where('certificate', true);
                    break;
                case 'students_count_asc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('result_count', 'asc');
                    break;

                case 'students_count_desc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('result_count', 'desc');
                    break;
                case 'passed_count_asc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
                        ->where('quizzes_results.status', 'passed')
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('result_count', 'asc');
                    break;

                case 'passed_count_desc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', DB::raw('count(quizzes_results.quiz_id) as result_count'))
                        ->where('quizzes_results.status', 'passed')
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('result_count', 'desc');
                    break;

                case 'grade_avg_asc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', 'quizzes_results.user_grade', DB::raw('avg(quizzes_results.user_grade) as grade_avg'))
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('grade_avg', 'asc');
                    break;

                case 'grade_avg_desc':
                    $query->join('quizzes_results', 'quizzes_results.quiz_id', '=', 'quizzes.id')
                        ->select('quizzes.*', 'quizzes_results.quiz_id', 'quizzes_results.user_grade', DB::raw('avg(quizzes_results.user_grade) as grade_avg'))
                        ->groupBy('quizzes_results.quiz_id')
                        ->orderBy('grade_avg', 'desc');
                    break;

                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;

                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if (!empty($teacher_ids)) {
            $query->whereIn('creator_id', $teacher_ids);
        }

        if (!empty($webinar_ids)) {
            $query->whereIn('webinar_id', $webinar_ids);
        }

        if (!empty($status) and $status !== 'all') {
            $query->where('status', strtolower($status));
        }

        return $query;
    }

    public function create()
    {
        $this->authorize('admin_quizzes_create');

        $data = [
            'pageTitle' => trans('quiz.new_quiz'),
        ];

        return view('admin.quizzes.create', $data);
    }

    public function store(Request $request)
    {
        $this->authorize('admin_quizzes_create');

        $data = $request->all();
        $locale = $request->get('locale', getDefaultLocale());

        $rules = [
            'title' => 'required|max:255',
            'webinar_id' => 'required|exists:webinars,id',
            'pass_mark' => 'required',
        ];

        if ($request->ajax()) {
            $data = $request->get('ajax');

            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json([
                    'code' => 422,
                    'errors' => $validate->errors()
                ], 422);
            }
        } else {
            $this->validate($request, $rules);
        }


        $webinar = Webinar::where('id', $data['webinar_id'])
            ->first();

        if (!empty($webinar)) {
            $chapter = null;

            if (!empty($data['chapter_id'])) {
                $chapter = WebinarChapter::where('id', $data['chapter_id'])
                    ->where('webinar_id', $webinar->id)
                    ->first();
            }

            $quiz = Quiz::create([
                'webinar_id' => $webinar->id,
                'chapter_id' => !empty($chapter) ? $chapter->id : null,
                'creator_id' => $webinar->creator_id,
                'webinar_title' => $webinar->title,
                'attempt' => $data['attempt'] ?? null,
                'pass_mark' => $data['pass_mark'],
                'time' => $data['time'] ?? null,
                'status' => (!empty($data['status']) and $data['status'] == 'on') ? Quiz::ACTIVE : Quiz::INACTIVE,
                'certificate' => (!empty($data['certificate']) and $data['certificate'] == 'on'),
                'created_at' => time(),
            ]);

            QuizTranslation::updateOrCreate([
                'quiz_id' => $quiz->id,
                'locale' => mb_strtolower($locale),
            ], [
                'title' => $data['title'],
            ]);

            if (!empty($quiz->chapter_id)) {
                WebinarChapterItem::makeItem($webinar->creator_id, $quiz->chapter_id, $quiz->id, WebinarChapterItem::$chapterQuiz);
            }

            if ($request->ajax()) {

                $redirectUrl = '';

                if (empty($data['is_webinar_page'])) {
                    $redirectUrl = '/admin/quizzes/' . $quiz->id . '/edit';
                }

                return response()->json([
                    'code' => 200,
                    'redirect_url' => $redirectUrl
                ]);
            } else {
                return redirect()->route('adminEditQuiz', ['id' => $quiz->id]);
            }
        } else {
            return back()->withErrors([
                'webinar_id' => trans('validation.exists', ['attribute' => trans('admin/main.course')])
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $this->authorize('admin_quizzes_edit');

        $quiz = Quiz::findOrFail($id);

        $creator = $quiz->creator;

        $webinars = Webinar::where('status', 'active')
            ->where(function ($query) use ($creator) {
                $query->where('teacher_id', $creator->id)
                    ->orWhere('creator_id', $creator->id);
            })->get();

        $locale = $request->get('locale', app()->getLocale());
        if (empty($locale)) {
            $locale = app()->getLocale();
        }
        storeContentLocale($locale, $quiz->getTable(), $quiz->id);

        $quiz->title = $quiz->getTitleAttribute();
        $quiz->locale = mb_strtoupper($locale);

        $chapters = collect();

        if (!empty($quiz->webinar)) {
            $chapters = $quiz->webinar->chapters;
        }

        $data = [
            'pageTitle' => trans('public.edit') . ' ' . $quiz->title,
            'webinars' => $webinars,
            'quiz' => $quiz,
            'quizQuestions' => $quiz->quizQuestions,
            'creator' => $creator,
            'chapters' => $chapters,
            'locale' => mb_strtolower($locale),
            'defaultLocale' => getDefaultLocale(),
        ];

        return view('admin.quizzes.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required|max:255',
            'webinar_id' => 'required|exists:webinars,id',
            'pass_mark' => 'required',
        ];

        $data = $request->all();
        $locale = $request->get('locale', getDefaultLocale());

        if ($request->ajax()) {
            $data = $request->get('ajax');

            $validate = Validator::make($data, $rules);

            if ($validate->fails()) {
                return response()->json([
                    'code' => 422,
                    'errors' => $validate->errors()
                ], 422);
            }
        } else {
            $this->validate($request, $rules);
        }

        $quiz = Quiz::find($id);
        $user = $quiz->creator;

        $webinar = null;
        $chapter = null;
        if (!empty($data['webinar_id'])) {
            $webinar = Webinar::where('id', $data['webinar_id'])
                ->where(function ($query) use ($user) {
                    $query->where('teacher_id', $user->id)
                        ->orWhere('creator_id', $user->id);
                })->where('status', 'active')
                ->first();

            if (!empty($webinar) and !empty($data['chapter_id'])) {
                $chapter = WebinarChapter::where('id', $data['chapter_id'])
                    ->where('webinar_id', $webinar->id)
                    ->first();
            }
        }

        $quiz->update([
            'webinar_id' => !empty($webinar) ? $webinar->id : null,
            'chapter_id' => !empty($chapter) ? $chapter->id : null,
            'webinar_title' => !empty($webinar) ? $webinar->title : null,
            'attempt' => $data['attempt'] ?? null,
            'pass_mark' => $data['pass_mark'],
            'time' => $data['time'] ?? null,
            'status' => (!empty($data['status']) and $data['status'] == 'on') ? Quiz::ACTIVE : Quiz::INACTIVE,
            'certificate' => (!empty($data['certificate']) and $data['certificate'] == 'on') ? true : false,
            'updated_at' => time(),
        ]);

        if (!empty($quiz)) {
            QuizTranslation::updateOrCreate([
                'quiz_id' => $quiz->id,
                'locale' => mb_strtolower($locale),
            ], [
                'title' => $data['title'],
            ]);

            $checkChapterItem = WebinarChapterItem::where('user_id', $user->id)
                ->where('item_id', $quiz->id)
                ->where('type', WebinarChapterItem::$chapterQuiz)
                ->first();

            if (!empty($quiz->chapter_id)) {
                if (empty($checkChapterItem)) {
                    WebinarChapterItem::makeItem($user->id, $quiz->chapter_id, $quiz->id, WebinarChapterItem::$chapterQuiz);
                } elseif ($checkChapterItem->chapter_id != $quiz->chapter_id) {
                    $checkChapterItem->delete(); // remove quiz from old chapter and assign it to new chapter

                    WebinarChapterItem::makeItem($user->id, $quiz->chapter_id, $quiz->id, WebinarChapterItem::$chapterQuiz);
                }
            } else if (!empty($checkChapterItem)) {
                $checkChapterItem->delete();
            }
        }

        removeContentLocale();

        if ($request->ajax()) {
            return response()->json([
                'code' => 200
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function delete(Request $request, $id)
    {
        $this->authorize('admin_quizzes_delete');

        $quiz = Quiz::findOrFail($id);

        $quiz->delete();

        $checkChapterItem = WebinarChapterItem::where('item_id', $id)
            ->where('type', WebinarChapterItem::$chapterQuiz)
            ->first();

        if (!empty($checkChapterItem)) {
            $checkChapterItem->delete();
        }

        if ($request->ajax()) {
            return response()->json([
                'code' => 200
            ], 200);
        }

        return redirect()->back();
    }

    public function results($id)
    {
        $this->authorize('admin_quizzes_results');

        $quizzesResults = QuizzesResult::where('quiz_id', $id)
            ->with([
                'quiz' => function ($query) {
                    $query->with(['teacher']);
                },
                'user'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('admin/pages/quizResults.quiz_result_list_page_title'),
            'quizzesResults' => $quizzesResults,
            'quiz_id' => $id
        ];

        return view('admin.quizzes.results', $data);
    }

    public function resultsExportExcel($id)
    {
        $this->authorize('admin_quiz_result_export_excel');

        $quizzesResults = QuizzesResult::where('quiz_id', $id)
            ->with([
                'quiz' => function ($query) {
                    $query->with(['teacher']);
                },
                'user'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $export = new QuizResultsExport($quizzesResults);

        return Excel::download($export, 'quiz_result.xlsx');
    }

    public function resultDelete($result_id)
    {
        $this->authorize('admin_quizzes_results_delete');

        $quizzesResults = QuizzesResult::where('id', $result_id)->first();

        if (!empty($quizzesResults)) {
            $quizzesResults->delete();
        }

        return redirect()->back();
    }

    public function exportExcel(Request $request)
    {
        $this->authorize('admin_quizzes_lists_excel');

        $query = Quiz::query();

        $query = $this->filters($query, $request);

        $quizzes = $query->with([
            'webinar',
            'teacher',
            'quizQuestions',
            'quizResults',
        ])->get();

        return Excel::download(new QuizzesAdminExport($quizzes), trans('quiz.quizzes') . '.xlsx');
    }
}
