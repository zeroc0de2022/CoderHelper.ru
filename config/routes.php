<?php 
/**
 * Массив с маршрутами
 * @date 2021-08-04
 * @var array
 */
return [
    '^search\/page-([0-9]+)' => 'search/index/$1',  //actionIndex в SearchController с пагинацией
    '^search' => 'search/index',  //actionIndex в SearchController    
    '^tags\/([0-9a-zA-Z%-.,]+)/page-([0-9]+)$' => 'tags/list/$1/$2',  //actionList в TagController с пагинацией
    '^tags\/page-([0-9]+)$' => 'tags/index/$1',  //actionIndex в TagController с пагинацией
    '^tags\/([0-9a-zA-Z%-.,]+)$' => 'tags/list/$1', //actionList в TagController первая страница
    '^tags$' => 'tags/index',                   //actionIndex в TagController первая страница
    '^questions/([0-9]+)/([0-9а-яёА-ЯЁA-Za-z-%.,]+)$' => 'question/view/$1/$2',    //actionView в SiteController
    '^questions/([0-9]+)/?$' => 'question/view/$1/question',    //actionView в SiteController
    '^q\/([0-9]+)/([0-9а-яёА-ЯЁA-Za-z-%.,]+)$' => 'question/view/$1/$2',    //actionView в SiteController
    '^q\/([0-9]+)/?$' => 'question/view/$1/question',    //actionView в SiteController
    '^page-([0-9]+)$' => 'question/index/$1',       // actionIndex c пагинацией в SiteController
    '' => 'question/index'                          //actionIndex в SiteController
    ]; 
