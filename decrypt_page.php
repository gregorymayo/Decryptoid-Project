<?php
echo <<<_END
    <h2>WELCOME TO THE DECRYPT PAGE</h2>

    <form method="POST" action="" >
        <label>Please Select Your Decryption Option:<br><br></label>
        <input type="radio" name="cipher" value="simpleSub" checked >Simple Subtitution<br>
        <input type="radio" name="cipher" value="doubleTra">Double Transposition<br>
        <input type="radio" name="cipher" value="rc4">RC4<br>
        <input type="radio" name="cipher" value="desCip">DES cipher<br><br>
        <label>Fill The Text Or The File To Decrypt (Cannoth Both)<br><br></label>
		Text To Decrypt: <input type="text" name="textDecrypt"><br><br>
        File To Decrypt: <input type="file" name="content"><br><br>
        <button name="uploadButton">Decrypt</button><br><br>
    </form>
    <button onclick="window.location.href='form.php'">Back To Main Page</button>
_END;

    require_once 'login.php';
    require_once 'regular_function.php';
    require_once 'simpleSub_key.php';
    require_once 'rc4_key.php';
    
	global $conn;
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_errno) 
        echo "<br>The Connection Is Error<br>";
        
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

    //Upload Button
    if(isset($_POST['uploadButton'])){
        $check = false;
        $inputCipher = $_POST['cipher'];
        $inputText = $_POST['textDecrypt'];
        $fileUpload = $_POST['content'];
        $isAFile = false;
        
        if($inputText!="" &&!file_exists($fileUpload)){
            //Sanitizing the inputs
            $content = sanitize($conn, $inputText);
            $check = true;
        } else if (($inputText=="")&&file_exists($fileUpload)){
            //The file does not exist
            if(!file_exists($fileUpload)) {
                echo "<br>The file does not exist<br>";
            } else {
                //Get the extention of the file
                $extension = pathinfo($fileUpload,PATHINFO_EXTENSION);
                //Check if the file is in txt
                if($extension == 'txt'){
                    //Open the file
                    $fileOutput = fopen($fileUpload, "r");
                    //Read the file
                    $content = fread($fileOutput , filesize($fileUpload));
                    //Sanitizing the inputs
                    $content = sanitize($conn, $content);
                    $check = true;
                    $isAFile = true;
                }  else {
                    echo "<br><br>The file is not in .txt format<br>";
                }
            }
        } else {
            echo "<br><br>You Need To Follow The Requirements<br>";
        }
        if($check){
            $finalText = "";
            $timestamp = date("Y-m-d H:i:s");
            if($inputCipher == "simpleSub"){
                echo "<br><br>Using A Simple Subtitution Cipher:";
                if(checkTextLetter($content))
                    $finalText = decryptionSimpleSubstitution($content);
                else
                    echo "<br><br>Your Text Should Only Lower Case Letter And Space<br>";
            }
            else if($inputCipher == "doubleTra"){
                echo "<br><br>Using A Double Transposition Cipher:";
                $finalText = decryptionDoubleTransposition($content);
            } else if($inputCipher == "rc4"){
                echo "<br><br>Using A RC4 Cipher:";
                $finalText = decryptionrc4($content);
            }else if($inputCipher == "desCip"){
                echo "<br><br>Using A DES Cipher:";
                $finalText = decryptionDES($content);
            }
            $sql = "INSERT INTO input_table (text_input, cipher, timestamp) VALUES (NULL, '$inputCipher', '$timestamp')";
            mysqli_query($conn,$sql);
            if($isAFile)
                fclose($fileOutput);
            //clear the connection
            mysqli_close($conn);
        }
    }

    //decryption for simple substitution
    function decryptionSimpleSubstitution($input){
        $input = strtolower($input);
        $cipherText = "";
        $length = strlen($input);;
        for($i=0;$i<$length;$i++){
            $key = getOriginalKey($input[$i]);
            if($key < 0){
                $key = 0;
                $cipherText .= getLetterKey($key);
            } else 
            $cipherText .= getLetterKey($key);
        }
        echo "<br><br>$cipherText";
        return $cipherText;
    }

    //encryption for Double Transposition
    function decryptionDoubleTransposition($input){
        $outputFinal = "";
        $outputTempt = " ";
        $arrayTrans = explode(" ",$input);
        for($i=0;$i < sizeof($arrayTrans);$i++){
            $text = $arrayTrans[$i];
            for($j = strlen($text)-1;$j >= 0;$j--){
                $outputFinal .= $text[$j];
            }
            $outputFinal .= $outputTempt;
        }
        echo "<br><br>$outputFinal";
        return $outputFinal;
    }

    //encryption for RC4
    function decryptionrc4($input){
        $input = hex2bin($input);
        $lengthInput = strlen($input);
        $key = getKeyRC4();
        $lengthKey = strlen($key);
        $s = array();
        for ($i = 0; $i < 256; $i++) {
            $s[$i] = $i;
        }
        $j = 0;
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % $lengthKey])) % 256;
            //swap
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
        $i = 0;
        $j = 0;
        $outputFinal = "";
        $outputTempt = "";
        for ($y = 0; $y < $lengthInput; $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            //swap
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;

            $outputTempt .= $input[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
        }
        echo "<br><br>$outputTempt";
        return $outputTempt;
    }

    //encryption for Double Transposition
    function decryptionDES($input){
        
    }
    
?>