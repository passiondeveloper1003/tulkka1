<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\User;
use App\Lesson;

class AfterLessonFeedBackModal extends Component
{
    public $show = true;
    public $authUser;
    public $title = 'Lesson Feedback';
    public $lesson_id;
    public $lesson;


    protected $listeners = ['showAfterLessonFeedBackModal' => 'showModal'];

    public function mount()
    {
        $this->authUser = auth()->user();
    }


    public function showModal()
    {
        $this->doShow();
    }

    public function doShow()
    {
        $this->show = true;
    }

    public function doClose()
    {
        $this->reset();
        $this->show = false;
    }




    public function render()
    {
        return view('livewire.after-lesson-feed-back-modal');
    }
}
