<?php
include_once("./_common.php");

check_demo();

if(!$is_member) {
    alert("로그인 후 작성 가능합니다.");
}


$upl_dir	=	TB_DATA_PATH."/review";
$upl		=	new upload_files($upl_dir);

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}


$gs_id = trim(strip_tags($_POST['gs_id']));
$od_id = trim(strip_tags($_POST['od_id']));
$seller_id = trim(strip_tags($_POST['seller_id']));
$score = trim(strip_tags($_POST['score']));

if(substr_count($_POST['memo'], "&#") > 50) {
    alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
}


$memo = addslashes($_POST['memo']);

//파일 업로드 
for($i=0; $i<5; $i++) {

	if($img = $_FILES['filename']['name'][$i]) {
		if(!preg_match("/\.(gif|jpg|png)$/i", $img)) {
			alert("이미지가 gif, jpg, png 파일이 아닙니다.");
		}
	}

	if($_FILES['filename']['name'][$i]) {

		$filename = $upl->create_new_filename($_FILES['filename']['name'][$i], true);

		if(!is_dir($upl_dir)) {
			@mkdir($upl_dir, TB_DIR_PERMISSION);
			@chmod($upl_dir, TB_DIR_PERMISSION);
		}

		// 파일을 지정된 폴더로 이동시킨다.
		if(move_uploaded_file($_FILES['filename']['tmp_name'][$i], $upl_dir.'/'.$filename)) {
			@chmod($upl_dir.'/'.$filename, TB_FILE_PERMISSION);
			@unlink($_FILES['filename']['tmp_name'][$i]);
			$rimg[$i]	=	$filename;
		}else{
			@unlink($_FILES['filename']['tmp_name'][$i]);
			die("업로드에 실패했습니다.");
		}

	}

}




$sql = "insert into shop_goods_review 
		   set		gs_id = '$gs_id', 
					od_id = '$od_id', 
					mb_id = '$member[id]',
					title = '".addslashes($title)."',
					memo = '$memo',
					score = '$score',
					reg_time = '".TB_TIME_YMDHIS."',
					seller_id = '$seller_id',
					rimg1		 = '".$rimg[0]."',
					rimg2		 = '".$rimg[1]."',
					rimg3		 = '".$rimg[2]."',
					rimg4		 = '".$rimg[3]."',
					rimg5		 = '".$rimg[4]."',
					pt_id = '$pt_id' ";
sql_query($sql);





sql_query("update shop_goods set m_count = m_count+1 where index_no='$gs_id'");

$it_href		= TB_SHOP_URL.'/view.php?index_no='.$gs_id;

alert_close("정상적으로 등록 되었습니다.");
?>