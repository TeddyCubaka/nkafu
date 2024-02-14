<?php

class TestView extends ViewInterface
{
    public function __construct()
    {
    }

    public function GET($request, $response, $args)
    {
        return ['code' => 200, 'message' => "Tout vas bien.", "args" => $args];
    }
}

class AlphaView extends ViewInterface
{
    public function __construct()
    {
    }

    public function GET($request, $response, $args)
    {
        return ['code' => 200, 'message' => "tout est OK", "data" => []];
    }
}
