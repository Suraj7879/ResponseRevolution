<?php
namespace ResponseGPT;
require_once(__DIR__."/vendor/autoload.php");

header( "Access-Control-Allow-Origin: *" );

use Orhanerday\OpenAi\OpenAi;
use League\CommonMark\CommonMarkConverter;

header( "Content-Type: application/json" );

$context = json_decode( $_POST['context'] ?? "[]" ) ?: [];

// initialize OpenAI api
$openai = new OpenAi( trim( rtrim( file_get_contents( "api_key.txt" ) ) ) );

$google = new GoogleSearch(
    api_key: "AIzaSyA1K9l6tk_jEkbWGlZge60Kcn65T1LnZuE",
    engine_id: "05a359d5377274ec4",
);

// initialize google search api
$responsegpt = new GoogleSearchResponseGPT( $openai );
$response = $responsegpt->ask($_POST['message']);

$search = $google->search( $response->answer);

// $page_source = strip_tags( file_get_contents( $search[0]->url ));
// Remove all styles
// $page_source = preg_replace('/<style.*?</style>/is', '', $page_source);
// $page_source = preg_replace('/<script.*?</script>/is', '', $page_source);
// $page_source = strip_tags($page_source);
// $page_source = substr( $page_source, 0, 1024 );

// $responsegpt = new ResponseGPT( $openai );
// $responsegpt->set_default_prompt( $page_source );
// $responsegpt->set_sample_questions( [] );
// $response = $responsegpt->ask( $_POST['message'] );
// return response;

echo json_encode( [
    "message" => $search[0]->url,
    "raw_message" => $search[0]->url,
    "status" => "success",
] );

exit;

// log for debugging
error_log( print_r($response, true));

// return response
echo json_encode( [
    "message" => $response->get_formatted_answer(),
    "raw_message" => $response->answer,
    "status" => "success",
] );
