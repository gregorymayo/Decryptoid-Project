<!-- 
	Project Name: Decryptoid
	Student Name: 
		Gregory Mayo, 013422357
		Kevin Prakasa, 012255087
 -->
<?php 	
	require_once 'login.php';
	$connection = new mysqli($hn, $un, $pw, $db);
	if ($connection->connect_error) die($connection->connect_error);

	if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
		$un_temp = mysql_entities_fix_string($connection, $_SERVER['PHP_AUTH_USER']);
		$pw_temp = mysql_entities_fix_string($connection, $_SERVER['PHP_AUTH_PW']);

		$query = "SELECT * FROM account_table WHERE username='$un_temp'";
		$result = $connection->query($query); 

		if (!$result) die($connection->error);
		elseif ($result->num_rows) {
			$row = $result->fetch_array(MYSQLI_NUM);
			$result->close();
            $salt1 = $row[2]; 
            $salt2 = $row[3];
			$token = hash('ripemd128', "$salt1$pw_temp$salt2");

			if ($token == $row[3]) {
				session_start();
				$_SESSION['username'] = $un_temp;
				$_SESSION['password'] = $pw_temp;
				$_SESSION['forename'] = $row[0];
				$_SESSION['surname'] = $row[1];
				echo "$row[0] $row[1] : Hi $row[0], you are now logged in as '$row[2]'";
				die ("<p><a href=continue.php>Click here to continue</a></p>");
			}
			else die("Invalid username/password combination");
		}
		else die("Invalid username/password combination");
	}
	else 	{
		header('WWW-Authenticate: Basic realm="Restricted Section"');
		header('HTTP/1.0 401 Unauthorized');
		die ("Please enter your username and password");
	}
	$connection->close();

?>
