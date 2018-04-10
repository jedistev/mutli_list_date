<?php
/**
 * Parses and verifies the doc comments for files.
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Steven Smith <stevenpetersmi@gmail.com>
 * @copyright 2018 Ishka Ltd (nope)
 * @license   https://github.com/ BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "amazon";

$conn = mysqli_connect($servername, $username, $password, $dbname) 
    or die("Connection failed: " . mysqli_connect_error());
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>


