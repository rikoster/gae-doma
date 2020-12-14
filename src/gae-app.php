<?php

// A simple front controller by rikoster on 2020-12-13

/**
 * This file handles all routing for the DOMA server running on App Engine.
 * It serves up the appropriate PHP file depending on the request URI.
 *
 * @see https://cloud.google.com/appengine/docs/standard/php7/runtime#application_startup
 */

/**
 * Function to return a PHP file to load based on the request URI.
 *
 * @param string $full_request_uri The request URI derivded from $_SERVER['REQUEST_URI'].
 */
function get_real_file_to_load($full_request_uri)
{
  $request_path = @parse_url($full_request_uri)['path'];

  // To support the normal paths, requested e.g. by 3dRerun
  // Redirect /map_images* to https://storage.googleapis.com/puistokartat.appspot.com
  if (substr($request_path, 0, 12) == '/map_images/') {
    $startpos = strpos($full_request_uri, '/map_images/');
    header('Location: https://storage.googleapis.com/' . $_SERVER["GOOGLE_CLOUD_PROJECT"] . '.appspot.com/map_images/' . substr($full_request_uri, $startpos + 12));
    exit;
  }

  // Load the file requested if it exists
  if (is_file(__DIR__ . $request_path)) {
    return $request_path;
  }

  // Send everything else through index.php
  return '/index.php';
}

// fixes b/111391534
$_SERVER['HTTPS'] = $_SERVER['HTTP_X_APPENGINE_HTTPS'];

// Loads the expected file
// (e.g index.php, wp-admin/* or wp-login.php)
$file = get_real_file_to_load($_SERVER['REQUEST_URI']);

// Set the environment variables to reflect the script we're loading
// (in order to trick WordPress)
$_SERVER['DOCUMENT_URI']    = $_ENV['DOCUMENT_URI']    = $file;
$_SERVER['PHP_SELF']        = $_ENV['PHP_SELF']        = $file;
$_SERVER['SCRIPT_NAME']     = $_ENV['SCRIPT_NAME']     = $file;
$_SERVER['SCRIPT_FILENAME'] = $_ENV['SCRIPT_FILENAME'] = __DIR__ . $file;

require __DIR__ . $file;
