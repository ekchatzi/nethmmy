<h2> <?php echo _('New Lab');?> </h2>
<div class='newLabWrapper'>
<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

	$allowed = false;
        if(!isset($error))
                $error = '';

	$cid = isset($_GET['id'])?$_GET['id']:0;
	if(!($e = class_id_validation($cid)))
	{
		if(can_create_lab($logged_userid,$cid))
		{?>
			<script type='text/javascript'>
				$(document).ready(function(){
					$('#dateField1').AnyTime_picker( { format: "%Y-%m-%d %H:%i", labelTitle: "<?php echo _('Registration Deadline');?>", labelHour: "<?php echo _('Hour');?>", labelMinute: "<?php echo _('Minute');?>",labelMonth: "<?php echo _('Month');?>",labelDayOfMonth: "<?php echo _('Day of the Month');?>",labelYear: "<?php echo _('Year');?>" } );
					$('#dateField2').AnyTime_picker( { format: "%Y-%m-%d %H:%i", labelTitle: "<?php echo _('Registration Deadline');?>", labelHour: "<?php echo _('Hour');?>", labelMinute: "<?php echo _('Minute');?>",labelMonth: "<?php echo _('Month');?>",labelDayOfMonth: "<?php echo _('Day of the Month');?>",labelYear: "<?php echo _('Year');?>" } );
					$('#canUploadCheckbox').click( function() {
						if($(this).attr('checked'))
							$('.uploadSettings').css('display','block');
						else
							$('.uploadSettings').css('display','none');							
					}).attr('checked',false);		
				});
				function onsubmission() {
					var converter = new AnyTime.Converter();
					$('#dateField1').val( converter.parse($('#dateField1').val()).getTime()/1000 );
					$('#dateField2').val( converter.parse($('#dateField2').val()).getTime()/1000 );
					return false;
				}
			</script>
			<form class="new_lab" action="new_lab.php" method="post" onsubmit="return onsubmission()" >
				<fieldset>
					<legend><?php echo _('Lab information and settings');?></legend>		
					<label><?php echo _('Title');?> </label>
					<input type='text' name='title' id='labTitleField' placeholder="<?php echo _('Lab title');?>" />
					<label><?php echo _('Description');?> </label>
					<textarea name='description' id='labDescriptionField' placeholder="<?php echo _('Write a description for the lab');?>" ></textarea>
					<label><?php echo _('Team limit');?> </label>
					<input type='text' name='team_limit' placeholder="<?php echo _('How many teams the lab can have');?>" />
					<label><?php echo _('Team size limit');?> </label>
					<input type='text' name='users_per_team_limit' placeholder="<?php echo _('How many users any lab team can have');?>" />
					<label><?php echo _('Registration deadline');?> </label>
					<input type="text" name='register_expire' id='dateField1' placeholder="<?php echo _('Date registrations close');?>" />					
					<br /><input type='checkbox' name='can_free_join' value='1'><label class='checkboxLabel'><?php echo _('Free join');?> </label>
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
<?php		}
		else
		{
			$error .= _('Access Denied.');
		}
	}
	else
	{
		$error .= $e;
	}
?>
</div>
