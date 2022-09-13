<?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tflashgame";
    $dbname_kiddy = "kiddy_intelligent";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");

    // Create connection
    $conn_kiddy = new mysqli($servername, $username, $password, $dbname_kiddy);
    // Check connection
    if ($conn_kiddy->connect_error) {
      die("Connection failed: " . $conn_kiddy->connect_error);
    }
    $conn_kiddy->set_charset("utf8");

function findUltimateDestination($url) {
    // First let's find out if we just typed the domain name alone or we prepended with a protocol 
    if (preg_match('/(http|https):\/\/[a-z0-9]+[a-z0-9_\/]*/',$url)) {
        $url = $url;
    } else {
        $url = 'http://' . $url;
        echo '<p>No protocol given, defaulting to http://';
    }
    // Let's print out the initial URL
    echo '<p>Initial URL: ' . $url . '</p>';
    // Prepare the HEAD method when we send the request
    stream_context_set_default(array('http' => array('method' => 'HEAD')));
    // Probe for headers
    $headers = get_headers($url, 1);
    // If there is a Location header, trigger logic
    if (isset($headers['Location'])) {
        // If there is more than 1 redirect, Location will be array
        if (is_array($headers['Location'])) {
            // If that's the case, we are interested in the last element of the array (thus the last Location)
            echo '<p>Redirected URL: ' . $headers['Location'][array_key_last($headers['Location'])] . '</p>';
            $url = $headers['Location'][array_key_last($headers['Location'])];
        } else {
            // If it's not an array, it means there is only 1 redirect
            //var_dump($headers['Location']);
            echo '<p>Redirected URL: ' . $headers['Location'] . '</p>';
            $url = $headers['Location'];
        }
    } else {
        echo '<p>URL: ' . $url . '</p>';
    }

    return $url;
}



function myDOMXPath($page) {
    $htmlString = file_get_contents($page);   

    //add this line to suppress any warnings
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadHTML($htmlString);
    return new DOMXPath($doc);
}
/*
*	Fake Human access 
*/
function myDOMXPathHuman($page) {
	$context = stream_context_create(
	    array(
	        "http" => array(
	            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
	        )
	    )
	);
    $htmlString = file_get_contents($page, false, $context);;   

    //add this line to suppress any warnings
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();
    $doc->loadHTML($htmlString);
    return new DOMXPath($doc);
}

function makeThumbName($title, $thumb_link) {
    if (substr($thumb_link,-4,1) == '.' || substr($thumb_link,-5,1) == '.') {
        return slugify($title).substr($thumb_link,-5);
    } else {
        return slugify($title).'.jpg';
    }
}
function saveDB($conn, $title, $t_slug, $dimension, $type, $vote, $vote_time, $play_time, $hot, $link, $site, $author, $cat1, $cat2, $cat3, $cat4, $g_not_mobi, $desc, $guide, $thumb, $allow_embed) {
        $sql = "INSERT INTO game (
            g_title, 
            g_title_slug, 
            g_dimension,
            g_type,
			g_vote,
			g_vote_time,
			g_play_time,
            g_hot,
            g_link,
            g_site,
            g_for_lang,
            g_author,
            g_cat_1,
            g_cat_2,
            g_cat_3,
            g_cat_4,
            g_not_mobi,
            g_desc,
            g_guide,
            g_thumb,
            g_allow_embed,
            g_status,
            created_at,
            updated_at,
            deleted_at)
    VALUES ('".$title."', '".$t_slug."', '".$dimension."', '".$type."', ".$vote.", ".$vote_time.", ".$play_time.", ".$hot.", '".$link."', '".$site."', 'en', '".$author."', '".$cat1."', '".$cat2."', '".$cat3."', '".$cat4."', '".$g_not_mobi."', '".$desc."', '".$guide."', '".$thumb."', ".$allow_embed.", 1, NOW(), NOW(), null)";

        if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
}
function updateDB($conn, $link, $cat_t) {
    $sql = "UPDATE game SET g_cat_t = '".$cat_t."' WHERE g_link = '".$link."'";
    if ($conn->query($sql) === TRUE) {
      echo "Record updated successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
function saveDBPage($conn, $platform, $page, $cat, $thumb, $type) {
        $sql = "INSERT IGNORE INTO game_page (platform, page, cat, thumb, type)
    VALUES ('".$platform."', '".$page."', '".$cat."', '".$thumb."', '".$type."')";

        if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
}

function updateTitle($conn, $title, $title_slug, $author, $link) {
        $sql = "UPDATE game SET g_title = '".$title."', g_title_slug = '".$title_slug."', g_author = '".$author."' WHERE g_link = '".$link."'";

        if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
}

function updateDimension($conn, $link, $dm) {
        $sql = "UPDATE game SET g_dimension = '".$dm."' WHERE g_link = '".$link."'";

        if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
}

function saveDBgame_page($conn, $platform, $page, $cat, $thumb) {
        $sql = "INSERT INTO game_page (
            platform, 
            page, 
            cat,
            thumb)
    VALUES ('".$platform."', '".$page."', ".$cat.", '".$thumb."')";

        if ($conn->query($sql) === TRUE) {
          echo "New record created successfully<br />";
        } else {
          echo "Error: " . $sql . "<br>" . $conn->error.'<br />';
        }
}

function slugify($text, string $divider = '-') {
  if (isNullOrEmptyString($text))
    return '';
  // replace non letter or digits by divider
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, $divider);

  // remove duplicate divider
  $text = preg_replace('~-+~', $divider, $text);

  // lowercase
  $text = strtolower($text);

  return $text;
}
function slugifyUnicode($title) {
        $replacement = '-';
        $map = array();
        $quotedReplacement = preg_quote($replacement, '/');
        $default = array(
            '/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|å/' => 'a',
            '/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|ë/' => 'e',
            '/ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ|î/' => 'i',
            '/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|ø/' => 'o',
            '/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|ů|û/' => 'u',
            '/ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ/' => 'y',
            '/đ|Đ/' => 'd',
            '/ç/' => 'c',
            '/ñ/' => 'n',
            '/ä|æ/' => 'ae',
            '/ö/' => 'oe',
            '/ü/' => 'ue',
            '/Ä/' => 'Ae',
            '/Ü/' => 'Ue',
            '/Ö/' => 'Oe',
            '/ß/' => 'ss',
            '/[^\s\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
            '/\\s+/' => $replacement,
            sprintf('/^[%s]+|[%s]+$/', $quotedReplacement, $quotedReplacement) => '',
        );
        //Some URL was encode, decode first
        $title = urldecode($title);
        $map = array_merge($map, $default);
        return strtolower(preg_replace(array_keys($map), array_values($map), $title));
}

function removeHeadTail($str, $remove = ' ') {
    while(substr($str, 0, 1) == $remove || substr($str, 0, 1) == "\n") {
        $str = substr($str, 1, strlen($str));
    }
    while(substr($str, -1, 1) == $remove || substr($str, -1, 1) == "\n") {
        $str = substr($str, 0, -1);
    }
    return $str;
}
function isNullOrEmptyString($str){
    return ($str === null || trim($str) === '');
}

function getCatIdFromNameENMemotize($conn, $name) {
    if ($name == 'Bejeweled')
        return 26;
    if ($name == 'Baby Hazel')
        return 35;
    if ($name == 'Puzzle')
        return 28;
    $sql = 'SELECT id FROM game_cat WHERE g_cat_name = "'.$name.'"';
    return $conn->query($sql)->fetch_object()->id ?? 61; // 61 is others
}

function makeTags($str, $deli = ',') {
	$arr = explode($deli, $str);
	if ($str == null || $str = "")
        return "";
    elseif (sizeof($arr) == 1)
		return '#'.str_replace('-', '', slugifyUnicode($arr[0]));
	else {
		$str_n = '';
		for ($i=0; $i<sizeof($arr); $i++) {
			if (!isNullOrEmptyString($arr[$i]))
				$str_n .= '#'.str_replace('-', '', slugifyUnicode($arr[$i])).',';
		}
		return $str_n;
	}
}


?>