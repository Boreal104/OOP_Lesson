<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');

include("class.php");

$from = isset($_GET['from']) ? $_GET['from'] : 0;
$to = $_GET['to'];
$step = $_GET['step'];
$method = $_GET['meth'];

if ($to) {
    $var = new TXPO($from, $to, $step, $method);
} else {
    $var = new TXPO($from, $from, null, 1);
}

?>