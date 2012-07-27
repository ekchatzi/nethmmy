<h2> <?php echo _('New Class');?> </h2>
<div class='allclasses'>
	<form class="new_class" action="new_class.php" method="post">
		<label><?php echo _('Title');?> </label>
		<input type='text' name='title' id='classTitleField'/>
		<label><?php echo _('Semesters');?> </label>
		<input type='text' name='semesters' id='semestersField'/>
		<label><?php echo _('Description');?> </label>
		<textarea name='description' id='classDescriptionField' placeholder="<?php echo _('Write some information about the class');?>"></textarea> 
		<input type="submit" value="<?php echo _('Add');?>" />
	</form>
</div>
