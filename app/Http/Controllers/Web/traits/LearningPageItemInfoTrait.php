<?php

namespace App\Http\Controllers\Web\traits;

use App\Models\File;
use App\Models\Quiz;
use App\Models\QuizzesResult;
use App\Models\Session;
use App\Models\TextLesson;
use App\Models\Webinar;
use App\Models\WebinarChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait LearningPageItemInfoTrait
{
    public function getItemInfo(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'type' => 'required|in:file,session,text_lesson,quiz',
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $type = $data['type'];
        $id = $data['id'];

        switch ($type) {
            case 'file':
                return $this->getFileInfo($id);
            case 'text_lesson':
                return $this->getTextLessonInfo($id);
            case 'session':
                return $this->getSessionInfo($id);
            case 'quiz':
                return $this->getQuizInfo($id);
        }
    }

    private function checkCourseAccess($courseId): bool
    {
        $user = auth()->user();

        $course = Webinar::find($courseId);

        return (!empty($course) and $course->checkUserHasBought($user));
    }

    private function getFileInfo($id)
    {
        $file = File::select('id', 'downloadable', 'webinar_id', 'chapter_id', 'storage', 'online_viewer', 'file')
            ->where('id', $id)
            ->where('status', WebinarChapter::$chapterActive)
            ->first();

        $checkSequenceContent = !empty($file) ? $file->checkSequenceContent() : null;
        $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

        if (!empty($file) and ($file->accessibility == 'free' or $this->checkCourseAccess($file->webinar_id)) and !$sequenceContentHasError) {

            $filePath = url($file->file);

            if (in_array($file->storage, ['s3', 'external_link'])) {
                $filePath = $file->file;
            }

            // for translate send on array of data
            $data = [
                'file' => [
                    'id' => $file->id,
                    'title' => $file->title,
                    'storage' => $file->storage,
                    'downloadable' => $file->downloadable ?? false,
                    'online_viewer' => $file->online_viewer ?? false,
                    'file_path' => $file->online_viewer ? $filePath : false,
                ]
            ];

            return response()->json($data);
        }

        abort(403);
    }

    private function getSessionInfo($id)
    {
        $session = Session::where('id', $id)
            ->where('status', WebinarChapter::$chapterActive)
            ->first();

        $checkSequenceContent = !empty($session) ? $session->checkSequenceContent() : null;
        $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

        if (!empty($session) and $this->checkCourseAccess($session->webinar_id) and !$sequenceContentHasError) {

            $isFinished = $session->isFinished();
            // for translate send on array of data
            $data = [
                'session' => [
                    'id' => $session->id,
                    'title' => $session->title,
                    'is_finished' => $isFinished,
                    'is_started' => (time() > $session->date),
                    'join_url' => !$isFinished ? $session->getJoinLink(true) : null,
                    'start_data' => dateTimeFormat($session->date, 'j M Y H:i')
                ]
            ];

            return response()->json($data);
        }

        abort(403);
    }

    private function checkQuizResult($quiz)
    {
        $user = auth()->user();

        $userQuizDone = QuizzesResult::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $canDownloadCertificate = false;
        $canTryAgainQuiz = false;

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

    private function getQuizInfo($id)
    {
        $quiz = Quiz::where('id', $id)
            ->where('status', WebinarChapter::$chapterActive)
            ->first();

        if (!empty($quiz) and $this->checkCourseAccess($quiz->webinar_id)) {
            $quiz = $this->checkQuizResult($quiz);

            // for translate send on array of data
            $data = [
                'quiz' => [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'can_try' => $quiz->can_try,
                ]
            ];

            return response()->json($data);
        }

        abort(403);
    }

    private function getTextLessonInfo($id)
    {
        $user = auth()->user();

        $textLesson = TextLesson::where('id', $id)
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

        $checkSequenceContent = !empty($textLesson) ? $textLesson->checkSequenceContent() : null;
        $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

        if (!empty($textLesson) and ($textLesson->accessibility == 'free' or $this->checkCourseAccess($textLesson->webinar_id)) and !$sequenceContentHasError) {

            $attachments = [];

            if (!empty($textLesson->attachments) and count($textLesson->attachments)) {
                foreach ($textLesson->attachments as $attachment) {
                    if ($attachment and $attachment->file) {
                        $attachments[] = [
                            'id' => $attachment->id,
                            'file' => [
                                'id' => $attachment->file->id,
                                'title' => $attachment->file->title,
                                'file_type' => $attachment->file->file_type,
                                'volume' => $attachment->file->volume,
                            ]
                        ];
                    }
                }
            }

            // for translate send on array of data
            $data = [
                'textLesson' => [
                    'id' => $textLesson->id,
                    'title' => $textLesson->title,
                    'image' => url($textLesson->image),
                    'study_time' => $textLesson->study_time,
                    'summary' => $textLesson->summary,
                    'content' => $textLesson->content,
                    'attachments' => $attachments,
                    'learningStatus' => $textLesson->learningStatus,
                ]
            ];

            return response()->json($data);
        }

        abort(403);
    }
}
