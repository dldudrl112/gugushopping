<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_cs.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>Customer Service<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<h3 class="anc_tit">an account to be deposited with</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">Bank name</th>
			<td><?php echo $partner['bank_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">bank account number</th>
			<td><?php echo $partner['bank_account']; ?></td>
		</tr>
		<tr>
			<th scope="row">Deposit owner name</th>
			<td><?php echo $partner['bank_holder']; ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="anc_tit mart30">Payment information</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">Application date</th>
			<td><?php echo $partner['reg_time']; ?></td>
		</tr>
		<tr>
			<th scope="row">Payment method</th>
			<td><?php echo ($partner['pay_settle_case']=='1')?"without bankbook":"Credit card"; ?></td>
		</tr>
		<tr>
			<th scope="row">결제금액</th>
			<td>
				<?php 
				if($partner['receipt_price'] > 0) 
					echo display_price($partner['receipt_price']);
				else
					echo 'For free.';
				?>
			</td>
		</tr>
		<?php if($partner['pay_settle_case']=='1') { ?>		
		<tr>
			<th scope="row">Deposit account</th>
			<td><?php echo $partner['bank_acc']; ?></td>
		</tr>
		<tr>
			<th scope="row">Deposit holder name</th>
			<td><?php echo $partner['deposit_name']; ?></td>
		</tr>
		<?php } ?>
		<?php if($partner['memo']) { ?>
		<tr>
			<th scope="row">Forwarding items</th>
			<td><?php echo conv_content($partner['memo'], 0); ?></td>
		</tr>
		<?php } ?>
		</table>
	</div>

	<div class="btn_confirm">
		<a href="<?php echo TB_URL; ?>" class="btn_large wset">Check</a>
	</div>
</div>
