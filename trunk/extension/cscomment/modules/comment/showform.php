<?


include_once( "kernel/common/template.php" );
$tpl = templateInit(); 


$http = eZHTTPTool::instance();

$tpl->setVariable('comment_reply',$http->PostVariable('postid'));
$tpl->setVariable('node_reply',$http->PostVariable('nodeid'));

echo $tpl->fetch( "design:cscomment/showform.tpl" );

eZExecution::setCleanExit( );
exit;
?>