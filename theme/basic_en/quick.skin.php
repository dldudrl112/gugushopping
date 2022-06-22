<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- Quick Menu Left Wing Start { -->
<div id="qcl">
	<?php echo display_banner_rows(90, $pt_id); ?>
</div>
<!-- } Quick Menu Left Wing End -->

<!-- Quick Menu Right Wing Start { -->
<div id="qcr">
	<ul>
		<li class="tit">a recent article</li>
		<li>
			<?php
			$pr_tmp = get_cookie('ss_pr_idx');
			$pr_idx = explode('|',$pr_tmp);
			$pr_tot_count = 0;
			$k = 0;
			$mod = 3;
			foreach($pr_idx as $idx)
			{
				$rowx = get_goods($idx, 'simg1');
				if(!$rowx['index_no'])
					continue;

				$href = TB_SHOP_URL.'/view.php?index_no='.$idx;

				if($pr_tot_count % $mod == 0) $k++;

				$pr_tot_count++;
			?>
			<p class="dn c<?php echo $k; ?>">
				<a href="<?php echo $href; ?>"><?php echo get_it_image($idx, $rowx['simg1'], 60, 60); ?></a>
			</p>
			<?php
			}
			if(!$pr_tot_count)
				echo '<p class="no_item">None</p>'
			?>
		</li>
		<?php if($pr_tot_count > 0){ ?>
		<li class="stv_wrap">
			<img src="<?php echo TB_IMG_URL; ?>/bt_qcr_prev.gif" id="up">
			<span id="stv_pg"></span>
			<img src="<?php echo TB_IMG_URL; ?>/bt_qcr_next.gif" id="down">
		</li>
		<?php } ?>
	</ul>
</div>
<!-- } Quick Menu Right Wing End -->

<div class="qbtn_bx">
	<button type="button" id="anc_up">TOP</button>
	<button type="button" id="anc_dw">DOWN</button>
</div>

<script>
$(function() {
	var itemQty = <?php echo $pr_tot_count; ?>; // Total Item Quantity
	var itemShow = <?php echo $mod; ?>; // Quantity of items to show at once
	var Flag = 1; // Page
	var EOFlag = parseInt(itemQty/itemShow); // Divide the whole list, get the page's best value
	var itemRest = parseInt(itemQty%itemShow); // After you get the rest of the value
	if(itemRest > 0) // If you have the rest of it
	{
		EOFlag++; // Increase the maximum value of the page by 1.
	}
	$('.c'+Flag).css('display','block');
	$('#stv_pg').text(Flag+'/'+EOFlag); // Page's initial output value
	$('#up').click(function() {
		if(Flag == 1)
		{
			alert('This is my first time on the list.');
		} else {
			Flag--;
			$('.c'+Flag).css('display','block');
			$('.c'+(Flag+1)).css('display','none');
		}
		$('#stv_pg').text(Flag+'/'+EOFlag); // Reset Page Values
	})
	$('#down').click(function() {
		if(Flag == EOFlag)
		{
			alert('There is no more.');
		} else {
			Flag++;
			$('.c'+Flag).css('display','block');
			$('.c'+(Flag-1)).css('display','none');
		}
		$('#stv_pg').text(Flag+'/'+EOFlag); // Reset Page Values
	});

	$(window).scroll(function () {
		var pos = $("#ft").offset().top - $(window).height();
		if($(this).scrollTop() > 0) {
			$(".qbtn_bx").fadeIn(300);
			if($(this).scrollTop() > pos) {
				$(".qbtn_bx").addClass('active');
			}else{
				$(".qbtn_bx").removeClass('active');
			}
		} else {
			$(".qbtn_bx").fadeOut(300);
		}
	});

	// Quick menu moves to the top of quick menu
    $("#anc_up").click(function(){
        $("html, body").animate({ scrollTop: 0 }, 400);
    });

	// Move To Lower
    $("#anc_dw").click(function(){
		$("html, body").animate({ scrollTop: $(document).height() }, 400);
    });

	// Auto Adjust Left/Right Quick Menu Height
	<?php if(defined('_INDEX_')) { ?>
	var Theight = $("#header").height() - $("#gnb").height();
	var ptop = 30;
	<?php } else { ?>
	var Theight = $("#header").height() - $("#gnb").height();
	var ptop = 20;
	<?php } ?>
	$("#qcr, #qcl").css({'top':ptop + 'px'});

	$(window).scroll(function () {
		if($(this).scrollTop() > Theight) {
			$("#qcr, #qcl").css({'position':'fixed','top':'67px','z-index':'999'});
		} else {
			$("#qcr, #qcl").css({'position':'absolute','top':ptop + 'px'});
		}
	});
});
</script>
<!-- } Right quick menu end-->
