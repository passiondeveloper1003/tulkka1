<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Component;
use App\Message;
use App\User;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class Chat extends Component
{
    use WithFileUploads,WithPagination;
    public $students;
    public $selectedContact;
    public $messages;
    public $writtenMessage;
    public $search;
    public $chatfile;
    public $authUser;


    // get user unreaded messages
    public function mount($teacher = null)
    {
        $user = auth()->user();
        $this->authUser = $user;
        if ($teacher) {
            $this->selectedContact = User::find($teacher);
        }

        if ($user->role_name == 'user') {
            $this->students = User::with(['recievedMessages','sentMessages'])->where('role_name', 'teacher')
            ->get();
        } else {
            $this->students = User::with(['recievedMessages','sentMessages'])->where('role_name', 'user')
            ->get();
        }

        $this->students = $this->students->sort(function ($people, $key) {
          if( $people->recievedMessages->count() > 0 ){
            foreach($people->recievedMessages as $recievedMessage){
              if($recievedMessage->from_user == $this->authUser->id){
                return -1;
              }
            }
          }
          if($people->sentMessages->count() > 0){
            foreach($people->sentMessages as $sentMessage){
              if($sentMessage->to_user == $this->authUser->id){
                return -1;
              }
            }
          }
          return 1;
      });


    }
    public function render()
    {

        $user = auth()->user();
        if ($user->role_name == 'user') {
            $this->students = User::with(['recievedMessages','sentMessages'])->where('role_name', 'teacher')
            ->when($this->search, function ($q) {
                return $q->where('full_name', 'LIKE', '%'.$this->search.'%');
            })
            ->get();
        } else {
            $this->students = User::with(['recievedMessages','sentMessages'])->where('role_name', 'user')
            ->when($this->search, function ($q) {
                return $q->where('full_name', 'LIKE', '%'.$this->search.'%');
            })
            ->get();
        }
        if (isset($this->selectedContact)) {
            $contact = $this->selectedContact->id;
            $from_user = auth()->user();
            $this->messages = Message::where(function ($query) use ($from_user, $contact) {
                $query->where('from_user', $from_user->id)
                ->where('to_user', $contact);
            })
            ->orWhere(function ($query) use ($from_user, $contact) {
                $query->where('to_user', $from_user->id)
                ->where('from_user', $contact)
                ;
            })
            ->get();

        }
        $this->students = $this->students->sort(function ($people, $secondPeople) {
          if( $people->recievedMessages->count() > 0 ){
            foreach($people->recievedMessages as $recievedMessage){
              if($recievedMessage->from_user == $this->authUser->id ){
                return -1;
              }
            }
          }
          if($people->sentMessages->count() > 0){
            foreach($people->sentMessages as $sentMessage){
              if($sentMessage->to_user == $this->authUser->id){
                return -1;
              }
            }
          }
          return 1;
      });

        return view('livewire.chat');
    }

    public function setSelectedContact($contact, $name)
    {
        $from_user = auth()->user();
        $this->selectedContact = User::find($contact);
        $messages = Message::where(function ($query) use ($from_user, $contact) {
            $query->where('from_user', $from_user->id)
            ->where('to_user', $contact);
        })
        ->orWhere(function ($query) use ($from_user, $contact) {
            $query->where('to_user', $from_user->id)
            ->where('from_user', $contact);
        })
        ->get();
        foreach ($messages as $message) {
            $message->statu = 'readed';
            $message->save();
        }
        $this->messages = $messages;

        //where('from_user',$from_user->id)->where('to_user',$contact)->get();
    }

    public function sendMessage()
    {
      if(strlen($this->writtenMessage) < 1 && !$this->chatfile){
        return;
      }
        $from_user = auth()->user();
        if($this->chatfile){
        $validatedData = Validator::make(
          ['chatfile' => $this->chatfile],
          ['chatfile' => 'mimes:jpg,bmp,png,pdf,docx|max:4096'],
      )->validate();
      $folderPath = "/" . $from_user->id . '/message_attachment/';
        $file = $this->chatfile->getClientOriginalName();
        $this->chatfile->storeAs($folderPath,$file);
        $url = Storage::disk('public')->url($folderPath . $file);
        $this->chatfile->store('public');
        }


        Message::create([
          'from_user' => $from_user->id,
          'to_user' => $this->selectedContact->id,
          'body' => $this->writtenMessage,
          'statu' => 'unread',
          'created_at' => time(),
          'attachment' => $url ?? null,
          'attachment_name' => $file ?? null
        ]);
        $this->writtenMessage = '';
        $this->chatfile = null;
    }




   public function loadMore()
   {
       $this->paginate_var = $this->paginate_var + 10;
       $this->emit('load');
   }
}
