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
<<<<<<< HEAD

            $ci = get_instance();
            $config = $ci->config->item('battle.net');
=======
>>>>>>> 5af9f32bb5f3bda4af2a91f727efec67c9b3e595
/*
            // Temporarily change the PHP exception handler while we . . .
            set_exception_handler(array(__CLASS__, 'exception_handler'));
*/
            // . . . create a PDO object
<<<<<<< HEAD
            $driver = $config['db']['driver'];
            $host = $config['db']['hostname'];
            $dbname = $config['db']['dbname'];
            $username = $config['db']['username'];
            $password = $config['db']['password'];
            if (isset($config['db']['port'])){
            	$port = $config['db']['port'];
=======
            $driver = $GLOBALS['wowarmory']['db']['driver'];
            $host = $GLOBALS['wowarmory']['db']['hostname'];
            $dbname = $GLOBALS['wowarmory']['db']['dbname'];
            $username = $GLOBALS['wowarmory']['db']['username'];
            $password = $GLOBALS['wowarmory']['db']['password'];
            if (isset($GLOBALS['wowarmory']['db']['port'])){
            	$port = $GLOBALS['wowarmory']['db']['port'];
>>>>>>> 5af9f32bb5f3bda4af2a91f727efec67c9b3e595
            	$dsn = $driver.':host='.$adress.';port='.$port.';dbname='.$dbname;
            } else {
            	$dsn = $driver.':host='.$host.';dbname='.$dbname;
            }
            parent::__construct($dsn, $username, $password);

            // Change the exception handler back to whatever it was before
            restore_exception_handler();
        }

}
