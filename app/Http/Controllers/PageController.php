<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;    

class PageController extends Controller
{
    public function about()
    {
        $name = "Артем";
        $age = 18;

        return view('about', ['name' => $name, 'age' => $age]);
    }

    public function users()
    {
        $users = ['Артем', 'Даур', 'Джони', 'Жангир', 'Расул'];

        return view('users', ['users' => $users]);
    }
}