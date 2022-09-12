<?php

require_once('_common.php');
$conn->close();

$arr_cats = array(
28 => array("https://www.crazygames.com/c/puzzle"));

$arr_cats2 = array(
66 => array("https://kids.crazygames.com/")); // kids

// $arr_cats = array(
// 34 => array("https://www.crazygames.com/c/io"));

$arr_games_link = array();
function getListAllGame($conn, $arr_cats) {
    $arr_link = array();
    ob_implicit_flush(true); // Support sleep()/usleep()
    ob_end_flush(); // Support sleep()/usleep()
    for ($i=28; $i<70; $i++) {
        if (isset($arr_cats[$i])) {
            $arr_link[$i] = array();
            for ($k=10; $k<20; $k++) { // pagination
                array_push($arr_link[$i], getListGameByCat($conn, $i, $arr_cats[$i][0].'/'.$k));
				
            }
        }
		
        usleep(400);
    }
    // print_r('<pre>');
    // print_r($arr_link);
    // print_r('</pre>');
    return $arr_link;
}
function getListAllGame2($conn, $arr_cats2) { // kids
    $arr_link = array();
    ob_implicit_flush(true); // Support sleep()/usleep()
    ob_end_flush(); // Support sleep()/usleep()
    for ($i=28; $i<70; $i++) {
        if (isset($arr_cats2[$i])) {
            $arr_link[$i] = array();
            array_push($arr_link[$i], getListGameByCat2($conn, $i, $arr_cats2[$i][0]));
        }	
        usleep(400);
    }
    return $arr_link;
}
function getListGameByCat($conn, $cat, $page) { // input to game_page
    $xpath = myDOMXPath($page);
    
    // get first div have class=...
    $el_link = $xpath->query('//a[@class="css-jerizh"]');
    $el_thumb = $xpath->query('//a[@class="css-jerizh"]/img');
    $el_title = $xpath->query('//a[@class="css-jerizh"]/div');
    $arr_games_each_cat = array();
    $all_item = 0;
    $li_count = 0;
	echo $el_link->length;
    for ($i=0; $i<$el_link->length; $i++) {
        if ($el_link->item($i))
                $all_item++;
                $link = mysqli_real_escape_string($conn,$el_link->item($i)->getAttribute("href"));
                $thumb = mysqli_real_escape_string($conn, substr($el_thumb->item($i)->getAttribute("src"), 0, strpos($el_thumb->item($i)->getAttribute("src"), '?auto')));
                $title = mysqli_real_escape_string($conn, $el_title->item($i) ? $el_title->item($i)->textContent : '');
                echo $title.' - '.$thumb.' - '.$link.' - '.makeThumbName($title, $thumb).'<br />';
                array_push($arr_games_each_cat, $link);
                file_put_contents('../res/thumb_kiddy/crazygames/'.makeThumbName($title, $thumb), file_get_contents($thumb));
                saveDBPage($conn, 'crazygames.com', $link, $cat, makeThumbName($title, $thumb), 'Unknown');
            
        
    }
    return $arr_games_each_cat;
}
function getListGameByCat2($conn, $cat, $page) { // kids
    $xpath = myDOMXPath($page);
    
    // get first div have class=...
    $el_link = $xpath->query('//div[contains(@class, "MuiGrid-root MuiGrid-item css-1wxaqej")]/a');
    $el_thumb = $xpath->query('//div[contains(@class, "MuiGrid-root MuiGrid-item css-1wxaqej")]/a/img');
    $arr_games_each_cat = array();
    $all_item = 0;
    $li_count = 0;
    for ($i=2; $i<$el_link->length; $i++) {
        if ($el_link->item($i)) {
                $all_item++;
                $link = mysqli_real_escape_string($conn,$el_link->item($i)->getAttribute("href"));
				$full_link = 'https://kids.crazygames.com'.$link;
                $thumb_link = mysqli_real_escape_string($conn, substr($el_thumb->item($i)->getAttribute("src"), 0, strpos($el_thumb->item($i)->getAttribute("src"), '?auto')));
                $thumb_file_name = basename($thumb_link);
				echo $thumb_link.' - '.$full_link.' - '.$thumb_file_name.'<br />';
                array_push($arr_games_each_cat, $link);
                // file_put_contents('../res/thumb_kiddy/crazygames/'.$thumb_file_name, file_get_contents($thumb_link));
                saveDBPage($conn, 'kids.crazygames.com', $full_link, $cat, $thumb_file_name, 'Unknown');
		}
        
    }
    return $arr_games_each_cat;
}


function getAllGameDetail($conn) { // input to game
    $arr_games = array();
    $sql = "SELECT * FROM game_page";

    if ($result = $conn -> query($sql)) {
        ob_implicit_flush(true); // Support sleep()/usleep()
        ob_end_flush(); // Support sleep()/usleep()
        while ($row = $result -> fetch_row()) {
            $arr_game = array();
            array_push($arr_game, $row[2], $row[3], $row[4]);
            array_push($arr_games, $arr_game);
            myParseContent($conn, $row[2], $row[3], $row[4]);
            usleep(300);
			break;
        }
        $result -> free_result();
    }
    // print_r('<pre>');
    // print_r($arr_games);
    // print_r('</pre>');
}
function myParseContent($conn, $page, $cat, $thumb) {
    $xpath = myDOMXPath($page);

    $el_title = $xpath->query('//h1[@class="MuiTypography-root MuiTypography-h1 css-1xvbffa"]');
        $title = $el_title->item(0) ? mysqli_real_escape_string($conn, $el_title->item(0)->textContent) : '';
	$s_title = slugifyUnicode($title);
    
    $el_author = $xpath->query('//h3[text()="Developer"]/following-sibling::p');
        $f_author = $el_author->item(0) ? mysqli_real_escape_string($conn, $el_author->item(0)->textContent) : '';
	if (strpos($f_author, 'developed') !== false) {
		$author = explode(' developed ', $f_author)[0];
	} else {
		$el_author = $xpath->query('//div[h3/text()="Developer"]');
		$f_author = $el_author->item(0) ? mysqli_real_escape_string($conn, $el_author->item(0)->textContent) : '';
		$author = explode('\n', explode(' by ', $f_author)[1])[0];
	}
	$el_desc = $xpath->query('//div[@class="gameDescription_first css-1c11sc5"]');
        $desc = $el_desc->item(0) ? mysqli_real_escape_string($conn, $el_desc->item(0)->textContent) : '';

	$el_guide = $xpath->query('//h3[text()="Controls"]/following-sibling::p');
	if (null === $el_guide->item(0)) {
		$el_guide = $xpath->query('//h3[text()="Controls"]/following-sibling::ul');
	}
	$guide = $el_guide->item(0) ? mysqli_real_escape_string($conn, $el_guide->item(0)->textContent) : '';
		
    $el_link = $xpath->query('//iframe[@id="game-iframe"]');
        $link = $el_link->item(0) ? $el_link->item(0)->getAttribute('src') : '';
    echo $title.'-'.$link.'<br /><br />';
    
	$vote = rand(3,5);
	$vote_time = rand(10, 20);
	$play_time = rand(21, 1144);
		
    saveDB($conn, $title, $s_title, 'Unknown', $vote, $vote_time, $play_time, 0, $link, 'https://crazygames.com', $author, $cat, 0, 0, 0, 0, $desc, $guide, $thumb, 1);

}


function getAllGameDetail2($conn) { // kids
    $arr_games = array();
    $sql = "SELECT * FROM game_page";

    if ($result = $conn -> query($sql)) {
        ob_implicit_flush(true); // Support sleep()/usleep()
        ob_end_flush(); // Support sleep()/usleep()
        while ($row = $result -> fetch_row()) {
            $arr_game = array();
            array_push($arr_game, $row[2], $row[3], $row[4]);
            array_push($arr_games, $arr_game);
            myParseContent2($conn, $row[2], $row[3], $row[4]);
            usleep(300);
			break;
        }
        $result -> free_result();
    }
}
function myParseContent2($conn, $page, $cat, $thumb) { // kids
    $xpath = myDOMXPath($page);

    $el_title = $xpath->query('//h3[@class="MuiTypography-root MuiTypography-h3 css-1b0cs5f"]');
        $title = $el_title->item(0) ? mysqli_real_escape_string($conn, $el_title->item(0)->textContent) : '';
	$s_title = slugifyUnicode($title);
    
    $el_desc = $xpath->query('//div[@class="MuiGrid-root MuiGrid-item css-1hvuz4d"]/p');
        $desc = $el_desc->item(0) ? mysqli_real_escape_string($conn, $el_desc->item(0)->textContent) : '';
		
	$el_author = $xpath->query('//div[@class="MuiGrid-root MuiGrid-item css-1hvuz4d"]/p');
        $author = $el_author->item(1) ? mysqli_real_escape_string($conn, $el_author->item(1)->textContent) : '';
		
    $el_link = $xpath->query('//div[@class="MuiGrid-root MuiGrid-item css-1mttrpn"]/div/iframe');
        $link = $el_link->item(0) ? $el_link->item(0)->getAttribute('src') : '';
		
    echo $title.'-'.$link.'-'.$desc.'-'.$author.'<br />';
    
	$vote = rand(3,5);
	$vote_time = rand(10, 20);
	$play_time = rand(21, 1144);
		
	// When I code go here, I realize that kids.crazygame not allow embed :)))))
    // saveDB($conn, $title, $s_title, 'Unknown', $vote, $vote_time, $play_time, 0, $link, 'https://crazygames.com', $author, $cat, 0, 0, 0, 0, $desc, $guide, $thumb, 1);

}

// myParseContent($conn, 'https://www.crazygames.com/game/tuggowar-io', 14, 'abc.png');
// myParseContent($conn, 'https://www.crazygames.com/game/buildroyale-io', 14, 'abc.png');
// getListAllGame2($conn_kiddy, $arr_cats2);
getAllGameDetail2($conn_kiddy);

// getAllGameDetail($conn);
// myParseContent($conn, 'https://www.trochoi.net/trò+chơi/solitaire-klondike-2.html', 3, 'abc.png');
// putSlugForVN($conn);
// getListAllGame($conn, $arr_cats);
$conn_kiddy->close();

?>