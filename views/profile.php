<div class='profileWrapper'>
<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error))
                $error = '';

	$uid = isset($_GET['id'])?$_GET['id']:0;
	if(!($e = user_id_validation($uid)))
	{
		$query = "SELECT * FROM users WHERE id='$uid' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$result = mysql_fetch_array($ret);
?>
			<h2><?php echo $result['first_name'].' '.$result['last_name'];?></h2>
<?php			if(can_view_profile($logged_userid,$uid))
			{				
				/* Title */
				$title_id = $result['title'];
				$title = _('User');
				$title_description = _('Common user');
				if(!$e = title_id_validation($title_id))
				{
					$query = "SELECT title,description FROM titles WHERE id='$title_id";
					$ret2 = mysql_query($query);
					if($ret2 && mysql_num_rows($ret))
					{
						$result2 = mysql_fetch_array($ret2);
						$title = $result2['title'];
						$title_description = $result2['description'];
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
				echo "<p>$title</p>";
?>
<?php				/* AEM */
				$uid_type = $result['user_type'];
				if(!($e = user_type_validation($uid_type)) && $USER_TYPES[$uid_type] == 's')
				{
					echo "<p><label>"._("ΑΕΜ").":</label>&nbsp;".$result['aem']."</p>";
					echo "<p><label>"._("Semester").":</label>&nbsp;".($result['semester'] + floor((time() - $result['semester_update_time'])/(60*60*24*30*6)))."</p>";
				}
				else
				{
					$error .= $e;
				}
				?>

<?php				/* Contact information */
				if(can_view_contact_information($logged_user,$uid))
				{?>
					<fieldset class='contactInfoWrapper'>
						<legend><?php echo _('Contact Info');?></legend>
						<ul>
							<li><label><?php echo _("Email");?>: </label><?php echo $result['email'];?></li>
							<li><label><?php echo _("Telephone");?>: </label><?php echo $result['telephone'];?></li>
							<li><label><?php echo _("Website");?>: </label><?php echo $result['website'];?></li>
						</ul>
					</fieldset>
<?php				}?>
				
				<fieldset class='bioWrapper'>
					<legend><?php echo _('Biography');?></legend>
					<p><?php echo ((strlen($result['bio'])>0)?$result['bio']:_('There is no biography yet.'))?></p>
				</fieldset>

				<fieldset class='associatedClassesWrapper'>
					<legend><?php echo _('Associated classes');?></legend>
<?php				/* Associated classes */
				$classes = array();
				$classes_titles = array();
				$query = "SELECT class_associatons.class AS class,
							class_associatons_types.title AS title,
							class_associatons_types.description AS description
							FROM class_associatons,class_associatons_types WHERE class_associatons.user='$uid' AND class_associatons_types.id = class_associatons.type";
				$ret2 = mysql_query($query);
				if($ret2 && mysql_num_rows($ret2))
				{
					while( $row = mysql_fetch_array($ret2))
					{
						$id = $row['class'];
						$classes[] = $id;
						$classes_titles[$id] = $row['title'];
						$classes_descriptions[$id] = $row['description'];
					}
					$classes = implode(',',$classes);
					
					$query = "SELECT id,title FROM classes 
							WHERE FIND_IN_SET(id,'$classes')";
					$ret3 = mysql_query($query);
					if($ret3 && mysql_num_rows($ret3))
					{
						while( $row = mysql_fetch_array($ret3) )
						{
							$cid = $row['id'];
							$title = $row['title'];							
							?>
							<ul class='associatedClassesList'>
								<li><a href="index.php?v=class&amp;cid=<?php echo $cid;?>"><?php echo "$title - ".$classes_titles[$cid];?></li>
							</ul>
<?php						}			
					} 
				}
				else
				{
					echo "<p>"._('None')."</p>";
				}
?>
				</fieldset>
	
<?php				/* Account information */
				
				$account_type_text = $USER_TYPES_FULL[$result['user_type']];				
				if(can_view_account_information($logged_userid,$uid))
				{?>
					<fieldset class='accountInformationWrapper'>
						<legend><?php echo _('Account Information');?></legend>
						<ul>
							<li><label> <?php echo _("Username");?>: </label><?php echo $result['username'];?></li>
							<li><label> <?php echo _("Account type");?>: </label><?php echo $account_type_text." - ".($result['is_active']?'Active':'Inactive');?></li>
							<li><label> <?php echo _("Registered on");?> </label><?php echo strftime($DATE_FORMAT,$result['registration_time']);?></li>
							<li><label> <?php echo _("Last Login on");?> </label><?php echo strftime($DATE_FORMAT,$result['last_login']);?></li>
							<li><label> <?php echo _("From");?> </label><?php echo $result['last_remote_adress'];?></li>
						</ul>
					</fieldset>
<?php				}?>
<?php
				if(can_edit_account($logged_userid,$uid))
				{?>
					<a href="index.php?v=edit_profile&amp;id=<?php echo $uid;?>" id='editProfileLink'><?php echo _('Edit');?></a> 
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
