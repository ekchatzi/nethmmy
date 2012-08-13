<?php
	include_once('../lib/connect_db.php');
	include_once('../lib/validate.php');

	function file_download_stat_log($file)
	{
		if(!file_id_validation($file))
		{
			$query = "UPDATE files SET download_count = download_count +1 WHERE id='$file'";
			mysql_query($query);
		}
	}
