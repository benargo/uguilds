<?php
namespace BattlenetArmory;

Class SafePDO extends \PDO {
 /*
        public static function exception_handler($exception) {
            // Output the exception details
            die('Uncaught exception: '. $exception->getMessage());
        }
 */
        public function __construct() {
            $ci = get_instance();
            $config = $ci->config->item('battle.net');
/*
            // Temporarily change the PHP exception handler while we . . .
            set_exception_handler(array(__CLASS__, 'exception_handler'));
*/
            // . . . create a PDO object
            $driver = $config['db']['driver'];
            $host = $config['db']['hostname'];
            $dbname = $config['db']['dbname'];
            $username = $config['db']['username'];
            $password = $config['db']['password'];
            if (isset($config['db']['port'])){
            	$port = $config['db']['port'];
            	$dsn = $driver.':host='.$adress.';port='.$port.';dbname='.$dbname;
            } else {
            	$dsn = $driver.':host='.$host.';dbname='.$dbname;
            }
            parent::__construct($dsn, $username, $password);

            // Change the exception handler back to whatever it was before
            restore_exception_handler();
        }

}
