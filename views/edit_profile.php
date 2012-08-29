<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$show = false;
	$user_link = _('some user');
	$uid = isset($_GET['id'])?$_GET['id']:0;
	$users_link = _('Users');
	if(user_type($uid) == 'p')
	{
		$users_link = _('Professors');
		if(can_view_professor_list($logged_userid))
			$users_link = "<a href='professors/'>$users_link</a>";
	}
	if(!($e = user_id_validation($uid)))
	{
		$query = "SELECT * FROM users WHERE id='$uid' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$result = mysql_fetch_array($ret);
			if(can_edit_account($logged_userid,$uid))
			{
				$show = true;
				$user_type = $result['user_type'];
				$account_type_text = $USER_TYPES_FULL[$result['user_type']];
				$first_name = $result['first_name'];
				$last_name = $result['last_name'];
				$user_link = "<a href='profile/$uid/'>".$first_name . ' ' . $last_name."</a>";
				$uid_type = $result['user_type'];
				$aem = $result['aem'];
				$semester = $result['semester'];
				$semester_update = $result['semester_update_time'];
				$email = $result['email'];
				$telephone = $result['telephone'];
				$website = $result['website'];
				$title_id = $result['title'];
				$is_active = $result['is_active'];
				$email_urgent = $result['email_urgent'];

				$title_list_id = array();
				$title_list_title = array();
				$title_list_description = array();
				if(can_edit_title($logged_userid,$uid))
				{
					$query = "SELECT id,title,description FROM titles";
					$ret2 = mysql_query($query);
					while($row = mysql_fetch_array($ret2))
					{
						$title_list_id[] = $row['id'];
						$title_list_title[] = $row['title'];
						$title_list_description[] = $row['description'];
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
<div class='editProfileWrapper'>
<h2><?php echo _('Edit profile');?></h2>
<p class='hierarchyNavigationRow'><?php echo $users_link . " > " . $user_link . " > " . _('Edit profile');?></p>
<?php	if($show) {?>
		<form method='post' action='edit_profile.php'>
			<fieldset>
			<legend><?php echo _('User information');?></legend>
			<ul>
				<li><label><?php echo _("First name");?> </label><input type='text' name='first_name' value="<?php echo $first_name;?>" placeholder="<?php echo _('Your first name');?>" /></li>
				<li><label><?php echo _("Last name");?> </label><input type='text' name='last_name' value="<?php echo $last_name;?>" placeholder="<?php echo _('Your last name');?>" /></li>
<?php		if(!($e = user_type_validation($uid_type)) && $USER_TYPES[$uid_type] == 's' && can_edit_aem($logged_userid,$uid)) {?>
				<li><label><?php echo _("ΑΕΜ");?> </label><input type='text' name='aem' value="<?php echo $aem;?>" placeholder="<?php echo _('Your AEM');?>"/></li>
<?php		}?>
				<li><label><?php echo _("Semester");?> </label><input type='text' name='semester' value="<?php echo ($semester + floor((time() - $semester_update)/(60*60*24*30*6)));?>" placeholder="<?php echo _('Your current semester');?>"/></li>
				<li><label><?php echo _("Email");?> </label><input type='text' name='email' value="<?php echo $email;?>" placeholder="<?php echo _('Your email address');?>" />
				<div id='receiveUrgents'><input type='checkbox' name='send_urgent' value='1' <?php if(isset($email_urgent)&&$email_urgent) echo "checked='true'";?>/><?php echo _('Send urgent announcements to this mail');?></div></li>
				<li><label><?php echo _("Telephone");?></label><input type='text' name='telephone' value="<?php echo $telephone;?>" placeholder="<?php echo _('Your telephone number');?>" /></li>
				<li><label><?php echo _("Website");?> </label><input type='text' name='website' value="<?php echo $website;?>" placeholder="<?php echo _('Your personal website URL');?>" /></li>
			</ul>
<?php		if(count($title_list_id)) {?>
			<p>
			<label><?php echo _('Title');?> </label>
			<select name='title' id='titleSelect'>
<?php			for($i=0;$i<count($title_list_id);++$i) {?>
				<option value="<?php echo $title_list_id[$i];?>" <?php if($title_id == $title_list_id[$i]) echo " selected='selected'";?> ><?php echo $title_list_title[$i];?></option>
<?php			}?>
			</select>
			</p>
<?php		}?>	
			<p>
				<label id='biographyLabel'><?php echo _('Biography');?></label>
				<textarea name='bio' class='bioTextarea' id='textEditor' placeholder="<?php echo _('Some words about your self');?>"><?php echo $result['bio'];?></textarea>
			</p>
			<input type='hidden' name='uid' value="<?php echo $uid;?>" />
			<input type='submit' value="<?php echo _('Submit');?>" />
			</fieldset>
		</form>
<?php		
		$c1 = can_change_user_type($logged_userid,$uid);
		$c2 = can_change_active_status($logged_userid,$uid);
		if( $c1 || $c2 ) {?>
			<form method='post' action='change_user_type.php'>
			<fieldset class='accountInformationWrapper'>
			<legend><?php echo _('Account');?></legend>
				<label> <?php echo _("Account type");?> </label>
<?php			if($c1) {?>
					<select name='user_type' id='userTypeSelect'>
<?php					for($i=0;$i<count($USER_TYPES);++$i) {?>
						<option value="<?php echo $i;?>" <?php if($i == $user_type) echo "selected='selected' ";?> ><?php echo $USER_TYPES_FULL[$i];?></option>
<?php					}?>
					</select>						
<?php			}?>
<?php			if($c2) {?>
				<select name='active_status' id='activeStatusSelect'>
<?php				for($i=0;$i<=1;++$i) {?>
					<option value="<?php echo $i;?>" <?php if($i == $is_active) echo "selected='selected'";?> ><?php echo (($i == 1)?_('Active'):_('Inactive'));?></option>
<?php				}?>
				</select>
<?php			}?>						
				<input type='hidden' name='uid' value="<?php echo $uid;?>" />
				<input type='submit' value="<?php echo _('Set');?>" />
			</fieldset>
			</form>
<?php		}?>	
			<script>
				//turn the textareas into rich text editors
				bkLib.onDomLoaded(function() {
					new nicEditor({buttonList : ['bold','italic','underline','left','center','right','ol','ul','fontSize','fontFamily','fontFormat','superscript','subscript','removeformat','strikethrough','link','unlink','striketrhough','forecolor','bgcolor','image','upload','xhtml'], xhtml : true}).panelInstance('textEditor');
				});
			</script>
<?php	}?>
</div>
