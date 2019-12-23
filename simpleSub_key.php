<!-- 
	Project Name: Decryptoid
	Student Name: 
		Gregory Mayo, 013422357
		Kevin Prakasa, 012255087
 -->
 <?php
    function getKey($input){
        $keyCipher = array(' ','z','h','q','e',
                        'y','f','i','g',
                        'j','r','a','v',
                        'w','u','o','b',
                        'k','m','l','c',
                        'n','d','p', 't',
                        's','x');
        return $keyCipher[$input];
    }
    function getOriginalKey($input){
        $keyCipher = array();
        $keyCipher[" "]= 0;
        $keyCipher["z"]= 1;
        $keyCipher["h"]= 2;
        $keyCipher["q"]= 3;
        $keyCipher["e"]= 4;
        $keyCipher["y"]= 5;
        $keyCipher["f"]= 6;
        $keyCipher["i"]= 7;
        $keyCipher["g"]= 8;
        $keyCipher["j"]= 9;
        $keyCipher["r"]= 10;
        $keyCipher["a"]= 11;
        $keyCipher["v"]= 12;
        $keyCipher["w"]= 13;
        $keyCipher["u"]= 14;
        $keyCipher["o"]= 15;
        $keyCipher["b"]= 16;
        $keyCipher["k"]= 17;
        $keyCipher["m"]= 18;
        $keyCipher["l"]= 19;
        $keyCipher["c"]= 20;
        $keyCipher["n"]= 21;
        $keyCipher["d"]= 22;
        $keyCipher["p"]= 23;
        $keyCipher["t"]= 24;
        $keyCipher["s"]= 25;
        $keyCipher["x"]= 26;
        return $keyCipher[$input];
    }
    function getLetterKey($input){
        $keyCipherOri = array(' ','a','b','c','d',
                        'e','f','g','h',
                        'i','j','k','l',
                        'm','n','o','p',
                        'q','r','s','t',
                        'u','v','w', 'x',
                        'y','z');
        return $keyCipherOri[$input];
    }
?>