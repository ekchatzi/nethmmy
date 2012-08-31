<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$show = false;
	$class_title = _('Class information');
	$description = _('There is no description yet.');
	$cid = isset($_GET['id'])?$_GET['id']:0;
	$classes_link = _('Classes');
	if(can_view_classes_list($logged_userid))
		$classes_link = "<a href='classes/'>$classes_link</a>";
	if(!($e = class_id_validation($cid)))
	{
		$query = "SELECT * FROM classes WHERE id='$cid' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			if(can_view_class($logged_userid,$cid))
			{
				$show = true;	
				$result = mysql_fetch_array($ret);		
				$class_title = $result['title'];
				if((strlen($result['description'])>0))
					$description = $result['description'];
				/* Associated professors */
				$uid = array();
				$name = array();
				$association_title = array();
				$query = "SELECT class_associations.user AS user,
							class_association_types.title AS title
							FROM class_associations,class_association_types 
							WHERE class_associations.class = '$cid' AND class_association_types.id = class_associations.type ORDER by class_association_types.priority ASC";
				$ret2 = mysql_query($query);
				if($ret2 && mysql_num_rows($ret2))
				{
					while( $row = mysql_fetch_array($ret2))
					{
						$uid_t = $row['user'];
						$association_title[] = $row['title'];
						$query = "SELECT id,first_name,last_name FROM users 
								WHERE id = '$uid_t'";
						$ret3 = mysql_query($query);
						if($ret3 && mysql_num_rows($ret3))
						{
							while( $row = mysql_fetch_array($ret3) )
							{
								$uid[] = $row['id'];
								$name[] = $row['first_name'].' '.$row['last_name'];
							}			
						} 
					}
				}
			}
			else
			{
				$error[] = _('Access denied.');
			}
		}
		else	
		{
			$error[] = _('Database Error.');			
		}
	}
	else
	{
		$error[] = $e;
	}
?>
<h2><?php echo $class_title;?></h2>
<p class='hierarchyNavigationRow'><?php echo $classes_link . " > " . $class_title;?></p>
<div class='classWrapper'>
<?php	if($show) {?>
		<p><?php echo sprintf(_('Taught on semester(s) %s'),$result['semesters']);?></p>
		<fieldset class='associatedProffessorsWrapper'>
			<legend><?php echo _('Associated professors');?></legend>
<?php			for($i=0;$i<count($uid);++$i){?>
				<ul class='associatedUsersList'>
					<li><a href="profile/<?php echo $uid[$i];?>/"><?php echo $name[$i];?></a> - <?php echo $association_title[$i];?></li>
				</ul>
<?php			}?>
<?php			if(count($uid) == 0) {?>
				<p><?php echo _('No associated professors.');?></p>
<?php			}?>
		</fieldset>
		<fieldset class='classDescriptionWrapper'>
			<legend><?php echo _('Description');?></legend>
			<p><?php echo $description?></p>
		</fieldset>
<?php		
		$c1 = can_edit_class($logged_userid,$cid);
		$c2 = can_delete_class($logged_userid,$cid);
		if($c1 || $c2) {?>
			<div class='editOptionsWrapper'>
<?php			if($c1) {?>
				<a class='editLink' href="edit_class/<?php echo $cid;?>/"' ><img src='images/resource/edit-pencil.gif' class='icon editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
<?php			};?>
<?php			if($c2) {?>
				<a class='deleteLink' href='javascript:void(0)'><img src='images/resource/trash_can.png' class='icon deleteIcon' id="deleteIcon<?php echo $cid;?>" alt="<?php echo _('Delete');?>" title="<?php echo _('Delete');?>" /></a>
			<script type='text/javascript'>
				$(document).ready(function(){
					$('.deleteLink').click(function(){
						if (confirm("<?php echo _('Are you sure you want to delete this class?');?>")) {
							var s = "<form style='display:none' action='delete_class.php' method='post'>";
							s += "<input type='hidden' name='cid' value='"+<?php echo $cid;?>+"' />";
							s += '</form>';
							var form = $(s).appendTo('body');
							form.submit(); 	
						}
					});
				});
			</script>
<?php			};?>
			</div>
<?php		}?>
<?php	}?>
</div>
