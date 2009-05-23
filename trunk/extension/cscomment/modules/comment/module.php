<?php
//
// Created on: <30-Jul-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor extension for eZ Publish
// SOFTWARE RELEASE: 5.0
// COPYRIGHT NOTICE: Copyright (C) 2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
// 
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
// 
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
// 
// 
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//


$Module = array( 'name' => 'CS Coral solutions comments module ajax powered', 'variable_params' => true );

$ViewList = array();
$ViewList['list'] = array(
    'functions' => array( 'read' ),
    'script' => 'list.php',
    'params' => array( 'NodeID' )
);

$ViewList['adminlist'] = array(
    'functions' => array( 'administrate' ),
    'script' => 'adminlist.php',
    'ui_context' => 'administration',
    'default_navigation_part' => 'ezcontentnavigationpart',
    'params' => array( 'NodeID' )
);

$ViewList['delete'] = array(
    'functions' => array( 'delete' ),
    'script' => 'delete.php',
    'ui_context' => 'administration',
    'default_navigation_part' => 'ezcontentnavigationpart',
    'params' => array()
);
    
$ViewList['showform'] = array(
    'functions' => array( 'read' ),
    'script' => 'showform.php',
    'params' => array()
); 
       
$ViewList['addreplytocoment'] = array(
    'functions' => array( 'read' ),
    'script' => 'addreplytocoment.php',
    'params' => array()
);
    
$ViewList['addcomment'] = array(
    'functions' => array( 'read' ),
    'script' => 'addcomment.php',
    'params' => array()
); 
   
$ViewList['addcommentcaptcha'] = array(
    'functions' => array( 'read' ),
    'script' => 'addcommentcaptcha.php',
    'params' => array()
);
      
$ViewList['addreplycaptcha'] = array(
    'functions' => array( 'read' ),
    'script' => 'addreplycaptcha.php',
    'params' => array()
);


$FunctionList = array();
$FunctionList['read'] = array();
$FunctionList['create'] = array();
$FunctionList['delete'] = array();
$FunctionList['administrate'] = array();


?>