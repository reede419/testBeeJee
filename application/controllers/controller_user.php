<?php

class Controller_User
{
    /**
     * @var Model_User
     */
    private $model;

    function __construct()
	{
		$this->model = new Model_User;
	}

	function action_index()
	{
	    $this->model->authUser($_POST['username'], $_POST['password']);
	    header('Location: /');
	}
}
