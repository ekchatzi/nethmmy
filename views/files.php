<h2><?php echo _('Folder contents');?></h2>
<div class='filesWrapper'>
<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');

        if(!isset($error))
                $error = '';

	$fid = isset($_GET['id'])?$_GET['id']:0;
	if(!($e = folder_id_validation($fid)))
	{
		$first = true;
		$query = "SELECT * FROM files WHERE folder='$fid'";
		$ret = mysql_query($query);
		if($ret && mysql_numrows($ret))
		{
			while($row = mysql_fetch_array($ret))
			{
				$id = $row['id'];
				$name = $row['name'];
				$filepath = $row['full_path'];
				$upload_time = $row['upload_time'];
				$uploader = $row['uploader'];
				$filesize = file_exists($filepath)?filesize($filepath):0;
				$file_extension = strtolower(substr(strrchr($filepath,"."),1));
				$icon = file_exists("images/resource/filetype_icons/$file_extension.png")?"images/resource/filetype_icons/$file_extension.png":"images/resource/filetype_icons/default.png";
				if(can_download_file($logged_userid,$id))
				{
					$first = false;
?>
				<div class='fileContainer' id="fileContainer<?php echo $id;?>">
						<a class='fileName' href="download_file.php?fid=<?php echo $id;?>"><img src="<?php echo $icon;?>" title="<?php echo sprintf(_('Download %s'),$name);?>" alt="<?php echo _('download');?>" class='filetypeIcon' /> <?php echo $name;?><span class='filesizeSpan'>(<?php echo $filesize;?> bytes)</span></a>
					<div class='editOptionsWrapper'>
<?php
					if(can_edit_file($logged_userid,$id))
					{?>
						<a class='editLink' id="editLink<?php echo $id;?>" href='javascript:void(0)' ><img src='images/resource/edit-pencil.gif' class='icon editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
						<a class='deleteLink' id="deleteLink<?php echo $id;?>" href='javascript:void(0)'><img src='images/resource/trash_can.png' class='icon deleteIcon' id="deleteIcon<?php echo $id;?>" alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
						<script type='text/javascript'>
							$(document).ready(function(){
								$('.deleteLink').click(function(){
									var id = $(this).attr('id').replace('deleteLink','');
									var s = "<form style='display:none' action='delete_files.php' method='post'>";
									s += "<input type='hidden' name='fid[]' value='"+id+"' />";
									s += "<input type='folder' name='folder' value='"+<?php echo $fid;?>+"' />";
									s += '</form>';
									var form = $(s).appendTo('body');
									form.submit(); 	
								});
								$('.editLink').click(function(){
									var id = $(this).attr('id').replace('editLink','');
									$('.editFilePrompt').css('display','none');
									$('.fileContainer').removeClass('fileContainerEditted');

									$('#editFilePrompt'+id).css('display','block');
									$('#fileContainer'+id).addClass('fileContainerEditted');
								});
								$('.cancelButton').click(function(){
									$('.editFilePrompt').css('display','none');
									$('.fileContainer').removeClass('fileContainerEditted');
								});
							});
						</script>
<?php					}?>
					</div>
<?php
					if(can_edit_file($logged_userid,$id))
					{?>
					<div class='editFilePrompt' id="editFilePrompt<?php echo $id;?>">
						<form action='edit_file.php' method='post'>
						<input type='hidden' name='fid' value="<?php echo $id;?>" />
						<label><?php echo _('New Name');?></label>
						<input type='text' name='name' value="<?php echo $name;?>" placeholder="<?php echo _('New name here...');?>" /> 

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
			echo _('No files yet.');
		}

		if(can_upload_file($logged_userid,$fid))
		{?>
			<div class='newFileWrapper'>
				<fieldset>
					<legend><?php echo _('Upload file');?></legend>
					<form action='upload_file.php' method='post'  enctype="multipart/form-data" >
						<label><?php echo _('Name');?></label>
						<input type='text' name='name' placeholder="<?php echo _('File name here...');?>" />
						<label><?php echo _('File');?></label>
						<input type='file' name='file' />
						<input type='hidden' name='folder' value="<?php echo $fid;?>" />
						<input type='submit' value="<?php echo _('Upload file');?>" />
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
