<?php

/**
 * Description of TagsController
 * @author zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
 * @date 2021-04-08
 */

namespace Coderhelper\Controllers;
use Coderhelper\Components\Pagination;
use Coderhelper\Models\Common;
use Coderhelper\Models\Debug;
use Coderhelper\Models\Questions;
use Coderhelper\Models\Tags;

class TagsController {
    private array $nav = ['questions' => '', 'tags' => 'class="active"'];

    public function actionIndex($page = 1): bool
    {
        # Теги 
        $Tags = Tags::getAllTagsList($page); 
        # Всего вопросов
        $total = Common::getTotalItems('tag_id', 'tags');   
        // Создаем объект Pagination - постраничная навигация
        $pagination = new Pagination($total, $page, Tags::SHOW_TAGS_BY_DEFAULT, 'page-'); 
        # Подключаем шаблон
        require_once(PUBLIC_HTML.'/views/tags/index.php');
        
        return true;
    }

    public function actionList(string $tagName, int $page = 1): bool
    {

        $tagName = rawurldecode($tagName);
        # Описание тега
        $tag_description = Tags::TagDescriptionByTagName($tagName);
        # Популярные теги
        $popularTags = Questions::popularTags();
        # Популярные вопросы
        $popularQuestions = Questions::popularQuestions();
        # Вопросы по тегу
        $Questions = Questions::getQuestionsByTagName($tagName, $page);          
        # Всего вопросов по тегу
        $total = Tags::QuestionsInTagByTagName($tagName);  
        // Создаем объект Pagination - постраничная навигация
        $pagination = new Pagination($total, $page, Questions::SHOW_QUESTIONS_BY_DEFAULT, 'page-');  
        # Мета данные для хедера страницы
        $metadata = [
            'title' => "Вопросы, помеченные тегом «".$tagName."»",
            'description' => "Вопросы, помеченные тегом «".$tagName."»",
            'keywords' => $tagName.", программирование, языки программирования"
        ];
        # Подключаем шаблон
        require_once(PUBLIC_HTML.'/views/tags/list.php');
        
        return true;
    }
}