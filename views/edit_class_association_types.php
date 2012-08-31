<?php
	include_once('../lib/access_rules.php');
	include_once('../config/security.php');

        if(!isset($error)) 
                $error = array();

	if(!isset($message))
		$message = array();

	$show = false;
	$home_link = "<a href='home/'>"._('Home')."</a>";
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
				$permissions[] = explode(',',$row['permissions']);		
			}		
		}
	}
	else
	{
		$error[] = _('Access Denied.');
	}
?>
<h2> <?php echo _('Edit Class Association Types');?> </h2>
<p class='hierarchyNavigationRow'><?php echo $home_link . " > " ._('Class Association Types');?></p>
<div class='editClassAssociationTypesWrapper'>
<?php	if($show) {?>
		<fieldset>
		<legend><?php echo _('Class Association Types');?></legend>
		<form action='edit_class_association_types.php' method='post' onsubmit='return check()'>
		<table class='associationTable'>
		<tbody>
			<tr><th><img class='deleteIcon' src='images/resource/trash_can.png' alt='X' title="<?php echo _('Delete?');?>"></th><th><?php echo _('Title');?></th><th><?php echo _('Priority');?></th><th><?php echo _('Permissions');?></tr>
<?php		for($i=0;$i<count($tid);++$i) {?>
			<tr id="associationRow<?php echo $i;?>" <?php if($i%2) echo " class='alternateRow'";?> >
				<td><input type='hidden' value="<?php echo $tid[$i];?>" name='id[]' /><input class='classAssociationTypeField deleteCheck' id="delete<?php echo $i;?>" type='checkbox' name='delete[]' value="<?php echo $tid[$i];?>" /></td>
				<td><input class='classAssociationTypeField' id="title<?php echo $i;?>" type='text' name='title[]' value="<?php echo $title[$i];?>" /></td>
				<td><input class='classAssociationTypeField' id="priority<?php echo $i;?>" type='text' name='priority[]' value="<?php echo $priority[$i];?>" /></td>
				<td><ul>
<?php       	foreach($CLASS_PERMISSIONS_TEXT as $per => $per_txt) {?>		
					<li class='permissionCheck'><input class='classAssociationCheck' id="<?php echo $per.$i;?>" type='checkbox' name='<?php echo $per.$i;?>' <?php if(in_array($per, $permissions[$i])) echo "checked='true'";?> /><?php echo $per_txt;?></li>
<?php			}?>	
				</ul>
				</td>
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
		<form class='newAssociation' action='new_class_association_type.php' method='post' onsubmit='return editAssocCheck(false)'>
			<label><?php echo _('Title');?></label><input type='text' name='title' placeholder="<?php echo _('Association Title');?>"/>
			<label><?php echo _('Priority');?></label><input type='text' name='priority' placeholder="<?php echo _('Less is more important');?>"/>
			<div class='newClassAssociationPerm'><label><?php echo _('Permissions');?></label><ul>
<?php     		foreach($CLASS_PERMISSIONS_TEXT as $per => $per_txt) {?>		
					<li class='permissionCheck'><input class='newClassAssociationCheck' id="<?php echo $per;?>" type='checkbox' name='<?php echo $per;?>'/><?php echo $per_txt;?></li>
<?php			}?>	
			</ul></div>
			<input class='submit' type='submit' value="<?php echo _('Submit');?>" />
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
				tid=tid.replace(/[a-z_]+/,"");
				changes[tid]=1;
			});
		});
								

		function check() {
			//delete confirmation//
			var checked=$('input.deleteCheck:checked').length;
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
			editAssocCheck(true);
			return true;
		}
		
		//turn the association checkboxes into comma seperated list//
		function editAssocCheck(multiple) {
			if(multiple) {
				var rows = <?php echo count($tid);?>;
			}
			else {
				rows = 1;
			}
			for(var i=0;i<rows;i++) {
				var s = '';
				var permlist = '';
				if(multiple) {
					var selector = $('#associationRow'+i+' .classAssociationCheck:checked');
				}
				else {
					var selector = $('.newClassAssociationCheck:checked');
				}
				selector.each(function() {
					var perm = this.id.replace(/[0-9]+/,"");
					if (perm!='' && perm!='delete') {
						if (permlist=='') {
							permlist = permlist+perm;
						}
						else {
							permlist = permlist+','+perm;
						}
					}
				});
				s += "<input type='hidden' value='"+permlist+"' ";
				if(multiple) {
					s+= "name='permissions[]'/>";
					$(s).appendTo('#associationRow'+i);
				}
				else {
					s+= "name='permissions'/>";
					$(s).appendTo('.newAssociation');
				}
			}
			return true;
		}
		</script>
<?php	}?>
</div>
