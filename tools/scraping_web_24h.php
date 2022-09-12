<?php

require_once('_common.php');

function getListCats() {
    $xpath = myDOMXPathHuman('https://gamevui.vn/nhin-hinh-doan-chu-tieng-anh-2/game');
    
    $el_link = $xpath->query('//iframe[@id="giframe"]');
        $link = $el_link->item(0) ? $el_link->item(0)->getAttribute("src") : '';
    echo $link.'<br />';
}

getListCats();
$arr_page_t = array(
    "https://gamemonetize.com/10x10-fill-the-grid-game", 
    "https://gamemonetize.com/mountain-car-driving-simulation-game", 
    "https://gamemonetize.com/abyssal-fish-game"
);

// ob_implicit_flush(true); // Support sleep()/usleep()
// ob_end_flush(); // Support sleep()/usleep()
// for ($p = 0; $p < sizeof($arr_page); $p++) {
//     $page = $arr_page[$p];
//     myParseContent($conn, $page);
//     usleep(500);
// }
//myParseContent($conn, $site.$arr_page[0]);
$conn->close();

function myParseContent($conn, $page) {
    $xpath = myDOMXPath($page);

    $el_title = $xpath->query('//h2[@class="hero section__title main-games__title"]');
        $title = $el_title->item(0) ? mysqli_real_escape_string($conn, removeHeadTail($el_title->item(0)->textContent)) : '';
    $s_title = slugify($title);
    
    $el_author = $xpath->query('//a[@id="companyLinkId"]');
        $author = $el_author->item(0) ? mysqli_real_escape_string($conn, $el_author->item(0)->textContent) : '';

    $el_link = $xpath->query('//textarea[@id="urlTextAreaId"]');
        $link = $el_link->item(0) ? $el_link->item(0)->textContent : '';

    $el_type = 'HTML5';

    $el_cat = $xpath->query('//ul/li/a[@href[contains(., "https://gamemonetize.com/games?category")]]');
        $cat1 = $el_cat->item(0) ? getCatId($conn, $el_cat->item(0)->textContent) : 0;
        $cat2 = $el_cat->item(1) ? getCatId($conn, $el_cat->item(1)->textContent) : 0;
        $cat3 = $el_cat->item(2) ? getCatId($conn, $el_cat->item(2)->textContent) : 0;
        $cat4 = $el_cat->item(3) ? getCatId($conn, $el_cat->item(3)->textContent) : 0;
        $cat5 = $el_cat->item(4) ? getCatId($conn, $el_cat->item(4)->textContent) : 0;

    $el_tags = $xpath->query('//ul/li/a[@href[contains(., "https://gamemonetize.com/games?tags")]]');
        $arr_tags=array();
        for ($i=0; $i<$el_tags->length; $i++) {
            array_push($arr_tags, $el_tags->item($i) ? mysqli_real_escape_string($conn, $el_tags->item($i)->textContent) : '');
        }
    
    $el_desc = $xpath->query('//p[@id = "descriptionId"]');
        $desc = $el_desc->item(0) ? mysqli_real_escape_string($conn, $el_desc->item(0)->textContent) : '';
        $guide = $el_desc->item(1) ? mysqli_real_escape_string($conn, removeHeadTail($el_desc->item(1)->textContent)) : '';

    $el_thumb = $xpath->query('//div[a/text() = "Download"]/a');
        $thumb = $el_thumb->item(0) ? $el_thumb->item(0)->getAttribute("href") : '';
    
    file_put_contents('../res/thumb/'.$s_title.'.jpeg', file_get_contents($thumb));

    saveDB($conn, $title, $s_title, 'HTML5', $link, 'https://gamemonetize.com', $author, $cat1, $cat2, $cat3, $cat4, $cat5, implode(',',$arr_tags), $desc, $guide, $s_title.'.jpeg');

}


?>