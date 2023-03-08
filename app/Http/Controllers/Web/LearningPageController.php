<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\LearningPageAssignmentTrait;
use App\Http\Controllers\Web\traits\LearningPageForumTrait;
use App\Http\Controllers\Web\traits\LearningPageItemInfoTrait;
use App\Http\Controllers\Web\traits\LearningPageMixinsTrait;
use App\Http\Controllers\Web\traits\LearningPageNoticeboardsTrait;
use App\Models\CourseNoticeboard;
use Illuminate\Http\Request;

class LearningPageController extends Controller
{
    use LearningPageMixinsTrait, LearningPageAssignmentTrait, LearningPageItemInfoTrait,
        LearningPageNoticeboardsTrait, LearningPageForumTrait;

    public function index(Request $request, $slug)
    {
        $requestData = $request->all();

        $webinarController = new WebinarController();

        $data = $webinarController->course($slug, true);

        if (!$data or !$data['hasBought']) {
            abort(403);
        }

        $course = $data['course'];
        $user = $data['user'];

        if ($data['hasBought'] and !empty($requestData['type']) and $requestData['type'] == 'assignment' and !empty($requestData['item'])) {

            $assignmentData = $this->getAssignmentData($course, $requestData);

            $data = array_merge($data, $assignmentData);
        }

        if ($course->creator_id != $user->id and $course->teacher_id != $user->id and !$user->isAdmin()) {
            $unReadCourseNoticeboards = CourseNoticeboard::where('webinar_id', $course->id)
                ->whereDoesntHave('noticeboardStatus', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->count();

            if ($unReadCourseNoticeboards) {
                $url = $course->getNoticeboardsPageUrl();
                return redirect($url);
            }
        }

        return view('web.default.course.learningPage.index', $data);
    }
}
