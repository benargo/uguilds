<?PHP

require_once APPPATH."/libraries/couchdb/couch.php";
require_once APPPATH."/libraries/couchdb/couchClient.php";
require_once APPPATH."/libraries/couchdb/couchDocument.php";
require_once APPPATH."/libraries/couchdb/couchReplicator.php";

class couchdb extends couchClient {

	function __construct() {
		$ci =& get_instance();
		$ci->config->load("couchdb");
		parent::__construct($ci->config->item("couch_dsn"), $ci->config->item("couch_database"));
	}

}
