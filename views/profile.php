<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
       
	if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$show = false;
	$user_name = _('Some user');
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
			if(can_view_profile($logged_userid,$uid))
			{
				$show = true;
				$user_type = $result['user_type'];
				$account_type_text = $USER_TYPES_FULL[$result['user_type']];
				$first_name = $result['first_name'];
				$last_name = $result['last_name'];
				$user_name = $first_name . ' ' . $last_name;
				$uid_type = $result['user_type'];
				$aem = $result['aem'];
				$semester = $result['semester'];
				$semester_update = $result['semester_update_time'];
				$email = $result['email'];
				$telephone = ($result['telephone'])?$result['telephone']:_('Not available');
				$website = ($result['website'])?$result['website']:_('Not available');
				$title_id = $result['title'];
				$is_active = $result['is_active'];
				$is_email_validated = $result['is_email_validated'];
				/* Title */
				$title_id = $result['title'];
				$title = _('User');
				$title_description = _('Common user');
				if(!$e = title_id_validation($title_id))
				{
					$query = "SELECT title,description FROM titles WHERE id='$title_id'";
					$ret2 = mysql_query($query);
					if($ret2 && mysql_num_rows($ret))
					{
						$result2 = mysql_fetch_array($ret2);
						$title = $result2['title'];
						$title_description = $result2['description'];
					}
					else
					{
						$error[] = _('Database Error.');			
					}				
				}

				$uid_type = $result['user_type'];
				/* Associated classes */
				$class_associations = array();
				$query = "SELECT class_associations.class AS class,
								class_association_types.title AS title
								FROM class_associations,class_association_types WHERE class_associations.user='$uid' AND class_association_types.id = class_associations.type";
				$ret2 = mysql_query($query);
				if($ret2 && mysql_num_rows($ret2))
				{
					while( $row = mysql_fetch_array($ret2))
					{
						$cid = $row['class'];
						$association_title = $row['title'];
				
						$query = "SELECT id,title FROM classes 
								WHERE id = '$cid'";
						$ret3 = mysql_query($query);
						if($ret3 && mysql_num_rows($ret3))
						{
							while( $row = mysql_fetch_array($ret3) )
							{
								$cid = $row['id'];
								$class_title = $row['title'];							
								$class_associations[] = "<a href='class/$cid/'>$class_title</a> - $association_title";
							}			
						} 
					}
				}
				$account_type_text = $USER_TYPES_FULL[$result['user_type']];				
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
<h2><?php echo $user_name;?></h2>
<p class='hierarchyNavigationRow'><?php echo $users_link . " > " . $user_name;?></p>
<div class='profileWrapper'>
<?php	if($show) {?>
	<p><?php echo $title;?></p>
<?php		if(!($e = user_type_validation($uid_type)) && $USER_TYPES[$uid_type] == 's') {?>
			<p><label><?php echo _("ΑΕΜ");?>:</label>&nbsp;<?php echo $aem;?></p>
			<p><label><?php echo _("Semester");?>:</label>&nbsp;<?php echo ($semester + floor((time() - $semester_update)/(60*60*24*30*6)));?></p>
<?php		}?>
<?php		if(can_view_contact_information($logged_userid,$uid)) {?>
			<fieldset class='contactInfoWrapper'>
				<legend><?php echo _('Contact Info');?></legend>
				<ul>
					<li><label><?php echo _("Email");?>: </label><?php echo $email;?>
							   <?php if ($is_email_validated) {?>
							   <img alt='valid' src='images/resource/checkIcon.png' height='15px' width='15px'/>
							   <?php }?>
							   <?php if (!$is_email_validated) {?>
							   <img id='invalidIcon' alt='invalid' src='images/resource/crossIcon.png' height='15px' width='15px'/>
<?php 						   			if (can_send_validation_email($logged_userid, $logged_userid)) {?>							   
											<a id='validateHref' href='javascript:void(0)'>Send validation email</a>
											<script>
												$(document).ready(function() {
													$('#validateHref').click(function(){
														$.ajax({
														   type: "POST",
														   url: "../public_html/request_validate_email.php?AJAX",
														   data: 'uid='+ <?php echo $logged_userid;?>,
														   cache: false,
														   success: function(response) {
																//works only when email server is set up correctly
																var ob = $.parseJSON(response);
																if(ob.error!='') {
																	alert(ob.error);
																}
																else {
																	alert('<?php echo _('Email sent! Please check your inbox and spam folder.');?>');
																}
															}
														});
													});
												});
											</script>
<?php 									}?>
<?php								}?>
								 
					</li>
					<li><label><?php echo _("Telephone");?>: </label><?php echo $telephone;?></li>
					<li><label><?php echo _("Website");?>: </label><?php echo $website;?></li>
				</ul>
			</fieldset>
<?php		}?>
		<fieldset class='bioWrapper'>
			<legend><?php echo _('Biography');?></legend>
			<p><?php echo ((strlen($result['bio'])>0)?$result['bio']:_('There is no biography yet.'))?></p>
		</fieldset>
<?php		if(can_view_user_associations($logged_userid,$uid)) {?>
			<fieldset class='associatedClassesWrapper'>
				<legend><?php echo _('Associated classes');?></legend>
<?php			if(count($class_associations)) {?>
				<ul class='associatedClassesList'>
<?php				for($i=0;$i<count($class_associations);++$i) {?>
					<li><?php echo $class_associations[$i];?></li>
<?php				}?>
				</ul>
<?php			} else {?>
				<p><?php echo _('None');?></p>
<?php			}?>
			</fieldset>
<?php		}?>
<?php		if(can_view_account_information($logged_userid,$uid)) {?>
			<fieldset class='accountInformationWrapper'>
				<legend><?php echo _('Account Information');?></legend>
				<ul>
					<li><label> <?php echo _("Username");?>: </label><?php echo $result['username'];?></li>
					<li><label> <?php echo _("User ID");?>: </label><?php echo $result['id'];?></li>
					<li><label> <?php echo _("Account type");?>: </label><?php echo $account_type_text." - ".($result['is_active']?'Active':'Inactive');?></li>
					<li><label> <?php echo _("Registered on");?> </label><?php echo strftime($DATE_FORMAT,$result['registration_time']);?></li>
					<li><label> <?php echo _("Last Login on");?> </label><?php echo strftime($DATE_FORMAT,$result['last_login']);?></li>
					<li><label> <?php echo _("From");?> </label><?php echo ($result['last_remote_adress'])?$result['last_remote_adress']:_('Not available');?></li>
				</ul>
			</fieldset>
<?php		}?>
<?php		
		$c1 = can_edit_account($logged_userid,$uid);
		$c2 = can_delete_account($logged_userid,$uid);
		if($c1 || $c2) {?>
			<div class='editOptionsWrapper'>
<?php			if($c1) {?>
				<a class='editLink' href="edit_profile/<?php echo $uid;?>/"' ><img src='images/resource/edit-pencil.gif' class='icon editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
<?php			};?>
<?php			if($c2) {?>
				<a class='deleteLink' href='javascript:void(0)'><img src='images/resource/trash_can.png' class='icon deleteIcon' id="deleteIcon" alt="<?php echo _('Delete');?>" title="<?php echo _('Delete');?>" /></a>
			<script type='text/javascript'>
				$(document).ready(function(){
					$('.deleteLink').click(function(){
						if (confirm("<?php echo _('Are you sure you want to delete this user account?');?>")) {
							var id = $(this).attr('id').replace('deleteLink','');
							var s = "<form style='display:none' action='delete_user.php' method='post'>";
							s += "<input type='hidden' name='uid' value='"+<?php echo $uid;?>+"' />";
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
