<?php
$http = eZHTTPTool::instance();

/* Validation starts*/
$inpValObj = new inputvalid(
		array(
			'username' 		=> 	$http->postVariable('username'),
			'comment' 		=> 	$http->postVariable('comment'),
			'email' 		=>	$http->postVariable('email'),
			'captcha' 		=>	$http->postVariable('captcha')
		)
	);

$Error = array();

$INI   = eZINI::instance( 'cscomment.ini.php' );	

$inpValObj->setMinMaxLenghts($INI->variable('CSComment', 'MinUsername'),$INI->variable('CSComment', 'MaxUsername'));

$result = $inpValObj->validateLenghts(array('username'));
if (in_array('username',$result))
		$Error[] = ezi18n( 'cscomment/addcomment','Incorrect name!');
		
$inpValObj->setMinMaxLenghts($INI->variable('CSComment', 'MinMessageLenght'),$INI->variable('CSComment', 'MaxMessageLenght'));		
$result = $inpValObj->validateLenghts(array('comment'));
	
if (in_array('comment',$result))
	$Error[] = ezi18n( 'cscomment/addcomment','Please enter comment text!');		
		
if ($inpValObj->issetvalue('email') && !$inpValObj->isValidEmail('email'))	
	$Error[] = ezi18n( 'cscomment/addcomment','Incorrect e-mail!');
		
if ($_SESSION[$_SERVER['REMOTE_ADDR']]['addcomment_phrase'] != $inpValObj->getInputValue('captcha'))
	$Error[] = ezi18n( 'cscomment/addcomment','Incorrect captcha code!');
	
/* Validation ends */	

$node = eZContentObjectTreeNode::fetch($http->PostVariable('node_id'));	

if (!is_object($node))
{
	$Error[] = ezi18n( 'cscomment/addcomment','Node not found!');
}

$object = $node->attribute( 'object' );

if ( !is_object( $object ) )
{
    return  array( 'content' => $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' ),
                   'store'   => false );
}

if ( !$object instanceof eZContentObject )
{
    return  array( 'content' => $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' ),
                   'store'   => false );
}
if ( $node === null )
{
    return  array( 'content' => $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' ),
                   'store'   => false );
}

if ( $object === null )
{
    return  array( 'content' => $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' ),
                   'store'   => false );
}

if ( $node->attribute( 'is_invisible' ) && !eZContentObjectTreeNode::showInvisibleNodes() )
{
    return array( 'content' => $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' ),
                  'store'   => false );
}

if ( !$object->canRead() )
{
    return array( 'content' => $Module->handleError( eZError::KERNEL_ACCESS_DENIED,
                                                     'kernel',
                                                     array( 'AccessList' => $object->accessList( 'read' ) ) ),
                  'store'   => false );
}	
	
include_once( "kernel/common/template.php" );
$tpl = templateInit(); 
	
if (count($Error) > 0)
{
	$tpl->setVariable('errors',$Error);
	echo json_encode(array('result' => $tpl->fetch( "design:cscomment/validation_error.tpl" ) , 'error' => 'true'));
} else {

	$comment = new CSComment(
		array(
			'name' => $http->PostVariable('username'),
			'message' => $http->PostVariable('comment'),
			'email' => $http->PostVariable('email'),
			'date' => time(),
			'ip' => isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'],
			'replytocomment' => 0,
			'node_id' => $http->PostVariable('node_id')
		)
	);
	$comment->store();
		
	CSCommentClean::addDelayCacheClear($node->attribute('contentobject_id'));
		
	//Confirmation message
	$tpl->setVariable('comment',$comment);	
	$tplcommentstored = templateInit();	
	
	echo json_encode(array('result' => $tpl->fetch( "design:cscomment/addcomment.tpl" ) , 'error' => 'false', 'feedback' => $tplcommentstored->fetch( "design:cscomment/comment_stored.tpl" ) ));
}

eZExecution::setCleanExit( );
exit;
?>