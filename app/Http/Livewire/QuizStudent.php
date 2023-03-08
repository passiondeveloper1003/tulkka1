<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\NewQuiz;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Jobs\SendNotificationJob;

class QuizStudent extends Component
{
    use WithFileUploads;

    public $quiz;
    public $quizAnswer;
    public $authUser;
    public $quizPoint;
    public $answer_attachment;
    public $teacher_notes;

    public function mount($quiz)
    {
        $this->authUser = auth()->user();
        $this->quiz = $quiz;
        $this->teacher_notes = $quiz->teacher_notes;
    }
    public function render()
    {
        return view('livewire.quiz-student');
    }

    public function sendNote()
    {
      $quiz = $this->quiz;
      $quiz->teacher_notes = $this->teacher_notes;
      $quiz->save();
      $this->emit('noteSent');
    }

    public function sendQuiz()
    {
        $stored_file = null;
        if ($this->answer_attachment) {
            $this->validate([
              'answer_attachment' => 'mimes:pdf,jpeg,png,jpg,zip,rar|max:9000',
            ]);
            $stored_file =  $this->answer_attachment->store('quiz_answer_attachment');
        }


        $quiz = $this->quiz;
        $quiz->student_answers = $this->quizAnswer;
        if ($stored_file) {
            $quiz->answer_attachment = $stored_file;
        }

        $quiz->status = 'ended';
        $quiz->save();
        $notifyOptions = [
          'student.name' => ''
        ];
        dispatch(new SendNotificationJob('quiz_answer_recieved', $notifyOptions, $quiz->teacher_id));
        $this->emit('quizSent');
        return redirect()->to('/panel/quizes');
    }
    public function givePoint()
    {
        $quiz = $this->quiz;
        $quiz->result = $this->quizPoint;
        $quiz->save();
        $this->emit('quizResultSent');
        return redirect()->to('/panel/quizes');
    }

    public function download()
    {
        return Storage::disk('public')->download($this->quiz->attachment);
    }
    public function downloadAnswers()
    {
        return Storage::disk('public')->download($this->quiz->answer_attachment);
    }
}
