<?php

function db_connect() {
   @$result = new mysqli('mariadb', 'cs431s39', 'xooM8she', 'cs431s39');

   if (!$result) {
     throw new Exception('Could not connect to database server');
   } else {
     return $result;
   }
}

?>
