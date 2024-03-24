<?php
/**
 * Copyright (c) 2022
 * wbs GmbH
 * Mail: it@wbs.de
 * Fon: +49 (0)4488-52540 25
 * All rights reserved.
 */
namespace wbs\Framework\Api;

class Request
{

    private string $request_uri;
    private array $uri;

    private string $parameter1;
    private string $parameter2;
    private string $parameter3;

    public function __construct()
    {
        $this->request_uri = $_SERVER['REQUEST_URI'];
        $this->parseRequest($this->request_uri);
    }


    /**
     * JSON Input aus Request als Array
     *
     * @param bool $associative
     * @return mixed
     */
    public function getPHPInput(bool $associative = false){
        $json = file_get_contents('php://input','r');
        return json_decode($json,$associative);
    }
    /**
     * Input aus dem Request
     *
     * @param bool $associative
     * @return string|false
     */
    public function getRarePHPInput(): bool|string
    {
        return file_get_contents('php://input','r');
    }

    public function parseRequest($request_uri): void
    {
        $this->parameter1 = '';
        $this->parameter2 = '';
        $this->parameter3 = '';

        $uri = parse_url($request_uri, PHP_URL_PATH);
        $this->uri = explode('/', $uri);

        if(count($this->uri) > 2){
            $this->parameter1 = $this->uri[2];
        }
        if(count($this->uri) > 3){
            $this->parameter2 = $this->uri[3];
        }
        if(count($this->uri) > 4){
            $this->parameter3 = $this->uri[4];
        }
    }

    public function getRequestMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function getController(): string
    {
        return ucwords($this->parameter1);
    }

    public function getDetail(): string
    {
        return ucwords($this->parameter2);
    }

    public function getID(): string
    {
        return $this->parameter3;
    }
    public function getParameter():array{
        return $this->uri;
     }

    /**************************************************************************
     * G E T T E R / S E T T E R
     *************************************************************************/
    /**
     * @return mixed
     */
    public function getRequestUri(): mixed
    {
        return $this->request_uri;
    }

}