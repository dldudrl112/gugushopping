<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
?>

<h2 class="pop_title">
	<?php echo $tb['title']; ?>
	<a href="javascript:window.close();" class="btn_small bx-white">close window</a>
</h2>
<div class="m_agree">
	<?php echo nl2br($config['shop_policy']); ?>
</div>
<div class="btn_confirm">
	<button type="button" onclick="window.close();" class="btn_medium bx-white">close Window</button>
</div>
