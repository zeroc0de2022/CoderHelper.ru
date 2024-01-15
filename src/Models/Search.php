<?php
/**
 * Description of Search
 * @author zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
 * @date 2021-04-08
 */

namespace Coderhelper\Models;

use Coderhelper\Components\Database;

class Search {

    /**
     * get list of questions by Query
     * @param string $query
     * @param int $page
     * @return array|void
     */
    public static function getQuestionsByQuery(string $query, int $page){
        if($page){
            $offset = ($page - 1) * Questions::SHOW_QUESTIONS_BY_DEFAULT;
            $db = Database::getConnection();
            $questionList = [];
            $stmt = $db->prepare("SELECT * FROM `questions`"
                    ." WHERE `title` LIKE :title_query"
                    ." OR `content` LIKE :content_query"
                    ." ORDER BY `pub_date` DESC"
                    ." LIMIT ".Questions::SHOW_QUESTIONS_BY_DEFAULT
                    ." OFFSET $offset");
            $stmt->execute( [
                ':title_query' => "%$query%",
                ':content_query' => "%$query%"
            ]);
            return Questions::ExtractQuestionList($stmt);
        }
    }
    
    /**
     * get total questions by query
     * @param string $query
     * @return int
     */
    public static function totalQuestionsByQuery(string $query): int
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT count(`question_id`) as `count` FROM `questions`"
                    . " WHERE `title` LIKE :title_query"
                    . " OR `content` LIKE :content_query"
                    . " ORDER BY `pub_date` DESC");
        $stmt->execute( [
            ':title_query' => "%$query%",
            ':content_query' => "%$query%"
        ]);
        $row = $stmt->fetch();
        return $row['count'];
    }
    
    
}
