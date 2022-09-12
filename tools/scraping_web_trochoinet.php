<?php

require_once('_common.php');

// $arr_cats = array(
// 3  => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/ch%C6%A1i+b%C3%A0i"),
// 4  => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/c%C3%A2u+c%C3%A1+%C4%91%C3%A1nh+c%C3%A1"),
// 6  => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/b%E1%BA%AFn"),
// 11 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/%C4%91ua"),
// 14 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/rpg"),
// 19 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/n%E1%BA%A5u+%C4%83n"),
// 22 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/phi%C3%AAu+l%C6%B0u"),
// 25 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/th%E1%BB%83+thao"),
// 26 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/th%E1%BB%9Di+trang"),
// 28 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/gh%C3%A9p+h%C3%ACnh+m%E1%BB%9Bi",
       // "https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/quiz"),
// 39 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/b%C3%B3ng+%C4%91%C3%A1"),  
// 41 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/x%C3%A2y+d%E1%BB%B1ng"),
// 42 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/x%E1%BA%BFp+h%C3%ACnh+lego"),  
// 43 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/minecraft"),
// 44 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/kh%E1%BB%A7ng+long"),  
// 45 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/c%E1%BA%A3nh+s%C3%A1t"),   
// 46 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/chi%E1%BA%BFn+tranh"),
// 47 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/m%C3%A1y+bay+chi%E1%BA%BFn+%C4%91%E1%BA%A5u"), 
// 48 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/b%E1%BA%AFn+t%E1%BB%89a"),
// 49 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/xe+t%E1%BA%A3i"),
// 50 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/t%C3%A0u+l%E1%BB%ADa"),   
// 51 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/%C4%91ua-xe-m%C3%A1y"), 
// 52 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/b%E1%BA%AFn+cung"),
// 53 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/elsa"),
// 54 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/uno"),
// 55 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/mario"),  
// 56 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/ch%C6%A1i+c%E1%BB%9D"),    
// 57 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/th%C3%BA+v%E1%BA%ADt"), 
// 58 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/n%C3%B4ng+tr%E1%BA%A1i"), 
// 59 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/tr%C3%AD+tu%E1%BB%87"),
// 60 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/to%C3%A1n"),
// 63 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/m%C3%A8o"),
// 64 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/tr%C3%B2+di%E1%BB%85n+l%E1%BA%A1i"),  
// 65 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/m%E1%BA%A1t+ch%C6%B0%E1%BB%A3c"),
// );

$arr_cats = array(
6 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/th%E1%BB%9Di+trang"),
28 => array("https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/gh%C3%A9p+h%C3%ACnh+m%E1%BB%9Bi",
       "https://www.trochoi.net/c%C3%A1c+tr%C3%B2+ch%C6%A1i/quiz")
);
$arr_games_link = array();
function getListAllGame($conn, $arr_cats) {
    $arr_link = array();
    ob_implicit_flush(true); // Support sleep()/usleep()
    ob_end_flush(); // Support sleep()/usleep()
    for ($i=0; $i<66; $i++) {
        if (isset($arr_cats[$i])) {
            $arr_link[$i] = array();
            for ($k=0; $k<sizeof($arr_cats[$i]); $k++) {
                array_push($arr_link[$i], getListGameByCat($conn, $i, $arr_cats[$i][$k]));
            }
        }
        usleep(300);
    }
    // print_r('<pre>');
    // print_r($arr_link);
    // print_r('</pre>');
    return $arr_link;
}
function getListGameByCat($conn, $cat, $page) { // input to game_page
    $xpath = myDOMXPath($page);
    
    // get first div have class=...
    $el_link = $xpath->query('//div[1][@class="gridCategoryPage__PageCategoryGrid-sc-1bi8huj-0 EzZJw"]//a');
    $el_thumb = $xpath->query('//div[1][@class="gridCategoryPage__PageCategoryGrid-sc-1bi8huj-0 EzZJw"]//a/img');
    $el_title = $xpath->query('//div[1][@class="gridCategoryPage__PageCategoryGrid-sc-1bi8huj-0 EzZJw"]//a/span');
    $arr_games_each_cat = array();
    $all_item = 0;
    $li_count = 0;
    for ($i=0; $i<$el_link->length; $i++) {
        if ($el_link->item($i))
            if (strpos($el_link->item($i)->getAttribute("href"), 'các+trò+chơi') == false) {
                $all_item++;
                $link = mysqli_real_escape_string($conn, 'https://www.trochoi.net'.$el_link->item($i)->getAttribute("href"));
                if ($el_thumb->item($i)) { // inside ul>li tag
                    $li_count++;
                    $thumb = mysqli_real_escape_string($conn, $el_thumb->item($i)->getAttribute("src"));
                }
                else {
                    $el_thumb2 = $xpath->query('//div[1][@class="gridCategoryPage__PageCategoryGrid-sc-1bi8huj-0 EzZJw"]//a/picture/img');
                    $thumb = mysqli_real_escape_string($conn, $el_thumb2->item($all_item-$li_count-1)->getAttribute("src"));
                }
                $title = mysqli_real_escape_string($conn, $el_title->item($i) ? $el_title->item($i)->textContent : '');
                // echo $title.' - '.$thumb.' - '.$all_item.' - '.$li_count.'<br />';
                array_push($arr_games_each_cat, $link);
                file_put_contents('../res/thumb/trochoinet/'.slugify($title).substr($thumb,-5), file_get_contents($thumb));
                saveDBPage($conn, 'trochoi.net', $link, $cat, slugify($title).substr($thumb,-5));
            }
        
    }
    return $arr_games_each_cat;
}
// $arr_games_link = getListAllGame($conn, $arr_cats);


// for ($p = 0; $p < sizeof($arr_page); $p++) {
//     $page = $arr_page[$p];
//     myParseContent($conn, $page);
//     usleep(500);
// }

function getAllGameDetail($conn) { // input to game
    $arr_games = array();
    $sql = "SELECT * FROM games_page";

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

    $el_title = $xpath->query('//h1[contains(@class, "DetailedTile__DetailedTileTitleText-sc-1ercfrx-4")]');
        $title = $el_title->item(0) ? mysqli_real_escape_string($conn, removeHeadTail($el_title->item(0)->textContent)) : '';
	$s_title = slugifyUnicode($title);
    
    $el_author = $xpath->query('//span[contains(@class, "DetailedTile__DetailedTileDeveloper-sc-1ercfrx-6")]');
        $author = $el_author->item(0) ? mysqli_real_escape_string($conn, removeHeadTail(substr($el_author->item(0)->textContent, 6))) : '';

    $el_link = $xpath->query('//iframe[@id="game-element"]');
        $link = $el_link->item(0) ? explode('?tag', $el_link->item(0)->getAttribute('src'))[0] : '';
    echo $title.'-'.$author.'<br />';
    
    // saveDB($conn, $title, $s_title, 'HTML5', $link, 'https://trochoi.net', $author, $cat, 0, 0, 0, 0, '', '', '', $thumb);
	updateTitle($conn, $title, $s_title, $author, $link);

}

// getAllGameDetail($conn);
// myParseContent($conn, 'https://www.trochoi.net/trò+chơi/solitaire-klondike-2.html', 3, 'abc.png');
// putSlugForVN($conn);
$conn->close();

?>