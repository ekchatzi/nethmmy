<?php
	include_once('../lib/access_rules.php');

	$show = false;
	if(can_edit_titles($logged_userid))
	{
		$query = "SELECT * FROM titles";
		$ret = mysql_query($query);
		if($ret && mysql_num_rows($ret))
		{
			$show = true;
			$tid = array();
			$title = array();
			$description = array();
			while($row = mysql_fetch_array($ret))
			{
				$tid[] = $row['id'];
				$title[] = $row['title'];
				$description[] = $row['description'];	
			}		
		}
	}
	else
	{
		$error .= _('Access Denied.');
	}
?>
<h2> <?php echo _('Edit Titles');?> </h2>
<div class='editTitlesWrapper'>
<?php	if($show) {?>
		<fieldset>
		<legend><?php echo _('Titles');?></legend>
		<form action='edit_titles.php' method='post' onsubmit='return check()'>
		<table class='titlesTable'>
		<tbody>
			<tr><th><img class='deleteIcon' src='images/resource/trash_can.png' alt='X' title="<?php echo _('Delete?');?>"></th><th><?php echo _('Title');?></th><th><?php echo _('Description');?></tr>
<?php		for($i=0;$i<count($tid);++$i) {?>
			<tr id="titlesRow<?php echo $i;?>" <?php if($i%2) echo " class='alternateRow'";?> >
				<td><input type='hidden' value="<?php echo $tid[$i];?>" name='id[]' /><input class='titleField' id="delete<?php echo $i;?>" type='checkbox' name='delete[]' value="<?php echo $tid[$i];?>" /></td>
				<td><input class='titlesField' id="title<?php echo $i;?>" type='text' name='title[]' value="<?php echo $title[$i];?>" /></td>
				<td><input class='titlesField' id="description<?php echo $i;?>" type='text' name='description[]' value="<?php echo $description[$i];?>" /></td>
			</tr>
<?php		}?>
<?php		if(count($tid) == 0) {?>
			<tr>
				<td colspan='3'><?php echo _('No entries.');?></td>
			</tr>
<?php		}?>
		</tbody>
		</table>
		<input class='submit' type='submit' value="<?php echo _('Apply changes');?>" />
		</form>
		</fieldset>

		<fieldset>		
		<legend><?php echo _('New title');?></legend>
		<form action='new_title.php' method='post'>
			<label><?php echo _('Title');?></label><input type='text' name='title' placeholder="<?php echo _('Title');?>"/>
			<label><?php echo _('Description');?></label><input type='text' name='description' placeholder="<?php echo _('Short title description');?>"/>
			<input class='submit newTitleSubmit' type='submit' value="<?php echo _('Submit');?>" />
		</form>
		</fieldset>
		<script type='text/javascript'>
		var changes = new Array();
		var a=<?php echo count($title);?>;
		for (var i=0;i<a;i++) {
			changes[i]=0;
		}
		$(document).ready(function() {
			$('.titlesField').keyup(function() { 
				var tid = this.id;
				tid=tid.replace(/[a-z]+/,"");
				changes[tid]=1;
			});
			$(':checkbox').click(function() { 
				var tid = this.id;
				tid=tid.replace(/[a-z]+/,"");
				changes[tid]=1;
			});
		});
								

		function check() {
			//delete confirmation//
			var checked=$('input:checked').length;
			if (checked>0) {
				if (!confirm("<?php echo _('Are you sure you want to delete the selected titles?');?>")) {
					return false;
				}
			}
			//removes unchanged rows//
			for (var i=0;i<changes.length;i++) {
				if (changes[i]==0) {
					$('#titlesRow'+i).remove();
				}
			}
			return true;
		}
		</script>
<?php	}?>
</div>
