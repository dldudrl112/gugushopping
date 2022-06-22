<?php
if (!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

if($default['de_card_test']) {
	$default['de_inicis_mid'] = 'INIBillTst';
	$default['de_inicis_admin_key'] = '1111';
	$default['de_inicis_sign_key'] = 'b09LVzhuTGZVaEY1WmJoQnZzdXpRdz09';		
}
?>
<script language=javascript>
function make_auto_signature(frm){

    // 데이터 암호화 처리
    var result = true;

	    $.ajax({
        url: tb_url+"/shop/inicis/autobill_makesignature.php",
        type: "POST",
        data: {
            price : frm.price.value
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
			console.log(data);
            if(data.error == "") {
                frm.timestamp.value	=	data.timestamp;
                frm.hashdata.value		=	data.sign;
            } else {
                alert(data.error);
                result = false;
            }
        }
    });

    return result;
}

</script>
<form name="sm_form" method="POST" action="" accept-charset="UTF-8">
	<input type="hidden" name="mid"	value="<?php echo $default['de_inicis_mid']; ?>">
	<?php if($od['paymethod'] == "신용카드"){?>
	<input type="hidden" name="authtype"	value="D">
	<input type="hidden" name="buyeremail"	value="">
	<input type="hidden" name="buyertel"	value="">
	<?php } ?>

	<?php if($od['paymethod'] == "휴대폰"){?>
	<input type="hidden" name="type"	value="2">
	<?php } ?>

	<input type="hidden" name="orderid"	value="<?php echo $od_id; ?>">
	<input type="hidden" name="price"	 value="<?php echo $tot_price; ?>" >
	<input type="hidden" name="goodname"	value="<?php echo $goods; ?>">
	<input type="hidden" name="buyername"	value="">
	<input type="hidden" name="returnurl"	value="">
	<input type="hidden" name="timestamp"	value="">
	<input type="hidden" name="period"	value="M2">
	<input type="hidden" name="hashdata"	value="">
</form>