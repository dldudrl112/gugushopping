<?php
if(!defined('_TUBEWEB_')) exit;

?>
  <!-- Mypage Area Start -->
  <div id="mypage" class="pd_12">

      <!-- Mem_info Area Start -->
 	  <? include_once(TB_THEME_PATH.'/aside_mem_info.skin.php'); ?>
      <!-- Mem_info Area End -->

      <!-- My_box Area Start -->
      <div class="my_box">
        <div class="container1">
	 	  <? include_once(TB_THEME_PATH.'/aside_my.skin.php'); ?>
          <!-- Right_box Area Start -->
          <div class="right_box">
            <!-- Content_box Area Start -->
            <div class="content_box">
              <!-- Sub_title01 Area Start -->
              <div class="sub_title01"><h3><?=$tb['title']?></h3></div><!-- Sub_title01 Area End -->
				<div id="edit_profile">
				  <form name="fmemberform" id="fmemberform" method="post" action="<?=$form_action_url?>" enctype="MULTIPART/FORM-DATA">
				  <input type="hidden" name="token" value="<?php echo $token; ?>">
				  <input type="hidden" name="id" value="<?=$member['id']?>">
				  <input type="hidden" name="phone_flag" value="0">
				  <input type="hidden" name="check_number" value="">
				  <div class="list_box">
					<div class="mem_edit">
					  <div class="mem_img">
						<div class="img_box">
							<?php if($member['mem_img']){ ?>
								<img src="<?php echo $upl_dir; ?>/<?=$member['mem_img']?>" alt="img">
							<?php }else{ ?>
								<span class="mem_no_img"><img src="<?php echo TB_IMG_URL; ?>/no_img.png" alt="img"></span>
							<?php } ?>
						</div>
						<input type="file" name="mem_img" id="thumb_upload" class="screen-hidden" onchange="checkImg(this);">
						<label for="thumb_upload"></label>
					  </div>
					  <div class="input-group">
						<div class="form-group has-feedback has-clear">
							<input type="text" class="form-control" name="nickname" value='<?=$member['nickname']?>'>
							<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"><img src="<?php echo TB_IMG_URL; ?>/no_text.png" alt="no_text"></span>
						</div>
					</div>
					</div>
					<ul>
					  <li>
						<dl class="name df">
						  <dt>이름: <span class="user_name"><?=$member['name']?></span></dt>
						</dl>
					  </li>
					  <li>
						<dl class="email df">
						  <dt>이메일</dt>
						  <dd><!-- <span><img src="<?php echo TB_IMG_URL; ?>/naver_email_ico.png" alt="naver_email_ico"></span> --><?=$member['id']?></dd>
						</dl>
					  </li>
					  <li>
						<dl class="pwd df">
						  <dt>비밀번호<span class="m_txt">(영문 숫자 조합 8자 이상)</span></dt>
						  <dd class="pwd_hidden"><input type="password" name="passwd" id="pwd1" placeholder="10자~20자 이내"></dd>
						  <dd><button class="pw_bt" type="button">변경</button></dd>
						</dl>
					  </li>
					  <li class="pwd_hidden">
						<dl class="pwd1 df">
						  <dt>비밀번호 확인</dt>
						  <dd class="df"><input type="password" name="repasswd" id="pwd2" placeholder="10자~20자 이내"><button type="button" onclick = "pass_change();">변경</button><span>(영문 숫자 조합 8자 이상)</span></dd>
						</dl>
					  </li>
					  <li>
						<dl class="phone df">
						  <dt>휴대폰 인증</dt>
						  <dd class="df phone_hidden clearfix">
							<div class="p_1 df">
							  <input type="text" name="phone1" id="phone1" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=3 value="<?=$phone_01?>" readonly />
							  <input type="text" name="phone2" id="phone2" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=4 value="<?=$phone_02?>" readonly />
							  <input type="text" name="phone3" id="phone3" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=4 value="<?=$phone_03?>" readonly />
							  <button type="button" onclick="sms_send_check();">재인증</button>
							</div>
							<div class="p_2 df">
							  <input type="text" name="certification" id="certification" placeholder="인증번호 입력">
							  <button type="button" onclick="sms_check_confirm();">인증</button>
							</div>
						  </dd>
						  <dd><button class="ph_bt" type="button">인증</button></dd>
						</dl>
					  </li>
					  <li class="bb_mo">
						<dl class="date">
						  <dt>생년월일 <span>(년/월/일/시)</span></dt>
						  <dd class="df">
							<input type="text" name="birth_year" id="year" placeholder="년도" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=2 value="<?=$member["birth_year"]?>" />
							<input type="text" name="birth_month" id="month" placeholder="월" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=2 value="<?=$member["birth_month"]?>" />
							<input type="text" name="birth_day" id="day" placeholder="일" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=2  value="<?=$member["birth_day"]?>"  />
							<input type="text" name="birth_time" id="time" placeholder="시" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=2 value="<?=$member["birth_time"]?>" />
						  </dd>
						</dl>
					  </li>
					  <li>
						<dl class="marketing">
						  <dt>마케팅 수신 정보동의</dt>
						  <dd>*이벤트 및 혜택에 대한 정보를 받으실 수 있어요.</dd>
						</dl>
					  </li>
					</ul>
					<div class="agree_box">
					  <label for="email_agree">
						<span>메일 수신동의</span>
						<input type="checkbox" name="mailser" id="email_agree" value="Y" <?php if($member["mailser"] == "Y") echo "checked"; ?>> 
					  </label>
					  <label for="sms_agree">
						<span>SMS 수신동의</span>
						<input type="checkbox" name="smsser" id="sms_agree" value="Y" <?php if($member["smsser"] == "Y") echo "checked"; ?>> 
					  </label>
					</div>
				  </form>
				  </div>
				  <div class="apply_bt">
					<a href="javascript:member_edit_submit();">수정하기</a>
				  </div>
				  <div class="btn_box">
					<a href="<?php echo TB_BBS_URL; ?>/logout.php">로그아웃</a>
					<a href="<?php echo TB_BBS_URL; ?>/leave_form.php">회원탈퇴</a>
				  </div>
				</div>
            </div><!-- Content_box Area End -->
          </div><!-- Right_box Area End -->

        </div>
      </div><!-- My_box Area End -->

  </div><!-- Mypage Area End -->
<script>

	function pass_change(){

		if($("input[name=passwd]").val().length < 10) {
			alert("비밀번호를 10자 이상 입력해주세요.");
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