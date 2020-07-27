<?php

class Model_Main
{
    protected $table = 'tasks';

    use MysqlConnect;

	public function get_tasks()
	{
        return $this->getAllData($this->table);
	}

	public function toggleTaskStatus($id)
    {
        if(!$task = $this->getOne($this->table, ['id' => $id])) throw new Exception('Record not found');
        return $this->update($this->table, ['done' => $task['done'] ? 0 : 1], $id);
    }

	public function updateTask($id, $text)
    {
        if(!$task = $this->getOne($this->table, ['id' => $id])) throw new Exception('Record not found');
        return $this->update($this->table, ['text' => $text], $id);
    }

	public function addTask($data)
    {
        return $this->add($this->table, [
            "user_name" => $data['user_name'],
            "email" => $data['email'],
            "text" => $data['text']
        ]);
    }
}
