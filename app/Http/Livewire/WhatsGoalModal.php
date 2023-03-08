<?php

namespace App\Http\Livewire;

use Livewire\Component;

class WhatsGoalModal extends Component
{
    public $show = false;
    public $data;
    public $goals = [];
    public $notes;
    public $authUser;
    public $instructor_id;
    public $isTrial;

    protected $listeners = ['showGoalModal' => 'showModal'];
    public function mount()
    {
        $this->authUser = auth()->user();
    }
    public function showModal($instructor_id, $trial)
    {
        $this->instructor_id = $instructor_id;
        $this->isTrial = $trial;
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

    public function sendGoal()
    {
        $this->emit('sendGoalData', $this->goals, $this->notes, $this->instructor_id, $this->isTrial);
        $this->doClose();
    }
    public function addToGoals($goal)
    {
        if (in_array($goal, $this->goals)) {
            return;
        }
        $this->goals[] = $goal;
    }

    public function render()
    {
        return view('livewire.whats-goal-modal');
    }
}
