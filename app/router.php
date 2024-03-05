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

    /**
     * Simple method to format erreur data response
     * */
    public function get_error_data($err)
    {
        $error = [
            'code' => $err->getCode(),
            'message' => $err->getMessage(),
        ];

        if ($_ENV['ENVIRONMENT'] == 'DEV') {
            $error['file'] = $err->getFile();
            $error['line'] = $err->getLine();
            $error['trace'] = $err->getTrace();
        }

        return $error;
    }
    public static function autoload($module): array
    {
        /*
            this method takes care to include the the requested module.
        */

        // this variable define the path of the module
        $module_path = __DIR__ . '/modules/' . str_replace('\\', '/', $module);

        // this condition verify if the folder exist
        if (!is_dir($module_path)) {
            throw new \Exception("The folder $module do not exist.");
        }

        // these two variable verify take the two files of a module.
        $url_path = $module_path . '/urls.php';
        $views_path = $module_path . '/views.php';

        // this verify if these two files realy exist
        if (file_exists($url_path) && file_exists($views_path)) {
            require $url_path;
            require $views_path;
            return $urlpatterns;
        } else {
            /**
             * if one of these two file don't exist. The app return this error to the dev.
             */
            throw new \Exception("The file $url_path or $views_path do not exist.");
        }
    }

    private function req_response($response, array $data)
    {
        /**
         * This a global reponse interface for the app. 
         * As you can see, it a private method of this classes you can't use it elsewhere.
         */
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($data['code']);
    }

    public function routes()
    {
        /**
         * This a middleware which handle the cors conflit between your app and the client app.
         * You can customise it by your self.
         */
        $this->app->add(function ($request, $handler) {
            $response = $handler->handle($request);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        });

        $this->app->get('/', function (Request $request, Response $response, array $args) {
            $url = __DIR__ . '/IHM/index.php';
            return $response->withHeader('Location', $url)->withStatus(302);
        });
        /**
         * Well, well. This route is the main route for our all application. By default it takes the api/v1 as the default base url for the app.
         * This mean the app url are look like this http://localhost:8080/api/v1/module.
         * this route supports by default 4 possible sub argumets. Not that you can add more if you want.
         * The 4 routes are module first_arg second_arg third_arg,
         * To take a visual example look at this url 
         
            http://localhost:8080/api/v1/module/first_arg/second_arg/third_arg

         * I think you have now an idea of what say
         * If you want to specify an other base url, like the api/v2, just duplicate the middleware. 
         * So good adventure
         */
        $this->app->map(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], '/api/v1/{module}[/{first_arg}[/{second_arg}[/{third_arg}]]]', function (Request $request, Response $response, array $args) {
            try {
                try {
                    /**
                     * this code code autoload the module folder. It takes the url.php and views.php files from you module. 
                     * as we saw above
                     */
                    $module_urls = self::autoload($args['module']);
                } catch (\Exception $e) {
                    $data = [
                        'message' => 'cette route est introuvable',
                        'code' => 404,
                        'error' => [
                            'code' => 404,
                            'message' => $e->getMessage()
                        ]
                    ];
                    return $this->req_response($response, $data);
                }
                // Let take the client request method. By using the the slim Response object
                $method = strtolower($request->getMethod());

                // this variable take the route which come after the base url. By default it's api/v1
                $route = implode('/', $args);

                /**
                 * This code verify if the route which come after the base url existe in the urlpattern in urls.php file of the module.
                 */
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
                // if the url exist in urlpattern, we stock is name in this variable.
                $module_class = $module_urls[$route];

                if (class_exists($module_class)) {
                    /**
                     * If the class name exist in our app, we instantiate it as $module object
                     */
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

                if (!($module instanceof ViewInterface)) {
                    /**
                     * This conditionnal verify the module object is an instance of the ViewInterface
                     */
                    $data = [
                        'message' => 'cette route est introuvable',
                        'code' => 405,
                        'error' => [
                            'code' => 405,
                            'message' => $_ENV['ENVIRONMENT'] == "DEV" ?  'This class exist but is not an instance of the ViewInterface. If you want to make it an available route view, instantiate it.' : 'path not allowed. contact the development team for more'
                        ]
                    ];
                    return $this->req_response($response, $data);
                }
                /**
                 * If the module object is an instance of the the ViewInterface, The we can call the dispatcher method
                 */
                $payload = $module->dispatcher($request, $response, $args);

                /**
                 * Finally, there is the app response.
                 * */
                return $this->req_response($response, $payload);
            } catch (Exception $err) {
                $data = [
                    'code' => 500,
                    'message' => 'Une erreur s\'est produite',
                    'error' => $this->get_error_data($err)
                ];
                return $this->req_response($response, $data);
            } catch (TypeError $err) {
                $data = [
                    'code' => 500,
                    'message' => 'Une erreur s\'est produite',
                    'error' => $this->get_error_data($err)
                ];
                return $this->req_response($response, $data);
            }
        });

        /**
         * This middleware handle all the not founded page or route.
         */
        $this->app->map(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], '/{routes:.+}[/]', function (Request $request, Response $response, array $args) {
            $data = [
                'message' => 'page non trouvé',
                'code' => 404,
                'error' => [
                    'code' => 404,
                    'message' => 'page not found'
                ]
            ];
            return $this->req_response($response, $data);
        });
    }
}
