i<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="fpartner_result">
	<h3 class="anc_tit">ご入金いただく口座</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">銀行名</th>
			<td><?php echo $partner['bank_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">口座番号</th>
			<td><?php echo $partner['bank_account']; ?></td>
		</tr>
		<tr>
			<th scope="row">預金主名</th>
			<td><?php echo $partner['bank_holder']; ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="anc_tit">決済情報</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tr>
			<th scope="row">申請日時</th>
			<td><?php echo $partner['reg_time']; ?></td>
		</tr>
		<tr>
			<th scope="row">決済方法</th>
			<td><?php echo ($partner['pay_settle_case']=='1')?"無通帳入金":"クレジットカード"; ?></td>
		</tr>
		<tr>
			<th scope="row">決済金額</th>
			<td>
				<?php 
				if($partner['receipt_price'] > 0) 
					echo display_price($partner['receipt_price']);
				else
					echo /'無料';
				?>
			</td>
		</tr>
		<?php if($partner['pay_settle_case']=='1') { ?>		
		<tr>
			<th scope="row">入金口座</th>
			<td><?php echo $partner['bank_acc']; ?></td>
		</tr>
		<tr>
			<th scope="row">入金者名</th>
			<td><?php echo $partner['deposit_name']; ?></td>
		</tr>
		<?php } ?>
		<?php if($partner['memo']) { ?>
		<tr>
			<th scope="row">伝達事項</th>
			<td><?php echo conv_content($partner['memo'], 0); ?></td>
		</tr>
		<?php } ?>
		</table>
	</div>

	<div class="btn_confirm">
		<a href="<?php echo TB_MURL; ?>" class="btn_medium wset">確認</a>
	</div>
</div>
