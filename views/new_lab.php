<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$show = false;
	$class_link = _('some class');
	$cid = isset($_GET['id'])?$_GET['id']:0;
	if(!($e = class_id_validation($cid)))
	{
		if(can_create_lab($logged_userid,$cid))
		{
			$show = true;
			$query = "SELECT title FROM classes WHERE id='$cid'";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
				$class_link = "<a href='class/$cid/'>".mysql_result($ret,0,0)."</a>";
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
<h2> <?php echo _('New Lab');?> </h2>
<div class='newLabWrapper'>
<p class='hierarchyNavigationRow'><?php echo $class_link . " > " . _('New Lab');?></p>
<?php	if($show) {?>
		<script type='text/javascript'>
			$(document).ready(function(){
				$('#dateField1').AnyTime_picker( { format: "%Y-%m-%d %H:%i", labelTitle: "<?php echo _('Registration Deadline');?>", labelHour: "<?php echo _('Hour');?>", labelMinute: "<?php echo _('Minute');?>",labelMonth: "<?php echo _('Month');?>",labelDayOfMonth: "<?php echo _('Day of the Month');?>",labelYear: "<?php echo _('Year');?>" } );
				$('#dateField2').AnyTime_picker( { format: "%Y-%m-%d %H:%i", labelTitle: "<?php echo _('Registration Deadline');?>", labelHour: "<?php echo _('Hour');?>", labelMinute: "<?php echo _('Minute');?>",labelMonth: "<?php echo _('Month');?>",labelDayOfMonth: "<?php echo _('Day of the Month');?>",labelYear: "<?php echo _('Year');?>" } );
				$('#canUploadCheckbox').click( function() {
					if($(this).attr('checked'))
						$('.uploadSettings').show('fast');
					else
						$('.uploadSettings').hide('fast');							
				}).attr('checked',false);		
			});
			function onsubmission() {
				var converter = new AnyTime.Converter();
				$('#dateField1').val( converter.parse($('#dateField1').val()).getTime()/1000 );
				$('#dateField2').val( converter.parse($('#dateField2').val()).getTime()/1000 );
				return true;
			}
		</script>
		<form class="new_lab" action="new_lab.php" method="post" onsubmit="return onsubmission();" >
			<fieldset>
				<legend><?php echo _('Lab information and settings');?></legend>		
				<label><?php echo _('Title');?> </label>
				<input type='text' name='title' id='labTitleField' placeholder="<?php echo _('Lab title');?>" />
				<label><?php echo _('Description');?> </label>
				<textarea name='description' class='labDescriptionField' id='textEditor' placeholder="<?php echo _('Write a description for the lab');?>" ></textarea>
				<label><?php echo _('Team limit');?> </label>
				<input type='text' name='team_limit' placeholder="<?php echo _('How many teams the lab can have');?>" />
				<label><?php echo _('Team size limit');?> </label>
				<input type='text' name='users_per_team_limit' placeholder="<?php echo _('How many users any lab team can have');?>" />
				<label><?php echo _('Registration deadline');?> </label>
				<input type="text" name='register_expire' id='dateField1' placeholder="<?php echo _('Date registrations close');?>" />
				<br /><input type='checkbox' name='can_make_new_teams' value='1'><label class='checkboxLabel'><?php echo _('Users can create teams');?> </label>
				<br /><input type='checkbox' name='can_lock_teams' value='1'><label class='checkboxLabel'><?php echo _('Users can lock their teams');?> </label>
				<br /><input type='checkbox' name='can_upload' id='canUploadCheckbox' value='1'><label class='checkboxLabel'><?php echo _('Users can upload files');?> </label>
				<div class='uploadSettings'>
					<label><?php echo _('Upload limit');?> </label>
					<input type='text' name='upload_limit' value='0' placeholder="<?php echo _('How many files lab teams can upload');?>" />
					<label><?php echo _('Upload deadline');?> </label>
					<input type="text" name='upload_expire' value='0' id='dateField2' placeholder="<?php echo _('Date file uploads close');?>" />
				</div>
				<input type="hidden" name='class' value="<?php echo $cid;?>" /><br />
				<input type="submit" value="<?php echo _('Create Lab');?>" />
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
