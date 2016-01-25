<?php

namespace Controller;

use \Hasty\DB\Query;
use Hasty\Controller;
use Hasty\Session;
use Model\PgDatabase;

class Auth extends Controller
{

    /**
     * @route GET,POST /login
     */
    public function login()
    {
        if ($this->isPost()) {

            Query::config([
                'schema' => 'pgsql',
                'host' => trim($_POST['host']),
                'user' => trim($_POST['username']),
                'pass' => $_POST['password'],
                'dbname' => trim($_POST['dbname']),
            ]);
            try {
                Query::get([new PgDatabase()])->select()->all();
            } catch (\Exception $e) {
                return $this->redirect('/login', ['error' => 'Invalid account, try again.']);
            }

            Session::set([
                'host' => trim($_POST['host']),
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'dbname' => trim($_POST['dbname'])
            ]);

            return $this->redirect('/');
        }
        return $this->render('auth/login.twig');
    }

    /**
     * @route GET /logout
     */
    public function logout()
    {
        Session::del('user');
        return $this->redirect('/login');
    }
}