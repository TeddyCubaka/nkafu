<?php
class AuthView extends ViewInterface
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
        $token  = $this->token_ws->generate_token(['login' => 808080800, 'user_id' => '345']);
        return [
            'code' => 200,
            'message' => 'le token est prêt',
            'token' =>  $token
        ];
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

            $token  = $this->token_ws->generate_token($data);
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
