<?php



class CSComment extends eZPersistentObject{
				
	function CSComment( $row )
    {
        $this->eZPersistentObject( $row );
    }
	 
    static function definition()
    {
     
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => 'Name',
                                                        'datatype' => 'string',
                                                        'default' => 0,
                                                        'required' => true ), 
                                         "message" => array( 'name' => 'Message',
                                                        'datatype' => 'string',
                                                        'default' => '',
                                                        'required' => true ),                                        
                                         "date" => array( 'name' => "Date",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true ),  
                                         "ip" => array( 'name' => "Ip",
                                                                'datatype' => 'string',
                                                                'default' => 0,
                                                                'required' => false ),  
                                         "email" => array( 'name' => "Email",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => false ),  
                                         "node_id" => array( 'name' => "NodeID",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => false ),  
                                         "replytocomment" => array( 'name' => "ReplyToComment",
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => false )
                                                              ),
                      'function_attributes' => array(
                      	'subcount' => 'getCountSubcomment',
                      	'subcommentlist' => 'fetchSubcommentList'
                      ),
                      "keys" => array( "id" ),
                      "sharding_key" => "node_id",
                      "sharding_table" => "cssharding",
                                            
                      "increment_key" => "id",
                      "class_name" => "CSComment",
                      "name" => "cscomment" );
                      
    }
    /**
     * Taken from eZPersistentObject, because of need implementing sharding...
     * 
     * */
    function store($fieldFilters = null)
    {
        
        $obj = $this;
             
        $db = eZDB::instance();
        $useFieldFilters = ( isset( $fieldFilters ) && is_array( $fieldFilters ) && $fieldFilters );

        $def = $obj->definition();
          
        $fields = $def["fields"];
        $keys = $def["keys"];
        $table = CSSharding::getShardingTableByNodeID($this->attribute('node_id'),$def["name"]);
        
        $relations = isset( $def["relations"] ) ? $def["relations"] : null;
        $insert_object = false;
        $exclude_fields = array();
        foreach ( $keys as $key )
        {
            $value = $obj->attribute( $key );
            if ( is_null( $value ) )
            {
                $insert_object = true;
                $exclude_fields[] = $key;
            }
        }

        if ( $useFieldFilters )
            $insert_object = false;

        $use_fields = array_diff( array_keys( $fields ), $exclude_fields );
        // If we filter out some of the fields we need to intersect it with $use_fields
        if ( is_array( $fieldFilters ) )
            $use_fields = array_intersect( $use_fields, $fieldFilters );
        $doNotEscapeFields = array();
        $changedValueFields = array();
        $numericDataTypes = array( 'integer', 'float', 'double' );

        foreach ( $use_fields as $field_name  )
        {
            $field_def = $fields[$field_name];
            $value = $obj->attribute( $field_name );

            if ( is_null( $value ) )
            {
                if ( ! is_array( $field_def ) )
                {
                    $exclude_fields[] = $field_name;
                }
                else
                {
                    if ( array_key_exists( 'default', $field_def ) &&
                         (! is_null( $field_def['default'] ) ||
                          ( $field_name == 'data_int' &&
                            array_key_exists( 'required', $field_def ) &&
                            $field_def[ 'required' ] == false ) ) )
                    {
                        $obj->setAttribute( $field_name, $field_def[ 'default' ] );
                    }
                    else
                    {
                        //if ( in_array( $field_def['datatype'], $numericDataTypes )
                        $exclude_fields[] = $field_name;
                    }
                }
            }

            if ( strlen( $value ) == 0 &&
                 is_array( $field_def ) &&
                 in_array( $field_def['datatype'], $numericDataTypes  ) &&
                 array_key_exists( 'default', $field_def ) &&
                 ( is_null( $field_def[ 'default' ] ) || is_numeric( $field_def[ 'default' ] ) ) )
            {
                $obj->setAttribute( $field_name, $field_def[ 'default' ] );
            }

            if ( !is_null( $value )                             &&
                 $field_def['datatype'] === 'string'            &&
                 array_key_exists( 'max_length', $field_def )   &&
                 $field_def['max_length'] > 0                   &&
                 strlen( $value ) > $field_def['max_length'] )
            {
                $obj->setAttribute( $field_name, substr( $value, 0, $field_def['max_length'] ) );
                eZDebug::writeDebug( $value, "truncation of $field_name to max_length=". $field_def['max_length'] );
            }
            $bindDataTypes = array( 'text' );
            if ( $db->bindingType() != eZDBInterface::BINDING_NO &&
                 strlen( $value ) > 2000 &&
                 is_array( $field_def ) &&
                 in_array( $field_def['datatype'], $bindDataTypes  )
                 )
            {
                $boundValue = $db->bindVariable( $value, $field_def );
//                $obj->setAttribute( $field_name, $value );
                $doNotEscapeFields[] = $field_name;
                $changedValueFields[$field_name] = $boundValue;
            }

        }
        $key_conds = array();
        foreach ( $keys as $key )
        {
            $value = $obj->attribute( $key );
            $key_conds[$key] = $value;
        }
        unset( $value );

        $important_keys = $keys;
        if ( is_array( $relations ) )
        {
//            $important_keys = array();
            foreach( $relations as $relation => $relation_data )
            {
                if ( !in_array( $relation, $keys ) )
                    $important_keys[] = $relation;
            }
        }
        if ( count( $important_keys ) == 0 && !$useFieldFilters )
        {
            $insert_object = true;
        }
        else if ( !$insert_object )
        {
            $rows = eZPersistentObject::fetchObjectList( $def, $keys, $key_conds,
                                                          array(), null, false,
                                                          null, null );
            if ( count( $rows ) == 0 )
            {
                /* If we only want to update some fields in a record
                 * and that records does not exist, then we should do nothing, only return.
                 */
                if ( $useFieldFilters )
                    return;

                $insert_object = true;
            }
        }

        if ( $insert_object )
        {
            // Note: When inserting we cannot hone the $fieldFilters parameters

            $use_fields = array_diff( array_keys( $fields ), $exclude_fields );
            $use_field_names = $use_fields;
            if ( $db->useShortNames() )
            {
                $use_short_field_names = $use_field_names;
                eZPersistentObject::replaceFieldsWithShortNames( $db, $fields, $use_short_field_names );
                $field_text = implode( ', ', $use_short_field_names );
                unset( $use_short_field_names );
            }
            else
                $field_text = implode( ', ', $use_field_names );

            $use_values_hash = array();
            $escapeFields = array_diff( $use_fields, $doNotEscapeFields );

            foreach ( $escapeFields as $key )
            {
                $value = $obj->attribute( $key );
                $field_def = $fields[$key];

                if ( $field_def['datatype'] == 'float' || $field_def['datatype'] == 'double' )
                {
                    if ( is_null( $value ) )
                    {
                        $use_values_hash[$key] = 'NULL';
                    }
                    else
                    {
                        $use_values_hash[$key] = sprintf( '%F', $value );
                    }
                }
                else if ( $field_def['datatype'] == 'int' || $field_def['datatype'] == 'integer' )
                {
                    if ( is_null( $value ) )
                    {
                        $use_values_hash[$key] = 'NULL';
                    }
                    else
                    {
                        $use_values_hash[$key] = sprintf( '%d', $value );
                    }
                }
                else
                {
                    // Note: for more colherence, we might use NULL for sql strings if the php value is NULL and not an empty sring
                    //       but to keep compatibility with ez db, where most string columns are "not null default ''",
                    //       and code feeding us a php null value without meaning it, we do not.
                    $use_values_hash[$key] = "'" . $db->escapeString( $value ) . "'";
                }
            }
            foreach ( $doNotEscapeFields as $key )
            {
                $use_values_hash[$key] = $changedValueFields[$key];
            }
            $use_values = array();
            foreach ( $use_field_names as $field )
                $use_values[] = $use_values_hash[$field];
            unset( $use_values_hash );
            $value_text = implode( ", ", $use_values );

            $sql = "INSERT INTO $table ($field_text) VALUES($value_text)";
            $db->query( $sql );

            if ( isset( $def["increment_key"] ) &&
                 is_string( $def["increment_key"] ) &&
                 !( $obj->attribute( $def["increment_key"] ) > 0 ) )
            {
                $inc = $def["increment_key"];
                $id = $db->lastSerialID( $table, $inc );
                if ( $id !== false )
                    $obj->setAttribute( $inc, $id );
            }
        }
        else
        {
            $use_fields = array_diff( array_keys( $fields ), array_merge( $keys, $exclude_fields ) );
            if ( count( $use_fields ) > 0 )
            {
                // If we filter out some of the fields we need to intersect it with $use_fields
                if ( is_array( $fieldFilters ) )
                    $use_fields = array_intersect( $use_fields, $fieldFilters );
                $use_field_names = array();
                foreach ( $use_fields as $key )
                {
                    if ( $db->useShortNames() && is_array( $fields[$key] ) && array_key_exists( 'short_name', $fields[$key] ) && strlen( $fields[$key]['short_name'] ) > 0 )
                        $use_field_names[$key] = $fields[$key]['short_name'];
                    else
                        $use_field_names[$key] = $key;
                }

                $field_text = "";
                $field_text_len = 0;
                $i = 0;


                foreach ( $use_fields as $key )
                {
                    $value = $obj->attribute( $key );

                    if ( $fields[$key]['datatype'] == 'float' || $fields[$key]['datatype'] == 'double' )
                    {
                        if (is_null($value))
                            $field_text_entry = $use_field_names[$key] . '=NULL';
                        else
                            $field_text_entry = $use_field_names[$key] . "=" . sprintf( '%F', $value );
                    }
                    else if ($fields[$key]['datatype'] == 'int' || $fields[$key]['datatype'] == 'integer' )
                    {
                        if (is_null($value))
                            $field_text_entry = $use_field_names[$key] . '=NULL';
                        else
                            $field_text_entry = $use_field_names[$key] . "=" . sprintf( '%d', $value );
                    }
                    else if ( in_array( $use_field_names[$key], $doNotEscapeFields ) )
                    {
                        $field_text_entry = $use_field_names[$key] . "=" .  $changedValueFields[$key];
                    }
                    else
                    {
                        $field_text_entry = $use_field_names[$key] . "='" . $db->escapeString( $value ) . "'";
                    }

                    $field_text_len += strlen( $field_text_entry );
                    $needNewline = false;
                    if ( $field_text_len > 60 )
                    {
                        $needNewline = true;
                        $field_text_len = 0;
                    }
                    if ( $i > 0 )
                        $field_text .= "," . ($needNewline ? "\n    " : ' ');
                    $field_text .= $field_text_entry;
                    ++$i;
                }
                $cond_text = eZPersistentObject::conditionText( $key_conds );
                $sql = "UPDATE $table\nSET $field_text$cond_text";
                $db->query( $sql );
            }
        }
        $obj->setHasDirtyData( false );
    }
    
    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( CSComment::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }
      
    function fetchSubcommentList()
    {
        
        $tableName = CSSharding::getShardingTableByNodeID($this->attribute('node_id'),'cscomment');
        
    	$query = "SELECT {$tableName}.* FROM {$tableName} WHERE replytocomment = {$this->attribute('id')} ORDER BY date DESC";
    	
    	$db = eZDB::instance(); 
    	$resultArray = $db->arrayQuery( $query );  
    	
    	$Array = array();
    	foreach ($resultArray as $ArrayItem)
    	{
    		$Array[] = new CSComment($ArrayItem);
    	}
    	
    	return $Array;
    }
    
    static function fetchListCount($nodeID)
    {
    	
    	$ArchiveFilter = array('node_id' => array('=',$nodeID));
    
    	
    	$tableName = CSSharding::getShardingTableByNodeID($nodeID,'cscomment');
    	
    	$def = CSComment::definition();
    	$def['name'] = $tableName;
    	
    	 $rows = eZPersistentObject::fetchObjectList( $def,
                                                        array(),
                                                        $ArchiveFilter,
                                                        null,
                                                        null,
                                                        null,
                                                        null,
                                                        array( array( 'operation' => 'count( id )',
                             							'name' => 'count' ) ) );       
       return $rows[0]['count'];
    }

    static function removeByCommentID($commentID,$node_id)
    {
        $tableName = CSSharding::getShardingTableByNodeID($node_id,'cscomment');
        
    	$query = 
    	"DELETE 
    	{$tableName}.*,
		subcom.*		
		FROM {$tableName}		
		LEFT JOIN {$tableName} subcom ON subcom.replytocomment = {$tableName}.id		
		WHERE {$tableName}.id = {$commentID}";
    	$db = eZDB::instance(); 
		$db->query($query);	
    	
    }
    
    static function fetchList($limit, $offset, $node_id) 
    {    	    	
        $tableName = CSSharding::getShardingTableByNodeID($node_id,'cscomment');
        
    	$query = "SELECT {$tableName} . *, count( subcom.replytocomment ) AS subcount
		FROM `{$tableName}`
		LEFT JOIN {$tableName} subcom ON {$tableName}.id = subcom.replytocomment
		WHERE {$tableName}.node_id = {$node_id}
		AND {$tableName}.replytocomment =0
		GROUP BY {$tableName}.id
		ORDER BY {$tableName}.date DESC
		LIMIT $offset,$limit ";
         
        	  	
    	$db = eZDB::instance(); 
    	$resultArray = $db->arrayQuery( $query );  
    	
    	$Array = array();
    	foreach ($resultArray as $ArrayItem)
    	{
    		$CommentTmp = new CSComment($ArrayItem);  		
    		$CommentTmp->setSubcommentcount($ArrayItem['subcount']);
    		$Array[] = $CommentTmp;
    	}
    	
        return $Array;
    }
    
    function getCountSubcomment()
    {
    	return $this->subcomments;
    }
    
    static function removeByNodeID($NodeID)
    {
        $tableName = CSSharding::getShardingTableByNodeID($NodeID,'cscomment');
        
    	$query = 
    	"DELETE 
    	{$tableName}.*,
		subcom.*		
		FROM {$tableName}		
		LEFT JOIN {$tableName} subcom ON subcom.replytocomment = {$tableName}.id		
		WHERE {$tableName}.node_id = {$NodeID}";
    	
    	echo $query;exit;
    	
		$db = eZDB::instance(); 
		$db->query($query);		    	
    }
    
    function setSubcommentcount($count)
    {
    
    	$this->subcomments = $count;
    }
    
	var $subcomments = 0;
         
}

?>