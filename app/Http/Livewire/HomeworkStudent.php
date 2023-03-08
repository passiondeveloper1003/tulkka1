<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Homework;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Jobs\SendNotificationJob;

class HomeworkStudent extends Component
{
    use WithFileUploads;

    public $homework;
    public $answer;
    public $authUser;
    public $answer_attachment;
    public $teacher_notes;

    public function mount($homework)
    {
        $this->authUser = auth()->user();
        $this->homework = $homework;
        $this->teacher_notes = $homework->teacher_notes;
    }
    public function render()
    {
        return view('livewire.homework-student');
    }

    public function sendNote()
    {
        $homework = $this->homework;
        $homework->teacher_notes = $this->teacher_notes;
        $homework->save();
        $this->emit('noteSent');
    }
    public function sendHomework()
    {
        $stored_file = null;
        if ($this->answer_attachment) {
            $this->validate([
              'answer_attachment' => 'mimes:pdf,jpeg,png,jpg,zip,rar|max:9000',
            ]);

            $stored_file =  $this->answer_attachment->store('homework_answer_attachment');
        }
        $homework = $this->homework;
        $homework->student_answers = $this->answer;
        if ($stored_file) {
            $homework->answer_attachment = $stored_file;
        }
        $homework->status = 'ended';
        $homework->save();
        $homework_url = url('panel/homeworks/'. $homework->id .'/details');
        $notifyOptions = [
          'student.name' => '',
          'link' => $homework_url
        ];
        dispatch(new SendNotificationJob('homework_answer_recieved', $notifyOptions, $homework->teacher_id));
        $this->emit('homeworkSent');
        return redirect()->to('/panel/homeworks');
    }

    public function download()
    {
        return Storage::disk('public')->download($this->homework->attachment);
    }

    public function downloadAnswers()
    {
        return Storage::disk('public')->download($this->homework->answer_attachment);
    }
}
