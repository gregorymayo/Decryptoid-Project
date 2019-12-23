<!-- 
	Project Name: Decryptoid
	Student Name: 
		Gregory Mayo, 013422357
		Kevin Prakasa, 012255087
 -->
 <?php
echo <<<_END
<html><head><title>Register Page</title></head><body>
<style>
  input[type=text]:focus {
    border: 3px solid #555;
  } 
  input {
    margin-bottom: 10px;
  } 
  pre {
    font-size: 16px;
    
  }
</style>
<h2> Insert User Credential </h2> <pre>
<form action="register.php" method="post"> 
Email    <input type="text" placeholder="Enter Email" name="email" required>
Username <input type="text" placeholder="Enter Username" name="username" required>      
Password <input type="password" placeholder="Enter Password" name="password" required> <br> <br>
<input type="submit" value="REGISTER">  <button type="button" onclick="window.location.href='home.php'">BACK</button>
</form></pre>
_END;
    require_once 'login.php';
    require_once 'sanitize.php';
	$conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->error);
    
    if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
        $email = get_post($conn, 'email');
        $username = get_post($conn, 'username');
        $password = get_post($conn, 'password');
        $salt1 = randomizeString(5); 
        $salt2 = randomizeString(5);
        registerAdmin($conn,$email,$username,$salt1,$salt2,$password);
        $conn->close();
    }

    // check if e-mail address is well-formed
    function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // The username can contain English letters (capitalized or not), digits, and the characters 
    // '_' (underscore) and '-' (dash). Nothing else.
    function validate_username($username) {
        //return preg_match("/^[A-Za-z0-9_- ]+$/", $username);
        return preg_match("/^[A-Za-z0-9_-]+$/", $username);
    }

    // The password can have limitations of your choice, 
    function validate_password($password) {
        return preg_match("/^\w{8,}+$/", $password); 
    }

    // function to produce a randomize salt from alpha numeric
    function randomizeString($length) {
        $alphaNum = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($alphaNum), 0 , $length);
    }

    // function to register admin
    function registerAdmin($conn,$email,$username,$salt1,$salt2,$password){
        $token = hash('ripemd128', "$salt1$password$salt2");
        if(validate_email($email) && validate_password($password) && validate_username($username))
        { 
            $usernameCheck = "SELECT * FROM account_table WHERE username='$username'";
            $result = $conn->query($usernameCheck);
            $query = "INSERT INTO account_table VALUES('$email', '$username', '$salt1', '$salt2', '$token')";
            $result = $conn->query($query);
            if (!$result) die($conn->error);
            else{
                echo "Successfully register!";
        }

        } else if(!validate_email($email)){
            die ("Invalid Email Address <br>");
        } else if(!validate_username($username)){
            die ("Invalid Username, username can only contains alphanumeric and ('_', '-')<br>");
        } else if(!validate_password($password)){
            die ("Invalid Password, password must contain 8 alphanumeric <br>");
        }
		add_user($conn,$email,$username,$salt1,$salt2,$token);	
    }
    // function to add user to db
	function add_user($conn,$email,$username,$salt1,$salt2,$token){
		$query = "INSERT INTO account_table (email, username, salt1, salt2, password) VALUES ('$email', '$username','$salt1','$salt2','$token')";
		mysqli_query($conn,$query);
	}

    
?>


