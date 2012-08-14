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
				$public = $row['public'];
				if(can_read_folder($logged_userid,$id))
				{
					$first = false;
?>
				<div class='classFolder' id="classFolder<?php echo $id;?>">
						<a class='folderName' href="files/<?php echo $id;?>/"><img src='images/resource/folder.png' title="<?php echo $name;?>" class='folderIcon icon ' /><?php echo $name;?></a>
					<div class='editOptionsWrapper'>
<?php
					if(can_edit_folder($logged_userid,$id))
					{?>
						<a class='editLink' id="editLink<?php echo $id;?>" href='javascript:void(0)' ><img src='images/resource/edit-pencil.gif' class='icon editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
<?php					}

					if(can_delete_folder($logged_userid,$id))
					{?>
						<a class='deleteLink' id="deleteLink<?php echo $id;?>" href='javascript:void(0)'><img src='images/resource/trash_can.png' class='icon deleteIcon' id="deleteIcon<?php echo $id;?>" alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
						<script type='text/javascript'>
							$(document).ready(function(){
								$('.deleteLink').click(function(){
									if (confirm(<?php echo _("'Are you sure you want to delete this file?'");?>)) {
										var id = $(this).attr('id').replace('deleteLink','');
										var s = "<form style='display:none' action='delete_folder.php' method='post'>";
										s += "<input type='hidden' name='fid' value='"+id+"' />";
										s += '</form>';
										var form = $(s).appendTo('body');
										form.submit(); 	
									}
								});
								$('.editLink').click(function(){
									var id = $(this).attr('id').replace('editLink','');
									$('.editFolderPrompt').css('display','none');
									$('.classFolder').removeClass('classFolderEditted');

									$('#editFolderPrompt'+id).css('display','block');
									$('#classFolder'+id).addClass('classFolderEditted');
								});
								$('.cancelButton').click(function(){
									$('.editFolderPrompt').css('display','none');
									$('.classFolder').removeClass('classFolderEditted');
								});
							});
						</script>
<?php					}?>
					</div>
<?php
					if(can_edit_folder($logged_userid,$id))
					{?>
					<div class='editFolderPrompt' id="editFolderPrompt<?php echo $id;?>">
						<form action='edit_folder.php' method='post'>
						<label><?php echo _('New Name');?></label>
						<input type='text' name='name' value="<?php echo $name;?>" placeholder="<?php echo _('New name here...');?>" />
						<input type='checkbox' name='public' value="1" 
<?php 
						if($public)
							echo "checked='checked'";
?>
						 />
						<label><?php echo _('Public');?></label>
						<input type='hidden' name='fid' value="<?php echo $id;?>" />
						<input type='submit' value="<?php echo _('Submit');?>" />
						<button type='button' class='cancelButton' onclick='javascript:void(0)'><?php echo _('Cancel');?></button>
					</form>
					</div>
<?php
					}?>
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
?>
</div>
