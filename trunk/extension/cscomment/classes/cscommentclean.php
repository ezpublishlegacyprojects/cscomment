<?php



class CSCommentClean extends eZPersistentObject{
				
	function CSCommentClean( $row )
    {
        $this->eZPersistentObject( $row );
    }
		
    
	static function definition()
    {
        return array( "fields" => array("id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "contentobject_id" => array( 'name' => 'contentobject_ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true )),  
                                   
                      'function_attributes' => array(),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "CSCommentClean",
                      "name" => "cscleancomment" );
    }
    
    static function fetchList() 
    {    
    	$SQL = "SELECT contentobject_id FROM cscleancomment ";
       	$db = eZDB::instance();
    	$resultArray = $db->arrayQuery( $SQL );
    
    	return $resultArray;
    }
    
    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( CSCommentClean::definition(),
                                                null,
                                                array( "contentobject_id" => $id ),
                                                $asObject );
    }
    
    static function addDelayCacheClear($objectId)
    {
        $INI   = eZINI::instance( 'cscomment.ini.php' );
        
        if ( $INI->variable('CSComment', 'delayCacheClear') == 'enabled')
        {        
            if (!$obj = CSCommentClean::fetch($objectId))
            {
                $add_id = new CSCommentClean (array(		
        		  'contentobject_id' => $objectId
        		));	
        		$add_id->store();
            }
        } else {
            eZContentCacheManager::clearContentCache( $objectId );
        }
    }
}

?>