<?php
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');

	$allowed = false;
        if(!isset($error))
                $error = '';
	
	$show = false;
	$edit_class = false;
	$edit_associations = false;
	$class_link = _('Edit class');
	$cid = isset($_GET['id'])?$_GET['id']:0;
	if(!($e = class_id_validation($cid)))
	{
		$query = "SELECT * FROM classes WHERE id='$cid' LIMIT 1";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$show = true;
			$result = mysql_fetch_array($ret);
			$class = $result['id'];
			$class_name = $result['title'];
			$class_link = "<a href='class/$class/'>$class_name</a>";
			$semesters = $result['semesters'];
			if(can_edit_class($logged_userid,$class) && can_view_class($logged_userid,$class))
			{
				$edit_class = true;
				$allowed = true;
			}

			if(can_edit_class_associations($logged_userid,$cid))
			{
				$allowed = true;
				$edit_associations = true;

				$tid = array();
				$uid = array();
				$type = array();
				$name = array();
				$permissions = array();
				$title = array();
				$query = "SELECT class_associations.id AS id,
					 class_associations.user AS user,
					 class_associations.type AS type
					 FROM class_associations,class_association_types WHERE class_associations.class='$cid' AND class_associations.type = class_association_types.id ORDER BY class_association_types.priority ASC";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{

					while($row = mysql_fetch_array($ret))
					{
						$tid[] = $row['id'];
						$uid_t = $uid[] = $row['user'];
						$type_t = $type[] = $row['type'];
						$query = "SELECT users.first_name AS first_name,
								 users.last_name AS last_name,
								 class_association_types.title AS title,
								 class_association_types.permissions AS permissions
								FROM class_association_types,users WHERE users.id='$uid_t' AND class_association_types.id='$type_t'";
						$ret2 = mysql_query($query);
						if($ret2 && mysql_num_rows($ret2))
						{
							$row = mysql_fetch_array($ret2);
							$name[] = $row['first_name']." ".$row['last_name'];
							$title[] = $row['title'];
							$permissions[] = $row['permissions'];	
						}
					}		
				}

				$assoc_id = array();
				$assoc_title = array();
				$query = "SELECT * FROM class_association_types";
				$ret = mysql_query($query);
				if($ret && mysql_num_rows($ret))
				{
					while($row = mysql_fetch_array($ret))
					{
						$assoc_id[] = $row['id'];
						$assoc_title[] = $row['title'];
					}
				}
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

	if($allowed == false)
		$error .= _('Access Denied');
?>
<h2><?php echo _('Edit Class');?></h2>
<p class='hierarchyNavigationRow'><?php echo $class_link . " > " . _('Edit Class');?></p>
<div class='editClassWrapper'>
<?php	if($show) {?>
<?php		if($edit_class) {?>
		<form method='post' action='edit_class.php'>
			<fieldset>
			<legend><?php echo _('Class information');?></legend>
			<ul>
				<li><label><?php echo _("Title");?> </label><input type='text' name='title' value="<?php echo $class_name;?>" placeholder="<?php echo _('Class\' title');?>" /></li>
				<li><label><?php echo _("Semesters");?> </label><input type='text' name='semesters' value="<?php echo $semesters;?>" placeholder="<?php echo _('Semesters the class is taught');?>"/></li>
			</ul>
			<p>
				<label id='descriptionLabel'><?php echo _('Description');?></label>
				<textarea name='description' class='descriptionTextarea' placeholder="<?php echo _('Class description');?>"><?php echo $result['description'];?></textarea>
			</p>
			<input type='hidden' name='cid' value="<?php echo $cid;?>" />
			<input type='submit' value="<?php echo _('Apply');?>" />
			</fieldset>
		</form>
<?php		}?>
<?php		if($edit_associations) {?>
	<div class='editClassAssociationsWrapper'>
			<fieldset>
			<legend><?php echo _('Class Associations');?></legend>
			<form action='edit_class_association_types.php' method='post'>
			<table class='associationTable'>
			<tbody>
				<tr><th>&nbsp;</th><th><?php echo _('User');?></th><th><?php echo _('Type');?></th><th><?php echo _('Permissions');?></th></tr>
<?php			for($i=0;$i<count($tid);++$i){	?>
				<tr <?php if($i%2) echo " class='alternateRow' ";?> >
					<td><a class='deleteLink' id="deleteLink<?php echo $tid[$i];?>" href='javascript:void(0)'><img id="deleteIcon<?php echo $tid[$i];?>" class='icon deleteIcon' src='images/resource/trash_can.png' alt='X' title="<?php echo _('Delete');?>"></a></td>
					<td><a href="profile/<?php echo $uid[$i];?>/"><?php echo $name[$i];?></a></td>
					<td><?php echo $title[$i];?></td>
					<td><?php echo $permissions[$i];?></td>
				</tr>
<?php			}?>
<?php			if(count($tid) == 0) {?>
				<tr>
					<td colspan='4'><?php echo _('No entries.');?></td>
				</tr>
<?php			}?>
			</tbody>
			</table>		
			</form>
			</fieldset>
			<fieldset class='newClassAssociationFieldset'>	
			<legend><?php echo _('New association');?></legend>
			<form action='new_class_association.php' method='post'>
				<input type='hidden' value="<?php echo $cid;?>" name='class' />
				<label><?php echo _('User ID');?></label><input type='text' name='user' placeholder="<?php echo _('User ID');?>" />
				<label> <?php echo _('as');?> </label>
				<select name='type'>
<?php			for($i=0;$i<count($assoc_id);$i++){?>
					<option value="<?php echo $assoc_id[$i];?>"><?php echo $assoc_title[$i];?></option>
<?php			}?>
				</select>
				<input class='submit' type='submit' value="<?php echo _('Submit');?>" />
			</form>
			</fieldset>
			<script type='text/javascript'>
				$(document).ready(function(){
					var classId = "<?php echo $cid;?>";
					$('.deleteLink').click(function(){
						if (confirm("<?php echo _('Are you sure you want to delete this class association?');?>")) {
							var id = $(this).attr('id').replace('deleteLink','');
							var s = "<form style='display:none' action='delete_class_association.php' method='post'>";
							s += "<input type='hidden' name='tid' value='"+id+"' />";
							s += "<input type='hidden' name='class' value='"+classId+"' />";
							s += '</form>';
							var form = $(s).appendTo('body');
							form.submit(); 	
						}
					});
				});
			</script>
		</div>
<?php		}?>
<?php		if(can_view_class_association_types($logged_userid)) {?>
				<a href='class_association_types/'><?php echo _('Association types');?></a>
<?php		}?>
<?php	}?>
</div>
