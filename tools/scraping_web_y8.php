<?php

require_once('_common.php');

$arr_cats = array(
2 => array("https://www.y8.com/tags/2_players/y8_account"),
43 => array("https://www.y8.com/search?utf8=%E2%9C%93&kind=game&q=minecraft"),
11 => array("https://www.y8.com/categories/driving_racing/y8_account"),
6 => array("https://www.y8.com/categories/shooting/y8_account"),
7 => array("https://www.y8.com/search?utf8=%E2%9C%93&kind=game&q=stick+war",
    "https://www.y8.com/categories/strategy/y8_account"),
22 => array("https://www.y8.com/categories/action_adventure/y8_account"),
25 => array("https://www.y8.com/categories/sports/y8_account"),
28 => array("https://www.y8.com/categories/thinking/y8_account"),
31 => array("https://www.y8.com/categories/fun/y8_account"),
59 => array("https://www.y8.com/search?utf8=%E2%9C%93&kind=game&q=slope",
    "https://www.y8.com/categories/skill/y8_account"),
14 => array("https://www.y8.com/search?utf8=%E2%9C%93&kind=game&q=game+ranger",
    "https://www.y8.com/categories/fighting/y8_account"),
65 => array("https://www.y8.com/search?utf8=%E2%9C%93&kind=game&q=poker"),
34 => array("https://www.y8.com/search?utf8=%E2%9C%93&kind=game&q=io"),
61 => array("https://www.y8.com/search?utf8=%E2%9C%93&kind=game&q=bubble+shooter"),
);

$arr_cats2 = array(
14 => array("https://www.y8.com/tags/escape/y8_account"),
64 => array("https://www.y8.com/tags/simulation/y8_account"),
28 => array("https://www.y8.com/tags/educational/y8_account")
);

function getListAllGame($conn, $arr_cats) {
    $arr_link = array();
    ob_implicit_flush(true); // Support sleep()/usleep()
    ob_end_flush(); // Support sleep()/usleep()
    for ($i=22; $i<70; $i++) {
        if (isset($arr_cats[$i])) {
            $arr_link[$i] = array();
            for ($k=0; $k<sizeof($arr_cats[$i]); $k++) {
                if (strpos($arr_cats[$i][$k], 'categories') === false)
                    array_push($arr_link[$i], getListGameByCat($conn, $i, $arr_cats[$i][$k]));
                else
                    for ($l=1; $l<4; $l++) { // pagination
                        array_push($arr_link[$i], getListGameByCat($conn, $i, $arr_cats[$i][$k].'?page='.$l));
                    }
            }
        }
        usleep(200);
    }
    // print_r('<pre>');
    // print_r($arr_link);
    // print_r('</pre>');
    return $arr_link;
}
function getListGameByCat($conn, $cat, $page) { // input to game_page
    $xpath = myDOMXPath($page);
    
    // get first div have class=...
    $el_link = $xpath->query('//div[@class="item thumb videobox grid-column"]/a');
    $el_thumb = $xpath->query('//div[@class="item__img-container"]//img');
    $el_title = $xpath->query('//h4[@class="item__title ltr"]');
    $el_type = $xpath->query('//div[@class="item__technology"]');
    $arr_games_each_cat = array();
    $all_item = 0;
    $li_count = 0;
    for ($i=0; $i<$el_link->length; $i++) {
        if ($el_link->item($i))
                $all_item++;
                $link = mysqli_real_escape_string($conn,$el_link->item($i)->getAttribute("href"));
                $thumb_link = mysqli_real_escape_string($conn, $el_thumb->item($i)->getAttribute("data-src"));
                $title = mysqli_real_escape_string($conn, $el_title->item($i) ? $el_title->item($i)->textContent : '');
                $type = trim(mysqli_real_escape_string($conn, $el_type->item($i) ? $el_type->item($i)->textContent : ''), '\n ');
                echo $title.' - '.$thumb_link.' - '.$type.' - '.$link.' - '.makeThumbName($title, $thumb_link).'<br />';
                
                array_push($arr_games_each_cat, $link);
                file_put_contents('../res/thumb/y8/'.makeThumbName($title, $thumb_link), file_get_contents($thumb_link));
                saveDBPage($conn, 'y8.com', $link, $cat, makeThumbName($title, $thumb_link), $type);
            
    }
    return $arr_games_each_cat;
}

function getSpecifiedGameDetail($conn, $id) {
    $sql = "SELECT * FROM game_page where id = ".$id;

    if ($result = $conn -> query($sql)) {
        ob_implicit_flush(true); // Support sleep()/usleep()
        ob_end_flush(); // Support sleep()/usleep()
        while ($row = $result -> fetch_row()) {
            $arr_game = array();
            array_push($arr_game, $row[2], $row[3], $row[4], $row[5]);
            array_push($arr_games, $arr_game);
            myParseContent($conn, $row[2], $row[3], $row[4], $row[5]);
            usleep(200);
            
        }
        $result -> free_result();
    }
}
function getAllGameDetail($conn) { // input to game
    $arr_games = array();
    $sql = "SELECT * FROM game_page";

    if ($result = $conn -> query($sql)) {
        ob_implicit_flush(true); // Support sleep()/usleep()
        ob_end_flush(); // Support sleep()/usleep()
        while ($row = $result -> fetch_row()) {
            $arr_game = array();
            array_push($arr_game, $row[2], $row[3], $row[4], $row[5]);
            array_push($arr_games, $arr_game);
            myParseContent($conn, $row[2], $row[3], $row[4], $row[5]);
            usleep(500);
            
        }
        $result -> free_result();
    }
    // print_r('<pre>');
    // print_r($arr_games);
    // print_r('</pre>');
}
function myParseContent($conn, $page, $cat, $thumb, $type) {
    $xpath = myDOMXPath($page);

    $el_title = $xpath->query('//div[@class="left-part"]/h1');
        $title = $el_title->item(0) ? trim(mysqli_real_escape_string($conn, $el_title->item(0)->textContent), '\n ') : '';
	$s_title = slugifyUnicode($title);
    
    $el_author = $xpath->query('//div[contains(span/text(), "Developer:")]/span');
        $author = $el_author->item(1) ? trim(mysqli_real_escape_string($conn, $el_author->item(1)->textContent), '\n ') : '';

	$el_desc = $xpath->query('//h2[@class="ltr description"]');
        $desc = $el_desc->item(0) ? trim(mysqli_real_escape_string($conn, $el_desc->item(0)->textContent), '\n ') : '';
		
    $el_link = $xpath->query('//textarea[@id="embed-text"]/iframe');
    $allow_embed = 0;
    if ($el_link->item(0)) {
        $allow_embed = 1;
    }
    $link = str_replace('games', 'embed', $page);
    echo $title.'-'.$author.'-'.$desc.'-'.$link.' - '.$allow_embed.'<br /><br />';
    
	$vote = rand(3,5);
	$vote_time = rand(10, 20);
	$play_time = rand(21, 1144);
		
    saveDB($conn, $title, $s_title, $type, $vote, $vote_time, $play_time, $link, 'https://y8.com', $author, $cat, 0, 0, 0, 0, $desc, '', $thumb, $allow_embed);

}

getAllGameDetail($conn);
// myParseContent($conn, 'https://www.crazygames.com/game/tuggowar-io', 14, 'abc.png');
// myParseContent($conn, 'https://www.crazygames.com/game/buildroyale-io', 14, 'abc.png');
// myParseContent($conn, 'https://www.crazygames.com/game/bomber-friends', 14, 'bomberfriends.png');


// myParseContent($conn, 'https://www.trochoi.net/trò+chơi/solitaire-klondike-2.html', 3, 'abc.png');
// putSlugForVN($conn);
// getListAllGame($conn, $arr_cats2);
$conn->close();

?>