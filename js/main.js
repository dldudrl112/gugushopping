$(function(){

  /* 상품상세페이지 공유 */

  $('.share_bt').click(function(){
    $('#view_page .view_info .share_box').css('display','inline-block');
  });

  $('#view_page .view_info .share_box button').click(function(){
    $('#view_page .view_info .share_box').css('display','none');
  });

  /* Faq 토글 */
  $('#faq ul li .a_box').hide();

  $('#faq ul li .q_box').click(function(){ 
    $('#faq ul li .a_box').stop().slideUp();
    $(this).next('#faq ul li .a_box').stop().slideToggle();
  });

  /* 프로필 편집 */
  $('#edit_profile .list_box ul li dl.pwd dd .pw_bt').click(function(){
    $('#edit_profile .list_box ul .pwd_hidden').css('display','block');
    $(this).css('display','none');
    $('#edit_profile .list_box ul li dl.pwd dt').css('margin-right','64px');
  });

  $('#edit_profile .list_box ul li dl.phone dd .ph_bt').click(function(){
    $('#edit_profile .list_box ul li .phone_hidden').show();
    $(this).css('display','none');
    $('#edit_profile .list_box ul li dl.phone dt').css('margin-right','40px');
  });

  /* pc 전화하기 팝업 오픈 */
  $(".phone_box button").click(function(){
	  $(".phone_box").hide();
  });

});


$('.form-control').on('input change', function() {
  var $this = $(this);
  var visible = Boolean($this.val());
  $('.form-control-clear').toggleClass('hidden', !visible);
}).trigger('propertychange');

$('.form-control-clear').on('click', function() {
  $('.form-control').val('').trigger('change').focus();
  $(this).toggleClass('hidden', true);
});




// 회원가입 약관 전체동의

function allCheckFunc( obj ) {
  $('.agree_checkbox').prop("checked", $(obj).prop("checked") );
  // $(this).prop("checked", true);
}
/* 체크박스 체크시 전체선택 체크 여부 */
function oneCheckFunc( obj )
{
  var allObj = $(".all_agree_checked_typeA");
  var objName = $(obj).attr('class');

  if( $(obj).prop("checked") )
  {
    checkBoxLength = $("."+ objName ).length;
    checkedLength = $("."+ objName+":checked").length;

    if( checkBoxLength == checkedLength ) {
      allObj.prop("checked", true);
    } else {
      allObj.prop("checked", false);
    }
  }
  else
  {
    allObj.prop("checked", false);
  }
}

$(function(){

  $(".all_agree_checked_typeA").click(function(){          
    allCheckFunc( this );      
  });
  $('.agree_checkbox').each(function(){
    $(this).click(function(){
      oneCheckFunc( $(this) );
    });
  });
});



/* 팝업창 */
$('#mem_agree .terms .terms_box .box li .more_bt button').click(function(){
  var index1 = $(this).parent('.more_bt').parent('li').index();
  $('#mem_agree .terms_box .terms_text').css('display', 'none');
  $('#mem_agree .terms_box .terms_text').eq(index1).css('display', 'block');
});

$('#mem_agree .terms_box .terms_text .close_bt').click(function(){
  $('#mem_agree .terms_box .terms_text').css('display', 'none');
});

/* 팝업 닫기시 체크 */
$('#mem_agree .terms_box .terms_text .confirm_bt a').click(function(){
  var index2 = $(this).parent('.confirm_bt').parent('.terms_text').index();
  var index3 = $('#mem_agree .terms .terms_box .box li').eq(index2);
  $(index3).find('input').prop("checked",true);
  $('#mem_agree .terms_box .terms_text').css('display', 'none');
});


/* View_page 리뷰 */

 $(function(){

   $('#view_page .review .review_box .review_body:nth-child(n+20)').hide();
   $('#view_page .review .review_box .review_body:nth-child(2)').css('border-bottom','0');

   $('#view_page .review .more_btn button').click(function(){
     $('#view_page .review .review_box .review_body:nth-child(n+20)').show();
    $('#view_page .review .review_box .review_body:nth-child(2)').css('border-bottom','2px solid #dcdcdc');
    $(this).hide();
   });

 });


/* 회원탈퇴 체크 */
$(function(){

  $('#withdrawal label input').click(function(){
    if($('#withdrawal label input').prop('checked')){
      $('#withdrawal .btn_box button').css({
        'background-color' : '#b22323',
        'color' : '#fff'
      })
    }else{
      $('#withdrawal .btn_box button').css({
        'background-color' : '#f4f4f4',
        'color' : '#aeaeae'
      })
    }
  });

});


/* Quick_menu */
var $target = $('.sticky');
var $footer = $('.trigger');
$(window).on('scroll', function(){
  var $window = $(window), anchor = $window.scrollTop() + $window.height();
  /*
  var fot = $footer.offset().top;
  if (anchor > fot) $target.removeClass('fixed');
  else $target.addClass('fixed');
  */
});



$(function(){

  var setA = "close";
  /* 마이페이지 메뉴 슬라이드 */
  $('#m_my_menu_ico button').click(function(){
    if(setA == "close") {
      $('#mypage .my_box .container1>.left_box').css({
        'left' : '0',
        'overflow-x' : 'hidden'
      })
      $('#mypage').css('background-color','#f8f8f8');
      $('#m_my_menu_ico').css({
        'right' : '0',
        'left': 'unset',
        'transform' : 'translateY(-50%) rotate(180deg)'
      });
      $('#mypage .my_box .container1>.right_box .content_box').hide();
      $('#m_my_menu_ico').removeClass('tran_r');
      setA = "open";
    } else {
      $('#mypage .my_box .container1>.left_box').css({
        'left' : '-100%',
        'overflow-x' : 'unset'
      })
      $('#mypage').css('background-color','#fff');
      $('#m_my_menu_ico').addClass('tran_r');
      $('#mypage .my_box .container1>.right_box .content_box').show();
      setA = "close";
    }
  });   



});



/* 승균 part */
/*로그인 part 이미지 자동변경 */
/*$(document).ready(function(){

  // resize 이벤트가 발생할때마다 사이즈를 조절
  if($(window).innerWidth() < 801) {
    $(window).resize(resizeContents);
    // 처음 페이지가 뜰때 사이즈 조정 부분 입니다.
    function resizeContents() {
      var setimgFormat = $('#membership .banner img').outerHeight();
      $('#membership .membership_box').css('marginBottom', setimgFormat + 'px');
      var setmBFormatA = $('.sns_login').outerHeight() + $('.sns_caution').outerHeight() + Number($('#membership').css('padding-bottom').replace(/[^-\d\.]/g, ''));
      $('#membership .banner').css('bottom', setmBFormatA + 'px');
    };
    resizeContents()
  } else {
    function resizeContents() {
      $('#membership .membership_box').css('marginBottom', 55 + 'px');
    }
  }
});*/


/*input type"number" 글자수제한 */
function numberMaxLength(e){
  if(e.value.length > e.maxLength){
      e.value = e.value.slice(0, e.maxLength);
  }
};



/* Pager 호버 */
$('#pager button img').hover(function(){
  var imgHover = $(this).attr('src')
  var imgHover1 = imgHover.substring(0, 13)
  $(this).attr('src', imgHover1 + '_on.png')
},function(){
  var imgHover2 = $(this).attr('src')
  var imgHover3 = imgHover2.substring(0, 13)
  $(this).attr('src', imgHover3 + '.png')
});



/* 결제페이지 쿠폰 팝업창 닫기 */
$('#product_pay .coupon_box').hide();

$('#product_pay .box .coupon label button').click(function(){
  $('#product_pay .coupon_box').show();
});

$('#product_pay .coupon_box .btn_box button.close').click(function(){
  $('#product_pay .coupon_box').hide();
});


/* 결재수단 선택 */
$(function(){

  $('#product_pay ul li label').click(function(){
    if($(this).find('input').is(':checked')){
      $(this).css({
        'border-color' : '#b22323'
      })
    }else{
      $('#product_pay ul li label').css({'border-color' : '#898989'})
    }
  });

  $('#point_pay ul li label').click(function(){
    if($(this).find('input').is(':checked')){
      $(this).css({
        'border-color' : '#b22323'
      })
    }else{
      $('#point_pay ul li label').css({'border-color' : '#898989'})
    }
  });

});