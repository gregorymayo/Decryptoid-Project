<!-- 
	Project Name: Decryptoid
	Student Name: 
		Gregory Mayo, 013422357
		Kevin Prakasa, 012255087
 -->
 <?php 	
	echo <<<_END
<h1>WELCOME TO THE DECRYPTOID</h1>
<p>Please Select Your Option:</p>
<input type="radio" name="userOptionToEncryptOrDecrypt" value="encrypt" onclick="window.location.href='encrypt_page.php'">Encrypt<br>
<input type="radio" name="userOptionToEncryptOrDecrypt" value="decrypt" onclick="window.location.href='decrypt_page.php'">Decrypt<br> <br>


_END;

	session_start();
	if (isset($_SESSION['username']))
	{
		$username = $_SESSION['username'];
		$password = $_SESSION['password'];
        $email = $_SESSION['email'];
		echo "Welcome back " . $username;
	}
    else echo "Please <a href='home.php'>click here</a> to log in.";
?>
