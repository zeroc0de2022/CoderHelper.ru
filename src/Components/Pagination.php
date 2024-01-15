<?php
/**
 * Описание класса Pagination
 *  - Для генерации подвала страницы с количеством записей и количеством страниц
 *  - Используется в контроллерах, в моделях, в шаблонах и т.д.
 *  @author zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
 *  @date 2021-08-04
 */
namespace Coderhelper\Components;

class Pagination
{
      
    # Ключ для GET в который пишется номер страницы
    private string $index;
    # Текущая страница
    private int $current_page;
    # Общее количество записей
    private int $total;
    # Записей на страницу
    private int $limit;
    # Переменная для хранения расчета От и До
    private string $count;
    # @var type 
    private int $toS;
    
    /**
     * Запуск необходимых данных для навигации
     * @param int $total - общее количество записей
     * @param int $current_page - текущая страница
     * @param int $limit - количество записей на страницу
     * @param string $index - ключ в URL
     */
    public function __construct(int $total, int $current_page, int $limit, string $index = 'page-') {
        # Устанавливаем общее количество записей
        $this->total = $total;
        # Устанавливаем количество записей на страницу
        $this->limit = $limit;
        # Устанавливаем ключ в URL
        $this->index = $index;
        # Устанавливаем номер текущей страницы
        $this->current_page = $current_page;
        # Расчитываем строку От-До Всего
        $this->count = $this->count();
    }
    
    /**
     * Генерирует HTML-код пагинации и возвращает его
     * @return string
     */
    public function count(): string
    {
        $count = $this->countCalculate();
        # формируем пагинацию
        $currentURI = rtrim($_SERVER['REQUEST_URI'], '/').'/';
        $currentURI = preg_replace('~/page-[0-9]+~', '', $currentURI);
        $prev_page = $this->current_page - 1;
        $next_page = $this->current_page + 1;
        $prev = ($this->current_page == 1)
            ? "<a class='button prev disabled'></a>"
            : "<a class='button prev' target='_self' href='".$currentURI.$this->index.$prev_page."'></a>";
        $next = ($this->toS >= $this->total)
            ? "<a class='button next disabled'></a>"
            : "<a class='button next' href='".$currentURI.$this->index.$next_page."'  target='_self'></a>";
        $return = "";
        if($this->total > 0){
            $return = ($this->total > $this->limit)
                ? "<div class='page_nav clearfix'>
                        <div class='count'>$count</div>".$prev.$next."</div>"
                : "<div class='page_nav clearfix'>
                        <div class='count'>$count</div>
                    </div>";
        }
        return $return;			
    }
    
    /**
     * Поисковая пагинация - для вывода в цикле
     * @return string
     * @todo Переделать на общую пагинацию
     */
    public function searchPagination(): string
    {
        $count = $this->countCalculate();
        # формируем пагинацию
        $currentURI = rtrim($_SERVER['REQUEST_URI'], '/');
        $currentURI = preg_replace('~/page-[0-9]+~', '', $currentURI);
        [$currentURI, $query] = explode('?', $currentURI);
  
        $prev_page = $this->current_page - 1;
        $next_page = $this->current_page + 1;
        $prev = ($this->current_page == 1)
            ? "<a class='button prev disabled'></a>"
            : "<a class='button prev' target='_self' href='$currentURI/".$this->index."$prev_page?$query'></a>";
        $next = ($this->toS >= $this->total)
            ? "<a class='button next disabled'></a>"
            : "<a class='button next' href='".$currentURI."/".$this->index."$next_page?$query'  target='_self'></a>";
        $return = "";
        if($this->total > 0){
            $return = ($this->total > $this->limit)
                ? "<div class='page_nav clearfix'>
                        <div class='count'>$count</div>".$prev.$next."</div>"
                : "<div class='page_nav clearfix'>
                        <div class='count'>$count</div>
                    </div>";
        }
        return $return;	
    }
    
    /**
     * Расчитываем строку От и До
     * @return string
     */
    public function countCalculate(): string
    {
        $from = ((($this->current_page - 1) * $this->limit)+1);
        $this->toS = ($this->limit * $this->current_page);
        $to = ($this->toS > $this->total) ? $this->total : $this->toS;
        $total = number_format($this->total, 0, ',', ' ');
        
        return number_format($from, 0, ',', ' ')
            ." - ".number_format($to, 0, ',', ' ')
            ." из $total";
    }
    
    
    
}
