<?php

class Database {

    public static $MySQL_Host = "";
    public static $MySQL_Username = "";
    public static $MySQL_Password = "";
    public static $MySQL_Database = "";

    public $ExistingPubArticleIDs = Array();

    private $mysqliConnection;

    function __construct($host, $user, $pass, $db) {
        $this->mysqliConnection = new mysqli($host, $user, $pass, $db);
        if ($this->mysqliConnection->connect_errno) {
            echo "Failed to connect to MySQL: " . $this->mysqliConnection->connect_error;
        }

        /* change character set to utf8 */
        if (!$this->mysqliConnection->set_charset("utf8")) {
            printf("Error loading character set utf8: %s\n", $this->mysqliConnection->error);
        }

        // Load an array full of existing article ids to compare new articles with
        $this->LoadExistingArticleIDs();
    }

    function AddArticle($Article) {

        $insert_query = "INSERT INTO `articles` (`pub_id`, `pub_article_id`, `article_url`, `title`, `timestamp`, `image_url`, `article_keywords`) VALUES (?, ?, ?, ?, ?, ?, ?);";
        if(!($stmt = mysqli_prepare($this->mysqliConnection, $insert_query))) {
            print("Prepare failed: (" . $this->mysqliConnection->errno . ") " . $this->mysqliConnection->error ."\n");
            return false;
        }

        if(!(@$stmt->bind_param("iississ", $Article['pub_id'], $Article['article_id'], $Article['article_url'], $Article['article_title'], $Article['article_timestamp'], $Article['article_image_url'], $this->KeywordsForDatabase($Article['article_keywords'])))) {
            print("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error . "\n");
            return false;
        }

        if(!($stmt->execute())) {
            print("Execute failed: (" . $stmt->errno . ") " . $stmt->error ."\n");
            return false;
        }

        mysqli_stmt_close($stmt);
        return true;
    }

    private function KeywordsForDatabase($keywords) {
        foreach($keywords as $value) {
            $string .= $value . ",";
        }
        return substr($string, 0, -1);
    }
    

    function LoadExistingArticleIDs() {
        if ($result = $this->mysqliConnection->query("SELECT `pub_article_id` FROM `articles`")) {
            $this->ExistingPubArticleIDs = $result->fetch_all(MYSQLI_NUM);
            $result->close();

        } else if ($this->mysqliConnection->error) {
            print("Could not load exisiting publisher article IDs:\n");
            printf("Error: %s\n", $this->mysqliConnection->error);

        }
    }

    function ArticleExists($id) {
        foreach($this->ExistingPubArticleIDs as $value) {
            if($value[0] == $id) {
                return true;
            }
        }
        return false;
    }
    
}

?>