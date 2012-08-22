<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');

        if(!isset($error))
                $error = '';
	$show = false;
	$edit = false;
	$delete = false;
	$cid = isset($_GET['id'])?$_GET['id']:0;
	$class_link = _('Some class');
	if(!($e = class_id_validation($cid)))
	{
		$show = true;
		$first = true;
		$query = "SELECT classes.title AS class_name FROM classes WHERE classes.id='$cid'";
		$ret = mysql_query($query);
		if($ret && mysql_numrows($ret))
		{
			$result = mysql_fetch_array($ret);
			$class_link = "<a href='class/$cid/'>".$result['class_name']."</a>";
		}

		$id = array();
		$name = array();
		$public = array();
		$query = "SELECT * FROM file_folders WHERE class='$cid'";
		$ret = mysql_query($query);
		if($ret && mysql_numrows($ret))
		{
			while($row = mysql_fetch_array($ret))
			{
				if(can_read_folder($logged_userid,$row['id']))
				{
					$id[] = $row['id'];
					$name[] = $row['name'];
					$public[] = $row['public'];
				}
			}
		}	
	}
	else
	{
		$error .= $e;
	}
?>
<h2><?php echo _('Class File Folders');?></h2>
<p><?php echo $class_link . " > "._('Class Files');?></p>
<div class='classFilesWrapper'>
<?php	if($show) {
		for($i=0;$i<count($id);++$i) {?>
				<div class='classFolder' id="classFolder<?php echo $id[$i];?>">
						<a class='folderName' href="files/<?php echo $id[$i];?>/"><img src='images/resource/folder.png' title="<?php echo $name[$i];?>" class='folderIcon icon ' /><?php echo $name[$i];?></a>
					<div class='editOptionsWrapper'>
<?php				if(can_edit_folder($logged_userid,$id[$i])) { $edit = true;?>
					<a class='editLink' id="editLink<?php echo $id[$i];?>" href='javascript:void(0)' ><img src='images/resource/edit-pencil.gif' class='icon editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
<?php				}?>
<?php				if(can_delete_folder($logged_userid,$id[$i])) { $delete = true;?>
					<a class='deleteLink' id="deleteLink<?php echo $id[$i];?>" href='javascript:void(0)'><img src='images/resource/trash_can.png' class='icon deleteIcon' id="deleteIcon<?php echo $id[$i];?>" alt="<?php echo _('Delete');?>" title="<?php echo _('Delete');?>" /></a>
<?php				}?>
<?php				if($edit || $delete) {?>
					<script type='text/javascript'>
						$(document).ready(function(){
<?php					if($delete) {?>
							$('.deleteLink').click(function(){
								if (confirm("<?php echo _('Are you sure you want to delete this folder?');?>")) {
									var id = $(this).attr('id').replace('deleteLink','');
									var s = "<form style='display:none' action='delete_folder.php' method='post'>";
									s += "<input type='hidden' name='fid' value='"+id+"' />";
									s += '</form>';
									var form = $(s).appendTo('body');
									form.submit(); 	
								}
							});
<?php					}?>
<?php					if($edit) {?>
							$('.editLink').click(function(){
								var id = $(this).attr('id').replace('editLink','');
								$('.editFolderPrompt').css('display','none');
								$('.classFolder').removeClass('classFolderEditted');

								$('#editFolderPrompt'+id).css('display','block');
								$('#classFolder'+id).addClass('classFolderEditted');
							});
<?php					}?>
							$('.cancelButton').click(function(){
								$('.editFolderPrompt').css('display','none');
								$('.classFolder').removeClass('classFolderEditted');
							});
						});
					</script>
<?php				}?>
					</div>
<?php				if(can_edit_folder($logged_userid,$id[$i])) {?>
					<div class='editFolderPrompt' id="editFolderPrompt<?php echo $id[$i];?>">
						<form action='edit_folder.php' method='post'>
						<label><?php echo _('New Name');?></label>
						<input type='text' name='name' value="<?php echo $name[$i];?>" placeholder="<?php echo _('New name here...');?>" />
						<input type='checkbox' name='public' value="1" <?php if($public[$i]) echo "checked='checked'";?>/>
						<label><?php echo _('Public');?></label>
						<input type='hidden' name='fid' value="<?php echo $id[$i];?>" />
						<input type='submit' value="<?php echo _('Submit');?>" />
						<button type='button' class='cancelButton' onclick='javascript:void(0)'><?php echo _('Cancel');?></button>
					</form>
					</div>
<?php				}?>
				</div>
<?php		}?>
<?php		if(count($id) == 0) {?>
			<p><?php echo _('No file folders yet.');?></p>
<?php		}?>
<?php		if(can_create_folder($logged_userid,$cid))
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
<?php	}?>
</div>
