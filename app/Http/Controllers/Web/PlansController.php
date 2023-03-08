<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Subscribe;

use Illuminate\Http\Request;

class PlansController extends Controller
{
    public function index()
    {
        $subscribes = Subscribe::all();
        
        $data = [
            'subscribes' => $subscribes ?? [],
        ];
        
        return view('web.default.pages.plans', $data);
    
    }
}