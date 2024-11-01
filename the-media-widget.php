<?php
/*
Plugin Name: The Media Widget
Plugin URI: http://wp-plugins.in/the-media-widget
Description: Display media in text widget easily, youtube video, vimeo video, instagram image, easy to use just paste link! fully responsive and custom height.
Version: 1.0.0
Author: Alobaidi
Author URI: http://wp-plugins.in
License: GPLv2 or later
*/

/*  Copyright 2015 Alobaidi (email: wp-plugins@outlook.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function alobaidi_the_media_widget_plugin_row_meta( $links, $file ) {

	if ( strpos( $file, 'the-media-widget.php' ) !== false ) {
		
		$new_links = array(
						'<a href="http://wp-plugins.in/the-media-widget" target="_blank">Explanation of Use</a>',
						'<a href="https://profiles.wordpress.org/alobaidi#content-plugins" target="_blank">More Plugins</a>',
						'<a href="http://j.mp/ET_WPTime_ref_pl" target="_blank">Elegant Themes</a>'
					);
		
		$links = array_merge( $links, $new_links );
		
	}
	
	return $links;
	
}
add_filter( 'plugin_row_meta', 'alobaidi_the_media_widget_plugin_row_meta', 10, 2 );


function alobaidi_the_media_widget_add_setting(){
	add_settings_field( "obi_the_media_widget_height", 'Video Height', "obi_the_media_widget_height", "media", "default", array('label_for' => 'obi_the_media_widget_height') );
	register_setting( 'media', 'obi_the_media_widget_height' );
}
add_action( 'admin_init', 'alobaidi_the_media_widget_add_setting' );


function obi_the_media_widget_height(){
	?>
    	<input class="small-text" id="obi_the_media_widget_height" name="obi_the_media_widget_height" type="text" value="<?php echo esc_attr( get_option('obi_the_media_widget_height') ); ?>">
    	<p class="description">Enter video height size for youtube and vimeo iframe, default is 300px.</p>
    <?php
}


function alobaidi_the_media_widget_filter($text){

	if( get_option('obi_the_media_widget_height') ){
		$get_height = get_option('obi_the_media_widget_height');
		$height = str_replace(array('px', ' ', '.', ','), '', $get_height);
	}else{
		$height = '300';
	}

	/* YouTube */
	$youtube_regex = '/(https?:\/\/youtube.com\/watch)|(www.youtube.com\/watch)|(youtube.com\/watch)|(https?:\/\/www.youtube.com\/watch)|(https?:\/\/youtu.be)|(www.youtu.be)|(youtu.be)|(https?:\/\/www.youtu.be)/';
	$youtube_exclude = '/("https?:\/\/youtube.com\/watch)|("www.youtube.com\/watch)|("youtube.com\/watch)|("https?:\/\/www.youtube.com\/watch)|("https?:\/\/youtu.be)|("www.youtu.be)|("youtu.be)|("https?:\/\/www.youtu.be)/';

	if( preg_match($youtube_regex, $text) and !preg_match($youtube_exclude, $text) ){
		$protocol 	= array('http://', 'https://', 'www.', 'youtube.com', 'youtu.be', 'embed', 'watch?v=', '/');
		$str_replace = str_replace($protocol, '', $text);
		$video_link = preg_replace( array('/[^&?]*?=[^&?]*/', '/[(&)]/'), '', $str_replace );
		$text = '<iframe style="width:100%!important;max-width:100%!important;display:block!important;height:'.$height.'px;" src="http://youtube.com/embed/'.$video_link.'" allowfullscreen></iframe>';
	}


	/* Instagram */
	$instagram_regex = '/(https?:\/\/instagram.com\/p)|(www.instagram.com\/p)|(instagram.com\/p)|(https?:\/\/www.instagram.com\/p)|(https?:\/\/instagr.am\/p)|(www.instagr.am\/p)|(instagr.am\/p)|(https?:\/\/www.instagr.am\/p)/';
	$instagram_exclude = '/("https?:\/\/instagram.com\/p)|("www.instagram.com\/p)|("instagram.com\/p)|("https?:\/\/www.instagram.com\/p)|("https?:\/\/instagr.am\/p)|("www.instagr.am\/p)|("instagr.am\/p)|("https?:\/\/www.instagr.am\/p)/';

	if( preg_match($instagram_regex, $text) and !preg_match($instagram_exclude, $text) ){
		$regex = array("/[^&?]*?=[^&?]*/", "/[(?)]/", "/(\/p\/)/");
		$preg_replace = preg_replace($regex, "", $text);
		$protocol = array('http://', 'https://', 'www.', 'instagram.com', 'instagr.am', '/');
		$str_replace = str_replace($protocol, "", $preg_replace);
		$instagram_image_link = 'https://instagram.com/p/'.$str_replace.'/media?size=l';
		$text = '<img src="'.$instagram_image_link.'" style="width:100% !important; max-width:100% !important; display:block!important; height:auto !important;">';
	}


	/* Vimeo */
	$vimeo_regex = '/(https?:\/\/vimeo.com\/)|(www.vimeo.com\/)|(vimeo.com\/)|(https?:\/\/www.vimeo.com\/)/';
	$vimeo_exclude = '/("https?:\/\/vimeo.com\/)|("www.vimeo.com\/)|("vimeo.com\/)|("https?:\/\/www.vimeo.com\/)/';

	if( preg_match($vimeo_regex, $text) and !preg_match($vimeo_exclude, $text) ){
		$protocol 	= array('http://', 'https://', 'www.', 'vimeo.com', '/');
		$str_replace = str_replace($protocol, '', $text);
		$video_link = preg_replace('/[a-zA-Z]/', '', $str_replace);
		$text = '<iframe style="width:100%!important;max-width:100%!important;display:block!important;height:'.$height.'px;" src="http://player.vimeo.com/video/'.$video_link.'" allowfullscreen></iframe>';
	}
	
	return $text; // Display Media!

}
add_filter('widget_text', 'alobaidi_the_media_widget_filter');

?>