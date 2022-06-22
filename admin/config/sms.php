<?php
if(!defined('_TUBEWEB_')) exit;

$sms = sql_fetch("select * from shop_sms");

if(!$sms['cf_icode_server_ip'])   $sms['cf_icode_server_ip'] = '211.172.232.124';
if(!$sms['cf_icode_server_port']) $sms['cf_icode_server_port'] = '7295';

$sms_call_cnt	=	0;


if($sms['cf_sms_use'] && $sms['cf_icode_id']) {
    $userinfo = get_sms_userinfo($sms['cf_icode_id']);		

	if($userinfo->e_code == "0000"){
		$sms_call_cnt	=	$userinfo->sms_call_cnt;	
	}
}

if(!$sms['cf_icode_id']) {
    $sms['cf_icode_id'] = 'gnd_';
}

$seq_sms = array(
	array("","","",""),
	array("회원가입",
		  "이름 : <b>{이름}</b>, 아이디: <b>{아이디}</b>",
		  "",
		  "사이트 회원가입 즉시 문자발송"
	),
	array("주문완료",
		  "이름 : <b>{이름}</b>, 주문번호: <b>{주문번호}</b>",
		  "",
		  "상품 주문완료시 문자발송"
	),
	array("입금확인 (무통장)",
		  "이름 : <b>{이름}</b>, 주문번호: <b>{주문번호}</b>",
		  "",
		  "입금확인 후 상품준비중으로 변경시"
	),
	array("상품발주",
		  "이름 : <b>{이름}</b>, 주문번호: <b>{주문번호}</b>",
		  "업체 : <b>{업체}</b>, 송장번호: <b>{송장번호}</b>",
		  "주문상품을 배송중으로 단계 변경시"
	),
	array("주문취소",
		  "이름 : <b>{이름}</b>, 주문번호: <b>{주문번호}</b>",
		  "",
		  "주문상품을 취소할 경우"
	),
	array("배송완료",
		  "이름 : <b>{이름}</b>, 주문번호: <b>{주문번호}</b>",
		  "업체 : <b>{업체}</b>, 송장번호: <b>{송장번호}</b>",
		  "주문상품을 배송완료 단계 변경시"
	)
);
?>

<script>
function byte_check(el_message, el_byte, el_max_byte)
{
    var conts = document.getElementById(el_message);
    var bytes = document.getElementById(el_byte);
    var max_bytes = document.getElementById(el_max_byte);

    var i = 0;
    var cnt = 0;
    var exceed = 0;
    var ch = '';

    for(i=0; i<conts.value.length; i++)
    {
        ch = conts.value.charAt(i);
        if(escape(ch).length > 4) {
            cnt += 2;
        } else {
            cnt += 1;
        }
    }

    bytes.innerHTML = cnt;

    <?php if($sms['cf_sms_type'] == 'LMS') { ?>
    if(cnt > 90)
        max_bytes.innerHTML = 1500;
    else
        max_bytes.innerHTML = 90;

    if(cnt > 1500)
    {
        exceed = cnt - 1500;
        alert('메시지 내용은 1500바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
        var tcnt = 0;
        var xcnt = 0;
        var tmp = conts.value;
        for(i=0; i<tmp.length; i++)
        {
            ch = tmp.charAt(i);
            if(escape(ch).length > 4) {
                tcnt += 2;
            } else {
                tcnt += 1;
            }

            if(tcnt > 1500) {
                tmp = tmp.substring(0,i);
                break;
            } else {
                xcnt = tcnt;
            }
        }
        conts.value = tmp;
        bytes.innerHTML = xcnt;
        return;
    }
    <?php } else { ?>
    if(cnt > 80)
    {
        exceed = cnt - 80;
        alert('메시지 내용은 80바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
        var tcnt = 0;
        var xcnt = 0;
        var tmp = conts.value;
        for(i=0; i<tmp.length; i++)
        {
            ch = tmp.charAt(i);
            if(escape(ch).length > 4) {
                tcnt += 2;
            } else {
                tcnt += 1;
            }

            if(tcnt > 80) {
                tmp = tmp.substring(0,i);
                break;
            } else {
                xcnt = tcnt;
            }
        }
        conts.value = tmp;
        bytes.innerHTML = xcnt;
        return;
    }
    <?php } ?>
}
</script>

<form name="fconfig" id="fconfig" action="./config/sms_update.php" method="post">
<input type="hidden" name="cf_icode_server_ip" value="<?php echo $sms['cf_icode_server_ip']; ?>">
<input type="hidden" name="token" value="">

<h2>기본설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">SMS 사용</th>
		<td>
			<select name="cf_sms_use" id="cf_sms_use">
				<?php echo option_selected('1', $sms['cf_sms_use'], '사용함'); ?>
				<?php echo option_selected('0', $sms['cf_sms_use'], '사용안함'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">SMS 잔액</th>
		<td><?=$sms_call_cnt?>건<?php echo help('SMS의 경우 최대 80바이트까지 전송하실 수 있습니다.<br>SMS 발송시 SMS 남은 잔액에서 1건씩 차감되어집니다.'); ?></td>
	</tr>
	<tr>
		<th scope="row">SMS 아이디</th>
		<td>
			<input type="text" name="cf_icode_id" value="<?php echo $sms['cf_icode_id']; ?>" id="cf_icode_id" required itemname="SMS 아이디" class="frm_input required" maxlength="10" readonly>
			<a href="javascript:sms_pay_submit(document.fconfig);" class="btn_small grey">SMS 충전</a>
		</td>
	</tr>
	<tr>
		<th scope="row">발신 전화번호</th>
		<td>
			<input type="text" name="cf_sms_recall" value="<?php echo $sms['cf_sms_recall']?>" id="cf_sms_recall" required itemname="발신 전화번호" class="frm_input required">
			<?php echo help('메세지 발송시 발신번호로 찍히는 전화번호입니다. 발신번호는 사전등록된 번호와 동일해야 합니다.'); ?>
			<?php echo help('발신번호 사전등록의 경우 통신서비스 이용증명원, 재직증명서가 필요하며 통신서비스 이용증명원의 경우 등록할 휴대폰번호 또는 유선번호의 해당 통신사 고객센터를 통해 발급 받을수 있습니다.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">요금제</th>
		<td>충전제</td>
	</tr>
	</tbody>
	</table>
</div>

<div id="scf_sms_pre">
	<h2>사전에 정의된 SMS프리셋</h2>
	<div class="local_desc01 local_desc">
		<dl>
			<dt>회원가입시</dt>
			<dd>{이름} {아이디}</dd>
			<dt>주문완료시</dt>
			<dd>{이름} {주문번호}</dd>
			<dt>입금확인시</dt>
			<dd>{이름} {주문번호}</dd>
			<dt>상품발주시</dt>
			<dd>{이름} {주문번호} {업체} {송장번호}</dd>
			<dt>주문취소시</dt>
			<dd>{이름} {주문번호}</dd>
			<dt>배송완료시</dt>
			<dd>{이름} {주문번호} {업체} {송장번호}</dd>
		</dl>
	   <p><?php echo help('주의! 80 bytes 까지만 전송됩니다. (영문 한글자 : 1byte , 한글 한글자 : 2bytes , 특수문자의 경우 1 또는 2 bytes 임)'); ?></p>
	</div>
</div>

<h2>메세지설정</h2>
<table class="wfull">
<colgroup>
	<col width="175px">
	<col>
	<col width="175px">
	<col>
</colgroup>
<tbody>
<?php
$f = 0;
for($i=1; $i<=6; $i=$i+2) {
	$k = $i+1;
	$t = $f+1;

	$tbl_sly = "";
	if($i < 6) $tbl_sly = ' style="margin-bottom:5px;"';
?>
<tr>
	<td>
		<div class="scf_sms_img">
			<textarea name="cf_cont<?php echo $i; ?>" id="cf_cont<?php echo $i; ?>" onkeyup="byte_check('cf_cont<?php echo $i; ?>', 'byte<?php echo $i; ?>', 'max_byte<?php echo $i; ?>');"><?php echo $sms["cf_cont{$i}"]; ?></textarea>
			<p class="scf_sms_byte"><span id="byte<?php echo $i; ?>">0</span> / <span id="max_byte<?php echo $i; ?>"><?php echo ($sms['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte</p>
		</div>
	</td>
	<td class="vat td_label">
		<div class="scf_sms_wrap">
			<p class="tit"><?php echo $seq_sms[$i][0]; ?></p>
			<p>
				<input type="checkbox" name="cf_mb_use<?php echo $i; ?>" value="1" id="cf_mb_use<?php echo $i; ?>"<?php echo get_checked($sms["cf_mb_use{$i}"], "1"); ?>> <label for="cf_mb_use<?php echo $i; ?>">고객</label>
				<input type="checkbox" name="cf_ad_use<?php echo $i; ?>" value="1" id="cf_ad_use<?php echo $i; ?>"<?php echo get_checked($sms["cf_ad_use{$i}"], "1"); ?>> <label for="cf_ad_use<?php echo $i; ?>">관리자</label>
				<input type="checkbox" name="cf_re_use<?php echo $i; ?>" value="1" id="cf_re_use<?php echo $i; ?>"<?php echo get_checked($sms["cf_re_use{$i}"], "1"); ?>> <label for="cf_re_use<?php echo $i; ?>">가맹점</label>
				<?php if($i > 1) { ?>
				<input type="checkbox" name="cf_sr_use<?php echo $i; ?>" value="1" id="cf_sr_use<?php echo $i; ?>"<?php echo get_checked($sms["cf_sr_use{$i}"], "1"); ?>> <label for="cf_sr_use<?php echo $i; ?>">공급사</label>
				<?php } ?>
			</p>
			<p><?php echo $seq_sms[$i][1]; ?></p>
			<?php if($seq_sms[$i][2]) { ?>
			<p><?php echo $seq_sms[$i][2]; ?></p>
			<?php } ?>
			<?php if($seq_sms[$i][3]) { ?>
			<p><?php echo $seq_sms[$i][3]; ?></p>
			<?php } ?>
		</div>
	</td>
	<td>
		<div class="scf_sms_img">
			<textarea name="cf_cont<?php echo $k; ?>" id="cf_cont<?php echo $k; ?>" onkeyup="byte_check('cf_cont<?php echo $k; ?>', 'byte<?php echo $k; ?>', 'max_byte<?php echo $k; ?>');"><?php echo $sms["cf_cont{$k}"]; ?></textarea>
			<p class="scf_sms_byte"><span id="byte<?php echo $k; ?>">0</span> / <span id="max_byte<?php echo $k; ?>"><?php echo ($sms['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte</p>
		</div>
	</td>
	<td class="vat td_label">
		<div class="scf_sms_wrap">
			<p class="tit"><?php echo $seq_sms[$k][0]; ?></p>
			<p>
				<input type="checkbox" name="cf_mb_use<?php echo $k; ?>" value="1" id="cf_mb_use<?php echo $k; ?>"<?php echo get_checked($sms["cf_mb_use{$k}"], "1"); ?>> <label for="cf_mb_use<?php echo $k; ?>">고객</label>
				<input type="checkbox" name="cf_ad_use<?php echo $k; ?>" value="1" id="cf_ad_use<?php echo $k; ?>"<?php echo get_checked($sms["cf_ad_use{$k}"], "1"); ?>> <label for="cf_ad_use<?php echo $k; ?>">관리자</label>
				<input type="checkbox" name="cf_re_use<?php echo $k; ?>" value="1" id="cf_re_use<?php echo $k; ?>"<?php echo get_checked($sms["cf_re_use{$k}"], "1"); ?>> <label for="cf_re_use<?php echo $k; ?>">가맹점</label>
				<input type="checkbox" name="cf_sr_use<?php echo $k; ?>" value="1" id="cf_sr_use<?php echo $k; ?>"<?php echo get_checked($sms["cf_sr_use{$k}"], "1"); ?>> <label for="cf_sr_use<?php echo $k; ?>">공급사</label>
			</p>
			<p><?php echo $seq_sms[$k][1]; ?></p>
			<?php if($seq_sms[$k][2]) { ?>
			<p><?php echo $seq_sms[$k][2]; ?></p>
			<?php } ?>
			<?php if($seq_sms[$k][3]) { ?>
			<p><?php echo $seq_sms[$k][3]; ?></p>
			<?php } ?>
		</div>
	</td>
</tr>

<script>
byte_check('cf_cont<?php echo $i; ?>','byte<?php echo $i; ?>','max_byte<?php echo $i; ?>');
byte_check('cf_cont<?php echo $k; ?>','byte<?php echo $k; ?>','max_byte<?php echo $k; ?>');
</script>
<?php
	$f++;
}
?>
</tbody>
</table>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p class="fc_red">ㆍ 주의! 영문 한글자당 : 1 byte , 한글 한글자당 : 2 byte , 특수문자의 경우 1 또는 2 byte</p>
			<p>ㆍ SMS 포인트가 충전되어 있어야 발송이 가능합니다. 위 <strong>[충전하기]</strong> 버튼을 클릭 후 충전하세요.</p>
			<p>ㆍ SMS 자동 발송은 문자 수신여부와 상관없이 발송됩니다.</p>
			<p>ㆍ 네트워크 상태에 따라 약 1~2분정도 늦게 발송될 수 있습니다.</p>
			<p>ㆍ 문자메세지에 바로가기 링크 혹은 URL 입력시 문자메세지가 스팸처리 되거나 수신이 거부될 수 있을 수 있습니다.</p>
		</div>
	 </div>
</div>
<script>

function sms_pay_submit(f){

	
	if(!confirm("SMS 결제를 통해 충전하시겠습니까?"))
		return;


	win_open('about:blank', "popwin", "750", "800", "no");


	f.target	=	"popwin";

	f.action	=	"http://sms.jisigin.co.kr/sms_pay.php";
	f.submit();
}
</script>
