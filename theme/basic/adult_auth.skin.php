<?php
if(!defined('_TUBEWEB_')) exit;
?>
<div class="sub-page my-page sub08-11" id="section">
    <div class="container">
	<!-- Mem_info Area Start -->
	<? include_once(TB_THEME_PATH.'/aside_mem_info.skin.php'); ?>
	<!-- Mem_info Area End -->
                <div class="sub08-05-content-wrap content-wrap">
			<!-- Left_box Area Start -->
			<? include_once(TB_THEME_PATH.'/aside_my.skin.php');?>
			<!-- Left_box Area End -->

                    <!---------------------내정보관리--------------------->
                    <div class="content-box">                       
                      <h3><a href="javascript:history.back()"><img src="../img/m-back.png" alt="뒤로가기"><?=$tb['title']?></a></h3>      
                      <form class="adult_certification" name="adult_certification" method="post" action="<?=$form_action_url?>" enctype="MULTIPART/FORM-DATA" target="">
                        <div class="list_box">
                          <dl class="name df">
                            <dt>이름</dt>
                            <dd><input type="text" name="user_name" value="<?=$member['name']?>"></dd>
                          </dl>
                          <dl class="gender df">
                            <dt>성별</dt>
                            <dd>
                              <label for="women">
                                <input type="radio" name="gender" value="여성" id="women">
                                <span class="mr">여성</span>                       
                              </label>
                              <label for="men">
                                <input type="radio" name="gender" value="남성" id="men">
                                <span>남성</span>
                              </label>
                            </dd>
                          </dl>
                          <dl class="date">
                            <dt class="mb">생일</dt>
                            <dd>
                              <select name="birth_year" id="birth_year">
                                <option value="<?=$member["birth_year"]?>"><?=$member["birth_year"]?></option>
                              </select>
                              <select name="birth_month" id="birth_month">
                                <option value="<?=$member["birth_month"]?>"><?=$member["birth_month"]?></option>
                              </select>
                              <select name="birth_day" id="birth_day">
                                <option value="<?=$member["birth_day"]?>"><?=$member["birth_day"]?></option>
                              </select>
                            </dd>
                          </dl>
                          <dl class="news_agency">
                            <dt class="mb">이동 통신사</dt>
                            <dd>
                              <select name="news_agency" id="news_agency">
                                <option value="SKT">SKT</option>
                                <option value="KT">KT</option>
                                <option value="LGU">LGU+</option>
                              </select>
                            </dd>
                          </dl>
                          <dl class="phone">
                            <dt class="mb">휴대폰 번호</dt>
                            <dd><input type="number" name="phone" id="phone"></dd>
                          </dl>
                        </div>
                        <label for="agree_btn" class="ch_box">                   
                          <input type="checkbox" name="agree_btn" id="agree_btn">
                          <span>본인의 개인정보를 본인 인증 서비스에 제공하는데 동의합니다.</span>
                        </label>
                        <div class="btn_box">
                          <a href="javascript:void(0)">인증  번호 받기</a>
                        </div>
                      </form>
                    </div>
         	</div><!-- content-wrap -->
	</div><!-- container -->
</div><!-- section -->
