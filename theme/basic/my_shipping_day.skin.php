<?php
if(!defined('_TUBEWEB_')) exit;
$od["od_time"] =	date("Y-m-d", strtotime($od["od_time"]."+1 day"));						
?>
  <div class="sub-page2 sub04 sub04-03" id="section">
	<div class="container">		
		<div class="sub-visual">
			<h3 class="back mob"><span><?=$tb['title']?></span></a></h3>                          
			<div class="box">
				<h2 class="sub-vis-tit">내구독</h2>
				<!-- <p class="sub-vis-con">아직 내가 구독한 구구가 없습니다!</p> -->
			</div>                
		</div><!-- sub-visual -->
		<div class="sub04-03-content-wrap">
			<form id="day_chg" name="day_chg" method="post" action="<?=$action_url?>">
			<input type="hidden" name="od_id" value="<?=$od_id?>">
			<input type="hidden" name="day_year" id="day_year" value="">
			<input type="hidden" name="day_month" id="day_month" value="">
			<input type="hidden" name="chg_date" id="chg_date" value="">
                <div class="calendar-box">
                   
                    <div class="calendar"></div>
                    <div class="box">
                        <ul>
                            <li><span>기존 배송일</span></li>
                            <li><span>변경 배송일</span></li>
                        </ul>
                    </div>
                </div>
                <div class="delivery-box">
                    <ul>
                        <li>
                            <span class="subject">기존 배송 예정일 :</span>
                            <span class="con"><?=substr($od["od_time"],0,4)?>년 <?=substr($od["od_time"],5,2)?>월 <?=substr($od["od_time"],8,2)?>일</span>
                        </li>
						<? if(substr($od["delivery_date"],0,10) != "0000-00-00"){ ?>
                        <li>
                            <span class="subject">변경 배송 예정일 :</span>
                            <span class="con"><?=substr($od["delivery_date"],0,4)?>년 <?=substr($od["delivery_date"],5,2)?>월 <?=substr($od["delivery_date"],8,2)?>일</span>
                        </li>
						<? }?>
                    </ul>
                    <div class="radio-box">
                        <label for="delivery01"><input type="radio" name="delivery_type" id="delivery01" value="0" style="display:none" checked><span>1회 변경</span></label>
                        <label for="delivery02"><input type="radio" name="delivery_type" id="delivery02" value="1" style="display:none"><span>계속 변경</span></label>
                    </div>
                    <div class="btn-box">
                        <a href="javascript:void(0);" class="btn01" onclick="shipping_day_chg()">변경하기</a>
                    </div>
                </div>
		</form>
		</div>
	</div>
</div>
<script>
	$(document).ready(function (){
		//캘린더 오늘 날짜 표시
		$(".calendar-box .calendar .calendar__day .<?=date('Ymd', strtotime($od['od_time']));?>").addClass("on");

		<? if(substr($od["delivery_date"],0,10) != "0000-00-00"){ ?>
		$(".calendar-box .calendar .calendar__day .<?=date('Ymd', strtotime($od['delivery_date']));?>").addClass("modify");
		<? } ?>
    });

	
	function shipping_day_chg(){

		if($("#chg_date").val() == ""){
			alert("변경 배송 예정일을 선택해주세요.");
			return false;
		}
		var set_now = new Date(); 
		var set_year = set_now.getFullYear();
		var set_month =""+(set_now.getMonth()+1);
		var now_day =set_now.getDate();
		if(now_day > $("#chg_date").val()){
			alert("변경 배송 예정일을 확인해 주세요.");
			return false;
		}

		var set_day = ""+$("#chg_date").val();


		if(set_month.length ==1){
			set_month = "0"+set_month;
		}

		if(set_day.length ==1){
			set_day = "0"+set_day;
		}

		var str = set_year+'-'+set_month+'-'+set_day;

		$("#chg_date").val(str);
		
		$("#day_chg").submit();
	}
</script>