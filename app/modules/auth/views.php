<?php
class AuthView extends ViewInterface
{
    private $db;
    private $token_ws;
    private $app_validator;
    public function __construct($db)
    {
        $this->db = $db;
        $this->token_ws = new TokenControllers();
        $this->app_validator = new Validator();
    }

    public function get($request, $response, $args)
    {
        $token = $this->token_ws->get_token_from_headers($request);
        $token_veficator  = $this->token_ws->access_token_verify($token);
        $state = $this->token_ws->get_state();

        if (!$token_veficator) {
            $error = $this->token_ws->get_error();
            return [
                'code' => 401,
                'message' => "le token n'est pas valide",
                "data" => [
                    "state" => $state,
                    "error" => $_ENV['ENVIRONMENT'] == 'DEV' ? $error : null
                ]
            ];
        }
        if ($state == "decoded") return [
            'code' => 200,
            'message' => "le token est encore valide",
            "data" => $this->token_ws->getDecoded_token()
        ];
    }

    public function post($request, $response, $args)
    {
        $request_body = $request->getParsedBody();
        if (!$this->app_validator->is_valid_request_body($request_body)) {
            return $this->app_validator->get_error();
        }

        if (!(key_exists('login', $request_body) || key_exists('pwd', $request_body))) {
            return [
                'code' => 400,
                'message' => 'certain clé sont manquantes',
                'error' => [
                    'code' => 400,
                    'message' =>  "Missing required key in request body"
                ]
            ];
        }
        $token  = $this->token_ws->generate_token($request_body, $this->db);
        return $token;
    }
}

class RefreshTokenView extends ViewInterface
{
    private $db;
    private $token_ws;
    public function __construct($db)
    {
        $this->db = $db;
        $this->token_ws = new TokenControllers();
    }

    public function post($request, $response, $args)
    {
        $token = $this->token_ws->get_token_from_headers($request);
        $token_veficator  = $this->token_ws->refresh_token_verify($token);
        $state = $this->token_ws->get_state();

        if (!$token_veficator) {
            $error = $this->token_ws->get_error();
            return [
                'code' => 401,
                'message' => "le token n'est pas valide",
                "data" => [
                    "state" => $state,
                    "error" => $_ENV['ENVIRONMENT'] == 'DEV' ? $error : null
                ]
            ];
        }
        if ($state == "decoded") {
            $decoded_token = $this->token_ws->getDecoded_token();

            $data = ['login' => $decoded_token->iss, 'user_id' => $decoded_token->uuid, 'statut' => $decoded_token->type];

            $token  = $this->token_ws->generate_token($data, $this->db);
            return [
                'code' => 200,
                'message' => 'le token a été reproduit',
                'token' =>  $token
            ];
        }
    }
}

class VerifyTokenView extends ViewInterface
{
    private $db;
    private $token_ws;
    public function __construct($db)
    {
        $this->db = $db;
        $this->token_ws = new TokenControllers();
    }

    public function get($request, $response, $args)
    {
        $token = $this->token_ws->get_token_from_headers($request);
        $token_veficator  = $this->token_ws->verify_token_type($token);
        $state = $this->token_ws->get_state();

        if (!$token_veficator) {
            $error = $this->token_ws->get_error();
            return [
                'code' => 401,
                'message' => "le token n'est pas valide",
                "data" => [
                    "state" => $state,
                    "error" => $_ENV['ENVIRONMENT'] == 'DEV' ? $error : null
                ]
            ];
        }

        if ($state == "decoded") return [
            'code' => 200,
            'message' => "le token est encore valide",
            "data" => $token_veficator
        ];
    }
}
