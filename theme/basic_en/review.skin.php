<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_cs.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>Customer Service<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<p class="pg_cnt">
		<em>Total <?php echo number_format($total_count); ?>case</em>have a review of
	</p>

	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col width="50">
			<col width="70">
			<col>
			<col width="90">
			<col width="90">
			<col width="90">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">Number</th>
			<th scope="col">Image</th>
			<th scope="col">commodity review</th>
			<th scope="col">Writer</th>
			<th scope="col">Date of creation</th>
			<th scope="col">GPA</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$href = TB_MSHOP_URL.'/view.php?index_no='.$row['gs_id'];
			$gs = get_goods($row['gs_id'], 'gname, simg1');

			$bg = 'list'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>">
			<td class="tac"><?php echo $num--; ?></td>
			<td class="tac"><a href="<?php echo $href; ?>" target="_blank"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 50, 50); ?></a></td>
			<td class="td_name">
				<a href="<?php echo $href; ?>" target="_blank"><?php echo cut_str($gs['gname'], 55); ?></a>
				<p class="fc_999"><?php echo cut_str($row['memo'], 100); ?></p>
			</td>
			<td class="tac"><?php echo $row['writer_s']; ?></td>
			<td class="tac"><?php echo date("Y-m-d", $row['wdate']); ?></td>
			<td class="tac"><img src="<?php echo TB_IMG_URL; ?>/sub/score_<?php echo $row['score']; ?>.gif"></td>
		</tr>
		<?php
		}
		if($total_count==0)
			echo '<tr><td colspan="6" class="empty_list">We don't have any data.</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
	?>
</div>
