<?php
/**
 * Description of Tags
 * @author zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
 * @date 2021-04-08
 */

namespace Coderhelper\Models;
use Coderhelper\Components\Database;
use Exception;
use PDO;
use PDOException;

class Tags {
    const SHOW_TAGS_BY_DEFAULT = 24;
    const SHOW_QUESTIONS_BY_DEFAULT = 20;

    /**
     * return tag description by tag_name
     * @param string $tagName
     * @return mixed
     * @throws PDOException
     */
    public static function TagDescriptionByTagName(string $tagName): mixed
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT `tag_description`"
                    . " FROM `tags`"
                    . " WHERE `tag_name`=:tagName");
        $stmt->execute([ ':tagName' => $tagName ]);
        return $stmt->rowCount()
            ? $stmt->fetch()['tag_description']
            : 'Не найдены вопросы с тегом «'.$tagName.'»';
    }
    
    /**
     * return all tags list with pagination
     * @param int $page
     * @return array
     */
    public static function getAllTagsList(int $page = 1): array
    {
            $offset = ($page - 1) * self::SHOW_TAGS_BY_DEFAULT;            
            $db = Database::getConnection();
            $tagsList = [];
            $result = $db->query("SELECT * FROM `tags`"
                    . " ORDER BY `questions_count` DESC"
                    . " LIMIT ".self::SHOW_TAGS_BY_DEFAULT
                    . " OFFSET ".$offset);
            while($row = $result->fetch()){
                $tagsList[] = [
                    'tag_id' => $row['tag_id'],
                    'tag_name' => $row['tag_name'],
                    'tag_description' => Common::mbCutString($row['tag_description'], 250),
                    'questions_count' => number_format($row['questions_count'], 0, ',', ' '),
                ];
            }
            return $tagsList;
    }
    
    /**
     * return sum of questions in tag by tag_id
     * @param int $tag_id
     * @return int
     */
    private static function QuestionsInTagByTagId(int $tag_id): int
    {
        $db = Database::getConnection();
        $result = $db->query("SELECT count(`tag_id`) as `count`"
                    . " FROM `tags_to_questions`"
                    . " WHERE `tag_id`='$tag_id'");
        $questionInTag = $result->fetch();
        return $questionInTag['count'];
    }
    
    /**
     * return sum of questions in tag by tag_name
     * @param string $tagName
     * @return int
     */   
    public static function QuestionsInTagByTagName(string $tagName): int
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT count(`tags_to_questions`.`tag_id`) AS `count`"
            . " FROM `tags`, `tags_to_questions`"
            . " WHERE `tags`.`tag_id`=`tags_to_questions`.`tag_id`"
            . " AND `tags`.`tag_name`=:tagName");
        $stmt->execute([
            ':tagName' => $tagName
        ]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
    
    
    
    
    
    
    
    
}
