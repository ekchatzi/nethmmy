<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$show = false;
	$home_link = "<a href='home/'>"._('Home')."</a>";
	if(can_view_professor_list($logged_userid)) 
	{
		$show = true;
		$query = "SELECT users.id AS id,
				  users.last_name AS last_name,
				  users.first_name AS first_name,
				  titles.title AS title
				  FROM users, titles
				  WHERE user_type = '2' AND titles.id=users.title ORDER BY last_name ASC";
		$ret = mysql_query($query);
		$name = array();
		if($ret && mysql_num_rows($ret))
		{	
			while($row = mysql_fetch_array($ret))
			{	
				$name[] = (can_view_profile($logged_userid,$row['id']))?"<a href = 'profile/".$row['id']."/'>".$row['last_name']." ".$row['first_name']."</a> - ".$row['title']:$row['last_name']." ".$row['first_name']." - ".$row['title'];
			}
		}
	}
	else 
	{
		$error[] = _("Access Denied");
	}
?>
<h2><?php echo _('Professors');?></h2>
<p class='hierarchyNavigationRow'><?php echo $home_link . " > " . _('Professors');?></p>
<div class='professorListWrapper'>
<?php if($show) {?>
<?php 	for($i=0;$i<count($name);$i++) {?>
	<p class='professorName'><?php echo $name[$i];?></p>
<?php 	}?>
<?php 	if(count($name)<1) {?>
	<p><?php echo _('There are no registered professors');?></p>
<?php 	}?>
<?php }?>
</div>
