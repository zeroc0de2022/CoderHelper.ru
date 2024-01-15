<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$metadata['title'] ?? "Ответы на вопросы по программированию"; ?> | CoderHelper.ru</title>
    <meta name="keywords" content="<?=$metadata['keywords'] ?? "программирование, языки программирования, javascript, java, python, c#, php, android, html, jquery, c++, css"?>">
    <meta name="description" content="<?=$metadata['description'] ?? "Ответы на вопросы по программированию"?>">
    <link rel="shortcut icon" href="/images/favicon.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/template/css/reset.css">
    <?php if( defined('IS_QUESTION_PAGE') && IS_QUESTION_PAGE === 1 ): ?>
    <link rel="stylesheet" href="/template/css/prettify.css">
    <?php endif; ?>
    <link rel="stylesheet" href="/template/css/styles.css?v=2">
</head>
<body>
    <!-- header -->
    <header class="header">
	<div class="header_wrapper clearfix">
            <div class="logo_block">
		<a class="logo" href="https://coderhelper.ru/">CoderHelper<span>.ru</span></a>
            </div>
            <nav class="nav_wrapper">
                <ul class="nav">
                    <li><a <?=$this->nav['questions'];?> href="/">Вопросы</a></li>
                    <li><a <?=$this->nav['tags'];?> href="/tags">Теги</a></li>
		</ul>
            </nav>
            <form class="search_form" action="/search" method="get">
                <label for="querys"></label>
                    <input class="search_query" type="text" id="querys" name="query" placeholder="Поиск" required="required" minlength="3">

            </form>
            <div class="menu_button js-menu_button"></div>
        </div>
    </header>
    <!-- header -->
    <!-- mobile menu -->
    <div class="mobile_menu js-mobile_menu">
        <nav class="nav_wrapper">
            <ul class="nav">
		<li><a href="/">Вопросы</a></li>
		<li><a href="/tags">Теги</a></li>
            </ul>
	</nav>
	<form class="search_form" action="/search" method="get">
            <label for="query"></label>
            <input class="search_query" type="text" id="query" name="query" placeholder="Поиск" required="required" minlength="3">
	</form>
    </div>
