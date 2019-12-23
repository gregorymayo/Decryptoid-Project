<!-- 
	Project Name: Decryptoid
	Student Name: 
		Gregory Mayo, 013422357
		Kevin Prakasa, 012255087
 -->
 <?php
// Php file to sanitize

function get_post($conn, $var)
{
    return $conn->real_escape_string($_POST[$var]);
}

function sanitizeString($var) {
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

function sanitizeMySQL($connection, $var) {
    $var = $connection->real_escape_string($var);
    $var = sanitizeString($var);
    return $var;
}

function mysql_entities_fix_string($connection, $string)
{
    return htmlentities(mysql_fix_string($connection, $string));
}

function mysql_fix_string($connection, $string)
{
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return $connection->real_escape_string($string);
}

?>