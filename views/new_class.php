<h2> <?php echo _('New Class');?> </h2>
<div class='allclasses'>
	<form class="new_class" action="public_html/new_class.php" method="post">
		<label><?php echo _('The name of the class:');?>  <input type='text' name='title' id='title'/></label>
		<label><?php echo _('Semester:');?> <input type='text' name='semester' id='semester'/></label><br/><br/>
		<textarea name='description' rows="30" cols="80"><?php echo _('Write some information about the class');?></textarea> 
		<input type="submit" value="<?php echo _('Add');?>" />
	</form>
</div>
