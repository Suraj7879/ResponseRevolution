<?php
namespace ResponseGPT;
use Orhanerday\OpenAi\OpenAi;
class ResponseGPT
{
    private string $default_prompt;
    /**
     *  @var ResponseGPTResponse[]
     */
    private array $sample_questions;
    private array $context;

    public function __construct(
        protected OpenAi $open_ai_api,
    ) {}

    public function set_default_prompt( string $prompt ) {
        $this->default_prompt = $prompt;
    }

    /**
     *  @param ResponseGPTResponse[] $questions
     */
    public function set_sample_questions( array $questions ) {
        $this->sample_questions = $questions;
    }
    public function add_to_context( ResponseGPTResponse $response ) {
        $this->context[] = $response;
    }

    public function ask( string $question ): ResponseGPTResponse{
        $prompt = $this->create_prompt( $question );

        $complete = json_decode( $this->open_ai_api->completion( [
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'temperature' => 0.9,
            'max_tokens' => 2000,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
            'stop' => [
                "\nNote:",
                "\nQuestion:"
            ]
        ] ) );
        // get message text
        if( isset( $complete->choices[0]->text ) ) {
            $answer = str_replace( "\\n", "\n", $complete->choices[0]->text );
        } elseif( isset( $complete->error->message ) ) {
            $answer = $complete->error->message;
        } else {
            $answer = "Sorry, but I don't know how to answer that.";
        }

        return new ResponseGPTResponse( $answer, $question );
    }

    private function create_prompt( string $question ) {
        // set default prompt
        $prompt = $this->default_prompt;

        // add context to prompt
        if(empty( $this->context ) ) {
            // if no context, return default prompt
            foreach( $this->sample_questions as $sample_question ) {
                $prompt .= "Question:\n'".$sample_question->question."'\n\nAnswer:\n".$sample_question->answer."\n\n";
            }
            $please_use_above = "";
        } else {
            // add old questions and answers to prompt
            $prompt .= "";
            $this->context = array_slice( $this->context, -5 );
            foreach( $this->context as $message ) {
                $prompt .= "Question:\n" . $message->question . "\n\nAnswer:\n" . $message->answer . "\n\n";
            }
            $please_use_above = ". Please use the questions and answers above as context for the answer.";
        }

        // add new question to prompt
        $prompt = $prompt . "Question:\n" . $question .
        $please_use_above . "\n\nAnswer:\n\n";

        return $prompt;
    }
}