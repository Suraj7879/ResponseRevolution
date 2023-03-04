<?php
require_once(__DIR__."/vendor/autoload.php");

header( "Access-Control-Allow-Origin: *" );


use ResponseGPT\ResponseGPT;
use ResponseGPT\ResponseGPTResponse;
use Orhanerday\OpenAi\OpenAi;
use League\CommonMark\CommonMarkConverter;

header( "Content-Type: application/json" );

$context = json_decode( $_POST['context'] ?? "[]" ) ?: [];

// initialize OpenAI api
$openai = new OpenAi( trim( rtrim( file_get_contents( "api_key.txt" ) ) ) );

$responsegpt = new ResponseGPT( $openai );

$responsegpt->set_default_prompt("Act as an AI mentor for a programmer by answering the questions provided. If the question is related to a piece of code, write the code and explain what it does and how it works in simple terms. Format the response in Markdown format so that the code can be distinguised from it easily. Please also explain the steps involved, don't only tell the code to use. Every response must have more than just code: at least one sentence about the code. If you're asked for your identity, say that your name is the magnificent ResponseGPT, created by Suraj.\n\n");

$sample_questions = [
    new ResponseGPTResponse(
        question: "How do you write a hello world script in PHP?",
        answer: "In PHP, you can write a hello world script with the following code:\n\n```\n<?php\necho 'Hello world';\n?>\n```\n\nYou need to put this code into a file with the .php extension and then run it with PHP or with a web server.",
    ),

    new ResponseGPTResponse(
        question: "Can you use print function instead?",
        answer: "Certainly! Here's how you would use the `print` function insted:\n\n```\n<?php\nprint('Hello world');\n?>\n```\n\n",
    )
];

$responsegpt->set_sample_questions( $sample_questions );

$response = $responsegpt->ask($_POST['message']);

// log for debugging
error_log( print_r($response, true));

// return response
echo json_encode( [
    "message" => $response->get_formatted_answer(),
    "raw_message" => $response->answer,
    "status" => "success",
] );
