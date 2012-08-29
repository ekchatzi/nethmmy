<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

	$stats = array('announcements_made' => _('Announcements made'),
			'email_addresses_validated' => _('Email adresses validated'),
			'email_notifications' => _('Email notifications'),
			'files_downloaded' => _('Files downloaded'),
			'files_uploaded' => _('Files uploaded'),
			'file_folders_created' => _('File folders created'),
			'labs_created' => _('Labs created'),
			'lab_files_uploaded' => _('Lab files uploaded'),
			'lab_teams_created' => _('Lab teams created'),
			'passwords_changed' => _('Passwords changed'),
			'user_accounts_created' => _('User accounts created'));

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$show = false;
	$home_link = "<a href='home/'>"._('Home')."</a>";
	if(can_view_statistics($logged_userid))
	{
		$show = true;	
		$stat_name = array();
		$stat_value = array();
		$query = "SELECT * FROM global_stats";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			while( $row = mysql_fetch_array($ret))
			{
				$stat_name[] = $row['name'];
				$stat_value[] = $row['value'];
			}
		}
	}
	else
	{
		$error[] = _('Access denied.');
	}

?>
<h2><?php echo _('Statistics');?></h2>
<p class='hierarchyNavigationRow'><?php echo $home_link . " > " . _('Statistics');?></p>
<div class='statsWrapper'>
<?php	if($show) {?>
		<ul class='statisticsList'>
<?php		for($i=0;$i<count($stat_name);++$i){?>
			<li><?php echo (isset($stats[$stat_name[$i]])?$stats[$stat_name[$i]]:$stat_name[$i]);?> : <?php echo $stat_value[$i];?></li>
<?php		}?>
<?php		if(count($stat_name) == 0){?>
			<li><?php echo _('No statistics.');?></li>
<?php		}?>
		</ul>
<?php	}?>
</div>
