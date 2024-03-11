<?php

/**
 * To declare your view, don't forget to extends it to the main ViewInterface.
 * */
class TestView extends ViewInterface
{
    private $db;
    private $user_ws;
    public function __construct($db)
    {
        $this->db = $db;
        $this->user_ws = new Sys_user_controller($db);
        /**
         * declare here all your initial state of your class or object.
         */
    }

    public function get($request, $response, $args)
    {
        /**
         * Note : the name of all method of the must conrespond to the http method. Namely, GET, PUT, POST, PATCH.
         * The method response must be an array.
         */
        $params = $request->getQueryParams();
        return ['code' => 200, 'message' => "Tout vas bien.", "data" => $params["data"]];
    }

    public function post($request, $response, $args)
    {
        return [
            'code' => 200,
            'message' => "Tout vas bien.",
            "data" => ['method' => 'post']
        ];
    }
}

class AlphaView extends ViewInterface
{
    public function __construct()
    {
    }

    public function get($request, $response, $args)
    {
        return ['code' => 200, 'message' => "tout est OK", "data" => []];
    }
}
