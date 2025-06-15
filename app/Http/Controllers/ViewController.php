<?php

namespace App\Http\Controllers;

use App\Models\LessonPackage;
use App\Models\Menu;
use App\Traits\HasMenus;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    use HasMenus;
    public function index()
    {
        $lessonPackages = LessonPackage::all();

        return view('home', compact('lessonPackages'));
        // return view('home');
    }

    public function dashboard()
    {
        return view('master.dashboard');
    }

    public function settings()
    {
        return view('master.settings');
    }
}
