<?php
namespace SuperHeroList\app;

use SuperHeroList\ViewRenderer;

/*
Простой класс для роутинга запросов
*/

class Router
{
    private static array $routes = [];

    /**
     * Shortcut метод для регистрации GET-запроса
     */
    public static function get (string $route, string $controller, string $action)
    {
        self::register($route, 'GET', $controller, $action);
    }

    /**
     * Shortcut метод для регистрации POST-запроса
     */
    public static function post (string $route, string $controller, string $action)
    {
        self::register($route, 'POST', $controller, $action);
    }

    public static function delete (string $route, string $controller, string $action)
    {
        self::register($route, 'DELETE', $controller, $action);
    }
    /**
     * Зарегистрировать запрос
     */
    public static function register (
        string $route,
        string $method,
        string $controller,
        string $action
        )
    {
        self::$routes["{$route}|{$method}"] = [
            'controller' => $controller,
            'action'     => $action
        ];
    }
        
    public static function controller ($route)
    {
        if (isset(self::$routes[$route]['controller'])) {
            return new (self::$routes[$route]['controller'])(new ViewRenderer('templates'));
        } else throw new \Exception(static::class . " exception. There is no such route");
        
    }

    public static function action ($route)
    {
        if (isset (self::$routes[$route]['action'])) {
            return self::$routes[$route]['action'];
        } else throw new \Exception(static::class . " exception. There is no such action");
    }
}