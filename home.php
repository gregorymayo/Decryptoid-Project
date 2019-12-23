<!-- 
	Project Name: Decryptoid
	Student Name: 
		Gregory Mayo, 013422357
		Kevin Prakasa, 012255087
 -->
 <?php 
// output html form
echo <<<_END

<h1>WELCOME TO THE DECRYPTOID</h1>
<p>Please Select Your Option:</p>
<input type="radio" name="userOptionToEncryptOrDecrypt" value="encrypt" onclick="window.location.href='encrypt_page.php'">Encrypt<br>
<input type="radio" name="userOptionToEncryptOrDecrypt" value="decrypt" onclick="window.location.href='decrypt_page.php'">Decrypt<br> <br>


<html><head><title>Final Project</title></head><body>
<h2> User Login </h2> <pre>
<form  method="post"> 
Username <input type="text" name="username">      
Password <input type="password" name="password"> <br> <br>
<input type="submit" value="LOGIN"> <br> 
<button type="button" onclick="window.location.href='register.php'">REGISTER</button>
</pre></form>

_END;

    require_once 'login.php';
    require_once 'sanitize.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if($conn->connect_error) die($conn->connect_error);

    // to check if user has inserted their credentials
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = get_post($conn, 'username');
        $password = get_post($conn, 'password');

        $query = "SELECT * FROM account_table WHERE username='$username'" ;
        $result = $conn->query($query);
        if (!$result) die ("Invalid username/password combination");

        $row = $result->fetch_array(MYSQLI_NUM);
        $temp_salt1 = $row[2];
        $temp_salt2 = $row[3];
        $token = hash('ripemd128', "$temp_salt1$password$temp_salt2");

        if ($token == $row[4]) {
            // start session and set session credentials
            session_start();
            $_SESSION['username'] = $username;
			$_SESSION['password'] = $token;
			$_SESSION['email'] = $row[0];
            
            echo "Welcome back " . $row[1] . " you are now logged in <br>";
            die ("<p><a href=userpage.php>Click here to continue</a></p>");
        }
        else die ("Invalid username/password combination");
        
        $result->close();
    }
    $conn->close();

    function destroy_session_and_data() {
		$_SESSION = array();
		setcookie(session_name(), '', time() - 2592000, '/');
		session_destroy();
	}

?>