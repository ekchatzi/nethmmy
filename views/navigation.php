<ul>
<?php
	$query = "SELECT * FROM classes";
	$ret = mysql_query($query);
	if($ret && mysql_num_rows($ret)) {
		while($row = mysql_fetch_array($ret)) {
			echo "<li><a href='class/".$row['id']."/'>".$row['title']."</a>
			<ul class='navigationClassDirectories' id='class".$row['id']."'>
				<li><a href='announcements/".$row['id']."/'>"._("Announcements")."</a></li>
				<li><a href='class_files/".$row['id']."/'>"._("Files")."</a></li>
				</ul></li>";
		}
	}
	else {
		echo "<li>"._("No classes there are")."</li>";
	}
?>
</ul>
<script>
	var cid = <?php echo isset($_GET['id'])?$_GET['id']:0;?>;
	if (cid > 0) {
		var select = 'class'+cid;
		$('#'+select).show('fast');
	}
</script>