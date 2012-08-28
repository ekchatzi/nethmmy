<?php	
	include_once('../lib/access_rules.php');

	$show = false;
	$classes_link = _('Classes');
	if(can_view_classes_list($logged_userid))
		$classes_link = "<a href='classes/'>$classes_link</a>";
	if(can_create_class($logged_userid))
	{
		$show = true;
	}
	else
	{
		$error .= _('Access Denied.');
	}
?>
<h2> <?php echo _('New Class');?> </h2>
<p class='hierarchyNavigationRow'><?php echo $classes_link . " > " . _('New class');?></p>
<div class='newClassWrapper'>
<?php	if($show) {?>
	<form class="new_class" action="new_class.php" method="post">
		<fieldset>
			<legend><?php echo _('Class Information');?></legend>		
			<label><?php echo _('Title');?> </label>
			<input type='text' name='title' id='classTitleField' placeholder="<?php echo _('Class name');?>" />
			<label><?php echo _('Semesters');?> </label>
			<input type='text' name='semesters' id='semestersField' placeholder="<?php echo _('The semesters the class is taught');?>" />
			<label><?php echo _('Description');?> </label>
			<textarea name='description' id='classDescriptionField' placeholder="<?php echo _('Write a description for the class');?>" ></textarea> 
			<input type="submit" value="<?php echo _('Add');?>" />
		</fieldset>
	</form>
<?php	}?>
</div>
