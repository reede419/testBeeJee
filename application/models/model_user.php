<?php

class Model_User
{
    protected $table = 'users';

    use MysqlConnect;

    public function authUser($username, $password)
    {
        if(!$this->checkUser($username, $password)) {
            return false;
        }
        $user = $this->getOne($this->table, [
            'name' => $username,
            'password' => $password
        ]);
        setcookie('auth', $user['id']);
    }

    public function getAuth()
    {
        if(!isset($_COOKIE['auth'])) return false;
        return $this->getOne($this->table, ['id' => $_COOKIE['auth']]);
    }

	public function checkUser($username, $password)
	{
	    return $this->ifExists($this->table, [
            'name' => $username,
            'password' => $password
        ]);
	}

}
