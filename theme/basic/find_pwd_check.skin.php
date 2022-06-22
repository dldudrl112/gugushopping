<?php
if(!defined('_TUBEWEB_')) exit;
?>
<style type="text/css">

	@font-face {
		font-family: 'GmarketSansLight';
		src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_2001@1.1/GmarketSansLight.woff') format('woff');
		font-weight: normal;
		font-style: normal;
	  }
	  
	  @font-face {
		font-family: 'GmarketSansBold';
		src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_2001@1.1/GmarketSansBold.woff') format('woff');
		font-weight: normal;
		font-style: normal;
	  }
	  
	  @font-face {
		font-family: 'GmarketSansMedium';
		src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_2001@1.1/GmarketSansMedium.woff') format('woff');
		font-weight: normal;
		font-style: normal;
	  }



	@media (max-width: 768px){
		body,html{margin:0; padding:0;}
		form, form fieldset input[type=button]{font-family: 'GmarketSansMedium';}
		form{
			width: auto; height: auto; background-color: #1a1a1a; padding: 59px 0;
			text-align: center; color: #ffffff; margin:0;
			box-sizing: border-box;
		}
		form fieldset{border: none; padding: 0; margin: 0;}
		form fieldset:after{
			content: ''; display: block; visibility: hidden; clear: both;
		}
		form fieldset legend{display: none;}
		form fieldset img{display: inline-block; margin-bottom: 23px; width: 61px;}
		form fieldset p{line-height: 1;}
		form fieldset p:first-of-type{font-size: 30px; margin: 0;  word-break: keep-all;
    line-height: 1.5;}
		form fieldset p:last-of-type{font-family: 'GmarketSansLight'; font-size: 18px; margin: 60px 0;}
		form fieldset input[type=button]{
			display:inline-block; vertical-align: middle; width: 246px; height: 73px; background-color: #4a4a4a; color: #ffffff;
			border: 1px solid #ffffff; border-radius: 3px; font-size: 30px;
		}		
	}

	@media (min-width: 769px){
		body,html{margin:0; padding:0;}
		form, form fieldset input[type=button]{font-family: 'GmarketSansMedium';}
		form{
			width: auto; height: auto; background-color: #1a1a1a; padding: 59px 0;
			text-align: center; color: #ffffff;   margin:0;
			box-sizing: border-box;
		}
		form fieldset{border: none; padding: 0; margin: 0;}
		form fieldset:after{
			content: ''; display: block; visibility: hidden; clear: both;
		}
		form fieldset legend{display: none;}
		form fieldset img{display: inline-block; margin-bottom: 20px;}
		form fieldset p{line-height: 1;}
		form fieldset p:first-of-type{font-size: 25px; margin: 0;     word-break: keep-all;
    line-height: 1.5;}
		form fieldset p:last-of-type{font-family: 'GmarketSansLight'; font-size: 16px; margin: 30px 0;}
		form fieldset input[type=button]{
			display:inline-block; vertical-align: middle; width: 162px; height: 35px; background-color: #4a4a4a; color: #ffffff;
			border: 1px solid #ffffff; border-radius: 3px; font-size: 16px;
		}
		
	}
	
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>비밀번호 찾기 팝업</title>
	<script src="<?php echo TB_JS_URL; ?>/jquery-3.3.1.js"></script>
	<script>
			function autoSizePopup()
			{
				var winResizeW=0;
				var winResizeH=0;
			   
				$(document).ready(function() {
					//크롬, 사파리일때
					if (navigator.userAgent.indexOf('Chrome')>-1 || navigator.userAgent.indexOf('Safari')>-1)
					{
						$(window).resize(function() {
						   
							if(winResizeW==0 && winResizeH==0)
							{
								resizeWin();
							}
						});
					}
					//크롬, 사파리말고 모두
					else
					{
						resizeWin();
					}
				});
			   
				function resizeWin()
				{
					var conW = $("body").innerWidth(); //컨텐트 사이즈
					var conH = $("body").innerHeight();
			   
					var winOuterW = window.outerWidth; //브라우저 전체 사이즈
					var winOuterH = window.outerHeight;
				   
					var winInnerW = window.innerWidth; //스크롤 포함한 body영역
					var winInnerH = window.innerHeight;
				   
					var winOffSetW = window.document.body.offsetWidth; //스크롤 제외한 body영역
					var winOffSetH = window.document.body.offsetHeight;
				   
					var borderW = winOuterW - winInnerW;
					var borderH = winOuterH - winInnerH;
				   
					//var scrollW = winInnerW - winOffSetW;
					//var scrollH = winInnerH - winOffSetH;
				   
					winResizeW = conW + borderW;
					winResizeH = conH + borderH;
				   
					window.resizeTo(winResizeW,winResizeH);
				}
			}
			
	</script>
</head>
<body onload="autoSizePopup();">
    <form action="#">
        <fieldset>
            <legend>비밀번호 찾기 팝업</legend>
            <!-- <img src="/img/ico.png" alt="searchIcon"> -->
            <p>회원가입하신 이메일로 비밀번호를 인증할 수 있는 메일이 발송 되었습니다.</p>
            <p>* 문제가 해결되지 않은 경우 고객센터를 이용해 주세요.</p>
            <input type="button" value="아이디 찾기" onclick="javascript:opener.location.href = '<?php echo TB_BBS_URL; ?>/find_id.php';window.close();">
            <input type="button" value="로그인하기" onclick="javascript:opener.location.href = '<?php echo TB_BBS_URL; ?>/login.php';window.close();">
        </fieldset>
    </form>
</body>
</html>
