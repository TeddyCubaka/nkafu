<?php
class ViewInterface
{
    private static $environment;
    public function __construct()
    {
        self::$environment = $_ENV['ENVIRONMENT'];
    }
    public function dispatcher($request, $response, $args)
    {
        $method = strtolower($request->getMethod());
        if (!method_exists($this, $method)) {
            return [
                'code' => 405,
                'message' => 'cette route ne prends pas en charge cette méthode',
                'error' => [
                    'code' => 404,
                    'message' => 'Method not allow'
                ]
            ];
        } else return $this->$method($request, $response, $args);
    }
}
