<?php
require_once(__DIR__ . "/autoload.php");

$google = new GoogleSearch(
    api_key: "AIzaSyA1K9l6tk_jEkbWGlZge60Kcn65T1LnZuE",
    engine_id: "05a359d5377274ec4",
);
$search = $google->search( "puppies" );

print_r( $search );


