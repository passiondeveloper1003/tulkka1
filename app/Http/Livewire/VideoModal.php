<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\User;

class VideoModal extends Component
{
    public $show = false;
    public $authUser;
    public $title;
    public $video_link;
    public $type;



    protected $listeners = ['showVideoModal' => 'showModal'];

    public function mount()
    {
        $this->authUser = auth()->user();
    }


    public function showModal($title,$video_link)
    {
      $this->title = $title;
      $this->video_link = $video_link;
      $this->type = strpos($video_link,'youtube') ? 'youtube':'local' ;
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
        return view('livewire.video-modal');
    }
}
