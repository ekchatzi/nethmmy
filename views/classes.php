<?php	
	include_once('../lib/access_rules.php');
	include_once('../lib/validate.php');
	include_once('../config/general.php');
	
	if(!isset($error))
				$error = '';
	$show = false;
	$show_sub = false;
	if (can_view_classes_list($logged_userid)) 
	{
		$show =true;
		$title = array();
		$id = array();
		for ($i=0;$i<=$SEMESTERS_COUNT;$i++) 
		{
			$title_t = array();
			$id_t = array();
			$query = "SELECT * FROM classes WHERE FIND_IN_SET($i, semesters) ORDER BY title ASC";
			$ret = mysql_query($query);
			if($ret && mysql_num_rows($ret)) 
			{
				while($row = mysql_fetch_array($ret)) 
				{
					$title_t[] = (can_view_class($logged_userid,$row['id']))?"<a href='class/".$row['id']."/' class='classLink'>".$row['title']."</a>":$row['title'];
					$id_t[] = $row['id'];
				}
			}
			$title[] = $title_t;
			$id[] = $id_t;
		}

		if (can_change_class_subscriptions($logged_userid,$logged_userid )) 
		{
			$show_sub = true;
			$query = "SELECT classes FROM users WHERE id = '$logged_userid'";
			$ret = mysql_query($query);
			$classes = array();
			if($ret && mysql_num_rows($ret) && ($classesraw = mysql_result($ret,0,0)))
			{
				$classes = explode(",", $classesraw);
			}
		}
	}
	else 
	{
		$error .= _("Access Denied");
	}
?>
<h2><?php echo _('Classes');?> </h2>
<div class='classesWrapper'>
<?php	if($show) {?>
<?php		if($show_sub) {?>
			<form action='classes_subscription.php' method='post'>
<?php		}?>
<?php		for($s=1;$s<=$SEMESTERS_COUNT;++$s) {?>
				<div class='semesterDiv'><a href='javascript:void(0)'><img class='showImage' id='semesterImage<?php echo $s;?>' alt='expand/collapse' src='../public_html/images/resource/expandIcon.gif' height="15" width="15"></a><div class='semesterTitle'><?php echo sprintf(_("Semester %s"),$s);?></div><div class='semesterClassesDiv' id='classes<?php echo $s;?>'>
<?php			for($i=0;$i<count($id[$s]);++$i) {?>
					<div class='classField'><p class='classTitleField'><?php echo $title[$s][$i];?>
<?php				if($show_sub) {?>						
					<div class='subscribeCheck'><input id='classCheck' name='subscribe[]' value="<?php echo $id[$s][$i];?>" id="checkbox<?php echo $id[$s][$i];?>" type="checkbox"
					<?php if(isset($classes)&&in_array($id[$s][$i], $classes)) echo "checked='true'";?> />
					<img id='joinImage' src='../public_html/images/resource/joinIcon.png' alt='join icon' height="30" width="30"/></div>
<?php				}?>
					</div>
<?php			}?>
<?php			if(count($id[$s]) == 0) {?>
					<p><?php echo _('No classes.');?></p>
<?php			}?>
				</div></div>
<?php		}?>
<?php		if($show_sub) {?>
				<input class='submit' id='button' type='submit' value='<?php echo _("Set subscriptions");?>'/> 
			</form>
			<script>
				$(document).ready(function(){	
					//unchecks all classes with same id when one is unchecked//
					$('.classCheck').click(function(){
						var thischeck=$(this);
						var checkid=$(this).attr('id');
						if (!thischeck.is(':checked')) 
						{
							$('#'+checkid).attr('checked', false);
						}
					});
				});
			</script>	
<?php		}?>
		<script>
			$(document).ready(function(){			
				//expands or collapses semester divs//
				$('img.showImage').click(function(){
					var src = $(this).attr("src");
					if(src.indexOf("expand") >= 0) {
						var src = src.replace("expand", "collapse");
						$(this).attr("src",src);
						var id = $(this).attr("id").replace("semesterImage", "");
						$('div#classes'+id).show("fast");
					}
					else if (src.indexOf("collapse") >= 0) {
						var src = src.replace("collapse", "expand");
						$(this).attr("src",src);
						var id = $(this).attr("id").replace("semesterImage", "");
						$('div#classes'+id).hide("fast");
					}
				});
			});
		</script>
<?php	}?>
</div>
