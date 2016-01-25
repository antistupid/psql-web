<?php

namespace Controller;

use Hasty\Controller;
use Hasty\Session;
use Lib\Commander;

class Main extends Controller
{
    /**
     * @route GET /
     */
    public function index()
    {
        if (!Session::get('username'))
            return $this->redirect('/login');
        return $this->render('main/index.twig', [
            'username' => Session::get('username'),
            'host' => Session::get('host'),
            'dbname' => Session::get('dbname'),
        ]);
    }

    /**
     * @route POST /query
     */
    public function query()
    {
        if (!Session::get('username'))
            return $this->redirect('/login');

        $commander = new Commander(trim($_POST['command']));

        /** @var \Lib\CommandResult $result */
        $result = $commander->execute();

        return $this->jsonify($result->getValue(), !$result->getIsSuccess() ? 406 : 200);
    }
}
