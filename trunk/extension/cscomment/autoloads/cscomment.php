<?php

class CSCommentValues
{
    
    function operatorList()
    {
        return array( 'cscommentcount');
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {   
        	
        return array( 	   'cscommentcount' =>  array (		'node_id' => array( 'type' => 'int',
                                                          	'required' => true,
                                                          	'default' => array() )
                                                          	)                                                 
                                                 
                                                 );
    }

    function modify( $tpl, $operatorName, $operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
                
        switch ( $operatorName )
        {
            case 'cscommentcount':
            {             	
                $operatorValue = CSComment::fetchListCount($namedParameters['node_id']);
                
            } break;
                        	
        }
    }
}

?>