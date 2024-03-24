<?php

namespace wbs\Framework\Api;

use wbs\Framework\Config\ENV;
use wbs\Framework\Json\ApiResponse;
use wbs\Framework\WbsClass;

class ApiController extends WbsClass
{

    /**
     * @throws \Exception
     */
    public function run(string $namespace_api_controller = 'App\Api\Controller\\'): void
    {

        $api_request = $this->getRequest();

        $auth_user = $_SERVER['PHP_AUTH_USER'] ?? '';
        $auth_password = $_SERVER['PHP_AUTH_PW'] ?? '';

        /**
         * If you dont want to check for user & password,
         * simply leave the ENV Parameter empty
         */
        if ($this->wbs()->env(ENV::API_SERVER_USER) !== $auth_user ||
            $this->wbs()->env(ENV::API_SERVER_USER) !== $auth_password) {
            $this->response()->sendNoPermission();
        }

        /**
         * Namespace for alle Api Controller
         */
        if (!class_exists($namespace_api_controller . $api_request->getController())) {
            $this->response()->sendFailure(
                404, "Controller nicht vorhanden"
            );
        }

        $classname = $namespace_api_controller . $api_request->getController();
        $controller = new $classname($this->wbs());
        $controller->processRequest($api_request);
    }

    /**
     * Return NEW Instance of the API Request
     */
    public function getRequest(): Request
    {
        return new Request();
    }


    public function response(): ApiResponse
    {
        return new ApiResponse();
    }

}