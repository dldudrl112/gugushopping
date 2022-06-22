<?php
if(!defined('_TUBEWEB_')) exit;
?>


<div class="sub-page2 sub04 sub04-02" id="section">
	<div class="container">
		<div class="sub-visual">
				<h3 class="back mob" style="margin: 0 10px;"><a href="javascript:history.back()"><img src="/img/header/back.png" alt="뒤로가기"><span>내구독</span></a></h3>
				<div style="border: 1px solid #f9f9f9;"></div>
				<div class="box">

					<h2 class="sub-vis-tit">리뷰쓰기</h2>

			<!-- <h3 class="back mob"><span>리뷰쓰기</span></a></h3>                           -->
			<!-- <h3 class="back mob"><a href="javascript:history.back()"><img src="<?php echo TB_IMG_URL; ?>/m-back.png" alt="뒤로가기"><span>내구독</span></a></h3>                           -->
			<div class="box">
				<h2 class="sub-vis-tit">리뷰쓰기</h2>
				<!-- <p class="sub-vis-con">아직 내가 구독한 구구가 없습니다!</p> -->
			</div>                
		</div><!-- sub-visual -->

		<div class="sub04-02-content-wrap">
		<form class="reviewform" name="reviewform" method="post" action="<?php echo $form_action_url; ?>"  enctype="multipart/form-data">  
		<input type="hidden" name="title" value="<?=$od["name"]?>님의 <?=$gs["gname"]?>상품 리뷰">
		<input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">
		<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
		<input type="hidden" name="seller_id" value="<?php echo $gs['mb_id']; ?>">
		<input type="hidden" name="score" value="5">
		<input type="hidden" name="token" value="<?php echo $token; ?>">
			<div class="sub04-02-content01">
				<h5><?=$gs["gname"]?></h5>
				<div class="star-box">
					<img src="<?php echo TB_IMG_URL; ?>/m-review-star-on.png" class="review_score" alt="">
					<img src="<?php echo TB_IMG_URL; ?>/m-review-star-on.png" class="review_score" alt="">
					<img src="<?php echo TB_IMG_URL; ?>/m-review-star-on.png" class="review_score" alt="">
					<img src="<?php echo TB_IMG_URL; ?>/m-review-star-on.png" class="review_score" alt="">
					<img src="<?php echo TB_IMG_URL; ?>/m-review-star-on.png" class="review_score" alt="">
				</div>
			</div>
			<div class="sub04-02-content02">
				<div class="content-box">
					<textarea name="memo" id="memo" cols="30" rows="10" placeholder="‘<?=$gs["gname"]?>’ 상품은 어떠셨나요?"></textarea>
					<div class="content-bottom">
						<!-- 사진 업로드 되는 부분 -->
						<div class="review-picture-box">
							<!-- <span>-</i></span>
							<span><i>-</i></span>
							<span><i>-</i></span>
							<span><i>-</i></span>
							<span><i>-</i></span> -->
						</div>
						<label for="secret-review">
							<input type="checkbox" name="secret-review" id="secret-review" value = "1">
							<span>비밀리뷰</span>
						</label>
						<label for="upload-pic">
							<input multiple="multiple" type="file" name="filename[]" id="upload-pic" onchange="fileUploadAct(this);">
							<span class="camera">
								<img src="<?php echo TB_IMG_URL; ?>/camera.png" alt="">                                
								<span><strong>사진</strong><i>0/5</i></span>
							</span>
						</label>
					</div>
				</div>
			</div>
			<div class="btn-box">
				<a href="javascript:history.back()" class="btn02">뒤로가기</a>
				<a href="javascript:forderreview_submit(document.reviewform);" class="btn03">리뷰 작성하기</a>
			</div>
			</form>
		</div>
	</div>
</div>
<script>

$(function(){

  /* 별점 선택 */
  $('.star-box .review_score').click(function(){

		var review_score	=	parseInt($(this).index());
	
		for(var i=0;i<5;i++){

			if(i <= review_score){
				$('.star-box .review_score:eq('+i+')').attr("src","<?php echo TB_IMG_URL; ?>/m-review-star-on.png");
			}else{
				$('.star-box .review_score:eq('+i+')').attr("src","<?php echo TB_IMG_URL; ?>/m-review-star.png");
			}
		}

		$("input[name=score]").val((review_score+1));
	
  });

});


function fileUploadAct(obj){
	var e					=	$(obj);
	var files				=	e[0].files;
	var filesArr			=	Array.prototype.slice.call(files);

	handleImgFileSelect(filesArr,obj);
}


function fileDel(obj){

	var file_index	=	$(".review-picture-box span i").index(obj);


	$(".review-picture-box span:eq("+file_index+")").remove();

	var img_cnt		=	$(".review-picture-box span").length;

	$(".camera span i").text((img_cnt)+"/5");

	//파일 array 삭제

	var	fileBuffer	=	[];
	var	e				=	$("#upload-pic");
	var	files			=	e[0].files;

    Array.prototype.push.apply(fileBuffer, files);

	fileBuffer.splice(file_index, 1);

	var filesArr			=	Array.prototype.slice.call(files);

	console.log(filesArr);

}


function handleImgFileSelect(arr){

	$(".review-picture-box").html("");

	arr.forEach(function(f){

        //이미지 파일 미리보기
        if(f.type.match('image.*')){

          var reader = new FileReader(); //파일을 읽기 위한 FileReader객체 생성

          reader.onload = function (e) { //파일 읽어들이기를 성공했을때 호출되는 이벤트 핸들러			

			  var img_cnt		=	$(".review-picture-box span").length;

			  $(".review-picture-box").append("<span><img src='"+e.target.result+"'><i onclick=\"fileDel(this);\">-</i><input type=\"hidden\" name=\"file_size[]\" value=\""+e.total+"\"></span>");

				if((img_cnt) >= 5){
					alert("이미지는 5장까지만 업로드 가능합니다.");
					return;
				}
				
				$(".camera span i").text((img_cnt+1)+"/5");

          } 


          reader.readAsDataURL(f);

        }else{

			alert("확장자는 이미지 확장자만 가능합니다.");
			return;		
		}

	});//arr.forEach
}

function forderreview_submit(f) {

	if(!f.title.value) {
		alert('제목을 입력하세요.');
		f.title.focus();
		return;
	}

	if(!f.memo.value) {
		alert('내용을 입력하세요.');
		f.memo.focus();
		return;
	}

	if(confirm("등록 하시겠습니까?") == false)
		return;

	f.submit();
}
</script>