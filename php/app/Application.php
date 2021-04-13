<?php

namespace SuperHeroList\app;

use Exception;
use SuperHeroList\db\Db;
use SuperHeroList\Utils\Filter;
use SuperHeroList\ViewRenderer;

class Application
{
    public function __construct(private array $config)
    {
        $this->config = $config;
        $this->init();
    }

    /**
     * Присвоение переменных конфигурации
     */
    private function init()
    {
        $this->db = new Db($this->config['db']);
        $this->filter = new Filter;

        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $rawData = file_get_contents('php://input');

            $data = json_decode($rawData, true);
            // var_dump($data);
            $filtered = $this->filter->filter($data);
            // var_dump($filtered);
            Request::$data = $filtered;
        }
        include_once 'routes.php';
    }

    /**
     * Обработка запроса и генерация ответа для клиента
     */
    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $route = "{$path}|{$method}";
        /**
         * Обработка запроса и редирект на главную в случае ошибки
         */
        try {
            $this->handleRequest($route);
        } catch (Exception $e) {
            // TODO log
           echo json_encode([
               'success' => false,
               'message' => $e->getMessage()
            ]);
        //    header("Location: /index.php");

        }
    }

    private function handleRequest(string $route)
    {
        $controller = Router::controller($route);
        $action = Router::action($route);
        return $controller->$action();
    }
}
