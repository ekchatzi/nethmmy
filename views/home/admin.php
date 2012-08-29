<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');
	
	$show = false;
	if(!isset($error))
                $error = '';
	$query = "SELECT id FROM users WHERE is_active = '0'";
	$ret = mysql_query($query);
	$inactive = 0;
	if($ret && mysql_num_rows($ret))
	{
		$show = true;
		while( $row = mysql_fetch_array($ret)) {
			$inactive += 1;
		}
	}
?>
<h2> Admin </h2>
<div class='adminHomeWrapper'>
<?php if($show) {?>
	<p>There are <a href='inactive/'><?php echo $inactive;?></a> inactive accounts</p>
<?php }?>
</div>