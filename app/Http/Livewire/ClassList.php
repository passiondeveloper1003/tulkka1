<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\User;
use App\Lesson;
use App\UserSubscription;
use Livewire\WithPagination;
use Illuminate\Http\Request;

class ClassList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $instructors;
    public $students;
    public $authUser;
    public $totalCount;
    public $from;
    public $to;
    public $teacher;
    public $student;
    public $status;
    public $started;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount($from = null, $to = null, $teacher = null, $status = null, $started = null, $student = null)
    {
        $this->authUser = auth()->user();
        $this->instructors = User::where('role_name', 'teacher')->get();
        $this->students = User::where('role_name', 'user')->get();
        $this->from = $from;
        $this->to = $to;
        $this->teacher = $teacher;
        $this->status = $status;
        $this->started = $started;
        $this->student = $student;
    }
    public function render()
    {
        $this->totalCount = Lesson::where('student_id', $this->authUser->id)->count();
        $reserveMeetings = Lesson::orderBy('meeting_start', 'desc')
        ->with(['teacher','student'])->where('student_id', $this->authUser->id)
        ->when($this->from, function ($q) {
            return $q->whereDate('meeting_start', '>', $this->from);
        })
        ->when($this->to, function ($q) {
            return $q->whereDate('meeting_end', '<', $this->to);
        })
        ->when($this->status != 'All' && $this->status, function ($q) {
            return $q->where('status', $this->status);
        })
        ->when($this->started, function ($q) {
            return $q->where('status', 'started');
        })
        ->when($this->teacher != 'all' && $this->teacher, function ($q) {
            return $q->where('teacher_id', $this->teacher);
        })
        ->when($this->student != 'all' && $this->student, function ($q) {
            return $q->where('student_id', $this->student);
        })
        ->paginate(10);

        $this->openReserveCount = Lesson::where('student_id', $this->authUser->id)->where('status', 'pending')->count();
        $this->totalReserveCount = Lesson::where('student_id', $this->authUser->id)->where('status', 'ended')->count();

        if ($this->authUser->isTeacher()) {
            $this->totalCount = Lesson::where('teacher_id', $this->authUser->id)->count();
            $reserveMeetings =  Lesson::orderBy('meeting_start', 'desc')
            ->with(['teacher','student'])->where('teacher_id', $this->authUser->id)
            ->when($this->from, function ($q) {
                return $q->whereDate('meeting_start', '>', $this->from);
            })
            ->when($this->to, function ($q) {
                return $q->whereDate('meeting_end', '<', $this->to);
            })
            ->when($this->status != 'All' && $this->status, function ($q) {
                return $q->where('status', $this->status);
            })
            ->when($this->started, function ($q) {
                return $q->where('status', 'started');
            })
            ->when($this->teacher != 'all' && $this->teacher, function ($q) {
                return $q->where('teacher_id', $this->teacher);
            })
            ->when($this->student != 'all' && $this->student, function ($q) {
                return $q->where('student_id', $this->student);
            })
            ->paginate(10);
            $this->openReserveCount = Lesson::where('teacher_id', $this->authUser->id)->where('status', 'pending')->count();
            $this->totalReserveCount = Lesson::where('teacher_id', $this->authUser->id)->where('status', 'ended')->count();
        }
        return view('livewire.class-list', [
          'reserveMeetings' => $reserveMeetings,
          'openReserveCount' => $this->openReserveCount,
          'totalReserveCount' => $this->totalReserveCount
      ]);
    }
    public function goToChat($teacher_id)
    {
        return redirect()->to('/panel/chat?teacher='.$teacher_id);
    }

    public function goToHomework($teacher_id)
    {
        return redirect()->to('/panel/chat?teacher='.$teacher_id);
    }

    public function goToEvaluation($id)
    {
        return redirect()->to('/panel/feedbacks?feedback_id='.$id);
    }


    public function cancelLesson($lessonId)
    {
        $lesson = Lesson::where('id', $lessonId)->get()->first();
        if ($lesson) {
            $lesson->status = 'canceled';
            $lesson->save();
            //$canceledCurrentWeek = \Carbon\Carbon::parse($lesson->meeting_start)->isCurrentWeek();
        }
    }
}
