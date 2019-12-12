<?php
	//For sanitizing the input from the user
	function sanitize($connection, $var){
		$var = $connection->real_escape_string($var);
		return $var;
	}
	//For check the text for simple subtitution cipher
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
	//For check the text for DES cipher
    function checkDESLetter($input){
		$length = strlen($input);
		if($length!=16){
			echo "<br>The Text Should Contains Of 16 Characters";
			return false;
		}
        $check = true;
		for($count = 0; $count < $length; $count++ ){
            if( $input[$count] == 'A' || $input[$count] == 'B' || $input[$count] == 'C' || $input[$count] == 'D' || $input[$count] == 'E' || $input[$count] == 'F' || $input[$count] == '0' || $input[$count] == '1' || $input[$count] =='2' || $input[$count] == '3'||
                $input[$count] == '4' || $input[$count] == '5' || $input[$count] == '6' || $input[$count] == '7' || $input[$count] == '8' || $input[$count] == '9')
				$check =  true;
			else {
				echo "<br>The Text Should Contains Numbers And Characters (A/B/C/D/E/F) Only";
				echo "<br>Example: ABBBC12345AAAAAB";
				return false;
			}
				
		}
		if($check)
			return true;
		else
			return false;
	}

    function hex2binn($s){ 
        // hexadecimal to binary conversion 
        $mp = array(); 
        $mp["0"]= "0000"; 
        $mp["1"]= "0001"; 
        $mp["2"]= "0010"; 
        $mp["3"]= "0011"; 
        $mp["4"]= "0100"; 
        $mp["5"]= "0101"; 
        $mp["6"]= "0110"; 
        $mp["7"]= "0111"; 
        $mp["8"]= "1000"; 
        $mp["9"]= "1001"; 
        $mp["A"]= "1010"; 
        $mp["B"]= "1011"; 
        $mp["C"]= "1100"; 
        $mp["D"]= "1101"; 
        $mp["E"]= "1110"; 
        $mp["F"]= "1111"; 
        $bin=""; 
        for($i=0; $i<strlen($s); $i++){ 
            $bin .= $mp[$s[$i]]; 
        } 
        return $bin; 
	} 
	function bin2hexx($s){ 
        // binary to hexadecimal conversion 
        $mp = array(); 
        $mp["0000"]= "0"; 
        $mp["0001"]= "1"; 
        $mp["0010"]= "2"; 
        $mp["0011"]= "3"; 
        $mp["0100"]= "4"; 
        $mp["0101"]= "5"; 
        $mp["0110"]= "6"; 
        $mp["0111"]= "7"; 
        $mp["1000"]= "8"; 
        $mp["1001"]= "9"; 
        $mp["1010"]= "A"; 
        $mp["1011"]= "B"; 
        $mp["1100"]= "C"; 
        $mp["1101"]= "D"; 
        $mp["1110"]= "E"; 
        $mp["1111"]= "F"; 
        $hex=""; 
        for($i=0; $i<strlen($s); $i+=4){ 
            $ch=""; 
            $ch.= $s[$i]; 
            $ch.= $s[$i+1]; 
            $ch.= $s[$i+2]; 
            $ch.= $s[$i+3]; 
            $hex.= $mp[$ch]; 
        } 
        return $hex; 
	} 
	function Reverse($array) { 
		return(array_reverse($array)); 
	}
?>