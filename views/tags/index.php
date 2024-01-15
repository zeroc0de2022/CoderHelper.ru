<?php include_once PUBLIC_HTML.'/views/layouts/header.php';?>
<!-- content -->
<div class="content_wrapper clearfix">
    <!-- tags block -->
    <main class="tags_block">
        <h1 class="main_title">Тэги</h1>
        <div class="tags_list clearfix">
            <?php foreach($Tags as $tagItem) : ?>
            <!-- *** -->
            <div class="tag_item">
                <div class="tag_top clearfix">
                    <a class="tag_name" href="/tags/<?=mb_strtolower(urlencode($tagItem['tag_name']));?>"><?=$tagItem['tag_name'];?></a>
                    <div class="questions_count"><?=$tagItem['questions_count']?></div>
		</div>
		<p class="tag_description"><?=$tagItem['tag_description'];?></p>
            </div>
            <?php endforeach; ?>
            <!-- *** -->
        </div>
        <!-- page nav -->
        <?=$pagination->count();?>
        <!-- /page nav -->
    </main>
    <!-- /tags block -->
</div>
<!-- /content -->
<?php include_once PUBLIC_HTML.'/views/layouts/footer.php';?>