<a href="orderinquiry.skin.php"></a><?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<script src="<?php echo TB_MJS_URL; ?>/jquery.lazyload.min.js"></script>
<script src="<?php echo TB_MJS_URL; ?>/masonry.pkgd.js"></script>

<?php if($board['fileurl1']) { ?>
<div class="m_bo_hd"><img src='<?php echo TB_DATA_URL; ?>/board/boardimg/<?php echo $board['fileurl1']; ?>'></div>
<?php } ?>
<div class="m_gall">
	<?php if($board['use_category']) { ?>
	<select name="faq_type" class="faq_sch" onchange="location=this.value;">
		<option value="<?php echo TB_MBBS_URL; ?>/board_list.php?boardid=<?php echo $boardid; ?>">全体表示</option>
		<?php
		for($i=0; $i<count($usecate); $i++) {
			$selected = "";
			if($usecate[$i]==$ca_name) {
				$selected = ' selected';
			}
		?>
		<option value="<?php echo TB_MBBS_URL; ?>/board_list.php?boardid=<?php echo $boardid; ?>&ca_name=<?php echo $usecate[$i]; ?>"<?php echo $selected; ?>><?php echo $usecate[$i]; ?></option>
		<?php } ?>
	</select>
	<?php } ?>

	<?php
	if(!$total_count) {
		echo "<p class=\"empty_list\">書き込みがありません。</p>";
	} else {
	?>
	<ul id="m_gall_ul">
		<?php
		$sql = " select * from shop_board_{$boardid} where btype = '1' {$sql_search2} order by fid desc ";
		$rst = sql_query($sql);
		for($i=0; $row=sql_fetch_array($rst); $i++) {
			$href = TB_MBBS_URL.'/board_read.php?index_no='.$row['index_no'].'&boardid='.$boardid.'&page='.$page;

			$bo_subject = '<strong class="fc_eb7">[お知らせ]</strong> '.get_text($row['subject']);
			$bo_wdate = get_text($row['writer_s'])."<span class='padl10'>".date("y/m/d",$row['wdate']);

			if((TB_SERVER_TIME - $row['wdate']) < (60*60*24)) {
				$bo_subject .= " <img src='".TB_IMG_URL."/iconY.gif' class='marl3'>";
			}

			$thumb = get_list_thumbnail($boardid, $row, 400, 0);
			if(!$thumb['src']) {
				$thumb['src'] = TB_IMG_URL.'/noimage.gif';
			}
		?>
		<li class="item">
			<a href="<?php echo $href; ?>">
			<dl>
				<dt><img src="<?php echo $thumb['src']; ?>" class="lazyload"></dt>
				<dd class="subj"><?php echo $bo_subject; ?></dd>
				<dd class="date"><?php echo $bo_wdate; ?></dd>
			</dl>
			</a>
		</li>
		<?php
		}

		for($i=0; $row=sql_fetch_array($result); $i++) {
			$href = TB_MBBS_URL.'/board_read.php?index_no='.$row['index_no'].'&boardid='.$boardid.'&page='.$page;

			$bo_subject = '';
			$bo_wdate_c = '';
			$spacer = strlen($row['thread'] != 'A');
			if($spacer>$reply_limit) {
				$spacer = $reply_limit;
			}

			for($i2=0; $i2<$spacer; $i2++) {
				$bo_subject = "<img src='{$bo_img_url}/img/icon_reply.gif'> ";
				$bo_wdate_c = " padl13";
			}

			$bo_subject = $bo_subject .get_text($row['subject']);
			$bo_wdate = get_text($row['writer_s'])."<span class='padl10'>".date("y/m/d",$row['wdate']);

			if($row['issecret'] == 'Y') {
				$bo_subject .= " <img src='{$bo_img_url}/img/icon_secret.gif'>";
			}

			if((TB_SERVER_TIME - $row['wdate']) < (60*60*24)) {
				$bo_subject .= " <img src='{$bo_img_url}/img/iconY.gif'>";
			}

			$thumb = get_list_thumbnail($boardid, $row, 400, 0);
			if(!$thumb['src']) {
				$thumb['src'] = TB_IMG_URL.'/noimage.gif';
			}
		?>
		<li class="item">
			<a href="<?php echo $href; ?>">
			<dl>
				<dt><img src="<?php echo $thumb['src']; ?>" class="lazyload"></dt>
				<dd class="subj"><?php echo $bo_subject; ?></dd>
				<dd class="date"><?php echo $bo_wdate; ?></dd>
			</dl>
			</a>
		</li>
		<?php } ?>
	</ul>
	<?php } ?>

	<?php
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?boardid='.$boardid.'&page=');
	?>

	<?php if($member['grade'] <= $board['write_priv']) { ?>
	<div class="btn_confirm">
		<a href="<?php echo TB_MBBS_URL; ?>/board_write.php?boardid=<?php echo $boardid;?>" class="btn_medium">書き物</a>
	</div>
	<?php } ?>

	<form name="searchform" method="get">
	<input type="hidden" name="boardid" value="<?php echo $boardid; ?>">
	<div class="bottom_sch">
		<select name="sfl">
		<?php
		for($i=0;$i<sizeof($gw_search_value);$i++) {
			echo "<option value='{$gw_search_value[$i]}'".get_selected($gw_search_value[$i], $sfl).">{$gw_search_text[$i]}</option>\n";
		}
		?>
		</select>
		<input type="text" name="stx" class="frm_input" value="<?php echo $stx; ?>">
		<input type="submit" value="検索" class="btn_small grey">
	</div>
	</form>
</div>

<script>
/* Mobile ブラウザチェック関数 */
function mobile_chk(){
	var user_device = navigator.userAgent.toLowerCase();
	var mobile_device = new Array('iphone','ipad', 'firefox', 'android');
	for(var i=0;i<mobile_device.length;i++){
		if(user_device.indexOf(mobile_device[i]) != -1)	return true;
	}
	return false;
}
/*
setTimeout チェック値
*/
var ing = {'lazyloadCallback' : false};
/*
masonry オプションの値
*/
var masonryOptions = {itemSelector : ".item", columnWidth : ".item", percentPosition : true};
$(function(){
	/*
	Lazy Load プラグインの適用
	(loadメソッドを使用してコールバック関数を指定 /masonryの並べ替えは、layoutメソッドではなく、
destroyメソッドを利用したmasonry除去した後masonryの再適用に処理します。
	destroy後の再適用時pcブラウザでは、スクロールが最上段に上がる問題があり
navigator.userAgentの値をチェックして、モバイルではない場合
	scrollの高さの値を格納したmasonryが適用完了後保存されたscroll高さの値に移動させてくれる。)
	*/
	$('img.lazyload').not('.lazyed').lazyload({
		effect : 'fadeIn',
		load : function(){
			$(this).addClass('lazyed');
			if($masgall){
				if(ing['lazyloadCallback']) clearTimeout(ing['lazyloadCallback']);
				ing['lazyloadCallback'] = window.setTimeout(function(){
					if(!mobile_chk())	var scroll = $(window).scrollTop();
					$masgall.masonry('destroy').masonry(masonryOptions);
					if(!mobile_chk())	$(window).scrollTop(scroll);
					console.log(/'関数の重複チェック、ログ - masonry 再配置!');
				}, 100);
			}
		}
	});
	/*
	masonry 最初の適用
	*/
	$masgall = $("#m_gall_ul").masonry(masonryOptions);
});
</script>
