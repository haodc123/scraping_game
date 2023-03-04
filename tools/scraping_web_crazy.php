<?php

require_once('_common.php');

$arr_cats = array(
6  => array("https://www.crazygames.com/c/shooting"),
11 => array("https://www.crazygames.com/c/driving"),
14 => array("https://www.crazygames.com/c/action"),
20 => array("https://www.crazygames.com/c/clicker"),
22 => array("https://www.crazygames.com/c/adventure"),
25 => array("https://www.crazygames.com/c/sports"),
26 => array("https://www.crazygames.com/c/beauty"),
28 => array("https://www.crazygames.com/c/puzzle"),
31 => array("https://www.crazygames.com/c/arcade"),  
34 => array("https://www.crazygames.com/c/io")
);
$arr_cats_kiddy = array(
28 => array("https://www.crazygames.com/c/puzzle"),
65 => array("https://www.crazygames.com/t/board"),
66 => array("https://kids.crazygames.com/"));

// $arr_cats = array(
// 34 => array("https://www.crazygames.com/c/io"));

$arr_games_link = array();

// Insert to [game_page]
function getListAllGame($_conn, $arr_cats, $our_site = 'tflash') {
    $arr_link = array();
    ob_implicit_flush(true); // Support sleep()/usleep()
    ob_end_flush(); // Support sleep()/usleep()
    for ($i=0; $i<67; $i++) {
        if (isset($arr_cats[$i])) {
            $arr_link[$i] = array();
            for ($k=1; $k<4; $k++) { // pagination
                array_push($arr_link[$i], getListGameByCat($_conn, $i, $arr_cats[$i][0].'/'.$k, $our_site));
            }
        }
        usleep(400);
    }
    print_r('<pre>');
    print_r($arr_link);
    print_r('</pre>');
    return $arr_link;
}
function getListGameByCat($_conn, $cat, $page, $our_site = 'tflash') { // input to game_page
    
    $xpath = myDOMXPath($page);
    
    // get first div have class=...
    $el_link = $xpath->query('//a[@class="css-9w4sfg"]');
    $el_thumb = $xpath->query('//img[@class="GameThumbImage"]');
    $el_title = $xpath->query('//div[@class="gameThumbTitleContainer"]');
    $arr_games_each_cat = array();
    $all_item = 0;
    $li_count = 0;
    echo $el_link->length;
    for ($i=0; $i<$el_link->length; $i++) {
        if ($el_link->item($i))
                $all_item++;
                $link = mysqli_real_escape_string($_conn,$el_link->item($i)->getAttribute("href"));
                $thumb = mysqli_real_escape_string($_conn, substr($el_thumb->item($i)->getAttribute("src"), 0, strpos($el_thumb->item($i)->getAttribute("src"), '?auto')));
                $title = mysqli_real_escape_string($_conn, $el_title->item($i) ? $el_title->item($i)->textContent : '');
                echo $title.' - '.$thumb.' - '.$link.' - '.makeThumbName($title, $thumb).'<br />';
                array_push($arr_games_each_cat, $link);

                if ($our_site == 'tflash') {
                    file_put_contents('../res/thumb/crazygames/'.makeThumbName($title, $thumb), file_get_contents($thumb));
                } elseif ($our_site == 'kiddy') {
                    file_put_contents('../res/thumb_kiddy/crazygames/'.makeThumbName($title, $thumb), file_get_contents($thumb));
                }

                if ($our_site == 'tflash')
                    saveDBPage($_conn, 'crazygames.com', $link, $cat, makeThumbName($title, $thumb), '', $our_site); 
                elseif ($our_site == 'kiddy')
                    saveDBPage($_conn, 'crazygames.com', $link, $cat, makeThumbName($title, $thumb), '', $our_site);

    }
    return $arr_games_each_cat;
}

// Insert to [game]
function getAllGameDetail($_conn, $is_ignore_exist = 1, $our_site = 'tflash') { 
    $arr_games = array();
    $sql = "SELECT * FROM game_page WHERE platform = 'crazygames.com'";

    if ($result = $_conn -> query($sql)) {
        ob_implicit_flush(true); // Support sleep()/usleep()
        ob_end_flush(); // Support sleep()/usleep()
        while ($row = $result -> fetch_row()) {
            $arr_game = array();
            array_push($arr_game, $row[1], $row[2], $row[3]);
            array_push($arr_games, $arr_game);
            myParseContent($_conn, $row[1], $row[2], $row[3], 1, $our_site);
            usleep(300);
            // break;
        }
        $result -> free_result();
    }
    // print_r('<pre>');
    // print_r($arr_games);
    // print_r('</pre>');
}
function myParseContent($_conn, $page, $cat, $thumb, $is_ignore_exist = 1, $our_site = 'tflash') {
    // echo $our_site;
    $xpath = myDOMXPath($page);

    $el_title = $xpath->query('//div[@class="css-11skw8i"]/h1');
        $title = $el_title->item(0) ? mysqli_real_escape_string($_conn, $el_title->item(0)->textContent) : '';
	$s_title = slugifyUnicode($title);
    
    // $el_author = $xpath->query('//h3[text()="Developer"]/following-sibling::p');
    $el_author = $xpath->query('//span[@class="css-exrwgm"]');
        $author = $el_author->item(0) ? mysqli_real_escape_string($_conn, $el_author->item(0)->textContent) : '';
	// if (strpos($f_author, 'developed') !== false) {
	// 	$author = explode(' developed ', $f_author)[0];
	// } else {
	// 	$el_author = $xpath->query('//div[h3/text()="Developer"]');
	// 	$f_author = $el_author->item(0) ? mysqli_real_escape_string($_conn, $el_author->item(0)->textContent) : '';
	// 	$author = explode('\n', explode(' by ', $f_author)[1])[0];
	// }
	$el_desc = $xpath->query('//div[@class="gameDescription_first css-1tfyr3k"]');
        $desc = $el_desc->item(0) ? mysqli_real_escape_string($_conn, $el_desc->item(0)->textContent) : '';

	$el_guide = $xpath->query('//h3[text()="Controls"]/following-sibling::p');
	if (null === $el_guide->item(0)) {
		$el_guide = $xpath->query('//h3[text()="Controls"]/following-sibling::ul');
	}
	$guide = $el_guide->item(0) ? mysqli_real_escape_string($_conn, $el_guide->item(0)->textContent) : '';
		
    $el_link = $xpath->query('//iframe[@id="game-iframe"]');
        $link = $el_link->item(0) ? $el_link->item(0)->getAttribute('src') : '';
    echo 't-a: '.$title.' - '.$author.'<br />';
    echo 'd-g: '.$desc.' - '.$guide.'<br />';
    echo 'l: '.$link.'<br />';

	$vote = rand(3,5);
	$vote_time = rand(10, 20);
	$play_time = rand(21, 1144);
		
    if ($our_site == 'tflash')
        saveDB($_conn, $title, $s_title, 'Unknown', 0, $vote, $vote_time, $play_time, 0, $link, 'https://crazygames.com', $author, $cat, 0, 0, 0, 0, $desc, $guide, $thumb, 1, $is_ignore_exist);
    elseif ($our_site == 'kiddy')
        saveDB_Kiddy($_conn, $title, $s_title, 'Unknown', 0, $vote, $vote_time, $play_time, 0, $link, 'https://crazygames.com', $author, $cat, 0, 0, 0, 0, $desc, $guide, $thumb, 1, $is_ignore_exist);

}

//getAllGameDetail($conn);
// getListAllGame($conn_kiddy, $arr_cats_kiddy, 'kiddy');
getAllGameDetail($conn_kiddy, 1, 'kiddy');

$conn->close();

?>