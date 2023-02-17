<?php
spl_autoload_register( function( $class ){
    $class = str_replace("\\", "/", $class);
    require_once(__DIR__."/src/" . $class);
});

$google = new GoogleSearch();
$search = $google->search( "puppies" );

print_r( $search );


