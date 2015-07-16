<?php

	
	$templatestyle = $_GET['templatestyle'];
		setcookie("templatestyle", $templatestyle, time()+604800,"/");
		if(isset($_GET['js'])) {
		echo $templatestyle;
		} else {
		header("Location: ".$_SERVER['HTTP_REFERER']);
	}


?>


