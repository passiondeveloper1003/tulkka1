<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NewQuiz;
use App\User;

class NewQuizController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $quizes;
        $students = User::where('role_name', 'user')->get();
        $teachers = User::where('role_name', 'teacher')->get();
        $quizes = NewQuiz::orderBy('created_at', 'desc')
        ->with(['student','teacher'])
        ->when($request->from, function ($q) use($request) {
            return $q->whereDate('created_at', '>', $request->from);
        })
        ->when($request->to, function ($q) use($request) {
            return $q->whereDate('created_at', '<', $request->to);
        })
        ->when($request->status != 'all' && $request->status, function ($q) use($request) {
            return $q->where('status', $request->status);
        })
        ->when($request->people_id != 'all' && $request->people_id, function ($q) use($request) {
            return $q->where('student_id', $request->people_id);
        })
        ->where('student_id', $user->id)->paginate(10);
        if ($user->isTeacher()) {
            $quizes = NewQuiz::orderBy('created_at', 'desc')
            ->with(['student','teacher'])
            ->when($request->from, function ($q) use($request) {
                return $q->whereDate('created_at', '>', $request->from);
            })
          ->when($request->to, function ($q) use($request) {
              return $q->whereDate('created_at', '<', $request->to);
          })
          ->when($request->status != 'all' && $request->status, function ($q) use($request) {
              return $q->where('status', $request->status);
          })
          ->when($request->people_id != 'all' && $request->people_id, function ($q) use($request) {
              return $q->where('teacher_id', $request->people_id);
          })
            ->where('teacher_id', $user->id)->paginate(10);
        }
        $pending = $quizes->filter(function ($value, $key) {
            return $value->status == 'pending';
        });
        $data = [
          'user' => $user,
          'quizes' => $quizes,
          'pending' => $pending,
          'students' => $students,
          'teachers' => $teachers
        ];
        return view(getTemplate() . '.panel.new_quiz.list', $data);
    }

    public function create()
    {
        $user = auth()->user();
        $data = [];


        return view(getTemplate() . '.panel.new_quiz.create', $data);
    }

    public function store()
    {
    }

    public function edit()
    {
    }
    public function update()
    {
    }
    public function destroy(Request $request)
    {
        $homework_id = $request->homework_id;
        $homework = NewQuiz::destroy($homework_id);
        return redirect('/panel/quizes');
    }

    public function show(Request $request)
    {
        $homework_id = $request->homework_id;
        $user = auth()->user();
        $quiz = NewQuiz::with(['student','teacher'])->where('id', $homework_id)->get()->first();
        $data = [
          'quiz' => $quiz
        ];

        return view(getTemplate() . '.panel.new_quiz.show', $data);
    }
}
