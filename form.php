<?php
echo <<<_END
	<h1>WELCOME TO THE DECRYPTOID</h1>
	<p>Please Select Your Option:</p>
	<input type="radio" name="userOptionToEncryptOrDecrypt" value="encrypt" onclick="window.location.href='encrypt_page.php'">Encrypt<br>
	<input type="radio" name="userOptionToEncryptOrDecrypt" value="decrypt" onclick="window.location.href='decrypt_page.php'">Decrypt<br>
_END;

	require_once 'login.php';
	require_once 'regular_function.php';
	global $conn;
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_errno) 
		echo "<br>The Connection Is Error<br>";
	//clear the connection
	mysqli_close($conn);
?>