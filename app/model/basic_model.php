<?php

class basic_model
{
    private $dbase;

    public function __construct()
    {
        $this->dbase = new database;
    }

    public function get_query($sql)
    {
        $this->dbase->query($sql);
        return $this->dbase->result_set();
    }

    public function get_table($table)
    {
        $query = "SELECT * FROM $table";
        $this->dbase->query($query);
        return $this->dbase->result_set();
    }

    public function get_where($table, $field, $where, $like = false)
    {
        if ($like) {
            $query = "SELECT * FROM $table WHERE $field LIKE :field";
            $this->dbase->query($query);
            $this->dbase->bind('field', $where);
            return $this->dbase->result_set();
        } else {
            $query = "SELECT * FROM $table WHERE $field = :field";
            $this->dbase->query($query);
            $this->dbase->bind('field', $where);
            return $this->dbase->single_result();
        }
    }

    public function delete($table, $field, $where)
    {
        $query = "DELETE FROM $table WHERE $field = :field";
        $this->dbase->query($query);
        $this->dbase->bind('field', $where);
        $this->dbase->execute_query();
        return ($this->dbase->count_row() > 0) ? true : false;
    }

    public function insert($table, $data = [])
    {
        $query = 'INSERT INTO ' . $table . ' (' . implode(', ', array_keys($data)) . ') VALUES (' . implode(', ', preg_filter('/^/', ':', array_keys($data))) . ')';
        $this->dbase->query($query);
        foreach ($data as $key => $val) {
            $this->dbase->bind($key, $val);
        }
        $this->dbase->execute_query();
        return ($this->dbase->count_row() > 0) ? true : false;
    }

    public function update($table, $data = [], $where = [])
    {
        $query = 'UPDATE ' . $table . ' SET ' . implode(', ', preg_filter('/.+/', '$0 = :$0', array_keys($data))) . ' WHERE ' . implode(' AND ', preg_filter('/.+/', '$0 = :$0', array_keys($where)));
        $this->dbase->query($query);
        foreach ($data as $key => $val) {
            $this->dbase->bind($key, $val);
        }
        foreach ($where as $key => $val) {
            $this->dbase->bind($key, $val);
        }
        $this->dbase->execute_query();
        return ($this->dbase->count_row() > 0) ? true : false;
    }

    public function row_count($query)
    {
        $this->dbase->query($query);
        $this->dbase->execute_query();
        return $this->dbase->count_row();
    }
}
