<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error))
                $error = '';

	$show = false;
	$delete = false;
	$kick = false;
	$edit_team = false;
	$lid = isset($_GET['id'])?$_GET['id']:0;
	$lab_name = _('Lab');
	$class_link = _('Some class');
	$team_limit = '';
	$team_size_limit = '';
	$upload_limit = '';
	if(!($e = lab_id_validation($lid)))
	{
		$query = "SELECT * FROM labs WHERE id='$lid' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{

			if(can_view_lab($logged_userid,$lid))
			{
				$result = mysql_fetch_array($ret);
				$show = true;
				$class = $result['class'];
				$lab_name = $result['title'];
				$upload_expire = $result['upload_expire'];
				$team_limit = $result['team_limit'];
				$team_size_limit = $result['users_per_team_limit'];
				$upload_limit = $result['upload_limit'];
				$class_name = _('Class');
				$folder = $result['folder'];
				$query = "SELECT title FROM classes WHERE id='$class'";
				$ret2 = mysql_query($query);
				if($ret2 && mysql_num_rows($ret2))
				{
					$class_name = mysql_result($ret2,0,0);
				}

				$description = ((strlen($result['description'])>0)?$result['description']:_('There is no description yet.'));
				$register_expire_message = sprintf(_('Registrations close on %s.'),strftime($DATE_FORMAT,$result['register_expire']));
				$upload_expire_message = ($result['folder'])?sprintf(_('File uploads deadline on %s.'),strftime($DATE_FORMAT,$result['upload_expire'])):'';
				$class_link = "<a href='class/$class/'>$class_name</a>";
				$lab_info_message = sprintf(_('Created on %s for %s and updated on %s.'),strftime($DATE_FORMAT,$result['creation_time']),$class_link,strftime($DATE_FORMAT,$result['update_time']));

				$current_team = '';

				$id = array();
				$team_name = array();
				$team_info = array();
				$students = array();
				$students_count = array();
				$files = array();
				$files_count = array();
				$can_view_files = array();
				$query = "SELECT * FROM lab_teams WHERE lab='$lid'";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					while($row = mysql_fetch_array($ret))
					{
						//Basic info
						$id_t = $id[] = $row['id'];
						$team_name[] = $row['title'];
						//Info						
						$team_info_t = sprintf(_('Created on %s.'),strftime($DATE_FORMAT,$row['creation_time']));
						if($row['update_time'] > $row['creation_time'])
							$team_info_t .= sprintf(_('Last updated on %s.'),strftime($DATE_FORMAT,$row['update_time']));
						$team_info_t .= sprintf(_("Team is %s."),($row['is_locked'])?_("Locked"):_("Not Locked"));
						$team_info[] = $team_info_t;

						//Students
						$students_t = _('No students.');
						$students_count_t = 0;		
						if(!($e = id_list_validation($row['students'])))
						{
							$query = "SELECT id,first_name,last_name,aem FROM users WHERE id IN(".$row['students'].") ORDER BY last_name ASC";
							$ret2 = mysql_query($query);			
							if($ret2 && mysql_num_rows($ret2))
							{
								$student_links = array();
								while($row2 = mysql_fetch_array($ret2))
								{
									$uid = $row2['id'];
									$uname = $row2['first_name'] . " " . $row2['last_name'];
									if($aem = $row2['aem'])
										$uname .= " [$aem]";
									$s = "<span class='user' id='user$uid'>";
									$s .= can_view_profile($logged_userid,$uid)?"<a href='profile/$uid/'>$uname</a>":"<span>$uname</span>";
									if(can_kick_from_lab_team($logged_userid,$uid,$id_t))
									{
										$s .= "<a href='javascript:void(0)' class='kickLink' id='kickLink$uid'><img src='images/resource/trash_can.png' class='icon deleteIcon' id='deleteIcon$uid' alt='"._('Kick')."' title='"._('Kick')."' /></a>";
										$kick = true;
									}
									$s .= "</span>";
									$student_links[] = $s;
									if($uid == $logged_userid)
										$current_team = $id_t;
								}
								if($students_count_t = count($student_links))
									$students_t = implode(' ',$student_links);
							}
						}
						else
						{
							$error .= _("Invalid student list.") . " : " . $e;
						}
						$students[] = $students_t;
						$students_count[] = $students_count_t;

						//Files
						$can_view_files_t = $can_view_files[] = (can_view_lab_team_files($logged_userid,$id_t) && $folder);
						if($can_view_files_t)
						{
							$files_t = _("No files.");
							$files_count_t = 0;
							if(!($e = id_list_validation($row['files'])))
							{
								$query = "SELECT id,name,full_path FROM files WHERE id IN(".$row['files'].") ORDER BY name ASC";
								$ret2 = mysql_query($query);						
								if($ret2 && mysql_num_rows($ret2))
								{
									$file_links = array();
									while($row2 = mysql_fetch_array($ret2))
									{
										$fid = $row2['id'];
										$fname = $row2['name'];
										$f = "<span class='file' id='file$fid'>";
										$file_extension_t = strtolower(substr(strrchr($row2['full_path'],"."),1));
										$icon = file_exists("images/resource/filetype_icons/$file_extension_t.png")?"images/resource/filetype_icons/$file_extension_t.png":"images/resource/filetype_icons/default.png";
										if(can_download_file($logged_userid,$fid))
											$f .= "<a href='download_file.php?fid=$fid'><img src='$icon' title='".sprintf(_('Download %s'),$fname)."' alt='"._('download')."' class='filetypeIcon' />$fname</a>";
										if(can_edit_file($logged_userid,$fid))
										{
											$f .= "<a class='deleteLinkFiles' id='deleteLinkFiles$fid' href='javascript:void(0)'><img src='images/resource/trash_can.png' class='icon deleteIcon' id='deleteIcon$fid' alt='"._('Delete')."' title='"._('Delete')."' /></a>";
											$delete = true;
										}
										$f .= "</span>";
										$file_links[] = $f;
									}
									if($files_count_t = count($file_links))		
										$files_t = implode(' ',$file_links);
								}
							}
							else
							{
								$error .= _("Invalid file list.") . " : " . $e;
							}
							$files[] = $files_t;
							$files_count[] = $files_count_t;
						}
					}				
				}
			}
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
?>
<h2><?php echo $lab_name;?></h2>
<p class='hierarchyNavigationRow'><?php echo $class_link . " > " . _('Labs/Assignements') . " > " .$lab_name;?></p>
<div class='labWrapper'>
<?php	if($show) {?>
		<p class='labInfo'><?php echo $lab_info_message;?></p>
		<p class='labInfo'><?php echo $register_expire_message.$upload_expire_message;?></p>
		<fieldset class='labDescriptionWrapper'>
			<legend><?php echo _('Description');?></legend>
		<p><?php echo $description?></p>
		</fieldset>
<?php		
		$c1 = can_edit_lab($logged_userid,$lid);
		$c2 = can_delete_lab($logged_userid,$lid);
		if($c1 || $c2) {?>
			<div class='editOptionsWrapper'>
<?php			if($c1) {?>
				<a href="edit_lab/<?php echo $lid;?>/" ><img src='images/resource/edit-pencil.gif' class='icon editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
<?php			};?>
<?php			if($c2) {?>
				<a class='deleteLink' href='javascript:void(0)'><img src='images/resource/trash_can.png' class='icon deleteIcon' alt="<?php echo _('Delete');?>" title="<?php echo _('Delete');?>" /></a>
			<script type='text/javascript'>
				$(document).ready(function(){
					$('.deleteLink').click(function(){
						if (confirm("<?php echo _('Are you sure you want to delete this lab?');?>")) {
							var s = "<form style='display:none' action='delete_lab.php' method='post'>";
							s += "<input type='hidden' name='lid' value='"+<?php echo $lid;?>+"' />";
							s += '</form>';
							var form = $(s).appendTo('body');
							form.submit(); 	
						}
					});
				});
			</script>
<?php			};?>
			</div>			
<?php		};?>
<?php		if(can_view_lab_teams($logged_userid,$lid)) {?>
		<div class='teamsWrapper'>
			<h3><?php echo _('Teams');?><?if($team_limit) echo "(".count($id)."/$team_limit)";?>
<?php			if(can_create_lab_team($logged_userid,$lid)){?>
				<a href="javascript:void(0)" id='createLabTeamLink'><img src='images/resource/add.png' class='icon addIcon addTeamIcon' alt="<?php echo _('Add');?>" title="<?php echo _('Create new team');?>" /></a>
				<script type='text/javascript'>
				$(document).ready(function(){
					$('#createLabTeamLink').click(function(){
							var s = "<form style='display:none' action='new_lab_team.php' method='post'>";
							s += "<input type='hidden' name='lid' value='"+<?php echo $lid;?>+"' />";
							s += '</form>';
							var form = $(s).appendTo('body');
							form.submit(); 	
					});
				});
				</script>	
<?php			};?>
			</h3>
<?php			if($current_team){?>
				<a href="lab/<?php echo $lid;?>/#labTeamContainer<?php echo $current_team;?>" ><?php echo _("Go to my team");?></a>
<?php			}?>
			<div class='existingTeamsWrapper'>
<?php			$a = 0;
			for($i=0;$i<count($id);++$i){
				if(can_view_lab_team($logged_userid,$id[$i])){?>
					<div class='labTeamContainer <?php if($a%2) echo "alternateLabTeamContainer";?>' id="labTeamContainer<?php echo $id[$i];?>">
						<div class='labTeamNameContainer' id="labTeamNameContainer<?php echo $id[$i];?>">
						<div class='labTeamName'><?php echo $team_name[$i];?> 
<?php						if(can_edit_lab_team($logged_userid,$id[$i])){ $edit_team = true;?>
							<a class='editLink' id="editLink<?php echo $id[$i];?>" href='javascript:void(0)' ><img src='images/resource/edit-pencil.gif' class='icon editIcon editLabIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
							<div class='editLabTeamPrompt' id="editLabTeamPrompt<?php echo $id[$i];?>">
								<form action='edit_lab_team.php' method='post'>
									<input type='hidden' name='tid' value="<?php echo $id[$i];?>" />
									<label><?php echo _('New Name');?></label>
									<input type='text' name='name' value="<?php echo $team_name[$i];?>" placeholder="<?php echo _('New name here...');?>" /> 
									<input type='submit' value="<?php echo _('Submit');?>" />
									<button type='button' class='cancelButton' onclick='javascript:void(0)'><?php echo _('Cancel');?></button>
								</form>
							</div>
<?php						}?>
						</div>
						</div>
						<p class='labInfo'><?php echo $team_info[$i];?></p>
						<label>Students<?php if($team_size_limit) echo "(".$students_count[$i]."/$team_size_limit)";?></label><p class='labTeamStudents' id="labTeamStudents<?php echo $id[$i];?>"><?php echo $students[$i];?></p>
<?php						if($can_view_files[$i]){?>
						<label>Files<?php if($upload_limit) echo "(".$files_count[$i]."/$upload_limit)";?></label><p class='labTeamFiles' id="labTeamFiles<?php echo $id[$i];?>"><?php echo $files[$i];?></p>
<?php							if(can_upload_lab_team_file($logged_userid,$id[$i])){?>
								<div class='newFileWrapper'>
									<fieldset>
										<legend><?php echo _('Upload file');?></legend>
										<form action='upload_lab_team_file.php' method='post'  enctype="multipart/form-data" >
											<label><?php echo _('Name');?></label>
											<input type='text' name='name' placeholder="<?php echo _('(Optional) File name here...');?>" />
											<label><?php echo _('File');?></label>
											<input type='file' name='file' />
											<input type='hidden' name='tid' value="<?php echo $id[$i];?>" />
											<input type='hidden' name='lab' value="<?php echo $lid;?>" />
											<input type='submit' value="<?php echo _('Upload file');?>" />
										</form>
									</fieldset>
								</div>
<?php							}?>			
<?php						}?>
<?php						$c1 = can_join_lab_team($logged_userid,$id[$i]);
						$c2 = can_leave_lab_team($logged_userid,$id[$i]);
						$c3 = can_delete_lab_team($logged_userid,$id[$i]);
						if($c1 || $c2){?>
							<div class='joinLabTeamContainer'>
<?php							if($c1){?>
								<p><a href="join_lab_team.php?tid=<?php echo $id[$i];?>" ><img src='images/resource/add.png' class='icon addIcon' alt="<?php echo _('Add');?>" title="<?php echo _('Join team');?>" /><?php echo _('Join');?></a></p>
<?php							}?>
<?php							if($c2){?>
								<p><a href="leave_lab_team.php?tid=<?php echo $id[$i];?>" ><img src='images/resource/substract.png' class='icon minusIcon' alt="<?php echo _('Leave');?>" title="<?php echo _('Leave team');?>" /><?php echo _('Leave');?></a></p>
<?php							}?>
<?php							if($c3){?>
								<p><a href="delete_lab_team.php?tid=<?php echo $id[$i];?>" ><img src='images/resource/trash_can.png' class='icon deleteIcon' alt="<?php echo _('Delete');?>" title="<?php echo _('Delete team');?>" /><?php echo _('Delete');?></a></p>
<?php							}?>
							</div>
<?php						}?>
					</div>
<?php					$a++;
				}?>
<?php			}?>
<?php			if((count($id) == 0) || $a == 0) {?>
				<p><?php echo _('No teams.');?></p>
<?php			}?>

			<script type='text/javascript'>
			$(document).ready(function(){
<?php			if($kick){?>
					$('.kickLink').click(function(){
						if (confirm("<?php echo _('Are you sure you want to kick this student?');?>")) {
							var id = $(this).attr('id').replace('kickLink','');
							var teamId = $(this).parents('.labTeamContainer').attr('id').replace('labTeamContainer','');
							var lab = "<?php echo $lid;?>";
							$.post("kick_from_lab_team.php?AJAX",{ 'uid' : id,'tid' : teamId},function(data){
								var ob = $.parseJSON(data);
								if(ob.error) {
									alert(ob.error);
								} else {
									if($('#labTeamStudents'+teamId).find('.user').size() == 1)
									{
										$('#labTeamStudents'+teamId).html("<?php echo _('No students.');?>");
										window.location.reload();
									}									
									$('#labTeamStudents'+teamId +' #user'+id).remove();
								}
							});
						}
					});
<?php			}?>
<?php			if($edit_team){?>
					$('.editLink').click(function(){
						var id = $(this).attr('id').replace('editLink','');
						$('.editLabTeamPrompt').css('display','none');
						$('.labTeamNameContainer').removeClass('labTeamNameContainerEditted');

						$('#editLabTeamPrompt'+id).css('display','block');
						$('#labTeamNameContainer'+id).addClass('labTeamNameContainerEditted');
					});
					$('.cancelButton').click(function(){
						$('.editLabTeamPrompt').css('display','none');
						$('.labTeamNameContainer').removeClass('labTeamNameContainerEditted');
					});
<?php			}?>
<?php			if($delete){?>
				$('.deleteLinkFiles').click(function(){
					if (confirm("<?php echo _('Are you sure you want to delete this file?');?>")) {
						var id = $(this).attr('id').replace('deleteLinkFiles','');
						var folder = "<?php echo $folder;?>";
						var labID = "<?php echo $lid;?>";
						var teamId = $(this).parents('.labTeamContainer').attr('id').replace('labTeamContainer','');
						$.post("delete_files.php?AJAX",{ 'fid[]' : [id],'folder' : folder,'lab' : labID},function(data){
							var ob = $.parseJSON(data);
							if(ob.error) {
								alert(ob.error);
							} else {
								if($('#labTeamFiles'+teamId).find('.file').size() == 1)
									$('#labTeamFiles'+teamId).html("<?php echo _('No files.');?>");
								$('#labTeamFiles'+teamId + ' #file'+id).remove();
							}
						});
					}
				});
<?php			};?>
			});
			</script>
			</div>
<?php			if(can_create_lab_teams_bulk($logged_userid,$lid)){?>
				<form action='new_lab_teams_bulk.php' method='post'>
				<fieldset>
					<legend><?php echo _('Bulk create teams');?></legend>
					<p><?php echo sprintf(_('Create %s new teams'),"<input class='countInput' name='count' value='1'/>");?>
						<input type='hidden' name='lid' value="<?php echo $lid;?>" />
						<input type='submit' value="<?php echo _('Go');?>" />
					</p>
				</fieldset>
				</form> 
<?php			};?>
		</div>
<?php		};?>
<?php	}?>
</div>
