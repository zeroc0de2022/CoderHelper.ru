<?php
/**
 * Description of SearchController
 * @author zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
 * @date 2021-04-08
 */

namespace Coderhelper\Controllers;

use Coderhelper\Components\Pagination;
use Coderhelper\Models\Questions;
use Coderhelper\Models\Search;


class SearchController {

    /***
     * Массив с данными для шапки сайта
     * @var array $nav
     */
    private array $nav = ['questions' => 'class="active"', 'tags' => ''];

    /**
     * Вывод страницы поиска
     * @param int $page
     * @param string $query
     * @return boolean
     */
    public function actionIndex(int $page = 1, string $query = ""): bool
    {
        # Популярные теги
        $popularTags = Questions::popularTags();
        # Популярные вопросы
        $popularQuestions = Questions::popularQuestions();
        # Meta данные
        $metadata = [
            'title' => "Результаты поиска",
        ];
        if(strlen(trim($query)) >= 3){
            # Список вопросов по ключевому слову
            $Questions = Search::getQuestionsByQuery($query, $page); 
            # Всего вопросов
            $total = Search::totalQuestionsByQuery($query);            
            # Мета данные

            $metadata['description'] = "Результаты поиска по запросу «".htmlspecialchars($query)."»";
            $query_text = ($total > 0) ? "найдено $total соответствий" : "ничего не найдено";
        }
        else {
            # недостаточно аргументов для поиска
            $Questions = [];
            # Мета данные

            $metadata['description'] = "Недостаточно символов. Уточните свой запрос «".htmlspecialchars($query)."»";
            $query_text = "недостаточно символов для поиска.";
        }
        # Всего вопросов
        $total = $total ?? 0;        
        // Создаем объект Pagination - постраничная навигация
        $pagination = new Pagination($total, $page, Questions::SHOW_QUESTIONS_BY_DEFAULT, 'page-');   

        # Подключаем шаблон
        require_once(PUBLIC_HTML.'/views/search/index.php');
        #
        return true;
    }
}
