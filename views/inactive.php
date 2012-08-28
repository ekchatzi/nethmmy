<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$show = false;
	if(can_view_inactive_accounts($logged_userid)) 
	{
		$show = true;
		$query = "SELECT users.id AS id,
				  users.last_name AS last_name,
				  users.first_name AS first_name,
				  users.username AS username
				  FROM users
				  WHERE users.is_active = '0' ORDER BY users.registration_time DESC";
		$ret = mysql_query($query);
		$name = array();
		if($ret && mysql_num_rows($ret))
		{	
			while($row = mysql_fetch_array($ret))
			{	
				$name[] = (can_view_profile($logged_userid,$row['id']))?"<a href = 'profile/".$row['id']."/'>".$row['last_name']." ".$row['first_name']."</a>"." (".$row['username'].")":$row['last_name']." ".$row['first_name']." (".$row['username'].")";
			}
		}
	}
	else 
	{
		$error[] = _("Access Denied");
	}
?>

<h2><?php echo _('Inactive accounts');?></h2>
<div class='professorListWrapper'>
<?php if($show) {?>
<?php 	for($i=0;$i<count($name);$i++) {?>
	<p class='professorName'><?php echo $name[$i];?></p>
<?php 	}?>
<?php 	if(count($name)<1) {?>
	<p><?php echo _('There are no inactive accounts.');?></p>
<?php 	}?>
<?php }?>
</div>

