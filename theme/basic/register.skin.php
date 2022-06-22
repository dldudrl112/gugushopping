<?php
if(!defined('_TUBEWEB_')) exit;
?>
<form class="agreement" name="fregister" id="fregister" action="<?php echo $register_action_url; ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">
    <div class="sub-page sub06-04" id="section">
        <div class="container">         
            <div class="sub06-04-content-wrap">
              <h3><a href="javascript:history.back()"><img src="<?php echo TB_IMG_URL; ?>/m-back.png" alt="뒤로가기">구구 서비스</a></h3>    
              <p>구구서비스를 이용하기 위해 약관동의가 필요합니다.</p>

		<?php if(false && ($default['de_certify_use'] || isset($_GET['isDev']))) { // 실명인증 사용시 ?>
            <input type="hidden" name="m" value="checkplusSerivce">
            <input type="hidden" name="EncodeData" value="<?php echo $enc_data; ?>">
            <input type="hidden" name="enc_data" value="<?php echo $sEncData; ?>">
            <input type="hidden" name="param_r1" value="">
            <input type="hidden" name="param_r2" value="">
            <input type="hidden" name="param_r3" value="<?php echo $regReqSeq; ?>">
		<?php } ?>
                <div class="box-wrap">
                  <div class="box">
                    <label for="agree01">
                      <input type="checkbox" name="agree_checkbox_agree" class="agree-check" id="agree01">
                      <span>이용약관 동의<i>(필수)</i></span>
                    </label>
                    <a class="shop_provision" href="javascript:void(0)">내용보기</a>
                  </div>
                  <div class="box">
                    <label for="agree02">
                      <input type="checkbox" name="agree_checkbox_agree" class="agree-check" id="agree02">
                      <span>개인정보 수집 및 이용동의<i>(필수)</i></span>
                    </label>
                    <a class="shop_private" href="javascript:void(0)">내용보기</a>
                  </div>
                  <div class="box">
                    <label for="agree03">
                      <input type="checkbox" name="agree_checkbox_agree" class="agree-check" id="agree03">
                      <span>전자금융거래 이용약관<i>(필수)</i></span>
                    </label>
                    <a class="shop_electronic" href="javascript:void(0)">내용보기</a>
                  </div>
                  <div class="box">
                    <label for="agree04">
                      <input type="checkbox" name="agree_checkbox_agree" class="agree-check" id="agree04">
                      <span>만 14세 이상 이용자<i>(필수)</i></span>
                    </label>
                    <a  class="shop_adult" href="javascript:void(0)">내용보기</a>
                  </div>
                  <div class="box">
                    <label for="agree05">
                      <input type="checkbox" name="agree_checkbox" class="agree-check" id="agree05">
                      <span>구구 혜택 알림 동의<i class="choose">(선택)</i></span>
                    </label>                  
                  </div>
                </div>
                <label for="agree06">
                  <input type="checkbox" name="agree_checkbox" class="agree-check" id="agree06" style="display:none">
                  <span>약관 전체 동의</span>
                </label>      

               

              <div class="btn-box">
                <a href="javascript:void(0)" onclick="fregister_submit()" class="btn01">동의하고 가입하기</a>
              </div>

            <div class="pop-up" >
              <img src="<?php echo TB_IMG_URL; ?>/agree-close.png" alt="">
                <h4>이용약관 동의</h4>
                <p style="max-height: 300px;overflow-x: overlay;">
					<?=$config["shop_provision"]?>
                </p>
                <a href="javascript:void(0)">확인</a>
            </div>
			<div class="pop-up2" style="display:none;">
              <img src="<?php echo TB_IMG_URL; ?>/agree-close.png" alt="">
                <h4>개인정보 수집 및 이용동의</h4>
                <p style="max-height: 300px;overflow-x: overlay;">
					<?=$config["shop_private"]?>
                </p>
                <a href="javascript:void(0)">확인</a>
            </div>
			<div class="pop-up3" style="display:none;">
              <img src="<?php echo TB_IMG_URL; ?>/agree-close.png" alt="">
                <h4>전자금융거래 이용약관</h4>
                <p style="max-height: 300px;overflow-x: overlay;">
					<?=$config["shop_electronic"]?>
                </p>
                <a href="javascript:void(0)">확인</a>
            </div>
			<div class="pop-up4" style="display:none;">
              <img src="<?php echo TB_IMG_URL; ?>/agree-close.png" alt="">
                <h4>만 14세 이상 이용자</h4>
                <p style="max-height: 300px;overflow-x: overlay;">
					<?=$config["shop_adult"]?>
                </p>
                <a href="javascript:void(0)">확인</a>
            </div>
            </div>
        </div>
    </div>
</form>
<script>
function fregister_submit(f){
	var check_cnt		=	0;

	for(var i=0;i<$("input[name=agree_checkbox_agree]").length;i++){
		if($("input[name=agree_checkbox_agree]:eq("+i+")").is(":checked") == true){
			check_cnt++;
		}
	}
	
	if(check_cnt < 4){
		alert("회원가입 이용 약관 내용에 동의하셔야 회원가입 하실 수 있습니다.");	
		return false;
	}

	document.getElementById('fregister').submit();

}

function fnPopup(val){
    window.name ="Parent_window";
    var f = document.fregister;

    switch(val){
        case 1: //Mobile phone authentication
            window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
            document.fregister.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
            document.fregister.target = "popupChk";
            document.fregister.submit();
            break;
        case 0: //I-PIN authentication
            window.open('', 'popupIPIN2', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
            document.fregister.target = "popupIPIN2";
            document.fregister.action = "https://cert.vno.co.kr/ipin.cb";
            document.fregister.submit();
            break;
    }
}

</script>
