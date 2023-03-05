<?php
namespace ResponseGPT;

use Orhanerday\OpenAi\OpenAi;

class GoogleSearchResponseGPT extends ResponseGPT
{
    public function __construct(
        protected OpenAi $open_ai_api,
    ) {
        $this->set_default_prompt("You are the best googler there is. You know exactly what to type in the Google search box to get the best results. Please tell what is the best Google search term for the given question\n\n");

        $sample_questions = [
            new ResponseGPTResponse(
                question: "How do you write a hello world script in PHP?",
                answer: "how to write hello world script in php",
            ),
        
            new ResponseGPTResponse(
                question: "What's the weather like in Chennai",
                answer: "weather in chennai",
            ),

            new ResponseGPTResponse(
                question: "How much money does Elon Musk have ?",
                answer: "elon musk net worth",
            )
        ];
        
        $this->set_sample_questions( $sample_questions );
    }
}