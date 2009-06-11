<?php
$NodeID = $Params['NodeID'];

$Module = $Params['Module'];
$Offset = isset($Params['Offset']) ? (int)$Params['Offset'] : null;

if ( $Offset )
    $Offset = (int) $Offset;

if ( !is_numeric( $Offset ) )
    $Offset = 0;
    
$UserParameters = isset($UserParameters) ? $UserParameters : array();

$ArrayPagination = array('1' => 10 , '2' => 25, '3' => 50);

$viewParameters = array( 'offset' => $Offset,
                         'limit' => $ArrayPagination[eZPreferences::value('admin_list_limit') > 0 ? eZPreferences::value('admin_list_limit') : 1]
                       );
                       
$viewParameters = array_merge( $viewParameters, $UserParameters );

include_once( "kernel/common/template.php" );
                    
$tpl = templateInit(); 

/* PATH */
$node = eZContentObjectTreeNode::fetch( $NodeID );

if ( !is_object( $node ) )
{
    return  array( 'content' => $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' ),
                   'store'   => false );
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


$parents = $node->attribute( 'path' );
$path = array();
$titlePath = array();

foreach ( $parents as $parent )
{
    $path[] = array( 'text' => $parent->attribute( 'name' ),
                     'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                     'url_alias' => $parent->attribute( 'url_alias' ),
                     'node_id' => $parent->attribute( 'node_id' ) );
}	
				
$path[] = array( 'text' => $node->attribute( 'name' ),
                         'url' => '/content/view/full/' . $node->attribute( 'node_id' ),
                         'url_alias' => $node->attribute( 'url_alias' ),
                         'node_id' => $node->attribute( 'node_id' ) );
                         
$path[] = array( 'text' => ezi18n( 'cscomment/adminlist','Comments'),
                         'url' => false,
                         'url_alias' => false,
                         'node_id' => $node->attribute( 'node_id' ) );
                         

/* PATH */		                         
$Result['path'] = $path;



$listComments = CSComment::fetchList($viewParameters['limit'],$viewParameters['offset'],$NodeID);

$tpl->setVariable('node',$node);

$tpl->setVariable('list',$listComments);
$tpl->setVariable('node_id',$NodeID);
$tpl->setVariable('children_count',CSComment::fetchListCount($NodeID));
$tpl->setVariable('view_parameters',$viewParameters);
$tpl->setVariable('page_limit',10);

$Result['content'] = $tpl->fetch( "design:cscomment/adminlist.tpl" );

?>