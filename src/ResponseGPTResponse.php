<?php
namespace ResponseGPT;
use League\CommonMark\CommonMarkConverter;
class ResponseGPTResponse
{
    public function __construct(
        public string $answer,
        public string $question,
    ){}

    public function get_formatted_answer(): string {
        // convert markdown to HTML
        $converter = new CommonMarkConverter();
        $styled = $converter->convert( $this->answer );
    
        return (string) $styled;
    }
    
}