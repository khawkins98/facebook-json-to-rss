<?php

   /****
    ** Translates a Facebook Graph JSON to RSS
    ** Use this script by invoking the its url plus the Facebook Page name in the URL ala /index.php?page=PageNameHere
    **/

    //Max title length in characters, will trim on word boundary
    $title_length = 70;

    //OPTIONAL You can specify your timezone here, if different from your server config
    //date_default_timezone_set('America/New_York');

    //Download this at https://github.com/facebook/facebook-php-sdk
    require_once("facebook-php-sdk-master/src/facebook.php");

    //Setup your app at https://developers.facebook.com/apps
    $config = array();
    $config['appId'] = 'ADDHERE';
    $config['secret'] = 'ADDHERE';
    $config['fileUpload'] = false; // optional

    $facebook = new Facebook($config);
    $access_token = $facebook->getAccessToken();

    $screen_name  = $_GET['page'];

    $statuses_url = 'https://graph.facebook.com/' . $screen_name . '/posts?access_token=' . $access_token;
    $fetch_json   = file_get_contents($statuses_url);
    $return       = json_decode($fetch_json);
    $now          = date("D, d M Y H:i:s O");

    $output = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
        <rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">
            <channel>
                <title>".$screen_name."</title>
                <link>http://www.facebook.com/".$screen_name."</link>
                <description> </description>
                <pubDate>$now</pubDate>
                <lastBuildDate>$now</lastBuildDate>
                ";
    foreach ($return->data as $line){

        //Catch facebook posts with no links and set it to the page url
        if (!$line->link) {
            $line->link = "http://www.facebook.com/".$screen_name."#".$line->id;
        }

        //Check for titles longer than the specified length, trims on word end and adds ellipses
        if (strlen($line->message) > $title_length) { 
            $linetitle = preg_replace('/\s+?(\S+)?$/', '', substr($line->message, 0, $title_length)) . ' ...';
        } else {
            $linetitle = $line->message;
        }

        $output .= "<item><title><![CDATA[".htmlspecialchars(strip_tags($linetitle),ENT_COMPAT,'utf-8')." ]]></title>
            <link>".utf8_encode(htmlentities(strip_tags($line->link),ENT_COMPAT,'utf-8'))."</link>
            <description><![CDATA[".htmlspecialchars(strip_tags($line->message),ENT_COMPAT,'utf-8')." ]]></description>
            <author>".htmlentities($line->from->name)."</author>
            <pubDate>".date("D, d M Y H:i:s T",strtotime($line->created_time))."</pubDate>
            <guid>".utf8_encode(htmlentities(strip_tags($line->link),ENT_COMPAT,'utf-8'))."</guid>
            </item>
            ";
    }
    $output .= "</channel>
                </rss>";
    header("Content-Type: application/rss+xml");
    echo $output;

?>
