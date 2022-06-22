<?php
if(!defined('_TUBEWEB_')) exit;
?>
<!-- 장바구니 시작 { -->
<script src="<?php echo TB_JS_URL; ?>/shop.js"></script>

<!-- wish_list Area Start -->
<form name="frmcartlist" id="sod_bsk_list" method="post" action="<?php echo $cart_action_url; ?>">
<div id="wish_list" class="pb_12">
	<div class="sub_title04">
	  <div class="container">
		<!-- 모바일 히스토리 뒤로가기 버튼 -->
		<div id="history_back">
		  <button onclick="history.back()"><img src="<?php echo TB_IMG_URL; ?>/hi_back_ico.png" alt="history_back"></button>
		</div><!-- 모바일 히스토리 뒤로가기 버튼 -->
		<h2><span>찜주머니</span></h2>
	  </div>
	</div>
	<!-- List_box Area Start -->
	<div class="list_box">
	  <div class="container">
		<ul class="clearfix">
		<?php
		$tot_point			=	0;
		$tot_sell_price		=	0;
		$tot_opt_price		=	0;
		$tot_sell_qty		=	0;
		$tot_sell_amt		=	0;
		$upl_dir				=	TB_DATA_URL."/member";

		for($i=0; $row=sql_fetch_array($result); $i++) {

			$gs	=	get_goods($row['gs_id']);
			$ca	=	sql_fetch("select b.catename from shop_goods_cate a, shop_cate b where a.gcate = b.catecode and  a.gs_id = '".$gs["index_no"]."'");
			$tc	=	sql_fetch("select a.*, b.mem_img from shop_teacher a, shop_member b where a.mb_code = b.index_no and a.index_no = '".$gs["tc_code"]."'");

			// 합계금액 계산
			$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
							SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
							SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
							SUM(io_price * ct_qty) as opt_price
						from shop_cart
					   where gs_id = '$row[gs_id]'
						 and ct_direct = '$set_cart_id'
						 and ct_select = '0'";
			$sum = sql_fetch($sql);

			if($i==0) { // 계속쇼핑
				$continue_ca_id = $row['ca_id'];
			}

			unset($it_name);
			unset($mod_options);
			$it_options = print_item_options($row['gs_id'], $set_cart_id);
			if($it_options) {
				$mod_options = '<div class="sod_option_btn"><button type="button" class="btn_small bx-white mod_options">옵션변경/추가</button></div>';
				$it_name = '<div class="sod_opt">'.$it_options.'</div>';
			}

			$point = $sum['point'];
			$sell_price = $sum['price'];
			$sell_opt_price = $sum['opt_price'];
			$sell_qty = $sum['qty'];
			$sell_amt = $sum['price'] - $sum['opt_price'];

			// 배송비
			if($gs['use_aff'])
				$sr = get_partner($gs['mb_id']);
			else
				$sr = get_seller_cd($gs['mb_id']);

			$info = get_item_sendcost($sell_price);
			$item_sendcost[] = $info['pattern'];

			$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
		?>
		  <li>
			<!-- PC Area Start -->
			<a href="<?=$it_href?>" class="pc">
			  <div class="txt_top">
				<div class="icon_box">
				  <div class="cate_ico">#<?=$ca["catename"]?></div>
  				  <?=counseling_sticker($tc["counseling_type"],'pc')?>
				</div>
				<div class="txt">
				  <h3><?=$gs["gname"]?></h3>
				  <dl>
					<dt class="time">상담시간 <span><?=$tc["counseling_time"]?></span></dt>
					<dd class="point"><img src="<?php echo TB_IMG_URL; ?>/point_ico.png" alt="point">5.0<span class="count">(13)</span></dd>
				  </dl>
				</div>
			  </div>
			  <div class="txt_bottom">
				<div class="img_box"><img src="<?php echo TB_IMG_URL; ?>/mem_img.png" alt="img"></div>
				<div class="name"><?=$tc["name"]?></div>
				<div class="price"><?php echo get_price($row['gs_id']); ?></div>
			  </div>
			</a><!-- PC Area End -->
			<!-- Mobild Area Start -->
			<a href="<?=$it_href?>" class="mb">
			  <div class="top">
				<div class="m_h">
				  <div class="subject"><?=$gs["gname"]?></div>
				  <div class="point"><img src="<?php echo TB_IMG_URL; ?>/m_point_ico.png" alt="point">5.0<span class="count">(13)</span></div>
				</div>
				<div class="m_b">
				  <div class="time">상담시간 <span><?=$tc["counseling_time"]?></span></div>
				  <div class="price"><?php echo get_price($row['gs_id']); ?></div>
				</div>
			  </div>
			  <div class="bottom">
				<div class="user">
				  <div class="img_box"><img src="<?php echo $upl_dir; ?>/<?=$tc["mem_img"]?>" alt="img"></div>
				  <div class="name"><?=$tc["name"]?></div>
				  <div class="cate_ico">#<?=$ca["catename"]?></div>
				</div>
				<?=counseling_sticker($tc["counseling_type"],'mo')?>
			  </div>
			</a><!-- Mobild Area End -->
		  </li>
	<?php
		$tot_point		+= $point;
		$tot_sell_price += $sell_price;
		$tot_opt_price	+= $sell_opt_price;
		$tot_sell_qty	+= $sell_qty;
		$tot_sell_amt	+= $sell_amt;

		if(!$is_member) {
			$tot_point = 0;
		}
	} // for

	if($i == 0) {
		echo '<li><div class="txt_top">찜주머니에 담긴 상품이 없습니다.</div></li>';
	}

	// 배송비 검사
	$send_cost = 0;
	$com_send_cost = 0;
	$sep_send_cost = 0;
	$max_send_cost = 0;

	if($i > 0) {
		$k = 0;
		$condition = array();
		foreach($item_sendcost as $key) {
			list($userid, $bundle, $price) = explode('|', $key);
			$condition[$userid][$bundle][$k] = $price;
			$k++;
		}

		$com_array = array();
		$val_array = array();
		foreach($condition as $key=>$value) {
			if($condition[$key]['묶음']) {
				$com_send_cost += array_sum($condition[$key]['묶음']); // 묶음배송 합산
				$max_send_cost += max($condition[$key]['묶음']); // 가장 큰 배송비 합산
				$com_array[] = max(array_keys($condition[$key]['묶음'])); // max key
				$val_array[] = max(array_values($condition[$key]['묶음']));// max value
			}
			if($condition[$key]['개별']) {
				$sep_send_cost += array_sum($condition[$key]['개별']); // 묶음배송불가 합산
				$com_array[] = array_keys($condition[$key]['개별']); // 모든 배열 key
				$val_array[] = array_values($condition[$key]['개별']); // 모든 배열 value
			}
		}

		$tune = get_tune_sendcost($com_array, $val_array);

		$send_cost = $com_send_cost + $sep_send_cost; // 총 배송비합계
		$tot_send_cost = $max_send_cost + $sep_send_cost; // 최종배송비
		$tot_final_sum = $send_cost - $tot_send_cost; // 배송비할인
		$tot_price = $tot_sell_price + $tot_send_cost; // 결제예정금액
	}
	?>
		</ul>
	  </div>
	</div><!-- List_box Area End -->
</div>
</form>
<!-- wish_list Area End -->