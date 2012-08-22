<?php
	include_once('../lib/access_rules.php');

	$show = false;
	if(can_view_class_association_types($logged_userid))
	{
		$show =true;
		$title = array();
		$priority = array();
		$permissions = array();
		$query = "SELECT * FROM class_association_types";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			while($row = mysql_fetch_array($ret))
			{ 
				$title[] = $row['title'];
				$priority[] = $row['priority'];
				$permissions[] = $row['permissions'];
			}		
		}
		else
		{
			echo "<tr colspan='3'><td>"._('No entries.')."</td></tr>";
		}
	}
	else
	{
		$error .= _('Access Denied.');
	}

?>
<h2> <?php echo _('Class Association Types');?> </h2>
<div class='classAssociationTypesWrapper'>
<?php	if($show) {?>
		<fieldset>
		<legend><?php echo _('Association type list');?></legend>
		<table>
		<tbody>
			<tr><th><?php echo _('Title');?></th><th><?php echo _('Priority');?></th><th><?php echo _('Permissions');?></tr>
<?php		$a = 0;
		for($i=0;$i<count($title);++$i) { ++$a;?>
			<tr <?php if($a%2) echo " class='alternateRow'";?> >
				<td><?php echo $title[$i];?></td>
				<td><?php echo $priority[$i];?></td>
				<td><?php echo $permissions[$i];?></td>
			</tr>
<?php		}?>
<?php		if(count($title) == 0) {?>
			<tr>
				<td colspan='3'><?php echo _('No entries.');?></td>
			</tr>
<?php		}?>
		</tbody>
		</table>
		</fieldset>
<?php		if(can_edit_class_association_types($logged_userid)) {?>
		<div class='editOptionsWrapper'>
			<a class='editLink' id="editLink<?php echo $id[$i];?>" href="edit_class_association_types/"' ><img src='images/resource/edit-pencil.gif' class='icon editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
		</div>
<?php		}?>
<?php	}?>
</div>
