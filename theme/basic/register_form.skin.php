<?php
if(!defined('_TUBEWEB_')) exit;
?>
    <div class="sub-page2 sub06-02" id="section">
        <div class="container sub06-02-container01">
            <h3 class="back mob"><a href="javascript:history.back()"><img src="<?php echo TB_IMG_URL; ?>/m-back.png" alt="뒤로가기"><span>아이디 / 비밀번호 찾기</span></a></h3>                          
            <div class="sub-visual">                
                <div class="box">
                    <h2 class="sub-vis-tit">회원가입</h2>
                    <!-- <p class="sub-vis-con">회원가입 시 3,000P의 선물이 있어요!</p> -->
                </div>
            </div><!-- sub-visual -->
        </div>
            <div class="container">
            <div class="sub06-02-content-wrap">
                <div class="join-tab">
                    <span>이메일로 간단 회원가입</span>
                </div>
                <form class="join-form" name="fregisterform" id="reg_user_form" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
				<input type="hidden" name="pt_id" value="<?php echo $pt_id; ?>">
				<input type="hidden" name="token" value="<?php echo $token; ?>">
                <div class="join-box">
                    <ul>
                        <li>
                            <div class="subject">
                                <span class="tit"><i>*</i>이름</span>
                            </div>
                            <div class="con">
									<input type="text" name="name"  value="<?php echo $cert_name; ?>"  required itemname="회원명" >
                            </div>
                        </li>
                        <li>
                            <div class="subject">
                                <span class="tit"><i>*</i>아이디</span>
                                <span class="sub">(이메일)</span>
                            </div>
                            <div class="con">
				<dd></dd>
				<input type="text" name="id" id="mb_id" email required  itemname="아이디" placeholder="10~20자 이내" onblur="reg_mb_id_ajax();">
                            </div>
                        </li>
                        <li>
                            <div class="subject">
                                <span class="tit"><i>*</i>비밀번호</span>
                                <span class="sub">(영문 숫자 조합 8자이상)</span>
                            </div>
                            <div class="con">
                                <input type="password" name="passwd" id="password" required itemname="비밀번호" placeholder="8자~20자 이내" maxlength="20" minlength="8" onkeyup="login.noSpaceCheck(this);" onchange="login.noSpaceCheck(this)">
                            </div>
                         
                        </li>
                        <li>
                            <div class="subject">
                                <span class="tit"><i>*</i>비밀번호 확인</span>                                
                            </div>
                            <div class="con">
                                <input type="password" name="repasswd" id="passwordConfirm" required itemname="비밀번호확인" placeholder="8자~20자 이내" maxlength="20" minlength="8">
                            </div>
                        </li>
                        <li>
                            <div class="subject">
                                <span class="tit"><i>*</i>닉네임</span>                                
                            </div>
                            <div class="con">
				<input type="text" name="nickname" id="nickname" required itemname="닉네임" >
                            </div>
                        </li>
                        <li>
                            <div class="subject">
                                <span class="tit"><i>*</i>성별</span>                                
                            </div>
                            <div class="con">
                                <label for="women">
					<input type="radio" <?php if($cert['j_sex']=='0'){echo " checked";}?> value="F" name="gender" id="women" style="display:none">
					<span>여자</span>
                                </label>
                                <label for="men">
					<input type="radio" <?php if($cert['j_sex']=='1'){echo " checked";}?> value="M" name="gender" id="men" style="display:none">
					<span>남자</span>
                                </label>
                            </div>
                        </li>
                        <li>
                            <div class="subject">
                                <span class="tit">생년월일</span>                                
                            </div>
                            <div class="con">
				  <select name="birth_year"  itemname="생년월일" >
					<option value="" selected >년</option>
					<?php for($i=1900;$i<date("Y");$i++){ ?>
					<option value="<?=$i?>"><?=$i?></option>
					<?php }?>
				  </select>
				  <select name="birth_month"  itemname="생년월일">
					<option value="" selected >월</option>
					<?php for($i=1;$i<=12;$i++){ ?>
					<option value="<?=str_pad($i,2, "0", STR_PAD_LEFT)?>"><?=str_pad($i,2, "0", STR_PAD_LEFT)?></option>
					<?php }?>
				  </select>
				  <select name="birth_day"  itemname="생년월일">
					<option value="" selected >일</option>
					<?php for($i=1;$i<=31;$i++){ ?>
					<option value="<?=str_pad($i,2, "0", STR_PAD_LEFT)?>"><?=str_pad($i,2, "0", STR_PAD_LEFT)?></option>
					<?php }?>
				  </select>
                            </div>
                        </li>
                        <li>
                            <div class="subject">
                                <span class="tit"><i>*</i>이동통신사</span>                                
                            </div>
                            <div class="con">
                                <select name="coperation" id="coperation">
                                    <option value="SKT">SKT</option>
                                    <option value="KT">KT</option>
                                    <option value="LG">LG</option>
                                  </select>                                 
                            </div>
                        </li>
                        <li>
                            <div class="subject">
                                <span class="tit"><i>*</i>전화번호</span>                                
                            </div>
                            <div class="con">
                                <input type="number" name="phone1" required maxlength="3" oninput="maxLengthCheck(this)" placeholder="010" >
                                <input type="number" name="phone2" required maxlength="4" oninput="maxLengthCheck(this)">
                                <input type="number" name="phone3" required maxlength="4" oninput="maxLengthCheck(this)">
                            </div>
                          
                        </li>
                    </ul>
                    <label for="mem_agree_1" class="ch_box">                   
			<input type="checkbox" name="mem_agree" id="mem_agree_1">
                        <span>본인은 만 19세 이상이며, <u>이용약관, 개인정보 수집 및 이용</u> 내용을 확인하였으며, 동의합니다.</span>
                    </label>
                    
                    <div class="btn-box">
                        <a href="javascript:login.memberReg();" class="btn01 agree">동의하고 가입하기</a>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
<script>

function form_check(check_type){

	var f		=	document.fregisterform;
	var msg	=	"";

	//패스워드 체크
	if(check_type == "pass"){

		var msg		=	"사용하셔도 좋은 패스워드 입니다.";

		// 패스워드 검사
		if(f.passwd.value.length < 8) {
			msg	=	"비밀번호를 8글자 이상 입력하십시오.";
			f.passwd.focus();
		}

		if(f.passwd.value != f.repasswd.value) {
			msg	=	"비밀번호가 일치하지 않습니다.";
			f.repasswd.focus();		
		}

		$(".invalid").fadeIn("slow")
		$(".invalid p").empty().text(msg);
		
		setTimeout(function() {
			$(".invalid").fadeOut("slow")
		}, 2000);
		
		return;

	}


	//닉네임 체크
	if(check_type == "nicname"){

		if($.trim($("input[name=nickname]").val()) == ""){

			f.nickname.focus();
			$(".invalid").fadeIn("slow")
			$(".invalid p").empty().text("닉네임을 입력하십시오.");
			
			setTimeout(function() {
				$(".invalid").fadeOut("slow")
			}, 2000);

			return;
		}

		var mb_name = $.trim($("input[name=nickname]").val());

		$.post(
			tb_bbs_url+"/ajax.mb_name_check.php",
			{ mb_name: mb_name },
			function(data) {
				
				if(data == "Y"){
					var msg		=	"사용하셔도 좋은 닉네임 입니다.";
				}else{
					var msg		=	"이미 사용중인 닉네임 입니다.";				
				}

				$(".invalid").fadeIn("slow")
				$(".invalid p").empty().text(msg);
				
				setTimeout(function() {
					$(".invalid").fadeOut("slow")
				}, 2000);
				
			}
		);
	}

	//전화번호 체크
	if(check_type == "phone"){

		if($.trim($("input[name=phone1]").val()) == "" || $.trim($("input[name=phone2]").val()) == "" || $.trim($("input[name=phone3]").val()) == ""){

			$(".invalid").fadeIn("slow")
			$(".invalid p").empty().text("전화번호를 입력하십시오.");
			
			setTimeout(function() {
				$(".invalid").fadeOut("slow")
			}, 2000);

			return;
		}


		var mb_phone = $.trim($("input[name=phone1]").val())+"-"+$.trim($("input[name=phone2]").val())+"-"+$.trim($("input[name=phone3]").val());

		$.post(
			tb_bbs_url+"/ajax.mb_phone_check.php",
			{mb_phone: mb_phone},
			function(data) {

				if(data == "Y"){
					var msg		=	"사용하셔도 좋은 전화번호 입니다.";
				}else{
					var msg		=	"이미 사용중인 전화번호 입니다.";				
				}

				$(".invalid").fadeIn("slow")
				$(".invalid p").empty().text(msg);
				
				setTimeout(function() {
					$(".invalid").fadeOut("slow")
				}, 2000);
				
			}
		);
	}

}


function fregisterform_submit(f)
{
	var mb_id = reg_mb_id_check(f.id.value);
	if(mb_id) {
		alert("'"+mb_id+"'은(는) 사용하실 수 없는 아이디입니다.");
		f.id.focus();
		return false;
	}

	// 패스워드 검사
	if(f.passwd.value.length < 8) {
		msg	=	"비밀번호를 8글자 이상 입력하십시오.";
		f.passwd.focus();
		$(".invalid").fadeIn("slow")
		$(".invalid p").empty().text(msg);
		
		setTimeout(function() {
			$(".invalid").fadeOut("slow")
		}, 2000);
		
		return false;
	}

	if(f.passwd.value != f.repasswd.value) {

		msg	=	"비밀번호가 일치하지 않습니다.";
		f.repasswd.focus();
		$(".invalid").fadeIn("slow")
		$(".invalid p").empty().text(msg);
		
		setTimeout(function() {
			$(".invalid").fadeOut("slow")
		}, 2000);

		return false;
	}

	if($.trim($("input[name=nickname]").val()) == ""){

		f.nickname.focus();
		$(".invalid").fadeIn("slow")
		$(".invalid p").empty().text("닉네임을 입력하십시오.");
		
		setTimeout(function() {
			$(".invalid").fadeOut("slow")
		}, 2000);

		return false;
	}

	var mb_name = $.trim($("input[name=nickname]").val());

	$.post(
		tb_bbs_url+"/ajax.mb_name_check.php",
		{ mb_name: mb_name },
		function(data) {

			if(data != "Y"){
				
				$(".invalid").fadeIn("slow")
				$(".invalid p").empty().text("이미 사용중인 닉네임 입니다.");
				
				setTimeout(function() {
					$(".invalid").fadeOut("slow")
				}, 2000);

				return false;
			}			
		}
	);

	if($("input[name=gender]:eq(0)").is(":checked") == false && $("input[name=gender]:eq(1)").is(":checked") == false){
		alert("성별을 선택해주세요.");
		return false;
	}

	if($.trim($("input[name=phone1]").val()) == "" || $.trim($("input[name=phone2]").val()) == "" || $.trim($("input[name=phone3]").val()) == ""){

		$(".invalid").fadeIn("slow")
		$(".invalid p").empty().text("전화번호를 입력하십시오.");
		
		setTimeout(function() {
			$(".invalid").fadeOut("slow")
		}, 2000);

		return false;
	}


	var mb_phone = $.trim($("input[name=phone1]").val())+"-"+$.trim($("input[name=phone2]").val())+"-"+$.trim($("input[name=phone3]").val());

	$.post(
		tb_bbs_url+"/ajax.mb_phone_check.php",
		{mb_phone: mb_phone},
		function(data) {

			if(data != "Y"){			

				$(".invalid").fadeIn("slow")
				$(".invalid p").empty().text("이미 사용중인 전화번호 입니다.");
				
				setTimeout(function() {
					$(".invalid").fadeOut("slow")
				}, 2000);

				return false;
			}	
		}
	);


	if($("input[name=mem_agree]").is(":checked") == false){
		alert("본인은 만 19세 이상이며, 이용약관, 개인정보 수집 및 이용 내용을 확인하였으며, 동의를 하셔야 합니다.");
		return false;
	}

	if(confirm("회원가입 하시겠습니까?") == false)
		return false;

    return true;
}

// 회원아이디 검사
function reg_mb_id_check(mb_id)
{
    mb_id = mb_id.toLowerCase();

    var prohibit_mb_id = "<?php echo trim(strtolower($config['prohibit_id'])); ?>";
    var s = prohibit_mb_id.split(",");

    for(i=0; i<s.length; i++) {
        if(s[i] == mb_id)
            return mb_id;
    }
    return "";
}

// 금지 메일 도메인 검사
function prohibit_email_check(email)
{
    email = email.toLowerCase();

    var prohibit_email = "<?php echo trim(strtolower(preg_replace("/(\r\n|\r|\n)/", ",", $config['prohibit_email']))); ?>";
    var s = prohibit_email.split(",");
    var tmp = email.split("@");
    var domain = tmp[tmp.length - 1]; // 메일 도메인만 얻는다

    for(i=0; i<s.length; i++) {
        if(s[i] == domain)
            return domain;
    }
    return "";
}

function reg_mb_id_ajax() {

	var mb_id = $.trim($("#mb_id").val());

	$.post(
		tb_bbs_url+"/ajax.mb_id_check.php",
		{ mb_id: mb_id },
		function(data) {
			alert(data);			
		}
	);
}
</script>
