<?php

trait MysqlConnect {
    public $db;
    public function __construct()
    {
        // $this->db = new PDO('mysql:host=localhost;dbname=beejeetesttask97', 'testbeejee97', 'Password2', array(
        //     PDO::ATTR_PERSISTENT => true
        // ));
        $this->db = new PDO('mysql:host=localhost;dbname=testBeeJee', 'root', '2019', array(
            PDO::ATTR_PERSISTENT => true
        ));
    }

    public function getOne($table, $filters = [])
    {
        $getRow = $this->db->prepare("SELECT * FROM {$table} {$this->setFilters($filters)} ORDER BY id ASC");
        $getRow->execute();
        return $getRow->fetch();
    }

    public function getAllData($table, $filters = [])
    {
        $getTasks = $this->db->prepare("SELECT * FROM {$table} 
                {$this->setFilters($filters)} 
                ORDER BY {$this->setSort()} {$this->setOrder()} 
                LIMIT 3 
                {$this->setOffset()}");
        $getTasks->execute();
        $count = $this->db->prepare("SELECT COUNT(*) FROM {$table} {$this->setFilters($filters)}");
        $count->execute();
        return ['tasks' => $getTasks->fetchAll(), 'count' => $count->fetch()];
    }

    public function ifExists($table, $filters = [])
    {
        $row = $this->getOne($table, $filters);
        return (boolean)$row;
    }

    public function update($table, $fields, $id)
    {
        if(!is_array($fields) || count($fields) == 0) return false;
        $params = implode(array_map(function($k, $v){
            return "$k=\"$v\"";
        }, array_keys($fields), array_values($fields)));
        $getTasks = $this->db->prepare("UPDATE {$table} SET {$params} WHERE id=" . $id);
        $getTasks->execute();
        return $getTasks->fetchAll();
    }

    private function setFilters($filters)
    {
        if(is_array($filters) && count($filters) == 0) {
            return '';
        }
        foreach($filters as $key => $value) {
            $condition[] = $key . '="' . $value . '"';
        }
        return 'where ' . implode(' || ', $condition);
    }

    private function setOffset()
    {
        if(!isset($_GET['page']) || empty($_GET['page'])) return 'OFFSET 0';
        $offset = ($_GET['page']-1) * 3;
        return 'OFFSET ' . $offset;
    }

    private function setSort()
    {
        if(!isset($_GET['sort']) || empty($_GET['sort'])) return 'id';
        return $_GET['sort'];
    }

    private function setOrder()
    {
        if(!isset($_GET['order']) || empty($_GET['order'])) return 'ASC';
        return $_GET['order'];
    }
}