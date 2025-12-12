<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function profile()
    {
        return view('profile.show');
    }

    public function admin()
    {
        return view('admin');
    }
}
