<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Homework;
use App\User;

class HomeworkController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $homeworks;
        $students = User::where('role_name', 'user')->get();
        $teachers = User::where('role_name', 'teacher')->get();

        $homeworks = HomeWork::orderBy('created_at', 'desc')
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
        ->where('student_id', $user->id)->paginate(10);
        if ($user->isTeacher()) {
            $homeworks = HomeWork::orderBy('created_at', 'desc')
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
            ->where('teacher_id', $user->id)
            ->paginate(10);
        }
        $pending = $homeworks->filter(function ($value, $key) {
            return $value->status == 'pending';
        });
        $data = [
          'user' => $user,
          'homeworks' => $homeworks,
          'pending' => $pending,
          'students' => $students,
          'teachers' => $teachers
        ];
        return view(getTemplate() . '.panel.homeworks.index', $data);
    }

    public function create()
    {
        $user = auth()->user();
        $data = [];


        return view(getTemplate() . '.panel.homeworks.create', $data);
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
        $homework = HomeWork::destroy($homework_id);
        return redirect('/panel/homeworks');
    }

    public function show(Request $request)
    {
        $homework_id = $request->homework_id;
        $user = auth()->user();
        $homework = HomeWork::with(['student','teacher'])->where('id', $homework_id)->get()->first();
        $data = [
          'homework' => $homework
        ];

        return view(getTemplate() . '.panel.homeworks.show', $data);
    }
}
