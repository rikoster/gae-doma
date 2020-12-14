<?php

// [rikoster 2020-12-13] changed the mysqli_connect row in two ways, (1) assumes
// that databse name is always correct and database exists, and (2) uses the
// special format needed for Google App Engine database connection.
//
// Connection parameters quite different in GAE production vs. local
  if (isset($_SERVER['GAE_ENV'])) {
    $dbCon = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME, DB_PORT, DB_SOCKET);
  } else {
    $dbCon = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
  }
  //$dbCon = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD);
  //if(!$GLOBALS["dbCon"]) die("Could not connect to the database. Make sure that the DB_HOST, DB_USERNAME and DB_PASSWORD settings in config.php are correct.");
  //if(!mysqli_select_db($GLOBALS["dbCon"], DB_DATABASE_NAME)) die("Could not select database. Make sure that the DB_DATABASE_NAME setting in config.php is correct.");
  mysqli_query($GLOBALS["dbCon"], "SET NAMES utf8");
  mysqli_query($GLOBALS["dbCon"], "SET CHARACTER SET utf8");
?>
