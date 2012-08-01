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
			<h2><?php echo $result['title'];?></h2>
<?php			if(can_view_class($logged_userid,$cid))
			{?>					
				<p><?php echo _('Semesters') . " " . $result['semesters'];?></p>
				<fieldset class='associatedProffessorsWrapper'>
					<legend><?php echo _('Associated professors');?></legend>
<?php					/* Associated professors */
					$users = array();
					$user_titles = array();
					$query = "SELECT class_associatons.user AS user,
								class_associatons_types.title AS title,
								class_associatons_types.description AS description
								FROM class_associatons,class_associatons_types WHERE class_associatons.class = '$cid' AND class_associatons_types.id = class_associatons.type";
					$ret2 = mysql_query($query);
					if($ret2 && mysql_num_rows($ret2))
					{
						while( $row = mysql_fetch_array($ret2))
						{
							$id = $row['user'];
							$classes[] = $id;
							$user_titles[$id] = $row['title'];
							$user_descriptions[$id] = $row['description'];
						}
						$users = implode(',',$users);
					
						$query = "SELECT id,first_name,last_name FROM users 
								WHERE FIND_IN_SET(id,'$users')";
						$ret3 = mysql_query($query);
						if($ret3 && mysql_num_rows($ret3))
						{
							while( $row = mysql_fetch_array($ret3) )
							{
								$uid = $row['id'];
								$name = $row['first_name'].' '.$row['last_name'];							
								?>
								<ul class='associatedUsersList'>
									<li><a href="index.php?v=profile&amp;id=<?php echo $uid;?>"><?php echo "$name - ".$user_titles[$uid];?></li>
								</ul>
<?php							}			
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
					<a href="index.php?v=edit_class&amp;id=<?php echo $cid;?>" id='editClassLink'><?php echo _('Edit');?></a> 
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
		<p class='error'><?php echo $error;?></p>
<?php	}?>
</div>
