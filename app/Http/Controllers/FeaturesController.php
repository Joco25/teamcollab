<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FeaturesController extends Controller
{
    public function projects()
    {
        return view('features.projects');
    }

    public function conversations()
    {
        return view('features.conversations');
    }
}
