<?php

namespace App\Libraries\Response;

class Response
{
    public string $message;

    public int $statusCode;

    public  $data;

    public Error $error;

    public  function InitailizeResponse()
    {
        $response = new Response();

        return $response;
    }

    public static function SetResponse(int $statusCode, $data, $error)
    {
        $response = new Response();
        $response->statusCode = $statusCode;
        $response->data = $data;
        $response->error = $error;
        switch ($statusCode) {
            case 200:
                $response->message = "Ok";
                break;
            case 201:
                $response->message = "Created";
                break;
            case 400:
                $response->message = "Bad Request";
                break;
            case 401:
                $response->message = "Unauthorized";
                break;
            case 403:
                $response->message = "Forbidden";
                break;
            case 404:
                $response->message = "Not found";
                break;
            case 405:
                $response->message = "Method Not Allowed";
                break;
            case 500:
                $response->message = "Internal Server Error";
                break;
            default:
                # code...
                break;
        }
        return $response;
    }
}



class Error
{

    public int $errorId;

    public ?string $errorMessage;


    public function __construct($errormsg = null)
    {
        $this->errorMessage = $errormsg;
    }
}
