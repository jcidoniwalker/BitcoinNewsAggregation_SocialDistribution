<?php

class Social {

    /* Twitter Credentials */
    public static $twitterConsumerKey = "ENTER HERE";
    public static $twitterConsumerSecret = "ENTER HERE";
    public static $twitterAccessToken = "ENTER HERE";
    public static $twitterAccessTokenSecret = "ENTER HERE";
    /* End Twitter Credentials */
    
    function GetHashtags($keywords) {
        $hashtag_string = "";

        foreach($keywords as $value) {
            $hashtag_string .= "#" . str_replace(" ", "", $value) . " ";
        }

        return $hashtag_string . "#bitcoin #bitcoinnews ";
    }
}
?>