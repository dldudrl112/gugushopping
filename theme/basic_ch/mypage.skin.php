<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<!-- 启动我的页面 { -->
<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>我的页面</p>
	</h2>

	<!-- 会员资料概述 { -->
	<div id="smb_my_ov">
		<h2>会员信息概述</h2>
		<div id="smb_my_act">
			<ul>
				<li><a href="<?php echo TB_BBS_URL; ?>/register_mod.php" class="btn_lsmall">
编辑您的会员信息</a></li>
				<li><a href="<?php echo TB_BBS_URL; ?>/leave_form.php" class="btn_lsmall grey">离开会员</a></li>
			</ul>
		</div>

		<table id="smb_my_tbl">
		<tbody>
		<tr>
			<th scope="row">保持点</th>
			<td><a href="<?php echo TB_SHOP_URL; ?>/point.php"><b><?php echo display_point($member['point']); ?></b></a></td>
			<th scope="row">
拥有优惠券</th>
			<td><a href="<?php echo TB_SHOP_URL; ?>/coupon.php"><b><?php echo display_qty($cp_count); ?></b></a></td>
		</tr>
		<tr>
			<th scope="row">手机号码</th>
			<td><?php echo ($member['cellphone'] ? $member['cellphone'] : '未注册'); ?></td>
			<th scope="row">E-Mail</th>
			<td><?php echo ($member['email'] ? $member['email'] : '未注册'); ?></td>
		</tr>
		<tr>
			<th scope="row">上次访问日期</th>
			<td><?php echo $member['today_login']; ?></td>
			<th scope="row">会员日期和时间</th>
			<td><?php echo $member['reg_time']; ?></td>
		</tr>
		<tr>
			<th scope="row">
地址</th>
			<td colspan="3"><?php echo sprintf("(%s)", $member['zip']).' '.print_address($member['addr1'], $member['addr2'], $member['addr3'], $member['addr_jibeon']); ?></td>
		</tr>
		</tbody>
		</table>
	</div>
	<!-- } 会员信息摘要结束 -->

	<!-- 开始最近的订单记录 { -->
	<div id="smb_my_od">
		<h2 class="anc_tit">最近的订单历史</h2>
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
				<th scope="col">
订单日期</th>
				<th scope="col">产品信息</th>
				<th scope="col">产品确认</th>
				<th scope="col">状态</th>
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
					<p class="padt5"><a href="<?php echo TB_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $od['od_id']; ?>" class="btn_small grey">查看详细</a></p>			
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
								<p class="padt3 fc_999">订单号码 : <?php echo $od['od_id']; ?> / 数量 : <?php echo display_qty($od['sum_qty']); ?> / 配送费 : <?php echo display_price($od['baesong_price']); ?></p>
								<?php if($od['dan'] == 5) { ?>
								<p class="padt3"><a href="<?php echo TB_SHOP_URL; ?>/orderreview.php?gs_id=<?php echo $od['gs_id']; ?>&od_id=<?php echo $od['od_id']; ?>" onclick="win_open(this, 'winorderreview', '650', '530','yes');return false;" class="btn_ssmall bx-white">购买后期制作</a></p>		
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
				echo '<tr><td colspan="4" class="empty_table">没有资料。</td></tr>';
			?>
			</tbody>
			</table>
		</div>

		<div class="smb_my_more">
			<a href="<?php echo TB_SHOP_URL; ?>/orderinquiry.php" class="btn_lsmall bx-white">
更多订单历史</a>
		</div>
	</div>
	<!-- } 您的上次订单历史记录已结束。 -->

	<!-- 最近推出的产品 { -->
	<div id="smb_my_wish">
		<h2 class="anc_tit">最近的项目</h2>
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
				<th scope="col">形象</th>
				<th scope="col">商品名称</th>
				<th scope="col">售价</th>
				<th scope="col">保管日期</th>
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
				echo '<tr><td colspan="4" class="empty_table">保管明细不存在。</td></tr>';
			?>
			</tbody>
			</table>
		</div>

		<div class="smb_my_more">
			<a href="<?php echo TB_SHOP_URL; ?>/wish.php" class="btn_lsmall bx-white">更多看热卖商品</a>
		</div>
	</div>
	<!-- } 最近热乎乎的商品结束了 -->

</div>
<!-- } 我的页面结束 -->
