<?php  

class Util{
	static function redirect($location, $type, $em, $data=""){
	    header("Location: $location?$type=$em&$data");
	    exit;
	}

	static function log($message, $category = 'app') {
	    $logDir = dirname(__DIR__) . '/logs';
	    if (!is_dir($logDir)) {
	        @mkdir($logDir, 0777, true);
	    }
	    $logFile = $logDir . '/' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $category) . '.log';
	    $time = date('Y-m-d H:i:s');
	    @file_put_contents($logFile, "[$time] $message\n", FILE_APPEND | LOCK_EX);
	}

}