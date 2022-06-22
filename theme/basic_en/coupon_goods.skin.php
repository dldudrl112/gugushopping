<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="new_win">
	<h1 id="win_title"><?php echo $tb['title']; ?></h1>

	<div class="win_desc marb20">
		<p class="bx-danger">
			*You can purchase the product in detail with this coupon. Click on the item and use the coupon when ordering.<br>
			* The coupon issued is <b>[My Page > Coupon management]</b> You can check at .
		</p>
	</div>

	<div class="win_desc">
		<p class="pg_cnt">
			<em>Total <?php echo number_format($total_count); ?>case</em> inquiry
		</p>	

		<table class="wfull bt bb">
		<tr>
			<?php
			for($i=0; $row=sql_fetch_array($result); $i++) {	
				if($i && $i%$mod==0) {
					echo "</tr><tr><td colspan=\"{$mod}\" width=\"100%\" height=\"1\" style=\"background:#e5e5e5\"></td></tr><tr>";
				}

				echo "<td width=\"{$td_width}%\" align=\"center\" class=\"vat padt15\">";
			?>
			<table class="w140">
			<tr>
				<td style="border:1px solid #e5e5e5"><a href="javascript:goto_item('<?php echo $row['index_no']; ?>');"><?php echo get_it_image($row['index_no'], $row['simg1'], 140, 140); ?></a></td>
			</tr>
			<tr>
				<td class="padt10 bold tac"><?php echo get_price($row['index_no']); ?></td>
			</tr>
			<tr>
				<td class="padt10 padb10"><?php echo cut_str($row['gname'], 44); ?></td>
			</tr>
			</table>
		<?php
			echo "</td>";
		}

		// The rest td
		$cnt = ($i%$mod);
		if($cnt) {
			for($i=$cnt; $i<$mod; $i++) {
				echo "<td width=\"{$td_width}%\">&nbsp;</td>";
			}
		}
		
		if(!$total_count)
			echo '<tr><td colspan="5" class="empty_list">We don't have any data.</td></tr>';
		?>
		</table>
		
		<?php
		echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?lo_id='.$lo_id.'&page=');
		?>
	</div>

    <div class="win_btn">
		<a href="javascript:window.close();" class="btn_lsmall bx-white">Close Window</a>
    </div>
</div>

<script>
function goto_item(gs_id){
	opener.document.location.href = tb_shop_url+"/view.php?index_no="+gs_id;
	self.close();
}
</script>
