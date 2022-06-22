<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div><img src="<?php echo TB_IMG_URL; ?>/seller_reg_result.gif"></div>

<h3 class="anc_tit mart20">Business license information</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">Provided goods</th>
		<td><?php echo $seller['seller_item']; ?></td>
	</tr>
	<tr>
		<th scope="row">Company(corporation)name</th>
		<td><?php echo $seller['company_name']; ?></td>
	</tr>
	<tr>
		<th scope="row">Representative name</th>
		<td><?php echo $seller['company_owner']; ?></td>
	</tr>
	<tr>
		<th scope="row">Business license number</th>
		<td><?php echo $seller['company_saupja_no']; ?></td>
	</tr>
	<tr>
		<th scope="row">karma</th>
		<td><?php echo $seller['company_item']; ?></td>
	</tr>
	<tr>
		<th scope="row">Karma</th>
		<td><?php echo $seller['company_service']; ?></td>
	</tr>
	<tr>
		<th scope="row">Phone number</th>
		<td><?php echo $seller['company_tel']; ?></td>
	</tr>
	<tr>
		<th scope="row">Fax number</th>
		<td><?php echo $seller['company_fax']; ?></td>
	</tr>
	<tr>
		<th scope="row">Business address</th>
		<td><?php echo print_address($seller['company_addr1'], $seller['company_addr2'], $seller['company_addr3'], $seller['company_addr_jibeon']); ?></td>
	</tr>
	<?php if($seller['company_hompage']) { ?>
	<tr>
		<th scope="row">Homepage</th>
		<td><?php echo $seller['company_hompage']; ?></td>
	</tr>
	<?php } ?>
	<?php if($seller['memo']) { ?>
	<tr>
		<th scope="row">Forwarding items</th>
		<td><?php echo conv_content($seller['memo'], 0); ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<h3 class="anc_tit mart30">Deposit account information</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">Bank name</th>
		<td><?php echo $seller['bank_name']; ?></td>
	</tr>
	<tr>
		<th scope="row">bank account number</th>
		<td><?php echo $seller['bank_account']; ?></td>
	</tr>
	<tr>
		<th scope="row">Deposit owner name</th>
		<td><?php echo $seller['bank_holder']; ?></td>
	</tr>
	</tbody>
	</table>
</div>

<h3 class="anc_tit mart30">Staff information</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">Name of person in charge</th>
		<td><?php echo $seller['info_name']; ?></td>
	</tr>
	<tr>
		<th scope="row">person in charge cell phone</th>
		<td><?php echo $seller['info_tel']; ?></td>
	</tr>
	<tr>
		<th scope="row">Contact Email</th>
		<td><?php echo $seller['info_email']; ?></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<a href="<?php echo TB_URL; ?>" class="btn_large wset">Check</a>
</div>
