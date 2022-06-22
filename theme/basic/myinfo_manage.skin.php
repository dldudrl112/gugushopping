<?php
if(!defined('_TUBEWEB_')) exit;
?>
<div class="sub-page my-page sub08-05" id="section">
    <div class="container">
	<!-- Mem_info Area Start -->
	<? include_once(TB_THEME_PATH.'/aside_mem_info.skin.php'); ?>
	<!-- Mem_info Area End -->
                <div class="sub08-05-content-wrap content-wrap">
			<!-- Left_box Area Start -->
			
			<!-- Left_box Area End -->

                    <!---------------------내정보관리--------------------->
                    <div class="content-box">                       
                        <h3><a href="javascript:history.back()"><img src="<?php echo TB_IMG_URL; ?>/m-back.png" alt="뒤로가기">내정보 관리</a></h3>      
						  <form name="fmemberform" id="fmemberform" method="post" action="<?=$form_action_url?>" enctype="MULTIPART/FORM-DATA">
						  <input type="hidden" name="token" value="<?php echo $token; ?>">
						  <input type="hidden" name="id" value="<?=$member['id']?>">
						  <input type="hidden" name="phone_flag" value="0">
						  <input type="hidden" name="check_number" value="">
                            <div class="top">
                                <div class="img-box">
									<?php if($member['mem_img']){ ?>
										<img src="<?php echo $upl_dir; ?>/<?=$member['mem_img']?>" alt="img">
									<?php }else{ ?>
										<span class="mem_no_img"><img src="<?php echo TB_IMG_URL; ?>/no_img.png" alt="img"></span>
									<?php } ?>
                                    <input type="file" name="mem_img" id="thumb-upload" onchange="checkImg(this);">
                                    <label for="thumb-upload"><img src="<?php echo TB_IMG_URL; ?>/mypage-setting.png" alt="설정"></label>
                                </div>
                                <div class="nick-box">
                                    <input type="text" name="nickname" value="<?=$member['nickname']?>">
                                    <a href="javascript:void(0)" class="input-delte-btn"><img src="<?php echo TB_IMG_URL; ?>/input-delete.png" alt="삭제"></a>
                                </div>
                            </div>
                            <div class="mid">
                                <ul>
                                    <li>
                                        <div class="subject">이름 :</div>
                                        <div class="con"><?=$member['name']?></div>
                                    </li>
                                    <li class="email-line">
                                        <div class="subject">이메일</div>
                                        <div class="con">
                                            <div class="sns-icon">
                                                <!-- <img src="<?php echo TB_IMG_URL; ?>/mypage_sns01.png" alt="kakao"> -->
                                                <!-- <img src="<?php echo TB_IMG_URL; ?>/mypage_sns02.png" alt="facebook"> -->
                                                <!-- <img src="<?php echo TB_IMG_URL; ?>/mypage_sns03.png" alt="google"> -->
                                                <!-- <img src="<?php echo TB_IMG_URL; ?>/mypage_sns04.png" alt="naver"> -->
                                            </div>
                                            <p><?=$member['id']?></p>
                                        </div>
                                    </li>
                                    <li class="password-line">
                                        <div class="subject">비밀번호</div>
                                        <div class="con">
                                            <a href="javascript:void(0)" class="modify">인증</a>                                           
                                        </div> 
                                        <div class="fix-box">
                                            <input type="password" name="passwd" id="password" placeholder="10~20자 이내">
                                            <span class="subject">비밀번호 확인<span class="refer">(영문 숫자 조합 8자 이상)</span></span>
                                            <div>
                                                <input type="password" name="repasswd" id="password-confirm" placeholder="10~20자 이내" >                                            
                                                <a href="javascript:;" onclick = "pass_change();" class="password-re">변경</a>
                                            </div>
                                            
                                        </div>
                                    </li>
                                    <li class="phone-line">
                                        <div class="subject">휴대폰 인증</div>
                                        <div class="con">
                                            <a href="javascript:void(0)" class="modify">인증</a>                                        
                                        </div>
                                        <div class="fix-box">
                                            <span>
                                                <input type="number" name="phone1" id="phone1" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=3 value="<?=$phone_01?>" readonly class="input-col3">
                                                <input type="number" name="phone2" id="phone2" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=4 value="<?=$phone_02?>" readonly class="input-col3">
                                                <input type="number" name="phone3" id="phone3" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=4 value="<?=$phone_03?>" readonly class="input-col3">
                                                <a href="javascript:void(0)" onclick="sms_send_check();" class="re">재인증</a>
                                            </span>
                                            
                                            <span>
                                                <input type="number" name="certification"  id="certification" >
                                                <a href="javascript:void(0)" onclick="sms_check_confirm();" class="phone-confirm">인증</a>
                                            </span>
                                        </div>
                                    </li>
                                    <li class="birth-line">
                                        <div class="subject">생년월일<i>(년/월/일)</i></div>
                                        <div class="con">
                                            <input type="number" name="birth_year" id="year" placeholder="년도" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=2 value="<?=$member["birth_year"]?>" class="input-col3">
                                            <input type="number" name="birth_month" id="month" placeholder="월" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=2 value="<?=$member["birth_month"]?>"  class="input-col3" >
                                            <input type="number" name="birth_day" id="day" placeholder="일" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=2  value="<?=$member["birth_day"]?>"  class="input-col3">
                                        </div>
                                    </li>
                                    <li class="adult">
                                        <div class="subject">성인인증</div>
                                        <div class="con">
                                            <a href="<?php echo TB_SHOP_URL; ?>/adult_auth.php">인증</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="bottom">
                                <div class="bottom-top">
                                    <div class="subject">마케팅 정보 수신동의</div>
                                    <div class="con">* 이벤트 및 혜택에 대한 정보를 받으실 수 있어요.</div>
                                </div>

                                <div class="bottom-mid">                                    
                                    <div class="sms-agree01">                                        
                                        <input type="checkbox" name="mailser" value="Y" <?php if($member["mailser"] == "Y") echo "checked"; ?> id="sms-agree-btn01" >
                                        <label for="sms-agree-btn01" class="">
                                            <span>메일 수신동의</span>
                                            <div class="sms-agree-btn <?php if($member["mailser"] == "Y") echo "on"; ?>">
                                                <div class="circle"></div>
                                            </div>                         
                                        </label>                                        
                                    </div>
                                    <div class="sms-agree02">
                                        <input type="checkbox" name="smsser" value="Y" <?php if($member["smsser"] == "Y") echo "checked"; ?> id="sms-agree-btn02" >
                                        <label for="sms-agree-btn02" class="">
                                        <span>SMS 수신동의</span>
                                            <div class="sms-agree-btn <?php if($member["smsser"] == "Y") echo "on"; ?>">
                                                <div class="circle"></div>
                                            </div>                         
                                        </label>       
                                    </div>
                                </div>
                                <div class="btn-box">
                                    <a href="javascript:member_edit_submit();" class="btn01">수정하기</a>
                                </div>
                                <div class="bottom-bot">
                                    <a href="<?php echo TB_BBS_URL; ?>/logout.php">로그아웃</a>
                                    <a href="<?php echo TB_BBS_URL; ?>/leave_form.php">회원탈퇴</a>
                                </div>
                            </div>
                        </form>
                    </div>
		</div><!-- content-wrap -->
	</div><!-- container -->
</div><!-- section -->
<script>

	function pass_change(){


		var chk_num	=	$("input[name=passwd]").val().search(/[0-9]/g);
		var chk_eng	=	$("input[name=passwd]").val().search(/[a-z]/ig);


		if(chk_num < 0 || chk_eng < 0){
			alert("비밀번호는 숫자와 영문자 조합으로 8글자 이상 사용해야 합니다.");			
			return false;
		}

		if($("input[name=passwd]").val().length < 8) {
			alert("비밀번호를 8자 이상 입력해주세요.");
			return;		
		}

		if($("input[name=passwd]").val() == ""){
			alert("변경하실 비밀번호를 입력해주세요.");
			return;		
		}

		if($("input[name=repasswd]").val() == ""){
			alert("변경하실 비밀번호 확인을 위해 비밀번호를 재입력해주세요.");
			return;		
		}

		if($("input[name=passwd]").val() != $("input[name=repasswd]").val()) {
			alert("비밀번호가 일치하지 않습니다.");
			return;		
		}

		if($("input[name=phone_flag]").val() != 1){
			alert("휴대폰 인증을 하셔야 합니다.");
			return;
		}

		var pass	=	$("input[name=repasswd]").val();

		$.post(
			tb_bbs_url+"/ajax_mb_pass_change.php",
			{ passwd:pass},
			function(data) {

				if(data == "Y"){				
					$("input[name=phone_flag]").val(0);
					$("input[name=check_number]").val("");
					alert("패스워드 변경이 정상적으로 처리되었습니다.");
					return;				
				}else{
					alert("정보가 잘못되었거나 등록되지 않은 회원입니다.");
					return;				
				}
			}
		);
	}

	function sms_send_check(){
		
		var recv_phone	=	$("input[name=phone1]").val()+"-"+$("input[name=phone2]").val()+"-"+$("input[name=phone3]").val();

		$.post(
			tb_bbs_url+"/ajax_sms_check.php",
			{ check_type:'send',recv_phone:recv_phone},
			function(data) {
				if(data == "N"){				
					alert("연락처 정보가 잘못되었거나 등록되지 않은 회원입니다.");
					return;
				}else{
					if(data.length == 6){
						$("input[name=check_number]").val(data);
					}else{
						alert("인증코드 발급에 실패하였습니다. 다시 시도해주세요.");
						return;					
					}
				}
			}
		);
	}


	function sms_check_confirm(){
		
		var cert_code	=	$("input[name=certification]").val();

		if(cert_code == ""){
			alert("전송받은 인증번호를 입력해 주세요.");
			$("input[name=certification]").focus();
			return;
		}

		$.post(
			tb_bbs_url+"/ajax_sms_check.php",
			{ check_type:'cert',cert_code:cert_code},
			function(data) {
				if(data == "Y"){				
					alert("휴대폰 인증이 성공하였습니다.\n비밀번호 변경이 가능하십니다.");
					$("input[name=phone_flag]").val(1);
					return;
				}else{
					alert("인증에 실패하였습니다.\n올바른 인증코드를 입력해주세요.");
					return;
				}
			}
		);
	}
	
	function checkImg(obj){

		if( $(obj).val() != "" ){

			  var ext = $(obj).val().split('.').pop().toLowerCase();

			  if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
				 alert('gif,png,jpg,jpeg 파일만 업로드 할수 있습니다.');
				 return;
			  }
		}
	}

	function member_edit_submit(){
			
		var f	=	document.fmemberform;

		if(f.nickname.value == ""){
			f.nickname.focus();
			alert("닉네임을 입력해주세요.");
			return;
		}
		
		f.submit();
	}
</script>
