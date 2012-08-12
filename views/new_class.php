<h2> <?php echo _('New Class');?> </h2>
<div class='newClassWrapper'>
<?php	
	include_once('../lib/access_rules.php');

	if(can_create_class($logged_userid))
	{?>
	<form class="new_class" action="new_class.php" method="post">
		<fieldset>
			<legend><?php echo _('Class Information');?></legend>		
			<label><?php echo _('Title');?> </label>
			<input type='text' name='title' id='classTitleField' placeholder="<?php echo _('Class name');?>" />
			<label><?php echo _('Semesters');?> </label>
			<input type='text' name='semesters' id='semestersField' placeholder="<?php echo _('The semesters the class is taught');?>" />
			<label><?php echo _('Description');?> </label>
			<textarea name='description' id='classDescriptionField' placeholder="<?php echo _('Write a description of the class');?>" ></textarea> 
			<input type="submit" value="<?php echo _('Add');?>" />
		</fieldset>
	</form>
<?php	}
	else
	{
		$error .= _('Access Denied.');
	}
	if($error)
	{?>
		<script>
		$(document).ready(function(){
			$('#notificationText').html("<?php echo $error;?>");
		});
		</script>
<?php	}?>
</div>
