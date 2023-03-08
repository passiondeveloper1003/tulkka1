<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Homework;
use Livewire\WithFileUploads;
use App\Jobs\SendNotificationJob;

class HomeworkModal extends Component
{
    use WithFileUploads;

    public $show = false;
    public $authUser;
    public $student_id;
    public $teacher_id;
    public $lesson_id;
    public $title;
    public $description;
    public $attachment;
    public $student_name;


    protected $listeners = ['showHomeworkModal' => 'showModal'];

    public function mount()
    {
        $this->authUser = auth()->user();
    }


    public function showModal($student_name,$student, $teacher, $lesson)
    {
        $this->authUser = auth()->user();
        $this->student_name = $student_name;
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
    public function sendHomework()
    {
        $stored_file = null;
        if ($this->attachment) {
            $this->validate([
              'attachment' => 'mimes:pdf,jpeg,png,jpg,zip,rar|max:9000',
            ]);
            $stored_file =  $this->attachment->store('homework_attachment');
        }


        $homeworkDetails = [
          'lesson_id' => $this->lesson_id,
          'student_id' => $this->student_id,
          'teacher_id' => $this->teacher_id,
          'title' => $this->title,
          'status' => 'pending',
          'description' => $this->description,
          'created_at' => \Carbon\Carbon::now()
        ];

        if ($stored_file) {
            $homeworkDetails['attachment'] =  $stored_file;
        }


        $homework = Homework::create($homeworkDetails);
        $homework_url = url('panel/homeworks/'. $homework->id .'/details');
        $notifyOptions = [
          'student.name' => $this->student_name,
          'instructor.name' => $this->authUser->full_name,
          'link' => $homework_url
        ];
        dispatch(new SendNotificationJob('homework_recieved', $notifyOptions, $this->student_id));
        $this->emit('homeworkSent');
        $this->doClose();
    }

    public function render()
    {
        return view('livewire.homework-modal');
    }
}
