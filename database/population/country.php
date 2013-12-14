<?php
    include_once '../../config/config-local.php';
    include_once '../../models/database.php';
    include_once '../../models/db.php';
    include_once 'countries_array.php';
    
    $countries = getCountries();
    $array = array();
    $count = 0;
  
    foreach ( $countries as $key => $value ) {
        $res = dbInsert( 'countries', array( 'name' => $value, 'shortname' => $key ) );
        if ( $res === false ) { 
            die( "sql query died with the following error\n\"" . mysql_error() );
        }
        ++$count;
    }
    
    echo "You imported $count rows out of the 239 countries in the table 'countries'.";
?>

