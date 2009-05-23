<?php

//Get id and clean object
$list = CSCommentClean::fetchList();
foreach ($list as $id)
{
	eZContentCacheManager::clearContentCache( $id['contentobject_id'] );
	$CleanScript = CSCommentClean::fetch($id['contentobject_id']);
	$CleanScript->remove();	    		
}

?>