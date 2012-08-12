<h2><?php echo _('Class information');?></h2>
<div class='classWrapper'>
<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error))
                $error = '';

	$cid = isset($_GET['id'])?$_GET['id']:0;
	if(!($e = class_id_validation($cid)))
	{
		$query = "SELECT * FROM classes WHERE id='$cid' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$result = mysql_fetch_array($ret);
?>
<?php			if(can_view_class($logged_userid,$cid))
			{?>					
				<p><?php echo sprintf(_('Taught on semester(s) %s'),$result['semesters']);?></p>
				<fieldset class='associatedProffessorsWrapper'>
					<legend><?php echo _('Associated professors');?></legend>
<?php					/* Associated professors */
					$users = array();
					$user_titles = array();
					$query = "SELECT class_associations.user AS user,
								class_association_types.title AS title
								FROM class_associations,class_association_types 
								WHERE class_associations.class = '$cid' AND class_association_types.id = class_associations.type ORDER by class_association_types.priority ASC";
					$ret2 = mysql_query($query);
					if($ret2 && mysql_num_rows($ret2))
					{
						while( $row = mysql_fetch_array($ret2))
						{
							$uid = $row['user'];
							$association_title = $row['title'];
							$query = "SELECT id,first_name,last_name FROM users 
									WHERE id = '$uid'";
							$ret3 = mysql_query($query);
							if($ret3 && mysql_num_rows($ret3))
							{
								while( $row = mysql_fetch_array($ret3) )
								{
									$uid = $row['id'];
									$name = $row['first_name'].' '.$row['last_name'];							
									?>
									<ul class='associatedUsersList'>
										<li><a href="profile/<?php echo $uid;?>/"><?php echo "$name</a> - ".$association_title;?></li>
									</ul>
	<?php							}			
							} 
						}
					
						
					}
					else
					{
						echo "<p>"._('None')."</p>";
					}
?>
				</fieldset>
				<fieldset class='classDescriptionWrapper'>
					<legend><?php echo _('Description');?></legend>
					<p><?php echo ((strlen($result['description'])>0)?$result['description']:_('There is no description yet.'))?></p>
				</fieldset>
<?php
				if(can_edit_class($logged_userid,$uid))
				{?>
					<a href="edit_class/<?php echo $cid;?>/" id='editClassLink'><?php echo _('Edit');?></a> 
<?php				}?>
<?php			}
			else
			{
				$error .= _('Access denied.');
			}
		}
		else	
		{
			$error .= _('Database Error.');			
		}
	}
	else
	{
		$error .= $e;
	}

	if($error)
	{?>
		<script>
		$(document).ready(function(){
			$('#notificationText').html("<?php echo $error;?>");
		});
		</script>
<?php	}?>
</div>
