<?php
if(!defined('_TUBEWEB_')) exit;

$upl_dir	=	TB_DATA_URL."/member";
?>
<div class="mypage-info">
    <div class="img-box">
		<?php if($member['mem_img']){ ?>
			<img src="<?php echo $upl_dir; ?>/<?=$member['mem_img']?>" alt="img">
		<?php }else{ ?>
	        <img src="<?php echo TB_IMG_URL; ?>/no_img2.jpg" alt="my-thumb-img">
		<?php } ?>


    </div>
    <div class="text-box-wrap">
        <div class="text-box">
        <div class="top">
            <!-- <img src="<?php echo TB_IMG_URL; ?>/mypage_sns01.png" alt="kakao"> -->
            <!-- <img src="..<?php echo TB_IMG_URL; ?>/mypage_sns02.png" alt="facebook"> -->
            <!-- <img src="..<?php echo TB_IMG_URL; ?>/mypage_sns03.png" alt="google"> -->
            <!-- <img src="..<?php echo TB_IMG_URL; ?>/mypage_sns04.png" alt="naver"> -->
            <div class="name"><?php echo get_text($member['nickname']); ?></div>
            <div class="setting">
                <a href="<?php echo TB_SHOP_URL; ?>/mypage.php"><img src="<?php echo TB_IMG_URL; ?>/m-setting.png" alt="setting"></a>
            </div>
        </div>
        <div class="bottom" style="display:none">
            <p>
                <span class="subject">포인트 :</span>
                <span class="con"><?php echo number_format($member['point']); ?>P</span>
            </p>
        </div>
    </div>
    </div>
</div> <!-- mem_info -->
