facebook-json-to-rss
====================

Converts the JSON from Facebook's Graph API to RSS.

RSS may be aging and JSON the future, but the Graph API's lack of RSS support is really obnoxious when you just need a good old RSS feed.

This is a PHP script, you'll probably need PHP 5.2 or newer on your server. You'll also need to download Facebook SDK and a Facebook developer account, as you need an access token.

Download the SDK at https://github.com/facebook/facebook-php-sdk and setup your app at https://developers.facebook.com/apps

Use this script by invoking the its url plus the Facebook Page name in the URL ala /index.php?page=PageNameHere

This script doesn't add Facebook images to the feed, though it wouldn't be hard to make that happen.