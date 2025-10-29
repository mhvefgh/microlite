<?php
namespace App\Controllers\Dashboard;

use Src\Core\Request;
use Src\Core\Controller;
use Src\Core\Response;
use Src\Core\View;
class DashboardController extends Controller
{
    public function index(Request $req, Response $res): string
    {
        $db = $this->container->get('db');
        $users = $db->select('users', '*');

        return View::render('admin.index', ['users' => $users]);
    }
}
