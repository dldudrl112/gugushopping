<?php
/*
 * login_with_naver.php
 *
 * @(#) $Id: login_with_facebook.php,v 1.3 2013/07/31 11:48:04 mlemos Exp $
 * @(#) $Id: login_with_kakao,v 1.0 2014/12/30 wsgvet
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	include_once('./_common.php');
	require('http.php');
	require('oauth_client.php');


	$client = new oauth_client_class;
	$client->debug = false;
	$client->debug_http = true;
	$client->server = 'Kakao';
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_kakao.php';


	$client->client_id = $kakao_REST_APIkey; $application_line = __LINE__;
	//$client->client_secret = '';

	if(strlen($client->client_id) == 0)
		alert_close('카카오 연동키값을 확인해 주세요.');
	



	/* API permissions
	 */

	if(($success = $client->Initialize()))
	{





		if(($success = $client->Process())){

			if(strlen($client->access_token)){

				$success = $client->CallAPI(
					'https://kapi.kakao.com/v2/user/me', 
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}

		$success = $client->Finalize($success);


	}

	
	if($client->exit)
		exit;

	if($success)
	{


		$client->GetAccessToken($AccessToken);

		$mb_gubun		=	'k_';
		$mb_sns_id		=	$user->id; 
		$mb_id				=	$user->kakao_account->email;
		$mb_email			=	$user->kakao_account->email;
		$mb_name			=	$user->properties->nickname;
		$mb_nick			=	$user->properties->nickname;
		$mb_img			=	$user->properties->profile_image;
		$mb_img_type	=	"S";
		$mb_birth_year	=	$user->kakao_account->birthyear;
		$mb_birth_month	=	substr($user->kakao_account->birthday,0,2);
		$mb_birth_day	=	substr($user->kakao_account->birthday,2,2);
		$mb_phone		=	replace_tel(str_replace("+82","0",$user->kakao_account->phone_number));	//핸드폰

		if($user->kakao_account->birthday_type == "SOLAR"){
			$mb_birth_type		=	"S";
		}else if($user->kakao_account->birthday_type == "LUNAR"){
			$mb_birth_type		=	"L";
		}

		if($user->kakao_account->gender == "male"){
			$mb_gender		=	"M";
		}else if($user->kakao_account->gender == "female"){
			$mb_gender		=	"F";
		}

		$token_value		=	$AccessToken['value'];
		$token_refresh	=	'';
		$token_secret		=	'';

		//$client->ResetAccessToken();
		include_once('./oauth_check.php');

	} else {
		$error = HtmlSpecialChars($client->error);

		alert_close($error);
	}
?>