<?php
/**
 * PHP QR Code Helper class for http://phpqrcode.sourceforge.net/ library
 *
 * @package        	QRgen
 * @subpackage    	Library
 * @porting author	https://github.com/bogue
 * @original author	http://phpqrcode.sourceforge.net/ 
 *
 * @version		1.0.0
 *
 */
class QRgen
{
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
	
	function __construct($config = array()) 
	{
		$this->initialize($config);
	}
	
	public function initialize($config = array()) 
	{
		// replace default configurations if specifiy
		$this->config = array_merge($this->config, $config);

		// set cache use
		if(!defined('QR_CACHEABLE')) 
		{
			define('QR_CACHEABLE', $this->config['cacheable']);
		}
		// set cache dir
		if(!defined('QR_CACHE_DIR')) 
			define('QR_CACHE_DIR', $this->config['cachedir']);
		
		// set error logs dir
		if(!defined('QR_LOG_DIR')) 
		{
			define('QR_LOG_DIR', $this->config['errorlog']);
		}
		
		// if true, estimates optimal mask 
		// set false to speed up performance
		if($this->config['quality'] && !defined('QR_FIND_BEST_MASK')) 
		{ 
			define('QR_FIND_BEST_MASK', true);
		} 
		else 
		{
			if(!defined('QR_FIND_BEST_MASK')) 
			{
				define('QR_FIND_BEST_MASK', false);
			}
			if(!defined('QR_DEFAULT_MASK')) 
			{
				define('QR_DEFAULT_MASK', $this->config['quality']);
			}
				
		}
		// if false, checks all masks available
		if(!defined('QR_FIND_FROM_RANDOM')) 
		{
			define('QR_FIND_FROM_RANDOM', false);
		}
		
		// maximum allowed png image width in pixels
		if(!defined('QR_PNG_MAXIMUM_SIZE'))
		{
			define('QR_PNG_MAXIMUM_SIZE',  1024);
		}
		// include phpqrcode.sourceforge library
		include_once('QRgen/qrconst.php');
		include_once('QRgen/qrtools.php');
		include_once('QRgen/qrspec.php');
		include_once('QRgen/qrimage.php');
		include_once('QRgen/qrinput.php');
		include_once('QRgen/qrbitstream.php');
		include_once('QRgen/qrsplit.php');
		include_once('QRgen/qrrscode.php');
		include_once('QRgen/qrmask.php');
		include_once('QRgen/qrencode.php');
	}
	
	public function generate($data, $config = array()) 
	{
		// replace configurations if specifiy for generate only
		$tmpConfig = array_merge($this->config, $config);
		
		// set QR colors
		QRimage::$black = $tmpConfig['white'];
		QRimage::$white = $tmpConfig['black']; 
		
		// apply valid level
		$tmpConfig['level'] = in_array($tmpConfig['level'], array('L','M','Q','H')) ? $tmpConfig['level']:$this->config['level'];
		
		// if logo generate temporaly for merge
		if($tmpConfig['logo']){
			$tmp = $tmpConfig['cachedir'].'tmp.png';
			QRcode::png($data, $tmp, 'H', $tmpConfig['size'], $tmpConfig['margin'], true);
			
			$logo = imagecreatefromstring(file_get_contents($tmpConfig['logo']));
			$QR = imagecreatefrompng($tmp);
			
			$QR_width = imagesx($QR);
			$QR_height = imagesy($QR);
			
			$logo_width = imagesx($logo);
			$logo_height = imagesy($logo);
			
			// Scale logo to fit in the QR Code
			$logo_qr_width = $QR_width/3*$tmpConfig['logoscale'];
			$scale = $logo_width/$logo_qr_width;
			$logo_qr_height = $logo_height/$scale;

			imagecopyresampled($QR, $logo, $QR_width/2-$logo_qr_width/2, $QR_height/2-$logo_qr_height/2, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
			
			ob_start();	
			imagepng($QR);
			imagedestroy($QR);
			$data = ob_get_contents();
			ob_end_clean();
		} else {
			ob_start();
			QRcode::png($data, $tmpConfig['savename'], $tmpConfig['level'], $tmpConfig['size'], $tmpConfig['margin'], true);
			$data = ob_get_contents();
			ob_end_clean();
		}
		
		return $tmpConfig['savename'] ? $tmpConfig['savename'] : $data;
	}
}