<?php
	if($user_type == 's')
	{
		include('../views/home/student.php');
	}
	elseif($user_type == 'a')
	{
		include('../views/home/admin.php');
	}
	elseif($user_type == 'p')
	{
		include('../views/home/professor.php');
	}
	else
	{
		include('../views/home/guest.php');
	}
?>
