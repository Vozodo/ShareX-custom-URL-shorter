<?php
//SETTINGS
$secret_key = "changeme"; //Set this as your secret key, to prevent others uploading to your server.
$domain_url = 'http://phpshorter.test'; //Add an S at the end of HTTP if you have a SSL certificate.
$lengthofstring = 7; //Length of the file name




//DB
$databasePath = 'phpshorter.sqlite';

if (!file_exists($databasePath)) {
    $db = new PDO('sqlite:' . $databasePath);
    $db->exec('CREATE TABLE IF NOT EXISTS `url_shorten` (`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT, `url` text NOT NULL, `short_code` varchar(50) NOT NULL, `hits` INTEGER NOT NULL, `added_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP );');
}

$db = new PDO('sqlite:' . $databasePath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



//Redirect User if Shorten URL
if (isset($_GET['r'])) {
    $slug = filter_input(INPUT_GET, 'r');

    $query = "SELECT * FROM url_shorten WHERE short_code = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$slug]);
    $result = $stmt->fetch();

    if ($result) {
        $hits = $result['hits'] + 1;
        $query = "UPDATE url_shorten SET hits = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$hits, $result['id']]);

        header("Location: " . $result['url']);
        exit;
    } else {
        die("Invalid Link!");
    }
}



//TODO
//Generate short URL
if (isset($_POST['secret'])) {
    if ($_POST['secret'] === $secret_key) {
        $shortUrl = getShortUrl(filter_input(INPUT_POST, 'url'));
        if ($shortUrl) {
            echo "$domain_url/$shortUrl/";
        } else {
            echo "Error generating short URL";
        }
    } else {
        echo "Invalid Secret Key";
    }
} else {
    echo "No post data received";
}








// Functions
function getShortUrl($url) {
    global $db;
    global $lengthofstring;

    $query = "SELECT * FROM url_shorten WHERE url = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$url]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        return $result['short_code'];
    } else {
        $shortCode = generateUniqueID($lengthofstring);

        $query = "INSERT INTO url_shorten (url, short_code, hits) VALUES (?, ?, 0)";
        $stmt = $db->prepare($query);
        $stmt->execute([$url, $shortCode]);

        return $shortCode;
    }
}



//DONE
function generateUniqueID($length) {
    global $db;

    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $key = substr(str_shuffle($chars), 0, $length);

    $query = "SELECT * FROM url_shorten WHERE short_code = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$key]);
    $result = $stmt->fetch();

    if ($result) {
        return generateUniqueID($length);
    } else {
        return $key;
    }
}
