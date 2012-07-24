<h1><?php echo _("Redirection");?></h1>
<div class='redirectionWrapper'>
<?php

if($warning)
{
	echo "<p>"._("Warning<br/>")."$warning</p>";
}
if($error)
{
	echo "<p>"._("Error<br/>")."$error</p>";
}
	echo "<p class='redirectionMessage'>$message</p>";
	echo "<p class='redirectionCounter'></p>";
	echo "<p>".sprintf(_("<a href='%s'>Skip</a>"),$redirect)."</p>";

	?>
</div>
<script type='text/javascript'>
<!--
	var time_left = <?php echo $waittime;?>;
	$(document).ready(function(){
		var interval = setInterval(function(){
			time_left -=50;
			$('.redirectionCounter').html(sprintf('<?= _("Auto-redirection in %s seconds.");?>',Math.ceil(time_left/1000)));
			if(time_left <=0 )
			{
				window.location = '<?php echo $redirect;?>';
				clearInterval(interval); 
			}
		},50);
	});
//-->
</script>
