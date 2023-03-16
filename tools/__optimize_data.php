<?php

require_once('_common.php');
require_once('PHPExcel/Classes/PHPExcel.php');
require_once('_readfile.php');
require_once('_writefile.php');

function putCatSlugForVN($conn) {
	$sql_get = "SELECT * FROM game_cat_lang";

    if ($result = $conn -> query($sql_get)) {
        while ($row = $result -> fetch_row()) {
			$id = $row[0];
            $slug = slugifyUnicode($row[2]);
            echo $slug.'<br />';
			$sql_update = "UPDATE game_cat_lang SET g_cat_slug = '".$slug."' WHERE g_cat_id = ".$id." AND lang = 'vi'";
            $conn -> query($sql_update);
        }
        $result -> free_result();
    }
}
function updateThumb($conn) {
	$sql_get = "SELECT id, site, thumb FROM game";

    if ($result = $conn -> query($sql_get)) {
        while ($row = $result -> fetch_row()) {
			$id = $row[0];
			$site = $row[1];
			switch ($site) {
				case 'https://gamemonetize.com':
					$thumb = 'monetize'.'/'.$row[2];
					break;
				case 'https://crazygames.com':
					$thumb = 'crazygames'.'/'.$row[2];
					break;
				case 'https://trochoi.net':
					$thumb = 'trochoinet'.'/'.$row[2];
					break;
				case 'https://y8.com':
					$thumb = 'y8'.'/'.$row[2];
					break;
				default:
					$thumb = 'others'.'/'.$row[2];
					break;
			}
			$sql_update = "UPDATE game SET g_thumb = '".$thumb."' WHERE id = ".$id;
            $conn -> query($sql_update);
        }
        $result -> free_result();
    }
}
function putTagsSlugForVN($conn) {
	$sql_get = "SELECT g_id, g_tags FROM game_lang";

    if ($result = $conn -> query($sql_get)) {
        while ($row = $result -> fetch_row()) {
			$id = $row[0];
            $slug = makeTags($row[1]);
            echo $slug.'<br />';
			$sql_update = "UPDATE game_lang SET g_tags_slug = '".$slug."' WHERE g_id = ".$id;
            $conn -> query($sql_update);
        }
        $result -> free_result();
    }
}
function putTagsSlugForMain($conn) {
	$sql_get = "SELECT id, g_cat_1, g_tags FROM game";

    if ($result = $conn -> query($sql_get)) {
        while ($row = $result -> fetch_row()) {
			$id = $row[0];
			$cat = $row[1];
			$tags = $row[2];
            $tags_slug = makeTags($tags);
			$sql_update = "UPDATE game SET g_tags_slug = '".$tags_slug."' WHERE id = ".$id;
            $conn -> query($sql_update);
        }
        $result -> free_result();
    }
}
function getDescGuideFromDB($conn) {
	// $data = [
		// ['Nguyễn Khánh Linh', 'Nữ', '500k'],
		// ['Ngọc Trinh', 'Nữ', '700k'],
		// ['Tùng Sơn', 'Không xác định', 'Miễn phí'],
		// ['Kenny Sang', 'Không xác định', 'Miễn phí']
	// ];
	
	$sql_get = "SELECT id, g_cat_1, g_tags, g_desc, g_guide FROM game";

	$arr_data = array();
    if ($result = $conn -> query($sql_get)) {
        while ($row = $result -> fetch_row()) {
			$arr_row = array();
			$id = $row[0];
			$cat = $row[1];
			$tags = $row[2];
			$desc = $row[3];
			$guide = $row[4];
			array_push($arr_row, $id, $cat, $tags, $desc, $guide);
			array_push($arr_data, $arr_row);
        }
        $result -> free_result();
    }
	
	print_r('<pre>');
	print_r($arr_data);
	print_r('</pre>');
	
	w_file($arr_data, 'data.xlsx');
}
function putDescGuideToDB($conn) {
	$data_r = r_file('data_t.xlsx', array(0,3,4));
	for ($i=0; $i<sizeof($data_r); $i++) {
		$sql_update = "UPDATE game_lang SET g_desc = '".$data_r[$i][3]."', g_guide = '".$data_r[$i][4]."' WHERE g_id = ".$data_r[$i][0];
        $conn -> query($sql_update);
	}
}


function addTagsByCat($conn, $last_id) {

	$arr_tags = array(
		2 => '2 Player,Soccer,Sport,Sports',
		3 => 'Card,uno,board',
		4 => 'Fishing,Shoot',
		5 => 'Animal,Cooking,Cute,Food,Fun,Girls,Kids',
		6 => '1 Player,3D game,Shoot,Shooting,Unity3D,weapons,war',
		11 => '1 Player,3D game,Car,Racing,Simulator,Unity3D',
		14 => '1 Player,Baby,Boys,Casual,Crazy,action,shooting',
		19 => '2D,Burger,Cook,Cooking,cut,Educational,Girls,food',
		20 => 'Battle,Monster,Shooter,Shooting,Sniper',
		22 => '.io game,Action,Adventure,Arcade,Car,Cars,action,akill',
		25 => 'Sport,Football,Soocer',
		26 => 'Adventure,Bejeweled,Fashion,Classic',
		28 => 'Puzzle,Intelligent,Skills',
		29 => '3D,3D game,Block,Board,Bricks,HTML5,Hypercasual',
		31 => 'Arcade,Bubble,Casual,Fish,Memory,Monster,Race,runn',
		34 => 'IO,IOgame',
		35 => 'Click,Clicker',
		36 => '3D,Android,Boy,Brain,Fun,HTML5,Kids,Math,Number',
		37 => '.io,.io game,3D,Android,AR',
		38 => 'Fight,Fun,Game,Multiplayer',
		39 => 'Ball,Ballon,Boy,Boys,Football,Matching,Shoot,Sport',
		41 => 'Buiding,assembly,skill,intelligent',
		43 => 'Minecraft',
		44 => 'T-rex,animal',
		45 => 'Police,strategy,skill',
		46 => 'Skill,war,action,shooting',
		48 => 'Shoot,action',
		49 => 'Truck,action',
		50 => 'Train,Skill,action',
		51 => 'Road-bike,bike,road,action,skill',
		52 => 'Archery,action,sport',
		53 => 'Elsa',
		54 => 'Card,uno',
		55 => 'Mario',
		56 => 'Chess,China,skill,intelligent',
		57 => 'Animal,dog,cat,cute',
		58 => 'Farm,agriculture,plant',
		59 => 'Skill,puzzle,intelligent',
		60 => 'Skill,math',
		63 => 'Cat,animal',
		64 => 'Simulation,action,skill',
		65 => 'Board-game,skill',
		61 => ''
	);
	
	$sql_get = "SELECT id, g_cat_1 FROM game WHERE id > ".$last_id;

    if ($result = $conn -> query($sql_get)) {
        while ($row = $result -> fetch_row()) {
			$id = $row[0];
			$g_cat_1 = $row[1];
			$sql_update = "UPDATE game SET g_tags = '".$arr_tags[$g_cat_1]."', g_tags_slug = '".makeTags($arr_tags[$g_cat_1])."' WHERE id = ".$id;
        $conn -> query($sql_update);
        }
        $result -> free_result();
    }
}

function randomVote($conn) {
	for ($i=0; $i<8000; $i++) {
		$g_vote = rand(3,5);
		$g_vote_time = rand(10, 20);
		$g_play_time = rand(21, 1144);
		$sql_update = "UPDATE game SET g_vote = ".$g_vote.", g_vote_time = ".$g_vote_time.", g_play_time = ".$g_play_time." WHERE id = ".$i;
        $conn->query($sql_update);
	}
}
function deleteDupplicate($conn) {
	$sql = "delete game
			   from game
			  inner join (
			     select max(id) as lastId, g_title_slug
			       from game
			      group by g_title_slug
			     having count(*) > 1) duplic on duplic.g_title_slug = game.g_title_slug
			  where game.id < duplic.lastId;";
	$sql2 = "delete game_lang
			   from game_lang
			  inner join (
			     select max(g_id) as lastId, g_title_slug
			       from game_lang
			      group by g_title_slug
			     having count(*) > 1) duplic on duplic.g_title_slug = game_lang.g_title_slug
			  where game_lang.g_id < duplic.lastId;";
			  // delete game_page
			  //  from game_page
			  // inner join (
			  //    select max(id) as lastId, page
			  //      from game_page
			  //     group by page
			  //    having count(*) > 1) duplic on duplic.page = game_page.page
			  // where game_page.id < duplic.lastId;
	$conn->query($sql);
	$conn->query($sql2);
}
function deleteNotAllowEmbed($conn) {
	$sql = "delete from game where g_alloed_embed = 0 and g_site = 'https://y8.com';";
	$conn->query($sql);
}
function deleteBySite($conn) {
	$sql = "UPDATE game set deleted_at = NOW() WHERE g_site = 'https://trochoi.net' and g_cat_1 NOT in (35, 39, 41, 45, 50, 51, 53, 55, 57, 60, 63, 65)";
	$conn->query($sql);
}

function addFreeGame($conn) {
	$title = '8 BALL POOL STARS 1';
	$link = 'https://html5.gamedistribution.com/8fbe293962c0492e8f361f4a391b346a';
	$author = '';
	$cat = 59; //skill
	$thumb = '8 BALL POOL STARS 1.jpeg';
	$allow_embed = 1;
	saveDB($conn, $title, slugifyUnicode($title), 'HTML5', 5, 1000, 2623, 2, $link, 'others', $author, $cat, 0, 0, 0, 0, '', '', $thumb, $allow_embed);
}
function addFreeGame2($conn) {
	$title = '3d Billiard 8 ball Pool';
	$link = 'https://html5.gamemonetize.co/jf2a06o1z5ezy7stb7c4lmm13syg12yk/';
	$author = '';
	$cat = 59; 
	$thumb = '3d Billiard 8 ball Pool.jpg';
	$allow_embed = 1;
	saveDB($conn, $title, slugifyUnicode($title), 'HTML5', 5, 1000, 2123, 2, $link, 'others', $author, $cat, 0, 0, 0, 0, '', '', $thumb, $allow_embed);
}
function addFreeGame3($conn) {
	$title = 'Gartic phone';
	$link = 'https://garticphone.com/';
	$author = '';
	$cat = 59; 
	$thumb = 'gartic-phone.jpg';
	$allow_embed = 1;
	saveDB($conn, $title, slugifyUnicode($title), 'HTML5', 5, 1000, 2123, 2, $link, 'others', $author, $cat, 0, 0, 0, 0, '', '', $thumb, $allow_embed);
}
function addFreeGame4($conn) {
	$title = 'Tetris online';
	$link = 'https://tetris.com/games-content/play-tetris-content/resources/project-tetriscom/game/game-8DB7D5BEC8BE7216/if_game_html5.php';
	$author = '';
	$cat = 59;
	$thumb = 'tetris-online.jpg';
	$allow_embed = 1;
	saveDB($conn, $title, slugifyUnicode($title), 'HTML5', 5, 1000, 2123, 2, $link, 'others', $author, $cat, 0, 0, 0, 0, '', '', $thumb, $allow_embed);
}
function addFreeGame5($conn) {
	$title = 'Krunker';
	$link = 'https://krunker.io/?game=SIN:osum9';
	$author = '';
	$cat = 14;
	$thumb = 'krunkerio.png';
	$allow_embed = 1;
	saveDB($conn, $title, slugifyUnicode($title), 'HTML5', 5, 1000, 2123, 2, $link, 'others', $author, $cat, 0, 0, 0, 0, '', '', $thumb, $allow_embed);
}
function addFreeGame6($conn) {
	$title = 'Subway Clash 3D';
	$link = 'https://www.y8.com/embed/subway_clash_3d';
	$author = '';
	$cat = 14;
	$thumb = 'subway-clash-3d.png';
	$allow_embed = 1;
	saveDB($conn, $title, slugifyUnicode($title), 'HTML5', 5, 1000, 2123, 2, $link, 'others', $author, $cat, 0, 0, 0, 0, '', '', $thumb, $allow_embed);
}
function addFreeGame7($conn) {
	$title = 'Snake game';
	$link = 'https://playsnake.org/';
	$author = '';
	$cat = 65;
	$thumb = 'snake.png';
	$allow_embed = 1;
	saveDB($conn, $title, slugifyUnicode($title), 'HTML5', 5, 1000, 2123, 2, $link, 'others', $author, $cat, 0, 0, 0, 0, '', '', $thumb, $allow_embed);
}
function addFreeGame8($conn) {
	$title = 'Cookie clicker';
	$link = 'https://orteil.dashnet.org/cookieclicker/';
	$author = '';
	$cat = 20;
	$thumb = 'cookie-clicker.png';
	$allow_embed = 1;
	saveDB($conn, $title, slugifyUnicode($title), 'HTML5', 5, 1000, 767, 2, $link, 'others', $author, $cat, 0, 0, 0, 0, '', '', $thumb, $allow_embed);
}
function addFreeGame9($conn) {
	$title = 'Minesweeper';
	$link = 'https://www.y8.com/embed/minesweeper';
	$author = '';
	$cat = 59;
	$thumb = 'minesweeper.gif';
	$allow_embed = 1;
	saveDB($conn, $title, slugifyUnicode($title), 'HTML5', 5, 1000, 2123, 2, $link, 'others', $author, $cat, 0, 0, 0, 0, '', '', $thumb, $allow_embed);
}
function addFreeGame10($conn) {
	$title = 'Minesweeper io';
	$link = 'https://www.y8.com/embed/minesweeper_io_';
	$author = '';
	$cat = 59;
	$thumb = 'minesweeper-io.gif';
	$allow_embed = 1;
	saveDB($conn, $title, slugifyUnicode($title), 'HTML5', 5, 1000, 2123, 2, $link, 'others', $author, $cat, 0, 0, 0, 0, '', '', $thumb, $allow_embed);
}
function addFreeGame11($conn) {
	$title = 'Happy wheels';
	$link = 'https://happywheels2.io/happy-wheels.embed';
	$author = '';
	$cat = 59;
	$thumb = 'happy-wheels.jpg';
	$allow_embed = 1;
	saveDB($conn, $title, slugifyUnicode($title), 'HTML5', 5, 543, 2123, 1, $link, 'others', $author, $cat, 0, 0, 0, 0, '', '', $thumb, $allow_embed);
}
function updateNotMobi($conn) {
	$sql = "update game set g_not_mobi = 1 where g_type = 'WebGL' and g_site = 'https://y8.com';";
	$conn->query($sql);
}

function addCatT($conn) {
	// SELECT g_cat_t, count(*) as num FROM `game` where g_cat_t != '' group by g_cat_t;
	$sql = 'INSERT INTO game_cat (g_cat_name, g_cat_slug, g_cat_tags, g_cat_tags_slug, g_cat_number) 
			SELECT g_cat_t_name, g_cat_t_slug, CONCAT("Puzzle,Intelligent,Skills,smart,kid,", g_cat_t_name), CONCAT("#puzzle,#intelligent,#skills,#smart,#kid,#", g_cat_t_slug), g_cat_t_number FROM game_tag';
	if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function convertCatT($conn) {
	$sql = 'UPDATE game g JOIN game_cat gc ON g.g_cat_t=gc.g_cat_name SET g.g_cat_2 = gc.id';
	$conn->query($sql);
}

function common($conn) {
	$sql = 'UPDATE game SET g_cat_yo = 7 WHERE g_cat_1 = 65';
	if ($conn->query($sql) === TRUE) {
      echo "New record created successfully";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
// updateNotMobi($conn);
// deleteDupplicate($conn);
// convertCatT($conn_kiddy);
// addFreeGame2($conn);
// addFreeGame3($conn);
// addFreeGame4($conn);
// addFreeGame5($conn);
// addFreeGame6($conn);
// addFreeGame7($conn);
// addFreeGame8($conn);
// addFreeGame9($conn);
// addFreeGame10($conn);
// addFreeGame11($conn);

// randomVote($conn);
// getDescGuideFromDB($conn);
// putDescGuideToDB($conn);
// echo findUltimateDestination('https://play.famobi.com/moto-x3m');

// putNewGameToFile($conn, 7562);
// deleteDupplicate($conn);

// last id 9308/ 11634




//////////////////// For TFlashGame

////////// Add new Categories
/**
* Procedure when insert many categories:
* 1. Prepare file/gmae_cat.xlsx and Translate game_cat -> game_cat_$lang, carefully check
* 2. insertNewCat($conn): (*** NOTE: If only add for new lang, just run 2.2
*  2.1. Read game_cat.xlsx and insert to [game_cat]
*  2.2. Read game_cat_$lang.xlsx ond insert to [game_cat_lang]
*/
// Step 1
function putCatToFile($conn, $last_id) {
	// game.xlsx
	$sql_get = "SELECT id, g_cat_name, g_cat_slug, g_cat_tags, g_cat_tags_slug FROM game_cat WHERE id > ".$last_id;

	$arr_data = array();
    if ($result = $conn -> query($sql_get)) {
        while ($row = $result -> fetch_row()) {
			$arr_row = array();
			$id = $row[0];
			$g_cat_name = $row[1];
			$g_cat_slug = $row[2];
			$g_cat_tags = $row[3];
			$g_cat_tags_slug = $row[4];
			array_push($arr_row, $id, $g_cat_name, $g_cat_slug, $g_cat_tags, $g_cat_tags_slug);
			array_push($arr_data, $arr_row);
        }
        $result -> free_result();
    }
	
	print_r('<pre>');
	print_r($arr_data);
	print_r('</pre>');
	
	w_file($arr_data, 'game_cat.xlsx');
}
// Step 2
function insertNewCat($conn, $lang = 'vi') {
	// Step 2.1
	putNewCats($conn);
	// Step 2.2
	putNewCatsToLang($conn, $lang);
}
function putNewCats($conn) {
	// game_cat.xlsx
	$data_r = r_file('game_cat.xlsx', array(0,1,2,3,4));
	for ($i=0; $i<sizeof($data_r); $i++) {
		$id = $data_r[$i][0];
		$name = $data_r[$i][1];
		$slug = slugifyUnicode($name);
		$cat_tags = $data_r[$i][3];
		$cat_tags_slug = $data_r[$i][4];
		$sql_update = "INSERT INTO game_cat (id, g_cat_name, g_cat_slug, g_cat_tags, g_cat_tags_slug) VALUES (".$id.", '".$name."', '".$slug."', '".$cat_tags."', '".$cat_tags_slug."')";
        $conn -> query($sql_update);
	}
}
function putNewCatsToLang($conn, $lang) {
	// game_cat_$lang.xlsx
	$data_r = r_file('game_cat_'.$lang.'.xlsx', array(0,1,2,3,4));
	for ($i=0; $i<sizeof($data_r); $i++) {
		$id = $data_r[$i][0];
		$name = $data_r[$i][1];
		$slug = slugifyUnicode($name);
		$cat_tags = $data_r[$i][3];
		$cat_tags_slug = $data_r[$i][4];
		$sql_update = "INSERT INTO game_cat_lang (g_cat_id, lang, g_cat_name, g_cat_slug, g_cat_tags, g_cat_tags_slug) VALUES (".$id.", '".$lang."', '".$name."', '".$slug."', '".$cat_tags."', '".$cat_tags_slug."')";
        $conn -> query($sql_update);
		// echo $cat_tags;
	}
}

///////// Insert game by scrap tool
/**
 * Procedure when scrap and insert games:
 * 0. Download DB exported file fom web, import to DB local, Save last id (max) of [game]
 * 1.     gamemonetize
 *   1a. Run live live_scraping_web_gamemonetize.html -> Get List detail game page
 *   1b. Run scraping_web_gamemonetize.php -> Parse and insert [game], image in ../res/thumb/monetize/
 * 2.     crazy
 *   2a. Get list categories manually, or keep previous
 *   2b. Get list page, insert [game_page], (should keep this table data) 
 *   2c. Get list detail game, inser [game]
 * 3.     trochoi.net
 * 4.     y8
 */
// Last id 13548


///////// Add new games to lang
/**
* Procedure when insert games to lang: (NOTE: Should do after filter game)
*
* 0. Save last id (max) of [game], Insert game by scrap tool
* 1. Create file/gmae.xlsx -> putNewGameToFile($conn, $last_id) 
* 2. Translate game.xlsx -> game_$lang, carefully check, copy title, title_slug from game.xlsx to game_$lang.xlsx (keep lang en)
*      (Title, title_slug no need translate)
* 3. Copy game to game_lang, add slug -> putNewGameToLang($conn)
*/
// Step 1
function putNewGameToFile($conn, $last_id) {
	// game.xlsx
	$sql_get = "SELECT id, g_title, g_title_slug, g_desc, g_guide FROM game WHERE id > ".$last_id." AND deleted_at IS NULL";

	$arr_data = array();
    if ($result = $conn -> query($sql_get)) {
        while ($row = $result -> fetch_row()) {
			$arr_row = array();
			$id = $row[0];
			$title = $row[1];
			$title_slug = $row[2];
			$desc = $row[3];
			$guide = $row[4];
			array_push($arr_row, $id, $title, $title_slug, $desc, $guide);
			array_push($arr_data, $arr_row);
        }
        $result -> free_result();
    }
	
	print_r('<pre>');
	print_r($arr_data);
	print_r('</pre>');
	
	w_file($arr_data, 'game.xlsx');
}
// Step 3
function putNewGameToLang($conn, $lang) {
	// game_$lang.xlsx
	$data_r = r_file('game_'.$lang.'.xlsx', array(0,1,2,3,4,5));
	for ($i=0; $i<sizeof($data_r); $i++) {
		$id = $data_r[$i][0];
		$title = mysqli_real_escape_string($conn, $data_r[$i][1]);
		$title_slug = mysqli_real_escape_string($conn, $data_r[$i][2]);
		$desc = mysqli_real_escape_string($conn, $data_r[$i][3]);
		$guide = mysqli_real_escape_string($conn, $data_r[$i][4]);
		echo $id.' - '.$title.' - '.$title_slug.' - '.$desc.' - '.$guide.'<br />';
		$sql_update = "INSERT INTO game_lang (g_id, lang, g_title, g_title_slug, g_desc, g_guide) VALUES (".$id.", '".$lang."', '".$title."', '".$title_slug."', '".$desc."', '".$guide."')";
        $conn -> query($sql_update);
	}
}

putNewCatsToLang($conn, 'id');

$conn->close();
$conn_kiddy->close();

?>