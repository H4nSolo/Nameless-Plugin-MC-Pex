<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Forum initialisation file
 */

// Ensure module has been installed
$module_installed = $cache->retrieve('module_pex');
if(!$module_installed){
	// Hasn't been installed
	// Need to run the installer
	
	
	// Install Database Table for Pex Settings
  	$data = $queries->tableExists('module_pex');
	if(empty($data)){
		$queries->createTable("module_pex", " `id` int(1) NOT NULL AUTO_INCREMENT, `pex_database_adress` varchar(15) NOT NULL, `pex_database_port` int(5) DEFAULT NULL, `pex_database_username` varchar(255) NOT NULL, `pex_database_password` varchar(255) NOT NULL, `pex_database_prefix` varchar(255) NULL, `pex_database_dbname` varchar(255) NOT NULL,  `pex_websend_enable` varchar(4) DEFAULT NULL,  `pex_websend_ip` varchar(15) NOT NULL,  `pex_websend_port` int(5) DEFAULT NULL, `pex_websend_password` VARCHAR(255) DEFAULT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $queries->create('module_pex', array(
                'id' => NULL,
                'pex_database_adress' => 'NULL',
                'pex_database_port' => '3306',
                'pex_database_username' => 'NULL',
                'pex_database_password' => 'NULL',
				'pex_database_prefix' => 'NULL',
                'pex_database_dbname' => 'NULL',
                'pex_websend_enable' => '0',
                'pex_websend_ip' => 'NULL',
                'pex_websend_port' => '0',
				'pex_websend_password' => 'NULL'
            ));
	}


	//die('Run the installer first!');
	
} else {

}

define('PEX', true);

// Initialise forum language
$pex_language = new Language('modules/MC-Pex/language', LANGUAGE);

// Define URLs which belong to this module
$pages->add('MC-Pex', '/admin/pex', 'pages/admin/pex.php');
$pages->add('MC-Pex', '/admin/pex_groups', 'pages/admin/pex_groups.php');
$pages->add('MC-Pex', '/admin/pex_user', 'pages/admin/pex_user.php');
$pages->add('MC-Pex', '/admin/pex_settings', 'pages/admin/pex_settings.php');


// Add link to navbar
//$navigation->add('forum', $forum_language->get('forum', 'forum'), URL::build('/forum'));

// Add link to admin sidebar
if(!isset($admin_sidebar)) $admin_sidebar = array();
$admin_sidebar['mc_pex'] = array(
	'title' => $pex_language->get('pex', 'pex_navlinktitle'),
	'url' => URL::build('/admin/pex')
);