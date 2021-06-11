<?php

class database
{
    private $host = DBS_HOST;
    private $user = DBS_USER;
    private $pass = DBS_PASS;
    private $dbase = DBS_NAME;

    private $db_handler, $statement;

    public function __construct()
    {
        $data_source = 'mysql:host=' . $this->host . ';dbname=' . $this->dbase;
        $option = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        try {
            $this->db_handler = new PDO($data_source, $this->user, $this->pass, $option);
        } catch (PDOException $ex) {
            die($ex->getMessage());
        }
    }

    public function get_all($sql)
    {
        $this->statement = $this->db_handler->prepare($sql);
        $this->statement->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function query($query)
    {
        $this->statement = $this->db_handler->prepare($query);
    }

    public function bind($param, $vals, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($vals):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($vals):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($vals):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }
        $this->statement->bindValue($param, $vals, $type);
    }

    public function execute_query()
    {
        $this->statement->execute();
    }

    public function result_set()
    {
        $this->execute_query();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single_result()
    {
        $this->execute_query();
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function count_row()
    {
        return $this->statement->rowCount();
    }
}
