<?php
	include_once('../lib/access_rules.php');

	$show = false;
	if(can_edit_class_association_types($logged_userid))
	{
		$query = "SELECT * FROM class_association_types";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$show = true;
			$tid = array();
			$title = array();
			$priority = array();
			$permissions = array();
			while($row = mysql_fetch_array($ret))
			{
				$tid[] = $row['id'];
				$title[] = $row['title'];
				$priority[] = $row['priority'];
				$permissions[] = $row['permissions'];		
			}		
		}
	}
	else
	{
		$error .= _('Access Denied.');
	}
?>
<h2> <?php echo _('Edit Class Association Types');?> </h2>
<div class='editClassAssociationTypesWrapper'>
<?php	if($show) {?>
		<fieldset>
		<legend><?php echo _('Class associations types');?></legend>
		<form action='edit_class_association_types.php' method='post' onsubmit='return check()'>
		<table class='associationTable'>
		<tbody>
			<tr><th><img class='deleteIcon' src='images/resource/trash_can.png' alt='X' title="<?php echo _('Delete?');?>"></th><th><?php echo _('Title');?></th><th><?php echo _('Priority');?></th><th><?php echo _('Permissions');?></tr>
<?php		for($i=0;$i<count($tid);++$i) {?>
			<tr id="associationRow<?php echo $i;?>" <?php if($i%2) echo " class='alternateRow'";?> >
				<td><input type='hidden' value="<?php echo $tid[$i];?>" name='id[]' /><input class='classAssociationTypeField' id="delete<?php echo $i;?>" type='checkbox' name='delete[]' value="<?php echo $tid[$i];?>" /></td>
				<td><input class='classAssociationTypeField' id="title<?php echo $i;?>" type='text' name='title[]' value="<?php echo $title[$i];?>" /></td>
				<td><input class='classAssociationTypeField' id="priority<?php echo $i;?>" type='text' name='priority[]' value="<?php echo $priority[$i];?>" /></td>
				<td><input class='classAssociationTypeField' id="permissions<?php echo $i;?>" type='text' name='permissions[]' value="<?php echo $permissions[$i];?>" /></td>
			</tr>
<?php		}?>
<?php		if(count($tid) == 0) {?>
			<tr>
				<td colspan='4'><?php echo _('No entries.');?></td>
			</tr>
<?php		}?>
		</tbody>
		</table>
		<input class='submit' type='submit' value="<?php echo _('Apply changes');?>" />
		</form>
		</fieldset>

		<fieldset class = "editClassAssociationTypes">		
		<legend><?php echo _('New association type');?></legend>
		<form action='new_class_association_type.php' method='post'>
			<label><?php echo _('Title');?></label><input type='text' name='title' placeholder="<?php echo _('Association Title');?>"/>
			<label><?php echo _('Priority');?></label><input type='text' name='priority' placeholder="<?php echo _('How important is to the class');?>"/>
			<label><?php echo _('Permissions');?></label><input type='text' name='permissions' placeholder="<?php echo _('Associatied users permissions');?>"/>
			<br /><input class='submit' type='submit' value="<?php echo _('Submit');?>" />
		</form>
		</fieldset>
		<script type='text/javascript'>
		var changes = new Array();
		var a=<?php echo count($title);?>;
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
				if (!confirm("<?php echo _('Are you sure you want to delete the selected class associations?');?>")) {
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
<?php	}?>
</div>
