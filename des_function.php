<?php
    require_once 'regular_function.php';
    require_once 'des_key.php';
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
    function encrypt($input, $inputrkb, $inputrk){
        $input = hex2binn($input);
        $input = permute($input,getInitialPermutation(), 64);
        $left = substr($input, 0, 32);
        $right = substr($input, 32, 32);
        for($i=0;$i<16;$i++){
            $right_expand = permute($right, getComp1(), 48);
            $x = func_xor($inputrkb[$i],$right_expand);
            $op = "";
            for($j=0;$j<8;$j++){
                $row= 2*intval($x[$j*6])+ intval($x[$j*6 +5]); 
                $col= 8*intval($x[$j*6+1])+ 4*intval($x[$j*6+2])+ 2*intval($x[$j*6+3])+ intval($x[$j*6+4]); 
                $val = getComp2($j,$row,$col); 
                $a = $val/8;
                $a = intval($a);
                $op .= (string)$a; 
                $val= $val%8; 
                $b = $val/4;
                $b = intval($b);
                $op .= (string)$b;
                $val= $val%4; 
                $c = $val/2;
                $c = intval($c);
                $op .= (string)$c;
                $val= $val%2; 
                $d = intval($val);
                $op .= (string)$val; 
            } 
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
        $cipher = bin2hexx(permute($combine, getFinalPermutation(), 64));
        return $cipher;
    }
    function decrypt($input, $inputrkb, $inputrk){
        $inputrkb = Reverse($inputrkb);
        $inputrk = Reverse($inputrk);
        $input = hex2binn($input);
        $input = permute($input,getInitialPermutation(), 64);
        $left = substr($input, 0, 32);
        $right = substr($input, 32, 32);
        for($i=0;$i<16;$i++){
            $right_expand = permute($right, getComp1(), 48);
            $x = func_xor($inputrkb[$i],$right_expand);
            $op = "";
            for($j=0;$j<8;$j++){
                $row= 2*intval($x[$j*6])+ intval($x[$j*6+5]); 
                $col= 8*intval($x[$j*6+1])+ 4*intval($x[$j*6+2])+ 2*intval($x[$j*6+3])+ intval($x[$j*6+4]); 
                $val= getComp2($j,$row,$col); 
                $a = $val/8;
                $a = intval($a);
                $op .= (string)$a; 
                $val= $val%8; 
                $b = $val/4;
                $b = intval($b);
                $op .= (string)$b;
                $val= $val%4; 
                $c = $val/2;
                $c = intval($c);
                $op .= (string)$c;
                $val= $val%2; 
                $d = intval($val);
                $op .= (string)$val; 
            } 
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
        $cipher = bin2hexx(permute($combine, getFinalPermutation(), 64));
        return $cipher;
    }
    
?>