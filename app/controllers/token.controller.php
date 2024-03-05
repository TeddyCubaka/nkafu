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

    private function find_user($user_data, $db)
    {
        $stmt = $db->prepare('SELECT * FROM sys_user WHERE login = ?');
        $stmt->execute([$user_data['login']]);

        $user = $stmt->fetch();

        if (!$user) return null;

        if (!password_verify($user_data['pwd'], $user['pwd'])) return null;

        if (isset($user['is_active'])) {
            if ((int) $user['is_active'] !== 1) return 'UNACTIVATE';
            return $user;
        }
    }

    public function encode_jwt_payload($jwt_playload)
    {
        $secret_key = $_ENV['JWT_SERVER_SECRET_KEY'];
        return JWT::encode($jwt_playload, $secret_key, $_ENV['JWT_SERVER_ALGORITHME']);
    }

    public function generate_token($user_data, $db)
    {
        try {
            $user = $this->find_user($user_data, $db);
            if (!$user) return [
                'code' => 404,
                'message' => 'user not found'
            ];
            if ($user == 'UNACTIVATE') return [
                'code' => 404,
                'message' => 'user not activate'
            ];
            $payload_access = [
                'iss' => $user['login'],
                'uuid' =>  $user['id'],
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

            $access = $this->encode_jwt_payload($payload_access);
            $refresh = $this->encode_jwt_payload($payload_refresh);

            return [
                'code' => 200,
                'message' => 'le token est prêt',
                'token' => [
                    "access" => $access,
                    "refresh" => $refresh
                ]
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
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

    public function get_token_from_headers($request)
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
        if ($token === null) {
            $this->state = 'non-existent';
            $this->error = [
                'code' => 0,
                'message' => 'unexistant token'
            ];
            return false;
        }
        try {
            $decodedToken = $this->decode_token($token);

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

    public function access_token_verify($token)
    {
        $token_verified = $this->token_verify($token);
        if (!$token_verified) return $token_verified;

        if ($this->decoded_token->sub !== $_ENV['JWT_ACCESS_SUB_TAG']) return false;

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

    public function refresh_token_verify($token)
    {
        $token_verified = $this->token_verify($token);
        if (!$token_verified) return $token_verified;

        if ($this->decoded_token->sub !== $_ENV['JWT_REFRESH_SUB_TAG']) return false;

        return $token_verified;
    }

    public function verify_token_type($token)
    {
        $token_verified = $this->token_verify($token);
        if (!$token_verified) return $token_verified;

        if ($this->decoded_token->sub == $_ENV['JWT_REFRESH_SUB_TAG']) $data['type'] = 'refresh';
        else $data['type'] = 'access';

        return $data;
    }
}
