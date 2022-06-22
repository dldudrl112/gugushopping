$(document).ready(function () {
    // 메인슬라이드
		
	if($('.main-slide').length > 0){

		var $slick

		$slick = $('.main-slide')

		$slick.slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: true,
			prevArrow: "<button type='button' class='slick-prev'><img src='../img/slide-prev-btn.png' alt='prev'></button>", // 이전 화살표 모양 설정
			nextArrow: "<button type='button' class='slick-next'><img src='../img/slide-next-btn.png' alt='next'></button>", // 다음 화살표 모양 설정
			infinite: true,
			speed: 2000,
			autoplaySpeed: 4000,
			dots : true,
			cssEase: 'ease',
			autoplay: true,
			dotsClass: "slick-dots"
		});

	}
    //

    //상품상세 - wish
    var iconWish = $('.wish');
    iconWish.click(function(){

        //var img = $(this).attr('src');

        if($(this).hasClass('on')){
            $(this).removeClass('on')
            //$(this).find('img').attr("src", $(this).find('img').attr("src").replace("m-icon_wish.png","m-icon_wish_dim.png"));
        }else{
            //$(this).find('img').addClass();
            $(this).addClass('on')
            //$(this).find('img').attr("src", $(this).find('img').attr("src").replace("m-icon_wish_dim.png","m-icon_wish.png"));
        }
    })
    //

    // 상품상세 계산기
	/*
    var calc = $('.sub02-01 .goods-info-wrap .option-box ul li span.option-con .calculator')
    calc.find('a').click(function(){
        if($(this).hasClass('calc-min')){
            var m = calc.find('input').val()            
            if(m==1){
                calc.find('input').val('1')
                alert('최소 1개 이상 선택해야합니다.')
            }else{
                --m
                calc.find('input').val(m--)                
            }
            
        }
        else if($(this).hasClass('calc-plus')){
            var p = calc.find('input').val()
            ++p
            calc.find('input').val(p)
        }

    })
	*/


    //상품상세 탭
    var deTab = $('.sub02-01 .detail-info-box .detail-tab li')
    $('.sub02-01 .detail-info-box .goods-sec').eq(0).show();

    deTab.click(function(){
        var deTab_i = $(this).index()
        $('.sub02-01 .detail-info-box .detail-tab li').removeClass('on')
        $(this).addClass('on')
        $('.sub02-01 .detail-info-box .goods-sec').hide()
        $('.sub02-01 .detail-info-box .goods-sec').eq(deTab_i).fadeIn()
    })
    //

    //내구독 옵션변경
	/*
    $('.sub04-01 .section3 .right ul li').eq(0).find('a').click(function(){
      $('.sub04-01 .pop-up').fadeIn()
      $('.sub04-01 .pop-up > div').hide()
      $('.sub04-01 .pop-up > div:nth-child(1)').show()
    })
    $('.sub04-01 .section3 .right ul li').eq(3).find('a').click(function(){
      $('.sub04-01 .pop-up').fadeIn()
      $('.sub04-01 .pop-up > div').hide()
      $('.sub04-01 .pop-up > div:nth-child(2)').show()
    })
    $('.sub04-01 .section3 .right ul li').eq(5).find('a').click(function(){
      $('.sub04-01 .pop-up').fadeIn()
      $('.sub04-01 .pop-up > div').hide()
      $('.sub04-01 .pop-up > div:nth-child(3)').show()
    })
    $('.sub04-01 .pop-up .pop-up-btn .cancel').click(function(){
      $('.sub04-01 .pop-up').hide()
      $('.sub04-01 .pop-up > div').hide()
    })
	*/

    // 내구독 배송지 변경
    var phonePlus = $('.sub04-04-content-wrap ul li .input-box .plus')
    phonePlus.click(function(){
        $(this).parents('li').siblings('.hide').eq(0).slideDown().removeClass('hide')

    })
    var phoneMin = $('.sub04-04-content-wrap ul li .input-box .min')
    phoneMin.click(function(){
        $(this).parents('li').slideUp().addClass('hide')
    })

   



    //내구독 우편번호 찾기   
  
    $('.sub04-04-content-wrap ul li .input-box .zip-search-btn').click(function(){
      var h = $('.sub04-04-content-wrap ul li .input-box').height()
        $('.zipcode-pop').fadeIn()
        $('.sub04-04-content-wrap ul li > div.input-box-wrap .zipcode2-box').slideDown()
        $('.sub04-04-content-wrap ul li.zipcode-line .icon-box').css('height',h*2)
    })
    $('.zipcode-pop > a').click(function(){
        $('.zipcode-pop').fadeOut()
        $('.sub04-04-content-wrap ul li > div.input-box-wrap .zipcode2-box').slideUp()
        $('.sub04-04-content-wrap ul li.zipcode-line .icon-box').css('height',h)
    })

    //결제정보선택 주소찾기
    $('.zip-search-btn').click(function(){
      $('.zipcode-pop').fadeIn()
   
  })

    // 로그인 회원가입
    var loginBtn = $('.login-tab li')
    loginBtn.click(function(){
        $('.login-tab li').removeClass('on')
        $(this).addClass('on')
        var login_index = $(this).index()
        $('.sub06-01 .login-box, .sub06-03 .find-info').hide()
        $('.sub06-01 .login-box, .sub06-03 .find-info').eq(login_index).fadeIn()
        if(login_index==1){
          $('.sub06-01 .detail-banner').show()
          $('.sub06-01 .sub-visual').css({
            'background-image':'url(/img/join-banner.jpg)'
          })
          $('.sub06-01 .sub-visual h2').text('회원가입')
          $('.sub06-01 .sub-visual p').text('')
          
        }else{
          $('.sub06-01 .detail-banner').hide()
          $('.sub06-01 .sub-visual').css({
            'background-image':'url(/img/sub06-01-banner-bg.jpg)'
          })
          $('.sub06-01 .sub-visual h2').text('아직 로그인을 하지 않으셨습니다.')
          $('.sub06-01 .sub-visual p').text('로그인 후 이용 부탁드립니다.')
          
        }
        
    })

   // 로그인 회원가입
   var loginBtn = $('.login-tab li')
   loginBtn.click(function(){
       $('.login-tab li').removeClass('on')
       $(this).addClass('on')
       var login_index = $(this).index()
       $('.sub06-01 .login-box, .sub06-03 .find-info').hide()
       $('.sub06-01 .login-box, .sub06-03 .find-info').eq(login_index).fadeIn()
       if(login_index==1){
         $('.sub06-01 .detail-banner').show()
         $('.sub06-01 .sub-visual-login').css({
          //  'background-image':'url(/img/join-banner.jpg)'
         })
         $('.sub06-01 .sub-visual-login h2').text('구독자님')
         $('.sub06-01 .sub-visual-login p').text('환영합니다!')
         
       }else{
         $('.sub06-01 .detail-banner').hide()
         $('.sub06-01 .sub-visual-login').css({
          //  'background-image':'url(/img/sub06-01-banner-bg.jpg)'
         })
        //  $('.sub06-01 .sub-visual-login h2').text('아직 로그인을 하지 않으셨습니다.로그인 후 이용 부탁드립니다.')
         $('.sub06-01 .sub-visual-login h2').text('아직 로그인을 하지 않으셨네요.')
         $('.sub06-01 .sub-visual-login p').text('로그인 후 이용해 주세요.')
         
       }
       
   })


    // faq 리스트
    var faq = $('.sub07-01 .content-box .faq-list li')
    faq.click(function(){
        if($(this).find('.answer').css('display')=='none'){
            faq.find('.answer').slideUp(300)
            $(this).find('.answer').slideDown(300)
        }else{
            $(this).find('.answer').slideUp(300)
        }      

    })

    // input 안에 삭제 버튼 
    $('.input-delte-btn').click(function(){
        $(this).siblings('input').val('')

    })


    // 내 정보관리 '변경' 버튼 클릭시
    $('.sub08-05 .content-box form .mid ul li .con .modify').click(function(){
        $(this).hide()
        $(this).parent('.con').siblings('.fix-box').fadeIn()
        
    })

    // 내정보관리 수신동의
    var smsBtn = $('.sub08-05 .content-box form .bottom .bottom-mid label')
    smsBtn.click(function(){

        if($(this).find('.sms-agree-btn').hasClass('on')){
            $(this).find('.sms-agree-btn').removeClass('on')
            $(this).find('.circle').css({
                left:'5%'
            })
        }else{
            $(this).find('.sms-agree-btn').addClass('on')
            $(this).find('.circle').css({
                left:'52%',
            })
        }      
    })

    // 회원탈퇴
    var withdr = $(".sub08-06 .withdrawal-check-box input[type='checkbox'] + label")
    withdr.click(function(){
        if($(this).siblings('input').prop('checked')){
            $('.sub08-06 .account-delete').removeClass('on')
            $('.sub08-06 .account-delete').attr('disabled','disabled')
        }else{
            $('.sub08-06 .account-delete').addClass('on')
            $('.sub08-06 .account-delete').removeAttr('disabled')
        }

    })

    // 결제정보선택 radio
    $('.sub03-02 .section4 ul li label input').click(function(){
        var $this= $(this);
        var $parent= $this.closest('label');
        if($this.attr('type') === 'radio'){
            var $parents= $parent.closest('ul');

            $parents.find('li label').css({'border': '1px solid #106b37', 'background':'#fff'});
            $parent.css({
                'border': '2px solid #106b37',
                'background' : '#106b37'
            });

            $parents.find('label span').css({'color' : '#000'});
            $parent.find('span').css({color:'#fff'})
            return;
        }
      if($this.is(':checked')){
          $parent.css({
          'border': '2px solid #106b37',
          'background' : '#106b37'
          
        })
      }else{
        $('.sub03-02 .section4 ul li label').css({'border': '1px solid #106b37'});
      }
    });

    // $('.sub03-02 .section4 ul li label span').click(function(){
    //   if($(this).find('input').is(':checked')){
    //     $(this).css({
    //       'color' : '#000'
    //
    //     })
    //   }else{
    //     $('.sub03-02 .section4 ul li label span').css({'color' : '#fff'})
    //   }
    // });

    //반응형 마이메누
    $('.my-page .content-wrap > .content-button').click(function(){
      if($('.my-page .content-box').css('display')=='none'){        
        // $('.my-page .mypage-nav').css('visibility','hidden')
        $('.my-page .mypage-nav').addClass('active')
        $('.my-page .content-box').fadeIn()
        $('.my-page .content-box').animate({
          left:0  
        },300)
      }else{
        $('.my-page .content-box').animate({
          left:'100%'
        })
        $('.my-page .content-box').fadeOut()
        // $('.my-page .mypage-nav').css('visibility','visible')
        $('.my-page .mypage-nav').removeClass('active')
      }
    

    })


  // 캘린더 반응형
  $('.calendar-box .cal-btn').click(function(){
    if($('.sub04-01 .calendar').css('display')=='none'){
      $('.sub04-01 .calendar').slideDown()
      $(this).find('img').attr('src','../img/cal-btn-up.png')
    }else{
      $('.sub04-01 .calendar').slideUp()
      $(this).find('img').attr('src','../img/cal-btn.png')
    }


  })

  // 캘린더 반응형 밑에
  $('.sub04-01 .section3 .cal-btn').click(function(){
    if($(this).siblings('.sub04-01 .section3 .right').css('display')=='none'){
      $(this).parent('li').find('.right').slideDown()
      $(this).find('img').attr('src','../img/cal-btn-w-up.png')
    }else{
      $(this).parent('li').find('.right').slideUp()
      $(this).find('img').attr('src','../img/cal-btn-w.png')
    }

  })

  $('.sub02-01 .goods-review ul > li.content .con-bottom .review-img-box li img').click(function(){
    window.open(this.src)

  })


	if($(".calendar-box").length > 0){

		  // 캘린더
		  const today = new Date(); //오늘 날짜의 Date 객체를 생성합니다.

		  //달력 데이터를 가공하는 함수를 만듭니다.
		  const setCalendarData = (year, month) => {
		  let calHtml = "";       //빈 문자열을 만들어줍니다.
		  const setDate = new Date(year, month - 1, 1);
		   //getMonth(): Get the month as a number (0-11)
		  //month 인자는 getMonth로 구한 결과 값에 1을 더한 상태이므로
		  //다시 1을 뺀 값을 Date 객체의 인자로 넘겨줍니다.
		  //그러면 오늘 날짜의 Date 객체가 반환됩니다.

		   const firstDay = setDate.getDate();

			//getDate(): Get the day as a number (1-31)
		  //이번 달의 첫째 날을 구합니다.

		  const firstDayName = setDate.getDay(); 
		   //getDay(): Get the weekday as a number (0-6)
		  //이번 달의 처음 요일을 구합니다.


		   //new Date(today.getFullYear(), today.getMonth(), 0);
		  //Date객체의 day 인자에 0을 넘기면 지난달의 마지막 날이 반환됩니다.
		  //new Date(today.getFullYear(), today.getMonth(), 1);
		  //Date객체의 day 인자에 1을 넘기면 이번달 첫째 날이 반환됩니다.
		  const lastDay = new Date(   //이번 달의 마지막 날을 구합니다.
			today.getFullYear(),
			today.getMonth() + 1,
			0
		  ).getDate();
		  const prevLastDay = new Date( //지난 달의 마지막 날을 구합니다.
			today.getFullYear(),
			today.getMonth(),
			0
		  ).getDate();

	//매월 일수가 달라지므로 이번 달 날짜 개수를 세기 위한 변수를 만들고 초기화 합니다.
		  let startDayCount = 1;
		  let lastDayCount = 1;
	 
		  for (let i = 0; i < 6; i++) { //1~6주차를 위해 6번 반복합니다.
			for (let j = 0; j < 7; j++) { //일요일~토요일을 위해 7번 반복합니다.
			  if (i == 0 && j < firstDayName) {
				// i == 0: 1주차일 때
			  // j < firstDayName: 이번 달 시작 요일 이전 일 때

				if (j == 0) {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style=';' class='calendar__day'><span>${(prevLastDay - (firstDayName - 1) + j)}</span></div>`;
				} else if (j == 6) {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style=';' class='calendar__day'><span>${(prevLastDay - (firstDayName - 1) + j)}</span></div>`;
				} else {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style=';' class='calendar__day'><span>${(prevLastDay - (firstDayName - 1) + j)}</span></div>`;
				}
			  }
			  else if (i == 0 && j == firstDayName) {

				if (j == 0) {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style=';' class='calendar__day s_${year}${month}${startDayCount}'><span class='${year}${month}${setFixDayCount(startDayCount)}'>${startDayCount++}</span></div>`;
				} else if (j == 6) {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style=';' class='calendar__day s_${year}${month}${startDayCount}'><span class='${year}${month}${setFixDayCount(startDayCount)}'>${startDayCount++}</span></div>`;
				} else {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style=';' class='calendar__day s_${year}${month}${startDayCount}'><span class='${year}${month}${setFixDayCount(startDayCount)}'>${startDayCount++}</span></div>`;
				}
			  }
			  else if (i == 0 && j > firstDayName) {

				if (j == 0) {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style='' class='calendar__day s_${year}${month}${startDayCount}'><span class='${year}${month}${setFixDayCount(startDayCount)}'>${startDayCount++}</span></div>`;
				} else if (j == 6) {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style='' class='calendar__day s_${year}${month}${startDayCount}'><span class='${year}${month}${setFixDayCount(startDayCount)}'>${startDayCount++}</span></div>`;
				} else {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style='' class='calendar__day s_${year}${month}${startDayCount}'><span class='${year}${month}${setFixDayCount(startDayCount)}'>${startDayCount++}</span></div>`;
				}
			  }
			  else if (i > 0 && startDayCount <= lastDay) {

				if (j == 0) {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style=';'class='calendar__day s_${year}${month}${startDayCount}'><span class='${year}${month}${setFixDayCount(startDayCount)}'>${startDayCount++}</span></div>`;
				} else if (j == 6) {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style=';'class='calendar__day s_${year}${month}${startDayCount}'><span class='${year}${month}${setFixDayCount(startDayCount)}'>${startDayCount++}</span></div>`;
				} else {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style=';'class='calendar__day s_${year}${month}${startDayCount}'><span class='${year}${month}${setFixDayCount(startDayCount)}'>${startDayCount++}</span></div>`;
				}
			  }	
			  else if (startDayCount > lastDay) {

				if (j == 0) {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style=';' class='calendar__day'><span class='${year}${month+1}${lastDayCount}'>${lastDayCount++}</span></div>`;
				} else if (j == 6) {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style=';' class='calendar__day' ><span class='${year}${month+1}${lastDayCount}'>${lastDayCount++}</span></div>`;
				} else {
				  // 스타일링을 위한 클래스 추가
				  calHtml +=
					`<div style=';' class='calendar__day' ><span class='${year}${month+1}${lastDayCount}'>${lastDayCount++}</span></div>`;
				}
			  }
			}
		  }
		  document
			.querySelector(".calendar")
			.insertAdjacentHTML("beforeend", calHtml);
		};

		const setFixDayCount = number => {
		  let fixNum = "";
		  if (number < 10) {
			fixNum = "0" + number;
		  } else {
			fixNum = number;
		  }
		  return fixNum;
		};

		if (today.getMonth() + 1 < 10) {
		  setCalendarData(today.getFullYear(), "0" + (today.getMonth() + 1));
		} else {
		  setCalendarData(today.getFullYear(), "" + (today.getMonth() + 1));
		}

		
		//이번주 구하기
		// 결과
		let weekHtml = ""; 
		var value = [];
	 
		// 오늘의 요일 및 날짜
	  let weekDayCount = 1;
		var currentDay = new Date();  
		var theYear = currentDay.getFullYear();
		var theMonth = Number(currentDay.getMonth()) + 1;
		var theDate  = Number(currentDay.getDate());
		var theDay  = Number(currentDay.getDay());

		//console.log(theDay);
	  let startDayCount = 1;
	 
		// 날짜 업데이트
		var newYear, newMonth, newDate;
	  if (theMonth < 10) {
		theMonth = "0" + (theMonth);
	  } else {
		theMonth =  "" + (theMonth);
	  }
	 
		// 이번달 마지막날
		var nowLast = new Date ();
			nowLast.setMonth(nowLast.getMonth() + 1);
		var nowLastDay = new Date( nowLast.getYear(), nowLast.getMonth(), "");
			nowLastDay = nowLastDay.getDate();
	 
		var lastDay; // 이전 달 마지막날 파악
	 
		for (var i = -theDay; i < (theDay-7)*-1; i++){
	 
			newYear = theYear;
			newDate = theDate;
			newMonth = theMonth;
	 
			//첫주 일때
			if(theDate+i < 1){
				
				if(theMonth == 1){ // 1월 첫째주 일때
					lastDay = new Date(Number(currentDay.getFullYear())-1, Number(currentDay.getMonth())+12, "");
				} else { // 1월 첫째주가 아닐때
					lastDay = new Date(currentDay.getFullYear(), currentDay.getMonth(), "");
				}
	 
				newYear = lastDay.getFullYear();
				newMonth = lastDay.getMonth();
				newDate = Number(lastDay.getDate())+i;
	 
			//마지막주 일때
			} else if( theDate+i > nowLastDay) {
	 
				if(theMonth == 12){ // 12월 마지막주 일때
					newYear = Number(theYear) + 1;
				}
	 
				newMonth = Number(theMonth) + 1;
				newDate = i;
	 
			}
	 
			newDate = (newDate + i);
			
			// yyyy-mm-dd 형식으로
			if(String(newDate).length < 2){
				newDate = "0" + String(newDate);
			}
			if(String(newMonth).length < 2){
				newMonth = "0" + String(newMonth);
			}
	 
	 
			//이번주 7일의 날짜를 value에 담는다.
			value.push(newYear + "-" + newMonth + "-" + newDate);

			weekHtml +=
					`<div style='background-color:;' class='calendar__day'><span class='${theYear}${theMonth}${newDate}'>${newDate}</span></div>`;
			}
	 
		console.dir(value);
	  document
	  .querySelector("#week")
	  .insertAdjacentHTML("beforeend", weekHtml);

	}

  // 캘린더 클릭시
  $('.sub04-01 .calendar .calendar__day span').click(function(){

    if($(this).hasClass('on')){
      $(this).removeClass('on')  
    }else{

      $('.sub04-01 .calendar .calendar__day span').removeClass('on')  
      $(this).addClass('on')
      }  
	  
    })

    // week 클릭시
    $('#week .calendar__day span').click(function(){ 
        $('#week .calendar__day span').removeClass('on')  
        $(this).addClass('on')

      });


	  //월 변경
      $(".calendar .month .month-btn-left").click(function(){

			var today			=	new Date(); 

			var month_text	=	$(".calendar .month .month-text").text();
			var month			=	parseInt(month_text.replace("월",""));

			if(month == (today.getMonth()+1)){
				alert("현재일보다 낮게 설정은 불가능합니다.");
				return;
			}

			calendar_change((month-2),'prev');

      });

      $(".calendar .month .month-btn-right").click(function(){
			var month_text	=	$(".calendar .month .month-text").text();
			var month			=	parseInt(month_text.replace("월",""));

			calendar_change(month, 'next');
      });

})



$(function(){


 // $('.sub04-03 .calendar__day').eq(0).find('span').addClass('now')
  
  // 내구독 배송일 변경 
   $('.sub04-03 .calendar__day span').click(function(){    
    if($(this).hasClass('modify')){
      $(this).removeClass('modify') 
		$("#chg_date").val("");
    }else{
      $('.sub04-03 .calendar__day span').removeClass('modify')  
      $(this).addClass('modify')
		$("#chg_date").val($(this).text());
      }   
  })

  //입점 문의 및 제휴

  var fileTarget = $('.filebox .upload-hidden');
   fileTarget.on('change', function(){ 
    // 값이 변경되면 
    if(window.FileReader){ 
      // modern browser 
      var filename = $(this)[0].files[0].name; 
    } else { 
      // old IE 
      var filename = $(this).val().split('/').pop().split('\\').pop(); 
      // 파일명만 추출 } // 추출한 파일명 삽입
       
    }
    $(this).parents('.filebox').find('.upload-name').val(filename); 
  })

  $('input.upload-name').on({    
    click:function(){
      $('.filebox input[type=file] + span').trigger('click')
    },
    focus:function(){
      $('.filebox input[type=file] + span').trigger('click')
    }    
  })


//공유하기
$('.icon-box .share').click(function(){
  $('.sub02-01 .goods-content .share-box').show()
  $('.sub03-03 .share-box').show()
})
  var url_com = $('.sub02-01 .goods-content .share-box .share-input-box a')
  var _url = $(location).attr('href');
  url_com.click(function(){
    $('.sub02-01 .goods-content .share-box .share-input-box input').val(_url)
  })
  $('.sub02-01 .goods-content .share-box > a, .sub03-03 .share-box > a').click(function(){
      $('.sub02-01 .goods-content .share-box, .sub03-03 .share-box').hide()
      $('.sub02-01 .goods-content .share-box .share-input-box input, .sub03-03 .share-box .share-input-box input').val('')
  })

  //결제수단관리 삭제관련
  $('.sub08-04 .content-box ul li .delete').click(function(){
    $('.sub08-04 .content-box .cancel-box').show()  
  })
$('.sub08-04 .content-box .cancel-box .btn-box a:first-child').click(function(){
  $('.sub08-04 .content-box .cancel-box').hide()
})

  //스킵메뉴
  
  $('#skip a').click(function(){
    alert($(this.hash))
    $('html, body').animate({
      scrollTop: $(this.hash).offset().top 
    }, 300);    

  })

  // 결제수단 등록
  $('.sub09-02 .password-error span').click(function(){
    $('.sub09-02 .password-error-wrap').hide()
  })

  // 약관 내용보기
  $('.sub06-04 .box-wrap .box .shop_provision').click(function(){
    $('.sub06-04 .pop-up').fadeIn()
  })
  $('.sub06-04 .pop-up a, .sub06-04 .pop-up img').click(function(){
    $('.sub06-04 .pop-up').fadeOut()
  })

  // 개인정보 수집 및 이용
  $('.sub06-04 .box-wrap .box .shop_private').click(function(){
    $('.sub06-04 .pop-up2').fadeIn()
  })
  $('.sub06-04 .pop-up2 a, .sub06-04 .pop-up2 img').click(function(){
    $('.sub06-04 .pop-up2').fadeOut()
  })

  // 전자금융거래 이용약관
  $('.sub06-04 .box-wrap .box  .shop_electronic').click(function(){
    $('.sub06-04 .pop-up3').fadeIn()
  })
  $('.sub06-04 .pop-up3 a, .sub06-04 .pop-up3 img').click(function(){
    $('.sub06-04 .pop-up3').fadeOut()
  })

  // 만 14세 이상 이용자
  $('.sub06-04 .box-wrap .box .shop_adult').click(function(){
    $('.sub06-04 .pop-up4').fadeIn()
  })
  $('.sub06-04 .pop-up4 a, .sub06-04 .pop-up4 img').click(function(){
    $('.sub06-04 .pop-up4').fadeOut()
  })

  //회원가입 약관동의
    
    function allCheckFunc( obj ) {
      $('.agree-check').prop("checked", $(obj).prop("checked") );
      // $(this).prop("checked", true);
  }
   /* 체크박스 체크시 전체선택 체크 여부 */
  function oneCheckFunc( obj )
  {
  var allObj = $("#agree06");
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
      
      $("#agree06").click(function(){          
              allCheckFunc( this );      
      });
      $('.agree-check').each(function(){
          $(this).click(function(){
              oneCheckFunc( $(this) );
          });
      });
  });

  //알람
 var floatPosition = parseInt($(".alarm-side").css('top'));

 $(window).scroll(function() {

   var scrollTop = $(window).scrollTop();

   if($('#footer').length > 0){
	   var footerTop = $('#footer').offset().top;
   }else{
	   var footerTop = 0;
   }

   var newPosition = scrollTop + floatPosition + "px";

   if(scrollTop >= footerTop-900){
    $(".alarm-side").css({
      "top": footerTop-150
    });
   }else{
   $(".alarm-side").stop().animate({
     "top": newPosition
   });
   }

 }).scroll();
  // 알람

  $('.footer .footer-right .top li:eq(0) a').click(function(){
    $('#footer-pop-up-private').fadeIn()
  })

$('.footer .footer-right .top li:eq(1) a').click(function(){
    $('#footer-pop-up').fadeIn()
  })

  $('#footer-pop-up img, #footer-pop-up a').click(function(){
    $('#footer-pop-up').fadeOut()
  })

  $('#footer-pop-up-private img, #footer-pop-up-private a').click(function(){
    $('#footer-pop-up-private').fadeOut()
  })

}) //








//스크립트

// maxlength
function maxLengthCheck(object){
    if (object.value.length > object.maxLength){
    object.value = object.value.slice(0, object.maxLength);
    }    
}
// login check

var login = {
    init: function () {
    },
    noSpaceCheck: function (obj) {
        var str_space = /\s/;
        if(str_space.exec(obj.value)) {
            alert("해당 항목에는 공백을 사용할수 없습니다.\n\n공백은 자동적으로 제거 됩니다.");
            obj.focus();
            obj.value = obj.value.replace(' ','');
            return false;
        }
    },
    memberReg: function () {
        var loginId = $('#mb_id').val();
        var userName = $('#nickname').val();
        var password = $('#password').val();
        var passwordConfirm = $('#passwordConfirm').val();

        if (!loginId) {
            alert('아이디를 입력하세요.');
            $('#mb_id').focus();
            return false;
        }

        if (!login.idPolicy(loginId)) {
            return false;
        }     

        if (!password) {
            alert('패스워드를 입력하세요.');
            $('#password').focus();
            return false;
        }

        if (!login.passwordPolicy(password)) {
            return false;
        }

        if (!passwordConfirm) {
            alert('패스워드 확인을 입력하세요.');
            $('#passwordConfirm').focus();
            return false;
        }

        if (password != passwordConfirm) {
            alert("입력한 두 개의 비밀번호가 서로 일치하지 않습니다.");
            return false;
        }

        if (!userName) {
            alert('닉네임을 입력하세요.');
            $('#userName').focus();
            return false;
        }


		if($.trim($("input[name=phone1]").val()) == "" || $.trim($("input[name=phone2]").val()) == "" || $.trim($("input[name=phone3]").val()) == ""){

			alert('전화번호를 입력하십시오.');
			$("input[name=phone1]").focus();
			return false;
		}


        
        $('#reg_user_form').submit();
    },
    idPolicy: function (value) {
        var loginIdRex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

        if(!loginIdRex.test(value)) {
            alert("아이디는 10 ~ 20자 이메일 형식이어야 합니다.");
            $('#mb_id').val('');
            $('#mb_id').focus();
            return false;
        }

        return true;
    },
    passwordPolicy: function (value) {
        var num		=	value.search(/[0-9]/g);
		var eng		=	value.search(/[a-z]/ig);
        //var spe		=	value.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);

        if(value.length < 8 || value.length > 20){

            alert("8자리 ~ 20자리 이내로 입력해주세요.");
            return false;
           }else if(value.search(/\s/) != -1){
            alert("비밀번호는 공백 없이 입력해주세요.");
            return false;
           }else if(num < 0 || eng < 0){
            alert("영문,숫자를 혼합하여 입력해주세요.");
            return false;
           }else {
              console.log("통과"); 
              return true;
           }
    }
};
login.init();


// 캘린더
























