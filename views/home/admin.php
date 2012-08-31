<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');
	
	if(!isset($error))
                $error = array();

	if($show_inactive = can_view_inactive_accounts($logged_userid))
	{
		$inactive = 0;
		$query = "SELECT COUNT(*) FROM users WHERE is_active = '0'";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
			$inactive = mysql_result($ret,0,0);
	}

	if($show_log = can_view_log($logged_userid))
	{
		include_once('../lib/log.php');
		$log_messages = array();
		$log_times = array();
		$query = "SELECT * FROM log WHERE 1 ORDER BY time DESC LIMIT $LOG_MESSAGES_SHOWN";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			while($row = mysql_fetch_array($ret))
			{
				$log_times[] = strftime($DATE_FORMAT,$row['time']);
				$log_messages[] = parse_log_message($row['type'],$row['data']); 
			}
		}
	}
?>
<h2><?php echo _('Administrator\'s home');?></h2>
<div class='adminHomeWrapper'>
<?php	if($show_inactive) {?>
		<p>There are <a href='inactive/'><?php echo $inactive;?></a> inactive accounts.</p>
<?php 	}?>
<?php 	if($show_log) {?>
	<h3><?php echo _('Recent activity');?></h3>
		<ul class='logList'>
<?php		for($i=0;$i<count($log_messages);$i++){?>
			<li>[<?php echo $log_times[$i];?>] <?php echo $log_messages[$i];?></li>
<?php		}?>
<?php		if(count($log_messages) == 0) {?>
			<li><?php echo _('No log entries.');?></li>
<?php		}?>
		</ul>
<?php		if(can_clean_log($logged_userid)) {?>
		<form action='clean_log.php' method='post'>
		<fieldset>
			<legend><?php echo _('Clean Log');?></legend>
			<p><?php echo sprintf(_('Clean log and keep %s entries'),"<input class='countInput' name='count' value='$LOG_SIZE'/>");?>
				<input type='submit' value="<?php echo _('Go');?>" />
			</p>
		</fieldset>
		</form> 
<?php		}?>
<?php	}?>
</div>
