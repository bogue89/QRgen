<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

include_once("QRgen.php");

$config = array(
	'size'		=> 	7,
	'black'		=> 	array(80,207,121),
	'white'		=> 	array(255,255,255),
	//'savename'	=>	'test.png',
	'logo'		=> 	"http://random.jorgebenavides.com/ico/72-logo.png"
);

$qrgen = new QRgen($config);
$image = $qrgen->generate("Lorem ipsum dolor sit amet...");
if($image)
	header('Content-type: image/png');
echo $image;
exit;