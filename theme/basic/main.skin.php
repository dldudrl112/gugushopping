<?php
if(!defined('_TUBEWEB_')) exit;
?>
<section class="main-sec" id="section">
	<a href="<?php echo TB_BBS_URL; ?>/alarm.php" id="test1" class="alarm-side"><img src="/img/alarm_side.png" alt="alarm_side" class="mob"><img src="/img/alarm_side_pc.png" alt="alarm_side_pc" class="pc"></a>
	<?php
	if(defined('_INDEX_')) { // index에서만 실행

		$sql = sql_banner_rows(0, $pt_id);
		$res = sql_query($sql);
		$mbn_rows = sql_num_rows($res);
		if($mbn_rows) {
	?>
	<div class="main-slide">
		<?php
			$txt_w = (100 / $mbn_rows);
			$txt_arr = array();

			for($i=0; $row=sql_fetch_array($res); $i++){

				if($row['bn_text'])
					$txt_arr[] = $row['bn_text'];

				$a1 = $a2 = $bg = '';
				$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
				if(is_file($file) && $row['bn_file']) {
					if($row['bn_link']) {
						$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
						$a2 = "</a>";
					}

					// $img_src	=$img_src."<img src=\"/data/banner/".$row['bn_file']."\"

					$img_src	=	"<img src=\"/data/banner/".$row['bn_file']."\" alt=\"main_bg\" class=\"pc\">";
					
					$img_src	=$img_src	=$img_src."<img src=\"/data/banner/".$row['bn_file']."\"
					 alt=\"main_bg\" class=\"mob\" style=\"max-width:768px; object-fit:cover;\" >";

					$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
					if($row['bn_bg']) $bg = "#{$row['bn_bg']} ";

					$file = rpc($file, TB_PATH, TB_URL);
					echo "<li class=\"img_box mbn_img\" style=\"background:{$bg} no-repeat top center;\">{$a1}{$img_src}{$a2}</li>\n";
				}
			}
		?>
	</div><!-- main-slide -->
	
	<?php 
			}
		}
	?>
	<div class="category">
		<div class="main-container" style="display: flex;justify-content: center;">     
			<ul>
				<li class="on" onclick="product_list_ajax(this);"><a href="javascript:void(0)"><img src="/img/m-cate_img011.png" alt="cate_img01"><span>모두보기</span></a></li>
				<li class="on" onclick="product_list_ajax(this,'002');"><a href="javascript:void(0)"><img src="/img/test/m-cate_img012.png" alt="cate_img01"><span>인기구독</span></a></li>
				<li class="on" onclick="product_list_ajax(this,'004');"><a href="javascript:void(0)"><img src="/img/test/m-cate_img013.png" alt="cate_img01"><span>생활필수</span></a></li>
				<li class="on" onclick="product_list_ajax(this,'001');"><a href="javascript:void(0)"><img src="/img/test/m-cate_img014.png" alt="cate_img01"><span>특별한날</span></a></li>
				<li class="on" onclick="product_list_ajax(this,'003');"><a href="javascript:void(0)"><img src="/img/test/m-cate_img015.png" alt="cate_img01"><span>Only구독</span></a></li>
				<!-- <?php
				$res= sql_query_cgy('all');
				for($i=0; $row=sql_fetch_array($res); $i++) {
				?>
				<li onclick="product_list_ajax(this,'<?=$row['catecode']?>');"><a href="javascript:void(0);"><img src="/data/category/<?=$pt_id?>/<?=$row["img_head"]?>" alt="<?php echo $row['catename']; ?>"><span><?php echo $row['catename']; ?></span></a></li>
				
				<?php } ?> -->
			</ul>
		</div>
	</div><!-- category -->
	<div class="main-container">
		<div class="main-contents"></div>
        <?php
        if($default['de_certify_use']) { // 실명인증 사용시
            @include_once(TB_PLUGIN_PATH."/chekplus/checkplus_main.php");
            @include_once(TB_PLUGIN_PATH."/chekplus/ipin_main.php");
            ?>
            <form name="fregister" id="fregister" method="post" data-req="<?php echo get_session('REQ_SEQ');?>">
                <input type="hidden" name="m" value="checkplusSerivce">
                <input type="hidden" name="EncodeData" value="<?php echo $enc_data; ?>">
                <input type="hidden" name="enc_data" value="<?php echo $sEncData; ?>">
                <input type="hidden" name="param_r1" value="">
                <input type="hidden" name="param_r2" value="">
                <input type="hidden" name="param_r3" value="<?php echo $regReqSeq; ?>">
            </form>
            <script>
                $(function(){

                    $(".main-container").on("click", "#checkAdult", function(e){
                        e.preventDefault();
                        window.name ="Parent_window";
                        var mode = 1;
                        var f = document.fregister;

                        switch(mode){
                            case 1: //Mobile phone authentication
                                window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
                                f.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
                                f.target = "popupChk";
                                f.submit();
                                break;
                            case 0: //I-PIN authentication
                                window.open('', 'popupIPIN2', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
                                f.target = "popupIPIN2";
                                f.action = "https://cert.vno.co.kr/ipin.cb";
                                f.submit();
                                break;
                        }
                    });
                });
            </script>
        <?php } ?>
	</div>
</section>
<script type="text/javascript">

	function counseling_type_act(){

		$(".list_box .container .clearfix li").each(function(index) {
			
			if($(this).find(".state").attr("class") != "state state1"){
				$(this).hide();
			}

		});

	}	

	function product_list_ajax(obj, ca_id){
		
		$(".category .main-container ul li").removeClass("on");
		
		$(obj).addClass("on");


		if(ca_id == ""){
			$(".category .main-container ul li:eq(0)").addClass("on");		
		}

		var search_txt				=	$("input[name=ss_tx]").val();
		
		$.post(
			tb_shop_url+"/ajax.goods_list.php",
			{ ca_id: ca_id,  search_txt:search_txt},
			function(data) {				
				$(".main-container .main-contents").html(data);
			}
		);
	}

	product_list_ajax($(".category .main-container ul li:eq(0)"), '');

</script>