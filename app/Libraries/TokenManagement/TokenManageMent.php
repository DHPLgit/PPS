<?php

namespace App\Libraries\TokenManagement;

use CodeIgniter\HTTP\RequestInterface;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\JWT;
use Exception;

class TokenManagement
{
    private $secretKey="";

    private $algorithm="";
    public function __construct(){
        $config = new \Config\App();
        $this->secretKey=$config->JWTSecretKey;
        $this->algorithm=$config->algorithm;
    }
    public function generate_jwt_token($user)
    {

        $token = array(
            'iss' => 'PPS',
            'iat' => time(),
            'exp' => time() + 36000, // 1hr
            'data' => $user
        );
        return JWT::encode($token, $this->secretKey,$this->algorithm);
    }

    public function verify_token(RequestInterface $request, $arguments = null)
    {
        $token = $this->extract_token($request->headers());

        if (!$token) {
            throw new Exception("Token is missing");
        }
        $res = $this->validate_token($token);
        return $res->data;
    }

    private function extract_token($headers)
    {
        if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $jwt_token)) {
            return $jwt_token[1];
        }
    }

    private function validate_token($jwt_token)
    {
        try {

            $data = JWT::decode($jwt_token, new Key($this->secretKey,$this->algorithm));
            return $data;
        } catch (ExpiredException $e) {
            throw new Exception('Token expired');
        } catch (SignatureInvalidException $e) {
            throw new Exception('Invalid token signature');
        } catch (BeforeValidException $e) {
            throw new Exception('Token not valid yet');
        } catch (Exception $e) {
            throw new Exception('Invalid token');
        }
    }
}
