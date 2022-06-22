<?php
if(!defined('_TUBEWEB_')) exit;

$sql_common = " from shop_goods_qa ";
$sql_search = " where gs_id = '$index_no' ";
$sql_order  = " order by iq_id desc ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$qa_total_count = $row['cnt'];

$qa_rows = 10;
$qa_total_page = ceil($qa_total_count / $qa_rows); // 전체 페이지 계산
if($qa_page == "") { $qa_page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$qa_from_record = ($qa_page - 1) * $qa_rows; // 시작 열을 구함
$num = $qa_total_count - (($qa_page-1)*$qa_rows);

$sql = " select * $sql_common $sql_search $sql_order limit $qa_from_record, $qa_rows ";
$result = sql_query($sql);
?>
<div class="container">
	<h4>문의하기</h4>
	<ul class="qna-list">
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$iq_subject = cut_str($row['iq_subject'], 66);

			if(substr($row['iq_time'],0,10) == TB_TIME_YMD) {
				//$iq_subject .= '&nbsp;<img src="'.TB_IMG_URL.'/icon/icon_new.gif" alt="new">';
			}

			$is_secret = false;
			if($row['iq_secret']) {
				$icon_secret = '<img src="'.TB_IMG_URL.'/m-secret.png" alt="비밀글">';

				if(is_admin() || $member['id' ] == $row['mb_id']) {
					$iq_answer = $row['iq_answer'];
				} else {
					$iq_answer = "";
					$is_secret = true;
				}
			} else {
				$icon_secret = "";
				$iq_answer = $row['iq_answer'];
			}

			if($row['iq_answer']) {
				$icon_answer = '<img src="'.TB_IMG_URL.'/icon/icon_answer.jpg" alt="답변완료">';
			} else {
				$icon_answer = '<img src="'.TB_IMG_URL.'/icon/icon_standby.jpg" alt="미답변">';
			}

			$mb_qa	=	get_member($row["mb_id"],"name, nickname, mem_img");

			$len = strlen($row['mb_id']);
			$str = substr($row['mb_id'],0,3);
			$mb_id = $str.str_repeat("*",$len - 3);

			$hash = md5($row['iq_id'].$row['iq_time'].$row['iq_ip']);

			$bg = 'list'.$i%2;

		?>
		<li>
			<div class="top">
				<h5>
					<?php
					if(!$is_secret) { echo "<a href='javascript:void(0);' onclick=\"js_qna('".$i."')\">"; }
						echo $iq_subject;
					if(!$is_secret) { echo "</a>"; }
					?>
				</h5>
				<?=$icon_secret?>
				<?php if($iq_answer != "" && $row["iq_reply"] > 0){?>
				<img src="/img/reply-complete.png" alt="reply-complete" class="rep">
				<?php } ?>
			</div>
			<div class="bottom">
				<span class="name"><?=$mb_qa["name"]?></span>
				<span class="date"><?=substr($row['iq_time'],0,10)?></span>
			</div>
		</li>

		<li id="sod_qa_con_<?php echo $i; ?>" class="sod_qa_con" style="display:none;">
			<div class="top">
				<?php echo nl2br($row['iq_question']); ?>
			</div>
			<?php if($iq_answer != "" && $row["iq_reply"] > 0){?>
			<div class="qna-reply">
				<span class="thumb">
					<img src="/img/reply-thumb.png" alt="" class="reply-thumb">   				
					<!-- 관리자 썸네일 들어가는 부분 -->
				</span>
				<span class="con"><?=$iq_answer?></span>
			</div>
			<div class="bottom">
				<?php if(is_admin() || $member['id' ] == $row['mb_id'] && !$iq_answer) { ?>
				<div class="padt10"><a href="<?php echo TB_SHOP_URL; ?>/qaform.php?gs_id=<?php echo $row['gs_id']; ?>&iq_id=<?php echo $row['iq_id']; ?>&w=u" onclick="win_open(this,'upd','600','530','yes');return false"><span class="tu">수정</span></a>&nbsp;<a href="<?php echo TB_SHOP_URL; ?>/qaform_update.php?gs_id=<?php echo $row['gs_id']; ?>&iq_id=<?php echo $row['iq_id']; ?>&w=d&hash=<?php echo $hash; ?>" class="itemqa_delete"><span class="tu">삭제</span></a></div>
				<?php } ?>
			</div>
			<?php } ?>
		</li>
	<?php
	}
	if(!$qa_total_count)
		echo '<div class="empty_list bb">문의 내역이 없습니다.</div>';
	?>	
	</ul>

	<?php echo get_paging2($config['write_pages'], $qa_page, $qa_total_page, $_SERVER['SCRIPT_NAME'].'?index_no='.$index_no.'&qa_page='); ?>


	<div class="btn-box">
		<a href="<?php echo TB_SHOP_URL; ?>/qaform.php?gs_id=<?php echo $index_no; ?>" onclick="win_open(this,'qaform','600','600','yes');return false"  class="btn01">문의하기</a>
	</div>
</div>
<script>
function js_qna(id){
	var $con = $("#sod_qa_con_"+id);
	if($con.is(":visible")) {
		$con.hide(200);
	} else {
		$(".sod_qa_con:visible").hide();
		$con.show(200);
	}
}

$(function(){
    $(".itemqa_delete").click(function(){
        return confirm("정말 삭제 하시겠습니까?\n\n삭제후에는 되돌릴수 없습니다.");
    });
});
</script>
