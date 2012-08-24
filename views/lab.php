<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error))
                $error = '';

	$show = false;
	$lid = isset($_GET['id'])?$_GET['id']:0;
	$lab_name = _('Lab');
	$class_link = _('Some class');
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
				$class_name = _('Class');
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

				$id = array();
				$query = "SELECT * FROM labs_teams WHERE lab='$lid' LIMIT 1";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$result = mysql_fetch_array($ret);
					$id_t = $id[] = $result['id'];
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
<h2><?php echo $lab_name;?></a></h2>
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
				<a class='editLink' href="edit_lab/<?php echo $lid;?>/"' ><img src='images/resource/edit-pencil.gif' class='icon editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
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
			<h3><?php echo _('Teams');?></h3>
			<div class='existingTeamsWrapper'>
<?php			for($i=0;$i<count($id);++$i){?>

<?php			}?>
<?php			if(count($id) == 0) {?>
				<p><?php echo _('No teams.');?></p>
<?php			}?>
			</div>
<?php			if(can_create_lab_team($logged_userid,$lid)){?>
				<a href="javascript:void(0)" id='createLabTeamLink'><?php echo _('Create new team');?></a>
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
<?php			if(can_create_lab_teams_bulk($logged_userid,$lid)){?>
				<form action='new_lab_teams_bulk.php'>
					<p><?php echo sprintf(_('Create %s new teams'),"<input class='countInput' name='count' value='1'/>");?><input type='submit' value="<?php echo _('Go');?>" />
				</form> 
<?php			};?>
		</div>
<?php		};?>
<?php	}?>
</div>
