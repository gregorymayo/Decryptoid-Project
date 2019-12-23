<!-- 
	Project Name: Decryptoid
	Student Name: 
		Gregory Mayo, 013422357
		Kevin Prakasa, 012255087
 -->
<?php 	

    destroy_session_and_data();
    echo "You have been successfully logged out <br> Please <a href='home.php'>click here</a> to log in.";
    
    function destroy_session_and_data() {
		$_SESSION = array();
		setcookie(session_name(), '', time() - 2592000, '/');
		//session_destroy();
	}

?>
