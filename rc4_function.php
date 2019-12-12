<?php
    
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

    function permute($k,$arr,$n){
        $temp = "";
        for($i=0;$i<$n;$i++){
            $temp .= $k[$arr[$i]-1];
        }
        return $temp;
    }
      
    function shiftLeft($k,$shift){
        $s = "";
        for($i=0;$i<$shift;$i++){
            for($j=1;$j<28;$j++){
                $s .= $k[$j];
            }
            $s .= $k[0];
            $k = $s;
            $s ="";
        }
        return $k;
    }

    function func_xor($inputA,$inputB){
        $temp = "";
        for($i=0;$i<strlen($inputA);$i++){
            if($inputA[$i]==$inputB[$i])
                $temp .= "0";
            else
                $temp .= "1";
        }
        return $temp;
    }
    function toNumber($dest)
    {
        if ($dest)
            return ord(strtolower($dest));
        else
            return 0;
    }
    function encrypt($input, $inputrkb, $inputrk){
        $input = hex2binn($input);
        //Initial Permutation Table 
        $initialPermutation = array(
            58,50,42,34,26,18,10,2, 
            60,52,44,36,28,20,12,4, 
            62,54,46,38,30,22,14,6, 
            64,56,48,40,32,24,16,8, 
            57,49,41,33,25,17,9,1, 
            59,51,43,35,27,19,11,3, 
            61,53,45,37,29,21,13,5, 
            63,55,47,39,31,23,15,7 
        );
        $input = permute($input,$initialPermutation, 64);
        $left = substr($input, 0, 32);
        $right = substr($input, 32, 32);
        //Expansion D-box Table 
        $exp_d = array(
            32,1,2,3,4,5,4,5, 
            6,7,8,9,8,9,10,11, 
            12,13,12,13,14,15,16,17, 
            16,17,18,19,20,21,20,21, 
            22,23,24,25,24,25,26,27, 
            28,29,28,29,30,31,32,1 
            ); 
        
        //S-box Table 
        $s = array ( 
                array( 
                    array(14,4,13,1,2,15,11,8,3,10,6,12,5,9,0,7), 
                    array(0,15,7,4,14,2,13,1,10,6,12,11,9,5,3,8), 
                    array(4,1,14,8,13,6,2,11,15,12,9,7,3,10,5,0), 
                    array(15,12,8,2,4,9,1,7,5,11,3,14,10,0,6,13)
                ), 
                array( 
                    array(15,1,8,14,6,11,3,4,9,7,2,13,12,0,5,10), 
                    array(3,13,4,7,15,2,8,14,12,0,1,10,6,9,11,5), 
                    array(0,14,7,11,10,4,13,1,5,8,12,6,9,3,2,15), 
                    array(13,8,10,1,3,15,4,2,11,6,7,12,0,5,14,9)
                ),
                array( 
                    array(10,0,9,14,6,3,15,5,1,13,12,7,11,4,2,8), 
                    array(13,7,0,9,3,4,6,10,2,8,5,14,12,11,15,1), 
                    array(13,6,4,9,8,15,3,0,11,1,2,12,5,10,14,7), 
                    array(1,10,13,0,6,9,8,7,4,15,14,3,11,5,2,12 )
                ), 
                array( 
                    array(7,13,14,3,0,6,9,10,1,2,8,5,11,12,4,15), 
                    array(13,8,11,5,6,15,0,3,4,7,2,12,1,10,14,9), 
                    array(10,6,9,0,12,11,7,13,15,1,3,14,5,2,8,4), 
                    array(3,15,0,6,10,1,13,8,9,4,5,11,12,7,2,14)
                ), 
                array( 
                    array(2,12,4,1,7,10,11,6,8,5,3,15,13,0,14,9), 
                    array(14,11,2,12,4,7,13,1,5,0,15,10,3,9,8,6), 
                    array(4,2,1,11,10,13,7,8,15,9,12,5,6,3,0,14), 
                    array(11,8,12,7,1,14,2,13,6,15,0,9,10,4,5,3)
                ),
                array(
                    array(12,1,10,15,9,2,6,8,0,13,3,4,14,7,5,11), 
                    array(10,15,4,2,7,12,9,5,6,1,13,14,0,11,3,8), 
                    array(9,14,15,5,2,8,12,3,7,0,4,10,1,13,11,6), 
                    array(4,3,2,12,9,5,15,10,11,14,1,7,6,0,8,13)
                ), 
                array( 
                    array(4,11,2,14,15,0,8,13,3,12,9,7,5,10,6,1), 
                    array(13,0,11,7,4,9,1,10,14,3,5,12,2,15,8,6), 
                    array(1,4,11,13,12,3,7,14,10,15,6,8,0,5,9,2), 
                    array(6,11,13,8,1,4,10,7,9,5,0,15,14,2,3,12)
                ), 
                array( 
                    array(13,2,8,4,6,15,11,1,10,9,3,14,5,0,12,7), 
                    array(1,15,13,8,10,3,7,4,12,5,6,11,0,14,9,2), 
                    array(7,11,4,1,9,12,14,2,0,6,10,13,15,3,5,8), 
                    array(2,1,14,7,4,10,8,13,15,12,9,0,3,5,6,11)
                )
            );

            //Straight Permutation Table 
            $per[32] = array( 
                16,7,20,21, 
                29,12,28,17, 
                1,15,23,26, 
                5,18,31,10, 
                2,8,24,14, 
                32,27,3,9, 
                19,13,30,6, 
                22,11,4,25 
            ); 

        for($i=0;$i<16;$i++){
            $right_expand = permute($right, $exp_d, 48);
            $x = func_xor($inputrkb[$i],$right_expand);
            $op = "";
            
            for($j=0;$j<8;$j++){
                $row= 2*intval($x[$j*6])+ intval($x[$j*6 +5]); 
                $col= 8*intval($x[$j*6+1])+ 4*intval($x[$j*6 +2])+ 2*intval($x[$j*6 +3])+ intval($x[$j*6 +4]); 
                // echo "<br> $j";
                // echo "<br> $row";
                // echo "<br> $col";
                
                $val= $s[$j][$row][$col]; 
                // echo "<br> $val asu";
                $a = $val/8;
                $a = intval($a);
                //echo "<br> $a asu";
                $op .= (string)$a; 
                echo "<br> $op bebek";
                $val= $val%8; 

                $b = $val/4;
                $b = intval($b);
                $op .= (string)$b;
                $val= $val%4; 

                $c = $val/2;
                $c = intval($c);
                $op .= (string)$c
                ;

                $val= $val%2; 

                $d = intval($val);
                $op .= (string)$val; 
                
            } 
            echo "<br> $op asu";
            //Straight D-box 
            //$op = permute($op, $per, 32); 
          
            //XOR left and op 
            $x = func_xor($op, $left); 
          
            $left = $x;
             //Swapper 
            if($i!= 15){ 
                $temp = $left;
                $left = $right;
                $right = $temp;
            } 
        }
        
        $combine = "";
        $combine .= $left;
        $combine .= $right;


        //Final Permutation Table 
        $final_perm =  array(
            40,8,48,16,56,24,64,32, 
            39,7,47,15,55,23,63,31, 
            38,6,46,14,54,22,62,30, 
            37,5,45,13,53,21,61,29, 
            36,4,44,12,52,20,60,28, 
            35,3,43,11,51,19,59,27, 
            34,2,42,10,50,18,58,26, 
            33,1,41,9,49,17,57,25 
        );
        

        $cipher = bin2hexx(permute($combine, $final_perm, 64));
        return $cipher;
        
    }
    
?>