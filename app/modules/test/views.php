<?php

/**
 * To declare your view, don't forget to extends it to the main ViewInterface.
 * */
class TestView extends ViewInterface
{
    public function __construct()
    {
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
        return ['code' => 200, 'message' => "Tout vas bien.", "args" => $args];
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
