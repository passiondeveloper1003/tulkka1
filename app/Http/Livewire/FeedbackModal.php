<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\LessonFeedback;
use App\Jobs\SendNotificationJob;

class FeedbackModal extends Component
{
    public $show = false;
    public $data;
    public $grammar;
    public $pronunciation;
    public $comment;
    public $speaking;
    public $authUser;
    public $student_id;
    public $teacher_id;
    public $lesson_id;
    public $grammarRate;
    public $pronunciationRate;
    public $speakingRate;


    protected $listeners = ['showFeedbackModal' => 'showModal'];
    public function mount()
    {
        $this->authUser = auth()->user();
    }
    public function showModal($student, $teacher, $lesson)
    {
        $this->authUser = auth()->user();
        $this->student_id = $student;
        $this->teacher_id = $teacher;
        $this->lesson_id = $lesson;
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
    public function setPronRate($rate)
    {
        $this->pronunciationRate = $rate;
    }
    public function setGrammarRate($rate)
    {
        $this->grammarRate = $rate;
    }
    public function setSpeakingRate($rate)
    {
        $this->speakingRate = $rate;
    }
    public function sendFeedback()
    {
        $feedback = [
        'teacher_id' => $this->authUser->id,
        'student_id' => $this->student_id,
        'lesson_id' => $this->lesson_id,
        'grammar' => $this->grammar,
        'pronunciation' => $this->pronunciation,
        'speaking' => $this->speaking,
        'comment' => $this->comment,
        'grammar_rate' => $this->grammarRate,
        'pronunciation_rate' => $this->pronunciationRate,
        'speaking_rate' => $this->speakingRate,
    ];
        $lesson_feedback = LessonFeedback::create($feedback);
        $notifyOptions = [
          'link' => url('panel/feedbacks/'.$lesson_feedback->id)
        ];
        dispatch(new SendNotificationJob('feedback_recieved', $notifyOptions, $this->student_id));

        $this->emit('feedbackSent');
        $this->doClose();
    }

    public function render()
    {
        return view('livewire.feedback-modal');
    }
}
