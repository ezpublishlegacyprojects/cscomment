<?php
$module = $Params['Module'];
$http = eZHTTPTool::instance();

foreach ($http->PostVariable('DeleteIDArray') as $DeleteID)
{
    $DataID = explode('_',$DeleteID);
	CSComment::removeByCommentID($DataID[0],$DataID[1]);
}

$module->redirectToView( 'adminlist', array( $http->PostVariable('ContentNodeID') ) );
?>