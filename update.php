<?php

Class UPDATE extends ORM
{
    protected $update_data;
    protected $update_query;

    /*$table (string) – The table to query*/
    public function __construct($pdo, $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->query_begin = "UPDATE ";
    }

    /*$data[$field => $value] (array) – The data to query*/
    public function set(array $data)
    {
        if(count($data) < 1)
        {
            $this->error = true;
            echo ('Incorrectly entered data in set');
            return $this;
        }
        $this->update_data = $data;
        $update_query = '';
        $count = count($data);
        $i = 0;
        foreach ($this->update_data as $field => $value)
        {
            $update_query .= ' `' .addslashes($field) . '` =  "' .addslashes($value) . '"' ;
            if($i < $count - 1)
            {
                $update_query .= ',';
            }
            $i++;
        }

        $this->update_query = $update_query;
        return $this;
    }

    /*$data[$field => $value] (array) – The data to query*/
    public function where(array $data)
    {
        if(count($data) < 1)
        {
            $this->error = true;
            echo  ('Incorrectly entered data in where');
            return $this;
        }
        if($this->where == '')
        {
            $where = 'WHERE ';
        }else
        {
            $where = $this->where . ' AND  ';
        }

        $count = count($data);
        $i = 0;
        foreach ($data as $key => $val)
        {
            $where .= '`' . addslashes($key) . '` = "'.addslashes($val).'"';
            if($i < $count - 1)
            {
                $where .= ' AND ';
            }
            $i++;

        }

        $this->where = $where;
        return $this;
    }

    /*$limit (int) – The LIMIT clause*/
    /*$offset (int) – The OFFSET clause*/
    public function limit($limit, $offset = 0)
    {
        if($limit < 0)
        {
            $this->error = true;
            echo ('Incorrectly entered data in limit');
            return $this;
        }
        $limit = (int)$limit;
        $offset = (int)$offset;

        if($limit && $offset)
        {
            $this->limit = 'LIMIT'. $offset . ',' . $limit ;
        }else if( $limit )
        {
            $this->limit = 'LIMIT'. $limit ;
        }
        return $this;
    }

    public function result()
    {

        if ($this->error) return false;

        $this->query() ;
        try
        {
           $this->pdo->query($this->query);
            return  true;
        } catch (PDOException $e)
        {
            $this->error = true;
            echo $e->getMessage();
            return false;
        }
    }


    protected function query()
    {
        $this->query = $this->query_begin . ' `' . addslashes($this->table). '` SET ';
        $this->query .= $this->update_query . ' ';
        if($this->where)
        {
            $this->query .= $this->where . ' ';
        }
        if($this->limit)
        {
            $this->query .= $this->limit;
        }

    }

    public function get_compiled_update()
    {
        $this->query() ;
        return $this->query;
    }
}