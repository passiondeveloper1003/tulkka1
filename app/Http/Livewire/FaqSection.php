<?php

namespace App\Http\Livewire;

use Livewire\Component;

class FaqSection extends Component
{
    public $activeSection = 'general';

    public function setActiveSection($section)
    {
        $this->activeSection = $section;
    }
    public function render()
    {
        return view('livewire.faq-section');
    }
}
