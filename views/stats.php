<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$show = false;
	$home_link = "<a href='home/'>"._('Home')."</a>";
	if(can_view_statistics($logged_userid))
	{
		$show = true;	
		$query = "SELECT class_associations.user AS user,
					class_association_types.title AS title
					FROM class_associations,class_association_types 
					WHERE class_associations.class = '$cid' AND class_association_types.id = class_associations.type ORDER by class_association_types.priority ASC";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			while( $row = mysql_fetch_array($ret))
			{

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
<?php	}?>
</div>
