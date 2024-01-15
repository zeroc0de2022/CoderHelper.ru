<?php
/**
 * Description of Database
 * @author zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
 * @date: 2021-08-04
 */

namespace Coderhelper\Components;

use PDO;

class Database {

    /**
     * Переменная для хранения состояния объекта соединения с БД
     * @var bool
     */
    private static bool $connect = false;

    /**
     * Объект для работы с БД
     * @var PDO
     */
    private static PDO $database;


    /**
     * Метод для получения объекта соединения с БД
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        if(!Database::$connect){
            $paramsPath = PUBLIC_HTML.'/config/db_params.php';
            $params = include_once($paramsPath);
            $dsn = "mysql:host={$params['dbhost']};dbname={$params['dbname']}";
            Database::$database = new PDO(
                $dsn,
                $params['dbuser'],
                $params['dbpassword'],
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            Database::$database->exec("set names utf8");
            Database::$connect = true;
        }
        return Database::$database;
    }
}
