<?php

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '../.env');
$dotenv->safeLoad();

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenControllers
{
    private $error = null;
    private $state;
    private $decoded_token;

    public function generate_token($user)
    {
        $key = $_ENV['JWT_SERVER_SECRET_KEY'];
        $payload_access = [
            'iss' => $user['login'],
            'uuid' => key_exists('id', $user) ?  $user['id'] : $user['user_id'],
            'type' => $user['statut'],
            'iat' => time(),
            'exp' => time() + $_ENV['JWT_SERVER_DURATION'],
            'sub' => 'AUTH'
        ];
        $payload_refresh = [
            'iss' => $user['login'],
            'uuid' => key_exists('id', $user) ?  $user['id'] : $user['user_id'],
            'type' => $user['statut'],
            'iat' => time(),
            'exp' => time() + $_ENV['JWT_SERVER_DURATION'],
            'sub' => 'REFRESH'
        ];
        $access = JWT::encode($payload_access, $key, $_ENV['JWT_SERVER_ALGORITHME']);
        $refresh = JWT::encode($payload_refresh, $key, $_ENV['JWT_SERVER_ALGORITHME']);

        return [
            "access" => $access,
            "refresh" => $refresh
        ];
    }

    public function setDecoded_token($token)
    {
        $this->decoded_token = $token;
    }

    public function getDecoded_token()
    {
        return $this->decoded_token;
    }

    public function decode_token($token)
    {
        try {
            $secret_key = $_ENV['JWT_SERVER_SECRET_KEY'];
            $jwt_key = $_ENV['JWT_SERVER_ALGORITHME'];

            $decoded = JWT::decode($token, new Key($secret_key, $jwt_key));
            $this->state = 'decoded';
            $this->setDecoded_token($decoded);

            return $decoded;
        } catch (Firebase\JWT\ExpiredException $err) {
            $this->state = 'expired';
            $this->error = [
                'code' => $err->getCode(),
                'message' => $err->getMessage()
            ];
            return "Expired token";
        } catch (Firebase\JWT\SignatureInvalidException $err) {
            $this->state = 'invalid';
            $this->error = [
                'code' => $err->getCode(),
                'message' => $err->getMessage()
            ];
            return null;
        } catch (Exception $err) {
            $this->state = 'error';
            $this->error = [
                'code' => $err->getCode(),
                'message' => $err->getMessage()
            ];
            return null;
        }
    }

    private function get_token_from_headers($request)
    {
        $headers = $request->getHeaders();
        if (isset($headers['Authorization'][0])) {
            $authorization_header = $headers['Authorization'][0];
            if (preg_match('/Bearer\s(\S+)/', $authorization_header, $matches)) {
                $token = $matches[1];
                return $token;
            }
            return null;
        }
        return null;
    }

    public function token_verify($token)
    {
        try {
            $decodedToken = $this->decode_token($token);
            // if ($decodedToken === "Expired token") {
            //     $this->state = 'expired';
            //     return false;
            // }

            if ($decodedToken === null) {
                $this->state = 'invalid';
                $this->error = [
                    'code' => 0,
                    'message' => 'invalid token'
                ];
                return false;
            }

            $this->setDecoded_token($decodedToken);

            return true;
        } catch (Exception $err) {
            $this->state = 'error';
            $this->error = [
                'code' => $err->getCode(),
                'message' => $err->getMessage()
            ];
            return false;
        }
    }

    public function access_token_verify($request)
    {
        $token = $this->get_token_from_headers($request);

        if ($token === null) {
            $this->state = 'non-existent';
            $this->error = [
                'code' => 0,
                'message' => 'unexistant token'
            ];
            return false;
        }

        $token_verified = $this->token_verify($token);
        if (!$token_verified) return $token_verified;

        if ($this->decoded_token->sub !== 'AUTH') return false;

        return $token_verified;
    }

    public function get_error()
    {
        return $this->error;
    }

    public function get_state()
    {
        return $this->state;
    }
}
