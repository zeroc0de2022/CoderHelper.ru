<?php
/**
 * Description of Common
 * @author zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
 * @date 2021-04-08
 */
declare(strict_types = 1);

namespace Coderhelper\Models;

use Coderhelper\Components\Database;

class Common {
    
    /**
    * Обрезает строку до определённого количества символов не разбивая слова.
    * Поддерживает многобайтовые кодировки.
    * @param string $str строка
    * @param int $length длина, до скольки символов обрезать
    * @param string $postfix постфикс, который добавляется к строке
    * @param string $encoding кодировка, по-умолчанию 'UTF-8'
    * @return string обрезанная строка
    */
    public static function mbCutString(string $str, int $length, string $postfix='...', string $encoding='UTF-8'): string
    {
        $str = trim(strip_tags($str));
        if (mb_strlen($str, $encoding) <= $length) {
            $str = trim($str) ;
        }
        else {
            $tmp = mb_substr($str, 0, $length, $encoding);
            $str = mb_substr($tmp, 0, mb_strripos($tmp, ' ', 0, $encoding), $encoding) . $postfix;
        }
        return $str;
    }


    /***
     * return sum of total items
     * @param string $id
     * @param string $table
     * @return mixed
     */
    public static function getTotalItems(string $id, string $table): int
    {
        $database = Database::getConnection();
        $result = $database->query("SELECT count(`$id`) AS `count` FROM `$table`");
        $row = $result->fetch();
        return $row['count'] ?? 0;
    }
    
    
    /**
     * return ru date. 
     * example: 3 мая в 07:19
     */   
    public static function pubDateToString($pubDate): string
    {
        $dateArray = explode('-', date("Y-n-j-H:i", $pubDate));
        $monthRu = ['', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
        $dateArray[1]  = intval($dateArray[1]);        
        return $dateArray[2]." ".$monthRu[$dateArray[1]]." ".$dateArray[0]." в ".$dateArray[3];
    }









    
}
