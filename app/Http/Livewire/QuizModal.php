<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\NewQuiz;
use App\User;
use Livewire\WithFileUploads;
use App\Jobs\SendNotificationJob;

class QuizModal extends Component
{
    use WithFileUploads;
    public $show = false;
    public $authUser;
    public $title;
    public $description;
    public $students = [];
    public $students_ids = [];
    public $attachment;

    protected $listeners = ['showQuizModal' => 'showModal'];

    public function mount()
    {
        $this->authUser = auth()->user();
        $this->students = $this->authUser->students();
    }

    protected $rules = [
      'students_ids' => 'exists:permissions',
  ];

    public function showModal()
    {
        $this->authUser = auth()->user();
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
    public function sendQuiz()
    {
        $stored_file = null;
        if ($this->attachment) {
            $this->validate([
              'attachment' => 'mimes:pdf,jpeg,png,jpg,zip,rar|max:9000',
            ]);
            $stored_file =  $this->attachment->store('quiz_attachment');
        }


        foreach ($this->students_ids as $student_id) {
            $quizDetails = [
              'student_id' => $student_id,
              'teacher_id' => $this->authUser->id,
              'title' => $this->title,
              'status' => 'pending',
              'description' => $this->description,
              'created_at' => \Carbon\Carbon::now()
            ];
            if ($stored_file) {
                $quizDetails['attachment'] =  $stored_file;
            }
            $quiz = NewQuiz::create($quizDetails);
        }
        $quiz_url = url('panel/quizes/'. $quiz->id .'/details');
        $student = User::find($student_id);
        $notifyOptions = [
          'student.name' => $student->full_name,
          'instructor.name' => $this->authUser->full_name,
          'link' => $quiz_url
        ];
        dispatch(new SendNotificationJob('quiz_recieved', $notifyOptions, $student_id));
        $this->emit('quizSent');
        $this->doClose();
    }

    public function render()
    {
        $this->authUser = auth()->user();
        return view('livewire.quiz-modal');
    }
}
