<h2> <?php echo _('Class');?> </h2>
<div class='allclasses'>
	<form class="classelection" action="new_class.php" method="post">
		<p><?php echo _('Insertion of the new class');?></p><br/>

		<label><?php echo _('The name of the class:');?>  <input type='text' name='classname' id='classname'/></label>
		<label><?php echo _('Semester:');?> <input type='text' name='classemester' id='classemester'/></label><br/><br/>
		<textarea name='classdesc' rows="30" cols="80"><?php echo _('Write some information about the class');?></textarea> 
		<input type="submit" value="Submit" />
	</form>
</div>
