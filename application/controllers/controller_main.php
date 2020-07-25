<?php

include "application/models/model_user.php";

class Controller_Main
{
    /**
     * @var Model_User
     */
    private $user;
    /**
     * @var View
     */
    private $view;
    /**
     * @var Model_Main
     */
    private $model;

    function __construct()
	{
		$this->model = new Model_Main();
		$this->user = new Model_User();
		$this->view = new View();
	}
	
	function action_index()
	{
	    $user = $this->user->getAuth();
		$data['tasks'] = $this->model->get_tasks();
        $data['auth'] = [
            'user' => $user ? $user['name'] : null,
            'message' => $user ? 'Authorized success' : 'Wrong login or password'
        ];
		$this->view->generate('main_view.php', 'template_view.php', $data);
	}

    function action_toggle()
    {
        try {
            if(!$this->user->getAuth()) throw new Exception('Not authorized', 403);
            $this->model->toggleTaskStatus($_POST['id']);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }

    function action_update()
    {
        try {
            if(!$this->user->getAuth()) throw new Exception('Not authorized', 403);
            $this->model->updateTask($_POST['id'], $_POST['text']);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
