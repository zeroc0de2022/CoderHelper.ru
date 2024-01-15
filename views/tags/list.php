<?php include_once PUBLIC_HTML.'/views/layouts/header.php';?>
<!-- content -->
<div class="content_wrapper clearfix">
    <!-- questions block -->
    <main class="questions_block">
	<h1 class="main_title">Вопросы, помеченные тегом «<?=$tagName?>»</h1>
	<div class="tagged_description"><?=$tag_description?></div>
            <div class="questions_list">
            <!-- *** -->
            <?php foreach($Questions as $question): ?>
            <div class="question_item">
		<div class="question_stat_block">
                    <div class="author">
			<img class="avatar" src="<?=$question['avatar']?>" alt="avatar">
                        <div class="name"><?=$question['author']?></div>
                    </div>
                    <div class="question_stat">
			<div class="date stat_item"><?=$question['pub_date']?></div>
			<div class="views stat_item"><?=$question['views']?></div>
			<div class="answers stat_item"><?=$question['answers']?></div>
                    </div>
		</div>
		<h3 class="question_title">
                    <a href="/questions/<?=$question['question_id']?>/<?=$question['post']?>"><?=$question['title']?></a>
		</h3>
		<div class="description"><?=$question['content']?></div>
		<ul class="tags_list">
                    <?php for($i = 0; $i < count($question['tags']); $i++) :?>
                    <li><a href="/tags/<?=urlencode($question['tags'][$i])?>"><?=$question['tags'][$i]?></a></li>
                    <?php endfor;?>
		</ul>
            </div>
            <?php endforeach; ?>
            <!-- *** -->
	</div>
	<!-- page nav -->
        <?=$pagination->count();?>
        <!-- /page nav -->
    </main>
    <!-- /questions block -->
    <!-- sidebar -->
    <aside class="sidebar">
        <div class="info_block sidebar_tags_block">
            <h4 class="block_title">Популярные теги</h4>
            <ul class="tags_list">
		<?php foreach ($popularTags as $popularTag) : ?>
                <li><a href="/tags/<?=urlencode($popularTag)?>"><?=$popularTag?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="info_block sidebar_questions_block">
            <h4 class="block_title">Популярные вопросы</h4>
            <ul class="questions_list">
		<?php foreach($popularQuestions as $pQuestion) : ?>
                <li><a href="/questions/<?=$pQuestion['question_id']?>/<?=$pQuestion['post']?>"><?=$pQuestion['title']?></a></li>                
                <?php endforeach; ?>
            </ul>
	</div>
    </aside>
    <!-- /sidebar -->
</div>
<!-- /content -->
<?php include_once PUBLIC_HTML.'/views/layouts/footer.php';?>