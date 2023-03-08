<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Lesson;
use App\User;

class RescheduleModal extends Component
{
    public $show = false;
    public $data;
    public $goals = [];
    public $notes;
    public $authUser;
    public $instructor_id;
    public $lesson_id;
    public $i = 1;
    public $instructor;

    protected $listeners = ['showRescheduleModal' => 'showModal','openAddToCalendar' => 'doClose'];
    public function mount()
    {
        $this->authUser = auth()->user();
    }
    public function showModal($instructor, $lesson_id)
    {
        $this->instructor_id = $instructor;

        $this->instructor = User::where('id',$instructor)->first();
        $this->lesson_id = $lesson_id;
        $this->authUser = auth()->user();
        $this->doShow();
    }

    public function doShow()
    {
        $this->show = true;
    }

    public function doClose()
    {
        $this->show = false;
    }

    public function setReschedule(){
      $this->i = 2;
      $this->cancelLesson(true);
    }

    public function cancelLesson($res)
    {
        $lesson = Lesson::where('id', $this->lesson_id)->get()->first();
        if ($lesson) {
            $lesson->status = 'canceled';
            $lesson->save();
            //$canceledCurrentWeek = \Carbon\Carbon::parse($lesson->meeting_start)->isCurrentWeek();
        }
        if(!$res){
          $this->doClose();
          $this->redirect('/panel/meetings/reservation');
        }

    }

    public function render()
    {
        return view('livewire.reschedule-modal');
    }
}
