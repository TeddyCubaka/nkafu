<?php
class Sys_user_controller
{
    private $db;
    private $user_serializer;
    public function __construct($db)
    {
        $this->db = $db;
        $this->user_serializer = new Sys_user_serializer($db);
    }

    public function find_many($params)
    {
        return $this->user_serializer->find($params, $many = true);
    }
}
