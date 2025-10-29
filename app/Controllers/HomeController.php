<?php
namespace App\Controllers;

use Src\Core\Controller;
use Src\Core\Request;
use Src\Core\Response;
use Src\Core\View;
class HomeController extends Controller
{
    public function index(Request $req, Response $res): string
    {
        $db = $this->app->getDb();
        $users = $db->select('users', ['id', 'name', 'email']);

        return View::render('index', ['users' => $users]);
    }

    public function hello(Request $req, Response $res): string
    {
        // Sample: maybe later you’ll get data from DB using Medoo
        $users = ['Alice', 'Bob', 'Charlie'];

        // Render the view and pass data
        return View::render('home', ['users' => $users]);
    }
}
