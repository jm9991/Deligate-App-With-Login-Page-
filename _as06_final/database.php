<?php

// The Database class enables PHP to connect-to/disconnect-from MySQL database
class Database {
	//$code = 890;
	// declare and initialize variables for connect() function
	private static $dbName         = 'jmusarra355wi20' ; 
	private static $dbHost         = '10.8.30.49' ;
	private static $dbUsername     = 'jmusarra355wi20';
	private static $dbUserPassword = 'jmusarradbwi20v1l';
	
	// declare and initialize PDO instance variable: $connection
	private static $connection  = null;
	
	// method: __construct()
	public function __construct() {
		exit('No constructor required for class: Database');
	} 
	
	// method: connect()
	public static function connect() {
		if (null == self::$connection) {      
			try {
				self::$connection =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);  
			}
			catch(PDOException $e) { die($e->getMessage()); }
		} 	
		// echo "Connected."; exit(); // uncomment to test database connection
		return self::$connection;
	} 
	
	// method: disconnect()
	public static function disconnect() {
		self::$connection = null;
	} 
	
} // end class: Database
?>