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
        }
        $response = $this->$method($request, $response, $args);
        if (!is_array($response)) {
            throw new \Exception('Your response must be an array');
        }
        if (array_key_exists('code', $response)) {
            throw new \Exception('the key code is absent');
        }
        return $response;
    }
}
