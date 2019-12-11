<?php
	//For sanitizing the input from the user
	function sanitize($connection, $var){
		$var = $connection->real_escape_string($var);
		return $var;
	}
    //Function To Check Text Only Contains Lower Case Letter And Space
    function checkTextLetter($input){
        $length = strlen($input);
        $check = true;
		for($count = 0; $count < $length; $count++ ){
            if( $input[$count] == 'a' || $input[$count] == 'b' || $input[$count] == 'c' || $input[$count] == 'd' || $input[$count] == 'e' || $input[$count] == 'f' || $input[$count] == 'g' || $input[$count] == 'h' || $input[$count] =='i' || $input[$count] == 'j'||
                $input[$count] == 'k' || $input[$count] == 'l' || $input[$count] == 'm' || $input[$count] == 'n' || $input[$count] == 'o' || $input[$count] == 'p' || $input[$count] == 'q' || $input[$count] == 'r' || $input[$count] =='s' || $input[$count] == 't' ||
                $input[$count] == 'u' || $input[$count] == 'v' || $input[$count] == 'w' || $input[$count] == 'x' || $input[$count] == 'y' || $input[$count] == 'z'|| $input[$count] == ' ')
				$check =  true;
			else
				return false;
		}
		if($check)
			return true;
		else
			return false;
    }
?>