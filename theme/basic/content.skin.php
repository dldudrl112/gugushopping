<?php
if(!defined('_TUBEWEB_')) exit;
?>
<div class="content-page">
<h2 class="pg_tit">
	<span><?php echo $tb['title']; ?></span>
	<p class="pg_nav">HOME<i>&gt;</i><?php echo $tb['title']; ?></p>
</h2> 

<?php 
if(is_mobile() == true){
	echo get_view_thumbnail(conv_content($co["co_content"], 1, 0), 1000);
}else{
	echo get_view_thumbnail(conv_content($co["co_mobile_content"], 1, 0), 1000);
}

?>
</div>