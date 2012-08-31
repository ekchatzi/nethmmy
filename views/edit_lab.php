<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$show = false;
	$class_link = _('Some class');
	$lab_link = _('Some Lab');
	$lid = isset($_GET['id'])?$_GET['id']:0;
	if(!($e = lab_id_validation($lid)))
	{
		if(can_edit_lab($logged_userid,$lid))
		{
			$query = "SELECT * FROM labs WHERE id='$lid'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$show = true;
				$result = mysql_fetch_array($ret);
				$title = $result['title'];
				$lab_link = "<a href='lab/$lid/' >$title</a>";
				$description = $result['description'];
				$register_expire = $result['register_expire'];
				$upload_expire = $result['upload_expire'];
				$folder = $result['folder'];
				$can_lock_teams = $result['can_lock_teams'];
				$upload_limit = $result['upload_limit'];
				$can_make_new_teams = $result['can_make_new_teams'];
				$team_limit = $result['team_limit'];
				$team_size_limit = $result['users_per_team_limit'];
				$class = $result['class'];
				$query = "SELECT title FROM classes WHERE id='$class'";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					$class_link = mysql_result($ret,0,0);
					if(can_view_class($logged_userid,$class))
						$class_link = "<a href='class/$class/'>$class_link</a>";
				}
			}
			else
			{
				$error[] = _('Database Error');
			}
		}
		else
		{
			$error[] = _('Access Denied.');
		}
	}
	else
	{
		$error[] = $e;
	}
?>
<h2> <?php echo _('Edit Lab/assignment');?> </h2>
<p class='hierarchyNavigationRow'><?php echo $class_link . " > " . _('Labs/Assignements') . " > " .$lab_link . " > " . _('Edit Lab');?></p>
<div class='newLabWrapper'>
<?php	if($show) {?>
		<script type='text/javascript'>
			var converter = new AnyTime.Converter( { format: "%Y-%m-%d %H:%i" } );
			$(document).ready(function(){
				var upload_expire = new Date(<?php echo $upload_expire*1000;?>);
				var register_expire = new Date(<?php echo $register_expire*1000;?>);
				$('#dateField1').val( converter.format(register_expire) ).AnyTime_picker( { format: "%Y-%m-%d %H:%i", labelTitle: "<?php echo _('Registration Deadline');?>", labelHour: "<?php echo _('Hour');?>", labelMinute: "<?php echo _('Minute');?>",labelMonth: "<?php echo _('Month');?>",labelDayOfMonth: "<?php echo _('Day of the Month');?>",labelYear: "<?php echo _('Year');?>" } );
				$('#dateField2').val( converter.format(upload_expire) ).AnyTime_picker( { format: "%Y-%m-%d %H:%i", labelTitle: "<?php echo _('Registration Deadline');?>", labelHour: "<?php echo _('Hour');?>", labelMinute: "<?php echo _('Minute');?>",labelMonth: "<?php echo _('Month');?>",labelDayOfMonth: "<?php echo _('Day of the Month');?>",labelYear: "<?php echo _('Year');?>" } );

				var upload = "<?php echo $folder;?>";
				if(upload > 0) {
					$('.uploadSettings').css('display','block');
					$('#canUploadCheckbox').attr('checked','checked');
				} else {
					$('.uploadSettings').css('display','none');
					$('#canUploadCheckbox').attr('checked',false);
				}
				$('#canUploadCheckbox').click( function() {
					if($(this).attr('checked'))
						$('.uploadSettings').css('display','block');
					else
						$('.uploadSettings').css('display','none');							
				});		
			});
			function onsubmission() {
				$('#dateField1').val( converter.parse($('#dateField1').val()).getTime()/1000 );
				$('#dateField2').val( converter.parse($('#dateField2').val()).getTime()/1000 );
				return true;
			}
		</script>
		<form class="new_lab" action="edit_lab.php" method="post" onsubmit="return onsubmission()" >
			<fieldset>
				<legend><?php echo _('Lab information and settings');?></legend>		
				<label><?php echo _('Title');?> </label>
				<input type='text' name='title' id='labTitleField' placeholder="<?php echo _('Lab title');?>" value="<?php echo $title;?>"/>
				<label><?php echo _('Description');?> </label>
				<textarea name='description' class='labDescriptionField' id='textEditor' placeholder="<?php echo _('Write a description for the lab');?>" ><?php echo $description;?></textarea>
				<label><?php echo _('Team limit');?> </label>
				<input type='text' name='team_limit' placeholder="<?php echo _('How many teams the lab can have');?>" value="<?php echo $team_limit;?>" />
				<label><?php echo _('Team size limit');?> </label>
				<input type='text' name='users_per_team_limit' placeholder="<?php echo _('How many users any lab team can have');?>" value="<?php echo $team_size_limit;?>" />
				<label><?php echo _('Registration deadline');?> </label>
				<input type="text" name='register_expire' id='dateField1' placeholder="<?php echo _('Date registrations close');?>" />
				<br /><input type='checkbox' name='can_make_new_teams' value='1' <?php if($can_make_new_teams) echo "checked='checked'";?>><label class='checkboxLabel'><?php echo _('Users can create teams');?> </label>
				<br /><input type='checkbox' name='can_lock_teams' value='1' <?php if($can_lock_teams) echo "checked='checked'";?>><label class='checkboxLabel'  ><?php echo _('Users can lock their teams');?> </label>
				<br /><input type='checkbox' name='can_upload' id='canUploadCheckbox' value='1' <?php if($folder) echo "checked='checked'";?>><label class='checkboxLabel'><?php echo _('Users can upload files');?> </label>
				<div class='uploadSettings'>
					<label><?php echo _('Upload limit');?></label>
					<input type='text' name='upload_limit' placeholder="<?php echo _('How many files lab teams can upload');?>" value="<?php echo $upload_limit;?>" />
					<label><?php echo _('Upload deadline');?> </label>
					<input type="text" name='upload_expire' id='dateField2' placeholder="<?php echo _('Date file uploads close');?>" />
				</div>
				<input type="hidden" name='lab' value="<?php echo $lid;?>" /><br />
				<input type="submit" value="<?php echo _('Save changes');?>" />
			</fieldset>
		</form>
		<script>
			//turn the textareas into rich text editors
			bkLib.onDomLoaded(function() {
				new nicEditor({buttonList : ['bold','italic','underline','left','center','right','ol','ul','fontSize','fontFamily','fontFormat','superscript','subscript','removeformat','strikethrough','link','unlink','striketrhough','forecolor','bgcolor','image','upload','xhtml'], xhtml : true}).panelInstance('textEditor');
			});
		</script>
<?php	}?>
</div>
