<!-- 
	Project Name: Decryptoid
	Student Name: 
		Gregory Mayo, 013422357
		Kevin Prakasa, 012255087
 -->
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
        $i = 0;
        $keyPermutation = 6;
        while($i<16){
            $right_expand = permute($right, getComp1(), 48);
            $x = func_xor($inputrkb[$i],$right_expand);
            $op = "";
            $j = 0;
            $times=8;
            while($j<$times){
                $addNum = 0;
                $row= ($times/4)*intval($x[$j*$keyPermutation+$addNum]); 
                $addNum++;
                $col= ($times/1)*intval($x[$j*$keyPermutation+$addNum]);
                $addNum++;
                $col+= ($times/2)*intval($x[$j*$keyPermutation+$addNum]);
                $addNum++;
                $col+= ($times/4)*intval($x[$j*$keyPermutation+$addNum]);
                $addNum++;
                $col+= ($times/8)*intval($x[$j*$keyPermutation+$addNum]); 
                $addNum++;
                $row += intval($x[$j*$keyPermutation+$addNum]);
                $val = getComp2($j,$row,$col); 
                $a = $val/($times/1);
                $a = intval($a);
                $op .= (string)$a; 
                $val= $val%($times/1); 
                $b = $val/($times/2);
                $b = intval($b);
                $op .= (string)$b;
                $val= $val%($times/2); 
                $c = $val/($times/4);
                $c = intval($c);
                $op .= (string)$c;
                $val= $val%($times/4); 
                $d = intval($val);
                $op .= (string)$val; 
                $j++;
            } 
            $x = func_xor($op, $left); 
            $left = $x;
             //Swap
            if($i!= 15){ 
                $temp = $left;
                $left = $right;
                $right = $temp;
            } 
            $i++;
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
        $i=0;
        $keyPermutation = 6;
        while($i<16){
            $right_expand = permute($right, getComp1(), 48);
            $x = func_xor($inputrkb[$i],$right_expand);
            $op = "";
            $j=0;
            $times=8;
            while($j<$times){
                $addNum = 0;
                $row= ($times/4)*intval($x[$j*$keyPermutation+$addNum]);
                $addNum++;
                $col= ($times/1)*intval($x[$j*$keyPermutation+$addNum]);
                $addNum++;
                $col += ($times/2)*intval($x[$j*$keyPermutation+$addNum]);
                $addNum++;
                $col += ($times/4)*intval($x[$j*$keyPermutation+$addNum]);
                $addNum++;
                $col += ($times/8)*intval($x[$j*$keyPermutation+$addNum]); 
                $addNum++;
                $row += intval($x[$j*$keyPermutation+$addNum]); 
                $val= getComp2($j,$row,$col); 
                $a = $val/($times/1);
                $a = intval($a);
                $op .= (string)$a; 
                $val= $val%($times/1); 
                $b = $val/($times/2);
                $b = intval($b);
                $op .= (string)$b;
                $val= $val%($times/2); 
                $c = $val/($times/4);
                $c = intval($c);
                $op .= (string)$c;
                $val= $val%($times/4); 
                $d = intval($val);
                $op .= (string)$val; 
                $j++;
            } 
            $x = func_xor($op, $left); 
            $left = $x;
             //Swapper 
            if($i!= 15){ 
                $temp = $left;
                $left = $right;
                $right = $temp;
            }
            $i++; 
        }
        $combine = "";
        $combine .= $left;
        $combine .= $right;
        $cipher = bin2hexx(permute($combine, getFinalPermutation(), 64));
        return $cipher;
    }
    
?>