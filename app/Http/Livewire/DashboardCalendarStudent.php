<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Asantibanez\LivewireCalendar\LivewireCalendar;
use App\Lesson;
use Illuminate\Database\Eloquent\Collection;

class DashboardCalendarStudent extends LivewireCalendar
{
    public $lessons;

    public function events(): Collection
    {
        $authUser = auth()->user();
        return Lesson::query()
            ->where('student_id', $authUser->id)
            ->get();
    }

}
