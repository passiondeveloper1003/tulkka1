<?php

namespace App\Http\Controllers\Panel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
      return view(getTemplate() . '.panel.chat.index');
    }
}
