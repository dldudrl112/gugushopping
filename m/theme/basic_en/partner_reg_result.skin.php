<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="fpartner_result">
	<h3 class="anc_tit">Deposit account</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">name of bank</th>
			<td><?php echo $partner['bank_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">
Account Number</th>
			<td><?php echo $partner['bank_account']; ?></td>
		</tr>
		<tr>
			<th scope="row">Deposit account name</th>
			<td><?php echo $partner['bank_holder']; ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="anc_tit">Payment information</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
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
			<th scope="row">Payment amount</th>
			<td>
				<?php 
				if($partner['receipt_price'] > 0) 
					echo display_price($partner['receipt_price']);
				else
					echo '
free';
				?>
			</td>
		</tr>
		<?php if($partner['pay_settle_case']=='1') { ?>		
		<tr>
			<th scope="row">Deposit account</th>
			<td><?php echo $partner['bank_acc']; ?></td>
		</tr>
		<tr>
			<th scope="row">Name of depositor</th>
			<td><?php echo $partner['deposit_name']; ?></td>
		</tr>
		<?php } ?>
		<?php if($partner['memo']) { ?>
		<tr>
			<th scope="row">
Forwarding</th>
			<td><?php echo conv_content($partner['memo'], 0); ?></td>
		</tr>
		<?php } ?>
		</table>
	</div>

	<div class="btn_confirm">
		<a href="<?php echo TB_MURL; ?>" class="btn_medium wset">check</a>
	</div>
</div>
