<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
?>

<!-- Start coupon authentication { -->
<div id="sct_hdesc">
	<fieldset class="coupon_se">
		<h3>Please enter 16 characters of coupon number.</h3>
		<form name="fcouponcard" action="<?php echo $form_action_url; ?>" onsubmit="return form_check(this);" method="post" autocomplete="off">
		<input type="hidden" name="token" value="<?php echo $token; ?>">
		<div class="frm_inner">
			<input type="text" name="gi_num1" id="gi_num1" class="gi_num" maxlength="4" onkeyup="if(this.value.length==4) document.fcouponcard.gi_num2.focus();">
			<input type="text" name="gi_num2" id="gi_num2" class="gi_num" maxlength="4" onkeyup="if(this.value.length==4) document.fcouponcard.gi_num3.focus();">
			<input type="text" name="gi_num3" id="gi_num3" class="gi_num" maxlength="4" onkeyup="if(this.value.length==4) document.fcouponcard.gi_num4.focus();">
			<input type="text" name="gi_num4" id="gi_num4" class="gi_num" maxlength="4">			
		</div>	
		<div class="tac">
			<button type="submit" id="btn_submit" class="btn_large wset">Coupon registration</button>
		</div>
		</form>

		<script>
		function form_check(f) 
		{
			errmsg = "";
			errfld = "";

			check_field(f.gi_num1, "Please enter the first coupon number.");
			check_field(f.gi_num2, "Enter second coupon number.");
			check_field(f.gi_num3, "Please enter the third coupon number.");
			check_field(f.gi_num4, "Please enter the fourth coupon number.");

			if(errmsg)
			{
				alert(errmsg);
				errfld.focus();
				return false;
			}

			var gi_num = 0;
			gi_num += f.gi_num1.value.length;
			gi_num += f.gi_num2.value.length;
			gi_num += f.gi_num3.value.length;
			gi_num += f.gi_num4.value.length;

			if(gi_num < 16) {
				alert("The coupon number is 16 characters in all. Please check and re-register.");
				return false;
			}

			document.getElementById("btn_submit").disabled = true;

			return true;
		}
		</script>
	</fieldset>	
</div>

<p id="page_title">
	<em>Total <?php echo number_format($total_count); ?>case</em>There is a coupon history of.
</p>

<div id="scp_list">
	<ul>
		<?php 		
		for($i=0;$row=sql_fetch_array($result);$i++) { 
			$cp_date = substr($row['gr_sdate'],2,8).' ~ '.substr($row['gr_edate'],2,8);
		?>
		<li>
			<div class="li_title"><?php echo get_text($row['gr_subject']); ?></div>
			<div class="li_pd">
				<span class="pd_price"><?php echo display_point($row['gr_price']); ?></span>
				<span class="pd_date"><?php echo $cp_date; ?></span>
			</div>
			<div class="li_target">
				<?php echo get_text($row['gi_num']); ?>
			</div>
		</li>
		<?php
		}

		if($i == 0)
			echo '<li class="empty_list">No history</li>';
		?>
	</ul>
</div>
<!-- } End of coupon verification -->
