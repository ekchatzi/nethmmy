<h2> <?php echo _('Edit Class Association Types');?> </h2>
<div class='editClassAssociationTypesWrapper'>
<?php
	include_once('../lib/access_rules.php');

	if(can_edit_class_association_types($logged_userid))
	{?>
		<fieldset>
		<legend><?php echo _('Class associations types');?></legend>
		<form action='edit_class_association_types.php' method='post' onsubmit='return check()'>
		<table class='associationTable'>
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
				echo "<tr id='associationRow$a'";
				if($a%2)
					echo " class='alternateRow' ";
				echo ">\n";
				echo "<td><input type='hidden' value='$tid' name='id[]' />";
				echo "<input class='classAssociationTypeField' id='delete$a' type='checkbox' name='delete[]' value='$tid' /></td>\n";
				echo "<td><input class='classAssociationTypeField' id='title$a' type='text' name='title[]' value='".$row['title']."' /></td>\n";
				echo "<td><input class='classAssociationTypeField' id='priority$a' type='text' name='priority[]' value='".$row['priority']."' /></td>\n";
				echo "<td><input class='classAssociationTypeField' id='permissions$a' type='text' name='permissions[]' value='".$row['permissions']."' /></td>\n</tr>\n";	
				++$a;			
			}		
		}
		else
		{
			echo "\n<tr>\n<td colspan='4'>"._('No entries.')."</td>\n</tr>\n";
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
<script type='text/javascript'>
var changes = new Array();
var a=<?php echo $a;?>;
for (var i=0;i<a;i++) {
	changes[i]=0;
}
$(document).ready(function() {
				$('.classAssociationTypeField').keyup(function() { 
					var tid = this.id;
					tid=tid.replace(/[a-z]+/,"");
					changes[tid]=1;
				});
				$(':checkbox').click(function() { 
					var tid = this.id;
					tid=tid.replace(/[a-z]+/,"");
					changes[tid]=1;
				});
});
								

function check() {
	//delete confirmation//
	var checked=$('input:checked').length;
	if (checked>0) {
		if (!confirm(<?php echo _("'Are you sure you want to delete the selected class associations?'");?>)) {
			return false;
		}
	}
	//removes unchanged rows//
	for (var i=0;i<changes.length;i++) {
		if (changes[i]==0) {
			$('#associationRow'+i).remove();
		}
	}
	return true;
}
</script>
