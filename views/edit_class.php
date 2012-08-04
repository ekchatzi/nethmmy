<h2><?php echo _('Edit class');?></h2>
<div class='editClassWrapper'>
<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

        if(!isset($error))
                $error = '';

	$cid = isset($_GET['id'])?$_GET['id']:0;
	if(!($e = class_id_validation($cid)))
	{
		$query = "SELECT * FROM classes WHERE id='$cid' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$result = mysql_fetch_array($ret);
			if(can_edit_class($logged_userid,$cid) && can_view_class($logged_user,$uid))
			{?>
				<form method='post' action='edit_class.php'>
					<fieldset>
					<legend><?php echo _('Class information');?></legend>
					<ul>
						<li><label><?php echo _("Title");?> </label><input type='text' name='title' value="<?php echo $result['title'];?>" placeholder="<?php echo _('Class\' title');?>" /></li>
						<li><label><?php echo _("Semesters");?> </label><input type='text' name='semesters' value="<?php echo $result['semesters'];?>" placeholder="<?php echo _('Semesters the class is taught');?>"/></li>
					</ul>
					<p>
						<label id='descriptionLabel'><?php echo _('Description');?></label>
						<textarea name='description' class='descriptionTextarea' placeholder="<?php echo _('Class description');?>"><?php echo $result['description'];?></textarea>
					</p>
					<input type='hidden' name='cid' value="<?php echo $cid;?>" />
					<input type='submit' value="<?php echo _('Apply');?>" />
					</fieldset>
				</form>
<?php			}
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

	if($error)
	{?>
		<p class='error'><?php echo $error;?></p>
<?php	}?>
</div>
