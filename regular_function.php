<?php
	//For sanitizing the input from the user
	function sanitize($connection, $var){
		$var = $connection->real_escape_string($var);
		return $var;
    }
?>