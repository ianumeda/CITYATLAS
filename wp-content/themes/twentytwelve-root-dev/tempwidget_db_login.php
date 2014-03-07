<?php
// setup connection to database
  function doConnect ($host, $user, $pw, $database) {
    $connect = mysql_connect("$host","$user","$pw") or die (mysql_error());
    $db = mysql_select_db($database, $connect) or die (mysql_error());
  }

doConnect("localhost", "cityatla_tempwgt", "Halitosis-breath", "cityatla_0");
//print "CONNECTED TO DB\n\n";
?>