<h2> <?php echo _('Class Association Types');?> </h2>
<div class='classAssociationTypesWrapper'>
<?php
	include_once('../lib/access_rules.php');

	if(can_view_class_association_types($logged_userid))
	{?>
		<table>
		<tbody>
			<tr><th><?php echo _('Title');?></th><th><?php echo _('Priority');?></th><th><?php echo _('Permissions');?></tr>
<?php
		$query = "SELECT * FROM class_association_types";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			while($row = mysql_fetch_array($ret))
			{
				echo "<tr><td>".$row['title']."</td>";
				echo "<td>".$row['priority']."</td>";
				echo "<td>".$row['permissions']."</td></tr>";	
			}		
		}
		else
		{
			echo "<tr colspan='3'><td>"._('No entries.')."</td></tr>";
		}
?>
		</tbody>
		</table>
<?php		if(can_edit_class_association_types($logged_userid))
		{?>
			<a href='edit_class_association_types/'><?php echo _('Edit');?></a>
<?php		}
?>
<?php	}
	else
	{?>
		<p class='error'><?php echo _('Access Denied.');?></p>
<?php	}?>
</div>
