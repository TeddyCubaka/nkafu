<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// this file include all necessary files in the project.
require 'manager.php';

class Router
{
    private $app;
    private $db;

    public function __construct($app, $db)
    {
        $this->app = $app;
        $this->db = $db;
    }

    public static function autoload($module): array
    {
        /*
            this method takes care to include the the requested module.
        */
        $module_path = __DIR__ . '/modules/' . str_replace('\\', '/', $module);

        if (!is_dir($module_path)) {
            throw new \Exception("The folder $module do not exist.");
        }

        $url_path = $module_path . '/urls.php';
        $views_path = $module_path . '/views.php';

        if (file_exists($url_path)) {
            require $url_path;
            require $views_path;
            return $urlpatterns;
        } else {
            throw new \Exception("The file $url_path do not exist.");
        }
    }

    private function req_response($response, array $data)
    {
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($data['code']);
    }

    public function routes()
    {
        $this->app->options('/{routes:.+}', function ($request, $response, $args) {
            return $response;
        });

        $this->app->add(function ($request, $handler) {
            $response = $handler->handle($request);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        });

        $this->app->map(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], '/api/v1/{module}[/{first_arg}[/{second_arg}[/{third_arg}]]]', function (Request $request, Response $response, array $args) {
            try {
                try {
                    $module_urls = self::autoload($args['module']);
                } catch (\Exception $e) {
                    $data = [
                        'message' => 'Cette route est introuvable',
                        'code' => 404,
                        'error' => [
                            'code' => 404,
                            'message' => $e->getMessage()
                        ]
                    ];
                    return $this->req_response($response, $data);
                }
                $method = strtolower($request->getMethod());
                $route = implode('/', $args);
                if (!key_exists($route, $module_urls)) {
                    $data = [
                        'message' => 'page non trouvé',
                        'code' => 404,
                        'error' => [
                            'code' => 404,
                            'message' => ''
                        ]
                    ];
                    return $this->req_response($response, $data);
                }
                $module_class = $module_urls[$route];

                if (class_exists($module_class)) {
                    $module = new $module_class($this->db, $this->app);
                } else {
                    $data = [
                        'message' => 'une erreur s\'est produite',
                        'code' => 500,
                        'error' => [
                            'code' => 500,
                            'message' => 'class ' . $module_class . ' not found'
                        ]
                    ];
                    return $this->req_response($response, $data);
                }

                if (!method_exists($module, 'dispatcher')) {
                    $data = [
                        'message' => 'cette méthode n\'est pas prise en charge',
                        'code' => 405,
                        'error' => [
                            'code' => 405,
                            'message' => 'method not allow'
                        ]
                    ];
                    return $this->req_response($response, $data);
                }
                $payload = $module->dispatcher($request, $response, $args, $method);

                return $this->req_response($response, $payload);
            } catch (Exception $err) {
                return [
                    'code' => 500,
                    'message' => 'Une erreur s\'est produite',
                    'error' => $err->getMessage()
                ];
            }
        });

        $this->app->map(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], '/[{routes:.+}[/]]', function (Request $request, Response $response, array $args) {
            $data = [
                'message' => 'page non trouvé',
                'code' => 404,
                'error' => [
                    'code' => 404,
                    'message' => 'page not founded'
                ]
            ];
            return $this->req_response($response, $data);
        });
    }
}
