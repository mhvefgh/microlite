<?php
namespace App\Controllers;

use Src\Core\Controller;
use Src\Core\Request;
use Src\Core\Response;
use Src\Core\View;
use Src\Core\App;

class AllController extends Controller
{
    public function index(Request $req, Response $res): string
    {
        $db = $this->app->getDb();
        $users = $db->select('users', ['id', 'name', 'email']);

        return View::render('admin.index', ['users' => $users]);
    }
}
