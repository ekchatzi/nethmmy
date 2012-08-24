<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

	$show = false;
	if(can_view_professor_list($logged_userid)) 
	{
		$show = true;
		$query = "SELECT * FROM users WHERE user_type = '2' ORDER BY last_name ASC";
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
?>

<h2><?php echo _('Professors');?></h2>
<div class='professorListWrapper'>
<?php for($i=0;$i<count($name);$i++) {?>
	<p class='professorName'><?php echo $name[$i];?></p>
<?php }?>
<?php if(count($name)<1) {?>
	<p><?php echo _('There are no registered professors yet');?></p>
<?php }?>