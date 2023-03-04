<?php

require_once('_common.php');

saveDB($conn, "title", "s_title", "Unknown", 2, 2, 2, 0, 0, "link", "https://crazygames.com", "author", 2, 0, 0, 0, 0, "desc", "guide", "thumb", 1, 0);



// print_r('<pre>');
// print_r(array_unique($a));
// print_r('</pre>');
// $b = array_unique($a);

// foreach($b as $i){
//     echo ''.$i . '"<br />';
// }

    // $page = "https://gamemonetize.com/abyssal-fish-game";
    // $htmlString = file_get_contents($page);   

    // //add this line to suppress any warnings
    // libxml_use_internal_errors(true);
    // $doc = new DOMDocument();
    // $doc->loadHTML($htmlString);
    // $xpath = new DOMXPath($doc);

    // $el_title = $xpath->query('//ul/li/a[@href[contains(., "https://gamemonetize.com/games?category")]]');
    //     $title = $el_title->item(1) ? $el_title->item(1)->textContent : '';
    // echo $title;


// function getCatId($conn, $name) {
//     $sql = 'SELECT id FROM game_cat WHERE g_cat_name = "'.$name.'"';
//     return $conn->query($sql)->fetch_object()->id ?? 61; // 61 is others
// }
//     echo getCatId($conn, "2 players");

