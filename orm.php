<?php

class ORM
{
    protected $pdo;
    protected $table;
    protected $join;
    protected $where;
    protected $group;
    protected $order;
    protected $limit;
    protected $query;
    protected $query_begin;
    protected $error;


    public function __construct($info)
    {
        if(isset($info['db']) && isset($info['host']) &&  isset($info['user']) && isset($info['pass']) )
        {
            $host = '';
            if ($info['host'] != '/')
            {
                $host = 'host=' . $info["host"];

                if (isset($info["port"])){
                    $host .= ';port=' . $info["port"];
                }
            }
            $dbname = $info['db'];
            $user = $info['user'];
            $pass = $info['pass'];
            try
            {
                $this->pdo = new PDO("mysql:dbname=$dbname;$host",$user,$pass);
            } catch (PDOException $e) {
                $this->error = true;
                echo $e->getMessage();
                exit;
            }
        }else{

            $this->error = true;
            echo ('no params for connect to db');
           exit;
        }

    }

    public function select( $fields, $distinct = '')
    {
        require_once 'select.php';
        return new SELECT($this->pdo, $fields,  $distinct);
    }

    public function update($table)
    {
        require_once 'update.php';
        return new UPDATE($this->pdo, $table);
    }

    public function insert($table)
    {
        require_once 'insert.php';
        return new INSERT($this->pdo, $table);
    }

    protected function query(){}
}