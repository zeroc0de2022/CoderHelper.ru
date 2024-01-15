<?php
/**
 * Description of Questions
 * @author zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
 * @date 2021-04-08
 */

declare(strict_types = 1);

namespace Coderhelper\Models;

use Coderhelper\Components\Database;
use Coderhelper\Controllers\NotfoundController;
use PDOStatement;


class Questions {
    const SHOW_QUESTIONS_BY_DEFAULT = 20;

    /**
     * get list of questions by tag
     * @param string $tagName
     * @param int $page
     * @return array|void
     */
    public static function getQuestionsByTagName(string $tagName, int $page = 1){
        if($page){
            $offset = ($page - 1) * self::SHOW_QUESTIONS_BY_DEFAULT;
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM `tags`, `tags_to_questions`,  `questions`"
                    . " WHERE `questions`.`question_id`=`tags_to_questions`.`question_id`"
                    . " AND `tags`.`tag_id`=`tags_to_questions`.`tag_id`"
                    . " AND `tags`.`tag_name`=:tagName"
                    . " ORDER BY `pub_date` DESC"
                    . " LIMIT ".Tags::SHOW_QUESTIONS_BY_DEFAULT
                    . " OFFSET $offset");
            $stmt->execute(
                [ ':tagName' => $tagName ]
            );
            return self::ExtractQuestionList($stmt);
        }        
    }

    /**
     * similar questions by id list array, divided by ','
     * @param string $similar_id
     * @return array
     */
    public static function similarQuestions(string $similar_id): array
    {
        $similar_ids = explode(',', $similar_id);        
        $similarQuestions = [];
        $db = Database::getConnection();
        foreach($similar_ids as $question_id){            
            $question_id = intval($question_id);
            $stmt = $db->prepare("SELECT * FROM `questions`"
                . " WHERE `question_id`=:question_id"
                . " ORDER BY `pub_date` DESC");
            $stmt->execute([':question_id' => $question_id] );
            $questionItem = $stmt->fetch();
            if(is_array($questionItem) && count($questionItem) > 0){
                $similarQuestions[] = [
                    'question_id' => $questionItem['question_id'],
                    'post' => $questionItem['post'],
                    'title' => $questionItem['title']
                ];
            }
        }
        return $similarQuestions;        
    }
    
    
    /**
     *
     * @param int  $question_id
     * @return array question data
     */
    public static function viewQuestion(int $question_id): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM `questions` WHERE `question_id`=:question_id");
        $stmt->execute(
            [':question_id' => $question_id]
        );
        if($stmt->rowCount() === 0){
            NotfoundController::actionNotfound();
            exit;
        }
        $questionItem = $stmt->fetch();
        $questionItem['pub_date'] = Common::pubDateToString($questionItem['pub_date']); 
        $questionItem['tags'] = self::getTagsByQuestionId($questionItem['question_id']);
        # обновляем количество просмотров
        $view = $questionItem['views'] + 1;
        $stmt = $db->prepare("UPDATE `questions`"
            . " SET `views`=:view"
            . " WHERE `question_id`=:question_id");
        $stmt->execute(
            [
                ':view' => $view,
                ':question_id' => $question_id]
        );;
        return $questionItem;
    }
    
    /**
     * @param int $question_id
     * @return array answers by question_id
     */
    public static function getQuestionAnswers(int $question_id): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM `answers`"
            . " WHERE `question_id`=:question_id"
            . " ORDER BY `status` DESC, `rating` DESC, `pub_date` DESC");
        $stmt->execute([':question_id' => $question_id]);
        $answerList = [];
        while($row = $stmt->fetch()){
            $answerList[] = [
                'answer_id' => $row['answer_id'],
                'author' => $row['author'],
                'avatar' => $row['avatar'],
                'pub_date' => $row['pub_date'],
                'rating' => $row['rating'],
                'content' => $row['content'],
                'comments_block' => json_decode($row['comments_block'], true),
                'status' => $row['status']
            ];
        }
        return $answerList;
    }

    /**
     * return question list with pagination
     * @param int $page
     * @return array|void
     */
    public static function getQuestionsList(int $page = 1){
        if($page){
            $offset = ($page - 1) * self::SHOW_QUESTIONS_BY_DEFAULT;
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM `questions`"
                    . " ORDER BY `pub_date` DESC"
                    . " LIMIT ".self::SHOW_QUESTIONS_BY_DEFAULT
                    . " OFFSET $offset");
            return self::ExtractQuestionList($stmt);
        }
    }
    
    
    /**
     * tags by using in most questions
     * @return array
     */
    public static function popularTags(): array
    {
        $db = Database::getConnection();
        $popularTags = [];
        $result = $db->query("SELECT `tag_name`, COUNT(`tags_to_questions`.`tag_id`) AS tag_count 
                                    FROM `tags` 
                                    JOIN `tags_to_questions` 
                                    ON `tags_to_questions`.`tag_id` = `tags`.`tag_id` 
                                    GROUP BY `tags_to_questions`.`tag_id`, `tags`.`tag_name` 
                                    ORDER BY tag_count DESC
                                    LIMIT 10;");
        while($row = $result->fetch()){
            $popularTags[] = $row['tag_name'];
        }
        return $popularTags;        
    }
    
    /**
     * most popular questions
     * @return array
     */
    public static function popularQuestions(): array
    {
        $db = Database::getConnection();
        $popularQuestions = [];
        $result = $db->query("SELECT * FROM `questions` ORDER BY `views` DESC, `pub_date` DESC
         LIMIT 10");
        while($row = $result->fetch()){
            $popularQuestions[] = [
                'question_id' => $row['question_id'],
                'title' => $row['title'],
                'post' => $row['post'],
            ];;
        }
        return $popularQuestions; 
    }
    
    
    
    /**
     * get tags by question_id
     * @param int $question_id
     * @return array
     */
    public static function getTagsByQuestionId(int $question_id): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT `tag_name`"
            . " FROM `tags_to_questions`,`tags`"
            . " WHERE  `tags_to_questions`.`question_id`=:question_id"
            . " AND `tags`.`tag_id`=`tags_to_questions`.`tag_id`");
        $stmt->execute( [':question_id' => $question_id] );
        $tagsList = [];
        while($row = $stmt->fetch()){
            $tagsList[] = $row['tag_name'];
        }
        return $tagsList;
    }

    /**
     * @param false|PDOStatement $result
     * @return array
     */
    public static function ExtractQuestionList(false|PDOStatement $result): array
    {
        $questionList = [];
        while($row = $result->fetch()) {
            $questionList[] = [
                'question_id' => $row['question_id'],
                'avatar' => $row['avatar'],
                'author' => $row['author'],
                'post' => $row['post'],
                'views' => $row['views'],
                'answers' => $row['answers'],
                'pub_date' => Common::pubDateToString($row['pub_date']),
                'title' => $row['title'],
                'content' => Common::mbCutString($row['content'], 250),
                'tags' => self::getTagsByQuestionId($row['question_id']),
                'rating' => $row['rating']
            ];
        }
        return $questionList;
    }

}
