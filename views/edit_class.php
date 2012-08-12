<h2><?php echo _('Edit class');?></h2>
<div class='editClassWrapper'>
<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

	$allowed = false;
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
			$title = $result['title'];?>
<?php
			if(can_edit_class($logged_userid,$cid) && can_view_class($logged_user,$cid))
			{
				$allowed = true;
				?>
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
?>
</div>
<div class='editClassAssociationsWrapper'>
<?php
	if(!($e = class_id_validation($cid)))
	{
		if(can_edit_class_associations($logged_userid,$cid))
		{
			$allowed = true;?>
			<fieldset>
			<legend><?php echo _('Class Associations');?></legend>
			<form action='edit_class_association_types.php' method='post'>
			<table class='associationTable'>
			<tbody>
				<tr><th>&nbsp;</th><th><?php echo _('User');?></th><th><?php echo _('Type');?></th><th><?php echo _('Permissions');?></th></tr>
<?php
			$query = "SELECT class_associations.id AS id,
					 class_associations.user AS user,
					 class_associations.type AS type
					 FROM class_associations,class_association_types WHERE class_associations.class='$cid' AND class_associations.type = class_association_types.id ORDER BY class_association_types.priority ASC";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret))
			{
				$has_values = true;
				$a = 0;
				while($row = mysql_fetch_array($ret))
				{
					$tid = $row['id'];
					$uid = $row['user'];
					$type = $row['type'];
					$query = "SELECT users.first_name AS first_name,
							 users.last_name AS last_name,
							 class_association_types.title AS title,
							 class_association_types.permissions AS permissions
							FROM class_association_types,users WHERE users.id='$uid' AND class_association_types.id='$type'";
					$ret2 = mysql_query($query);
					if($ret2 && mysql_num_rows($ret2))
					{
						$row = mysql_fetch_array($ret2);
						echo "<tr";
						if($a%2)
							echo " class='alternateRow' ";
						echo ">\n";
						echo "<td><a class='deleteLink' id='deleteLink$tid' href='javascript:void(0)'><img id='deleteIcon$tid' class='deleteIcon' src='images/resource/trash_can.png' alt='X' title='"._('Delete')."'></a>";
						echo "</td>\n";
						echo "<td><a href='profile/$uid/'>".$row['first_name']." ".$row['last_name']."</a></td>\n";
						echo "<td>".$row['title']."</td>\n";
						echo "<td>".$row['permissions']."</td>\n</tr>\n";	
						++$a;
					}
				}		
			}
			else
			{
				echo "\n<tr>\n<td colspan='4'>"._('No entries.')."</td>\n</tr>\n";
			}
?>
			</tbody>
			</table>		
			</form>
			</fieldset>

			<fieldset>		
			<legend><?php echo _('New association');?></legend>
			<form action='new_class_association.php' method='post'>
				<label><?php echo _('User ID');?></label><input type='text' name='user' placeholder="<?php echo _('User ID');?>" />
				<label> <?php echo _('as');?> </label>
				<select name='type'>
<?php
				$query = "SELECT * FROM class_association_types";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					while($row = mysql_fetch_array($ret))
					{
						echo "<option value='".$row['id']."'>".$row['title']."</option>";
					}
				}
	?>
				</select>
				<input type='hidden' value="<?php echo $cid;?>" name='class' />
				<input class='submit' type='submit' value="<?php echo _('Submit');?>" />
			</form>
			</fieldset>
			<script type='text/javascript'>
				$(document).ready(function(){
					var classId = "<?php echo $cid;?>";
					$('.deleteLink').click(function(){
						var id = $(this).attr('id').replace('deleteLink','');
						var s = "<form style='display:none' action='delete_class_association.php' method='post'>";
						s += "<input type='hidden' name='tid' value='"+id+"' />";
						s += "<input type='hidden' name='class' value='"+classId+"' />";
						s += '</form>';
						var form = $(s).appendTo('body');
						form.submit(); 	
					});
				});
			</script>
<?php
			if(can_view_class_association_types($logged_userid))
			{?>
			<a href='class_association_types/'><?php echo _('Association types');?></a>
<?php			}?>
<?php		}
	}
	else
	{
		$error .= $e;
	}
	if(!$allowed)
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
