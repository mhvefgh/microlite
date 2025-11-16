<?php

namespace App\Controllers;

use Src\Core\Controller;
use Src\Core\Request;

/**
 * Home Controller - Sample controller for the framework
 */
class HomeController extends Controller
{
    /**
     * Display the homepage
     *
     * @param Request $req
     * @return string Rendered view
     */
    public function index(Request $req): string
    {
        $user = auth(); // Get authenticated user (or null)

        return view('index', [
            'title' => 'Welcome to MiniFrame',
            'user'  => $user,
            'framework' => 'MiniFrame v1.0',
            'tagline'   => 'A lightweight, fast and clean PHP framework'
        ], 'layouts.main');
    }
}