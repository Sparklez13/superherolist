<?php
namespace SuperHeroList\db;

use PDO;
use PDOException;

class Db
{
    private static ?PDO $pdo = null;

    public function __construct($c)
    {
        if (self::$pdo === null) {
            $dsn = "pgsql:host={$c['host']};port={$c['port']};dbname={$c['dbname']}";
            self::$pdo = new PDO($dsn, $c['user'], $c['password'], [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
    }

    public static function sql(string $statement = '')
    {
        return new SQL($statement);
    }

    /**
     * Выполнить запрос с привязкой параметров
     */
    public static function execute(string $statement, array $params = [])
    {        
        $stmt = self::$pdo->prepare($statement);
        // var_dump($statement);
        // var_dump($params);
        if (!empty($params)) {
            // Нужно подставить к ключам массива параметров ":"
            // чтобы использовать в вызове подготовленного запроса
            $paramsKeys = array_map(fn ($k) => ":{$k}", array_keys($params));
            $params = array_combine($paramsKeys, $params);  
        }
        
        $stmt->execute($params);
        /**
         * Если количество затронутых рядов > 0,
         * то вернуть массив (пустой при INSERT, UPDATE, DELETE),
         * иначе null
         */
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        } else return null;
    }
}