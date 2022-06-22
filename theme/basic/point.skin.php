<?php
if(!defined('_TUBEWEB_')) exit;
?>
<div class="sub-page my-page sub08-10" id="section">
    <div class="container">
	<!-- Mem_info Area Start -->
	<? include_once(TB_THEME_PATH.'/aside_mem_info.skin.php'); ?>
	<!-- Mem_info Area End -->
            <div class="sub08-10-content-wrap content-wrap">
			<!-- Left_box Area Start -->
			
			<!-- Left_box Area End -->
				<div class="content-box">                       
					<h3><a href="javascript:history.back()"><img src="<?php echo TB_IMG_URL; ?>/m-back.png" alt="뒤로가기"></a>포인트</h3>      
					<p><span class="sub">사용가능 포인트</span><span class="con"><?=number_format($member['point'])?>P</span></p>
					<ul class="point-list">
					<?php
					for($i=0; $row=sql_fetch_array($result); $i++) {

						if($row['po_point'] > 0){
							$set_point = "+".$row['po_point']."P 적립";
						}else{
							$set_point = "-".$row['po_use_point']."P 사용";
						}
					?>
						<li>
							<div class="box">
								<p class="cate"><?=$row['po_content']?></p>
								<p class="bottom">
								   <span><?=$set_point?>(<?=str_replace("-",".",substr($row["po_datetime"],2,8))?>)</span>
								</p>
								<span class="posible-point"><?=$row["po_mb_point"]?>P 사용가능</span>
							</div>
						</li>
					<?php
					}
					if($i==0)
						echo '<li class="empty">내역이 없습니다.</li>';
					?>	
					</ul>
				</div>
				<?php
				echo get_paging2($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
				?>
		</div><!-- content-wrap -->
	</div><!-- container -->
</div><!-- section -->