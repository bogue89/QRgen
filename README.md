PHP QR generator Code
=======================

Version 1
Jul 2014
by /bogue89



Intro
-----

This is a class for simple use and modification of the library found on http://phpqrcode.sourceforge.net/


###How to use

Include the QRgen.php file, and your ready to use it.

You can set several properties for the class to generate custom QRs like follow:
	
	var $config = array(
		'cacheable' => 	true,
		'cachedir' 	=> 	'QRgen/cache/',
		'errorlog' 	=>	'QRgen/logs/',
		'quality' 	=>	true,
		'size'		=> 	8,
		'level'		=> 	'M',
		'margin'	=> 	2, 
		'black'		=>	array(0,0,0),
		'white'		=>	array(255,255,255),
		'savename'	=>	NULL,
		'logo'		=>	NULL,
		'logoscale'	=>	0.7,
	);
	
pass it in the constructor for general setting
or in every ->generate($config) for just once configuration