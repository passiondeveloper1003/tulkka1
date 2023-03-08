<?php

namespace App\Http\Livewire;

use Livewire\Component;


class AddToCalendarModal extends Component
{

    public $show = false;
    public $authUser;
    public $googleLink;
    public $outlookLink;

    protected $listeners = ['openAddToCalendar' => 'showAddToModal'];

    public function mount()
    {
        $this->authUser = auth()->user();

    }

    public function showAddToModal($googleLink = null,$outlookLink = null)
    {
        $this->authUser = auth()->user();
        $this->googleLink = $googleLink;
        $this->outlookLink = $outlookLink;
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
        $this->redirect('/panel/meetings/reservation?booking=success');
    }


    public function render()
    {
        return view('livewire.add-to-calendar-modal');
    }
}
