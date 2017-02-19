<?php
/**
 * @package justicecpts
 * @version 1.0
 */

/*
Plugin Name: WP CPT Manager
Description: CPT's for davidparsons.me. Planning on extending this to be used on any site.
Author: David Parsons
Version: 1.0
Author URI: https://davidparsons.me/
*/

include_once('cpt.php');

// Portfolio Items
$project = new CPT('project');
$project->menu_icon('dashicons-book-alt');
// $speaking = new CPT('speaking');
// $speaking->menu_icon('dashicons-megaphone');

// Ratings
$location = new CPT('location');
$location->menu_icon('dashicons-location-alt');
// restaurant should be a locations category
// $restaurant = new CPT('restaurant');
// $restaurant->menu_icon('dashicons-location');
// $restaurant->register_taxonomy('cuisine');

// movies
// tv
// video games
// anime

// Journal
$desk = new CPT('desk');
$desk->menu_icon('dashicons-laptop');
$recipes = new CPT('recipe');
$recipes->menu_icon('dashicons-carrot');
$recipes->register_taxonomy('cuisine');

// Media
$photo = new CPT('photo');
$photo->menu_icon('dashicons-camera');
$video = new CPT('video');
$video->menu_icon('dashicons-video-alt2');
$music = new CPT('music');
$music->menu_icon('dashicons-format-audio');

// Bookmarks
$bookmark = new CPT('bookmark');
$bookmark->menu_icon('dashicons-sticky');

// inspiration
/*
music ... spotify syncer (youtube?)
websites ... bookmark syncer
code (other peoples code) ... github stars?

books
movies
*/

// bookmarks...
/*
music
inspiration
	- websites
	- projects
resources
code (open source)
tools
	- travel
speaking (speaches)
wp plugins
wp themes

books
movies
*/
