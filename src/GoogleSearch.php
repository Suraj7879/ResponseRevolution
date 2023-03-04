<?php
namespace ResponseGPT;
require_once 'SearchResult.php';
class GoogleSearch
{
    const URL = "https://www.googleapis.com/customsearch/v1?key=API_KEY&cx=ENGINE_ID";

    public function __construct(
        public string $api_key,
        public string $engine_id,
    ) {}
    
    /**
     *  @return SearchResult[]
     */
    public function search(string $search_query){
        $results = file_get_contents( $this->get_url() . "&q=" . urlencode( $search_query ) );

        return $this->parse_results( $results );
    }
    
    /**
     *  @return SearchResult[]
     */
    private function parse_results(string $results) {
        $json = json_decode( $results );

        $items = $json->items;

        $results = [];

        foreach( $items as $item ) {
            $result = new SearchResult(
                url: $item->link,
                title: $item->title,
                description: $item->snippet,
            );

            $results[] = $result;
            
        } 

        return $results;
    }

    private function get_url()
    {
        return str_replace(
            ["API_KEY", "ENGINE_ID"],
            [$this->api_key, $this->engine_id],
            static::URL 
        );
    }
}

