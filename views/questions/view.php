<?php
    if( !defined('IS_QUESTION_PAGE') ) define('IS_QUESTION_PAGE', 1);
    use Coderhelper\Models\Common;
?>
<?php include_once PUBLIC_HTML.'/views/layouts/header.php';?>
<!-- content -->
<div class="content_wrapper clearfix">
    <!-- questions block -->
    <main class="questions_block question_block">
	<h1 class="main_title"><?=$questionItem['title'];?></h1>
	<div class="question_stat_block clearfix">
            <div class="author">
		<img class="avatar" src="<?=$questionItem['avatar'];?>" alt="avatar">
		<div class="name"><?=$questionItem['author'];?></div>
            </div>
            <div class="question_stat">
		<div class="date stat_item"><?=$questionItem['pub_date'];?></div>
		<div class="views stat_item"><?=$questionItem['views'];?></div>
		<div class="answers stat_item"><?=(count($Answers));?></div>
            </div>
            <div class="votes_buttons">
		<div class="vote_button js-vote_button minus js-minus"></div>
		<div class="votes_count js-votes_count <?=(($questionItem['rating'] < 0) ? "red_color" : (($questionItem['rating'] > 0) ? "green_color" : ""));?>"><?=$questionItem['rating'];?></div>
		<div class="vote_button js-vote_button plus js-plus"></div>
            </div>
	</div>
        <div class="content_text">
            <!-- *** -->
            <?=trim($questionItem['content']);?>
            <!-- *** -->
	</div>
        <ul class="tags_list question_tags_list">
            <?php foreach($questionItem['tags'] as $tag) : ?>
            <li><a href="/tags/<?=urlencode($tag);?>"><?=$tag;?></a></li>
            <?php endforeach;?>
	</ul>
        <div class="sourse_block">
            <a class="sourse_item sourse_button" href="<?=$questionItem['outer_link'];?>" target="_blank" rel="nofollow noreferrer">Источник</a>
            <div class="sourse_item share_button js-share_button" data-link="<?=$_SERVER['REQUEST_SCHEME'];?>://<?=$_SERVER['SERVER_NAME'];?>/questions/<?=$questionItem['question_id']?>/<?=$questionItem['post'];?>">
                <span class="text">Поделиться</span>
                <span class="copy_text copy">Скопировать ссылку</span>
                <span class="copy_text copied">Скопировано</span>
            </div>
        </div>
        <?php if(is_array($questionItem['comments_block']) && count($questionItem['comments_block']) > 0) : ?>
        <div class="comments_block">
            <?php foreach($questionItem['comments_block'] as $comment) : ?>
            <div class="comment_item">
                <div class="question_stat_block clearfix">
                    <div class="author">
                        <div class="name"><?=$comment['author']?></div>
                    </div>
                    <div class="question_stat">
                        <div class="date stat_item"><?=Common::pubDateToString($comment['pub_date'])?></div>
                    </div>
                    <div class="votes_buttons">
                        <div class="vote_button js-vote_button minus js-minus"></div>
                            <div class="votes_count js-votes_count <?=(($comment['rating'] < 0) ? "red_color" : (($comment['rating'] > 0) ? "green_color" : ""));?>"><?=$comment['rating'];?></div>
                        <div class="vote_button js-vote_button plus js-plus"></div>
                    </div>
                </div>
                <p class="comment_text"><?=$comment['comment']?>
            </div>
            <?php endforeach;?>
        </div>
        <?php endif;?>
        <div class="answers_block">
            <h2 class="answers_title">Ответы (<?=(count($Answers));?>)</h2>
            <?php if(count($Answers) > 0) : ?>
            <!-- answers -->
            <div class="answers_list">
                <!-- *** -->
                <?php 
                $i = 1;
                foreach($Answers as $answer) :?>                
                <div class="answer_item <?=($answer['status']) ? "right" : "";?>">
                    <a class="point" name="<?=$answer['answer_id'];?>"></a>
                    <div class="question_stat_block clearfix">
                        <div class="author">
                            <img class="avatar" src="<?=$answer['avatar'];?>" alt="avatar">
                            <div class="name"><?=$answer['author'];?></div>
                        </div>
                        <div class="question_stat">
                            <div class="date stat_item"><?=Common::pubDateToString($answer['pub_date'])?></div>
                        </div>
                        <div class="votes_buttons">
                            <div class="vote_button js-vote_button minus js-minus"></div>
                            <div class="votes_count js-votes_count <?=(($answer['rating'] < 0) ? "red_color" : (($answer['rating'] > 0) ? "green_color" : ""));?>"><?=$answer['rating'];?></div>
                            <div class="vote_button js-vote_button plus js-plus"></div>
                        </div>
                    </div>
                    <div class="content_text">
                        <!-- *** -->
                        <?=$answer['content'];?>
                        <!-- *** -->
                    </div>
                    <div class="sourse_block">
                        <div class="sourse_item share_button js-share_button" data-link="<?=$_SERVER['REQUEST_SCHEME'];?>://<?=$_SERVER['SERVER_NAME'];?>/questions/<?=$questionItem['question_id']?>/<?=$questionItem['post'];?>#<?=$answer['answer_id'];?>">
                            <span class="text">Поделиться</span>
                            <span class="copy_text copy">Скопировать ссылку</span>
                            <span class="copy_text copied">Скопировано</span>
                        </div>
                    </div>
                    <?php if(is_array($answer['comments_block']) && count($answer['comments_block']) > 0) : ?>
                    <div class="comments_block">
                        <?php foreach($answer['comments_block'] as $comment) : ?>
                        <div class="comment_item">
                            <div class="question_stat_block clearfix">
                                <div class="author">
                                    <div class="name"><?=$comment['author']?></div>
                                </div>
                                <div class="question_stat">
                                    <div class="date stat_item"><?=Common::pubDateToString($comment['pub_date'])?></div>
                                </div>
                                <div class="votes_buttons">
                                    <div class="vote_button js-vote_button minus js-minus"></div>
                                    <div class="votes_count js-votes_count <?=(($comment['rating'] < 0) ? "red_color" : (($comment['rating'] > 0) ? "green_color" : ""));?>"><?=$comment['rating'];?></div>
                                    <div class="vote_button js-vote_button plus js-plus"></div>
                                </div>
                            </div>
                            <p class="comment_text"><?=$comment['comment'];?></p>
                        </div>
                        <?php endforeach;?>
                    </div>
                    <?php endif;?>
                </div>
                <?php 
                    $i++;
                    endforeach;
                ?>
                <!-- *** -->
            </div>
            <!-- /answers -->
            <?php endif;?>
        </div>
    </main>
    <!-- /questions block -->
    <!-- sidebar -->
    <aside class="sidebar">
        <div class="info_block sidebar_questions_block">
            <h4 class="block_title">Похожие вопросы</h4>
            <ul class="questions_list">
                <?php foreach($similarQuestions as $similar) : ?>
                <li><a href="/questions/<?=$similar['question_id']?>/<?=$similar['post']?>"><?=$similar['title']?></a></li> 
                <?php endforeach; ?>
            </ul>
        </div>
    </aside>
    <!-- /sidebar -->
</div>
<!-- /content -->        
<?php include_once PUBLIC_HTML.'/views/layouts/footer.php';?>