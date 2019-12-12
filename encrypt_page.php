<?php
echo <<<_END
    <h2>WELCOME TO THE ENCRYPT PAGE</h2>
    <form method="POST" action="" >
        <label>Please Select Your Encryption Option:<br><br></label>
        <input type="radio" name="cipher" value="simpleSub" checked >Simple Subtitution<br>
        <input type="radio" name="cipher" value="doubleTra">Double Transposition<br>
        <input type="radio" name="cipher" value="rc4">RC4<br>
        <input type="radio" name="cipher" value="desCip">DES cipher<br><br>
        <label>Fill The Text Or The File To Encrypt (Cannoth Both)<br><br></label>
		Text To Encrypt: <input type="text" name="textEncrypt"><br><br>
        File To Encrypt: <input type="file" name="content"><br><br>
        <button name="uploadButton">Encrypt</button><br><br>
    </form>
    <button onclick="window.location.href='form.php'">Back To Main Page</button>
_END;
    require_once 'login.php';
    require_once 'regular_function.php';
    require_once 'simpleSub_key.php';
    require_once 'rc4_key.php';
    require_once 'rc4_function.php';
    //Create A Connection With Database
	global $conn;
	$conn = new mysqli($hn, $un, $pw, $db);
	if ($conn->connect_errno) 
        echo "<br>The Connection Is Error<br>";
    //Upload Button
    if(isset($_POST['uploadButton'])){
        $check = false;
        $inputCipher = $_POST['cipher'];
        $inputText = $_POST['textEncrypt'];
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
                    //For Requirement
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
                    $finalText = encryptionSimpleSubstitution($content);
                else
                    echo "<br><br>Your Text Should Only Lower Case Letter And Space<br>";
            }
            else if($inputCipher == "doubleTra"){
                echo "<br><br>Using A Double Transposition Cipher:";
                $finalText = encryptionDoubleTransposition($content);
            } else if($inputCipher == "rc4"){
                echo "<br><br>Using A RC4 Cipher:";
                $finalText = encryptionrc4($content);
            }else if($inputCipher == "desCip"){
                echo "<br><br>Using A DES Cipher:";
                $finalText = encryptionDES($content);
            }
            $sql = "INSERT INTO input_table (text_input, cipher, timestamp) VALUES (NULL, '$inputCipher', '$timestamp')";
			mysqli_query($conn,$sql);
            if($isAFile)
                fclose($fileOutput);
            //clear the connection
            mysqli_close($conn);
        }
    }

    //encryption for simple substitution
    function encryptionSimpleSubstitution($input){
        $input = strtolower($input);
        $cipherText = "";
        $length = strlen($input);;
        for($i=0;$i<$length;$i++){
            $key = ord($input[$i]) - 96;
            if($key < 0){
                $key = 0;
                $cipherText .= getKey($key);
            } else 
            $cipherText .= getKey($key);
        }
        echo "<br><br>$cipherText";
        return $cipherText;
    }

    //encryption for Double Transposition
    function encryptionDoubleTransposition($input){
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
    function encryptionrc4($input){
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
        $outputFinal = bin2hex($outputTempt);
        echo "<br><br>$outputFinal";
        return $outputFinal;
    }

    //encryption for Double Transposition
    function encryptionDES($input){
        
        $pt= "123456ABCD132536"; 
        $key= "AABB09182736CCDD"; 
        //Hex to binary 
        $key= hex2binn($key); 
        //Parity bit drop table 
        $keyp = array (  
            57,49,41,33,25,17,9, 
            1,58,50,42,34,26,18, 
            10,2,59,51,43,35,27, 
            19,11,3,60,52,44,36,           
            63,55,47,39,31,23,15, 
            7,62,54,46,38,30,22, 
            14,6,61,53,45,37,29, 
            21,13,5,28,20,12,4 
        );
        //getting 56 bit key from 64 bit using the parity bits 
        $key= permute($key, $keyp, 56);
        // echo strlen($key);
        //echo $key;
        
        //Number of bit shifts  
        $shift_table  = array(
            1, 1, 2, 2, 
            2, 2, 2, 2,  
            1, 2, 2, 2,  
            2, 2, 2, 1 
        );  
        //Key- Compression Table 
        $key_comp  = array( 
            14,17,11,24,1,5, 
            3,28,15,6,21,10, 
            23,19,12,4,26,8, 
            16,7,27,20,13,2, 
            41,52,31,37,47,55, 
            30,40,51,45,33,48, 
            44,49,39,56,34,53, 
            46,42,50,36,29,32 
        );
        //Splitting 
        $left = substr($key, 0, 28);
        //echo "<br> $left";
        $right = substr($key, 28, 28);
        //echo "<br> $right";
        $inputrkb = array();
        $inputrk = array();
        
        for($i=0;$i<16;$i++){
            //Shifting 
            $left= shiftLeft($left, $shift_table[$i]); 
            $right= shiftLeft($right, $shift_table[$i]); 
            
            //Combining 
            $combine = "";
            $combine .= $left;
            $combine .= $right;
            //echo "<br> $combine";
            //Key Compression 
            $RoundKey= permute($combine, $key_comp, 48); 
            //echo "<br> $RoundKey";
            // $inputrkb.push_back($RoundKey); 
            // $inputrk.push_back(bin2hex($RoundKey));
            array_push($inputrkb,$RoundKey); 
            array_push($inputrk,bin2hexx($RoundKey));
            //echo "<br> $inputrkb[$i] $i susu";
            //echo "<br> $inputrk[$i] $i kuda";
        }
        //print_r($inputrkb);
        //print_r($inputrk);
        
        $finalOut = encrypt($pt,$inputrkb,$inputrk);

        echo "<br><br>$finalOut";
        /*
        return $finalOut;
        */
    }
    
?>
