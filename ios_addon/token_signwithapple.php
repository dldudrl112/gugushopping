<?php

// android의 경우 토큰 관련 정보 받기
// ios의 경우 사용자가 푸시 받을 지를 승인을 해주어야 토큰이 생성이 되어어서 javascript 함수로 앱에 요청하는 방식을 사용해야 한다

// 안드로이드용 토큰을 메인 페이지에서 get 방식으로 받는다.
$phone_token = $_REQUEST['token'];


//echo "<script>alert(" . $phone_token . ");</script>";

// URLEncoder로 엔코딩하니 디코딩하여 사용하도록 한다
//String tokenStr = URLEncoder.encode(tokenS, "UTF-8"); <- 안드로이드에서 엔코딩하여 get 방식으로 전달



$fields = array(
    //'registration_ids' => $tokens,
    'data' => $arr,
    'priority' => 'high',
    'content-available' => true,
    'to' => '/topics/android_gugu' //  전체 푸시 대상을 지정한다 ==>  android_gugu
   );


?>




<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" />
</head>
<body>


    <div class="w3-container">
        <h5>ios Apple Login</h5>
        // 로그인에 사용되는 버튼은 Apple사의 디자인 가이드대로 해야 리젝을 안당합니다.<br/>
        // 아래 주소를 참고하여 구현하세요<br/>
        // https://developer.apple.com/design/human-interface-guidelines/sign-in-with-apple/overview/buttons/<br/>
        <button id="appleid_signin" class="w3-btn w3-black">Apple Login</button>

    </div>

    <div class="w3-container">
        <h5>ios 푸시 토큰 가져오기</h5>
       
        <button id="gettoken" class="w3-btn w3-black">ios 푸시 토큰 가져오기</button>
        
    </div>


    <script>

        var phone_token = "";

        // token 정보 가져오기
        $(document).ready(function(){
           phone_token = "<?php echo $phone_token; ?>";

           //decoding하여 사용하도록 한다.
	       phone_token =decodeURI(phone_token );
           alert("안드로이드 토큰 정보:" + phone_token);
        
        });


        // ios 토큰 가져오기
        $("#gettoken").click(function () {
           

            try {
                window.webkit.messageHandlers.invokeAction.postMessage("getToken");  // ios 토큰 요청
            }
            catch (e) {
                //alert(e);
            }
 
        });

        // ios 토큰 요청 후 받는 곳
        function getToken(phone_kind, token) {
            //'ios', returnStr + "', '" + AppDelegate.token + "'
            alert("폰ㅋ토큰 = " + token);

        }

        



        // 애플 로그인 호출
        $("#appleid_signin").click(function () {
            //alert("login clicked.");
            try {
                //window.jsBridge.invokeAction("signwithapple");  // 안드로이드 호출
            }
            catch (e) {
                //alert(e);
            }

            try {
                window.webkit.messageHandlers.invokeAction.postMessage("signwithapple");  // ios apple logon 호출
            }
            catch (e) {
                //alert(e);
            }

        });

        // 애플 로그인 결과 받는 곳
        function getAppleToken(phone_kind, login_state, userIdentifier, userName, userEmail, token) {
            //'ios', returnStr + "', '" + AppDelegate.token + "'
            alert("정보:폰 종류 = " + phone_kind  + "\r\n로그인 상태 = " + login_state

                + "\r\nuser_id = " + userIdentifier + "\r\n유저네임 = " + userName + "\r\n유저이메일 = " + userEmail + "\r\n폰토큰 = " + token);

            // 정상 로그인 후 반환 시 returnStr 내용 => userIdentifier 외 다른 정보들은 반환 안될 수 있음(사용자가 공개를 꺼려서 안보내기 옵션을 선택 시)
            


        }




    </script>

</body>
</html>
