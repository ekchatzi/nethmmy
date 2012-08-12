<h2><?php echo _('Class Files');?></h2>
<div class='classFilesWrapper'>
<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');

        if(!isset($error))
                $error = '';

	$cid = isset($_GET['id'])?$_GET['id']:0;
	if(!($e = class_id_validation($cid)))
	{
		$first = true;
		$query = "SELECT * FROM file_folders WHERE class='$cid'";
		$ret = mysql_query($query);
		if($ret && mysql_numrows($ret))
		{
			while($row = mysql_fetch_array($ret))
			{
				$id = $row['id'];
				$name = $row['name'];

				if(can_read_folder($logged_userid,$id))
				{
					if(!$first)
						echo "<hr />\n";
?>
					<div class='classFolder' id="classFolder<?php echo $id;?>">
						<a class='folderName'><img src='images/resource/folder.jpeg' title="<?php echo $name;?>" /><?php echo $name;?></a>
					<div class='editOptionsWrapper'>
<?php
					if(can_edit_folder($logged_userid,$id))
					{?>
						<a class='editLink' id="editLink<?php echo $id;?>" href="edit_folder/<?php echo $id;?>/"><img src='images/resource/edit-pencil.gif' class='editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
<?php					}

					if(can_delete_folder($logged_userid,$id))
					{?>
						<a class='deleteLink' id="deleteLink<?php echo $id;?>" href='javascript:void(0)'><img src='images/resource/trash_can.png' class='deleteIcon' id="deleteIcon<?php echo $id;?>" alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
						<script type='text/javascript'>
							$(document).ready(function(){
								var classId = "<?php echo $cid;?>";
								$('.deleteLink').click(function(){
									var id = $(this).attr('id').replace('deleteLink','');
									var s = "<form style='display:none' action='delete_folder.php' method='post'>";
									s += "<input type='hidden' name='fid' value='"+id+"' />";
									s += '</form>';
									var form = $(s).appendTo('body');
									form.submit(); 	
								});
							});
						</script>
<?php					}?>
					</div>
<?php				}
			}
		}	
		if($first)
		{
			echo _('No file folders yet.');
		}

		if(can_create_folder($logged_userid,$cid))
		{?>
			<div class='newFolderWrapper'>
				<fieldset>
					<legend><?php echo _('New Folder');?></legend>
					<form action='new_folder.php' method='post'>
						<label><?php echo _('Name');?></label>
						<input type='text' name='name' placeholder="<?php echo _('Folder name here...');?>" />
						<input type='checkbox' name='public' value="1" checked='checked' />
						<label><?php echo _('Public');?></label>
						<input type='hidden' name='class' value="<?php echo $cid;?>" />
						<input type='submit' value="<?php echo _('Create folder');?>" />
					</form>
				</fieldset>
			</div>
<?php		}?>
		</div>
<?php	}
	else
	{
		$error .= $e;
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
