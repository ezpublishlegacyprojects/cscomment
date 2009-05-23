Author
======
(C) 2009 JSC Coral solutions
Remigijus Kiminas 
remdex@gmail.com


1. php ./bin/php/ezpgenerateautoloads.php
/************************************************/

2. Activate extension in site.ini (cscomment)
[ExtensionSettings]
ActiveExtensions[]=cscomment
/************************************************/

3. Grant anonymous function create / read
/************************************************/

4. Because ezPublish lacks of remove trigger, this can by avoided by adding these lines in removeobject.php file near 96 line.
IMPORTANT. It's kernel hack...

/*ACTION CS COMMENT*/
foreach ($deleteIDArray as $nodeIDCSRemove)
{
	CSComment::removeByNodeID($nodeIDCSRemove);
}
/*ACTION CS COMMENT*/ 


It should look like:
if ( $http->hasPostVariable( "ConfirmButton" ) or
     $hideRemoveConfirm )
{
    
    foreach ($deleteIDArray as $nodeIDCSRemove)
    {
    	CSComment::removeByNodeID($nodeIDCSRemove);
    }
    ..............
/************************************************/

5. If using sharding o delay clean cache feature cronjob must be running.  

Execute this command every five minits. Cleans delay cache.
php runcronjobs.php cscomment
     
Can be run one time in a day.
php runcronjobs.php cssharding

/************************************************/
6. Create initial comments tables.

CREATE TABLE IF NOT EXISTS `cscleancomment` (
  `contentobject_id` int(11) NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  KEY `id` (`id`),
  KEY `contentobject_id` (`contentobject_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cscomment`
--

CREATE TABLE IF NOT EXISTS `cscomment` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cscomment_t1`
--

CREATE TABLE IF NOT EXISTS `cscomment_t1` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------


--
-- Table structure for table `cssharding`
--

CREATE TABLE IF NOT EXISTS `cssharding` (
  `id` int(11) NOT NULL auto_increment,
  `node_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `node_id` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cssharding` (`id`, `node_id`) VALUES
(1, 1);