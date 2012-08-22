<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');

        if(!isset($error))
                $error = '';

	$show = false;
	$edit = false;
	$fid = isset($_GET['id'])?$_GET['id']:0;
	$folder_name =  _('Folder');
	$class_link = _('Some class');
	$class_files_link = _('Class files');
	if(!($e = folder_id_validation($fid)))
	{
		$show = true;
		$query = "SELECT file_folders.name AS folder_name, classes.id AS class_id ,classes.title AS class_name FROM file_folders,classes WHERE file_folders.id='$fid' AND classes.id=file_folders.class";
		$ret = mysql_query($query);
		if($ret && mysql_numrows($ret))
		{
			$result = mysql_fetch_array($ret);
			$folder_name =  $result['folder_name'];
			$class_link = "<a href='class/".$result['class_id']."/'>".$result['class_name']."</a>";
			$class_files_link = "<a href='class_files/".$result['class_id']."/'>"._('Class files')."</a>";
		}

		$id = array();
		$name = array();
		$filepath = array();
		$upload_time = array();
		$uploader = array();
		$filesize = array();
		$file_extension = array();
		$icon = array();
		$can_edit = array();
		$query = "SELECT * FROM files WHERE folder='$fid'";
		$ret = mysql_query($query);
		if($ret && mysql_numrows($ret))
		{
			while($row = mysql_fetch_array($ret))
			{
				if(can_download_file($logged_userid,$row['id'])) 
				{
					$id[] = $row['id'];
					$name[] = $row['name'];
					$filepath[] = $row['full_path'];
					$upload_time[] = $row['upload_time'];
					$uploader[] = $row['uploader'];
					$filesize[] = file_exists($row['full_path'])?filesize($row['full_path']):0;
					$file_extension[] = strtolower(substr(strrchr($filepath,"."),1));
					$icon[] = file_exists("images/resource/filetype_icons/$file_extension.png")?"images/resource/filetype_icons/$file_extension.png":"images/resource/filetype_icons/default.png";
					$can_edit_t = can_edit_file($logged_userid,$row['id']);
					if($can_edit_t)
						$edit = true;
					$can_edit[] = $can_edit_t;
				}
			}
		}	
	}
	else
	{
		$error .= $e;
	}
?>
<h2><?php echo _('Folder contents');?></h2>
<p><?php echo $class_link . " > " . $class_files_link . " > " . $folder_name;?></p>
<div class='filesWrapper'>
<?php	if($show) {?>
<?php 		if($edit) {?>
			<p><input class='selectAll' type='checkbox' /></p>
<?php		}?>
<?php		for($i=0;$i<count($id);++$i) {?>
			<div class='fileContainer' id="fileContainer<?php echo $id[$i];?>">
<?php 			if($can_edit[$i]) {?>
				<input class='deleteCheck' type='checkbox' name='delete[]' value="<?php echo $id[$i];?>"/>
<?php			}?>
				<a class='fileName' href="download_file.php?fid=<?php echo $id[$i];?>"><img src="<?php echo $icon[$i];?>" title="<?php echo sprintf(_('Download %s'),$name[$i]);?>" alt="<?php echo _('download');?>" class='filetypeIcon' /> <?php echo $name[$i];?><span class='filesizeSpan'>(<?php echo $filesize[$i];?> bytes)</span></a>
<?php			if($can_edit[$i]) { $edit = true;?>
				<div class='editOptionsWrapper'>
					<a class='editLink' id="editLink<?php echo $id[$i];?>" href='javascript:void(0)' ><img src='images/resource/edit-pencil.gif' class='icon editIcon' alt="<?php echo _('Edit');?>" title="<?php echo _('Edit');?>" /></a>
					<a class='deleteLink' id="deleteLink<?php echo $id[$i];?>" href='javascript:void(0)'><img src='images/resource/trash_can.png' class='icon deleteIcon' id="deleteIcon<?php echo $id[$i];?>" alt="<?php echo _('Delete');?>" title="<?php echo _('Delete');?>" /></a>
				</div>
				<div class='editFilePrompt' id="editFilePrompt<?php echo $id[$i];?>">
				<form action='edit_file.php' method='post'>
					<input type='hidden' name='fid' value="<?php echo $id[$i];?>" />
					<label><?php echo _('New Name');?></label>
					<input type='text' name='name' value="<?php echo $name[$i];?>" placeholder="<?php echo _('New name here...');?>" /> 
					<input type='submit' value="<?php echo _('Submit');?>" />
					<button type='button' class='cancelButton' onclick='javascript:void(0)'><?php echo _('Cancel');?></button>
				</form>
				</div>
			</div>
<?php			}?>
<?php		}?>
<?php		if(count($id) == 0) {?>
			<p><?php echo _('No files yet.');?></p>
<?php		}?>
<?php		if($edit) {?>
			<a class='deleteSelectedLink' href='javascript:void(0)'><img src='images/resource/trash_can.png' class='icon deleteIcon' id="deleteIcon<?php echo $id[$i];?>" alt="<?php echo _('Delete');?>" title="<?php echo _('Delete');?>" />Delete Selected</a>
			<script type='text/javascript'>
				$(document).ready(function(){
					$('.selectAll').click(function(){
						if($('.selectAll').attr('checked'))
							$('.deleteCheck').attr('checked','checked');
						else
							$('.deleteCheck').attr('checked',false);							
					}).add('.deleteCheck').attr('checked',false);
					$('.deleteSelected').click(function(){
					});
					$('.deleteLink').click(function(){
						if (confirm("<?php echo _('Are you sure you want to delete this file?');?>")) {
							var id = $(this).attr('id').replace('deleteLink','');
							var s = "<form style='display:none' action='delete_files.php' method='post'>";
							s += "<input type='hidden' name='fid[]' value='"+id+"' />";
							s += "<input type='folder' name='folder' value='"+<?php echo $fid;?>+"' />";
							s += '</form>';
							var form = $(s).appendTo('body');
							form.submit(); 	
						}
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
<?php		}?>
<?php		if(can_upload_file($logged_userid,$fid)) {?>
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
<?php 	}?>
</div>
