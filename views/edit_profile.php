<div class='editProfileWrapper'>
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
			<h2><?php echo _('Edit user information');?></h2>
<?php			if(can_edit_account($logged_userid,$uid))
			{								
				/* Contact information */
				if(can_view_contact_information($logged_user,$uid))
				{?>
				<form method='post' action='edit_profile.php'>
					<fieldset>
					<legend><?php echo _('User information');?></legend>
					<ul>
						<li><label><?php echo _("First name");?> </label><input type='text' name='first_name' value="<?php echo $result['first_name'];?>" placeholder="<?php echo _('Your first name');?>" /></li>
						<li><label><?php echo _("Last name");?> </label><input type='text' name='last_name' value="<?php echo $result['last_name'];?>" placeholder="<?php echo _('Your last name');?>" /></li>
<?php						$uid_type = $result['user_type'];
						if(!($e = user_type_validation($uid_type)) && $USER_TYPES[$uid_type] == 's' && can_edit_aem($logged_userid,$uid))
						{?>
							<li><label><?php echo _("ΑΕΜ");?> </label><input type='text' name='aem' value="<?php echo $result['aem'];?>" placeholder="<?php echo _('Your AEM');?>"/></li>
<?php						}
						else
						{
							$error .= $e;
						}?>
						<li><label><?php echo _("Semester");?> </label><input type='text' name='semester' value="<?php echo ($result['semester'] + floor((time() - $result['semester_update_time'])/(60*60*24*30*6)));?>" placeholder="<?php echo _('Your current semester');?>"/></li>
						<li><label><?php echo _("Email");?> </label><input type='text' name='email' value="<?php echo $result['email'];?>" placeholder="<?php echo _('Your email address');?>" /></li>
						<li><label><?php echo _("Telephone");?></label><input type='text' name='telephone' value="<?php echo $result['telephone'];?>" placeholder="<?php echo _('Your telephone number');?>" /></li>
						<li><label><?php echo _("Website");?> </label><input type='text' name='website' value="<?php echo $result['website'];?>" placeholder="<?php echo _('Your personal website URL');?>" /></li>
					</ul>
					<p>
						<label><?php echo _('Title');?> </label>
<?php					/* Title */
					if(can_edit_title($logged_userid,$uid,$row['id']))
					{
						$title_id = $result['title'];
						$title_list_id = array();
						$title_list_title = array();
						$title_list_description = array();
						echo "<select name='title' id='titleSelect'>";
						$query = "SELECT id,title,description FROM titles";
						$ret2 = mysql_query($query);
						while($row = mysql_fetch_array($ret2))
						{
							echo "<option value='".$row['id']."'";
							if($title_id == $row['id'])
								echo " selected='selected'";
							echo ">".$row['title']."</option>";
						}
					}				
					echo "</select>";				
					?>
					</p>
					<p>
						<label id='biographyLabel'><?php echo _('Biography');?></label>
						<textarea name='bio' class='bioTextarea' placeholder="<?php echo _('Some words about your self');?>"><?php echo $result['bio'];?></textarea>
					</p>
					<input type='hidden' name='uid' value="<?php echo $uid;?>" />
					<input type='submit' value="<?php echo _('Apply');?>" />
					</fieldset>
				</form>
<?php				}?>
<?php				/* Account information */
				$account_type_text = $USER_TYPES_FULL[$result['user_type']];				
				if(can_view_account_information($logged_userid,$uid))
				{?>
					<fieldset class='accountInformationWrapper'>
					<legend><?php echo _('Account');?></legend>
					<form method='post' action='change_user_type.php'>
						<ul>
							<li><label> <?php echo _("Account type");?> </label>
<?php						if(can_change_user_type($logged_userid,$uid))
						{?>
							<select name='user_type' id='userTypeSelect'>
<?php							
							$count = count($USER_TYPES);							
							for($i=0;$i<$count;++$i)
							{
								echo "<option value='$i' ";
								if($i == $result['user_type'])
									echo "selected='selected' ";
								echo ">".$USER_TYPES_FULL[$i]."</option>";
							}
?>							</select>						
<?php						}?>
<?php				
						if(can_change_active_status($logged_userid,$uid))
						{
							$is_active = $result['is_active'];
?>							<select name='active_status' id='activeStatusSelect'>	<?php
							for($i=0;$i<=1;++$i)
							{
								echo "<option value='$i' ";
								if($i == $is_active)
									echo "selected='selected' ";
								echo ">".(($i == 1)?_('Active'):_('Inactive'));
								echo "</option>";
							}
?>							</select>						<?php
						}?>						
						</ul>
						<input type='hidden' name='uid' value="<?php echo $uid;?>" />
						<input type='submit' value="<?php echo _('Apply');?>" />
					</fieldset>
					</form>
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
