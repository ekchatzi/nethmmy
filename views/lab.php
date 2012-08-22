<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error))
                $error = '';

	$show = false;
	$lid = isset($_GET['id'])?$_GET['id']:0;
	$lab_name = _('Lab');
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
				$upload_epire_message = ($result['folder'])?sprintf(_('File uploads deadline on %s.'),strftime($DATE_FORMAT,$result['upload_expire'])):'';
				$lab_info_message = sprintf(_('Created on %s for %s and updated on %s.'),strftime($DATE_FORMAT,$result['creation_time']),"<a href='class/$class/'>$class_name</a>",strftime($DATE_FORMAT,$result['update_time']));
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
				<a class='editLink' id="editLink<?php echo $id[$i];?>" href="edit_lab/<?php echo $lid;?>/"' ><img src='images/resource/edit-pencil.gif' class='icon editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
<?php			};?>
<?php			if($c2) {?>
				<a class='deleteLink' id="deleteLink<?php echo $id[$i];?>" href='javascript:void(0)'><img src='images/resource/trash_can.png' class='icon deleteIcon' id="deleteIcon<?php echo $id[$i];?>" alt="<?php echo _('Delete');?>" title="<?php echo _('Delete');?>" /></a>
			<script type='text/javascript'>
				$(document).ready(function(){
					$('.deleteLink').click(function(){
						if (confirm("<?php echo _('Are you sure you want to delete this lab?');?>")) {
							var id = $(this).attr('id').replace('deleteLink','');
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
		</div>
<?php		};?>
<?php	}?>
</div>
