<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Forum class
 */

Class Pex {

	
} 
 
 
class PexExtern {
	
	private $_db,
			$_data,
			$_language,
			$_prefix;
	
	public function __construct($inf_db) {
		
		
		$this->_db = DB_Custom::getInstance($inf_db['address'], $inf_db['name'], $inf_db['username'], $inf_db['password']);
		$this->_prefix = $inf_db['prefix'];
		//$this->_language = $language;
	}

	
/*  	public	function otherDB() {
		$pex_sql = $queries->orderAll('module_pex', 'ID');
		foreach($pex_sql as $pex_data){
			
			$this->_db = DB_Custom::getInstance($pex_data->pex_database_adress, $pex_data->pex_database_dbname, $pex_data->pex_database_username, $pex_data->pex_database_password);
			$this->_prefix = $pex_data->pex_database_prefix;
			$this->_language = $language;
			
			echo 'DB wurde geladen';
		}
	} */

}
