<?php



class CSSharding extends eZPersistentObject{
				
	function CSSharding( $row )
    {
        $this->eZPersistentObject( $row );
    }		
    
	static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "node_id" => array( 'name' => 'NodeID',
                                                        'datatype' => 'string',
                                                        'default' => 0,
                                                        'required' => true )                                    
                                                              ),
                      'function_attributes' => array( ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "CSSharding",
                      "name" => "cssharding" );
    }
    
    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( CSSharding::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }
    
    static function getShardingTableByNodeID($nodeID, $defaultTable)
    {
        $INI   = eZINI::instance( 'cscomment.ini.php' );
        
        if ( $INI->variable('CSComment', 'shardingActive') == 'enabled')
        {	
            
            $sql = "SELECT id FROM cssharding WHERE node_id <= {$nodeID} ORDER BY node_id DESC LIMIT 1"; 
            $db = eZDB::instance(); 
    	    $resultArray = $db->arrayQuery( $sql, array('id') );  
    	   
    	    return 'cscomment_t'.$resultArray[0]['id'];
            
        } else {
            return $defaultTable;
        }
    }
     
}

?>