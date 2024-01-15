<?php
/**
 * Description of Debug
 * @author: zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
 * @date 2024-01-14
 */
declare(strict_types = 1);

namespace Coderhelper\Models;

/**
 * Class Debug
 */
class Debug
{

    /**
     * Путь к файлу debug.log
     * @var string
     */
    private static string $logFile = 'debug.log';

    /**
     *  Создает переменные из значений массива с начальными значениями true и запускает методы
     * @param string|null $var - переменная для вывода на экран
     * @param array $actions - массив с ключами 'print', 'pre', 'debag', 'caller'
     * @param bool $stop - останавливает выполнение скрипта
     * @return void
     */
    public static function debug(mixed $var = null, array $actions = ['print', 'debug', 'caller', 'trace'], bool $stop = false): void
    {

        extract(array_combine($actions, array_fill(0, count($actions), true)));

        if(isset($print)) self::printVar($var);
        if(isset($debug)) self::debagInfo($debug);
        if(isset($caller)) self::callerInfo($caller);
        if(isset($trace)) self::traceInfo();
        self::logToFile($var) ;
        if($stop) {
            die();
        }
    }

    /**
     * Выводит на экран значение переменной $var в тегах <pre>
     * @param mixed $var
     * @param string $print_name
     */
    private static function printVar(mixed $var = '', string $print_name = 'Print'): void
    {
        echo "<h2>$print_name</h2><pre>";
        print_r($var);
        echo "</pre>";
    }

    /**
     * Выводит на экран информацию о файле, строке и методе, из которого был вызван метод debagInfo()
     * @param bool|null $debag - выводить информацию или нет
     * @return void
     */
    private static function debagInfo(bool $debag = true): void
    {
        if($debag) {
            $callerInfo = self::getCallerInfo();
            echo "<h2>Debug</h2>";
            echo "<br><strong>".$callerInfo['file']."</strong>";
            echo "<br><strong>".$callerInfo['line']."</strong>";
            echo "<br><strong>".$callerInfo['method']."</strong>";
        }
    }

    /**
     * Выводит на экран информацию о файле и строке, из которого был вызван метод debagInfo()
     * @param bool|null $caller - выводить информацию или нет
     * @return void
     */
    private static function callerInfo(bool $caller = true): void
    {
        if($caller) {
            $callerInfo = self::getCallerInfo(4);
            if($callerInfo) {
                echo "<h2>caller</h2>";
                echo "<br><strong>{$callerInfo['file']}</strong>";
                echo "<br><strong>{$callerInfo['line']}</strong>";
            }
        }
    }

    /**
     * Записывает значение переменной $var в файл debug.log
     * @param mixed $var - переменная для записи в файл
     * @return void
     */
    private static function logToFile(mixed $var = null): void
    {
        if($var !== null) {
            $logMessage = date('Y-m-d H:i:s').': '.print_r($var, true).PHP_EOL;
            file_put_contents(self::$logFile, $logMessage, FILE_APPEND);
        }
    }

    /**
     * Возвращает информацию о файле, строке и методе, из которого был вызван метод debagInfo()
     * @param int $depth
     * @return array
     */
    private static function getCallerInfo(int $depth = 1): array
    {
        //DEBUG_BACKTRACE_IGNORE_ARGS - игнорирует аргументы функций
        $trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, $depth + 1);
        $callerInfo = $trace[$depth] ?? null;
        return [
            'file' => $callerInfo['file'] ?? '',
            'line' => $callerInfo['line'] ?? '',
            'method' => $callerInfo['function'] ?? '',

        ];
    }

    /**
     *  Выводит на экран информацию о последовательности вызовов функций
     *  @return void
     */
    public static function traceInfo(): void
    {
        self::printVar(debug_backtrace(), 'trace' );
    }
}
