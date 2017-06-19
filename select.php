<?php

Class SELECT extends ORM
{
    protected $select_fields;

    /*$fields (string) – Field names*/
    /*$distinct (string) – For distinct select*/
    public function __construct($pdo, $fields, $distinct)
    {
        $this->pdo = $pdo;
        if(!$fields)
        {
            $fields = '*';
        }

        $this->select_fields = $fields;
        $this->query_begin = "SELECT ";

        if(mb_strtolower($distinct) === 'distinct')
        {
            $this->query_begin .=  $distinct;
        }
    }
    /*$table (string) – The table to query*/
    public function from($table)
    {
        if(!$table)
        {
            $this->error = true;
            echo ('Incorrectly entered data in from');
            return $this;
        }
        $this->table = $table;
        return $this;
    }

    /*$table (string) – The table to join query*/
    /*$data (array) – The JOIN ON condition*/
    /*$type (string) – The JOIN type*/
    public function join($table, array $on_data, $type = '')
    {
        if(!is_array($on_data) || count($on_data) < 1)
        {
            $this->error = true;
            echo ('Incorrectly entered data in join');
            return $this;
        }

        $join = '';
        $types = array('inner', 'outer', 'left', 'right');
        if(in_array(mb_strtolower($type), $types))
        {
            $join = $type;
        }
        $join .= ' JOIN ' .  ' `' . addslashes($table). '` ON';

        $count = count($on_data);
        $i = 0;
        foreach ($on_data as $key => $val)
        {
            $join .=  addslashes($key) . ' = '.addslashes($val);
            if($i < $count - 1)
            {
                $join .= ' AND ';
            }
            $i++;
        }

        $this->join = $join;
        return $this;
    }

    /*$data[$field => $value] (array) – The data to query*/
    public function where(array $data)
    {
        if(!is_array($data) || count($data) < 1)
        {
            $this->error = true;
            echo ('Incorrectly entered data in where');
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

    /*$fields (string) – Field names*/
    public function group_by($field)
    {
        if($field == '')
        {
            $this->error = true;
            echo ('Incorrectly entered data in group_by');
            return $this;
        }
        if(!$this->group)
        {
            $this->group = 'GROUP BY ';
        }else if ($field)
        {
            $this->group .= ',';
        }

        $this->group .= ' "'.addslashes($field).'"';
        return $this;
    }

    /*$fields (string) – Field names*/
    /*$sort (string) – ASC or DESC sort*/
    public function order_by($field, $sort = 'ASC')
    {
        if($field == '')
        {
            $this->error = true;
            echo ('Incorrectly entered data in order_by');
            return $this;
        }

        if(!$this->order)
        {
            $this->order = 'ORDER BY ';
        }else if($field)
        {
            $this->order .= ', ';
        }

        $this->order .= ' "'.addslashes($field).'" ';
        if($sort === ' DESC')
        {
            $this->order .= ' DESC';
        }else
            {
            $this->order .= ' ASC';
        }
        return $this;
    }

    /*$limit (int) – The LIMIT clause*/
    /*$offset (int) – The OFFSET clause*/
    public function limit($limit, $offset = 0)
    {
        if(!$limit || $limit < 0)
        {
            $this->error = true;
            echo ('Incorrectly entered data in limit');
            return $this;
        }
        $limit = (int)$limit;
        $offset = (int)$offset;

        if( $offset > 0)
        {
            $this->limit = 'LIMIT'. $offset . ',' . $limit ;
        }else
            {
            $this->limit = 'LIMIT'. $limit ;
        }
        return $this;
    }

    protected function query()
    {
        $this->query = $this->query_begin . ' ';
        $this->query .=  $this->select_fields . ' FROM ';
        $this->query .= ' `' . addslashes($this->table). '`' . ' ';
        if($this->join)
        {
            $this->query .= $this->join . ' ';
        }
        if($this->where)
        {
            $this->query .= $this->where . ' ';
        }
        if($this->group)
        {
            $this->query .= $this->group . ' ';
        }
        if($this->order)
        {
            $this->query .= $this->order . ' ';
        }
        if($this->limit)
        {
            $this->query .= $this->limit;
        }
    }

    public function result()
    {
        if ($this->error) return false;

        $this->query();
        $res = $this->pdo->query($this->query);

        if(!$res) return false;

        $result = array();
        try
        {
            while ($row = $res->fetch(PDO::FETCH_OBJ))
            {
                array_push($result, $row);
            }
            return $result;
        } catch (PDOException $e)
        {
            $this->error = true;
            echo $e->getMessage();
            return false;

        }
    }

    public function result_array()
    {
        if ($this->error) return false;

        $this->query();
        $res = $this->pdo->query($this->query);

        if(!$res) return false;

        $result = array();
        try
        {
            while ($row = $res->fetch(PDO::FETCH_ASSOC))
            {
                array_push($result, $row);
            }
            return $result;
        } catch (PDOException $e)
        {
            $this->error = true;
            echo $e->getMessage();
            return false;

        }
    }

    public function row()
    {
        if ($this->error) return false;

        $this->query();
        $res = $this->pdo->query($this->query);

        if(!$res) return false;

        try
        {
            $res = $res->fetch(PDO::FETCH_ASSOC);
            return $res;
        } catch (PDOException $e)
        {
            $this->error = true;
            echo $e->getMessage();
            return false;

        }
    }

    public function get_compiled_select()
    {
        $this->query() ;
        return $this->query;
    }

}