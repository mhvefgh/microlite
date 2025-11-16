<?php

namespace App\Controllers;

use Src\Core\Controller;
use Src\Core\Request;

class UserController extends Controller
{
    /**
     * Display the index page
     */
    public function index(Request $request): string
    {
        return view('welcome');
    }
}
