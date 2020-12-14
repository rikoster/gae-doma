<?php

// additions by rikoster on 2020-12-13 to enable Google Cloud Storage
// and its Streamwrapper for 'gs://'
//
// Requires that Google libraries are installed with php Composer on the
// standard 'vendor' directory.

require_once(dirname(__FILE__) ."/../vendor/autoload.php");
use Google\Cloud\Storage\StorageClient;
$storage = new StorageClient();
$storage->registerStreamWrapper();

?>
