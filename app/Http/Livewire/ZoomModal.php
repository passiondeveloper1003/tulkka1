<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\User;
use App\Lesson;

class ZoomModal extends Component
{
    public $show = false;
    public $authUser;
    public $zoom_url;
    public $title = 'Zoom Link';
    public $zoom_admin_url;
    public $lesson_id;
    public $lesson;


    protected $listeners = ['showZoomModal' => 'showModal'];

    public function mount()
    {
        $this->authUser = auth()->user();
    }


    public function showModal($join_url, $admin_url, $lesson_id)
    {
        $this->lesson_id = $lesson_id;
        $this->lesson = Lesson::where('id', $this->lesson_id)->get()->first();
        $this->zoom_url = $this->lesson->join_url;
        $this->zoom_admin_url = $this->lesson->admin_url;
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

    public function updateLesson()
    {
        $lesson = $this->lesson;
        $lesson->join_url = $this->zoom_url ;
        $lesson->admin_url = $this->zoom_admin_url;
        $lesson->save();
        $this->reset();
        $this->show = false;
    }


    public function render()
    {
        $this->lesson = Lesson::where('id', $this->lesson_id)->get()->first();
        return view('livewire.zoom-modal');
    }
}
