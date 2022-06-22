<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<div id="sod_fin">
	<div id="sod_fin_no">注文番号 <strong><?php echo $od_id; ?></strong></div>

	<section id="sod_fin_list">
        <h2>ご注文いただいた商品</h2>
        <ul id="sod_list_inq" class="sod_list">
            <?php
			$st_count1 = $st_count2 = $st_cancel_price = 0;
			$custom_cancel = false;

			$sql = " select * from shop_cart where od_id = '$od_id' group by gs_id order by index_no ";	
			$result = sql_query($sql);
			for($i=0; $row=sql_fetch_array($result); $i++) {
				$rw = get_order($row['od_no']);
				$gs = unserialize($rw['od_goods']);

				$dlcomp = explode('|', trim($rw['delivery']));

				$href = TB_MSHOP_URL.'/view.php?gs_id='.$rw['gs_id'];

				unset($it_name);
				$it_options = mobile_print_complete_options($row['gs_id'], $row['od_id']);
				if($it_options){
					$it_name = '<div class="li_name_od">'.$it_options.'</div>';
				}

				$li_btn = '';
				if($rw['dan'] == 5) {
					$li_btn .= '<a href="'.TB_MSHOP_URL.'/orderreview.php?gs_id='.$rw['gs_id'].'" onclick="win_open(this, \'winorderreview\');return false;" class="btn_ssmall bx-white">購入後期</a>';
				}

				if($dlcomp[1] && $rw['delivery_no']) {
					$li_btn .= get_delivery_inquiry($rw['delivery'], $rw['delivery_no'], 'btn_ssmall bx-white');
				}

				if($li_btn) 
					$li_btn = '<div class="li_btn">'.$li_btn.'</div>';
            ?>	
			<li class="sod_li">
                <div class="li_opt"><a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a></div>
				<?php echo $it_name; ?>
				<?php echo $li_btn; ?>
                <div class="li_prqty">
                    <span class="prqty_price li_prqty_sp"><span>商品金額 </span><?php echo number_format($rw['goods_price']); ?></span>
                    <span class="prqty_qty li_prqty_sp"><span>数量 </span><?php echo number_format($rw['sum_qty']); ?></span>
                    <span class="prqty_sc li_prqty_sp"><span>配送費 </span><?php echo number_format($rw['baesong_price']); ?></span>
                    <span class="prqty_stat li_prqty_sp"><span>状態 </span><?php echo $gw_status[$rw['dan']]; ?></span>
                </div>
                <div class="li_total" style="padding-left:60px;height:auto !important;height:50px;min-height:50px;">
                    <a href="<?php echo $href; ?>" class="total_img"><?php echo get_od_image($rw['od_id'], $gs['simg1'], 50, 50); ?></a>
                    <span class="total_price total_span"><span>決済金額 </span><?php echo number_format($rw['use_price']); ?></span>
                    <span class="total_point total_span"><span>積立ポイント </span><?php echo number_format($rw['sum_point']); ?></span>
                </div>
				<?php if($dlcomp[0] && $rw['delivery_no']) { ?>
				<div class="li_dvr">
					<strong class="fc_107">配送情報</strong>
					<?php echo $dlcomp[0]; ?>(請求書番号 : <?php echo $rw['delivery_no']; ?>)
				</div>
				<?php } ?>
            </li>
            <?php 
				$st_count1++;
				if(in_array($rw['dan'], array('1','2','3')))
					$st_count2++;

				$st_cancel_price += $rw['cancel_price'];
			}

			// 注文状態が配送中,前の段階なら顧客取り消し可能
			if($st_count1 > 0 && $st_count1 == $st_count2)
				$custom_cancel = true;
			?>
        </ul>

		<dl id="sod_bsk_tot">
            <dt class="sod_bsk_dvr"><span>注文総額</span></dt>
            <dd class="sod_bsk_dvr"><strong><?php echo display_price($stotal['price']); ?></strong></dd>

            <?php if($stotal['coupon']) { ?>
            <dt class="sod_bsk_dvr"><span>クーポン割引</span></dt>
            <dd class="sod_bsk_dvr"><strong><?php echo display_price($stotal['coupon']); ?></strong></dd>
            <?php } ?>

            <?php if($stotal['usepoint']) { ?>
            <dt class="sod_bsk_dvr"><span>ポイント決済</span></dt>
            <dd class="sod_bsk_dvr"><strong><?php echo display_point($stotal['usepoint']); ?></strong></dd>
            <?php } ?>

            <?php if($stotal['baesong']) { ?>
            <dt class="sod_bsk_dvr"><span>配送費</span></dt>
            <dd class="sod_bsk_dvr"><strong><?php echo display_price($stotal['baesong']); ?></strong></dd>
            <?php } ?>

            <dt class="sod_bsk_cnt"><span>総計</span></dt>
            <dd class="sod_bsk_cnt"><strong><?php echo display_price($stotal['useprice']); ?></strong></dd>

            <dt class="sod_bsk_point"><span>ポイント積立</span></dt>
            <dd class="sod_bsk_point"><strong><?php echo display_point($stotal['point']); ?></strong></dd>
        </dl>
    </section>

	<section id="sod_fin_pay">
		<h3 class="anc_tit">決済情報</h3>
		<div class="odf_tbl">
			<table>
			<colgroup>
				<col class="w70">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th scope="row">注文番号</th>
				<td><?php echo $od_id; ?></td>
			</tr>
			<tr>
				<th scope="row">注文日時</th>
				<td><?php echo $od['od_time']; ?></td>
			</tr>
			<tr>
				<th scope="row">決済方式</th>
				<td><?php echo ($easy_pay_name ? $easy_pay_name.'('.$od['paymethod'].')' : $od['paymethod']); ?></td>
			</tr>
			<tr>
				<th scope="row">決済金額</th>
				<td><?php echo display_price($stotal['useprice']); ?></td>
			</tr>
			<?php
			if(!is_null_time($od['receipt_time'])) {
			?>
			<tr>
				<th scope="row">決済日時</th>
				<td><?php echo $od['receipt_time']; ?></td>
			</tr>
			<?php
			}

			// 承認番号,携帯番号,取引番号
			if($app_no_subj) {
			?>
			<tr>
				<th scope="row"><?php echo $app_no_subj; ?></th>
				<td><?php echo $app_no; ?></td>
			</tr>
			<?php
			}

			// 口座情報
			if($disp_bank) {
			?>
			<tr>
				<th scope="row">入金者名</th>
				<td><?php echo get_text($od['deposit_name']); ?></td>
			</tr>
			<tr>
				<th scope="row">入金口座</th>
				<td><?php echo get_text($od['bank']); ?></td>
			</tr>
			<?php
			}

			if($disp_receipt) {
			?>
			<tr>
				<th scope="row">領収書</th>
				<td>
					<?php
					if($od['paymethod'] == /'携帯電話')
					{
						if($od['od_pg'] == 'lg') {
							require_once TB_SHOP_PATH.'/settle_lg.inc.php';
							$LGD_TID      = $od['od_tno'];
							$LGD_MERTKEY  = $default['de_lg_mid'];
							$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

							$hp_receipt_script = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';							
						} else if($od['od_pg'] == 'inicis') {
							$hp_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid='.$od['od_tno'].'&noMethod=1\',\'receipt\',\'width=430,height=700\');';	
						} else if($od['od_pg'] == 'kcp') {
							$hp_receipt_script = 'window.open(\''.TB_BILL_RECEIPT_URL.'mcash_bill&tno='.$od['od_tno'].'&order_no='.$od['od_id'].'&trade_mony='.$stotal['useprice'].'\', \'winreceipt\', \'width=500,height=690,scrollbars=yes,resizable=yes\');';
						}
					?>
					<a href="javascript:;" onclick="<?php echo $hp_receipt_script; ?>" class="btn_small">領収書の出力</a>
					<?php
					}

					if($od['paymethod'] == /'クレジットカード' || $od['paymethod'] == /'Samsung Pay')
					{
						if($od['od_pg'] == 'lg') {
							require_once TB_SHOP_PATH.'/settle_lg.inc.php';
							$LGD_TID      = $od['od_tno'];
							$LGD_MERTKEY  = $default['de_lg_mid'];
							$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

							$card_receipt_script = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
						} else if($od['od_pg'] == 'inicis') {
							$card_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid='.$od['od_tno'].'&noMethod=1\',\'receipt\',\'width=430,height=700\');';
						} else if($od['od_pg'] == 'kcp') {
							$card_receipt_script = 'window.open(\''.TB_BILL_RECEIPT_URL.'card_bill&tno='.$od['od_tno'].'&order_no='.$od['od_id'].'&trade_mony='.$stotal['useprice'].'\', \'winreceipt\', \'width=470,height=815,scrollbars=yes,resizable=yes\');';
						}
					?>
					<a href="javascript:;" onclick="<?php echo $card_receipt_script; ?>" class="btn_small">領収書出力</a>
					<?php
					}

					if($od['paymethod'] == 'KAKAOPAY')
					{
						$card_receipt_script = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID='.$od['od_tno'].'&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';						
					?>
					<a href="javascript:;" onclick="<?php echo $card_receipt_script; ?>" class="btn_small">領収書出力</a>
					<?php
					}
					?>
				</td>
			</tr>
			<?php
			}

			// 現金領収証発給を使う場合に限って
			if($default['de_taxsave_use']) {
				// 未収金がなく,現金である場合のみ現金領収証を発行することができます。
				if(!is_null_time($od['receipt_time']) && ($od['paymethod'] == /'無通帳' || $od['paymethod'] == /'振込み' || $od['paymethod'] == /'仮想口座')) {
			?>
			<tr>
				<th scope="row">現金領収証</th>
				<td>
				<?php
				if($od['od_cash'])
				{
					if($od['od_pg'] == 'lg') {
						require_once TB_SHOP_PATH.'/settle_lg.inc.php';

						switch($od['paymethod']) {
							case /'振込み':
								$trade_type = 'BANK';
								break;
							case '仮想口座':
								$trade_type = 'CAS';
								break;
							default:
								$trade_type = 'CR';
								break;
						}
						$cash_receipt_script = 'javascript:showCashReceipts(\''.$LGD_MID.'\',\''.$od['od_id'].'\',\''.$od['od_casseqno'].'\',\''.$trade_type.'\',\''.$CST_PLATFORM.'\');';
					} else if($od['od_pg'] == 'inicis') {
						$cash = unserialize($od['od_cash_info']);
						$cash_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/Cash_mCmReceipt.jsp?noTid='.$cash['TID'].'&clpaymethod=22\',\'showreceipt\',\'width=380,height=540,scrollbars=no,resizable=no\');';
					} else if($od['od_pg'] == 'kcp') {
						require_once TB_SHOP_PATH.'/settle_kcp.inc.php';

						$cash = unserialize($od['od_cash_info']);
						$cash_receipt_script = 'window.open(\''.TB_CASH_RECEIPT_URL.$default['de_kcp_mid'].'&orderid='.$od_id.'&bill_yn=Y&authno='.$cash['receipt_no'].'\', \'taxsave_receipt\', \'width=360,height=647,scrollbars=0,menus=0\');';
					}
				?>
					<a href="javascript:;" onclick="<?php echo $cash_receipt_script; ?>" class="btn_small">現金領収証を確認</a>
				<?php
				}
				else {
				?>
					<a href="javascript:;" onclick="window.open('<?php echo TB_MSHOP_URL; ?>/taxsave.php?od_id=<?php echo $od_id; ?>', 'taxsave', 'width=550,height=400,scrollbars=1,menus=0');" class="btn_small">現金領収書の発行する</a>
				<?php } ?>
				</td>
			</tr>
			<?php
				}
			}
			?>
			</tbody>
			</table>
		</div>
	</section>

	<section id="sod_fin_orderer">
		<h3 class="anc_tit">注文した方</h3>
		<div class="odf_tbl">
			<table>
			<colgroup>
				<col class="w70">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th scope="row">名前</th>
				<td><?php echo get_text($od['name']); ?></td>
			</tr>
			<tr>
				<th scope="row">電話番号</th>
				<td><?php echo get_text($od['telephone']); ?></td>
			</tr>
			<tr>
				<th scope="row">携帯電話</th>
				<td><?php echo get_text($od['cellphone']); ?></td>
			</tr>
			<tr>
				<th scope="row">主消</th>
				<td><?php echo get_text(sprintf("(%s)", $od['zip']).' '.print_address($od['addr1'], $od['addr2'], $od['addr3'], $od['addr_jibeon'])); ?></td>
			</tr>
			<tr>
				<th scope="row">E-mail</th>
				<td><?php echo get_text($od['email']); ?></td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="sod_fin_receiver">
		<h3 class="anc_tit">お受け取りの方</h3>
		<div class="odf_tbl">
			<table>
			<colgroup>
				<col class="w70">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th scope="row">名前</th>
				<td><?php echo get_text($od['b_name']); ?></td>
			</tr>
			<tr>
				<th scope="row">電話番号</th>
				<td><?php echo get_text($od['b_telephone']); ?></td>
			</tr>
			<tr>
				<th scope="row">携帯電話</th>
				<td><?php echo get_text($od['b_cellphone']); ?></td>
			</tr>
			<tr>
				<th scope="row">州の牛</th>
				<td><?php echo get_text(sprintf("(%s)", $od['b_zip']).' '.print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon'])); ?></td>
			</tr>
			<?php if($od['memo']) { ?>
			<tr>
				<th scope="row">伝言</th>
				<td><?php echo conv_content($od['memo'], 0); ?></td>
			</tr>
			<?php } ?>
			</tbody>
			</table>
		</div>
	</section>

	<?php
	// 取り消した内訳がなければ
	if($st_cancel_price == 0 && $custom_cancel) { 
	?>
	<section id="sod_fin_cancel">
		<h2>注文取消</h2>
		<button type="button" onclick="document.getElementById('sod_fin_cancelfrm').style.display='block';" class="btn_medium wset">注文キャンセル</button>

		<div id="sod_fin_cancelfrm">
			<form method="post" action="<?php echo TB_MSHOP_URL; ?>/orderinquirycancel.php" onsubmit="return fcancel_check(this);">
			<input type="hidden" name="od_id"  value="<?php echo $od_id; ?>">
			<input type="hidden" name="token"  value="<?php echo $token; ?>">
			<label for="cancel_memo">取消し事由</label>
			<input type="text" name="cancel_memo" id="cancel_memo" required class="frm_input required" maxlength="100">
			<input type="submit" value="確認" class="btn_small">
			</form>
		</div>
	</section>
	<?php } ?>

</div>

<script>
function fcancel_check(f)
{
    if(!confirm("ご注文をほんとうに取り消すでしょうか?"))
        return false;

    var memo = f.cancel_memo.value;
    if(memo == "") {
        alert("取消事由を入力してください.");
        return false;
    }

    return true;
}
</script>
