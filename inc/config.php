<?php

// I wish I could use const x = y; format but unfortunately const is unable
// to have any kind of expression in it, it seems.

define("NAME", "Elemovements");
define("URL", "el.x10.mx");

define("DATE_FMT", "F j<\s\u\p><\u>S</\u></\s\u\p>, Y \a\\t g:i A");

define("DIR_BASE", "el");
define("DIR_ETC", "etc");
define("DIR_IMG", "img");
define("DIR_JS", "js");
define("DIR_JS_LOGIC", DIR_JS . "/logic");
define("DIR_TEMPLATES", "templates");

// Other image directories

define("DIR_BAND", DIR_IMG . "/band");
define("DIR_COVERS", DIR_IMG . "/covers");
define("DIR_SLIDER", DIR_IMG . "/slider");

define("FB_COMMENTS_NUM", 5);
define("FB_COMMENTS_WIDTH", 619);

define("FILE_SMARTY", "smarty/Smarty.class.php");

define("ID_FB", 156848747719647);
define("ID_FB_ADMINS", "100000142903767,6512161,100003578410731");
define("ID_FB_APP", 333026093402769);
define("ID_FB_SEC", "cbd780cbf109fa395d2e96148f8937cc");

define("TEXT_ARCHIVE", "Click on the links to the right to load content.");
define("TEXT_ARCHIVE_TITLE", 'News Archive <span class="links"><a class="latest" href="#">Latest</a> · <a class="archive" href="archive">Archive</a> · <a href="news/" target="_blank">Login</a></span>');
define("TEXT_BACK", "« Back");

define("TEXT_BIO",

"Rooted in Funk-Jazz and Rock, Elemovements is designed to be dynamic. The band uses all aspects of music in order to create a sound music lovers of all types will enjoy. Some of the styles commonly experienced at Elemovements shows include: Funk, Jazz, Rock, Salsa, Samba, Afro-Beat, Bluegrass, Country, Hip-Hop. \"I really just wanted to incorporate all kinds of music as to not be limited. Somewhat like the elements in nature, all the musical elements should be there too,\" says Barrett O'Donnell (bass). The band will record their first studio effort this winter.
	
	Elemovements was founded by brothers Barrett and Thaddeus O'Donnell in 2010. After playing in numerous bands in the Memphis, TN - North Mississipi area the brothers decided to team up and form a group of their own.
	
	After toying with the lineup through many gigs the band solidified with veteran musicians Fred Dunlap (Bill Perry Trio, The Guruvs) on drums, Jason Ball (Zoogma, Gigantic Ant, Kristen Ford) on guitar, and Steven McCain (Hancock Co.) on bass. The group continues to incorporate percussionists, horn sections, and keyboardists in their live shows."

);

// You might want to think of a way to make use of some more divs and jQuery's unwrap() function to implement the titles
// such as for the news archive: instead of having a redundant definition, merely change the "title" div's text to "News Archive"
// then wrap() the "Latest" link, etc.

define("TEXT_DIVIDER", " - ");
define("TEXT_MIN_F", "min/?f=" . ( ( $_SERVER['HTTP_HOST'] == "localhost" ) ? parse_url( $_SERVER['REQUEST_URI'] , PHP_URL_PATH) : "" ) );
define("TEXT_NEWS_TITLE", 'Latest News <span class="links">Latest · <a class="archive" href="archive">Archive</a> · <a href="news/" target="_blank">Login</a></span>');
define("TEXT_NO_DATES", 'There are no upcoming tour dates.');
define("TEXT_NO_JS", '<noscript><h6>Turn on JavaScript to enable this feature.</h6></noscript>');
define("TEXT_NO_MUSIC", 'Elemovements is currently in the process of mixing and finishing their debut studio release.  Until it\'s finished check out some rough cuts from their sessions on ');
define("TEXT_NO_MUSIC_ADD_DESK", 'the player to the left');
define("TEXT_NO_MUSIC_ADD_IPHONE", 'ReverbNation');

define("URL_FB", "http://www.facebook.com/elemovements");
define("URL_MS", "http://www.myspace.com/elemovements");
define("URL_NEWS", "news/news.php");
define("URL_RN", "http://www.reverbnation.com/2083759");
define("URL_TOUR_RSS", "http://www.reverbnation.com/rss/artist_shows_rss/elemovements");
define("URL_TW", "http://www.twitter.com/elemovements");
define("URL_YT", "http://www.youtube.com/user/kbodonne");

// I have to define this down here because URL_TOUR_RSS isn't defined before the TEXT_ definitions.

define("TEXT_TOUR_TITLE", 'Tour Schedule <span class="links"><a href="' . URL_TOUR_RSS . '" target="_blank">RSS</a></span>');

define("TEXT_FB", '<span class="links"><a class="fblink" href="' . URL_FB . '?sk=photos" target="_blank">View on Facebook</a></span>');

define("WIDGET_ARTIST_ID", 2083759);
define("WIDGET_AUTOPLAY", "true");
define("WIDGET_FONT_COLOR", "222222");
define("WIDGET_MAP", "true");
define("WIDGET_SHUFFLE", "false");

$aBios = array(
					
					"Barrett" => "O'Donnell|After learning the basics of guitar at an early age from his father Kevin, a session player in the St. Louis area in the 70's and 80's, Barrett picked up the bass in college for the first time.  Playing with groups including Hot Tub Ricky (Tedo Stone), Slightly to the Middle, and Gonzo and the Clean Sneak, Barrett continues to advance his musical knowledge and collaborate with musicians.",
					
					"Fred" => "Dunlap III|Fred is the hardest working musician in North Mississippi.  Fred is currently completing his Masters Degree in Percussion Performance from the University of Mississippi. Fred plays with the Ole Miss Jazz Ensemble, African Drum Ensemble, Steel Drum Ensemble and Orchestra. Not to mention his performances with club groups including; Garry Burnside, Bill Perry Trio, Pitchecanfunkus Erectus, The Guruvs just to name a few.",
					
					"Jason" => "Ball|Jason's young music career has already taken him across the globe.  After helping found the electronic-rock group Zoogma in 2007, Jason enrolled in the Berkley College of Music in Boston.  There Jason collaborated with many artists including Kristen Ford and the experimental-jazz group Gigantic Ant.  Jason has since moved back to Oxford, MS with his wife and continues to study and teach music in the area.  He also plays with the Legit Jazz Quintet, and Casey Lipe.",
					
					"Steven" => "McCain|Steve is a well versed musician in Mississippi.  Playing with a slew of musicians too numerous to name, Steve has crafted a sound and ear that meshes with any style. Steve, a slap bass master, has sat in with almost every group that plays in North Mississippi and continues to collaborate with musicians of all genres.",
					
					"Thaddeus" => "O'Donnell|Thad’s encyclopedic knowledge of classic rock continues to impress his cohorts and audiences.  Thad’s first band, the short lived experimental-funk group, The Wave Function, allowed Thad to expand his skills at an early age: just 15. After playing with many different groups and a vast variety of styles, Thad continues to impress both young and old with a sound that incorporates styles of the rock of ages past with a vision of the new wave of music.",
					
		 );

// If we're in the working in the main directory, read the links file.

$sCurrDir = basename( getcwd() );

//if ( $sCurrDir == DIR_BASE )
	$aLinks = file(DIR_ETC . "/links.conf");

$aMeta = array();
$aTitles = array();

// Meta descriptions

$aMeta["archive"] = "Archives of all of " . NAME . "' news.";
$aMeta["biography"] = "Check out " . NAME . "' complete history and the members' individual biographies!";
$aMeta["contact"] = "Get all the contact information for " . NAME . " so you can book them or get in touch.";
$aMeta["home"] = "Rooted in Funk-Jazz and Rock, the experimental nature of Elemovements allows the band to incorporate everything from Bluegrass to Hip-Hop, continuing a tradition that proves to be an enriching experience for the music lover in everyone.";
$aMeta["music"] = "Learn more about " . NAME . "' albums and download some of their tracks in a variety of formats.";
$aMeta["pictures"] = "See all of " . NAME . "' pictures streamed straight from their Facebook page.";
$aMeta["tour"] = "Make sure you know when and where " . NAME . " will be playing next!";

// These are the titles for the content boxes.

$aTitles["404"] = "Not Good";
$aTitles["archive"] = TEXT_ARCHIVE_TITLE;
$aTitles["biography"] = "Band Biography";
$aTitles["contact"] = "Contact Us";
$aTitles["comments-box"] = "Comments";
$aTitles["home"] = TEXT_NEWS_TITLE;
$aTitles["music"] = "Music";
$aTitles["pictures"] = "Pictures " . TEXT_FB;
$aTitles["player-box"] = "Music Player";
$aTitles["schedule-box"] = "Tour Schedule";
$aTitles["social-box"] = "Social";
$aTitles["tour"] = TEXT_TOUR_TITLE;

require_once(FILE_SMARTY);
require_once("browser.php");

$oBr = new Browser;
$oSmarty = new Smarty;

$bI = ( isset( $_GET['d'] ) ) ? false : ($oBr->Platform == "iPhone"); // iPhone or not? Later on, add functionality for all mobile devices.
$bWK = ( strpos($oBr->UserAgent, "WebKit") );	// Is the current browser based on WebKit?

$oSmarty->setCaching(false);

$oSmarty->assign("bI", $bI);