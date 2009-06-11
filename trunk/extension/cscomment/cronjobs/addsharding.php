<?php

echo "Checking if needed new sharding comment's table.\n";

$INI   = eZINI::instance( 'cscomment.ini.php' );
        
if ( $INI->variable('CSComment', 'shardingActive') == 'enabled')
{	
    //Current sharding
    $sql = "SELECT MAX(id) as shard_id FROM `cssharding`"; 
    $db = eZDB::instance(); 
    $resultArray = $db->arrayQuery( $sql, array('shard_id') );  
        
       
    $tableName = 'cscomment_t'.$resultArray[0]['shard_id'];

    $sql =  "SELECT count(*) as total FROM $tableName" ;
    $resultArray = $db->arrayQuery( $sql, array('total') );  
     
  
    
    //Need create new sharding table
    if ($resultArray[0]['total'] >= $INI->variable('ShardingData','ShardingStep'))
    {
              
        $resultMaxNodeID = $db->arrayQuery( "SELECT MAX(node_id) as node_max FROM ezcontentobject_tree", array('node_max') );  
               
        // Create sharding index
        $shard = new CSSharding(
                    array( 'node_id' => $resultMaxNodeID[0]['node_max']+1 )
        );
        $shard->store();
        
        // Create sharding table
      $createIndexTable = "CREATE TABLE IF NOT EXISTS `cscomment_t{$shard->attribute('id')}` (
      `id` int(11) NOT NULL auto_increment,
      `name` varchar(100) NOT NULL,
      `message` text NOT NULL,
      `date` int(11) NOT NULL,
      `ip` varchar(100) NOT NULL,
      `email` varchar(255) NOT NULL,
      `node_id` int(11) NOT NULL,
      `replytocomment` int(11) NOT NULL default '0',
      PRIMARY KEY  (`id`),
      KEY `node_id` (`node_id`),
      KEY `node_id_root` (`node_id`,`replytocomment`),
      KEY `date` (`date`),
      KEY `replytocomment` (`replytocomment`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8";
      
      $db->query($createIndexTable);

    }
    
}

?>