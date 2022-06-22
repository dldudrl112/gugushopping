<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

// 전자결제를 사용할 때만 실행
if($default['de_iche_use'] || $default['de_vbank_use'] || $default['de_hp_use'] || $default['de_card_use'] || $default['de_easy_pay_use']) {

?>

<script language="javascript" type="text/javascript" src="<?php echo $stdpay_js_url; ?>"></script>

<script language=javascript>


	// 플러그인 설치(확인)
	<?php  if(!is_mobile()){ ?>
	StartSmartUpdate();
	<?php  } ?>

	function auth(frm){

		<?php  if(!is_mobile()){ ?>

		var agent = navigator.userAgent.toLowerCase();

			
		if((navigator.appName != 'Netscape' && agent.indexOf('trident') == -1) || (agent.indexOf("windows") == -1)) {
			alert("오토빌딩은 익스플로러 브라우저에서만 결제가 가능합니다.");
			return false; 		
		}

		if (document.forderform.buyername.value == "")
		{
			alert("구매자명이 빠졌습니다. 필수항목입니다.");
			return false;
		}

		// MakeAuthMessage()를 호출함으로써 플러그인이 화면에 나타나며,
		// Hidden Field에 값들이 채워지게 됩니다. 플러그인은 통신을 하는
		// 것이 아니라, Hidden Field의 값들을 채우고 종료한다는 사실에
		// 유의하십시오.

		if (document.INIpay == null || document.INIpay.object == null){

			alert("플러그인을 설치 후 다시 시도 하십시오.");
			return false;

		}else{

			if (MakeAuthMessage(frm)){
				disable_click();
				return true;
			}else{
				alert("확인에 실패하였습니다.");
				return false;
			}
		}

		<?php }else{ ?>
			frm.submit();
		<?php } ?>
	}

	function enable_click()
	{
		document.forderform.clickcontrol.value = "enable"
	}

	function disable_click()
	{
		document.forderform.clickcontrol.value = "disable"
	}



</script>	
<?php } ?>