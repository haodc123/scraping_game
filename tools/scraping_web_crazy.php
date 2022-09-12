<?php

require_once('_common.php');

// $arr_cats = array(
// 6  => array("https://www.crazygames.com/c/shooting"),
// 11 => array("https://www.crazygames.com/c/driving"),
// 14 => array("https://www.crazygames.com/c/action"),
// 20 => array("https://www.crazygames.com/c/clicker"),
// 22 => array("https://www.crazygames.com/c/adventure"),
// 25 => array("https://www.crazygames.com/c/sports"),
// 26 => array("https://www.crazygames.com/c/beauty"),
// 28 => array("https://www.crazygames.com/c/puzzle"),
// 31 => array("https://www.crazygames.com/c/arcade"),  
// 34 => array("https://www.crazygames.com/c/io")
// );

$arr_cats = array(
34 => array("https://www.crazygames.com/c/io"));

$arr_games_link = array();
function getListAllGame($conn, $arr_cats) {
    $arr_link = array();
    ob_implicit_flush(true); // Support sleep()/usleep()
    ob_end_flush(); // Support sleep()/usleep()
    for ($i=0; $i<35; $i++) {
        if (isset($arr_cats[$i])) {
            $arr_link[$i] = array();
            for ($k=1; $k<4; $k++) { // pagination
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
function getListGameByCat($conn, $cat, $page) { // input to game_page
    $xpath = myDOMXPath($page);
    
    // get first div have class=...
    $el_link = $xpath->query('//a[@class="css-jerizh"]');
    $el_thumb = $xpath->query('//img[@class="css-ubgyh"]');
    $el_title = $xpath->query('//div[@class="css-1a9saot"]');
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
                // file_put_contents('../res/thumb/crazygames/'.makeThumbName($title, $thumb), file_get_contents($thumb));
                // saveDBPage($conn, 'crazygames.com', $link, $cat, makeThumbName($title, $thumb));
            
        
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
    echo $title.'-'.$author.'-'.$desc.'-'.$guide.'-'.$link.'<br /><br />';
    
	$vote = rand(3,5);
	$vote_time = rand(10, 20);
	$play_time = rand(21, 1144);
		
    saveDB($conn, $title, $s_title, 'Unknown', $vote, $vote_time, $play_time, 0, $link, 'https://crazygames.com', $author, $cat, 0, 0, 0, 0, $desc, $guide, $thumb, 1);

}

// myParseContent($conn, 'https://www.crazygames.com/game/tuggowar-io', 14, 'abc.png');
// myParseContent($conn, 'https://www.crazygames.com/game/buildroyale-io', 14, 'abc.png');
// myParseContent($conn, 'https://www.crazygames.com/game/bomber-friends', 14, 'bomberfriends.png');
getListAllGame($conn, $arr_cats);

// getAllGameDetail($conn);
// myParseContent($conn, 'https://www.trochoi.net/trò+chơi/solitaire-klondike-2.html', 3, 'abc.png');
// putSlugForVN($conn);
// getListAllGame($conn, $arr_cats);
$conn->close();

?>