<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<!--Welcome to My Page { -->
<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>My Page</p>
	</h2>

	<!-- Welcome to the overview of membership information { -->
	<div id="smb_my_ov">
		<h2>Overview of member information</h2>
		<div id="smb_my_act">
			<ul>
				<li><a href="<?php echo TB_BBS_URL; ?>/register_mod.php" class="btn_lsmall">Modification of membership information</a></li>
				<li><a href="<?php echo TB_BBS_URL; ?>/leave_form.php" class="btn_lsmall grey">Membership Withdrawal</a></li>
			</ul>
		</div>

		<table id="smb_my_tbl">
		<tbody>
		<tr>
			<th scope="row">retention point</th>
			<td><a href="<?php echo TB_SHOP_URL; ?>/point.php"><b><?php echo display_point($member['point']); ?></b></a></td>
			<th scope="row">retained coupon</th>
			<td><a href="<?php echo TB_SHOP_URL; ?>/coupon.php"><b><?php echo display_qty($cp_count); ?></b></a></td>
		</tr>
		<tr>
			<th scope="row">Cell phone number</th>
			<td><?php echo ($member['cellphone'] ? $member['cellphone'] : 'Unregistered'); ?></td>
			<th scope="row">E-Mail</th>
			<td><?php echo ($member['email'] ? $member['email'] : 'Unregistered'); ?></td>
		</tr>
		<tr>
			<th scope="row">Final connection time</th>
			<td><?php echo $member['today_login']; ?></td>
			<th scope="row">When to sign up for membership</th>
			<td><?php echo $member['reg_time']; ?></td>
		</tr>
		<tr>
			<th scope="row">Address</th>
			<td colspan="3"><?php echo sprintf("(%s)", $member['zip']).' '.print_address($member['addr1'], $member['addr2'], $member['addr3'], $member['addr_jibeon']); ?></td>
		</tr>
		</tbody>
		</table>
	</div>
	<!-- } End of member information overview -->

	<!-- Start latest order details { -->
	<div id="smb_my_od">
		<h2 class="anc_tit">Recent order details</h2>
		<div class="tbl_head02 tbl_wrap">
			<table>
			<colgroup>
				<col class="w90">
				<col>
				<col class="w100">
				<col class="w140">
			</colgroup>
			<thead>
			<tr>
				<th scope="col">Order date</th>
				<th scope="col">Product information</th>
				<th scope="col">Payment amount</th>
				<th scope="col">State</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$sql = " select *
					   from shop_order 
					  where mb_id = '{$member['id']}' 
					    and dan <> '0' 
					  group by od_id 
					  order by index_no desc 
					  limit 0, 5 ";
			$result = sql_query($sql);
			for($i=0; $row=sql_fetch_array($result); $i++) {
				$sql = " select * from shop_cart where od_id = '$row[od_id]' ";
				$sql.= " group by gs_id order by io_type asc, index_no asc ";
				$res = sql_query($sql);
				$rowspan = sql_num_rows($res) + 1;

				for($k=0; $ct=sql_fetch_array($res); $k++) {
					$od = get_order($ct['od_no']);
					$gs = unserialize($od['od_goods']);

					$dlcomp = explode('|', trim($od['delivery']));

					$href = TB_SHOP_URL.'/view.php?index_no='.$od['gs_id'];

					if($k == 0) {
			?>
			<tr>
				<td class="tac" rowspan="<?php echo $rowspan; ?>">
					<p class="bold"><?php echo substr($od['od_time'],0,10);?></p>	
					<p class="padt5"><a href="<?php echo TB_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $od['od_id']; ?>" class="btn_small grey">View Details</a></p>			
				</td>
			</tr>
			<?php } ?>
			<tr class="rows">
				<td>
					<div class="ini_wrap">
						<table class="wfull">
						<colgroup>
							<col class="w70">
							<col>
						</colgroup>
						<tr>
							<td class="vat tal"><a href="<?php echo $href; ?>"><?php echo get_od_image($od['od_id'], $gs['simg1'], 60, 60); ?></a></td>
							<td class="vat tal">
								<a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a>
								<p class="padt3 fc_999">Order number : <?php echo $od['od_id']; ?> / Quantity: <?php echo display_qty($od['sum_qty']); ?> / The delivery charge : <?php echo display_price($od['baesong_price']); ?></p>
								<?php if($od['dan'] == 5) { ?>
								<p class="padt3"><a href="<?php echo TB_SHOP_URL; ?>/orderreview.php?gs_id=<?php echo $od['gs_id']; ?>&od_id=<?php echo $od['od_id']; ?>" onclick="win_open(this, 'winorderreview', '650', '530','yes');return false;" class="btn_ssmall bx-white">Preparation of post-purchase period</a></p>		
								<?php } ?>
							</td>
						</tr>
						</table>
					</div>
				</td>
				<td class="tar"><?php echo display_price($od['use_price']); ?></td>
				<td class="tac">
					<p><?php echo $gw_status[$od['dan']]; ?></p>
					<?php if($dlcomp[0] && $od['delivery_no']) { ?>
					<p class="padt3 fc_90"><?php echo $dlcomp[0]; ?><br><?php echo $od['delivery_no']; ?></p>
					<?php } ?>
					<?php if($dlcomp[1] && $od['delivery_no']) { ?>
					<p class="padt3"><?php echo get_delivery_inquiry($od['delivery'], $od['delivery_no'], 'btn_ssmall'); ?></p>
					<?php } ?>
				</td>
			</tr>
			<?php }
			}
			if($i==0)
				echo '<tr><td colspan="4" class="empty_table">We don't have any data.</td></tr>';
			?>
			</tbody>
			</table>
		</div>

		<div class="smb_my_more">
			<a href="<?php echo TB_SHOP_URL; ?>/orderinquiry.php" class="btn_lsmall bx-white">More order details</a>
		</div>
	</div>
	<!-- } End of last order -->

	<!-- Start of the latest hot product { -->
	<div id="smb_my_wish">
		<h2 class="anc_tit">a recent hot item</h2>
		<div class="tbl_head02 tbl_wrap">
			<table>
			<colgroup>
				<col class="w90">
				<col>
				<col class="w100">
				<col class="w140">
			</colgroup>
			<thead>
			<tr>
				<th scope="col">Image</th>
				<th scope="col">Product name</th>
				<th scope="col">Selling</th>
				<th scope="col">Storage dates</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$sql  = " select a.wi_id, a.wi_time, a.gs_id, b.* 
						from shop_wish a left join shop_goods b ON ( a.gs_id = b.index_no )
					   where a.mb_id = '{$member['id']}' 
					   order by a.wi_id desc
					   limit 0, 3 ";
			$result = sql_query($sql);
			for($i=0; $row = sql_fetch_array($result); $i++) {
				$href = TB_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
			?>
			<tr>
				<td class="tac"><a href="<?php echo $href; ?>"><?php echo get_it_image($row['gs_id'], $row['simg1'], 70, 70); ?></a></td>
				<td class="td_name">
					<a href="<?php echo $href; ?>"><?php echo stripslashes($row['gname']); ?></a>
					<p class="fc_999"><?php echo $row['explan']; ?></p>
				</td>
				<td class="tar"><?php echo get_price($row['gs_id']); ?></td>
				<td class="tac"><?php echo $row['wi_time']; ?></td>
			</tr>
			<?php
			}
			if($i == 0)
				echo '<tr><td colspan="4" class="empty_table">No archive history.</td></tr>';
			?>
			</tbody>
			</table>
		</div>

		<div class="smb_my_more">
			<a href="<?php echo TB_SHOP_URL; ?>/wish.php" class="btn_lsmall bx-white">More hot items</a>
		</div>
	</div>
	<!-- } the end of a recent hot product -->

</div>
<!-- }End of My Page -->
