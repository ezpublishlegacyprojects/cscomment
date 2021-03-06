<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1); 

require_once 'lib/ezfile/classes/ezfile.php';
require_once 'extension/cscomment/pear/Text/CAPTCHA.php';
require_once 'extension/cscomment/pear/Image/Text.php';
require_once 'extension/cscomment/pear/Image/Tools.php';
		 
$c = Text_CAPTCHA::factory('Image');
$c->init(150, 60, null,
array(

	  'font_path' => 'design/standard/fonts/',
	  'font_file' => 'arial.ttf',
	  'background_color' => '#FFFFFF',
	  'text_color' => '#0d52b1'
	  )

);


$_SESSION[$_SERVER['REMOTE_ADDR']]['addreply_phrase'] = $c->getPhrase();
			 
header('Content-type: image/jpeg');	
imagejpeg($c->getCAPTCHA());
 
eZExecution::setCleanExit( );
exit;
?>