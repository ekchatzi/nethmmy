<h2> <?php echo _('Edit Class Association Types');?> </h2>
<div class='editClassAssociationTypesWrapper'>
<?php
	include_once('../lib/access_rules.php');

	if(can_edit_class_association_types($logged_userid))
	{?>
		<fieldset>
		<legend><?php echo _('Existing association types');?></legend>
		<form action='edit_class_association_types.php' method='post'>
		<table class='associationTable'>
		<thead><?php echo _('Association Types');?></thead>
		<tbody>
			<tr><th><img class='deleteIcon' src='images/resource/trash_can.png' alt='X' title="<?php echo _('Delete?');?>"></th><th><?php echo _('Title');?></th><th><?php echo _('Priority');?></th><th><?php echo _('Permissions');?></tr>
<?php
		$query = "SELECT * FROM class_association_types";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$a = 0;
			while($row = mysql_fetch_array($ret))
			{
				$tid = $row['id'];
				echo "<tr";
				if($a%2)
					echo " class='alternateRow' ";
				echo ">";
				echo "<td><input type='hidden' value='$tid' name='id[]' />";
				echo "<input class='classAssociationTypeField' id='delete$a' type='checkbox' name='delete[]'/></td>";
				echo "<td><input class='classAssociationTypeField' id='title$a' type='text' name='title[]' value='".$row['title']."' /><input type='hidden' name='id[]' /></td>";
				echo "<td><input class='classAssociationTypeField' id='priority$a' type='text' value='".$row['priority']."' /></td>";
				echo "<td><input class='classAssociationTypeField' id='permissions$a' type='text' value='".$row['permissions']."' /></td></tr>";	
				++$a;			
			}		
		}
		else
		{
			echo "<tr><td colspan='4'>"._('No entries.')."</td></tr>";
		}
?>
		</tbody>
		</table>
		<input class='submit' type='submit' value="<?php echo _('Apply changes');?>" />
		</form>
		</fieldset>

		<fieldset>		
		<legend><?php echo _('New association type');?></legend>
		<form action='new_class_association_type.php' method='post'>
			<label><?php echo _('Title');?></label><input type='text' name='title' />
			<label><?php echo _('Priority');?></label><input type='text' name='priority' />
			<label><?php echo _('Permissions');?></label><input type='text' name='permissions' />
			<input class='submit' type='submit' value="<?php echo _('Submit');?>" />
		</form>
		</fieldset>
<?php	}
	else
	{?>
		<p class='error'><?php echo _('Access Denied.');?></p>
<?php	}?>
</div>
