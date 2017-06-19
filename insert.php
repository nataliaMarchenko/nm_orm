<?php

Class INSERT extends ORM
{
    protected $insert_data;
    protected $fields;
    protected $values;

    /*$table (string) – The table to query*/
    public function __construct($pdo, $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->query_begin = "INSERT INTO " ;
    }

    protected function query()
    {
        $fields = '';
        $values = '';

        foreach ($this->insert_data as $field => $value)
        {
            $fields .= ' ' . "`".str_replace("`","``",addslashes($field))."`" . ',';
            $values .= ' "' .$value . '",';
        }

        if($fields !== '' && $values !== '')
        {
            $this->fields = substr($fields, 0,-1);
            $this->values = substr($values, 0, -1);
            $this->query  = $this->query_begin . ' `' .  $this->table . '` ' . "(" .$this->fields. ") VALUES (" .$this->values. ")";
        }else
            {
                $this->error = true;
                echo  ('no values');
        }
    }
    /*$data[$field => $value] (array) – The data to query*/
    public function data( array $data)
    {
        if ($this->error) return false;

        $this->insert_data = $data;
        $this->query() ;
        try
        {
            $res = $this->pdo->prepare( $this->query);
            return  $res->execute();
        } catch (PDOException $e)
        {
            $this->error = true;
            echo  $e->getMessage();
            return false;
        }
    }

    public function get_compiled_insert()
    {
        return $this->query_begin . ' `' .  $this->table . '` ' . "(" .$this->fields. ") VALUES (" .$this->values. ")";
    }

}