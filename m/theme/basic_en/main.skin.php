<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
?>

<?php if($slider1 = mobile_slider(0, $pt_id)) { ?>
<!-- Start Main Banner { -->
<div id="main_bn">
	<?php echo $slider1; ?>
</div>
<script>
$(document).on('ready', function() {
	$('#main_bn').slick({
		autoplay: true,
		autoplaySpeed: 4000,
		dots: true,
		fade: true
	});
});
</script>
<!-- } main banner end -->
<?php } ?>

<!-- Starting the bottom of the main banner { -->
<ul class="mbm_bn01">
	<li class="bnr1"><?php echo mobile_banner(2, $pt_id); ?></li>
	<li class="bnr2"><?php echo mobile_banner(3, $pt_id); ?></li>
	<li class="bnr3"><?php echo mobile_banner(4, $pt_id); ?></li>
</ul>
<!-- } Main banner bottom end -->

<!-- Start shopping special { -->
<div class="pr_slide" id="type5">
	<?php echo mobile_slide_goods('5', '20', 'Special price for shopping', 'slider'); ?>
	<script>
    $(document).on('ready', function() {
      $("#type5 .slider").slick({
		autoplay: true,
        dots: false,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1
      });
    });
	</script>
</div>
<!-- } the end of a special shopping trip -->

<?php
if($default['de_maintype_best']) {
	$list_best = unserialize(base64_decode($default['de_maintype_best']));
	$list_count = count($list_best);
?>
<!-- Best Start by Category {-->
<div class="bscate mart30">
	<h2 class="mtit"><span><?php echo $default['de_maintype_title']; ?></span></h2>
	<div class="bscate_tab">
		<?php for($i=0; $i<$list_count; $i++) { ?>
		<a><span><?php echo trim($list_best[$i]['subj']); ?></span></a>
		<?php } ?>
	</div>
	<div class="bscate_li">
		<?php echo mobile_listtype_cate($list_best); ?>
	</div>
	<script>
	$(document).ready(function(){
		$('.bscate_li').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			infinite: false,
			asNavFor: '.bscate_tab'
		});
		$(".bscate_tab").slick({
			autoplay: false,
			dots: false,
			infinite: false,
			centerMode: true,
			variableWidth: true,
			slidesToScroll: 1,
			asNavFor: '.bscate_li',
			focusOnSelect: true
		});
	});
	</script>
</div>
<!-- } Best end by category -->
<?php } ?>

<?php if($banner = mobile_banner(5, $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- Start Best Sellers {-->
<div class="mart30">
	<?php echo mobile_display_goods('2', '6', 'Best Seller', 'pr_desc wli2'); ?>
</div>
<!-- } the end of a bestseller -->

<?php if($banner = mobile_banner(6, $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- Start a new product { -->
<div class="mart30">
	<?php echo mobile_display_goods('3', '6', 'New product', 'pr_desc wli2'); ?>
</div>
<!-- } the end of a new product -->

<?php if($banner = mobile_banner(7, $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- Start Popularity { -->
<div class="mart30">
	<?php echo mobile_display_goods('4', '6', 'Popular product', 'pr_desc wli2'); ?>
</div>
<!-- } End of Popularity -->

<?php if($banner = mobile_banner(8, $pt_id)) { ?>
<div class="ad mart30"><?php echo $banner; ?></div>
<?php } ?>

<!-- Start Recommendation { -->
<div class="mart30">
	<?php echo mobile_display_goods('5', '6', 'Recommended product', 'pr_desc wli2'); ?>
</div>
<!-- } End of Recommendation -->
