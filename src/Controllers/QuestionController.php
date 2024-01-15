<?php
/**
 * Description of QuestionController
 * @author zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
 * @date 2021-04-08
 */

namespace Coderhelper\Controllers;


use Coderhelper\Components\Pagination;
use Coderhelper\Models\Common;
use Coderhelper\Models\Questions;

class QuestionController {

    /***
     * Массив с данными для шапки сайта
     * @var array $nav
     */
    private array $nav = ['questions' => 'class="active"', 'tags' => ''];

    /**
     * Вывод главной страницы с вопросами
     * @param int $page
     * @return boolean
     */
    public function actionIndex(int $page = 1): bool
    {
        # Популярные теги
        $popularTags = Questions::popularTags();
        # Популярные вопросы
        $popularQuestions = Questions::popularQuestions();
        # Список вопросов
        $Questions = Questions::getQuestionsList($page);     
        # Всего вопросов
        $total = Common::getTotalItems('question_id', 'questions');        
        // Создаем объект Pagination - постраничная навигация
        $pagination = new Pagination($total, $page, Questions::SHOW_QUESTIONS_BY_DEFAULT, 'page-');   
        
        # Подключаем шаблон
        require_once(PUBLIC_HTML.'/views/questions/index.php');
        #
        return true;
    }
    
    
    /**
     * Вывод Вопроса
     * @param int $question_id
     * @param string $post
     * @return boolean
     */
    public function actionView(int $question_id, string $post = ""): bool
    {

        $post = urldecode($post);
        # Основные данные вопроса
        $questionItem = Questions::viewQuestion($question_id);
        # Переадресация на правильный адрес
        if($post !== $questionItem['post']){
            header( "Location: /questions/$question_id/".urlencode($questionItem['post']) );
        }
        if(stripos($_SERVER['REQUEST_URI'], '/questions/') !== 0){
            header( "Location: /questions/$question_id/".urlencode($questionItem['post']) );
        }
        $questionItem['comments_block'] = json_decode($questionItem['comments_block'], true);
        # ответы
        $Answers = Questions::getQuestionAnswers($question_id);
        # Похожие вопросы
        $similarQuestions = Questions::similarQuestions($questionItem['similar_id']);
        # Мета данные для хедера страницы
        $metadata = [
            'title' => $questionItem['title'],
            'description' => Common::mbCutString($questionItem['content'], 180, '.'),
            'keywords' => (is_array($questionItem['tags']))
                ? implode(', ', $questionItem['tags'])
                : preg_replace('#([^\d\s\wА-яёЁ])#iu', ',', trim($questionItem['title']))
        ];
        # Подключаем шаблон
        include_once PUBLIC_HTML.'/views/questions/view.php';
        #
        return true;
    }
    
}
