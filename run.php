<?php
require_once('Database/database.class.php');
require_once('Publishers/cryptocoinsnews.class.php');
require_once('Social/Twitter/twitter.class.php');
require_once('Social/social.class.php');

/* Establish Objects */
$Database = new Database(Database::$MySQL_Host, Database::$MySQL_Username, Database::$MySQL_Password, Database::$MySQL_Database);
$Twitter = new Twitter(Social::$twitterConsumerKey, Social::$twitterConsumerSecret, Social::$twitterAccessToken, Social::$twitterAccessTokenSecret);
$Social = new Social();
$CryptoCoinsNews = new CryptoCoinsNews();
/* End Establishing Objects */


foreach($CryptoCoinsNews->getArticles() as $Article) {

    if($Database->ArticleExists($Article['article_id']) == false) {

        if($Database->AddArticle($Article)) {
            echo " -> Article [" . $Article['article_id'] . "] (" . $Article['article_title'] . ") added!\n";
            $Twitter->send($Social->GetHashtags($Article['article_keywords']) . $Article['article_url']);
        }
        
    }

}


?>