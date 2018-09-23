<?php
include('./Libs/simple_html_dom.class.php');

class CryptoCoinsNews {

    private $indexURL = "https://www.ccn.com/news/";
    private $indexHTML;
    
    private $articlesArray = Array();

    public function getArticles() {
        
        $indexHTML = new simple_html_dom();
        $indexHTML->load_file($this->indexURL);
        
            $i = 0; 
        foreach($indexHTML->find('article') as $element) {

            $_article_url = $element->find('a', 0)->href;
            $_article_image_url = $element->find('img', 1)->src;

            $_article_id = explode(" ", $element->class);
            $_article_id = substr($_article_id[0], 5);

            $_article_timestamp = strtotime($element->find('time', 0)->plaintext);

            $_article_title = html_entity_decode($element->find('a', 0)->title);

            $this->articlesArray[$i]['pub_id'] = 0;
            $this->articlesArray[$i]['article_id'] = $_article_id;
            $this->articlesArray[$i]['article_url'] = $_article_url;
            $this->articlesArray[$i]['article_title'] = $_article_title;
            $this->articlesArray[$i]['article_timestamp'] = $_article_timestamp;
            $this->articlesArray[$i]['article_image_url'] = $_article_image_url;
            $this->articlesArray[$i]['article_keywords'] = $this->getArticleKeywords($_article_url);

            $i++;
        }

       return array_reverse($this->articlesArray);
    }

    private function getArticleKeywords($url) {
        $keywords = Array();
        $html = new simple_html_dom();
        $html->load_file($url);

        foreach($html->find('meta[property=article:tag]') as $element) {
            $keywords[] = $element->content;
        }
        return $keywords;
    }
}

?>