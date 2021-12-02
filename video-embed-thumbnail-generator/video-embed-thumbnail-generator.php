<?php
/*
Plugin Name: Videopack (formerly Video Embed & Thumbnail Generator)
Plugin URI: https://www.wordpressvideopack.com/
Description: Generates thumbnails, HTML5-compliant videos, and embed codes for locally hosted videos. Requires FFMPEG or LIBAV for encoding.
Version: 4.7.3
Author: Kyle Gilman
Author URI: https://www.kylegilman.net/
Text Domain: video-embed-thumbnail-generator
Domain Path: /languages

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

1) Includes code adapted from Joshua Eldridge's Flash Media Player Plugin
   Website: http://wordpress.org/extend/plugins/flash-video-player/
2) Includes code adapted from Gary Cao's Make Shortcodes User-Friendly tutorial
   Website: http://www.wphardcore.com/2010/how-to-make-shortcodes-user-friendly/
3) Includes code adapted from Justin Gable's "Modifying Wordpress' Default Method for Inserting Media"
   Website: http://justingable.com/2008/10/03/modifying-wordpress-default-method-for-inserting-media/
4) Includes Video-JS Player
	Website: http://www.videojs.com/
	License: http://www.gnu.org/licenses/lgpl.html
5) Includes code adapted from Kathy Darling's custom solution for saving thumbnails
	Website: http://www.kathyisawesome.com/
6) Includes code adapted from Jean-Marc Amiaud's "Replace WordPress default media icon with preview image"
	Website: http://www.amiaud.org/tag/video/
7) Includes Eric Martin's SimpleModal
	Website: http://www.ericmmartin.com/projects/simplemodal/
8) Includes Dominic's Video.js Resolution Selector
	Website: https://github.com/dominic-p/videojs-resolution-selector

=Translators=
Spanish: Andrew Kurtis, Webhostinghub http://www.webhostinghub.com/
French: F.R. "Friss" Ferry, friss.designs@gmail.com
Bulgarian: Emil Georgiev, svinqvmraka@gmail.com

*/

if ( ! defined( 'ABSPATH' ) ) {
	die( "Can't load this file directly" );
}

function kgvid_default_options_fn() {

	$upload_capable = kgvid_check_if_capable('upload_files');
	$edit_others_capable = kgvid_check_if_capable('edit_others_posts');

	$options = array(
		"version" => '4.7.3',
		"videojs_version" => '7.14.3',
		"embed_method" => "Video.js v7",
		"template" => false,
		"template_gentle" => "on",
		"replace_format" => "fullres",
		"custom_format" => array(
			'format' => 'h264',
			'width' => '',
			'height' => ''
		),
		"hide_video_formats" => "on",
		"app_path" => "/usr/local/bin",
		"video_app"  => "ffmpeg",
		"ffmpeg_exists" =>"notchecked",
		"video_bitrate_flag" => false,
		"ffmpeg_vpre" => false,
		"ffmpeg_old_rotation" => false,
		"ffmpeg_auto_rotate" => "on",
		"nostdin" => false,
		"moov" => "none",
		"generate_thumbs" => 4,
		"featured" => "on",
		"thumb_parent" => "video",
		"delete_children" => "encoded videos only",
		"titlecode" => "<strong>",
		"poster" => "",
		"watermark" => "",
		"watermark_link_to" => "home",
		"watermark_url" => "",
		"overlay_title" => "on",
		"overlay_embedcode" => false,
		"twitter_button" => false,
		"twitter_username" => kgvid_get_jetpack_twitter_username(),
		"facebook_button" => false,
		"downloadlink" => false,
		"click_download" => "on",
		"view_count" => false,
		"count_views" => "start_complete",
		"embeddable" => "on",
		"inline" => false,
		"align" => "left",
		"width" => "640",
		"height" => "360",
		"minimum_width" => false,
		"fullwidth" => "on",
		"fixed_aspect" => "vertical",
		"gallery_width" => "960",
		"gallery_thumb" => "250",
		"gallery_thumb_aspect" => "on",
		"gallery_end" => "",
		"gallery_pagination" => false,
		"gallery_per_page" => false,
		"gallery_title" => "on",
		"nativecontrolsfortouch" => false,
		"controls" => "on",
		"autoplay" => false,
		"pauseothervideos" => "on",
		"loop" => false,
		"playsinline" => "on",
		"volume" => 1,
		"muted" => false,
		"gifmode" => false,
		"preload" => "metadata",
		"playback_rate" => false,
		"endofvideooverlay" => false,
		"endofvideooverlaysame" => "",
		"skin" => "kg-video-js-skin",
		"js_skin" => "kg-video-js-skin",
		"custom_attributes" => "",
		"bitrate_multiplier" => 0.1,
		"h264_CRF" => "23",
		"webm_CRF" => "10",
		"ogv_CRF" => "6",
		"audio_bitrate" => 160,
		"audio_channels" => 'on',
		"threads" => 1,
		"nice" => "on",
		"browser_thumbnails" => "on",
		"rate_control" => "crf",
		"h264_profile" => "baseline",
		"h264_level" => "3.0",
		"auto_encode" => false,
		"auto_encode_gif" => false,
		"auto_thumb" => false,
		"auto_thumb_number" => 1,
		"auto_thumb_position" => 50,
		"right_click" => "on",
		"resize" => "on",
		"auto_res" => "automatic",
		"pixel_ratio" => "on",
		"capabilities" => array(
			"make_video_thumbnails" => $upload_capable,
			"encode_videos" => $upload_capable,
			"edit_others_video_encodes" => $edit_others_capable
		),
		"open_graph" => false,
		"schema" => "on",
		"twitter_card" => false,
		"oembed_provider" => false,
		"oembed_security" => false,
		"htaccess_login" => "",
		"htaccess_password" => "",
		"sample_format" => "mobile",
		"sample_rotate" => false,
		"ffmpeg_thumb_watermark" => array(
			"url" => "",
			"scale" => "50",
			"align" => "center",
			"valign"=> "center",
			"x" => "0",
			"y" => "0"
		),
		"ffmpeg_watermark" => array(
			"url" => "",
			"scale" => "9",
			"align" => "right",
			"valign"=> "bottom",
			"x" => "6",
			"y" => "5"
		),
		"simultaneous_encodes" => 1,
		"error_email" => "nobody",
		"alwaysloadscripts" => false,
		"replace_video_shortcode" => false,
		"rewrite_attachment_url" => 'on',
		"auto_publish_post" => false,
		"transient_cache" => false,
		"queue_control" => 'play'
	);

	$video_formats = kgvid_video_formats();
	foreach ($video_formats as $format => $format_stats ) {
		if ( array_key_exists('default_encode', $format_stats) && $format_stats['default_encode'] == 'on' ) {
			$options['encode'][$format] = $format_stats['default_encode'];
		}
	}

	return apply_filters('kgvid_default_options', $options);

}

function kgvid_default_network_options() {

	$default_options = kgvid_default_options_fn();

	$network_options = array(
		'app_path' => $default_options['app_path'],
		'video_app' => $default_options['video_app'],
		'moov' => $default_options['moov'],
		'video_bitrate_flag' => $default_options['video_bitrate_flag'],
		'ffmpeg_vpre' => $default_options['ffmpeg_vpre'],
		'nice' => $default_options['nice'],
		'threads' => $default_options['threads'],
		'ffmpeg_exists' => $default_options['ffmpeg_exists'],
		'default_capabilities' => $default_options['capabilities'],
		'superadmin_only_ffmpeg_settings' => false,
		'simultaneous_encodes' => $default_options['simultaneous_encodes'],
		'network_error_email' => $default_options['error_email'],
		'queue_control' => $default_options['queue_control']
	);

	return $network_options;

}

function kgvid_get_options() {

	$options = get_option('kgvid_video_embed_options');

	if ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( plugin_basename(__FILE__) ) ) {
		$network_options = get_site_option('kgvid_video_embed_network_options');
		if ( !is_array($options) ) {
			$options = kgvid_default_options_fn();
		}
		if ( is_array($network_options) ) { 
			if ( !fs_is_network_admin() && $network_options['queue_control'] == 'play' && $options['queue_control'] == 'pause' ) {
				$network_options['queue_control'] = 'pause'; //allows local queue to pause while network queue continues
			}
			$options = array_merge($options, $network_options); 
		}
	}

	return $options;

}

function kgvid_fs_custom_connect_message_on_update(
	$message,
	$user_first_name,
	$product_title,
	$user_login,
	$site_link,
	$freemius_link
) {
	return sprintf(
		__( 'Hi %1$s', 'video-embed-thumbnail-generator' ) . ',<br>' .
		__( 'I changed the name of the Video Embed & Thumbnail Generator plugin to Videopack and released the first of what I hope will be several useful premium add-ons. I\'m using %5$s to license and update the add-ons. Please help me improve Videopack. If you opt-in, some data about your usage of Videopack will be sent to %5$s. If you skip this, that\'s okay! Videopack will still work just fine.', 'video-embed-thumbnail-generator' ),
		$user_first_name,
		'<b>' . $product_title . '</b>',
		'<b>' . $user_login . '</b>',
		$site_link,
		$freemius_link
	);
}

function kgvid_videopack_fs_loaded() { //add Freemius customizations after Freemius is loaded

	if ( function_exists( 'videopack_fs' ) ) {

		videopack_fs()->add_filter('connect_message_on_update', 'kgvid_fs_custom_connect_message_on_update', 10, 6);

		videopack_fs()->override_i18n( array(
			'yee-haw' 		=> __( "Great", 'video-embed-thumbnail-generator' ),
			'woot'          => __( 'Great', 'video-embed-thumbnail-generator' ),
		) );

		videopack_fs()->add_action('after_uninstall', 'kgvid_uninstall_plugin'); //add uninstall logic

	}

}
add_action('videopack_fs_loaded', 'kgvid_videopack_fs_loaded');

if ( file_exists(dirname(__FILE__) . '/freemius/start.php') && ! function_exists( 'videopack_fs' ) ) {
    // Create a helper function for easy SDK access.
    function videopack_fs() {
        global $videopack_fs;

        if ( ! isset( $videopack_fs ) ) {
            // Activate multisite network integration.
            if ( ! defined( 'WP_FS__PRODUCT_7761_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_7761_MULTISITE', true );
            }

            // Include Freemius SDK.
			require_once dirname(__FILE__) . '/freemius/start.php';

			$init_options = array(
                'id'                  => '7761',
                'slug'                => 'video-embed-thumbnail-generator',
				'navigation'          => 'tabs',
                'type'                => 'plugin',
                'public_key'          => 'pk_c5b15a7a3cd2ec3cc20e012a2a7bf',
                'is_premium'          => false,
                'has_addons'          => true,
                'has_paid_plans'      => false,
                'menu'                => array(
					'slug'           => 'video_embed_thumbnail_generator_settings',
					'contact'        => false,
					'support'        => false,
					'network'        => true,
					'parent'         => array(
						'slug' => 'options-general.php',
					),
				)
			);

			if ( fs_is_network_admin() ) {
				$init_options['navigation'] = 'menu';
				$init_options['menu']['parent'] = array( 
					'slug' => 'settings.php', 
				);
			}

            $videopack_fs = fs_dynamic_init( $init_options );
        }

        return $videopack_fs;
    }

    // Init Freemius.
    videopack_fs();
    // Signal that SDK was initiated.
    do_action( 'videopack_fs_loaded' );
}

function kgvid_get_jetpack_twitter_username() {

	$jetpack_options = get_option('jetpack_options');
	$jetpack_twitter_cards_site_tag = get_option('jetpack-twitter-cards-site-tag');
	if ( is_array($jetpack_options)
		&& array_key_exists('publicize_connections', $jetpack_options)
		&& array_key_exists('twitter', $jetpack_options['publicize_connections'])
		&& array_key_exists('external_name', $jetpack_options['publicize_connections']['twitter'])
		&& !empty($jetpack_options['publicize_connections']['twitter']['external_name'])
	) {
		$twitter_username = $jetpack_options['publicize_connections']['twitter']['external_name'];
	}
	elseif ( !empty($jetpack_twitter_cards_site_tag) ) {
		$twitter_username = $jetpack_twitter_cards_site_tag;
	}
	else { $twitter_username = ''; }

	return $twitter_username;

}

function kgvid_get_attachment_meta($post_id) {

	$options = kgvid_get_options();

	$kgvid_postmeta = get_post_meta($post_id, "_kgvid-meta", true);

	$meta_key_array = array(
		'embed' => 'Single Video',
		'width' => '',
		'height' => '',
		'actualwidth' => '',
		'actualheight' => '',
		'downloadlink' => $options['downloadlink'],
		'track' => '',
		'starts' => '0',
		'play_25' => '0',
		'play_50' => '0',
		'play_75' => '0',
		'completeviews' => '0',
		'pickedformat' => '',
		'encode' => $options['encode'],
		'rotate' => '',
		'autothumb-error' => '',
		'numberofthumbs' => $options['generate_thumbs'],
		'randomize' => '',
		'forcefirst' => '',
		'featured' => $options['featured'],
		'thumbtime' => '',
		'lockaspect' => 'on',
		'showtitle' => '',
		'gallery_thumb_width' => $options['gallery_thumb'],
		'gallery_exclude' => '',
		'gallery_include' => '',
		'gallery_orderby' => '',
		'gallery_order' => '',
		'gallery_id' => '',
		'duration' => '',
		'aspect' => '',
		'original_replaced' => ''
	);

	if ( $kgvid_postmeta == '' ) {

		$kgvid_postmeta = array();

		$embed = get_post_meta($post_id, "_kgflashmediaplayer-embed", true); //this was always saved if you modified the attachment

		if ( !empty($embed) ) { //old meta values exist

			foreach ( $meta_key_array as $key => $value ) { //read old meta keys and delete them
				$kgvid_postmeta[$key] = get_post_meta($post_id, "_kgflashmediaplayer-".$key, true);
				if ( $kgvid_postmeta[$key] == 'checked' ) { $kgvid_postmeta[$key] = 'on'; }
				delete_post_meta($post_id, "_kgflashmediaplayer-".$key);
			}

			foreach ( $kgvid_postmeta as $key => $value ) {
				if ( $value === null ) {
					unset( $kgvid_postmeta[ $meta ] ); //remove empty elements
				}
			}

			kgvid_save_attachment_meta($post_id, $kgvid_postmeta);

		}

		$old_meta_encode_keys = array(
			'encodefullres',
			'encode1080',
			'encode720',
			'encode480',
			'encodemobile',
			'encodewebm',
			'encodeogg',
			'encodecustom',
		);

		$old_meta_exists = false;

		foreach ($old_meta_encode_keys as $old_key) {
			if ( array_key_exists($old_key, $kgvid_postmeta) ) {
				$format = str_replace('encode', '', $old_key);
				$kgvid_postmeta['encode'][$format] = $kgvid_postmeta[$old_key];
				unset($kgvid_postmeta[$old_key]);
				$old_meta_exists = true;
			}
		}

		if ( $old_meta_exists ) { kgvid_save_attachment_meta($post_id, $kgvid_postmeta); }

	}

	$kgvid_postmeta = array_merge($meta_key_array, $kgvid_postmeta); //make sure all keys are set

	return apply_filters('kgvid_attachment_meta', $kgvid_postmeta);

}

function kgvid_save_attachment_meta($post_id, $kgvid_postmeta) {

	if ( is_array($kgvid_postmeta) ) {

		$options = kgvid_get_options();
		$kgvid_old_postmeta = kgvid_get_attachment_meta($post_id);
		$kgvid_postmeta = array_merge($kgvid_old_postmeta, $kgvid_postmeta); //make sure all keys are saved

		foreach ( $kgvid_postmeta as $key => $meta ) { //don't save if it's the same as the default values or empty

			if ( (array_key_exists($key, $options) && $meta == $options[$key])
				|| ( !is_array($kgvid_postmeta[$key]) && strlen($kgvid_postmeta[$key]) == 0 
					&& ( ( array_key_exists($key, $options) && strlen($options[$key]) == 0 )
					|| !array_key_exists($key, $options) )
				)
			) { unset($kgvid_postmeta[$key]); }
			
		}

		update_post_meta($post_id, "_kgvid-meta", $kgvid_postmeta);

	}

}

function kgvid_get_encode_queue() {

	if ( defined( 'DOING_CRON' ) ) { //unlike AJAX, cron doesn't load plugin.php
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}

	if ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( plugin_basename(__FILE__) ) ) {
		$video_encode_queue = get_site_option('kgvid_video_embed_queue');
	}
	else { $video_encode_queue = get_option('kgvid_video_embed_queue'); }

	return $video_encode_queue;

}

function kgvid_save_encode_queue($video_encode_queue) {

	if ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( plugin_basename(__FILE__) ) ) {
		update_site_option('kgvid_video_embed_queue', $video_encode_queue);
	}
	else { update_option('kgvid_video_embed_queue', $video_encode_queue); }

}

function kgvid_video_formats( $return_replace = false, $return_customs = true, $return_dontembeds = true ) {

	$options = kgvid_get_options();

	$video_formats = array(
		"fullres" => array(
			"name" => __("same resolution H.264", 'video-embed-thumbnail-generator'),
			"label" => _x('Full', 'Full resolution', 'video-embed-thumbnail-generator'),
			"width" => INF,
			"height" => INF,
			"type" => "h264",
			"extension" => "mp4",
			"mime" => "video/mp4",
			"suffix" => "-fullres.mp4",
			"vcodec" => "libx264",
			"default_encode" => false
		),
		"1080" => array(
			"name" => "1080p H.264",
			"label" => '1080p',
			"width" => 1920,
			"height" => 1080,
			"type" => "h264",
			"extension" => "mp4",
			"mime" => "video/mp4",
			"suffix" => "-1080.mp4",
			"old_suffix" => "-1080.m4v",
			"vcodec" => "libx264",
			"default_encode" => "on"
		),
		"720" => array(
			"name" => "720p H.264",
			"label" => '720p',
			"width" => 1280,
			"height" => 720,
			"type" => "h264",
			"extension" => "mp4",
			"mime" => "video/mp4",
			"suffix" => "-720.mp4",
			"old_suffix" => "-720.m4v",
			"vcodec" => "libx264",
			"default_encode" => "on"
		),
		"480" => array(
			"name" => "480p H.264",
			"label" => '480p',
			"width" => 854,
			"height" => 480,
			"type" => "h264",
			"extension" => "mp4",
			"mime" => "video/mp4",
			"suffix" => "-480.mp4",
			"old_suffix" => "-480.m4v",
			"vcodec" => "libx264",
			"default_encode" => "on"
		),
		"mobile" => array(
			"name" => "360p H.264",
			"label" => '360p',
			"width" => 640,
			"height" => 360,
			"type" => "h264",
			"extension" => "mp4",
			"suffix" => "-360.mp4",
			"mime" => "video/mp4",
			"old_suffix" => "-ipod.m4v",
			"vcodec" => "libx264",
			"default_encode" => "on"
		),
		"webm" => array(
			"name" => "WEBM VP8",
			"label" => 'WEBM VP8',
			"width" => INF,
			"height" => INF,
			"type" => "webm",
			"extension" => "webm",
			"mime" => "video/webm",
			"suffix" => ".webm",
			"vcodec" => "libvpx",
			"default_encode" => false
		),
		"vp9" => array(
			"name" => "WEBM VP9",
			"label" => 'WEBM VP9',
			"width" => INF,
			"height" => INF,
			"type" => "vp9",
			"extension" => "webm",
			"mime" => "video/webm",
			"suffix" => "-vp9.webm",
			"vcodec" => "libvpx-vp9",
			"default_encode" => false
		),
		"ogg" => array(
			"name" => "OGV",
			"label" => 'OGV',
			"width" => INF,
			"height" => INF,
			"type" => "ogv",
			"extension" => "ogv",
			"mime" => "video/ogg",
			"suffix" => ".ogv",
			"vcodec" => "libtheora",
			"default_encode" => false
		)
	);

	if ( $return_customs ) {

		$video_formats = $video_formats + array(
			"custom_h264" => array(
				"name" => __('Custom MP4', 'video-embed-thumbnail-generator'),
				"label" => __('Custom MP4', 'video-embed-thumbnail-generator'),
				"width" => 0,
				"height" => 0,
				"type" => "h264",
				"extension" => "mp4",
				"mime" => "video/mp4",
				"suffix" => "-custom.mp4",
				"vcodec" => "libx264",
				"default_encode" => false
			),
			"custom_webm" => array(
				"name" => __('Custom WEBM', 'video-embed-thumbnail-generator'),
				"label" => __('Custom WEBM', 'video-embed-thumbnail-generator'),
				"width" => 0,
				"height" => 0,
				"type" => "webm",
				"extension" => "webm",
				"mime" => "video/webm",
				"suffix" => "-custom.webm",
				"vcodec" => "libvpx",
				"default_encode" => false
			),
			"custom_vp9" => array(
				"name" => __('Custom VP9 WEBM', 'video-embed-thumbnail-generator'),
				"label" => __('Custom VP9 WEBM', 'video-embed-thumbnail-generator'),
				"width" => 0,
				"height" => 0,
				"type" => "vp9",
				"extension" => "webm",
				"mime" => "video/webm",
				"suffix" => "-customvp9.webm",
				"vcodec" => "libvpx-vp9",
				"default_encode" => false
			),
			"custom_ogg" => array(
				"name" => __('Custom OGV', 'video-embed-thumbnail-generator'),
				"label" => __('Custom OGV', 'video-embed-thumbnail-generator'),
				"width" => 0,
				"height" => 0,
				"type" => "ogv",
				"extension" => "ogv",
				"mime" => "video/ogv",
				"suffix" => "-custom.ogv",
				"vcodec" => "libtheora",
				"default_encode" => false
			)
		);
	}

	if ( is_array($options['custom_format']) && ( !empty($options['custom_format']['width']) || !empty($options['custom_format']['height']) ) ) {
		$video_formats['custom'] = $options['custom_format'];
		unset($video_formats['custom_'.$options['custom_format']['format']]);
	}

	if ( isset($options['replace_format']) ) {

		$video_formats['fullres'] = array(
			'name' => sprintf( __("Replace original with %s", 'video-embed-thumbnail-generator'), $video_formats[$options['replace_format']]['name'] ),
			'width' => $video_formats[$options['replace_format']]['width'],
			'height' => $video_formats[$options['replace_format']]['height'],
			'type' => $video_formats[$options['replace_format']]['type'],
			'extension' => $video_formats[$options['replace_format']]['extension'],
			'mime' => $video_formats[$options['replace_format']]['mime'],
			'suffix' => '-fullres.'.$video_formats[$options['replace_format']]['extension'],
			'vcodec' => $video_formats[$options['replace_format']]['vcodec']
		);

		if ( !$return_replace && $options['replace_format'] != 'fullres' ) { unset($video_formats[$options['replace_format']]); }

	}

	return apply_filters('kgvid_video_formats', $video_formats, $return_replace, $return_customs, $return_dontembeds);

}

function kgvid_register_default_options_fn() { //add default values for options

	$options = kgvid_get_options();

    if( !is_array($options) ) {

		$options = kgvid_default_options_fn();

		$ffmpeg_check = kgvid_check_ffmpeg_exists($options, false);
		if ( true == $ffmpeg_check['ffmpeg_exists'] ) {
			$options['ffmpeg_exists'] = 'on';
			$options['app_path'] = $ffmpeg_check['app_path'];
		}
		else { $options['ffmpeg_exists'] = 'notinstalled'; }

		update_option('kgvid_video_embed_options', $options);

	}

	return $options;

}

function kgvid_video_embed_activation_hook( $network_wide ) {

	if ( is_multisite() && $network_wide ) { // if activated on the entire network

		$network_options = get_site_option( 'kgvid_video_embed_network_options' );

		if( !is_array($network_options) ) {

			$network_options = kgvid_default_network_options();

			$ffmpeg_check = kgvid_check_ffmpeg_exists($network_options, false);
			if ( true == $ffmpeg_check['ffmpeg_exists'] ) {
				$network_options['ffmpeg_exists'] = 'on';
				$network_options['app_path'] = $ffmpeg_check['app_path'];
			}
			else { $network_options['ffmpeg_exists'] = false; }

			update_site_option('kgvid_video_embed_network_options', $network_options);

			$current_blog_id = get_current_blog_id();
			$sites = get_sites();

			if ( is_array($sites) ) {

				foreach ( $sites as $site ) {
					$blog_id = $site->__get('id');
					switch_to_blog($blog_id);

					$options = get_option('kgvid_video_embed_options');

					if ( !is_array($options) ) {
						kgvid_register_default_options_fn();
						kgvid_set_capabilities($network_options['default_capabilities']);
					}

				}//end loop through sites

				switch_to_blog($current_blog_id);

			}//if there are existing sites to set

		}// if network options haven't been set already

	}
	else { // Running on a single blog

		$options = kgvid_register_default_options_fn();
		kgvid_set_capabilities($options['capabilities']);

	}

}
register_activation_hook(__FILE__, 'kgvid_video_embed_activation_hook');

function kgvid_add_new_blog($blog_id) {

	switch_to_blog($blog_id);

	$network_options = get_site_option( 'kgvid_video_embed_network_options' );
	kgvid_set_capabilities($network_options['default_capabilities']);

	restore_current_blog();

}
add_action( 'wpmu_new_blog', 'kgvid_add_new_blog' );

function kgvid_plugin_action_links($links) {

	$links[] = '<a href="'.get_admin_url(null, "options-general.php?page=video_embed_thumbnail_generator_settings").'">'.__('Settings', 'video-embed-thumbnail-generator').'</a>';
	return $links;

}
add_filter("plugin_action_links_".plugin_basename(__FILE__), 'kgvid_plugin_action_links' );

function kgvid_plugin_network_action_links($links) {

	$links[] = '<a href="'.network_admin_url().'settings.php?page=video_embed_thumbnail_generator_settings">'.__('Network Settings', 'video-embed-thumbnail-generator').'</a>';
	return $links;

}
add_filter("network_admin_plugin_action_links_".plugin_basename(__FILE__), 'kgvid_plugin_network_action_links' );

function kgvid_load_text_domain() {

	load_plugin_textdomain( 'video-embed-thumbnail-generator', false, basename(dirname(__FILE__)).'/languages/' );

}
add_action('plugins_loaded', 'kgvid_load_text_domain');


function kgvid_plugin_meta_links( $links, $file ) {

	$plugin = plugin_basename(__FILE__);

	if ( $file == $plugin ) {
		return array_merge(
			$links,
			array( '<a href="https://www.wordpressvideopack.com/donate/">Donate</a>' )
		);
	}
	return $links;

}
add_filter( 'plugin_row_meta', 'kgvid_plugin_meta_links', 10, 2 );

// add plugin upgrade notification
function kgvid_showUpgradeNotification($currentPluginMetadata, $newPluginMetadata){
   // check "upgrade_notice"
   if (isset($newPluginMetadata->upgrade_notice) && strlen(trim($newPluginMetadata->upgrade_notice)) > 0){
        echo 'Upgrade Notice: ';
        echo esc_html($newPluginMetadata->upgrade_notice);
   }
}
add_action('in_plugin_update_message-video-embed-thumbnail-generator/video-embed-thumbnail-generator.php', 'kgvid_showUpgradeNotification', 10, 2);

function kgvid_check_if_capable($capability) {
	global $wp_roles;
	$capable = array();

	if ( is_object($wp_roles) && property_exists($wp_roles, 'roles') ) {

		foreach ( $wp_roles->roles as $role => $role_info ) {
			if ( is_array($role_info['capabilities']) && array_key_exists($capability, $role_info['capabilities']) && $role_info['capabilities'][$capability] == 1 ) {
				$capable[$role] = "on";
			}
			else { $capable[$role] = false; }
		}

	}
	return $capable;
}

function kgvid_set_capabilities($capabilities) {

	global $wp_roles;

	if ( is_object($wp_roles) && property_exists($wp_roles, 'roles') ) {

		$default_options = kgvid_default_options_fn();

		foreach ( $default_options['capabilities'] as $default_capability => $default_enabled ) {
			if ( is_array($capabilities) && !array_key_exists($default_capability, $capabilities) ) {
				$capabilities[$default_capability] = array();
			}
		}

		foreach ( $capabilities as $capability => $enabled_roles ) {
			foreach ( $wp_roles->roles as $role => $role_info ) { //check all roles
				if ( is_array($role_info['capabilities']) && !array_key_exists($capability, $role_info['capabilities']) && array_key_exists($role, $enabled_roles) && $enabled_roles[$role] == "on" ) {
					$wp_roles->add_cap( $role, $capability );
				}
				if ( is_array($role_info['capabilities']) && array_key_exists($capability, $role_info['capabilities']) && !array_key_exists($role, $enabled_roles) ) {
					$wp_roles->remove_cap( $role, $capability );
				}
			}
		}

	}//end if $wp_roles defined

}

function kgvid_set_locale($filepath) {

	$old_locale = setlocale(LC_CTYPE, 0);
	$escaped_filepath = escapeshellcmd($filepath);

	if ($filepath != $escaped_filepath) {

		$wp_locale = get_locale();
		if ( strlen($wp_locale) == 4 ) {
			$locale_name = $wp_locale.'.UTF-8';
		}
		else {
			$locale_name = "en_US.UTF-8";
		}
		
		$new_locale = setlocale(LC_CTYPE, $locale_name);
		if ( !$new_locale ) {
			$new_locale = setlocale(LC_CTYPE, "en_US.UTF-8");
		}

	}

	return $old_locale;

}

function kgvid_get_videojs_locale() {

	$options = kgvid_get_options();
	$locale = get_locale();

	$locale_conversions = array( //all Video.js language codes are two-character except these
		'pt-BR' => 'pt_BR',
		'pt-PT' => 'pt_PT',
		'zh-CN' => 'zh_CN',
		'zh-TW' => 'zh_TW'
	);
	if ( $options['embed_method'] == "Video.js" ) { //v5 doesn't have pt-PT
		$locale_conversions['pt-BR'] = 'pt_PT';
	}

	$matching_locale = array_search($locale, $locale_conversions);
	if ( $matching_locale !== false ) {
		$locale = $matching_locale;
	}
	else {
		$locale = substr($locale, 0, 2);
	}

	return $locale;

}

function kgvid_aac_encoders() {

	$aac_array = array("libfdk_aac", "libfaac", "aac", "libvo_aacenc");
	return apply_filters('kgvid_aac_encoders', $aac_array);

}

function kgvid_set_transient_name($url) {

	$url = str_replace(' ', '', $url); //in case a url with spaces got through
	// Get the path or the original size image by slicing the widthxheight off the end and adding the extension back
 	$search_url = preg_replace( '/-\d+x\d+(\.(?:png|jpg|gif))$/i', '.' . pathinfo($url, PATHINFO_EXTENSION), $url );
	if (strlen($search_url) > 166 ) { $search_url = substr($search_url, -162); } //transients can't be more than 172 characters long. Including 'kgvid_' the URL has to be 162 characters or fewer

	return $search_url;

}

function kgvid_url_to_id($url) {

	global $wpdb;
	$options = kgvid_get_options();
	$uploads = wp_upload_dir();
	$post_id = false;
	$video_formats = kgvid_video_formats();
	$search_url = kgvid_set_transient_name($url);

	if ( $options['transient_cache'] == "on" ) {
		$post_id = get_transient( 'kgvid_'.$search_url );
	}

	if ( $post_id === false ) {
		
		$search_query =  "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value LIKE RIGHT('%s', CHAR_LENGTH(meta_value)) AND LENGTH(meta_value) > 0";
		$post_id = (int)$wpdb->get_var( $wpdb->prepare( $search_query, $search_url ) );

		if ( !$post_id && $options['ffmpeg_exists'] == "on" && $video_formats['fullres']['extension'] != pathinfo($url, PATHINFO_EXTENSION) ) {
			$search_url = str_replace( pathinfo($url, PATHINFO_EXTENSION), $video_formats['fullres']['extension'], $url );
			$post_id = (int)$wpdb->get_var( $wpdb->prepare( $search_query, $search_url ) );
			if ( $post_id ) { $kgvid_postmeta = kgvid_get_attachment_meta($post_id); }
			if ( !isset($kgvid_postmeta) || !is_array($kgvid_postmeta) || ( is_array($kgvid_postmeta) && !array_key_exists('original_replaced', $kgvid_postmeta) ) ) {
				$post_id = NULL;
			}
		}

		if ( $options['transient_cache'] == "on" ) {
			if ( !$post_id ) {
				$post_id = 'not found'; //don't save a transient value that could evaluate as false
			}

			set_transient( 'kgvid_'.$search_url, $post_id, MONTH_IN_SECONDS );
		}

	}
	
	if ( $post_id == 'not found' ) { 
		$post_id = NULL; 
	}

	return $post_id;

}

function kgvid_is_animated_gif($filename) {
    if(!($fh = @fopen($filename, 'rb')))
        return false;
    $count = 0;
    //an animated gif contains multiple "frames", with each frame having a
    //header made up of:
    // * a static 4-byte sequence (\x00\x21\xF9\x04)
    // * 4 variable bytes
    // * a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)

    // We read through the file til we reach the end of the file, or we've found
    // at least 2 frame headers
    while(!feof($fh) && $count < 2) {
        $chunk = fread($fh, 1024 * 100); //read 100kb at a time
        $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
   }

    fclose($fh);
    return $count > 1;
}

function kgvid_is_video($post) {

	if ( $post && is_object($post) && property_exists($post, 'post_mime_type') ) {

		if ( $post->post_mime_type == 'image/gif' ) {
			$moviefile = get_attached_file($post->ID);
			$is_animated = kgvid_is_animated_gif($moviefile);
		}
		else { $is_animated = false; }

		if ( substr($post->post_mime_type, 0, 5) == 'video'	&&
			( empty($post->post_parent)
			|| (strpos(get_post_mime_type( $post->post_parent ), 'video') === false && get_post_meta($post->ID, '_kgflashmediaplayer-externalurl', true) == '' )
			)
			|| $is_animated
		) { //if the attachment is a video with no parent or if it has a parent the parent is not a video and the video doesn't have the externalurl post meta

			return true;

		}

	}

	return false;

}

function kgvid_url_exists($url) {

	$ssl_context_options = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
		)
	);
	$ssl_context = stream_context_create($ssl_context_options);

	$hdrs = @get_headers($url, 0, $ssl_context);

	return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false;
	
}

function kgvid_url_mime_type($url, $post_id = false) {

	$mime_info = wp_check_filetype(strtok($url, '?'));

	if ( array_key_exists('type', $mime_info) && empty($mime_info['type']) ) { //wp unable to determine mime type
		
		$mime_info = '';

		if ( $post_id != false ) {

			$sanitized_url = kgvid_sanitize_url($url);		
			$mime_info = get_post_meta($post_id, '_kgflashmediaplayer-'.$sanitized_url['singleurl_id'].'-mime', true);

		}

		if ( empty($mime_info) ) {

			$mime_type = '';
			$url_extension = '';

			$context_options = array( 
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
				),
				'http' => array(
					'method' => 'HEAD'
				)
			);

			$context = stream_context_create($context_options);

			$fp = fopen($url, 'r', null, $context);
			$metadata = stream_get_meta_data($fp);
			fclose($fp);

			$headers = $metadata['wrapper_data'];

			foreach($headers as $line) {
					if (strtok($line, ':') == 'Content-Type') {
							$parts = explode(":", $line);
							$mime_type = trim($parts[1]);
					}
			}

			if ( !empty($mime_type) ) {
				$wp_mime_types = wp_get_mime_types();
				foreach ($wp_mime_types as $extension => $type ) {
					if ( $type == $mime_type ) {
						$url_extension = $extension;
						if ( strpos($url_extension, '|') !== false ) {
							$extensions = explode('|', $url_extension);
							$url_extension = $extensions[0];
						}
						break;
					}
				}
			}

			$mime_info['type'] = $mime_type;
			$mime_info['ext'] = $url_extension;

			if ( $post_id != false ) {
				$mime_info = update_post_meta($post_id, '_kgflashmediaplayer-'.$sanitized_url['singleurl_id'].'-mime', $mime_info);
			}

		}

	}

	return $mime_info;

}

function kgvid_is_empty_dir($dir)
{
    if ($dh = @opendir($dir))
    {
        while ($file = readdir($dh))
        {
            if ($file != '.' && $file != '..') {
                closedir($dh);
                return false;
            }
        }
        closedir($dh);
        return true;
    }
    else return false; // whatever the reason is : no such dir, not a dir, not readable
}

function kgvid_rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") kgvid_rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
}

function kgvid_build_paired_attributes($value, $key) {
	return $key.'="'.$value.'"';
}

function kvid_readfile_chunked($file, $retbytes=TRUE) { //sends large files in chunks so PHP doesn't timeout

	$chunksize = 1 * (1024 * 1024);
	$buffer = '';
	$cnt = 0;

	$handle = fopen($file, 'r');
	if ($handle === FALSE) { return FALSE; }

	$download_log = apply_filters( 'kg_file_download_logger_start', false );

	while (!feof($handle)) {

		$buffer = fread($handle, $chunksize);
		echo $buffer;
		ob_flush();
		flush();

		if ($retbytes) { $cnt += strlen($buffer); }

	}

	$status = fclose($handle);

	if ( $download_log ) {
		if ( $cnt == filesize($file) ) {
			$complete = true;
		}
		else {
			$complete = false;
		}
		do_action( 'kg_file_download_logger_end', $download_log, $complete );
	}

	if ($retbytes AND $status) { return $cnt; }

	return $status;

}

function kgvid_array_insert_after($key, array &$array, $new_key, $new_value) {
	if (array_key_exists($key, $array)) {
	  $new = array();
	  foreach ($array as $k => $value) {
		$new[$k] = $value;
		if ($k === $key) {
		  $new[$new_key] = $new_value;
		}
	  }
	  return $new;
	}
	return FALSE;
}

function kgvid_get_attachment_medium_url( $id ) {

    $medium_array = image_downsize( $id, 'medium' );
    $medium_path = $medium_array[0];

    return $medium_path;
}

function kgvid_sanitize_url($movieurl) {

	$movieurl = rawurldecode($movieurl);
	$movie_extension = pathinfo(parse_url($movieurl, PHP_URL_PATH), PATHINFO_EXTENSION);

	if ( empty($movie_extension) ) {
		$sanitized_url['noextension'] = $movieurl;
		$sanitized_url['basename'] = substr($movieurl, -20);
	}
	else {
		$movieurl = strtok($movieurl,'?');
		$sanitized_url['noextension'] = preg_replace("/\\.[^.\\s]{3,4}$/", "", $movieurl);
		$sanitized_url['basename'] = sanitize_file_name(basename($movieurl));
		$sanitized_url['basename'] = str_replace('.'.$movie_extension, '', $sanitized_url['basename']);
	}
	
	$sanitized_url['singleurl_id'] = "singleurl_".preg_replace('/[^a-zA-Z0-9]/', '_', $sanitized_url['basename']);
	$sanitized_url['movieurl'] = esc_url_raw(str_replace(" ", "%20", $movieurl));

	return $sanitized_url;

}

function kgvid_ajax_sanitize_url() {

	check_ajax_referer( 'video-embed-thumbnail-generator-nonce', 'security' );
	$movieurl = $_POST['movieurl'];
	$sanitized_url = kgvid_sanitize_url($movieurl);
	echo json_encode($sanitized_url);
	die();

}
add_action('wp_ajax_kgvid_sanitize_url', 'kgvid_ajax_sanitize_url');

function kgvid_check_ffmpeg_exists($options, $save) {
	$exec_enabled = false;
	$ffmpeg_exists = false;
	$output = array();
	$function = "";
	$uploads = wp_upload_dir();

	$exec_available = true;
	if (ini_get('safe_mode')) {
		$exec_available = false;
	} else {
		$d = ini_get('disable_functions');
		$s = ini_get('suhosin.executor.func.blacklist');
		if ("$d$s") {
			$array = preg_split('/,\s*/', "$d,$s");
			if (in_array('exec', $array)) {
				$exec_available = false;
			}
		}
	}

	if($exec_available) {
		if (function_exists('escapeshellcmd')) {
			$exec_enabled = true;
			$test_path = rtrim($options['app_path'], '/');
			$old_locale = kgvid_set_locale(plugin_dir_path(__FILE__).'images/sample-video-h264.mp4'); //fixes UTF-8 encoding problems
			$cmd = escapeshellcmd($test_path.'/'.$options['video_app'].' -i "'.plugin_dir_path(__FILE__).'images/sample-video-h264.mp4" -vframes 1 -f mjpeg "'.$uploads['path'].'/ffmpeg_exists_test.jpg"').' 2>&1';
			exec ( $cmd, $output, $returnvalue );
			$restore_locale = setlocale(LC_CTYPE, $old_locale);
		}
		else { $function = "ESCAPESHELLCMD"; }
	}
	else { $function = "EXEC"; }

	if ( $exec_enabled == true ) {

		if ( !file_exists($uploads['path'].'/ffmpeg_exists_test.jpg') ) { //if FFMPEG has not executed successfully
			$test_path = substr($test_path, 0, -strlen($options['video_app'])-1 );
			$old_locale = kgvid_set_locale(plugin_dir_path(__FILE__).'images/sample-video-h264.mp4'); //fixes UTF-8 encoding problems
			$cmd = escapeshellcmd($test_path.'/'.$options['video_app'].' -i "'.plugin_dir_path(__FILE__).'images/sample-video-h264.mp4" -vframes 1 -f mjpeg "'.$uploads['path'].'/ffmpeg_exists_test.jpg"');
			exec ( $cmd );
			$restore_locale = setlocale(LC_CTYPE, $old_locale);
		}

		if ( file_exists($uploads['path'].'/ffmpeg_exists_test.jpg') ) { //FFMEG has executed successfully
			$ffmpeg_exists = true;
			unlink($uploads['path'].'/ffmpeg_exists_test.jpg');
			$options['app_path'] = $test_path;
		}

	}

	if ( $save ) {

		if ( $ffmpeg_exists == true ) { $options['ffmpeg_exists'] = "on"; }
		else {
			$options['ffmpeg_exists'] = "notinstalled";
			$options['browser_thumbnails'] = "on"; //if FFMPEG isn't around, this should be enabled
		}

		update_option('kgvid_video_embed_options', $options);

	}

	$arr = array (
		"exec_enabled"=>$exec_enabled,
		"ffmpeg_exists"=>$ffmpeg_exists,
		"output"=>$output,
		"function"=>$function,
		"app_path"=>$options['app_path']
	);
	return $arr;
}

function kgvid_set_video_dimensions($id, $gallery = false) {

	$options = kgvid_get_options();
	$moviefile = get_attached_file( $id );
	$video_meta = wp_get_attachment_metadata( $id );
	$kgvid_postmeta = kgvid_get_attachment_meta($id);

	if ( is_array($video_meta) && array_key_exists('width', $video_meta) ) { $kgvid_postmeta['actualwidth'] = $video_meta['width']; }
	if ( empty($kgvid_postmeta['width']) ) { $kgvid_postmeta['width'] = $kgvid_postmeta['actualwidth']; }

	if ( is_array($video_meta) && array_key_exists('height', $video_meta) ) { $kgvid_postmeta['actualheight'] = $video_meta['height']; }
	if ( empty($kgvid_postmeta['height']) ) { $kgvid_postmeta['height'] = $kgvid_postmeta['actualheight']; }

	if ( !empty($kgvid_postmeta['width']) && !empty($kgvid_postmeta['height']) ) { $aspect_ratio = $kgvid_postmeta['height']/$kgvid_postmeta['width']; }
	else { $aspect_ratio = $options['height']/$options['width']; }

	if ( $gallery ) {
		if ( !empty($kgvid_postmeta['actualwidth']) ) { $kgvid_postmeta['width'] = $kgvid_postmeta['actualwidth']; }
		if ( intval($kgvid_postmeta['width']) > intval($options['gallery_width']) ) { $kgvid_postmeta['width'] = $options['gallery_width']; }
	}
	else {
		if ( intval($kgvid_postmeta['width']) > intval($options['width']) || $options['minimum_width'] == "on" ) { $kgvid_postmeta['width'] = $options['width']; }
	}

	$kgvid_postmeta['height'] = round(intval($kgvid_postmeta['width'])*$aspect_ratio);

	$dimensions = array( 'width' => strval($kgvid_postmeta['width']), 'height' => strval($kgvid_postmeta['height']), 'actualwidth' => strval($kgvid_postmeta['actualwidth']), 'actualheight' => strval($kgvid_postmeta['actualheight']) );

	return $dimensions;

}

function kgvid_set_encode_dimensions($movie_info, $format_stats) {

	if ( empty($format_stats['width']) || is_infinite($format_stats['width']) ) { $format_stats['width'] = $movie_info['width']; }
	if ( empty($format_stats['height']) || is_infinite($format_stats['height']) ) { $format_stats['height'] = $movie_info['height']; }

	if ( intval($movie_info['width']) > $format_stats['width'] ) { $encode_movie_width = $format_stats['width']; }
	else { $encode_movie_width = $movie_info['width']; }
	$encode_movie_height = strval(round(floatval($movie_info['height']) / floatval($movie_info['width']) * $encode_movie_width));
	if ($encode_movie_height % 2 != 0) { $encode_movie_height--; } //if it's odd, decrease by 1 to make sure it's an even number

	if ( intval($encode_movie_height) > $format_stats['height'] ) {
		$encode_movie_height = $format_stats['height'];
		$encode_movie_width = strval(round(floatval($movie_info['width']) / floatval($movie_info['height']) * $encode_movie_height));
	}
	if ($encode_movie_width % 2 != 0) { $encode_movie_width--; } //if it's odd, decrease by 1 to make sure it's an even number

	$arr = array( 'width' => $encode_movie_width, 'height' => $encode_movie_height );
	return $arr;

}

function kgvid_encodevideo_info($movieurl, $postID) {

	$options = kgvid_get_options();

	$uploads = wp_upload_dir();
	$video_formats = kgvid_video_formats();
	$sanitized_url = kgvid_sanitize_url($movieurl);
	$movieurl = $sanitized_url['movieurl'];
	$moviefile = '';

	$encodevideo_info['moviefilebasename'] = $sanitized_url['basename'];
	$encodevideo_info['encodepath'] = $uploads['path'];


	if ( get_post_type($postID) == "attachment" ) { //if it's an attachment, not from URL
		$moviefile = get_attached_file($postID);
		if ( $moviefile ) {
			$path_parts = pathinfo($moviefile);
			$encodevideo_info['encodepath'] = $path_parts['dirname']."/";
			$encodevideo_info['sameserver'] = true;
			$args = array(
				'numberposts' => '-1',
				'post_parent' => $postID,
				'post_type' => 'attachment'
			);
		}
	}
	elseif ($moviefile == '' || !is_file($moviefile) ) {

		$url_parts = parse_url($uploads['url']);
		if ( is_array($url_parts) && array_key_exists('host', $url_parts) && strpos($movieurl, $url_parts['host']) !== false ) { //if we're on the same server
			$encodevideo_info['sameserver'] = true;
			$decodedurl = urldecode($movieurl);
			$parsed_url= parse_url($decodedurl);
			$fileinfo = pathinfo($decodedurl);
			$parsed_url['extension'] = $fileinfo['extension'];
			$parsed_url['filename'] = $fileinfo['basename'];
			$parsed_url['localpath'] = $_SERVER['DOCUMENT_ROOT'].$parsed_url['path'];
			// just in case there is a double slash created when joining document_root and path
			$parsed_url['localpath'] = preg_replace('/\/\//', '/', $parsed_url['localpath']);

			$encodevideo_info['encodepath'] = rtrim($parsed_url['localpath'], $parsed_url['filename']);
		}
		else { $encodevideo_info['sameserver'] = false; }

		$args = array(
			'numberposts' => '-1',
			'post_type' => 'attachment',
			'meta_key' => '_kgflashmediaplayer-externalurl',
			'meta_value' => $sanitized_url['movieurl']
		);

	}
	$children = get_posts( $args );

	foreach ( $video_formats as $format => $format_stats ) { //loop through each format

		$encodevideo_info[$format]['exists'] = false;
		$encodevideo_info[$format]['writable'] = false;

		//start with the new database info before checking other locations

		if ( $children ) {
			foreach ( $children as $child ) {
				$mime_type = get_post_mime_type($child->ID);
				$wp_attached_file = get_attached_file($child->ID);
				$video_meta = wp_get_attachment_metadata( $child->ID );
				$meta_format = get_post_meta($child->ID, '_kgflashmediaplayer-format', true);
				if ( $meta_format == $format || ( substr($wp_attached_file, -strlen($format_stats['suffix'])) == $format_stats['suffix'] && $meta_format == false )  ) {
					$encodevideo_info[$format]['url'] = wp_get_attachment_url($child->ID);
					$encodevideo_info[$format]['filepath'] = $wp_attached_file;
					$encodevideo_info[$format]['id'] = $child->ID;
					$encodevideo_info[$format]['exists'] = true;
					$encodevideo_info[$format]['writable'] = true;
					if ( is_array($video_meta) && array_key_exists('width', $video_meta) ) {
						$encodevideo_info[$format]['width'] = $video_meta['width'];
					}
					if ( is_array($video_meta) && array_key_exists('height', $video_meta) ) {
						$encodevideo_info[$format]['height'] = $video_meta['height'];
					}
					continue 2; //skip rest of children loop and format loop
				}
			}
		}

		//if the format's not in the database, check these places

		if ( array_key_exists('old_suffix', $format_stats) ) { $old_suffix = $format_stats['old_suffix']; }
		else { $old_suffix = $format_stats['suffix']; }
		$potential_locations = array(
			"same_directory" => array(
				'url' => $sanitized_url['noextension'].$format_stats['suffix'],
				'filepath' => $encodevideo_info['encodepath'].$encodevideo_info['moviefilebasename'].$format_stats['suffix'] ),
			"same_directory_old_suffix" => array(
				'url' => $sanitized_url['noextension'].$old_suffix,
				'filepath' => $encodevideo_info['encodepath'].$encodevideo_info['moviefilebasename'].$old_suffix ),
			"html5encodes" => array(
				'url' => $uploads['baseurl']."/html5encodes/".$encodevideo_info['moviefilebasename'].$old_suffix,
				'filepath' => $uploads['basedir']."/html5encodes/".$encodevideo_info['moviefilebasename'].$old_suffix ),
		);
		if ( !array_key_exists('old_suffix', $format_stats) ) { unset($potential_locations['same_directory_old_suffix']); }

		foreach ( $potential_locations as $name => $location ) {

			if ( file_exists($location['filepath']) ) {
				$encodevideo_info[$format]['exists'] = true;
				$encodevideo_info[$format]['url'] = $location['url'];
				$encodevideo_info[$format]['filepath'] = $location['filepath'];
				if ( is_writable($location['filepath']) ) { $encodevideo_info[$format]['writable'] = true; }
				break;
			}
			elseif ( !empty($postID) && !$encodevideo_info['sameserver'] && $name != "html5encodes" ) { //last resort if it's not on the same server, check url_exists

				$already_checked_url = get_post_meta($postID, '_kgflashmediaplayer-'.$sanitized_url['singleurl_id'].'-'.$format, true);
				if ( empty($already_checked_url) ) {
					if ( kgvid_url_exists(esc_url_raw(str_replace(" ", "%20", $location['url']))) ) {
						$encodevideo_info[$format]['exists'] = true;
						$encodevideo_info[$format]['url'] = $location['url'];
						update_post_meta($postID, '_kgflashmediaplayer-'.$sanitized_url['singleurl_id'].'-'.$format, $encodevideo_info[$format]['url']);
					}
					else {
						update_post_meta($postID, '_kgflashmediaplayer-'.$sanitized_url['singleurl_id'].'-'.$format, 'not found');
					}
				}
				else { //url already checked
					if ( substr($already_checked_url, 0, 4) == 'http' ) { //if it smells like a URL...
						$encodevideo_info[$format]['exists'] = true;
						$encodevideo_info[$format]['url'] = $already_checked_url;
					}
				}
			}//end if not on same server
		}//end potential locations loop

		if ( !$encodevideo_info[$format]['exists'] ) {
			if ( get_post_type($postID) == "attachment" && is_writeable($encodevideo_info['encodepath']) ) {
				$encodevideo_info[$format]['url'] = $sanitized_url['noextension'].$format_stats['suffix'];
				$encodevideo_info[$format]['filepath'] = $encodevideo_info['encodepath'].$encodevideo_info['moviefilebasename'].$format_stats['suffix'];
			}
			else {
				$encodevideo_info[$format]['url'] = $uploads['url'].'/'.$encodevideo_info['moviefilebasename'].$format_stats['suffix'];
				$encodevideo_info[$format]['filepath'] = $uploads['path'].'/'.$encodevideo_info['moviefilebasename'].$format_stats['suffix'];
			}
		}

	}//end format loop

	return apply_filters('kgvid_encodevideo_info', $encodevideo_info, $movieurl, $postID);
}

/**
* Get the dimensions of a video file
*
* @param unknown_type $video
* @return array(width,height)
* @author Jamie Scott
*/
function kgvid_get_video_dimensions($video = false) {
	$options = kgvid_get_options();
	$ffmpegPath = $options['app_path']."/".$options['video_app'];
	$movie_info = array();

	if ( strpos($video, 'http') === 0 ) { //if it's a URL
		$video_id = kgvid_url_to_id($video);
		if ( $video_id ) {
			$video_path = get_attached_file($video_id);
			if ( file_exists($video_path) ) { $video = $video_path; }
		}
		else { //not in the database
			if ( !empty($options['htaccess_login']) && strpos($video, 'http://') === 0 ) {
				$video = substr_replace($video, $options['htaccess_login'].':'.$options['htaccess_password'].'@', 7, 0);
			}
		}
	}

	$old_locale = kgvid_set_locale($video); //fixes UTF-8 encoding problems
	$command = escapeshellcmd($ffmpegPath . ' -i "' . $video . '"');
	$command = $command.' 2>&1';
	exec ( $command, $output );
	$restore_locale = setlocale(LC_CTYPE, $old_locale);
	$lastline = end($output);
	$lastline = prev($output)."<br />".$lastline;
	$movie_info['output'] = addslashes($lastline);
	$output = implode("\n", $output);
	$regex = "/([0-9]{2,4})x([0-9]{2,4})/";
	if (preg_match($regex, $output, $regs)) { $result = $regs[0]; }
	else {	$result = ""; }

	if ( !empty($result) ) {
		$movie_info['worked'] = true;
		$movie_info['width'] = $regs [1] ? $regs [1] : null;
		$movie_info['height'] = $regs [2] ? $regs [2] : null;

		preg_match('/Duration: (.*?),/', $output, $matches);
		$duration = $matches[1];
		$movie_duration_hours = intval(substr($duration, -11, 2));
		$movie_duration_minutes = intval(substr($duration, -8, 2));
		$movie_duration_seconds = floatval(substr($duration, -5));
		$movie_info['duration'] = ($movie_duration_hours * 60 * 60) + ($movie_duration_minutes * 60) + $movie_duration_seconds;

		preg_match('/rotate          : (.*?)\n/', $output, $matches);
		if ( $options['ffmpeg_vpre'] == false && is_array($matches) && array_key_exists(1, $matches) == true ) { $rotate = $matches[1]; }
		else { $rotate = "0"; }

		switch ($rotate) {
			case "90": $movie_info['rotate'] = 90; break;
			case "180": $movie_info['rotate'] = 180; break;
			case "270": $movie_info['rotate'] = 270; break;
			case "-90": $movie_info['rotate'] = 270; break;
			default: $movie_info['rotate'] = ""; break;
		}
		$old_locale = kgvid_set_locale($video); //fixes UTF-8 encoding problems
		$command = escapeshellcmd($ffmpegPath . ' -i "' . $video . '" -codecs');
		$command = $command.' 2>&1';
		exec ( $command, $codec_output );
		$restore_locale = setlocale(LC_CTYPE, $old_locale);
		$codec_output = implode("\n", $codec_output);
		$video_lib_array = array('libvorbis');
		$video_formats = kgvid_video_formats();
		foreach ( $video_formats as $format => $format_stats ) {
			if ( isset($format_stats['vcodec']) ) {
				$video_lib_array[] = $format_stats['vcodec'];
			}
		}
		$aac_array = kgvid_aac_encoders();
		$lib_list = array_merge($video_lib_array, $aac_array);
		foreach ($lib_list as $lib) {
			if ( strpos($codec_output, $lib) !== false ) { $movie_info['configuration'][$lib] = "true"; }
			else { $movie_info['configuration'][$lib] = "false"; }
		}

	}
	else {
		$movie_info['worked'] = false;
	}

	return apply_filters('kgvid_get_video_dimensions', $movie_info, $video, $output, $codec_output);
}

function kgvid_ffmpeg_rotate_strings($rotate, $width, $height) {

	$options = kgvid_get_options();

	if ( $rotate === false || $options['ffmpeg_vpre'] == "on" ) { $rotate = ""; }

	switch ($rotate) { //if it's a sideways mobile video

		case 90:
			if ( empty($options['ffmpeg_watermark']['url']) ) {
				$rotate = ' -vf "transpose=1"';
			}
			else {
				$rotate = '';
				$rotate_complex = 'transpose=1[rotate];[rotate]';
			}

			if ( $options['video_bitrate_flag'] == "on" || $options['ffmpeg_old_rotation'] == "on" ) {
				$rotate .= " -metadata rotate=0";
			}
			else {
				$rotate .= " -metadata:s:v:0 rotate=0";

				//swap height & width
				$tmp = $width;
				$width = $height;
				$height = $tmp;

			}

			break;

		case 270:

			if ( empty($options['ffmpeg_watermark']['url']) ) {
				$rotate = ' -vf "transpose=2"';
			}
			else {
				$rotate = '';
				$rotate_complex = 'transpose=2[rotate];[rotate]';
			}

			if ( $options['video_bitrate_flag'] == "on" || $options['ffmpeg_old_rotation'] == "on" ) {
				$rotate .= " -metadata rotate=0";
			}
			else {
				$rotate .= " -metadata:s:v:0 rotate=0";

				//swap height & width
				$tmp = $width;
				$width = $height;
				$height = $tmp;
			}

			break;

		case 180:

			if ( empty($options['ffmpeg_watermark']['url']) ) {
				$rotate = ' -vf "hflip,vflip"';
			}
			else {
				$rotate = '';
				$rotate_complex = 'hflip,vflip[rotate];[rotate]';
			}

			if ( $options['video_bitrate_flag'] == "on" || $options['ffmpeg_old_rotation'] == "on" ) {
				$rotate .= " -metadata rotate=0";
			}
			else {
				$rotate .= " -metadata:s:v:0 rotate=0";
			}

			break;

		default:
			$rotate = '';
			$rotate_complex = '';
			break;
	}

	if ( $options['ffmpeg_auto_rotate'] == "on" ) { $rotate = ''; $rotate_complex = ''; }

	$rotate_strings = array(
		'rotate' => $rotate,
		'complex' => $rotate_complex,
		'width' => $width,
		'height' => $height
	);

	return $rotate_strings;

}

function kgvid_ffmpeg_watermark_strings( $ffmpeg_watermark, $movie_width, $rotate_complex = '' ) {

	if ( is_array($ffmpeg_watermark) && array_key_exists('url', $ffmpeg_watermark) && !empty($ffmpeg_watermark['url']) ) {

		$watermark_width = strval(round(intval($movie_width)*(intval($ffmpeg_watermark['scale'])/100)));

		if ( $ffmpeg_watermark['align'] == 'right' ) {
			$watermark_align = "main_w-overlay_w-";
		}
		elseif ( $ffmpeg_watermark['align'] == 'center' ) {
			$watermark_align = "main_w/2-overlay_w/2-";
		}
		else { $watermark_align = ""; } //left justified

		if ( $ffmpeg_watermark['valign'] == 'bottom' ) {
			$watermark_valign = "main_h-overlay_h-";
		}
		elseif ( $ffmpeg_watermark['valign'] == 'center' ) {
			$watermark_valign = "main_h/2-overlay_h/2-";
		}
		else { $watermark_valign = ""; } //top justified

		if ( strpos($ffmpeg_watermark['url'], 'http://') === 0 ) {
			$watermark_id = false;
			$watermark_id = kgvid_url_to_id($ffmpeg_watermark['url']);
			if ( $watermark_id ) {
				$watermark_file = get_attached_file($watermark_id);
				if ( file_exists($watermark_file) ) { $ffmpeg_watermark['url'] = $watermark_file; }
			}
		}

		$watermark_strings['input'] = '-i "'.$ffmpeg_watermark['url'].'" ';
		$watermark_strings['filter'] = ' -filter_complex "[1:v]scale='.$watermark_width.':-1[watermark];[0:v]'.$rotate_complex.'[watermark]overlay='.$watermark_align.'main_w*'.round($ffmpeg_watermark['x']/100, 3).':'.$watermark_valign.'main_w*'.round($ffmpeg_watermark['y']/100, 3).'"';

	}
	else {

		$watermark_strings['input'] = '';
		$watermark_strings['filter'] = '';
	
	}

	return $watermark_strings;

}

function kgvid_generate_encode_string($input, $output, $movie_info, $format, $width, $height, $rotate) {

	$options = kgvid_get_options();
	$libraries = $movie_info['configuration'];
	$encode_string = strtoupper($options['video_app'])." not found";
	$video_formats = kgvid_video_formats();

	if ( $options['ffmpeg_exists'] == "on" && isset($video_formats[$format]) ) {

		if ( $options['video_app'] == "avconv" || $options['video_bitrate_flag'] != "on" ) {
			$video_bitrate_flag = "b:v";
			$audio_bitrate_flag = "b:a";
			$profile_flag = "profile:v";
			$level_flag = "level:v";
			$qscale_flag = "q:v";
		}

		else {
			$video_bitrate_flag = "b";
			$audio_bitrate_flag = "ab";
			$profile_flag = "profile";
			$level_flag = "level";
			$qscale_flag = "qscale";
		}

		$rotate_strings = kgvid_ffmpeg_rotate_strings($rotate, $width, $height);
		$width = $rotate_strings['width']; //in case rotation requires swapping height and width
		$height = $rotate_strings['height']; 

		if ( $options['rate_control'] == "crf" ) {
			$crf_option = $video_formats[$format]['type'].'_CRF';
			if ( $video_formats[$format]['type'] == 'vp9' ) { 
				$options['vp9_CRF'] = round((-0.000002554 * $width * $height) + 35); //formula to generate close to Google-recommended CRFs https://developers.google.com/media/vp9/settings/vod/
			}
			$crf_flag = "crf";
			if ( $video_formats[$format]['type'] == 'ogv' ) { //ogg doesn't do CRF
				$crf_flag = $qscale_flag; 
			}
			if ( isset( $options[$crf_option] ) ) {
				$rate_control_flag = " -".$crf_flag." ".$options[$crf_option];
			}
			else {
				$rate_control_flag = '';
			}
		}
		else {
			if ( $video_formats[$format]['type'] == 'vp9' ) {
				$average_bitrate = round(102 + 0.000876 * $width * $height + 1.554*pow(10, -10) * pow($width * $height, 2) );
				$maxrate = round($average_bitrate * 1.45);
				$minrate = round($average_bitrate * .5);
				$rate_control_flag = " -".$video_bitrate_flag." ".$average_bitrate."k -maxrate ".$maxrate."k -minrate ".$minrate."k";
			}
			else {
				$rate_control_flag = " -".$video_bitrate_flag." ".round(floatval($options['bitrate_multiplier'])*$width*$height*30/1024)."k";
			}
		}

		if ( $options['audio_channels'] == 'on' ) {
			$audio_channels_flag = '-ac 2 ';
		} 
		else {
			$audio_channels_flag = '';
		}

		$watermark_strings = kgvid_ffmpeg_watermark_strings($options['ffmpeg_watermark'], $movie_info['width'], $rotate_strings['complex']);

		if ( $video_formats[$format]['type'] == 'h264' ) {

			$aac_array = kgvid_aac_encoders();
			foreach ( $aac_array as $aaclib ) { //cycle through available AAC encoders in order of quality
				if ( $libraries[$aaclib] == "true" ) { break; }
			}
			if ( $aaclib == "aac" ) { $aaclib = "aac -strict experimental"; } //the built-in aac encoder is considered experimental

			$vpre_flags = "";
			if ( $options['ffmpeg_vpre'] == 'on' ) { $vpre_flags = ' -coder 0 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 6 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 0 -refs 1 -trellis 1 -flags2 +bpyramid+mixed_refs-wpred-dct8x8+fastpskip -wpredp 0 -rc_lookahead 30 -maxrate 10000000 -bufsize 10000000'; }

			$movflags = "";
			if ( $options['moov'] == "movflag" ) {
				$movflags = " -movflags faststart";
			}

			$profile_text = "";
			if ( $options['h264_profile'] != "none" ) {
				$profile_text = " -".$profile_flag." ".$options['h264_profile'];
				if ( $options['h264_profile'] != "high422" && $options['h264_profile'] != "high444" ) {
					$profile_text .= " -pix_fmt yuv420p"; //makes sure output is converted to 4:2:0
				}
			}

			$level_text = "";
			if ( $options['h264_level'] != "none" ) {
				$level_text = " -".$level_flag." ".round(floatval($options['h264_level'])*10);
			}

			$ffmpeg_options = "-acodec ".$aaclib." -".$audio_bitrate_flag." ".$options['audio_bitrate']."k -s ".$width."x".$height." -vcodec libx264".$vpre_flags.$movflags.$profile_text.$level_text;

		}
		else { //if it's not H.264 the settings are basically the same
			$ffmpeg_options = "-acodec libvorbis -".$audio_bitrate_flag." ".$options['audio_bitrate']."k -s ".$width."x".$height." -vcodec ".$video_formats[$format]['vcodec'];
			if ( $options['rate_control'] == "crf" ) {
				if ( $video_formats[$format]['type'] == 'webm' ) {
					$ffmpeg_options .= " -".$video_bitrate_flag." ".round(floatval($options['bitrate_multiplier'])*1.25*$width*$height*30/1024)."k"; //set a max bitrate 25% larger than the ABR. Otherwise libvpx goes way too low.
				}
				if ( $video_formats[$format]['type'] == 'vp9' ) {
					$ffmpeg_options .= " -".$video_bitrate_flag." 0";
				}
			}
		}

		$nice = "";
		$sys = strtoupper(PHP_OS); // Get OS Name
		if( substr($sys,0,3) != "WIN" && $options['nice'] == "on" ) { $nice = "nice "; }

		if ( !empty($options['htaccess_login']) && strpos($input, 'http://') === 0 ) {
			$input = substr_replace($input, $options['htaccess_login'].':'.$options['htaccess_password'].'@', 7, 0);
		}

		$nostdin = "";
		if ( $options['nostdin'] == "on" && $options['video_app'] == 'ffmpeg' ) { $nostdin = " -nostdin"; }

		$encode_string = array();
		$encode_string[1] = $nice.$options['app_path']."/".$options['video_app'].$nostdin.' -y -i "'.$input.'" '.$watermark_strings['input'].$audio_channels_flag.$ffmpeg_options.$rate_control_flag.$rotate_strings['rotate']." -threads ".$options['threads'];
		$encode_string[2] = $watermark_strings['filter'];
		$encode_string[3] = ' "'.$output.'"';

		$encode_string = apply_filters('kgvid_generate_encode_string', $encode_string, $input, $output, $movie_info, $format, $width, $height, $rotate, $nostdin);

	} //if FFMPEG is found

	$options['encode_string'] = $encode_string;
	update_option('kgvid_video_embed_options', $options);

	return $encode_string;

}

class kgvid_Process {

    private $pid;
    private $command;

    public function __construct($cl=false){
        if ($cl != false){
            $this->command = $cl;
            $this->runCom();
        }
    }
    private function runCom(){
		$sys = strtoupper(PHP_OS); // Get OS Name
		if(substr($sys,0,3) == "WIN") { $this->OS = "windows"; }
		else { $this->OS = "linux";	}

		$command = $this->command;
		if ($this->OS != "windows") {
			exec($command ,$op);
			$this->output = $op;
			$this->pid = (int)$op[0];
		}
		else {
			proc_close(proc_open ('start /B '.$command, array(), $foo));
		}
    }

    public function setPid($pid){
        $this->pid = $pid;
    }

    public function getPid(){
        return $this->pid;
    }

    public function status(){
        $command = 'ps -p '.$this->pid;
        exec($command,$op);
        if (!isset($op[1]))return false;
        else return true;
    }

    public function start(){
        if ($this->command != '')$this->runCom();
        else return true;
    }

    public function stop(){
        $command = 'kill '.$this->pid;
        exec($command);
        if ($this->status() == false)return true;
        else return false;
    }
}// class Process

function kgvid_video_embed_enqueue_styles() {

	$options = kgvid_get_options();

	wp_register_script( 'kgvid_video_embed', plugins_url("/js/kgvid_video_embed.js", __FILE__), array('jquery'), $options['version'], true );

	wp_localize_script( 'kgvid_video_embed', 'kgvidL10n_frontend', array(
		'ajaxurl' => admin_url( 'admin-ajax.php', is_ssl() ? 'admin' : 'http' ),
		'ajax_nonce' => wp_create_nonce('kgvid_frontend_nonce'),
		'playstart' => _x("Play Start", 'noun for Google Analytics event', 'video-embed-thumbnail-generator'),
		'completeview' => _x("Complete View", 'noun for Google Analytics event', 'video-embed-thumbnail-generator'),
		'next' => _x("Next", 'button text to play next video', 'video-embed-thumbnail-generator'),
		'previous' => _x("Previous", 'button text to play previous video', 'video-embed-thumbnail-generator'),
		'quality' => _x("Quality", 'text above list of video resolutions', 'video-embed-thumbnail-generator'),
		'fullres' => _x("Full", 'Full resolution', 'video-embed-thumbnail-generator')
	) );

	wp_register_script( 'simplemodal', plugins_url("/js/jquery.simplemodal.1.4.5.min.js", __FILE__), '', '1.4.5', true );

	//Video.js styles

	if ( $options['embed_method'] == "Video.js" ||  $options['embed_method'] == "Video.js v7" ) {

		if ( $options['embed_method'] == "Video.js" ) {

			$videojs_register = array(
				'version' => '5.20.5',
				'path' => 'v5'
			);

		}
		if ( $options['embed_method'] == "Video.js v7" ) {

			$videojs_register = array(
				'version' => $options['videojs_version'],
				'path' => 'v7'
			);

		}

		wp_register_script( 'video-js', plugins_url("", __FILE__).'/video-js/'.$videojs_register['path'].'/video.min.js', '', $videojs_register['version'], true );
		wp_register_script( 'video-quality-selector', plugins_url("", __FILE__).'/video-js/'.$videojs_register['path'].'/video-quality-selector.js', array('video-js'), $options['version'], true );
		wp_enqueue_style( 'video-js', plugins_url("", __FILE__).'/video-js/'.$videojs_register['path'].'/video-js.min.css', '', $videojs_register['version'] );
		if ( $options['js_skin'] == 'kg-video-js-skin' ){ wp_enqueue_style( 'video-js-kg-skin', plugins_url("", __FILE__).'/video-js/'.$videojs_register['path'].'/kg-video-js-skin.css', '', $options['version'] ); }

		$locale = kgvid_get_videojs_locale();
		if ( $locale != 'en' && file_exists(plugin_dir_path(__FILE__).'video-js/'.$videojs_register['path'].'/lang/'.$locale.'.js')) {
			wp_register_script( 'videojs-l10n', plugins_url("", __FILE__).'/video-js/'.$videojs_register['path'].'/lang/'.$locale.'.js', array('video-js'), $videojs_register['version'], true );
		}

	}

	if ( $options['embed_method'] == "WordPress Default" ) {

		global $wp_version;
		
		if ( $wp_version >= 4.9 ) {
			$sourcechooser_path = plugins_url( 'js/mejs-source-chooser.js', __FILE__ );
		}
		else {
			$sourcechooser_path = plugins_url( 'js/mep-feature-sourcechooser.js', __FILE__ );
		}
		wp_register_script( 'mejs_sourcechooser', $sourcechooser_path, array( 'mediaelement' ), $options['version'], true );
		if ( $wp_version >= 4.9 ) {
			$speed_path = plugins_url( 'js/mejs-speed.js', __FILE__ );
		}
		else {
			$speed_path = plugins_url( 'js/mep-speed.js', __FILE__ );;
		}
		wp_register_script( 'mejs-speed', $speed_path, array( 'mediaelement' ), $options['version'], true );

		wp_enqueue_style( 'video-js', plugins_url("", __FILE__).'/video-js/v7/video-js.min.css', '', $options['videojs_version'] ); //gives access to video-js icons for resolution gear selector and social logos
		
	}

	//plugin-related frontend styles, requires video-js
	if ( $options['embed_method'] != 'None' ) {
		wp_enqueue_style( 'kgvid_video_styles', plugins_url("/css/kgvid_styles.css", __FILE__), array( 'video-js' ), $options['version'] );
	}

	if ( $options['alwaysloadscripts'] == 'on' ) {
		kgvid_enqueue_shortcode_scripts();
		wp_enqueue_script( 'simplemodal', plugins_url("/js/jquery.simplemodal.1.4.5.min.js", __FILE__), '', '1.4.5', true );
	}	

}
add_action('wp_enqueue_scripts', 'kgvid_video_embed_enqueue_styles', 12);

function enqueue_kgvid_script() { //loads plugin-related scripts in the admin area

	if ( !wp_script_is('kgvid_video_plugin_admin', 'enqueued') ) {

		$options = kgvid_get_options();

		wp_enqueue_script( 'kgvid_video_plugin_admin', plugins_url('/js/kgvid_video_plugin_admin.js', __FILE__), array('jquery'), $options['version'], true );
		wp_enqueue_style( 'video_embed_thumbnail_generator_style', plugins_url('/css/video-embed-thumbnail-generator_admin.css', __FILE__), '', $options['version'] );

		wp_localize_script( 'kgvid_video_plugin_admin', 'kgvidL10n', array(
				'ajax_nonce' => wp_create_nonce('kgvid_admin_nonce'),
				'wait' => _x('Wait', 'please wait', 'video-embed-thumbnail-generator'),
				'hidevideo' => __('Hide video...', 'video-embed-thumbnail-generator'),
				'choosefromvideo' => __('Choose from video...', 'video-embed-thumbnail-generator'),
				'cantloadvideo' => __('Can\'t load video', 'video-embed-thumbnail-generator'),
				'cantmakethumbs' => sprintf( __('Error: unable to load video in browser for thumbnail generation and %1$s not found at %2$s', 'video-embed-thumbnail-generator'), strtoupper($options['video_app']), $options['app_path'] ),
				'choosethumbnail' => __('Choose Thumbnail:', 'video-embed-thumbnail-generator'),
				'saveallthumbnails' => __('Save All Thumbnails', 'video-embed-thumbnail-generator'),
				'saving' => __('Saving...', 'video-embed-thumbnail-generator'),
				'loading' => __('Loading...', 'video-embed-thumbnail-generator'),
				'generate' => __('Generate', 'video-embed-thumbnail-generator'),
				'randomize' => __('Randomize', 'video-embed-thumbnail-generator'),
				'ffmpegnotfound' => sprintf( __('%s not found', 'video-embed-thumbnail-generator'), strtoupper($options['video_app']) ),
				'pleasevalidurl' => __('Please enter a valid video URL', 'video-embed-thumbnail-generator'),
				'deletemessage' => __("You are about to permanently delete the encoded video.\n 'Cancel' to stop, 'OK' to delete.", 'video-embed-thumbnail-generator'),
				'saved' => __('Saved.', 'video-embed-thumbnail-generator'),
				'runningtest' => __('Running test...', 'video-embed-thumbnail-generator'),
				'ffmpegrequired' => __('FFMPEG or LIBAV required for these functions.', 'video-embed-thumbnail-generator'),
				'featuredwarning' => __("You are about to set all existing video thumbnails previously generated by this plugin as the featured images for their posts. There is no 'undo' button, so proceed at your own risk.", 'video-embed-thumbnail-generator'),
				'autothumbnailwarning' => __("You are about to create thumbnails for every video in your Media Library that doesn't already have one. This might take a long time. There is no 'undo' button, so proceed at your own risk.\n\nNumber of videos without thumbnails: ", 'video-embed-thumbnail-generator'),
				'autoencodewarning' => __("You are about to add every video in your Media Library to the video encode queue if it hasn't already been encoded. This might take a long time.", 'video-embed-thumbnail-generator'),
				'nothumbstomake' => __("No files generated. All videos are processed already.", 'video-embed-thumbnail-generator'),
				'cancel_ok' => __("'Cancel' to stop, 'OK' to proceed.", 'video-embed-thumbnail-generator'),
				'processing' => __('Processing...', 'video-embed-thumbnail-generator'),
				'parentwarning_posts' => __("You are about to set all existing video thumbnails previously generated by this plugin as attachments of their posts rather than their associated videos. Proceed at your own risk.", 'video-embed-thumbnail-generator'),
				'parentwarning_videos' => __("You are about to set all existing video thumbnails previously generated by this plugin as attachments of their videos rather than their associated posts. Proceed at your own risk.", 'video-embed-thumbnail-generator'),
				'clearqueuedwarning' => __("You are about to clear all videos not yet encoded.", 'video-embed-thumbnail-generator'),
				'clearallwarning' => __("You are about to clear all videos currently encoding, not yet encoded, completed successfully, and completed with errors.", 'video-embed-thumbnail-generator'),
				'complete' => __('Complete', 'video-embed-thumbnail-generator'),
				'tracktype' => __('Track type:', 'video-embed-thumbnail-generator'),
				'subtitles' => __('subtitles', 'video-embed-thumbnail-generator'),
				'captions' => __('captions', 'video-embed-thumbnail-generator'),
				'chapters' => __('chapters', 'video-embed-thumbnail-generator'),
				'choosetextfile' => __('Choose a Text File', 'video-embed-thumbnail-generator'),
				'settracksource' => __('Set as track source', 'video-embed-thumbnail-generator'),
				'choosefromlibrary' => __('Choose from Library', 'video-embed-thumbnail-generator'),
				'languagecode' => __('Language code:', 'video-embed-thumbnail-generator'),
				'label' => _x('Label:', 'noun', 'video-embed-thumbnail-generator'),
				'trackdefault' => __('Default:', 'video-embed-thumbnail-generator'),
				'custom' => _x('Custom', 'Custom format', 'video-embed-thumbnail-generator'),
				'clearingcache' => __('Clearing URL cache...', 'video-embed-thumbnail-generator'),
				'queue_pause' => __('Pause the queue. Any videos currently encoding will complete.', 'video-embed-thumbnail-generator'),
				'queue_paused' => __('Queue is paused. Press play button at top of screen to start.', 'video-embed-thumbnail-generator'),
				'queue_play' => __('Start encoding', 'video-embed-thumbnail-generator'),
				'nothing_to_encode' => __('Nothing to encode', 'video-embed-thumbnail-generator'),
		) );
	}

}
add_action('wp_enqueue_media', 'enqueue_kgvid_script'); //always enqueue scripts if media elements are loaded

function maybe_enqueue_kgvid_script($hook_suffix) {

	if ( $hook_suffix == 'settings_page_video_embed_thumbnail_generator_settings'
		|| $hook_suffix == 'tools_page_kgvid_video_encoding_queue'
		|| $hook_suffix == 'settings_page_kgvid_network_video_encoding_queue'
	) {
		enqueue_kgvid_script();
	}

}
add_action('admin_enqueue_scripts', 'maybe_enqueue_kgvid_script'); //only enqueue scripts on settings page or encode queue

function kgvid_get_first_embedded_video( $post ) {

	$url = '';
	$attributes = array();

	$first_embedded_video_meta = get_post_meta($post->ID, '_kgvid_first_embedded_video', true);

	if ( !empty($first_embedded_video_meta) ) {

		if ( is_array($first_embedded_video_meta['atts']) ) {
			$dataattributes = array_map('kgvid_build_paired_attributes', array_values($first_embedded_video_meta['atts']), array_keys($first_embedded_video_meta['atts']));

			$dataattributes = ' '.implode(' ', $dataattributes);
		}
		else { $dataattributes = $first_embedded_video_meta['atts']; }

		$shortcode_text = '[videopack'.$dataattributes.']'.$first_embedded_video_meta['content'].'[/videopack]';

	}
	else { $shortcode_text = $post->post_content; }

	$pattern = get_shortcode_regex();
	preg_match_all( '/'. $pattern .'/s', $shortcode_text, $matches );

	if ( is_array($matches)
		&& array_key_exists( 2, $matches ) && array_key_exists( 5, $matches )
		&& ( in_array( 'videopack', $matches[2] ) 
			|| in_array( 'VIDEOPACK', $matches[2] ) 
			|| in_array( 'KGVID', $matches[2] ) 
			|| in_array( 'FMP', $matches[2] ) 
		)
	) { //if videopack, KGVID, or FMP shortcode is in posts on this page.

		if ( isset($matches) ) {

			$shortcode_names = array('videopack', 
				'VIDEOPACK', 
				'KGVID', 
				'FMP'
			);

			foreach ($shortcode_names as $shortcode ) {
				$first_key = array_search($shortcode, $matches[2]);
				if ( $first_key !== false ) { 
					break;
				}
			}

			if ( $first_key !== false ) {

				$url = "";

				if ( array_key_exists( 3, $matches ) ) {
					$attributes = shortcode_parse_atts($matches[3][$first_key]);
				}

				if ( is_array($attributes) && array_key_exists( 'id', $attributes ) ) {
					$url = wp_get_attachment_url($attributes['id']);
				}//if there's an ID attribute

				elseif ( !empty($matches[5][$first_key]) ) { //there's a URL but no ID

					$url = $matches[5][$first_key];
					if ( !is_array($attributes) ) {
						$attributes = array();
					}
					$attributes['id'] = kgvid_url_to_id($matches[5][$first_key]);

				}

				elseif ( ( is_array($attributes) && !array_key_exists( 'id', $attributes ) )
						|| empty($attributes)
				) {

					$post_id = $post->ID;

					$args = array(
						'numberposts' => 1,
						'post_mime_type' => 'video',
						'post_parent' => $post_id,
						'post_status' => null,
						'post_type' => 'attachment'
					);
					$video_attachment = get_posts($args);

					if ( $video_attachment ) {
						$attributes['id'] = $video_attachment[0]->ID;
						$url = wp_get_attachment_url($attributes['id']);
					}

				}//if no URL or ID attribute

			}//if there's a KGVID shortcode in the post
		}//if there's a shortcode in the post
		elseif ( is_attachment() ) {
			$attributes['id'] = $post->ID;
			$attributes['url'] = wp_get_attachment_url($post->ID);
		}

		if ( is_array($attributes) && array_key_exists( 'id', $attributes ) ) {

			$kgvid_postmeta = kgvid_get_attachment_meta($attributes['id']);
			$kgvid_postmeta['poster'] = get_post_meta($attributes['id'], "_kgflashmediaplayer-poster", true);
			$dimensions = kgvid_set_video_dimensions($attributes['id']);
			$attributes = array_merge($dimensions, array_filter($kgvid_postmeta), $attributes);

		}

	}

	if ( !is_array($attributes) ) {
		$attributes = array();
	}

	$attributes['url'] = $url;
	return $attributes;

}

function kgvid_video_embed_print_scripts() {

	global $wp_query;
	global $wpdb;
	global $wp_version;
    $posts = $wp_query->posts;
	$pattern = get_shortcode_regex();
	$options = kgvid_get_options();

	if ( !empty($posts) && is_array($posts) ) {
		foreach ( $posts as $post ) {
			$first_embedded_video = kgvid_get_first_embedded_video( $post );
			if ( !empty($first_embedded_video['url']) ) { //if KGVID or FMP shortcode is in posts on this page.

				if ( $options['open_graph'] == "on" ) {

					remove_action('wp_head','jetpack_og_tags');
					echo '<meta property="og:url" content="'.esc_attr(get_permalink($post)).'" />'."\n";
					echo '<meta property="og:title" content="'.esc_attr(get_the_title($post)).'" />'."\n";
					echo '<meta property="og:description" content="'.esc_attr(kgvid_generate_video_description($first_embedded_video, $post)).'" />'."\n";
					echo '<meta property="og:video" content="'.$first_embedded_video['url'].'" />'."\n";
					$secure_url = str_replace('http://', 'https://', $first_embedded_video['url']);
					echo '<meta property="og:video:secure_url" content="'.$secure_url.'" />'."\n";
					$mime_type_check = kgvid_url_mime_type($first_embedded_video['url'], $post->ID);
					echo '<meta property="og:video:type" content="'.$mime_type_check['type'].'" />'."\n";

					if ( array_key_exists( 'width', $first_embedded_video ) ) {
						echo '<meta property="og:video:width" content="'.$first_embedded_video['width'].'" />'."\n";
						if ( array_key_exists( 'height', $first_embedded_video ) ) {
							echo '<meta property="og:video:height" content="'.$first_embedded_video['height'].'" />'."\n";
						}
					}

					if ( array_key_exists( 'poster', $first_embedded_video) ) {
						echo '<meta property="og:image" content="'.$first_embedded_video['poster'].'" />'."\n";
						if ( array_key_exists( 'width', $first_embedded_video ) ) {
						echo '<meta property="og:image:width" content="'.$first_embedded_video['width'].'" />'."\n";
						if ( array_key_exists( 'height', $first_embedded_video ) ) {
							echo '<meta property="og:image:height" content="'.$first_embedded_video['height'].'" />'."\n";
						}
					}
					}

				}

				if ( $options['twitter_card'] == "on" && array_key_exists('id', $first_embedded_video) && !empty($first_embedded_video['id']) ) {

					add_filter( 'jetpack_disable_twitter_cards', '__return_true', 99 );

					echo '<meta name="twitter:card" content="player">'."\n";
					if ( !empty($options['twitter_username']) ) { echo '<meta name="twitter:site" content="@'.esc_attr($options['twitter_username']).'">'."\n"; }
					echo '<meta name="twitter:title" content="'.esc_attr($post->post_title).'">'."\n";
					echo '<meta name="twitter:description" content="'.substr(esc_attr(kgvid_generate_video_description($first_embedded_video, $post)), 0, 200).'">'."\n";
					if ( array_key_exists('poster', $first_embedded_video) ) {
						echo '<meta name="twitter:image" content="'.esc_attr(str_replace('http://', 'https://', $first_embedded_video['poster'])).'">'."\n";
					}
					echo '<meta name="twitter:player" content="'.esc_attr(str_replace('http://', 'https://', get_attachment_link($first_embedded_video['id']))).'?videopack[enable]=true'.'">'."\n";
					if ( array_key_exists( 'width', $first_embedded_video ) ) {
						echo '<meta name="twitter:player:width" content="'.esc_attr($first_embedded_video['width']).'">'."\n";
					}
					if ( array_key_exists( 'height', $first_embedded_video ) ) {
						echo '<meta name="twitter:player:height" content="'.esc_attr($first_embedded_video['height']).'">'."\n";
					}

					$encodevideo_info = kgvid_encodevideo_info($first_embedded_video['url'], $first_embedded_video['id']);
					$twitter_stream = false;
					if ( array_key_exists('mobile', $encodevideo_info) && $encodevideo_info['mobile']['exists'] ) {
						$twitter_stream = $encodevideo_info['mobile']['url'];
					}
					elseif ( get_post_mime_type($first_embedded_video['id']) == 'video/mp4' ) {
						$twitter_stream = $first_embedded_video['url'];
					}
					if ( $twitter_stream ) {
						echo '<meta name="twitter:player:stream" content="'.esc_attr(str_replace('http://', 'https://', $twitter_stream)).'">'."\n";
						echo '<meta name="twitter:player:stream:content_type" content="video/mp4; codecs=&quot;avc1.42E01E1, mp4a.40.2&quot;">'."\n";
					}


				}

				if ( $wp_version < 4.4 && array_key_exists( 'id', $first_embedded_video ) && $options['oembed_provider'] == "on" && is_singular() ) {

					echo '<link rel="alternate" type="application/json+oembed" href="' . site_url('/?videopack[oembed]=json&amp;videopack[post_id]=' .$first_embedded_video['id']).'" />'."\n";
					echo '<link rel="alternate" type="application/xml+oembed" href="' . site_url('/?videopack[oembed]=xml&amp;videopack[post_id]='.$first_embedded_video['id']).'" />'."\n";

				}

				break; //end execution after the first video embedded using the shortcode

			}//end if shortcode is in post or is attachment
		}//end post loop
	}//end if posts

}
add_action('wp_head', 'kgvid_video_embed_print_scripts', 9 );

function kgvid_change_oembed_data( $data, $post, $width, $height ) {

	$options = kgvid_get_options();

	$first_embedded_video = kgvid_get_first_embedded_video( $post );

	if ( !empty($data) && !empty($first_embedded_video['url']) && $options['oembed_provider'] == "on" ) {

		$data['type'] = 'video';

		if ( !empty($first_embedded_video['poster']) ) { $data['thumbnail_url'] = $first_embedded_video['poster']; }

	}
	
	return apply_filters('kgvid_change_oembed_data', $data, $post, $width, $height );

}
if ( function_exists('get_oembed_response_data') ) { add_filter( 'oembed_response_data', 'kgvid_change_oembed_data', 11, 4 ); }

function kgvid_change_oembed_iframe_url ( $embed_url, $post ) {

	$options = kgvid_get_options();

	if ( $options['oembed_provider'] == "on" ) {

		$first_embedded_video = kgvid_get_first_embedded_video( $post );

		if ( array_key_exists( 'id', $first_embedded_video ) ) {

			$embed_url = site_url('/')."?attachment_id=".$first_embedded_video['id']."&amp;kgvid_video_embed[enable]=true";

		}

	}

	return apply_filters('kgvid_change_oembed_iframe_url', $embed_url, $post);

}
if ( function_exists('get_post_embed_url') ) { add_filter( 'post_embed_url', 'kgvid_change_oembed_iframe_url', 11, 2 ); } //added in WP version 4.4

function kgvid_change_oembed_html($output, $post, $width, $height) {

	$output = preg_replace('/<blockquote(.*)<\/script>/s', '', $output);

	return $output;

}
if ( function_exists('get_post_embed_html') ) { add_filter( 'embed_html', 'kgvid_change_oembed_html', 11, 4 ); } //added in WP version 4.4

function kgvid_enqueue_shortcode_scripts() {

	$options = kgvid_get_options();

	if ( $options['embed_method'] == "Video.js" || $options['embed_method'] == "Video.js v7" ) {
			
			wp_enqueue_script( 'video-js' );
			wp_enqueue_script( 'videojs-l10n' );

			if ( $options['alwaysloadscripts'] == 'on' ) {
				wp_enqueue_script( 'video-quality-selector' );
			}
			
	}

	do_action( 'kgvid_enqueue_shortcode_scripts' );

	wp_enqueue_script( 'kgvid_video_embed' );

}

function kgvid_gallery_page($page_number, $query_atts, $last_video_id = 0) {

	$options = kgvid_get_options();
	global $kgvid_video_id;
	if ( !$kgvid_video_id ) { $kgvid_video_id = $last_video_id + 1; }

	$code = '';

	if ( $query_atts['gallery_orderby'] == 'menu_order' ) { $query_atts['gallery_orderby'] = 'menu_order ID'; }
	if ( $options['gallery_pagination'] != 'on' && empty($query_atts['gallery_per_page']) || $query_atts['gallery_per_page'] == 'false' ) { $query_atts['gallery_per_page'] = -1; }

	$args = array(
		'post_type' => 'attachment',
		'orderby' => $query_atts['gallery_orderby'],
		'order' => $query_atts['gallery_order'],
		'post_mime_type' => 'video',
		'posts_per_page' => $query_atts['gallery_per_page'],
		'paged' => $page_number,
		'post_status' => 'published',
		'post_parent' => $query_atts['gallery_id']
	);

	if ( !empty($query_atts['gallery_exclude']) ) {
		$exclude_arr = wp_parse_id_list($query_atts['gallery_exclude']);
		if ( !empty($exclude_arr) ) {
			$args['post__not_in'] = $exclude_arr;
		}
	}

	if ( !empty($query_atts['gallery_include']) ) {
		$include_arr = wp_parse_id_list($query_atts['gallery_include']);
		if ( !empty($include_arr) ) {
			$args['post__in'] = $include_arr;
			if ( $args['orderby'] == 'menu_order ID' ) {
				$args['orderby'] = 'post__in'; //sort by order of IDs in the gallery_include parameter
			}
			unset($args['post_parent']);
		}
	}

	$attachments = new WP_Query($args);

	if ( $attachments->have_posts() ) {

		foreach ( $attachments->posts as $attachment ) {

			$thumbnail_url = get_post_meta($attachment->ID, "_kgflashmediaplayer-poster", true);
			$poster_id = get_post_meta($attachment->ID, '_kgflashmediaplayer-poster-id', true);
			$thumbnail_srcset = false;

			if ( !empty($poster_id) ) {
				$thumbnail_url = wp_get_attachment_url($poster_id);
				$thumbnail_srcset = wp_get_attachment_image_srcset($poster_id);
				if ( intval($query_atts['gallery_thumb']) <= get_option('medium_size_h') ) {
					$poster_post = get_post($poster_id);
					if ( $poster_post->guid == $thumbnail_url ) {
						$thumbnail_url = kgvid_get_attachment_medium_url($poster_id);
					} //use the "medium" size image if available
				}
			}
			if (!$thumbnail_url) { $thumbnail_url = $options['poster']; } //use the default poster if no thumbnail set
			if (!$thumbnail_url) { $thumbnail_url = plugins_url('/images/nothumbnail.jpg', __FILE__);} //use the blank image if no other option

			if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {

				if ( $thumbnail_url ) { $thumbnail_url = set_url_scheme($thumbnail_url); }

			}

			$below_video = 0;
			if ( !empty($attachment->post_excerpt) || $query_atts['view_count'] == "true" ) { $below_video = 1; }

			$kgvid_postmeta = kgvid_get_attachment_meta( $attachment->ID );

			$play_button_html = '';

			if ( $options['embed_method'] == "WordPress Default" ) {

				$library = apply_filters( 'wp_video_shortcode_library', 'mediaelement' );
				if ( 'mediaelement' === $library && did_action( 'init' ) ) {
					wp_enqueue_style( 'wp-mediaelement' );
					wp_enqueue_script( 'wp-mediaelement' );
				}

				$play_button_class = "mejs-overlay-button";
				$play_scale = strval( round(intval($query_atts["gallery_thumb"])/400,2) );
				$play_translate = 5;
			}

			else {
				$play_button_class = "vjs-big-play-button";
				$play_scale = strval( round(intval($query_atts["gallery_thumb"])/600,2) );
				$play_translate = 30;
			}

			$play_button_html = '<div class="'.esc_attr($options['js_skin']).'" ><button class="'.$play_button_class.'" style="-webkit-transform: scale('.$play_scale.') translateY(-'.$play_translate.'px); -o-transform: scale('.$play_scale.') translateY(-'.$play_translate.'px); -ms-transform: scale('.$play_scale.') translateY(-'.$play_translate.'px); transform: scale('.$play_scale.') translateY(-'.$play_translate.'px);"></button></div>';

			$dimensions = kgvid_set_video_dimensions($attachment->ID, true);

			$atts = array(
				'autoplay' => 'true',
				'id' => $attachment->ID,
				'width' => $dimensions['width'],
				'height' => $dimensions['height']
			);
			if ( $kgvid_postmeta['downloadlink'] == "on" ) { $atts['downloadlink'] = "true"; }

			$popup_atts = kgvid_shortcode_atts($atts);
			if ( in_the_loop() ) { $post_id = get_the_ID(); }
			else { $post_id = 1; }
			$content = '';
			$popup_code = kgvid_single_video_code($popup_atts, $atts, $content, $post_id);
			preg_match('/data-kgvid_video_vars=".*?"/', $popup_code, $video_vars);
			$popup_code = str_replace(array("\r", "\n", "\t", $video_vars[0]), "", $popup_code);

			if ( $options['js_skin'] == "" ) { $options['js_skin'] = "vjs-default-skin"; }
			if ( is_array($query_atts) && array_key_exists('skin', $query_atts) ) {
				$options['js_skin'] = $query_atts['skin']; //allows user to set skin for individual videos using the skin="" attribute
			}

			$code .= '<div class="kgvid_video_gallery_thumb" onclick="kgvid_SetVideo(\'kgvid_'.strval($kgvid_video_id-1).'\')" id="kgvid_video_gallery_thumb_kgvid_'.strval($kgvid_video_id-1).'" data-id="kgvid_'.strval($kgvid_video_id-1).'" data-width="'.esc_attr($dimensions['width']).'" data-height="'.esc_attr($dimensions['height']).'" data-meta="'.esc_attr($below_video).'" data-gallery_end="'.esc_attr($query_atts['gallery_end']).'" data-popupcode="'.esc_attr($popup_code).'" '.$video_vars[0].'" style="width:'.$query_atts["gallery_thumb"].'px;';
			
			if ( $query_atts['gallery_thumb_aspect'] == "true" ) {
				$code .= ' height:'.round($options["height"]/$options["width"]*$query_atts["gallery_thumb"]).'px;';
			}

			$code .= '"><img ';
			if ( !empty($thumbnail_srcset) ) { 
				$code .= 'srcset="'.esc_attr($thumbnail_srcset).'"'; 
			}
			else { 
				$code .= 'src="'.esc_attr($thumbnail_url).'"'; 
			}
			$code .= 'alt="'.esc_attr($attachment->post_title).'">'.$play_button_html;

			if ( $query_atts['gallery_title'] == 'true' ) { $code .= '<div class="titlebackground"><div class="videotitle">'.$attachment->post_title.'</div></div>'; }

			$code .= '</div>'."\n\t\t\t";


		} //end attachment loop

		if ( $attachments->max_num_pages > 1 ) {

			$code .= '<div class="kgvid_gallery_pagination">';
			$code .= '<span class="kgvid_gallery_pagination_arrow"';
			if ( $page_number == 1 ) { $code .= ' style="visibility:hidden;"'; }
			$code .= ' onclick="kgvid_switch_gallery_page(jQuery(this).siblings(\'.kgvid_gallery_pagination_selected\').prev(), \'none\');"><a href="javascript:void(0)" title="'.__('Previous', 'video-embed-thumbnail-generator').'">&larr;</a></span> ';
			for ( $x = 1; $x <= $attachments->max_num_pages; $x++ ) {
				if ( $x == $page_number ) { $code .= '<span class="kgvid_gallery_pagination_selected">'.$x.'</span> '; }
				else { $code .= '<span onclick="kgvid_switch_gallery_page(this, \'none\');"><a href="javascript:void(0)">'.$x.'</a></span> '; }
			}
			$code .= '<span class="kgvid_gallery_pagination_arrow"';
			if ( $page_number == $attachments->max_num_pages ) { $code .= ' style="visibility:hidden;"'; }
			$code .= ' onclick="kgvid_switch_gallery_page(jQuery(this).siblings(\'.kgvid_gallery_pagination_selected\').next(), \'none\');"><a href="javascript:void(0)" title="'.__('Next', 'video-embed-thumbnail-generator').'">&rarr;</a></span>';
			$code .= '</div>';

		}

	} //if there are attachments

	return apply_filters('kgvid_gallery_page', $code, $kgvid_video_id);

}

function kgvid_switch_gallery_page() {

	check_ajax_referer( 'kgvid_frontend_nonce', 'security' );

	if ( isset($_POST['page']) ) { $page_number = $_POST['page']; }
	else { $page_number = 1; }
	$query_atts = $_POST['query_atts'];
	$last_video_id = $_POST['last_video_id'];
	$code = kgvid_gallery_page($page_number, $query_atts, $last_video_id);
	echo json_encode($code);
	die();

}
add_action( 'wp_ajax_kgvid_switch_gallery_page', 'kgvid_switch_gallery_page' ); // ajax for logged in users
add_action( 'wp_ajax_nopriv_kgvid_switch_gallery_page', 'kgvid_switch_gallery_page' ); // ajax for not logged in users

function kgvid_generate_video_description($query_atts, $post = false) {

	if ( array_key_exists('description', $query_atts) && !empty($query_atts['description']) && $query_atts['description'] != "false" ) {
		$description = $query_atts['description'];
	}
	elseif ( array_key_exists('description', $query_atts) && !empty($query_atts['caption']) && $query_atts['caption'] != "false" ) {
		$description = $query_atts['caption'];
	}
	elseif ( $post != false || ( in_the_loop() && !is_attachment() ) ) {

		if ( $post == false ) { global $post; }

		$yoast_meta = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true ); //try Yoast SEO meta description tag
		$aioseop_meta = get_post_meta( $post->ID, '_aioseop_description', true ); //try All in one SEO Pack meta description tag

		if ( !empty($yoast_meta) ) {
			$description = $yoast_meta;
		}
		elseif ( !empty($aioseop_meta) ) {
			$description = $aioseop_meta;
		}
		elseif ( !empty($post->post_excerpt) ) {
			$description = $post->post_excerpt;
		}
		else {
			$description = wp_trim_words(strip_tags(strip_shortcodes($post->post_content)));
		}
	}
	if ( empty($description) ) { $description = __('Video', 'video-embed-thumbnail-generator'); }

	return apply_filters('kgvid_generate_video_description', $description, $query_atts);

}

function kgvid_single_video_code($query_atts, $atts, $content, $post_id) {

	global $content_width;
	$content_width_save = $content_width;

	global $kgvid_video_id;
	if ( !$kgvid_video_id ) { $kgvid_video_id = 0; }

	global $wp_version;

	$options = kgvid_get_options();
	$code = "";
	$id_array = array();
	$video_formats = kgvid_video_formats(false, true, false);
	$compatible = array("flv", "f4v", "mp4", "mov", "m4v", "ogv", "ogg", "webm");
	$h264compatible = array("mp4", "mov", "m4v");

	if ( !empty($query_atts["id"]) ) {
		$id_array[0] = $query_atts["id"];
	}
	else { 

		if ( empty($content) ) {

			if ( $post_id != 0 ) {
				$args = array(
					'numberposts' => $query_atts['videos'],
					'post_mime_type' => 'video',
					'post_parent' => $post_id,
					'post_status' => null,
					'post_type' => 'attachment',
					'orderby' => $query_atts['orderby'],
					'order' => $query_atts['order']
				);
				$video_attachments = get_posts($args);
				if ( $video_attachments ) {
					foreach ( $video_attachments as $video ) {
						$id_array[] = $video->ID;
					}
				}
				else { return; } //if there are no video children of the current post
			}
			else { return; } //if there's no post ID and no $content
		}
		else { // $content is a URL
			// workaround for relative video URL (contributed by Lee Fernandes)
			if(substr($content, 0, 1) == '/') $content = get_bloginfo('url').$content;
			$content = apply_filters('kgvid_filter_url', trim($content));
			$id_array[0] = kgvid_url_to_id($content);
		}

	}

	$original_content = $content;

	foreach ( $id_array as $id ) { //loop through videos

		$div_suffix = 'kgvid_'.strval($kgvid_video_id);

		$query_atts = kgvid_shortcode_atts($atts); //reset values so they can be different with multiple videos
		$content = $original_content;
		$sources = array();
		$mp4already = false;
		$dimensions = array();

		if ( $query_atts['gallery'] == 'false' && $kgvid_video_id === 0 && $post_id != 0 ) {
			$first_embedded_video['atts'] = $atts;
			$first_embedded_video['content'] = $content;
			$first_embedded_video_meta = get_post_meta($post_id, '_kgvid_first_embedded_video', true);
			if ( $first_embedded_video_meta != $first_embedded_video ) {
				update_post_meta($post_id, '_kgvid_first_embedded_video', $first_embedded_video);
			}
		}

		if ( !empty($id) ) { //if the video is an attachment in the WordPress db

			$attachment_url = wp_get_attachment_url($id);
			if ( $attachment_url == false ) { _e("Invalid video ID", 'video-embed-thumbnail-generator'); continue; }

			if ( $options['rewrite_attachment_url'] == 'on' ) {

				$rewrite_url = true;

				//in case user doesn't know about this setting still check manually for popular CDNs like we used to
				$exempt_cdns = array(
					'amazonaws.com',
					'rackspace.com',
					'netdna-cdn.com',
					'nexcess-cdn.net',
					'limelight.com',
					'digitaloceanspaces.com'
				); //don't replace URLs that point to CDNs
				foreach ( $exempt_cdns as $exempt_cdn ) {
					if ( strpos($content, $exempt_cdn) !== false ) {
						$rewrite_url = false;
					}
				}

			}
			else {
				$rewrite_url = false;
			}
			if ( $rewrite_url || $content == '' ) { $content = $attachment_url; }

			$encodevideo_info = kgvid_encodevideo_info($content, $id);
			$attachment_info = get_post( $id );
			$kgvid_postmeta = kgvid_get_attachment_meta($id);

			$dimensions = kgvid_set_video_dimensions($id);

			if ( empty($atts['width']) ) {
				$query_atts['width'] = $dimensions['width'];
				$query_atts['height'] = $dimensions['height'];
			}

			$poster_id = get_post_meta($id, '_kgflashmediaplayer-poster-id', true);
			if ( !empty($poster_id) ) {
				$poster_image_src = wp_get_attachment_image_src($poster_id, 'full');
				$query_atts['poster'] = $poster_image_src[0];
				if ( strpos($query_atts['width'], '%') === false 
					&& $query_atts['resize'] == 'false'
					&& $query_atts['fullwidth'] == 'false'
					&& intval($query_atts['width']) <= get_option('medium_size_h')
				) {
					$query_atts['poster'] = kgvid_get_attachment_medium_url($poster_id);
				}
			}

			if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {

				if ( $query_atts['poster'] ) { $query_atts['poster'] = set_url_scheme($query_atts['poster']); }

			}

			if ( $query_atts['title'] == "true" ) {
				$query_atts['title'] = $attachment_info->post_title;
				$stats_title = $query_atts['title'];
			}
			else { $stats_title = $attachment_info->post_title; }
			if ( empty($query_atts['caption']) ) { $query_atts['caption'] = trim($attachment_info->post_excerpt); }
			if ( empty($query_atts['description']) ) { $query_atts['description'] = trim($attachment_info->post_content); }

			$countable = true;

		}
		else { //video is not in the database

			$encodevideo_info = kgvid_encodevideo_info($content, $post_id); //send the id of the post the video's embedded in
			if ( $query_atts['title'] == "true" ) {
				$query_atts['title'] = "false";
			}
			$stats_title = basename($content);
			if ( $query_atts['embedcode'] == "true" ) {
				$query_atts['embedcode'] = "false"; //can't use embed code with videos that are not in the database
			}

			$countable = false;
		}

		$mime_type_check = kgvid_url_mime_type($content, $post_id);
		if ( in_array($mime_type_check['ext'], $h264compatible) ) {
			$format_type = "h264";
			$mime_type = "video/mp4";
		}
		else {
			$format_type = $mime_type_check['ext'];
			$mime_type = $mime_type_check['type'];
		}

		unset($video_formats['fullres']);
		$video_formats = array('original' => array( "type" => $format_type, "mime" => $mime_type, "name" => "Full", "label" => _x("Full", 'Full resolution', 'video-embed-thumbnail-generator') ) ) + $video_formats;

		if ( in_array($mime_type_check['ext'], $compatible) ) {

			$encodevideo_info["original"]["exists"] = true;
			$encodevideo_info["original"]["url"] = $content;

			if ( is_array($dimensions) && array_key_exists('actualheight', $dimensions) && !empty($dimensions['actualheight']) ) {
				$video_formats['original']['label'] = $dimensions['actualheight'].'p';
				$video_formats['original']['height'] = $dimensions['actualheight'];
				$encodevideo_info["original"]["height"] = $dimensions['actualheight'];
			}

		}
		else { $encodevideo_info["original"]["exists"] = false; }

		if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {

			foreach ( $video_formats as $format => $format_stats ) {

				if ( array_key_exists($format, $encodevideo_info) && is_array($encodevideo_info[$format]) && array_key_exists('url', $encodevideo_info[$format]) ) {
					$encodevideo_info[$format]['url'] = set_url_scheme($encodevideo_info[$format]['url']);
				}

			}

		}

		if($query_atts["endofvideooverlaysame"] == "true") { $query_atts["endofvideooverlay"] = $query_atts["poster"]; }

		if ( $query_atts['inline'] == "true" ) {
			$aligncode = ' kgvid_wrapper_inline';
			if ( $query_atts['align'] == "left" ) { $aligncode .= ' kgvid_wrapper_inline_left'; }
			if ( $query_atts['align'] == "center" ) { $aligncode .= ' kgvid_wrapper_auto_left kgvid_wrapper_auto_right'; }
			if ( $query_atts['align'] == "right" ) { $aligncode .= ' kgvid_wrapper_inline_right'; }
		}
		else {
			if ( $query_atts['align'] == "left" ) { $aligncode = ''; }
			if ( $query_atts['align'] == "center" ) { $aligncode = ' kgvid_wrapper_auto_left kgvid_wrapper_auto_right'; }
			if ( $query_atts['align'] == "right" ) { $aligncode = ' kgvid_wrapper_auto_left'; }
		}

		if ( 
			( $query_atts['title'] != "false"
			|| $query_atts['embedcode'] != "false"
			|| $query_atts['downloadlink'] == "true"
			||  $options['twitter_button'] == 'on'
			||  $options['facebook_button'] == 'on' 
			)
			&& $options['embed_method'] != 'None'
		) { //generate content overlaid on video
			$kgvid_meta = true;
		}
		else { $kgvid_meta = false; }

		if ( $query_atts['width'] == "100%" ) {
			$query_atts['width'] = $options['width'];
			$query_atts['height'] = $options['height'];
			$query_atts['fullwidth'] = "true";
		}

		if ( ( $query_atts['fixed_aspect'] == 'vertical' && $query_atts['height'] > $query_atts['width'] )
			|| $query_atts['fixed_aspect'] == 'true'
		) {

			$default_aspect_ratio = intval($options['height']) / intval($options['width']);
			$query_atts['height'] = round($query_atts['width'] * $default_aspect_ratio);

		}

		if ( $query_atts['gifmode'] == "true" ) {
			$gifmode_atts = array(
				'muted' => 'true',
				'autoplay' => 'true',
				'loop' => 'true',
				'controls' => 'false',
				'title' => 'false',
				'embeddable' => 'false',
				'downloadlink' => 'false',
				'playsinline' => 'true'
			);

			$gifmode_atts = apply_filters('kgvid_gifmode_atts', $gifmode_atts);

			foreach ( $gifmode_atts as $gifmode_key => $gifmode_value ) {
				$query_atts[$gifmode_key] = $gifmode_value;
			}
		}

		$video_variables = array(
			'id' => $div_suffix,
			'attachment_id' => $id,
			'player_type' => $options['embed_method'],
			'width' => $query_atts['width'],
			'height' => $query_atts['height'],
			'fullwidth' => $query_atts['fullwidth'],
			'countable' => $countable,
			'count_views' => $query_atts['count_views'],
			'start' => $query_atts['start'],
			'autoplay' => $query_atts['autoplay'],
			'pauseothervideos' => $query_atts['pauseothervideos'],
			'set_volume' => $query_atts['volume'],
			'muted' => $query_atts['muted'],
			'meta' => $kgvid_meta,
			'endofvideooverlay' => $query_atts['endofvideooverlay'],
			'resize' => $query_atts['resize'],
			'auto_res' => $query_atts['auto_res'],
			'pixel_ratio' => $query_atts['pixel_ratio'],
			'right_click' => $query_atts['right_click'],
			'playback_rate' => $query_atts['playback_rate'],
			'title' => $stats_title
		);
		$video_variables = apply_filters('kgvid_video_variables', $video_variables, $query_atts, $encodevideo_info);

		if ( $options['embed_method'] == "Video.js" 
			|| $options['embed_method'] == "Video.js v7" 
			|| $options['embed_method'] == "None"
		) {

			$enable_resolutions_plugin = false;
			$x = 20;
			$h264_resolutions = array();

			foreach ($video_formats as $format => $format_stats) {
				if ( $format != "original" && $encodevideo_info[$format]["url"] == $content ) {
					continue; //don't double up on non-H.264 video sources
				}
				if ( $encodevideo_info[$format]["exists"] ) {

					if ( array_key_exists('height', $encodevideo_info[$format]) && $format_stats['type'] == 'h264' ) {
						$source_key = $encodevideo_info[$format]['height'];
						$format_stats['label'] = str_replace($format_stats['height'], $encodevideo_info[$format]['height'], $format_stats['label']);
					}
					else { $source_key = $x; }

					if ( strpos($encodevideo_info[$format]["url"], '?') === false )  { //if there isn't already a query string in this URL
						$encodevideo_info[$format]["url"] = $encodevideo_info[$format]["url"].'?id='.$kgvid_video_id;
					}

					$sources[$source_key] = "\t\t\t\t\t".'<source src="'.esc_attr($encodevideo_info[$format]["url"]).'" type="'.$format_stats["mime"].'"';
					if ( $format == 'vp9' ) { $sources[$source_key] .= ' codecs="vp9, vorbis"'; }
					if ( $format_stats['type'] == 'h264' ) {
						$sources[$source_key] .= ' data-res="'.$format_stats['label'].'"';
						if ( $mp4already ) { //there is more than one resolution available
							$enable_resolutions_plugin = true;
						}
						$mp4already = true;
						$h264_resolutions[] = $format_stats['label'];
					}
					else { $sources[$source_key] .= ' data-res="'.$format_stats['name'].'"'; }
					$sources[$source_key] .= '>'."\n";
				}
			$x--;
			}
			krsort($sources);
			natsort($h264_resolutions);

			$video_variables['nativecontrolsfortouch'] = $query_atts['nativecontrolsfortouch'];
			$video_variables['locale'] = kgvid_get_videojs_locale();

			if ( $enable_resolutions_plugin ) {
				$video_variables['enable_resolutions_plugin'] = "true";
				if ( wp_script_is('kgvid_video_embed', 'enqueued') ) { 
					wp_dequeue_script('kgvid_video_embed'); //ensure that the video-quality-selector script is loaded before kgvid_video_embed.js
				}
				wp_enqueue_script( 'video-quality-selector' );
				if ( $query_atts["auto_res"] == "highest" ) { $video_variables['default_res'] = end($h264_resolutions); }
				if ( $query_atts["auto_res"] == "lowest" ) { $video_variables['default_res'] = reset($h264_resolutions); }
				elseif ( in_array($query_atts["auto_res"], $h264_resolutions) ) { $video_variables['default_res'] = $query_atts["auto_res"]; }
				else { $video_variables['default_res'] = false; }

				$default_key = intval($video_variables['default_res']);

				if ( $video_variables['default_res'] && array_key_exists($default_key, $sources) ) {
					$default_source = $sources[$default_key];
					unset($sources[$default_key]);
					$sources = array($default_key => $default_source) + $sources;
				}

			}
			else { $video_variables['enable_resolutions_plugin'] = false; }

		} //if Video.js

		$code .= '<div id="kgvid_'.$div_suffix.'_wrapper" class="kgvid_wrapper';
		if ( $wp_version < 4.9 && $options['embed_method'] == "WordPress Default" ) { 
			$code .= ' kgvid_compat_mep'; 
		}
		$code .= $aligncode.'">'."\n\t\t\t";
		$code .= '<div id="video_'.$div_suffix.'_div" class="fitvidsignore kgvid_videodiv" data-id="'.$div_suffix.'" data-kgvid_video_vars="'.esc_attr(json_encode($video_variables)).'" ';
		if ( $query_atts["schema"] == "true" ) {
			$code .= 'itemprop="video" itemscope itemtype="https://schema.org/VideoObject">';
			if ( $query_atts["poster"] != '' ) { $code .= '<meta itemprop="thumbnailUrl" content="'.esc_attr($query_atts["poster"]).'" />'; }
			if ( !empty($id) && $query_atts['embeddable'] == "true" ) { $schema_embedURL = site_url('/')."?attachment_id=".$id."&amp;kgvid_video_embed[enable]=true"; }
			else { $schema_embedURL = $content; }
			$code .= '<meta itemprop="embedUrl" content="'.esc_attr($schema_embedURL).'" />';
			$code .= '<meta itemprop="contentUrl" content="'.$content.'" />';
			$code .= '<meta itemprop="name" content="'.esc_attr($stats_title).'" />';

			$description = kgvid_generate_video_description($query_atts);

			$code .= '<meta itemprop="description" content="'.esc_attr($description).'" />';

			if ( !empty($id) ) { $upload_date = get_the_date('c', $id); }
			elseif ( $post_id != 0 ) { $upload_date = get_the_date('c', $post_id); }
			else { $upload_date = current_time('c'); }
			$code .= '<meta itemprop="uploadDate" content="'.esc_attr($upload_date).'" />';
		}
		else { $code .= '>'; } //schema disabled

		$track_keys = array('kind', 'srclang', 'src', 'label', 'default');
		if ( !isset($kgvid_postmeta) || ( is_array($kgvid_postmeta) && !is_array($kgvid_postmeta['track']) ) ) {
			$kgvid_postmeta['track'] = array();
			$kgvid_postmeta['track'][0] = array ( 'kind' => '', 'srclang' => '', 'src' => '', 'label' => '',  'default' => '');
		}
		foreach ( $track_keys as $key ) {
			if ( empty($kgvid_postmeta['track'][0][$key]) ) { $kgvid_postmeta['track'][0][$key] = $query_atts['track_'.$key]; }
		}

		$track_code = "";
		if ( !empty($kgvid_postmeta['track'][0]['src']) ) {
			foreach ( $kgvid_postmeta['track'] as $track => $track_attribute ) {
				foreach ( $track_attribute as $attribute => $value ) {
					if ( empty($value) ) { $track_attribute[$attribute] = $query_atts['track_'.$attribute]; }
				}

				if ( $options['embed_method'] == "WordPress Default" && $track_attribute['kind'] == 'captions' ) { $track_attribute['kind'] = 'subtitles'; }
				$track_code .= "<track id='".$div_suffix."_text_".$track."' kind='".esc_attr($track_attribute['kind'])."' src='".esc_attr($track_attribute['src'])."' srclang='".esc_attr($track_attribute['srclang'])."' label='".esc_attr($track_attribute['label'])."' ".$track_attribute['default']." />";
			}
		}

		if ( $options['embed_method'] == "WordPress Default" ) {

			$enable_resolutions_plugin = false;
			$x = 20;
			$h264_resolutions = array();
			$attr = array();

			foreach ($video_formats as $format => $format_stats) {

				if ( $format != "original" && $encodevideo_info[$format]["url"] == $content ) { unset($sources['original']); }

				if ( $encodevideo_info[$format]["exists"] ) {

					if ( array_key_exists('height', $encodevideo_info[$format]) && $format_stats['type'] == 'h264' ) {
						$source_key = $encodevideo_info[$format]['height'];
						$format_stats['label'] = $encodevideo_info[$format]['height'].'p';
					}
					else { $source_key = $x; }

					$sources[$source_key] = '<source src="'.esc_attr($encodevideo_info[$format]["url"]).'?id='.$kgvid_video_id.'" type="'.$format_stats["mime"].'"';
					if ( $format == 'vp9' ) { $sources[$source_key] .= ' codecs="vp9, vorbis"'; }
					if ( $format_stats['type'] == 'h264' ) {
						$sources[$source_key] .= ' data-res="'.$format_stats['label'].'"';
						if ( $mp4already ) { //there is more than one resolution available
							$enable_resolutions_plugin = true;
						}
						$h264_resolutions[] = $format_stats['label'];
					}
					else { $sources[$source_key] .= ' data-res="'.$format_stats['name'].'"'; }

					if ( $format_stats['type'] != "h264" || !$mp4already ) { //build wp_video_shortcode attributes. Sources will be replaced later
						$shortcode_type = kgvid_url_mime_type($encodevideo_info[$format]["url"], $post_id);
						$attr[$shortcode_type['ext']] = $encodevideo_info[$format]["url"];
						if ( $format_stats['type'] == "h264" ) {
							$mp4already = true;
						}
					}
				}
			$x--;
			}
			krsort($sources);
			natsort($h264_resolutions);

			if ( $enable_resolutions_plugin ) {

				$default_key = false;

				if ( $query_atts["auto_res"] == "highest" ) {
					$res_label = end($h264_resolutions);
				}
				elseif ( $query_atts["auto_res"] == "lowest" ) {
					$res_label = reset($h264_resolutions);
				}
				elseif ( in_array($query_atts["auto_res"], $h264_resolutions) ) {
					$res_label = $query_atts["auto_res"];
				}
				else { $res_label = false; }

				foreach ( $sources as $key => $source ) {
					if ( strpos($source, 'data-res="'.$res_label.'"') !== false ) { $default_key = $key; }
				}

				if ( $default_key !== false )  {
					$sources[$default_key] .= ' data-default_res="true"';
				}

			}

			if ( $query_atts["poster"] != '' ) { $attr['poster'] = esc_attr($query_atts["poster"]); }
			if ( $query_atts["loop"] == 'true') { $attr['loop'] = "true"; }
			if ( $query_atts["autoplay"] == 'true') { $attr['autoplay'] = "true"; }
			$attr['preload'] = $query_atts['preload'];
			$attr['width'] = $query_atts['width'];
			$attr['height'] = $query_atts['height'];

			$localize = false;

			$wpmejssettings = array(
				'features' => array( 'playpause', 'progress', 'volume', 'tracks' ),
				'classPrefix' => 'mejs-',
				'stretching' => 'responsive',
				'pluginPath' => includes_url( 'js/mediaelement/', 'relative' ),
				'success' => 'kgvid_mejs_success'
			);

			if ( $enable_resolutions_plugin && !wp_script_is('mejs_sourcechooser', 'enqueued') ) {
				wp_enqueue_script( 'mejs_sourcechooser' );
				array_push($wpmejssettings['features'], 'sourcechooser');
				$localize = true;
			}

			if ( $kgvid_video_id === 0 ) {
				$localize = true;
			}

			if ( $query_atts['playback_rate'] == 'true' ) {
				array_push($wpmejssettings['features'], 'speed');
				$wpmejssettings['speeds'] = array('0.5', '1', '1.25', '1.5', '2');
				wp_enqueue_script( 'mejs-speed' );
			}

			array_push($wpmejssettings['features'], 'fullscreen');

			if ( $localize ) {
				wp_localize_script( 'wp-mediaelement', '_wpmejsSettings', $wpmejssettings );
			}

			$content_width = $query_atts['width'];
			if ( function_exists('wp_video_shortcode') ) { $executed_shortcode = wp_video_shortcode($attr); }
			else { $executed_shortcode = 'WordPress video shortcode function does not exist.'; }
			$content_width = $content_width_save;
			if ( $enable_resolutions_plugin ) {
				$executed_shortcode = preg_replace( '/<source .*<a /', implode(' />', $sources).' /><a ', $executed_shortcode );
			}
			if ( !empty($track_code) ) {
				$executed_shortcode = preg_replace( '/<a /', $track_code.'<a ', $executed_shortcode );
			}

			$code .= $executed_shortcode;
		}

		if ( $options['embed_method'] == "Video.js" 
			|| $options['embed_method'] == "Video.js v7" 
			|| $options['embed_method'] == "None" 
		) {

			$code .= "\n\t\t\t\t".'<video id="video_'.$div_suffix.'" ';
			if ( $query_atts["playsinline"] == 'true' ) { $code .= 'playsinline '; }
			if ( $query_atts["loop"] == 'true') { $code .= 'loop '; }
			if ( $query_atts["autoplay"] == 'true') { $code .= 'autoplay '; }
			if ( $query_atts["controls"] != 'false') { $code .= 'controls '; }
			if ( $query_atts["muted"] == 'true' ) { $code .= 'muted '; }
			$code .= 'preload="'.$query_atts['preload'].'" ';
			if ( $query_atts["poster"] != '' ) { $code .= 'poster="'.esc_attr($query_atts["poster"]).'" '; }
			if ( $options['embed_method'] != "None" ) {
				$code .= 'width="'.$query_atts["width"].'" height="'.esc_attr($query_atts["height"]).'"';
			}
			else {
				$code .= 'width="100%"';
			}
			
			if (  $options['embed_method'] != "None" ) {
				if ( $options['js_skin'] == "" ) { $options['js_skin'] = "vjs-default-skin"; }
				if ( is_array($atts) && array_key_exists('skin', $atts) ) {
					$options['js_skin'] = $atts['skin']; //allows user to set skin for individual videos using the skin="" attribute
				}
				$code .= ' class="fitvidsignore '.esc_attr('video-js '.$options['js_skin']).'">'."\n";
			}
			else {
				$code .= ' class="fitvidsignore">'."\n";
			}

			$code .= implode("", $sources); //add the <source> tags created earlier
			$code .= $track_code; //if there's a text track
			$code .= "\t\t\t\t</video>\n";

		}
		$code .= "\t\t\t</div>\n";
		$show_views = false;
		if ( ( !empty($id) && $query_atts['view_count'] == "true" ) || !empty($query_atts['caption']) || $content == plugins_url('/images/sample-video-h264.mp4', __FILE__) ) { //generate content below the video
			if ( is_array($kgvid_postmeta) && array_key_exists('starts', $kgvid_postmeta) ) {
				$view_count = number_format(intval($kgvid_postmeta['starts']));
			}
			else {
				$view_count = "0";
				$kgvid_postmeta['starts'] = 0;
			}
			if ( $content == plugins_url('/images/sample-video-h264.mp4', __FILE__) ) { $view_count = "XX"; }
			if ( $query_atts['view_count'] == "true" ) { $show_views = true; }
			if ( !empty($query_atts['caption']) || $show_views || $query_atts['downloadlink'] == "true" ) {
				$code .= "\t\t\t".'<div class="kgvid_below_video" id="video_'.$div_suffix.'_below">';
				if ( $show_views ) { $code .= '<div class="kgvid-viewcount" id="video_'.$div_suffix.'_viewcount">'.sprintf( _n( '%s view', '%s views', intval($kgvid_postmeta['starts']), 'video-embed-thumbnail-generator'), $view_count ).'</div>'; }
				if ( !empty($query_atts['caption']) ) {
					$code .= '<div class="kgvid-caption" id="video_'.$div_suffix.'_caption">'.$query_atts['caption'].'</div>';
				}
				$code .= '</div>';
			}
		}

		if ( $kgvid_meta == true ) { //generate content overlaid on video
			$code .= "\t\t\t<div style=\"display:none;\" id=\"video_".$div_suffix."_meta\" class=\"kgvid_video_meta kgvid_video_meta_hover ";
			if ( $query_atts['title'] != "false" ) {
				$show_title = true;
				$code .= "\">";
			}
			else {
				$show_title = false;
				$code .= "kgvid_no_title_meta\">";
			} //no title

			$code .= "\n\t\t\t\t<span class='kgvid_meta_icons'>";

			if ( $query_atts['downloadlink'] == "true" ) {
				$forceable = false;
				if ( !empty($id) && $options['click_download'] == 'on' ) {
					$filepath = get_attached_file($id);
					if ( file_exists($filepath) ) {
						$forceable = true;
						$download_code = '<a href="'.site_url('/').'?attachment_id='.$id.'&kgvid_video_embed[download]=true" title="'.__('Click to download', 'video-embed-thumbnail-generator').'">';
					}
				}
				if ( !$forceable ) { $download_code = '<a href="'.$content.'" title="'.__('Right-click or ctrl-click to download', 'video-embed-thumbnail-generator').'">'; }
				$download_code .= '<span class="kgvid-icons kgvid-icon-download"></span></a>';
			}
			else { $download_code = ''; }

			if ( $query_atts['embeddable'] == 'true'
				&& ( $query_atts['embedcode'] != "false" 
					|| $options['twitter_button'] == 'on' 
					|| $options['facebook_button'] == 'on' 
				)
			) {

				$embed_code = "\t\t\t\t<span id='kgvid_".$div_suffix."_shareicon' class='vjs-icon-share' onclick='kgvid_share_icon_click(\"".$div_suffix."\");'></span>\n";
				$embed_code .= "\t\t\t\t<div id='click_trap_".$div_suffix."' class='kgvid_click_trap'></div><div id='video_".$div_suffix."_embed' class='kgvid_share_container";
				if ( $show_title == false ) { $embed_code .= " kgvid_no_title_meta"; }
				$embed_code .= "'><div class='kgvid_share_icons'>";
				if ( $query_atts['embedcode'] != "false" ) {
					if ( $query_atts['embedcode'] == "true" ) { $iframeurl = site_url('/')."?attachment_id=".$id."&amp;videopack[enable]=true"; }
					else { $iframeurl = $query_atts['embedcode']; }
					$iframecode = "<iframe src='".$iframeurl."' frameborder='0' scrolling='no' width='".esc_attr($query_atts['width'])."' height='".esc_attr($query_atts["height"])." allowfullscreen allow='autoplay; fullscreen'></iframe>";
					$iframecode = apply_filters('kgvid_embedcode', $iframecode, $iframeurl, $id, $query_atts);
					$embed_code .= "<span class='kgvid_embedcode_container'><span class='kgvid-icons kgvid-icon-embed'></span>
					<span>"._x('Embed:', 'precedes code for embedding video', 'video-embed-thumbnail-generator')." </span><span><input class='kgvid_embedcode' type='text' value='".esc_attr($iframecode)."' onClick='this.select();'></span> <span class='kgvid_start_time'><input type='checkbox' class='kgvid_start_at_enable' onclick='kgvid_set_start_at(\"".$div_suffix."\")'> ".__('Start at:', 'video-embed-thumbnail-generator')." <input type='text' class='kgvid_start_at' onkeyup='kgvid_change_start_at(\"".$div_suffix."\")'></span></span>";
				} //embed code

				if ( $options['twitter_button'] == 'on' || $options['facebook_button'] == 'on' ) {

					$embed_code .= "<div class='kgvid_social_icons'>";
					if ( in_the_loop() ) { $permalink = get_permalink(); }
					elseif ( !empty($id) ) { $permalink = get_attachment_link($id); }
					else { $permalink = $content; }

					if ( $options['twitter_button'] == 'on' ) {
						$embed_code .= "<a title='".__('Share on Twitter', 'video-embed-thumbnail-generator')."' href='https://twitter.com/share?text=".urlencode($query_atts['title'])."&url=".urlencode($permalink);
						if ( !empty($options['twitter_username']) ) { $embed_code .= "&via=".urlencode($options['twitter_username']); }
						$embed_code .= "' onclick='window.open(this.href, \"\", \"menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=260,width=600\");return false;'><span class='vjs-icon-twitter'></span></a>";
					}

					if ( $options['facebook_button'] == 'on' ) {
						$embed_code .= "&nbsp;<a title='".__('Share on Facebook', 'video-embed-thumbnail-generator')."' href='https://www.facebook.com/sharer/sharer.php?u=".urlencode($permalink)."' onclick='window.open(this.href, \"\", \"menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=260,width=600\");return false;'><span class='vjs-icon-facebook'></span></a>";
					}

					$embed_code .= "</div>";

				}

				$embed_code .= "</div></div>\n";
			}
			else { $embed_code = ''; }

			$code .= $embed_code.$download_code;

			$code .= "</span>";
			if ( $show_title == true ) { $code .= "\n\t\t\t\t<span id='video_".$div_suffix."_title' class='kgvid_title'>".$query_atts['title']."</span>\n"; }
			$code .= "</div>\n";
		}

		if ( !empty($query_atts["watermark"]) 
			&& $query_atts["watermark"] != "false" 
			&& $options['embed_method'] != "None"
		) {
			$watermark_id = kgvid_url_to_id($query_atts["watermark"]);
			if ( $watermark_id ) { $query_atts["watermark"] = wp_get_attachment_url($watermark_id); }
			if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				if ( $query_atts["watermark"] ) { $query_atts["watermark"] = set_url_scheme($query_atts["watermark"]); }
			}
			$code .= "<div style=\"display:none;\" id='video_".$div_suffix."_watermark' class='kgvid_watermark'>";
			if ( !empty($query_atts["watermark_url"]) && $query_atts["watermark_link_to"] != 'custom' ) { $query_atts["watermark_link_to"] = 'custom'; }
			if ( $query_atts['watermark_link_to'] != 'false' && $query_atts["watermark_url"] != 'false' ) {
				$watermark_link = true;
				switch ( $query_atts['watermark_link_to'] ) {

					case 'home':
						$watermark_href = get_home_url();
					break;

					case 'parent':
						if ( !empty($id) && is_object($attachment_info) && array_key_exists('post_parent', $attachment_info) && !empty($attachment_info->post_parent) ) {
							$watermark_href = get_permalink($attachment_info->post_parent);
						}
						else { $watermark_href = get_home_url(); }
					break;

					case 'attachment':
						if ( !empty($id) ) {
							$watermark_href = get_permalink($id);
						}
						else { $watermark_href = get_home_url(); }
					break;

					case 'download':
						if ( !empty($id) ) {
							$watermark_href = site_url('/').'?attachment_id='.$id.'&kgvid_video_embed[download]=true';
						}
						else { $watermark_href = $content; }
					break;

					case 'custom':
					$watermark_href = $query_atts["watermark_url"];
					break;

				}
				$code .= "<a target='_parent' href='".$watermark_href."'>";
			}
			else { $watermark_link = false; }
			$code .= "<img src='".esc_attr($query_atts["watermark"])."' alt='watermark'>";
			if ( $watermark_link ) { $code .= "</a>"; }
			$code .= "</div>";
		} //generate watermark
		$code .= "\t\t</div>"; //end kgvid_XXXX_wrapper div

		$kgvid_video_id++;

	} //end id_array loop

	return apply_filters('kgvid_single_video_code', $code, $query_atts, $atts, $content, $post_id);

}

function kgvid_overwrite_shortcode() {

	$options = kgvid_get_options();
	if ($options['replace_video_shortcode'] == 'on') {
		remove_shortcode('video');
		add_shortcode('video', 'kgvid_replace_video_shortcode');
	}

}
add_action('wp_loaded', 'kgvid_overwrite_shortcode');

function kgvid_replace_video_shortcode( $atts, $content = '' ) {

	$src_atts = array('src', 'mp4', 'm4v', 'webm', 'ogv', 'wmv', 'flv');
	foreach ( $src_atts as $src_key ) {
		if ( is_array($atts) && array_key_exists($src_key, $atts) ) {
			$content = $atts[$src_key];
			break;
		}
	}

	return KGVID_shortcode($atts, $content);

}

function kgvid_shortcode_atts($atts) {

	$options = kgvid_get_options();

	if ( in_the_loop() ) { $post_id = get_the_ID(); }
	else { $post_id = 1; }

	$deprecated_atts = array(
		'controlbar' => 'controls',
		'mute' => 'muted'
	);

	if ( is_array($atts) ) {

		foreach( $deprecated_atts as $deprecated_att => $new_att ) { //loop through old atts and convert to new ones

			if ( array_key_exists($deprecated_att, $atts) ) {

				$atts[$new_att] = $atts[$deprecated_att];

				if ( $new_att == 'controls' ) {

					if ( $atts['controls'] == 'none' ) {
						$atts['controls'] = 'false';
					}
					else {
						$atts['controls'] = 'true';
					}

				}

			}

		}

	}

	$default_atts = array(
		'id' => '',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'videos' => -1,
		'width' => $options['width'],
		'height' => $options['height'],
		'fullwidth' => $options['fullwidth'],
		'fixed_aspect' => $options['fixed_aspect'],
		'align' => $options['align'],
		'controls' => $options['controls'],
		'poster' => $options['poster'],
		'start' => '',
		'preload' => $options['preload'],
		'watermark' => $options['watermark'],
		'watermark_link_to' => $options['watermark_link_to'],
		'watermark_url' => $options['watermark_url'],
		'endofvideooverlay' => $options['endofvideooverlay'],
		'endofvideooverlaysame' => $options['endofvideooverlaysame'],
		'loop' => $options['loop'],
		'autoplay' => $options['autoplay'],
		'gifmode' => $options['gifmode'],
		'pauseothervideos' => $options['pauseothervideos'],
		'playsinline' => $options['playsinline'],
		'skin' => $options['js_skin'],
		'gallery' => 'false',
		'gallery_per_page' => $options['gallery_per_page'],
		'gallery_thumb' => $options['gallery_thumb'],
		'gallery_thumb_aspect' => $options['gallery_thumb_aspect'],
		'gallery_orderby' => 'menu_order ID',
		'gallery_order' => 'ASC',
		'gallery_exclude' => '',
		'gallery_include' => '',
		'gallery_id' => $post_id,
		'gallery_end' => $options['gallery_end'],
		'gallery_title' => $options['gallery_title'],
		'volume' => $options['volume'],
		'muted' => $options['muted'],
		'preload' => $options['preload'],
		'playback_rate' => $options['playback_rate'],
		'title' => $options['overlay_title'],
		'embedcode' => $options['overlay_embedcode'],
		'embeddable' => $options['embeddable'],
		'view_count' => $options['view_count'],
		'count_views' => $options['count_views'],
		'caption' => '',
		'description' => '',
		'inline' => $options['inline'],
		'downloadlink' => $options['downloadlink'],
		'right_click' => $options['right_click'],
		'resize' => $options['resize'],
		'auto_res' => $options['auto_res'],
		'pixel_ratio' => $options['pixel_ratio'],
		'nativecontrolsfortouch' => $options['nativecontrolsfortouch'],
		'schema' => $options['schema'],
		'track_kind' => 'subtitles',
		'track_srclang' => substr(get_bloginfo('language'), 0, 2),
		'track_src' => '',
		'track_label' => get_bloginfo('language'),
		'track_default' => ''
	);

	$custom_atts_return = array();
	if ( !empty($options['custom_attributes']) ) {
		preg_match_all('/(\w+)\s*=\s*(["\'])((?:(?!\2).)*)\2/', $options['custom_attributes'], $custom_atts, PREG_SET_ORDER);
		if ( !empty($custom_atts) && is_array($custom_atts) ) {
			foreach ( $custom_atts as $custom_att ) {
				if ( array_key_exists($custom_att[1], $default_atts) ) {
					$default_atts[$custom_att[1]] = $custom_att[3];
				}
				else { $default_atts['custom_atts'][$custom_att[1]] = $custom_att[3]; }
			}
		}
	}

	$default_atts = apply_filters('kgvid_default_shortcode_atts', $default_atts);

	$query_atts = shortcode_atts($default_atts, $atts, 'videopack');

	$kgvid_video_embed_query_var = get_query_var('videopack'); //variables in URL
	if ( empty($kgvid_video_embed_query_var) ) {
		$kgvid_video_embed_query_var = get_query_var('kgvid_video_embed'); //check the old query variable
	}

	if ( !empty($kgvid_video_embed_query_var) ) {

		$allowed_query_var_atts = array( //attributes that can be changed via URL
			'auto_res',
			'autoplay',
			'controls',
			'default_res',
			'fullwidth',
			'gifmode',
			'height',
			'loop',
			'muted',
			'nativecontrolsfortouch',
			'pixel_ratio',
			'resize',
			'set_volume',
			'start',
			'width',
		);

		$allowed_query_var_atts = apply_filters('kgvid_allowed_query_var_atts', $allowed_query_var_atts);

		foreach ( $kgvid_video_embed_query_var as $key => $value ) {
			if ( in_array($key, $allowed_query_var_atts) ) {
				$query_atts[$key] = $value;
			}
		}

	}

	$checkbox_convert = array (
		"endofvideooverlaysame",
		"loop",
		"playsinline",
		"autoplay",
		"controls",
		"pauseothervideos",
		"title",
		"embedcode",
		"embeddable",
		"view_count",
		"inline",
		"resize",
		"downloadlink",
		"muted",
		"playback_rate",
		"fullwidth",
		"gallery_thumb_aspect",
		"gallery_title",
		"nativecontrolsfortouch",
		"pixel_ratio",
		"schema",
		"gifmode",
	);
	foreach ( $checkbox_convert as $query ) {
		if ( $query_atts[$query] == "on" ) { $query_atts[$query] = "true"; }
		if ( $query_atts[$query] == false ) { $query_atts[$query] = "false"; }
	}

	if ( $query_atts['auto_res'] == 'true' ) { $query_atts['auto_res'] = 'automatic'; } //if anyone used auto_res in the shortcode before version 4.4.3
	if ( $query_atts['auto_res'] == 'false' ) { $query_atts['auto_res'] = 'highest'; }
	if ( $query_atts['orderby'] == 'menu_order' ) { $query_atts['orderby'] = 'menu_order ID'; }
	if ( $query_atts['track_default'] == 'true' ) { $query_atts['track_default'] = 'default'; }
	if ( $query_atts['count_views'] == 'false' ) { $query_atts['view_count'] = 'false'; }

	return apply_filters('kgvid_shortcode_atts', $query_atts);

}

function KGVID_shortcode($atts, $content = '') {

	$code = "";
	$query_atts = "";

	if ( !is_feed() ) {

		$options = kgvid_get_options();
		if ( $options['embed_method'] != 'Video.js' && $options['embed_method'] != 'Video.js v7' ) { kgvid_enqueue_shortcode_scripts(); }

		if ( in_the_loop() ) {
			$post_id = get_the_ID();
		}
		else {
			global $wp_query;
    		$post_id = $wp_query->get_queried_object_id();
		}

		$query_atts = kgvid_shortcode_atts($atts);

		if ( $query_atts["gallery"] != "true" ) { //if this is not a pop-up gallery

			$code = kgvid_single_video_code($query_atts, $atts, $content, $post_id);

		} //if not gallery

		else { //if gallery

			static $kgvid_gallery_id = 0;
			$gallery_query_index = array(
				'gallery_orderby',
				'gallery_order',
				'gallery_id',
				'gallery_include',
				'gallery_exclude',
				'gallery_thumb',
				'gallery_thumb_aspect',
				'view_count',
				'gallery_end',
				'gallery_per_page',
				'gallery_title'
			);
			$gallery_query_atts = array();
			foreach($gallery_query_index as $index) { $gallery_query_atts[$index] = $query_atts[$index]; };

			if ( $gallery_query_atts['gallery_orderby'] == 'rand' ) {
				$gallery_query_atts['gallery_orderby'] = 'RAND('.rand().')'; //use the same seed on every page load
			}

			wp_enqueue_script( 'simplemodal' );

			if ( $query_atts['align'] == "left" ) { $aligncode = ' kgvid_textalign_left'; }
			if ( $query_atts['align'] == "center" ) { $aligncode = ' kgvid_textalign_center'; }
			if ( $query_atts['align'] == "right" ) { $aligncode = ' kgvid_textalign_right'; }

			$code .= '<div class="kgvid_gallerywrapper'.$aligncode.'" id="kgvid_gallery_'.$kgvid_gallery_id.'" data-query_atts="'.esc_attr(json_encode($gallery_query_atts)).'">';
			$code .= kgvid_gallery_page(1, $gallery_query_atts);
			$code .= '</div>'; //end wrapper div

			$kgvid_gallery_id++;

		} //if gallery

		if ( $options['embed_method'] == 'Video.js' || $options['embed_method'] == 'Video.js v7' ) { kgvid_enqueue_shortcode_scripts(); }

	} //if not feed

	return apply_filters('KGVID_shortcode', $code, $query_atts, $content);

}
add_shortcode('FMP', 'KGVID_shortcode');
add_shortcode('KGVID', 'KGVID_shortcode');
add_shortcode('videopack', 'KGVID_shortcode');
add_shortcode('VIDEOPACK', 'KGVID_shortcode');


function kgvid_no_texturize_shortcode($shortcodes){
    $shortcodes[] = 'KGVID';
    $shortcodes[] = 'FMP';
	$shortcodes[] = 'videopack';
	$shortcodes[] = 'VIDEOPACK';
    return $shortcodes;
}
add_filter( 'no_texturize_shortcodes', 'kgvid_no_texturize_shortcode' );

function kgvid_update_child_format() {

	check_ajax_referer( 'video-embed-thumbnail-generator-nonce', 'security' );
	$video_id = $_POST['video_id'];
	$parent_id = $_POST['parent_id'];
	$format = $_POST['format'];
	if ( isset($_POST['blog_id']) ) { $blog_id = $_POST['blog_id']; }
	else { $blog_id = false; }

	if ( !empty($blog_id) && $blog_id != 'false' ) { switch_to_blog($blog_id); }

	$video_encode_queue = kgvid_get_encode_queue();

	if ( $video_encode_queue ) {

		foreach ( $video_encode_queue as $video_key => $video_entry ) {
			if ( !empty($video_entry['attachmentID']) && $video_entry['attachmentID'] == $parent_id
				&& ( array_key_exists('blog_id', $video_entry) && $video_entry['blog_id'] == $blog_id  || $blog_id == 'false' )
				&& array_key_exists('encode_formats', $video_entry)
				&& array_key_exists($format, $video_entry['encode_formats'])
				&& array_key_exists('status', $video_entry['encode_formats'][$format])
			) {
				$video_encode_queue[$video_key]['encode_formats'][$format]['status'] = 'notchecked';
				kgvid_save_encode_queue($video_encode_queue);
				break;
			}
		}

	}

	$post = get_post($video_id);
	update_post_meta( $video_id, '_kgflashmediaplayer-format', $format );
	update_post_meta( $video_id, '_kgflashmediaplayer-pickedformat', $post->post_parent ); //save the original parent
	$post->post_parent = $parent_id;
	wp_update_post($post);

	if ( !empty($blog_id) && $blog_id != 'false' ) { restore_current_blog(); }

	die();

}
add_action('wp_ajax_kgvid_update_child_format', 'kgvid_update_child_format');

function kgvid_clear_child_format() {

	check_ajax_referer( 'video-embed-thumbnail-generator-nonce', 'security' );
	$video_id = $_POST['video_id'];
	if ( isset($_POST['blog_id']) ) { $blog_id = $_POST['blog_id']; }
	else { $blog_id = false; }

	if ( $blog_id ) { switch_to_blog($blog_id); }

	delete_post_meta( $video_id, '_kgflashmediaplayer-format' );
	$old_parent = get_post_meta( $video_id, '_kgflashmediaplayer-pickedformat', true );
	delete_post_meta( $video_id, '_kgflashmediaplayer-pickedformat' );
	$post = get_post($video_id);
	if ( is_string( get_post_status( $old_parent ) ) ) { $post->post_parent = $old_parent; }
	wp_update_post($post);

	if ( $blog_id ) { restore_current_blog(); }

	die();

}
add_action('wp_ajax_kgvid_clear_child_format', 'kgvid_clear_child_format');

function kgvid_update_encode_queue() {

	check_ajax_referer( 'video-embed-thumbnail-generator-nonce', 'security' );

	if ( isset( $_POST['page'] ) ) { $page = $_POST['page']; }
	else { die(); }

	$options = kgvid_get_options();
	$video_encode_queue = kgvid_get_encode_queue();

	if ( !empty($video_encode_queue) ) {

		foreach ( $video_encode_queue as $video_key => $video_entry ) {

			if ( $page == 'attachment' && array_key_exists('blog_id', $video_entry) && get_current_blog_id() != $video_entry['blog_id'] ) { //remove all entries from other blogs on attachment pages
				unset($video_encode_queue[$video_key]);
				continue;
			}

			if ( !empty($video_entry['movieurl']) && !empty($video_entry['attachmentID']) ) {
				$encodevideo_info = kgvid_encodevideo_info($video_entry['movieurl'], $video_entry['attachmentID']);
			}

			foreach ( $video_entry['encode_formats'] as $format => $value ) {

				if ( !array_key_exists('lastline', $value) ) { $value['lastline'] = ''; }

				$video_encode_queue[$video_key]['encode_formats'][$format]['meta_array'] = kgvid_encode_format_meta($encodevideo_info, $video_key, $format, $value['status'], $value['lastline'], $video_entry['attachmentID'], $video_entry['movieurl'], $page);

			}

		}

	}//if there's a queue

	$arr = array( 'queue' => $video_encode_queue, 'queue_control' => $options['queue_control'] );

	echo json_encode($arr);

	die();

}
add_action('wp_ajax_kgvid_update_encode_queue', 'kgvid_update_encode_queue');

function kgvid_encode_format_meta( $encodevideo_info, $video_key, $format, $status, $lastline, $post_id, $movieurl, $page ) {

	$options = kgvid_get_options();

	$encodeset = "false";
	$checked = "";
	$meta = "";
	$disabled = "";
	$child_id = "";
	$something_to_encode = false;
	$encoding_now = false;
	$time_to_wait = 5000;
	$user_delete_capability = false;

	if ( is_multisite() ) { $blog_id = get_current_blog_id(); }
	else { $blog_id = false; }

	if ( get_post_type($post_id) == "attachment" ) {
		$kgvid_postmeta = kgvid_get_attachment_meta($post_id);
		if ( array_key_exists('encode', $kgvid_postmeta) 
			&& is_array($kgvid_postmeta['encode'])
			&& array_key_exists($format, $kgvid_postmeta['encode']) 
		) { 
			$encodeset = $kgvid_postmeta['encode'][$format]; 
		}
		else { $encodeset = 'false'; }
		$post = get_post($post_id);
		$current_user = wp_get_current_user();
		if ( $post && ( $current_user->ID == $post->post_author )
			|| ( current_user_can('edit_others_video_encodes') )
		) {
			$user_delete_capability = true;
		}

	}
	if ( $encodeset == "false" && strpos($format, 'custom_') === false ) { 
		if ( is_array( $options['encode'])
			&& array_key_exists($format, $options['encode'])
		) { 
			$encodeset = "on"; 
		}
		else { $encodeset = false; }
	}

	if ( $encodeset == "on" || $status == "queued" ) { $checked = 'checked'; }

	if ( $status != "notchecked" ) { //File is in queue
		$meta = ' <strong>'.ucfirst($status).'</strong>';
		if ( $status == "error" && !empty($lastline) ) {
			$meta .= ': <span class="kgvid_warning">'.stripslashes($lastline)."</span>";
		}
	}

	if ( !empty($encodevideo_info) ) {

		if ( array_key_exists($format, $encodevideo_info) && $encodevideo_info[$format]['exists'] ) { //if the video file exists

			if ( array_key_exists('id', $encodevideo_info[$format]) ) {
				$child_id = $encodevideo_info[$format]['id'];
				$was_picked = get_post_meta( $child_id, '_kgflashmediaplayer-pickedformat', true );
			}
			else { $was_picked = false; }

			if ( $status != "encoding" ) { // not currently encoding

				if ( $status == "notchecked" ) {
					if ( $was_picked != false ) { $meta = ' <strong>'.__('Set:', 'video-embed-thumbnail-generator').' '.basename($encodevideo_info[$format]['filepath']).'</strong>'; }
					else { $meta = ' <strong>'.__('Encoded', 'video-embed-thumbnail-generator').'</strong>'; }
				}
				if ( $status != "canceling" ) {

					if ( $encodevideo_info[$format]['writable']
					&& current_user_can('encode_videos')
					&& $user_delete_capability == true
					&& $format != "fullres" ) {
						if ( $was_picked != false ) {
							$meta .= '<a id="unpick-'.$post_id.'-'.$format.'" class="kgvid_delete-format" onclick="kgvid_clear_video(\''.$movieurl.'\', \''.$post_id.'\', \''.$child_id.'\', \''.$blog_id.'\');" href="javascript:void(0)">'.__('Clear Format', 'video-embed-thumbnail-generator').'</a>';
						}
						else {
							$meta .= '<a id="delete-'.$post_id.'-'.$format.'" class="kgvid_delete-format" onclick="kgvid_delete_video(\''.$movieurl.'\', \''.$post_id.'\', \''.$format.'\', \''.$child_id.'\', \''.$blog_id.'\');" href="javascript:void(0)">'.__('Delete Permanently', 'video-embed-thumbnail-generator').'</a>';
						}
					}
				}
				$disabled = ' disabled title="'.__('Format already exists', 'video-embed-thumbnail-generator').'"';
				$checked = '';
			}
		}
		else {

			$something_to_encode = true;

		} //if the video file doesn't exist, there's something to encode
	}

	if ( $status == "encoding" ) {
		$encoding_now = true;
		$disabled = ' disabled title="'.__('Currently encoding', 'video-embed-thumbnail-generator').'"';
		$checked = 'checked';
		$progress = kgvid_encode_progress();
		if ( is_array($progress) 
			&& array_key_exists($video_key, $progress)
			&& array_key_exists($format, $progress[$video_key])
			&& array_key_exists('embed_display', $progress[$video_key][$format])
		) {
			$meta = $progress[$video_key][$format]['embed_display'];
		}
		if ( is_array($progress) 
			&& array_key_exists($video_key, $progress)
			&& array_key_exists($format, $progress[$video_key])
			&& array_key_exists('time_to_wait', $progress[$video_key][$format])
		) {
			$time_to_wait = $progress[$video_key][$format]['time_to_wait'];
		}
	}

	if ( $status == "Encoding Complete" ) {
		$disabled = ' disabled title="'.__('Format already exists', 'video-embed-thumbnail-generator').'"';
		$checked = '';
	}

	if ( $checked == '' ) { $something_to_encode = true; }

	if ( !current_user_can('encode_videos') ) {
		$disabled = ' disabled title="'.__('You don\'t have permission to encode videos', 'video-embed-thumbnail-generator').'"';
		$something_to_encode = false;
	}

	$meta_array = array( 
		'checked' => $checked, 
		'disabled' => $disabled, 
		'meta' => $meta, 
		'time_to_wait'=> $time_to_wait, 
		'something_to_encode' => $something_to_encode, 
		'encoding_now' => $encoding_now, 
		'blog_id' => $blog_id 
	);

	return $meta_array;

}

function kgvid_ajax_generate_encode_checkboxes() {

	check_ajax_referer( 'video-embed-thumbnail-generator-nonce', 'security' );

	$movieurl = $_POST['movieurl'];
	$post_id = $_POST['post_id'];
	$page = $_POST['page'];
	if ( isset($_POST['blog_id']) ) { $blog_id = $_POST['blog_id']; }
	else { $blog_id = false; }

	if (isset($_POST['encodeformats'])) {
		$encode_checked = $_POST['encodeformats'];
		$kgvid_postmeta = kgvid_get_attachment_meta($post_id);
		foreach ( $encode_checked as $format => $checked ) {
			if ( $checked == "true" ) { $kgvid_postmeta['encode'][$format] = 'on'; }
			else {$kgvid_postmeta['encode'][$format] = 'notchecked'; }
		}
		kgvid_save_attachment_meta($post_id, $kgvid_postmeta);
	}

	$checkboxes = kgvid_generate_encode_checkboxes($movieurl, $post_id, $page, $blog_id);
	echo json_encode($checkboxes);
	die();

}
add_action('wp_ajax_kgvid_generate_encode_checkboxes', 'kgvid_ajax_generate_encode_checkboxes');

function kgvid_generate_encode_checkboxes($movieurl, $post_id, $page, $blog_id = false) {

	$user_ID = get_current_user_id();

	$options = kgvid_get_options();
	$video_encode_queue = kgvid_get_encode_queue();
	$video_formats = kgvid_video_formats();

	$video_queued = false;
	$something_to_encode = false;
	$encoding_now = false;
	$encode_disabled = "";
	$post_mime_type = "";
	$actualwidth = "1921";
	$actualheight = "1081";
	$encodevideo_info = array();
	$is_attachment = false;

	if ( !empty($blog_id) && $blog_id != 'false' ) {
		switch_to_blog($blog_id);
		$blog_name_text = '['.$blog_id.']';
		$blog_id_text = $blog_id.'-';
	}
	else {
		$blog_name_text = '';
		$blog_id_text = '';
	}

	if ( !empty($movieurl) ) {

		$encodevideo_info = kgvid_encodevideo_info($movieurl, $post_id);
		$sanitized_url = kgvid_sanitize_url($movieurl);
		$movieurl = $sanitized_url['movieurl'];
		if ( get_post_type($post_id) == "attachment" ) { //if the video is in the database
			$is_attachment = true;
			$kgvid_postmeta = kgvid_get_attachment_meta($post_id);
			$post_mime_type = get_post_mime_type($post_id);
			$dimensions = kgvid_set_video_dimensions($post_id);
			$actualwidth = $dimensions['actualwidth'];
			$actualheight = $dimensions['actualheight'];
			$post = get_post($post_id);
		}
		else { //video's not in the database
			$is_attachment = false;
			unset($video_formats['fullres']);

			$check_mime_type = kgvid_url_mime_type($movieurl);
			
			$post_mime_type = $check_mime_type['type'];

			if ( !empty($video_encode_queue) ) {
				foreach ($video_encode_queue as $video_key => $video_entry) {
					if ( $video_entry['movieurl'] == $movieurl ) {
						if ( is_array($video_entry) && array_key_exists('movie_info', $video_entry) ) {
							$actualwidth = $video_entry['movie_info']['width'];
							$actualheight = $video_entry['movie_info']['height'];
						}
						break;
					}
				}
			reset($video_encode_queue);
			}

		}
		if ( $post_mime_type == "video/m4v" || $post_mime_type == "video/quicktime" ) { $post_mime_type = "video/mp4"; }

	}//if movieurl is set
	else {
		$encode_disabled = ' disabled title="'.__('Please enter a valid video URL', 'video-embed-thumbnail-generator').'"';
		unset($video_formats['fullres']);
		unset($video_formats['custom_h264']);
		unset($video_formats['custom_webm']);
		unset($video_formats['custom_ogg']);
		unset($video_formats['custom_vp9']);
	}

	if ( $options['ffmpeg_exists'] == "notinstalled" ) {
		$ffmpeg_disabled_text = 'disabled="disabled" title="'.sprintf( _x('%1$s not found at %2$s', 'ex: FFMPEG not found at /usr/local/bin', 'video-embed-thumbnail-generator'), strtoupper($options['video_app']), $options['app_path']).'"';
	}
	else { $ffmpeg_disabled_text = ""; }

	if ( ($is_attachment && $user_ID != $post->post_author && !current_user_can('edit_others_video_encodes') ) || !current_user_can('encode_videos') ) {
		$ffmpeg_disabled_text = ' disabled title="'.__('Insufficient priveleges to encode this video', 'video-embed-thumbnail-generator').'"';
		$security_disabled = true;
	}
	else { $security_disabled = false; }

	$video_key = false;
	if ( !empty($video_encode_queue) && !empty($movieurl) ) {
		foreach ($video_encode_queue as $video_key => $video_entry) {
			if ( $video_entry['movieurl'] == $movieurl ) {
				foreach ( $video_entry['encode_formats'] as $format => $value ) {
					if ( !array_key_exists($format, $video_formats) && $value['status'] != 'notchecked' ) {
						$video_formats[$format]['name'] = $value['name'];
						if ( array_key_exists('filepath', $value) && file_exists($value['filepath']) ) {
							$encodevideo_info[$format]['exists'] = true;
							if ( is_writable($value['filepath']) ) { $encodevideo_info[$format]['writable'] = true; }
							else { $encodevideo_info[$format]['writable'] = false; }
						}
						else {
							$encodevideo_info[$format]['exists'] = false;
							$encodevideo_info[$format]['writable'] = false;
						}
						$video_formats[$format]['status'] = $value['status'];
					}
					elseif ( array_key_exists($format, $video_formats) ) { //don't recreate any formats that were previously unset
						$video_formats[$format]['status'] = $value['status'];
					}
					if ( array_key_exists('lastline', $video_entry['encode_formats'][$format]) ) {
						$video_formats[$format]['lastline'] = $video_entry['encode_formats'][$format]['lastline'];
					}
				}
				$video_queued = true;
				break;
			}
		}
	}

	if ( $post_mime_type == 'image/gif' ) {
		$fullres_only = array('fullres');
		$video_formats = array_intersect_key($video_formats, array_flip($fullres_only));
	}

	$checkboxes = '<div id="attachments-'.$blog_id_text.$post_id.'-kgflashmediaplayer-encodeboxes" class="kgvid_checkboxes_section"><ul>';

	foreach ( $video_formats as $format => $format_stats ) {

		if ( strpos($post_mime_type, strval($format)) !== false ) { continue; } //skip webm or ogv checkbox if the video is webm or ogv

		if ( empty($movieurl) ) { $disabled[$format] = ' disabled title="Please enter a valid video URL"'; }

		if ( !array_key_exists('status', $format_stats) ) { $format_stats['status'] = "notchecked"; } //if this video isn't in the queue

		if ( $format_stats['status'] == "lowres" ||
			(
				$actualheight != "" && $format_stats['type'] == "h264" && $format != "fullres" &&
				(
					( strpos($post_mime_type, "mp4") !== false && $actualheight <= $format_stats['height'] ) ||
					( strpos($post_mime_type, "mp4") === false && $actualheight < $format_stats['height'] )
				)
			)
		) { continue; } //if the format is bigger than the original video, skip the checkbox

		if ( !empty($encodevideo_info) && !$encodevideo_info[$format]['exists']
				&& ( 
					strpos($format, 'custom_') === 0 //skip custom formats that don't exist
					|| ( $options['hide_video_formats'] && is_array($options['encode']) && !array_key_exists($format, $options['encode'])) //skip options disabled in settings
					|| ( $options['hide_video_formats'] && !is_array($options['encode']) ) //skip all options if they're all disabled
				) 
			) { continue; } 

		if ( $format == 'fullres' ) {

			if ( $encodevideo_info['fullres']['exists'] == true && $format_stats['status'] != "encoding" && $format_stats['status'] != "Encoding Complete" ) {
				unlink($encodevideo_info[$format]['filepath']);
				$encodevideo_info['fullres']['exists'] = false;
			}

			if ( isset($kgvid_postmeta)
				&& array_key_exists('original_replaced', $kgvid_postmeta)
				&& $kgvid_postmeta['original_replaced'] == $options['replace_format']
			) {
				$format_stats['name'] = sprintf( _x('%s again', 'Replace original with full resolution format again', 'video-embed-thumbnail-generator'), $format_stats['name']);
			}

		}

		if ( !array_key_exists('lastline', $format_stats) ) { $format_stats['lastline'] = ''; }
		$meta_array = kgvid_encode_format_meta($encodevideo_info, $video_key, $format, $format_stats['status'], $format_stats['lastline'], $post_id, $movieurl, $page);

		if ( $meta_array['something_to_encode'] == true ) { $something_to_encode = true; }
		if ( $meta_array['encoding_now'] == true ) { $encoding_now = true; }

		$checkboxes .= "\n\t\t\t".'<li><input class="kgvid_encode_checkbox" type="checkbox" id="attachments-'.$blog_id_text.$post_id.'-kgflashmediaplayer-encode'.$format.'" name="attachments'.$blog_name_text.'['.$post_id.'][kgflashmediaplayer-encode]['.$format.']" '.$meta_array['checked'].' '.$ffmpeg_disabled_text.$meta_array['disabled'].' data-format="'.$format.'"> <label for="attachments-'.$blog_id_text.$post_id.'-kgflashmediaplayer-encode'.$format.'">'.$format_stats['name'].'</label> <span id="attachments-'.$blog_id_text.$post_id.'-kgflashmediaplayer-meta'.$format.'" class="kgvid_format_meta">'.$meta_array['meta'].'</span>';

		if ( !$security_disabled 
			&& $is_attachment 
			&& empty($meta_array['disabled'])
			&& $format_stats['status'] != 'queued'
			&& $format != 'fullres' 
			&& $page != 'queue' 
		) { 
			$checkboxes .= "<span id='pick-".$post_id."-".$format."' class='button kgvid_encode_checkbox_button' data-choose='".sprintf( __('Choose %s', 'video-embed-thumbnail-generator'), $format_stats['name'] )."' data-update='".sprintf( __('Set as %s', 'video-embed-thumbnail-generator'), $format_stats['name'] )."' onclick='kgvid_pick_format(this, \"".$post_id."\", \"".esc_attr($format_stats['mime'])."\", \"".$format."\", \"".esc_attr($movieurl)."\", \"".$blog_id."\");'>".__('Choose from Library', 'video-embed-thumbnail-generator')."</span>";
		}
		$checkboxes .= '</li>';

	}//end format loop

	$checkboxes .= '</ul>';

	if ( $something_to_encode == false ) {
		$encode_disabled = ' disabled title="'.__('Nothing to encode', 'video-embed-thumbnail-generator').'" style="display:none;"';
	}

	if ( $page == "queue" ) {
		$button_text = _x('Update', 'Button text', 'video-embed-thumbnail-generator');
		$checkboxes .= "\n\t\t\t".'<input type="hidden" name="attachments'.$blog_name_text.'['.$post_id.'][kgflashmediaplayer-url]" value="'.$movieurl.'">';
	}
	else { $button_text = _x('Encode selected', 'Button text', 'video-embed-thumbnail-generator'); }

	$checkboxes .= '<input type="button" id="attachments-'.$blog_id_text.$post_id.'-kgflashmediaplayer-encode" name="attachments'.$blog_name_text.'['.$post_id.'][kgflashmediaplayer-encode]" class="button videopack-encode-button" value="'.$button_text.'" onclick="kgvid_enqueue_video_encode(\''.$post_id.'\', \''.$blog_id.'\');" '.$ffmpeg_disabled_text.$encode_disabled.'/><div style="display:block;" id="attachments-'.$blog_id_text.$post_id.'-encodeplaceholder"></div>';

	if ( $page != "queue" ) {
		if ( is_array($options['encode']) || $options['hide_video_formats'] == false ) {
			$checkboxes .= '<small><em>'.__('Generates additional video formats compatible with most mobile & HTML5-compatible browsers', 'video-embed-thumbnail-generator').'</em></small>';
		}
		else {
			$checkboxes .= '<em>'.__('All additional video formats are disabled in Videopack settings', 'video-embed-thumbnail-generator').'</em>';
		}
	}

	if ( $video_queued == true ) {
		while ( count($video_formats) > 0 ) {
			$last_format = array_pop( $video_formats );
			if ( array_key_exists('status', $last_format) && $last_format['status'] != "notchecked" ) { break; } //get the final queued format
		}

		if ( $page != "queue" && !$encoding_now && ($last_format['status'] == "queued" || $last_format['status'] == "canceling") ) {
			$checkboxes .= '<script type="text/javascript">percent_timeout = setTimeout(function(){ kgvid_redraw_encode_checkboxes("'.$video_entry['movieurl'].'", "'.$video_entry['attachmentID'].'", "'.$blog_id.'") }, 2000); jQuery(\'#wpwrap\').data("KGVIDCheckboxTimeout", percent_timeout);</script>';
		}

		else {
			$checkboxes .= '<script type="text/javascript">percent_timeout = setTimeout(function(){ kgvid_update_encode_queue() }, 2000);</script>';
		}
	}
	$checkboxes .= '</div>'; //close encodeboxes div

	if ( !empty($blog_id) && $blog_id != 'false' ) { restore_current_blog(); }

	$arr = array('checkboxes'=>$checkboxes, 'encoding'=>$encoding_now );

	return $arr;
}

function kgvid_generate_queue_table_header() {

	$table_headers = array( _x('Order', 'noun, column header', 'video-embed-thumbnail-generator'),
		_x('User', 'username, column header', 'video-embed-thumbnail-generator'),
		_x('Thumbnail', 'noun, column header', 'video-embed-thumbnail-generator'),
		_x('File', 'noun, column header', 'video-embed-thumbnail-generator'),
		_x('Formats', 'noun, column header', 'video-embed-thumbnail-generator'),
		_x('Actions', 'noun, column header', 'video-embed-thumbnail-generator')
	);

	if ( is_network_admin() ) {
		array_splice( $table_headers, 2, 0, array( _x('Site', 'multisite site name, column header', 'video-embed-thumbnail-generator') ) );
	}

	$code = "<tr>";
	foreach ( $table_headers as $header ) {
		$code .= "<th>".$header."</th>";
	}
	$code .= "</tr>\n";

	return $code;

}

function kgvid_generate_queue_table( $scope = 'site' ) {

	$html = "";
	$current_user = wp_get_current_user();
	$video_encode_queue = kgvid_get_encode_queue();
	$nonce = wp_create_nonce('video-embed-thumbnail-generator-nonce');

	$crons = _get_cron_array();

	if ( $crons ) {
		foreach ( $crons as $timestamp => $cron_job ) {
			if ( is_array($cron_job) && array_key_exists('kgvid_cron_new_attachment', $cron_job) ) {
				foreach ( $cron_job['kgvid_cron_new_attachment'] as $id => $cron_info ) {
					if ( is_array($cron_info) && array_key_exists('args', $cron_info) ) {
						$post = get_post($cron_info['args'][0]);
						if ( $post ) {
							$video_encode_queue[] = array(
								'attachmentID' => $post->ID,
								'parent_id' => $post->post_parent,
								'movieurl' => wp_get_attachment_url( $post->ID ),
								'encode_formats' => 'temp'
							);
						}
					}
				}
			}
		}
	}

	if ( is_network_admin() || 'network' == $scope ) { $total_columns = 8; }
	else { $total_columns = 7; }

	if ( !empty($video_encode_queue) ) {

		$video_formats = kgvid_video_formats();
		$currently_encoding = array();
		$queued = array();

		foreach ( $video_encode_queue as $order => $video_entry ) {

			if ( array_key_exists('blog_id', $video_entry) ) {

				$blog_id = $video_entry['blog_id'];
				$blog_name_text = '['.$blog_id.']';
				$blog_id_text = $blog_id.'-';

				$same_blog = true;

				if ( $video_entry['blog_id'] == get_current_blog_id() ) {
					$same_blog = true;
				}
				else {
					$same_blog = false;
					if ( is_network_admin() || $scope == "network" ) { switch_to_blog($blog_id); }
				}

			}
			else {
				$blog_id = false;
				$blog_name_text = '';
				$blog_id_text = '';
				$same_blog = true;
			}

			$html .= "\t<tr id='tr-".$blog_id_text.$video_entry['attachmentID']."'";

			if ( is_array($video_entry['encode_formats']) ) {
				foreach ( $video_formats as $format => $format_stats ) {

					if ( array_key_exists($format, $video_entry['encode_formats']) && array_key_exists('status', $video_entry['encode_formats'][$format]) ) {

						if ( $video_entry['encode_formats'][$format]['status'] == "encoding" ) {
							$currently_encoding[$order] = true;
							break;
						}
						else if ( $video_entry['encode_formats'][$format]['status'] == "queued" ) {
							$queued[$order] = true;
						}
						else {
							if ( !array_key_exists($order, $currently_encoding) ) { $currently_encoding[$order] = false; }
							if ( !array_key_exists($order, $queued) ) { $queued[$order] = false; }
						}

					}
				}
			}
			else {
				$currently_encoding[$order] = false;
				$queued[$order] = false;
			}

			if ( $currently_encoding[$order] ) { $html .= " class='currently_encoding' "; }
			elseif ( $queued[$order] ) { $html .= " class='kgvid_queued' "; }
			else { $html .= " class='kgvid_complete' "; }

			$html .= ">";

			//Order
			$html .= "<td id='td-".$blog_id_text.$video_entry['attachmentID']."'>".strval(intval($order)+1)."</td>\n";

			//User
			$post = get_post( $video_entry['attachmentID'] );

			if ( ( is_network_admin() || 'network' == $scope )
				|| ( $same_blog && $post && $current_user->ID == $post->post_author )
				|| ( current_user_can('edit_others_video_encodes') && $same_blog )
			) {

				if ( array_key_exists('user_id', $video_entry) && !empty($video_entry['user_id']) ) {
					$user = get_userdata($video_entry['user_id']);
					$html .= "<td>".$user->display_name."</td>\n";
				}
				elseif ( $post )  {
					$user = get_userdata( $post->post_author );
					$html .= "<td>".$user->display_name."</td>\n";
				}
				else { $html .= "<td></td>\n"; }

				//Site
				if ( (is_network_admin() || 'network' == $scope) && $blog_id ) {
					$blog_details = get_bloginfo();
					$html .= "<td><a href='".get_admin_url($blog_id)."'>".$blog_details."</a><input type='hidden' name='attachments[blog_id][".$video_entry['attachmentID']."]' value='".$blog_id."'></td>\n";
				}

				//Thumbnail
				$thumbnail_url = get_post_meta($video_entry['attachmentID'], "_kgflashmediaplayer-poster", true);
				$thumbnail_html = "";
				if ($thumbnail_url != "" ) {
					$thumbnail_html = '<img width="100" src="'.$thumbnail_url.'">';
				}

				//File
				if ( $post && $post->post_type == "attachment" ) {
					$moviefilepath = get_attached_file($video_entry['attachmentID']);
					$attachmentlink = get_admin_url()."post.php?post=".$video_entry['attachmentID']."&action=edit";
				}
				else {
					$moviefilepath = $video_entry['movieurl'];
					$attachmentlink = $video_entry['movieurl'];
				}
				$html .= "\t\t\t\t\t<td><a href='".$attachmentlink."'> ".$thumbnail_html."</a></td>\n";
				$path_info = pathinfo($moviefilepath);
				$file_name =  basename($moviefilepath,'.'.$path_info['extension']);
				$html .= "\t\t\t\t\t<td><a href='".$attachmentlink."'>".urldecode($file_name)."</a><input type='hidden' name='attachments".$blog_name_text."[".$video_entry['attachmentID']."][kgflashmediaplayer-url]' value='".$video_entry['movieurl']."'></td>\n";

				//Formats
				$html .= "\t\t\t\t\t<td class='queue_encode_formats' id='formats_".$video_entry['attachmentID']."'>";
				$html .= "<input type='hidden' id='attachments-".$blog_id_text.$video_entry['attachmentID']."-kgflashmediaplayer-security' name='attachments".$blog_name_text."[".$video_entry['attachmentID']."][kgflashmediaplayer-security]' value='".$nonce."' />";

				if ( is_array($video_entry['encode_formats']) ) { $checkboxes = kgvid_generate_encode_checkboxes($video_entry['movieurl'], $video_entry['attachmentID'], 'queue', $blog_id); }
				else { $checkboxes = array('checkboxes' => __('Please wait while this video is automatically added to the queue...', 'video-embed-thumbnail-generator') ); }
				$html .= $checkboxes['checkboxes'];
				$html .= "</td>\n";

				//Actions
				$html .= "\t\t\t\t\t<td>";
				$html .= "<a id='clear-".$blog_id_text.$video_entry['attachmentID']."' class='submitdelete' href='javascript:void(0)' onclick='kgvid_encode_queue(\"delete\", ".$order.", ".$video_entry['attachmentID'].", \"".$blog_id."\")'";
				if ( $currently_encoding[$order] ) { $html .= " style='display:none;'"; }
				$html .= ">Clear</a>";

			}//end if current user can see this stuff
			elseif ( $same_blog == false ) {
				$html .= "<td colspan='".strval($total_columns-1)."'><strong class='kgvid_queue_message'>".__("Other site's video", 'video-embed-thumbnail-generator')."</strong></td>";
			}
			else {
				$html .= "<td colspan='".strval($total_columns-1)."'><strong class='kgvid_queue_message'>".__("Other user's video", 'video-embed-thumbnail-generator')."</strong></td>";
			}
			$html .= "</td></tr>\n";

			if ( (is_network_admin() || 'network' == $scope) && $blog_id ) { restore_current_blog(); }

		}
	}

	if ( empty($html) ) { $html = "\t<tr><td colspan='".strval($total_columns)."'><strong class='kgvid_queue_message'>".__('Queue is empty', 'video-embed-thumbnail-generator')."</strong></td></tr>\n"; }

return $html;

}

function kgvid_add_FFMPEG_Queue_Page() {
	$options = kgvid_get_options();
	if ( $options['ffmpeg_exists'] == "on" ) { //only add the queue page if FFMPEG is installed
		add_submenu_page('tools.php', _x('Videopack Encoding Queue', 'Tools page title', 'video-embed-thumbnail-generator'), _x('Videopack Encode Queue', 'Title in admin sidebar', 'video-embed-thumbnail-generator'), 'encode_videos', 'kgvid_video_encoding_queue', 'kgvid_FFMPEG_Queue_Page');
	}
}
add_action('admin_menu', 'kgvid_add_FFMPEG_Queue_Page');

function kgvid_add_network_queue_page() {
	if ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( plugin_basename(__FILE__) ) ) {
		add_submenu_page('settings.php', _x('Videopack Encoding Queue', 'Tools page title', 'video-embed-thumbnail-generator'), _x('Network Video Encode Queue', 'Title in network admin sidebar', 'video-embed-thumbnail-generator'), 'manage_network', 'kgvid_network_video_encoding_queue', 'kgvid_FFMPEG_Queue_Page');
	}
}
add_action('network_admin_menu', 'kgvid_add_network_queue_page');

function kgvid_FFMPEG_Queue_Page() {

	wp_enqueue_media();
	$options = kgvid_get_options();
	$network_options = get_site_option('kgvid_video_embed_network_options');
	$queue_control_html = '';

	if ( current_user_can('edit_others_video_encodes') && 
		( !is_multisite()
		|| is_network_admin()
		|| ( function_exists( 'is_plugin_active_for_network' ) 
			&& is_plugin_active_for_network( plugin_basename(__FILE__) ) 
			&& ( 
				( 
					is_array($network_options) 
					&& array_key_exists('queue_control', $network_options) 
					&& $network_options['queue_control'] == 'play' 
				)
				|| is_super_admin() 
				)
			) 
		)
	) {

		if ( $options['queue_control'] == 'play') {
			$opposite_command = 'pause';
			$title_text = __('Pause the queue. Any videos currently encoding will complete.', 'video-embed-thumbnail-generator');
		}
		else {
			$opposite_command = 'play';
			$title_text = __('Start encoding', 'video-embed-thumbnail-generator');
		}

		$queue_control_html = '<span id="kgvid-encode-queue-control" class="kgvid-encode-queue dashicons dashicons-controls-'.$opposite_command.' kgvid-encode-queue-control-disabled" title="'.$title_text.'"></span>';
	}
	elseif ( is_multisite()
		&& !is_network_admin()
		&& function_exists( 'is_plugin_active_for_network' )
		&& is_plugin_active_for_network( plugin_basename(__FILE__) )
		&& is_array($network_options) 
		&& array_key_exists('queue_control', $network_options) 
		&& $network_options['queue_control'] == 'pause'
		&& !is_super_admin()
	) {
		$queue_control_html = '<span id="kgvid-encode-queue-control-disabled" class="kgvid-encode-queue dashicons dashicons-controls-play kgvid-encode-queue-control-disabled" title="'.__('Queue is paused by Network Super Admin.', 'video-embed-thumbnail-generator').'"></span>';
	}

?>
	<div class="wrap">
		<h1><?php _e('Videopack Encoding Queue', 'video-embed-thumbnail-generator'); 
			echo $queue_control_html;
		?></h1>
		<form method="post" action="tools.php?page=kgvid_video_encoding_queue">
		<?php wp_nonce_field('video-embed-thumbnail-generator-nonce','video-embed-thumbnail-generator-nonce'); ?>
		<table class="widefat" id="kgvid_encode_queue_table">
			<thead>
				<?php echo kgvid_generate_queue_table_header(); ?>
			</thead>
			<tfoot>
				<?php echo kgvid_generate_queue_table_header(); ?>
			</tfoot>
			<tbody class="rows">
				<?php echo kgvid_generate_queue_table(); ?>
			</tbody>
		</table>
		<p>
			<?php if ( current_user_can('edit_others_video_encodes') ) { 
				echo "<div class='attachment-info'><div class='actions'><a href='javascript:void(0)' onclick='kgvid_encode_queue(\"clear_completed\", 0, 0);'>". __('Clear All Completed', 'video-embed-thumbnail-generator') ."</a> | <a href='javascript:void(0)' onclick='kgvid_encode_queue(\"clear_queued\", 0, 0, \"\");'>". __('Clear All Queued', 'video-embed-thumbnail-generator') ."</a> | <a href='javascript:void(0)' onclick='kgvid_encode_queue(\"clear_all\", 0, 0, \"\");'>". __('Clear All', 'video-embed-thumbnail-generator') ."</a> <span class='kgvid_queue_clear_info'>".__('Completed videos are cleared weekly, or daily if there are more than 50 entries in the queue.')."</div></div>"; 
			}
			 
			?>
		</p>
		</form>
	</div>
<?php

kgvid_encode_videos();

}

function kgvid_add_network_settings_page() {
		add_submenu_page('settings.php', _x('Videopack', 'Settings page title', 'video-embed-thumbnail-generator'), _x('Videopack', 'Settings page title in admin sidebar', 'video-embed-thumbnail-generator'), 'manage_network_options', 'video_embed_thumbnail_generator_settings', 'kgvid_network_settings_page' );
}
add_action('network_admin_menu', 'kgvid_add_network_settings_page');

function kgvid_validate_network_settings($input) {

	$options = get_site_option( 'kgvid_video_embed_network_options' );
	$default_options = kgvid_default_network_options();

	if ( $input['app_path'] != $options['app_path'] || $input['video_app'] != $options['video_app'] ) {
		$input = kgvid_validate_ffmpeg_settings($input);
	}
	else { $input['ffmpeg_exists'] = $options['ffmpeg_exists']; }

	// load all settings and make sure they get a value of false if they weren't entered into the form
	foreach ( $default_options as $key => $value ) {
		if ( !array_key_exists($key, $input) ) { $input[$key] = false; }
	}

	return $input;

}

function kgvid_network_settings_page() {

	$network_options = get_site_option( 'kgvid_video_embed_network_options' );

	if (isset($_POST['action']) && $_POST['action'] == 'update_kgvid_network_settings') {

		$nonce = $_POST['kgvid_settings_security'];
		if ( ! wp_verify_nonce( $nonce, 'video-embed-thumbnail-generator-nonce' ) ) { die; }
		else {

			if (isset ($_POST["video-embed-thumbnail-generator-reset"])) { //reset button pressed
				$default_network_options = kgvid_default_network_options();
				$options_updated = update_site_option( 'kgvid_video_embed_network_options', $default_network_options );
				add_settings_error( 'video_embed_thumbnail_generator_settings', "options-reset", __("Videopack network settings reset to default values.", 'video-embed-thumbnail-generator'), "updated" );
			}
			else { //save button pressed
				$input = $_POST['kgvid_video_embed_options'];
				$validated_options = kgvid_validate_network_settings($input);
				$options_updated = update_site_option( 'kgvid_video_embed_network_options', $validated_options );
			}
		}

	}

	?>
	<div class="wrap videopack-settings">
		<h1>Videopack Network Settings</h1>
		<?php settings_errors( 'video_embed_thumbnail_generator_settings' ); ?>
		<form method="post">
		<input type="hidden" name="action" value="update_kgvid_network_settings" />
		<input type="hidden" name="kgvid_settings_security" id="kgvid_settings_security" value="<?php echo wp_create_nonce('video-embed-thumbnail-generator-nonce'); ?>">
		<table class='form-table'>
			<tbody>
				<tr valign='middle'>
					<th scope='row'><label for='app_path'><?php _e('Path to applications on the server:', 'video-embed-thumbnail-generator'); ?></label></th>
					<td><?php kgvid_app_path_callback(); ?></td>
				</tr>
					<th scope='row'><label for='video_app'><?php _e('Application for thumbnails & encoding:', 'video-embed-thumbnail-generator'); ?></label></th>
					<td><?php kgvid_video_app_callback(); ?></td>
				</tr>
				<tr>
					<th scope='row'><label for='moov'><?php _e('Method to fix encoded H.264 headers for streaming:', 'video-embed-thumbnail-generator'); ?></label></th>
					<td><?php kgvid_moov_callback(); ?></td>
				</tr>
				<tr>
					<th scope='row'><label for='video_bitrate_flag'><?php _e('FFMPEG legacy options:', 'video-embed-thumbnail-generator'); ?></label></th>
					<td><?php kgvid_ffmpeg_options_callback(); ?></td>
				</tr>
				<tr>
					<th scope='row'><label for='video_app'><?php _e('Execution:', 'video-embed-thumbnail-generator'); ?></label></th>
					<td><?php kgvid_execution_options_callback(); ?></td>
				</tr>
				<tr>
					<th scope='row'><label><?php _e('User capabilties for new sites:', 'video-embed-thumbnail-generator'); ?></label></th>
					<td><?php kgvid_user_roles_callback('network'); ?></td>
				</tr>
				<tr>
					<th scope='row'><label><?php _e('Super Admins only:', 'video-embed-thumbnail-generator'); ?></label></th>
					<td><?php kgvid_superadmin_capabilities_callback(); ?></td>
				</tr>
			</tbody>
		</table>
		<p class='submit'>
   		   <?php submit_button(__('Save Changes', 'video-embed-thumbnail-generator'), 'primary', 'kgvid_submit', false, array( 'onclick' => "jQuery('form :disabled').prop('disabled', false);" ) ); ?>
   		   <?php submit_button(__('Reset Options', 'video-embed-thumbnail-generator'), 'secondary', 'video-embed-thumbnail-generator-reset', false); ?>
   		</p>
   		</form>
   		<div class="kgvid-donate-box wp-core-ui wp-ui-highlight">
		<span><?php _e('If you\'re getting some use out of this plugin, please consider donating a few dollars to support its future development.', 'video-embed-thumbnail-generator') ?></span>
		<a href="https://www.wordpressvideopack.com/donate/"><img alt="Donate" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif"></a>
		</div>
		<script type='text/javascript'>
				jQuery(document).ready(function() {
						kgvid_hide_plugin_settings();
						kgvid_moov_setting();
						jQuery('form :input').change(function() {
							kgvid_save_plugin_settings(this);
						});
					}
				);
		</script>
	</div>
	<?php
}

function kgvid_superadmin_capabilities_callback() {

	$network_options = get_site_option('kgvid_video_embed_network_options');
	echo "<input ".checked( $network_options['superadmin_only_ffmpeg_settings'], "on", false )." id='superadmin_only_ffmpeg_settings' name='kgvid_video_embed_options[superadmin_only_ffmpeg_settings]' type='checkbox' /> <label for='superadmin_only_ffmpeg_settings'>".sprintf( _x('Can access %s settings tab.', 'Can access FFMPEG settings tab', 'video-embed-thumbnail-generator'), "<strong class='video_app_name'>".strtoupper($network_options['video_app'])."</strong>" )."</label> <span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>".__( sprintf( 'Only Super admins will be allowed to view and modify %s settings.', "<strong class='video_app_name'>".strtoupper($network_options['video_app'])."</strong>" ), 'video-embed-thumbnail-generator' )."</span></span></a><br>\n\t";

	echo "<div class='kgvid_video_app_required'>";
	echo __('Email all encoding errors on the network to:', 'video-embed-thumbnail-generator')." <select id='network_error_email' name='kgvid_video_embed_options[network_error_email]'>";
	$network_super_admins = get_super_admins();
	if ( $network_super_admins ) {
		$authorized_users = array();
		foreach ( $network_super_admins as $network_super_admin ) {
			$user = get_user_by('login', $network_super_admin);
			$authorized_users[$network_super_admin] = $user->ID;
		}
	}
	$items = array_merge( array(
		__('Nobody', 'video-embed-thumbnail-generator') => 'nobody',
		__('User who initiated encoding', 'video-embed-thumbnail-generator') => 'encoder'
		), $authorized_users
	);
	foreach($items as $name=>$value) {
		$selected = ($network_options['network_error_email']==$value) ? 'selected="selected"' : '';
		echo "<option value='$value' $selected>$name</option>";
	}
	echo "</select> <span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>".sprintf( __( 'Can also be set on individual sites if the %s settings tab isn\'t disabled.' , 'video-embed-thumbnail-generator'), "<strong class='video_app_name'>".strtoupper($network_options['video_app'])."</strong>" )."</a></span>";
	echo "</div>\n\t";

}

function kgvid_add_settings_page() {
		add_options_page( _x('Videopack', 'Settings page title', 'video-embed-thumbnail-generator'), _x('Videopack', 'Settings page title in admin sidebar', 'video-embed-thumbnail-generator'), 'manage_options', 'video_embed_thumbnail_generator_settings', 'kgvid_settings_page' );
}
add_action('admin_menu', 'kgvid_add_settings_page');

function kgvid_settings_page() {
	wp_enqueue_media();
	$options = kgvid_get_options();
	$network_options = get_site_option('kgvid_video_embed_network_options');
	$video_app = $options['video_app'];
	if ( $video_app == "avconv" ) { $video_app = "libav"; }
	?>
	<div class="wrap videopack-settings">
		<h1><?php _e('Videopack Settings', 'video-embed-thumbnail-generator'); ?></h1>
		<h2 class="nav-tab-wrapper">
			<a href="#general" id="general_tab" class="nav-tab" onclick="kgvid_switch_settings_tab('general');"><?php _ex('General', 'Adjective, tab title', 'video-embed-thumbnail-generator') ?></a>
			<?php if ( !is_multisite()
			|| ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( plugin_basename(__FILE__) ) && $options['ffmpeg_exists'] == "on" && is_array($network_options) && (is_super_admin() || $network_options['superadmin_only_ffmpeg_settings'] == false) )
			) { ?>
			<a href="#encoding" id="encoding_tab" class="nav-tab" onclick="kgvid_switch_settings_tab('encoding');"><?php printf( _x('%s Settings', 'FFMPEG Settings, tab title', 'video-embed-thumbnail-generator'), "<span class='video_app_name'>".strtoupper($video_app)."</span>" ); ?></a>
			<?php } ?>
		</h2>
		<form method="post" action="options.php">
		<?php settings_fields('kgvid_video_embed_options'); ?>
		<input type="hidden" id="kgvid_settings_security" value="<?php echo wp_create_nonce('video-embed-thumbnail-generator-nonce'); ?>">
		<?php kgvid_do_settings_sections('video_embed_thumbnail_generator_settings'); ?>
     	<p class='submit'>
   		   <?php submit_button(__('Save Changes', 'video-embed-thumbnail-generator'), 'primary', 'kgvid_submit', false, array( 'onclick' => "jQuery('form :disabled').prop('disabled', false);" ) ); ?>
   		   <?php submit_button(__('Reset Options', 'video-embed-thumbnail-generator'), 'secondary', 'video-embed-thumbnail-generator-reset', false); ?>
   		</p>
		</form>
		<div class="kgvid-donate-box wp-core-ui wp-ui-highlight">
		<span><?php _e('If you\'re getting some use out of this plugin, please consider donating a few dollars to support its future development.', 'video-embed-thumbnail-generator') ?></span>
		<a href="https://www.wordpressvideopack.com/donate/"><img alt="Donate" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif"></a>
		</form>
		</div>
		<script type='text/javascript'>
			jQuery(document).ready(function() {
					kgvid_switch_settings_tab(document.URL.substr(document.URL.indexOf('#')+1));
					jQuery('form :input').change(function() {
  						kgvid_save_plugin_settings(this);
					});
				}
			);
		</script>
	</div>
<?php
}

function kgvid_video_embed_options_init() {

	//check for network options in 'admin_init' because is_plugin_active_for_network is not defined in 'init' hook
	if ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( plugin_basename(__FILE__) ) ) {

		$network_options = get_site_option( 'kgvid_video_embed_network_options' );

		if( !is_array($network_options) ) { //if the network options haven't been set yet

			switch_to_blog(1);
			$options = get_option('kgvid_video_embed_options');
			$network_options = kgvid_default_network_options();
			if ( is_array($options) ) {
				$network_options = array_intersect_key($network_options, $options); //copy options from main blog to network
				$network_options['default_capabilities'] = $options['capabilities'];
				if ( !array_key_exists('simultaneous_encodes', $network_options) ) { $network_options['simultaneous_encodes'] = 1; }
			}
			restore_current_blog();

			if ( !isset($network_options['ffmpeg_exists']) || $network_options['ffmpeg_exists'] == "notchecked" ) {
				$ffmpeg_info = kgvid_check_ffmpeg_exists($network_options, false);
				if ( $ffmpeg_info['ffmpeg_exists'] == true ) { $network_options['ffmpeg_exists'] = "on"; }
				$network_options['app_path'] = $ffmpeg_info['app_path'];
			}
			update_site_option('kgvid_video_embed_network_options', $network_options);

		}//end setting initial network options
		else { //network options introduced in version 4.3 exist already

			$network_options_old = $network_options;

			if ( !array_key_exists('superadmin_only_ffmpeg_settings', $network_options) ) {
				$default_network_options = kgvid_default_network_options();
				$network_options['superadmin_only_ffmpeg_settings'] = $default_network_options['superadmin_only_ffmpeg_settings'];
			}

			if ( !array_key_exists('network_error_email', $network_options) ) {
				$network_options['network_error_email'] = 'nobody';
			}

			if ( $network_options_old != $network_options ) {
				update_site_option('kgvid_video_embed_network_options', $network_options);
			}

		}


		$network_queue = get_site_option('kgvid_video_embed_queue');

		if ( is_array($network_options) && array_key_exists('ffmpeg_exists', $network_options) && 'on' == $network_options['ffmpeg_exists']
 		&& false === $network_queue ) { //if the network queue hasn't been set yet

			$sites = get_sites();

			if ( is_array($sites) ) {
				$network_queue = array();
				foreach ( $sites as $site ) {
					$blog_id = $site->__get('id');
					$site_queue = get_blog_option($blog_id, 'kgvid_video_embed_queue');
					if ( is_array($site_queue) ) {
						foreach ( $site_queue as $index => $entry ) {
							$site_queue[$index]['blog_id'] = $blog_id;
						}
						$network_queue = array_merge($network_queue, $site_queue);
						delete_blog_option($blog_id, 'kgvid_video_embed_queue');
					}

				}//end loop through sites
				array_multisort($network_queue);
				update_site_option( 'kgvid_video_embed_queue', $network_queue );
			}

		}//end copying site queues to network
	}//end network activation setup

	function kgvid_do_settings_sections( $page ) {
		global $wp_settings_sections, $wp_settings_fields;
	 
		if ( ! isset( $wp_settings_sections[ $page ] ) ) {
			return;
		}
	 
		foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
			if ( $section['title'] ) {
				echo "<h2 id='header_{$section['id']}'>{$section['title']}</h2>\n";
			}
	 
			if ( $section['callback'] ) {
				call_user_func( $section['callback'], $section );
			}
	 
			if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
				continue;
			}
			echo '<table class="form-table" id="table_'.$section["id"].'">';
			do_settings_fields( $page, $section['id'] );
			echo '</table>';
		}
	} 

	register_setting('kgvid_video_embed_options', 'kgvid_video_embed_options', 'kgvid_video_embed_options_validate' );

	$options = kgvid_get_options();

	add_settings_section('kgvid_video_embed_playback_settings', __('Default Video Playback Settings', 'video-embed-thumbnail-generator'), 'kgvid_plugin_playback_settings_section_callback', 'video_embed_thumbnail_generator_settings');
	add_settings_section('kgvid_video_embed_plugin_settings', __('Plugin Settings', 'video-embed-thumbnail-generator'), 'kgvid_plugin_settings_section_callback', 'video_embed_thumbnail_generator_settings');
	add_settings_section('kgvid_video_embed_encode_settings', __('Video Encoding Settings', 'video-embed-thumbnail-generator'), 'kgvid_encode_settings_section_callback', 'video_embed_thumbnail_generator_settings');
	add_settings_section('kgvid_video_embed_encode_test_settings', __('Video Encoding Test', 'video-embed-thumbnail-generator'), 'kgvid_encode_settings_section_callback', 'video_embed_thumbnail_generator_settings');

	add_settings_field('poster', __('Default thumbnail:', 'video-embed-thumbnail-generator'), 'kgvid_poster_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_playback_settings', array( 'label_for' => 'poster' ) );
	add_settings_field('endofvideooverlay', __('End of video image:', 'video-embed-thumbnail-generator'), 'kgvid_endofvideooverlay_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_playback_settings' );
	add_settings_field('watermark', __('Watermark overlay:', 'video-embed-thumbnail-generator'), 'kgvid_watermark_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_playback_settings', array( 'label_for' => 'watermark' ) );
	add_settings_field('align', __('Video alignment:', 'video-embed-thumbnail-generator'), 'kgvid_align_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_playback_settings', array( 'label_for' => 'align' ) );
	add_settings_field('resize', __('Automatically adjust videos:', 'video-embed-thumbnail-generator'), 'kgvid_resize_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_playback_settings', array( 'label_for' => 'resize' ) );
	add_settings_field('dimensions', __('Video dimensions:', 'video-embed-thumbnail-generator'), 'kgvid_dimensions_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_playback_settings', array( 'label_for' => 'width' ) );
	add_settings_field('gallery_options', __('Video gallery:', 'video-embed-thumbnail-generator'), 'kgvid_video_gallery_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_playback_settings', array( 'label_for' => 'gallery_width' ) );
	add_settings_field('controls', __('Video controls:', 'video-embed-thumbnail-generator'), 'kgvid_controls_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_playback_settings', array( 'label_for' => 'controls' ) );
	add_settings_field('js_skin', _x('Skin class:', 'CSS class for video skin', 'video-embed-thumbnail-generator'), 'kgvid_js_skin_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_playback_settings', array( 'label_for' => 'js_skin' ) );
	add_settings_field('custom_attributes', __('Custom attributes:', 'video-embed-thumbnail-generator'), 'kgvid_custom_attributes_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_playback_settings', array( 'label_for' => 'custom_attributes' ) );

	add_settings_field('security', __('Video sharing:', 'video-embed-thumbnail-generator'), 'kgvid_security_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_plugin_settings', array( 'label_for' => 'right_click' ) );
	add_settings_field('performance', __("Performance:", 'video-embed-thumbnail-generator'), 'kgvid_performance_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_plugin_settings', array( 'label_for' => 'count_views' ) );
	add_settings_field('replacevideoshortcode', __("Replace video shortcode:", 'video-embed-thumbnail-generator'), 'kgvid_replace_video_shortcode_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_plugin_settings', array( 'label_for' => 'replace_video_shortcode' ) );
	add_settings_field('rewrite_attachment_url', __("Attachment URL Rewriting:", 'video-embed-thumbnail-generator'), 'kgvid_rewrite_attachment_url_callback', __FILE__, 'kgvid_video_embed_plugin_settings', array( 'label_for' => 'rewrite_attachment_url' ) );
	add_settings_field('generate_thumbs', __('Default number of thumbnails to generate:', 'video-embed-thumbnail-generator'), 'kgvid_generate_thumbs_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_plugin_settings', array( 'label_for' => 'generate_thumbs' ) );
	add_settings_field('featured', __('Featured image:', 'video-embed-thumbnail-generator'), 'kgvid_featured_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_plugin_settings', array( 'label_for' => 'featured' ) );
	add_settings_field('thumb_parent', __('Attach thumbnails to:', 'video-embed-thumbnail-generator'), 'kgvid_thumb_parent_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_plugin_settings', array( 'label_for' => 'thumb_parent' ) );
	add_settings_field('user_roles', __('User capabilities:', 'video-embed-thumbnail-generator'), 'kgvid_user_roles_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_plugin_settings', array( 'label_for' => 'user_roles' ) );
	add_settings_field('delete_children', __('Delete associated attachments:', 'video-embed-thumbnail-generator'), 'kgvid_delete_children_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_plugin_settings', array( 'label_for' => 'delete_children' ) );
	add_settings_field('titlecode', __('Video title text HTML formatting:', 'video-embed-thumbnail-generator'), 'kgvid_titlecode_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_plugin_settings', array( 'label_for' => 'titlecode' ) );
	add_settings_field('template', __('Attachment page design:', 'video-embed-thumbnail-generator'), 'kgvid_template_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_plugin_settings', array( 'label_for' => 'template' ) );

	if ( !is_plugin_active_for_network( plugin_basename('video_embed_thumbnail_generator_settings') ) ) {
		add_settings_field('app_path', __('Path to applications folder on server:', 'video-embed-thumbnail-generator'), 'kgvid_app_path_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'app_path' ) );
		add_settings_field('video_app', __('Application for thumbnails & encoding:', 'video-embed-thumbnail-generator'), 'kgvid_video_app_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'video_app' ) );
	}

	add_settings_field('browser_thumbnails', __('Enable in-browser thumbnails:', 'video-embed-thumbnail-generator'), 'kgvid_browser_thumbnails_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'browser_thumbnails' ) );
	add_settings_field('encode_formats', __('Default video encode formats:', 'video-embed-thumbnail-generator'), 'kgvid_encode_formats_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings');
	add_settings_field('automatic', __('Do automatically on upload:', 'video-embed-thumbnail-generator'), 'kgvid_automatic_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'auto_encode' ) );
	add_settings_field('automatic_encoded', __('Do automatically on completed encoding:', 'video-embed-thumbnail-generator'), 'kgvid_automatic_completed_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'auto_publish_post' ) );
	add_settings_field('old_videos', __('For previously uploaded videos:', 'video-embed-thumbnail-generator'), 'kgvid_old_video_buttons_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings' );
	add_settings_field('error_email', __('Email encoding errors to:', 'video-embed-thumbnail-generator'), 'kgvid_error_email_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'error_email' ) );
	add_settings_field('htaccess', __('htaccess login:', 'video-embed-thumbnail-generator'), 'kgvid_htaccess_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'htaccess_username' ) );
	add_settings_field('ffmpeg_thumb_watermark', __('Add watermark to thumbnails:', 'video-embed-thumbnail-generator'), 'kgvid_ffmpeg_thumb_watermark_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'ffmpeg_thumb_watermark' ) );
	add_settings_field('ffmpeg_watermark', __('Add watermark to encoded files:', 'video-embed-thumbnail-generator'), 'kgvid_ffmpeg_watermark_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'ffmpeg_watermark' ) );
	if ( !is_plugin_active_for_network( plugin_basename('video_embed_thumbnail_generator_settings') ) ) {
		add_settings_field('moov', __('Method to fix encoded H.264 headers for streaming:', 'video-embed-thumbnail-generator'), 'kgvid_moov_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'moov' ) );
	}
	add_settings_field('rate_control', __('Encode quality control method:', 'video-embed-thumbnail-generator'), 'kgvid_rate_control_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'rate_control' ) );
	add_settings_field('CRFs', __('Constant Rate Factors (CRF):', 'video-embed-thumbnail-generator'), 'kgvid_CRF_options_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'h264_CRF' ) );
	add_settings_field('bitrate_multiplier', __('Average Bit Rate:', 'video-embed-thumbnail-generator'), 'kgvid_average_bitrate_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'bitrate_multiplier' ) );
	add_settings_field('h264_profile', __('H.264 profile:', 'video-embed-thumbnail-generator'), 'kgvid_h264_profile_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'h264_profile' ) );
	add_settings_field('audio_options', __('Audio:', 'video-embed-thumbnail-generator'), 'kgvid_audio_options_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'audio_bitrate' ) );

	if ( !is_plugin_active_for_network( plugin_basename('video_embed_thumbnail_generator_settings') ) ) {
		add_settings_field('ffmpeg_options', __('FFMPEG legacy options:', 'video-embed-thumbnail-generator'), 'kgvid_ffmpeg_options_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'video_bitrate_flag' ) );
		add_settings_field('execution', _x('Execution:', 'program execution options', 'video-embed-thumbnail-generator'), 'kgvid_execution_options_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_settings', array( 'label_for' => 'threads' ) );
	}

	add_settings_field('test_ffmpeg', __('Test FFMPEG:', 'video-embed-thumbnail-generator'), 'kgvid_test_ffmpeg_options_callback', 'video_embed_thumbnail_generator_settings', 'kgvid_video_embed_encode_test_settings' );

	if ( !function_exists( 'videopack_fs' ) ) {

		register_uninstall_hook( __FILE__, 'kgvid_uninstall_plugin' ); //register WP uninstall instead of Freemius uninstall hook
		
	}

}
add_action('admin_init', 'kgvid_video_embed_options_init' );

//callback functions generating HTML for the settings form

	function kgvid_plugin_playback_settings_section_callback() {

		$options = kgvid_get_options();

		if ( $options['embeddable'] != "on" ) {
			$embed_disabled = "disabled='disabled'";
			if ( $options['overlay_embedcode'] == "on" || $options['open_graph'] == "on" ) {
				$options['overlay_embedcode'] = false;
				$options['open_graph'] = false;
				update_option('kgvid_video_embed_options', $options);
			}
		}
		else { $embed_disabled = ""; }

		$players = array(
			"Video.js v7" => "Video.js v7",
			"Video.js v5 (" . __("deprecated", 'video-embed-thumbnail-generator') . ")" => "Video.js",
			__("WordPress Default", 'video-embed-thumbnail-generator') => "WordPress Default",
			__("None", 'video-embed-thumbnail-generator') => "None"
		);

		$players = apply_filters('kgvid_available_video_players', $players);

		echo "<table class='form-table' id='table_kgvid_video_embed_embed_method'><tbody><tr valign='middle'><th scope='row'><label for='embed_method'>".__('Video player:', 'video-embed-thumbnail-generator')."</label></th><td><select class='affects_player' onchange='kgvid_hide_plugin_settings();' id='embed_method' name='kgvid_video_embed_options[embed_method]'>";
		foreach($players as $name => $value) {
			$selected = ($options['embed_method']==$value) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$name</option>";
		}
		echo "</select> <span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>".__('Video.js version 7 is the default player. You can also choose the WordPress Default Mediaelement.js player which may already be skinned to match your theme. Selecting "None" will disable all plugin-related CSS and JS on the front end.', 'video-embed-thumbnail-generator')."</span></span></td></tr></tbody></table>\n";

		$sampleheight = intval($options['height']) + 50;
		echo "<div class='kgvid_setting_nearvid' style='width:".$options['width']."px;'>";
		echo "<div id='kgvid_above_sample_vid'>";
		echo "<span><input class='affects_player' ".checked( $options['overlay_title'], "on", false )." id='overlay_title' name='kgvid_video_embed_options[overlay_title]' type='checkbox' /><label for='overlay_title'>".__('Overlay video title', 'video-embed-thumbnail-generator')."</label></span>";
		echo "<span><input class='affects_player' ".checked( $options['downloadlink'], "on", false )." id='downloadlink' name='kgvid_video_embed_options[downloadlink]' type='checkbox' /> <label for='downloadlink'>".__('Show download link', 'video-embed-thumbnail-generator')."</label></span>";
		echo "<span><span>".__('Sharing:','video-embed-thumbnail-generator')."</span><br>";
		echo "<input class='affects_player' ".checked( $options['overlay_embedcode'], "on", false )." id='overlay_embedcode' name='kgvid_video_embed_options[overlay_embedcode]' type='checkbox' ".$embed_disabled."/> <label for='overlay_embedcode'>".__('Embed code', 'video-embed-thumbnail-generator')."</label><br>";
		echo "<input class='affects_player' ".checked( $options['twitter_button'], "on", false )." id='twitter_button' name='kgvid_video_embed_options[twitter_button]' type='checkbox' onchange='kgvid_hide_plugin_settings();' /> <label for='twitter_button'>".__('Twitter button', 'video-embed-thumbnail-generator')."</label><span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>".__('Enter your Twitter username in the Video Sharing section below.', 'video-embed-thumbnail-generator')."</span></span><br />";
		echo "<input class='affects_player' ".checked( $options['facebook_button'], "on", false )." id='facebook_button' name='kgvid_video_embed_options[facebook_button]' type='checkbox' /> <label for='facebook_button'>".__('Facebook button', 'video-embed-thumbnail-generator')."</label></span></div>";
		$iframeurl = site_url('/')."?videopack[enable]=true&videopack[sample]=true";
		echo "<iframe id='kgvid_samplevideo' style='border:2px;' src='".$iframeurl."' scrolling='no' width='".$options['width']."' height='".$sampleheight."'></iframe>";
		echo "<div style='float:right;'><input class='affects_player' ".checked( $options['view_count'], "on", false )." id='view_count' name='kgvid_video_embed_options[view_count]' type='checkbox' /> <label for='view_count'>".__('Show view count', 'video-embed-thumbnail-generator')."</label></div>";
		echo "<hr style='width:100%;'></div>\n\t";
	}

	function kgvid_plugin_settings_section_callback() { }

	function kgvid_poster_callback() {
		$options = kgvid_get_options();
		echo "<input class='regular-text affects_player' id='poster' name='kgvid_video_embed_options[poster]' type='text' value='".$options['poster']."' /> <span id='pick-thumbnail' class='button' data-choose='".__('Choose a Thumbnail', 'video-embed-thumbnail-generator')."' data-update='".__('Set as video thumbnail', 'video-embed-thumbnail-generator')."' data-change='poster' onclick='kgvid_pick_image(this);'>".__('Choose from Library', 'video-embed-thumbnail-generator')."</span>\n\t";
	}

	function kgvid_endofvideooverlay_callback() {
		$options = kgvid_get_options();
		echo "<input class='affects_player' ".checked( $options['endofvideooverlaysame'], "on", false )." id='endofvideooverlaysame' name='kgvid_video_embed_options[endofvideooverlaysame]' type='checkbox' /> <label for='endofvideooverlaysame'>".__('Display thumbnail image again when video ends.', 'video-embed-thumbnail-generator')."</label><br />";
		echo "<input class='regular-text affects_player' id='endofvideooverlay' name='kgvid_video_embed_options[endofvideooverlay]' ".disabled( $options['endofvideooverlaysame'], "on", false )." type='text' value='".$options['endofvideooverlay']."' /> <span id='pick-endofvideooverlay' class='button' data-choose='".__('Choose End of Video Image', 'video-embed-thumbnail-generator')."' data-update='".__('Set as end of video image', 'video-embed-thumbnail-generator')."' data-change='endofvideooverlay' onclick='kgvid_pick_image(this);'>".__('Choose from Library', 'video-embed-thumbnail-generator')."</span><br />";
		echo __('Display alternate image when video ends.', 'video-embed-thumbnail-generator')."<small>\n\t";
	}

	function kgvid_watermark_callback() {
		$options = kgvid_get_options();
		echo __('Image:', 'video-embed-thumbnail-generator')." <input class='regular-text affects_player' id='watermark' name='kgvid_video_embed_options[watermark]' type='text' value='".$options['watermark']."' /> <span id='pick-watermark' class='button' data-choose='".__('Choose a Watermark', 'video-embed-thumbnail-generator')."' data-update='".__('Set as watermark', 'video-embed-thumbnail-generator')."' data-change='watermark' onclick='kgvid_pick_image(this);'>".__('Choose from Library', 'video-embed-thumbnail-generator')."</span><br />";
		echo __('Link to:', 'video-embed-thumbnail-generator').' ';
		$items = array(
			__("Home page", 'video-embed-thumbnail-generator') => "home",
			__("Parent post", 'video-embed-thumbnail-generator') => "parent",
			__("Video attachment page", 'video-embed-thumbnail-generator') => "attachment",
			__("Download video", 'video-embed-thumbnail-generator') => "download",
			__("Custom URL", 'video-embed-thumbnail-generator') => "custom",
			__("None", 'video-embed-thumbnail-generator') => "false"
		);

		echo "<select class='affects_player' onchange='kgvid_hide_watermark_url(this);' id='watermark_link_to' name='kgvid_video_embed_options[watermark_link_to]'>";
		foreach($items as $name => $value) {
			$selected = ($options['watermark_link_to']==$value) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$name</option>";
		}
		echo "</select>\n\t";
		echo " <input ";
		if ( $options['watermark_link_to'] != 'custom' ) {
			echo "style='display:none;' ";
			$options['watermark_url'] = '';
		}
		echo "class='regular-text affects_player' id='watermark_url' name='kgvid_video_embed_options[watermark_url]' type='text' value='".$options['watermark_url']."' />\n\t";
	}

	function kgvid_align_callback() {
		$options = kgvid_get_options();
		$items = array(__("left", 'video-embed-thumbnail-generator') => "left", __("center", 'video-embed-thumbnail-generator') => "center", __("right", 'video-embed-thumbnail-generator') => "right");
		echo "<select id='align' name='kgvid_video_embed_options[align]'>";
		foreach($items as $name => $value) {
			$selected = ($options['align']==$value) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$name</option>";
		}
		echo "</select>\n\t";
	}

	function kgvid_resize_callback() {
		$options = kgvid_get_options();
		$video_formats = kgvid_video_formats();
		echo "<div id='resize_div'><input ".checked( $options['resize'], "on", false )." id='resize' name='kgvid_video_embed_options[resize]' type='checkbox' /> <label for='resize'>".__('Make video player responsive.', 'video-embed-thumbnail-generator')."</label><br /></div>";
		$items = array( __("automatic", 'video-embed-thumbnail-generator'), __("highest", 'video-embed-thumbnail-generator'), __("lowest", 'video-embed-thumbnail-generator') );
		foreach ( $video_formats as $format => $format_stats ) {
			if ( $format_stats['type'] == 'h264' && !empty($format_stats['label']) ) {
				$items[] = $format_stats['label'];
			}
		}
		echo __('Default playback resolution', 'video-embed-thumbnail-generator')." <select id='auto_res' name='kgvid_video_embed_options[auto_res]' onchange='kgvid_hide_plugin_settings()'>";
		foreach( $items as $name ) {
			$selected = ($options['auto_res']==$name) ? 'selected="selected"' : '';
			echo "<option value='$name' $selected>$name</option>";
		}
		echo "</select> <span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>".__('If multiple H.264 resolutions for a video are available, you can choose to load the highest or lowest available resolution by default, automatically select the resolution based on the size of the video window, or indicate a particular resolution to use every time.', 'video-embed-thumbnail-generator')."</span></span>";
		echo "<p id='pixel_ratio_p'><input ".checked( $options['pixel_ratio'], "on", false )." id='pixel_ratio' name='kgvid_video_embed_options[pixel_ratio]' type='checkbox' /><label for='pixel_ratio'>".__('Use display pixel ratio for resolution calculation', 'video-embed-thumbnail-generator')."</label><span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>".__('Most modern mobile devices and some very high-resolution desktop displays (what Apple calls a Retina display) use a pixel ratio to calculate the size of their viewport. Using the pixel ratio can result in a higher resolution being selected on mobile devices than on desktop devices. Because these devices actually have extremely high resolutions, and in a responsive design the video player usually takes up more of the screen than on a desktop browser, this is not a mistake, but your users might prefer to use less mobile data.', 'video-embed-thumbnail-generator')."</span></span></p>\n\t";

	}

	function kgvid_dimensions_callback() {
		$options = kgvid_get_options();
		echo "<input ".checked( $options['fullwidth'], "on", false )." id='fullwidth' name='kgvid_video_embed_options[fullwidth]' type='checkbox' /> <label for='fullwidth'>".__('Set all videos to expand to 100% of their containers.', 'video-embed-thumbnail-generator')."</label> <span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>".__('Enabling this will ignore any other width settings and set the width of the video to the width of the container it\'s in.', 'video-embed-thumbnail-generator')."</span></span><br />";
		echo __('Default Width:', 'video-embed-thumbnail-generator')." <input class='small-text affects_player' id='width' name='kgvid_video_embed_options[width]' type='text' value='".$options['width']."' /> ".__('Height:', 'video-embed-thumbnail-generator')." <input class='small-text affects_player' id='height' name='kgvid_video_embed_options[height]' type='text' value='".$options['height']."' /><br />";
		$items = array(
			__("no", 'video-embed-thumbnail-generator') => "false",
			__("vertical", 'video-embed-thumbnail-generator') => "vertical",
			__("all", 'video-embed-thumbnail-generator') => "true"
		);
		$select = "<select id='fixed_aspect' name='kgvid_video_embed_options[fixed_aspect]'>";
		foreach($items as $name => $value) {
			$selected = ($options['fixed_aspect']==$value) ? 'selected="selected"' : '';
			$select .= "<option value='$value' $selected>$name</option>";
		}
		$select .= "</select>";
		echo sprintf( __('Constrain %s videos to default aspect ratio.', 'video-embed-thumbnail-generator'), $select )." <span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>".__('When set to "no," the video player will automatically adjust to the aspect ratio of the video, but in some cases a fixed aspect ratio is required, and vertical videos often fit better on the page when shown in a shorter window.', 'video-embed-thumbnail-generator')."</span></span><br />";
		echo "<input ".checked( $options['minimum_width'], "on", false )." id='minimum_width' name='kgvid_video_embed_options[minimum_width]' type='checkbox' /> <label for='minimum_width'>".__('Enlarge lower resolution videos to max width.', 'video-embed-thumbnail-generator')."</label> <span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>".__('Usually if a video\'s resolution is less than the max width, the video player is set to the actual width of the video. Enabling this will always set the same width regardless of the quality of the video. When necessary you can override by setting the dimensions manually.', 'video-embed-thumbnail-generator')."</span></span><br />";
		echo "<input ".checked( $options['inline'], "on", false )." id='inline' name='kgvid_video_embed_options[inline]' type='checkbox' /> <label for='inline'>".__('Allow other content on the same line as the video.', 'video-embed-thumbnail-generator')."</label>\n\t";
	}

	function kgvid_video_gallery_callback() {
		$options = kgvid_get_options();
		echo __('Maximum popup width:', 'video-embed-thumbnail-generator')." <input class='small-text' id='gallery_width' name='kgvid_video_embed_options[gallery_width]' type='text' value='".$options['gallery_width']."' /><br />";
		echo __('Thumbnail width:', 'video-embed-thumbnail-generator')." <input class='small-text' id='gallery_thumb' name='kgvid_video_embed_options[gallery_thumb]' type='text' value='".$options['gallery_thumb']."' /><br />";
		echo " <input ".checked( $options['gallery_thumb_aspect'], "on", false )." id='gallery_thumb_aspect' name='kgvid_video_embed_options[gallery_thumb_aspect]' type='checkbox' /> <label for='gallery_thumb_aspect'>".__('Constrain all gallery thumbnails to default video aspect ratio.', 'video-embed-thumbnail-generator')."</label><br>";
		$items = array();
		$items = array(
			__('Stop, but leave popup window open', 'video-embed-thumbnail-generator') => "",
			__('Autoplay next video in the gallery', 'video-embed-thumbnail-generator') => "next",
			__('Close popup window', 'video-embed-thumbnail-generator') => "close");
		echo "<select id='gallery_end' name='kgvid_video_embed_options[gallery_end]'>";
		foreach($items as $name => $value) {
			$selected = ($options['gallery_end']==$value) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$name</option>";
		}
		echo "</select> ". __('when current gallery video finishes.', 'video-embed-thumbnail-generator')."<br />";
		echo " <input ".checked( $options['gallery_pagination'], "on", false )." id='gallery_pagination' name='kgvid_video_embed_options[gallery_pagination]' onchange='kgvid_hide_paginate_gallery_setting(this)' type='checkbox' /> <label for='gallery_pagination'>".__('Paginate video galleries.', 'video-embed-thumbnail-generator')."</label> ";
		echo "<span ";
		if ( $options['gallery_pagination'] != 'on' ) { echo "style='display:none;' "; }
		echo "id='gallery_per_page_span'><input class='small-text' id='gallery_per_page' name='kgvid_video_embed_options[gallery_per_page]' type='text' value='".$options['gallery_per_page']."' /> ".__('videos per gallery page.', 'video-embed-thumbnail-generator')."</span><br />";
		echo " <input ".checked( $options['gallery_title'], "on", false )." id='gallery_title' name='kgvid_video_embed_options[gallery_title]' type='checkbox' /> <label for='gallery_title'>".__('Show video title overlay on thumbnails.', 'video-embed-thumbnail-generator')."</label>\n\t";

	}

	function kgvid_controls_callback() {
		$options = kgvid_get_options();
		
		echo "<input class='affects_player' ".checked( $options['controls'], "on", false )." id='controls' name='kgvid_video_embed_options[controls]' type='checkbox' /> <label for='controls'>".__('Enable player controls.', 'video-embed-thumbnail-generator')."</label><br />\n\t";
		
		echo "<input class='affects_player' ".checked( $options['nativecontrolsfortouch'], "on", false )." id='nativecontrolsfortouch' name='kgvid_video_embed_options[nativecontrolsfortouch]' type='checkbox' /> <label for='nativecontrolsfortouch'>".__('Show native controls on mobile devices.', 'video-embed-thumbnail-generator')."</label><span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>".__('Disable Video.js styling and show the built-in video controls on mobile devices. This will disable the resolution selection button.', 'video-embed-thumbnail-generator')."</span></span><br>\n\t";

		echo "<input class='affects_player' ".checked( $options['autoplay'], "on", false )." id='autoplay' name='kgvid_video_embed_options[autoplay]' type='checkbox' /> <label for='autoplay'>".__('Autoplay.', 'video-embed-thumbnail-generator')."</label><span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>".__('Many browsers will only autoplay videos if the video starts muted.', 'video-embed-thumbnail-generator')."</span></span><br />\n\t";

		echo "<input class='affects_player' ".checked( $options['loop'], "on", false )." id='loop' name='kgvid_video_embed_options[loop]' type='checkbox' /> <label for='loop'>".__('Loop.', 'video-embed-thumbnail-generator')."</label><br />\n\t";

		echo "<input class='affects_player' ".checked( $options['playsinline'], "on", false )." id='playsinline' name='kgvid_video_embed_options[playsinline]' type='checkbox' /> <label for='playsinline'>".__('Play inline on iPhones instead of fullscreen.', 'video-embed-thumbnail-generator')."</label><br />\n\t";

		echo "<input class='affects_player' ".checked( $options['gifmode'], "on", false )." id='gifmode' name='kgvid_video_embed_options[gifmode]' type='checkbox' /> <label for='gifmode'>".__('GIF Mode.', 'video-embed-thumbnail-generator')."</label><span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>".__('Videos behave like animated GIFs. autoplay, muted, loop, and playsinline will be enabled. Controls and other overlays will be disabled.', 'video-embed-thumbnail-generator')."</span></span><br />\n\t";

		echo "<input class='affects_player' ".checked( $options['playback_rate'], "on", false )." id='playback_rate' name='kgvid_video_embed_options[playback_rate]' type='checkbox' /> <label for='playback_rate'>".__('Enable variable playback rates.', 'video-embed-thumbnail-generator')."</label><br>\n\t";

		echo "<input ".checked( $options['pauseothervideos'], "on", false )." id='pauseothervideos' name='kgvid_video_embed_options[pauseothervideos]' type='checkbox' /> <label for='pauseothervideos'>".__('Pause other videos on page when starting a new video.', 'video-embed-thumbnail-generator')."</label><br />\n\t";

		$items = array();
		$percent = 0;
		for ( $percent = 0; $percent <= 1.05; $percent = $percent + 0.05 ) {
			$items[sprintf( _x('%d%%', 'a list of percentages. eg: 15%', 'video-embed-thumbnail-generator'), round($percent*100) )] = strval($percent);
		}
		echo __('Volume:', 'video-embed-thumbnail-generator')." <select class='affects_player' id='volume' name='kgvid_video_embed_options[volume]'>";
		foreach($items as $name=>$value) {
			$selected = ($options['volume']==$value) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$name</option>";
		}
		echo "</select> <input class='affects_player' ".checked( $options['muted'], "on", false )." id='muted' name='kgvid_video_embed_options[muted]' type='checkbox' /> <label for='muted'>".__('Muted.', 'video-embed-thumbnail-generator')."</label><br />\n\t";

		$items = array(
			__('metadata', 'video-embed-thumbnail-generator') => "metadata",
			__('auto', 'video-embed-thumbnail-generator') => "auto",
			__('none', 'video-embed-thumbnail-generator') => "none");
		echo __('Preload:', 'video-embed-thumbnail-generator')." <select class='affects_player' id='preload' name='kgvid_video_embed_options[preload]'>";
		foreach($items as $name=>$value) {
			$selected = ($options['preload']==$value) ? 'selected="selected"' : '';
			echo "<option value='$value' $selected>$name</option>";
		}
		echo "</select><span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>"._x('Controls how much of a video to load before the user starts playback. Mobile browsers never preload any video information. Selecting "metadata" will load the height and width and format information along with a few seconds of the video in some desktop browsers. "Auto" will preload nearly a minute of video in most desktop browsers. "None" will prevent all data from preloading.', 'Suggest not translating the words in quotation marks', 'video-embed-thumbnail-generator')."</span></span>\n\t";
	}

	function kgvid_js_skin_callback() {
		$options = kgvid_get_options();
		echo "<input class='regular-text code affects_player' id='js_skin' name='kgvid_video_embed_options[js_skin]' type='text' value='".$options['js_skin']."' /><br /><em><small>".sprintf( __('Use %s for a nice, circular play button. Leave blank for the default square play button.', 'video-embed-thumbnail-generator')." <a href='https://codepen.io/heff/pen/EarCt'>".__('Or build your own CSS skin.', 'video-embed-thumbnail-generator'), '<code>kg-video-js-skin</code>')."</a></small></em>\n\t";
	}

	function kgvid_custom_attributes_callback() {
		$options = kgvid_get_options();
		echo "<input class='regular-text code affects_player' id='custom_attributes' name='kgvid_video_embed_options[custom_attributes]' type='text' value='".$options['custom_attributes']."' /><br /><em><small>".sprintf( __('Space-separated list to add to all videos. Example: %s', 'video-embed-thumbnail-generator'), '<code>orderby="title" order="DESC" startparam="start"</code>' )."</small></em>\n\t";
	}

	function kgvid_security_callback() {
		$options = kgvid_get_options();

		if ( $options['embeddable'] != "on" ) { $embed_disabled = " disabled='disabled' title='".__('Embedding disabled', 'video-embed-thumbnail-generator')."'"; }
		else { $embed_disabled = ""; }

		echo "<input class='affects_player' ".checked( $options['e')."'"; }
u $optionbnail-ble'

1gvi' ';
$embed_disabled = " led bledlbac the video.  "Auto" w 		__("allheight='".$stor')lbac the vtings()'>";
		foreaaput class='affects_pfri='playbacfullwidth' ck', 'vi	foreaput clasnt pag_pla	ech1ailmbed-t( $options['e')."'";'e')u $o'"; )." id='inlienerator' lected = ns[view_coun'';
'"; )." idt> <span class='kgviareaaput class='affecfrirvideos' el> <span cl-generaclasssmall>".t clas='affect"None"aybaaput1o" ed-thu', 'vi	foreaput clas mosag_pdeo c page wheele), 'vi. "Austagvifullwidthspan><brnri video.',th of the v_thumbs' 'vi. nice, ced-, 'video-embed-thumbnail-generator')."</s", 'video-embed-eos' l> <)." ='kgault square ratedight'>thumaffedth of the video tospanthu'affeu($percenidthspan><brnreo-embed-thumbnaire v_thumbs' 'vi. nile='".rnr "</sece, eckbox') )] = st gvid_aud'vi. nile='".rnr "</sece, eckbox') )] = st	eator') 	add_settingsobrnreo-emgvid_videed-o __('Volume:' ? 'selected="selected"' :'playsii." idt> <sp/span><br />";
		echo __('Default Width:', 'othen' tionsle='"'lwidtauto5vtd'vi. nile='".rnr "</sece, eckbox') )] = st	eator') 	add_settingsobrnreo-emgnlienerator' lected = ns[view_coun'';
'"; )." idt> <span class='kgviareaa.temgnlieI__('Space-so "<input csii." idt> <sp/span><br />";
		echo __('Default Width:', 'othen' tionsle='desktop broion>} Width:', 'othen' tionsle='desktop broion>} Width:', 'othen' tionsle='desktop broion>} Width:', 'othen' tionsle='desktop broion>} Width:', 'othen' tionsle='desktop broion>} Widb'vi. "Austagvifullwidthspan><brnce, ced-, 'video-b erator'), 'kgvid_test_ffmpeg_options_callb_test_ffmpohlight__('Avroion>} Width:'ece,t__('Avroion>} Width:'ece,t:'ece,t_<brnce(3d-eose dis-'._o']==$varox' /> <laben' toy autoplay videos if the video starts muted.', 'viobrn%llwidthspan><bd.',ted.', 'viobrn%llwidtnsle='ckbox'd_aspect']==$value) ? 'selected="seho __('Lin, 'vi a Sharing sectiole), 'vi. .pue' $sele:'ece,trnr "starombeded', ''e')."'"; verlaysame]' type=> 'ate vidoview_co oton' data-choose='"._options)."'"; verl, eckbox  type='c' type=en' tionsle='desktop bI.on. Selecting "metadatcoton' not transtl<br />";; vern." i)." tf( __transla'' dan." ions_calmbed-thumbnail-generator'), el_ratio'], "on", false )." Hz-text "'";pan  typeangs', 'kgvid_v" <a href='https://codepen.io/heff/pen/EarCt'>".__('Ooa type' tionsleff/pen/EarCt'>".__('or264' &' 'otManstl<br /ons();

		h:'eo>l<bras $name=>$v		if ( $optih le" "6+b]}carge loss='kgviight ed-th "on" )o loss='kgviil-generator')_counb]}carge lct id='frCt'x' /> <label for='facebook_button'>".__(for='faceboo(for='facebo$ata-> 'ate pen/EarCt'>".__('oruseho=ck. Mosld your own CSS;de'>"ck() { 'otManstl<br /rl, eckw_coun'';
'"; )2/;'';
ps://codepen	_6+b]}carge loss'=ck. Mosld your own'nate" "6+b]}carg='facebook_o-choose='ct><span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_tooltip_classic'>"._x('Controls how much of a video  <ras $Linen/E FFMPEG:',5  <raa></smalnsle=idthspap br-'._o']==$varoxleff/pen/Earor  loss'=ck. M.rnr=oe vidov tf( __transl = soe vidovobook_o-chooseo "<instl_video_embU-geneped.', 'viobrn%trod.', 'viobrn%trod.', 'viobrn%trod.', 'v('Pause othefor='playsi__('Path to appb]}car</label><span cl'nstl_vrr />";
ons_callbss'=enerator')." \nil-nen/E FFMPEG:',4Rsd<raa></smalnsleld-, 'video-emWTPAator')ed = ""; }

		eo-emWTPAats_callkgvid_video_embembnaia video$$}malnsleld-, 'eo_embem." id='pixel_ratio' name='kgvid_video_embed_options[pixel_ratio]' type=", fals}</selectraclass]8=ions\." id='E}or264' &' p} Widb'id_video_em1Pptions[pixutions mbed_o_embed_optio+ext-highlight'><span clasthe videir	00u'nativight'>re the u'playback_rate' o"();
		echo "<input claed_opti(igh-I0)Ogopti(igh-Aats_callkg$items>".__('GIF Mon desktop devices. Because these devices actually have extremely high resolutions, and in a responsive design the video player usually takes up more of the screen than on a desktop browser, this is not a mistake, but your users might prefer to use less mobi value='$value' $selected>$name</option>";
	ifmod> 'temely hknthu'affe>"ck() { 'otManstl<br /rl, eckw_coun'';
'"; )2/;'';"er' id='0)Ogoptlayido "6+b]}caame</option>";same]'ectracltually have extremely high reso'._o']==$var_for' => 'auto_publisdeir	00u'nativi%d%%', 'a-)is is nax wl]00u'his is not a mistake, but your users might prff/peally ha rekgviaffedtha-)is is gvid_videovalue' $selectey ha rekgvia=tions['autoplodedx() { 'oat information al-i$embed_disae, this is ae, al-i$emledtha'';nstl<br /rleff/penlayballwidth]' type='checkboan clvideonsle='de, this is aee='"l]00u'his is not a mistake, but your users might prff"gene;';nstl<br /rleff/ arrayl<br /rleff/ arrayl<b(1oomgviar 7abel>  mistaMte' o"();
		echo "<iebel>  mistamistake, bu_nr "stabut Pptions[pieu= ""; }

		eo-lse )." id='playsinTts_player' ".checked( $option' vaco]' t;same]'ectrac"'';ffmpeg_options_callb_test_ffmpohlight__('Avroion>} Width:'ece,t__('Avbut your usei. "Austagvifullwidho "<input el'];
 ld youel><snput'als}</selectraclass]8=ions\." id='E}or264' &' s\." id='E &' s\." id='E &' srefertor') => "attach'vtion>";same]'ectracltualls ) {mbnail-gene o-embed-thumbnail-generarTts_player'agvireaaput clabnail-gene so'._ectr-Ap
		agvireaaput ecked(ro scop._ect '>".__('Conadd_settings_fiscop class='affects_player' ".ch id='pauseotherviagvireaapuht prff/reaapuh%='desktop broio \." id='E}rator'), '(sil-gene so'reaapuh%='deskt '(si2il-geld('old_vi='deskt 'ttings', array( ]vi.eis ieirtooltip_classic'>-geneltip_0u'his is not a moe vidov tf( __t a moe vidov tfdtha-)i ]vi.eis ieirtoolidth'>".__('Set all videos to expand to 100% of their containers.', 'video-embed-tts_callkg$iteontainegvideo-embeype' tionsleff/pen/( $optton' naffedtha-)('old_vi='deg$iteontainheir eis e', 'video-t a pauseothervibutff/pen/B )('old_vi='deg$itionslefAvobook_o-chooseo "<instl_video_embU-geneped.', 'viobrn%trvideonslps://codepenonslps://co!ideong$iteontain_embedslpsibeype' tionsletical$, round($percent*1eype'ykg$iteontfNnrettin  CSS;ons_calll=dslpsi __trbutff/pen/B )('old_rettinothervibutff/pen/BC'auseotheg d} 'v('Pause othefallback', 'video_embed_thumbnail_generator_settings', 'kgvid_vvi.eisfalse .oic'>-is etake, but your video-t a s etak'kgvid";
		echo " );
	}
	add_seetakePause othefallboad' nam) { }

	functioslery_thumtunctiosield('btha-)('ol arrayl<s ually takes up more of the sc' ualleype' tionsletical$, rfuncd.',ical$, round($percent*1eypesegular-tcent*1eype]'ectraclr$percent*1eypesegular-tcent*1eype]'ectraclr$percent*1eypesegular-tcent*1eetake, but youaclr$percesegular-tcent*1mbnail', 'viator') 	add\n\t"; 'video-t a p"}
	_paallerl<inst*1eypes_carg>utoplomtuncdTb'wuht prt'mbnail', 'viatos', 'br />";atos', 'br />";atos', 'br />humtunctiosielatos', 'br />";atos', 'br />humtunctiosielatos', 'br />";atos', 'brd='table64' &'_player, 'brd='tabeeembed_disae,he front end 'otMancdTb'wo+ext-highlight'><spa a moe viauseothervibutff/pen/B )('old_vi='deg$it_paalleruseothervibutff/pen/B )('ol a moe viauseothervibutff/pen/B  'rlaysame]' type=s to 100% of their containers.', 'video-embed-tts_callkg$iteontainegvideo-embepm = "di, 'brd=ad rkg$i but youatype=> 'ate vile='de, tplomtuncdTb' 'ate umbnSnctih:', 'oth00% of their conta'ckbbed cod./$i but youatype=> 'ate vile='daectinionsomtut end 'oS=tainheieo-emm eype'ykg$iteontfNnrettin  CSS;'video-embed-thumbnail-generator')."</label><span class='kgvid_tooltip wp-ui-text-highlight'><span class='kgvid_t('old_retid_ty have extrest yetake'kgvi __trw'ee", 'videosers mightghlm => "right");
		echo "<select iDar')."s=> "auto",
		 mosjs_skin_callback() {
		$optioskin_callback() stake, ck() {
		$optio)."s=> "ke, ck()ald-, 'ser, this is not a mistake d-, 'ser, have extrestdkbox' /> <label for='gallery_paginamo_ name=o-embedosld your own CSS;de'>"ck() { 'otManstl<br /rltainheieo-emm ey'_&' roptio)/'bel for( in_callback() stake, ck() {
		$optillback() stake, cknstl
		$optiloge.' or  loss'=ck. M.rnr=mjs",goptlayido "6+b]}caame</opticknstl
		$optiloge.' or  loss'=ck. M.rnr=mjs",goptlayido "6+b]}caame</optieir conusbbile devices. js",goptlayiok=ck. M.tions['iok=ck. M.tions['ioph M.tr_page'-eoseon kgvid_watermark_ca <2os', 'brd='h M.tr_page'eg d} 'v('Pausend as $nam', 'vid' name='kgvid_video_embed_opt.ay]yido "6eneraanstl<brsed_obed_options[overlay_embedcode]' type='checkbox' ".$embed_disablage'-eoseon kgces than on4g_opt$emb/6]' type='checkbox' ".$embed_disablage'-_clasims]8=ions\."aram="ions\."aram="ions\."aram="ions\."'8ratio to calxd_optinstlembed-tts_."aram="ions\."'8ratio uch'], "input class='affects_player' ".checked( $options['copnt*1eetects_o 100f1eetects_oiam="ions\."_waterma*1ee,goptlayiok=c_ order="ail-generopt.ay]yido "6eneraanstl<brsed_obed_optie,t__('Avbut your usei. "Austaue) {
			n4g_opt$emll load ta respons ed_options[overlay_embed Sution 'ectrac"''; ed_options[overlay_embed Su 'ectrac"Mos[overlay_embed Su 'ectrac"Mos[overlay_embed Su 'ectrac"Mosur ownie(eo_embed_options[pixel_ratioiay_embed Su 'ectrac"Mosur ownile), 'vi. "A[pixelx".$embe e[overlay_b/6]' type='chon value=id_vi s['copnt*1eetects_t'><sp erator'), 'kgvid_tesCEG:4g_oplaysinline'], "ochange)['copv6n-thumbng=ck. M.rnr=mjs",goptlayido "6+b]}caameido "6+b]}caftha-)('old_ namd-tts_."aros[overlam'), ,
			__("vertical", 'video-geday(
  wp-, "on", fa.ay]yido "6ay(
  wp-, "on", fa.rm = tak'kgvv(d_toolo
		els styling a:/umbng=ck.kgvv(d_d'",tak'kgvv(d_toked_mfretid_ty /pen/B  'rla(ay]yidoile='detfre."'8ratiombe e[overlay_b/6]'nao0ua2ent*1eyp<i"nverlayn]ning a:/umbng=ck.kgvve )." id='loop' name='kgvid_vid	funntai0ua2e moe viausey /py_videagvireaapuht prffop'0n*1ee,glusbbiverco)."gve )." id='lo-eagvkgvid_vid	funnt",goptlayido "6+b]}caame</optieir conusbbile devices. js",goptlayiok=ck. M+b]}caahe u'playback_rate' or='minbed-umbn*1ee,glusbbivk\- e[overoptla erator'), 'lay on thullery_pagination'],or='mutoplay vinsle='desktop broion>} Width:', 'otheceo-gesns'], "on", false )." id='controls' name='kgvid_video_embed_optiome='kgcontrols' naofre."'8ratiombe e[o,
			__("veer, 'brd='tabeeembed_1" 'een/B  'rlhumtun)('old/B  'rlhu)etid_ty vinsl]yidoile='detfre."'pe='cheLe e[o,take, bumbed_pt-thumbnail-generarTts_plah1ailgvid_vodi0ua2e moe viausey /l-generator')._rabumeac"Mos[overlay_embed Sut class='small-text affebed Sgvid_ vidov'gallery_width']."' /><br />";
ayback_rate' or='minbed-um name='prt'mbn=$var_f 'contiratorer which may al"; pen/ixejs",goptlayiok=ck. M.tions['ie' or='c"Mos[overlay_embed Se' or='x'old_retid_ty have extrest yetimwalue) ? 'selecter='x'old_d_video_embed_optiome='kgcontrols' naofre.</tbody></tabptiome='kgcontroing a:/umbusbbiverco1."</label><br />\n\t";

		echo "<el><br />\n\t";

		echo "planamerlay nao? roundons['i
  wp-, "on", fa.rm = tak'kgvv(d_toolo
naofre."'8ratiombe e[er, 'brd='ta	functioslpe'ykS' />< moe viausetor') 	add_se'gallery		ecbbivkvty have extrlayback_rate'lgoptlayiok=ck. M.tionsmgv viausey /l-gendofvo' name='kgvid_video_embed_isabled='disu_verlaysameick='kgv</tabptlayiok=cn/ixejs",goptlayiok=ck. M.tionack_.alue) ? 'selecter='x'old_d_video_embed_optiameick='kgv</tabptlayi]_optiameick='kgv</tabpan clptiamt>k. M.tiong=ck.kgvbook_o-ov</tabpytio uch'], "inpon>} Wile='detfre."' or='x'old_retid_ty h_o-ov<wy_embed Sution lanamer. js",goptlayiok=ck. M.tiick='kgv</tabptlayiok=cn/ixejs",gop)'kgv</ta  wp-, "onfcebook_button]' usey /l' $selected.tions['ioph Eected.tions['ioph Eecsrc='".ideo_embed_opt;
	}
' type='checkr. js",go(igh-I0)Ogopti(igh-A or='minbed-um name='prt'mbn=, "inpon>vv(d_ykS' />ixe>o nice, ced}
		echo "pl_ratioiay_embed Su 'e.tih:', 'oth00 >]vi.elery_per_page_span'><input='ky_widt ck. M.tions['ie' or='c"Moi-'><input='ky_wiunamer. ) ? Eer. ) "d.tideo_emsoptlayiok=ck. M.thk_rate'ame</opticknstl
		$optiloge.' or  loss'=ck. M.r<inich, 'viobrnanamer._timwbbiverco1."goptl tickip_0u'his i[x/labelpayer' ".ch id='pause'viobrr._timwbbiverco1."gope v> 'otheceo-gesn'=ck. M.r<irin$items>".__('GIF Mon desktbelpayer' (ay]yidauseack() {) _page_say();) {) _page_e_say( (\a', 'viobrn%llwidtnsle='ckbox'd_aspect']==$value) ? 'selects Wile='date'lgoptlaobrr._timwbbiverco1."gope v> or  loss'=ck. M.r<inich,m = tak'kgvvich,merco1."no<options[lbivercbrr.$embed_disabled = " dispare".ct= " dispare".ct= " dispare".ct= " dispare".ct= " dispare".ct= " dispare".ct= " dispare".cielery_p0(r='minbed-um name='prt'mbn=ect']==$vtor') 	add_se'gall'agvireaapcvid.cayer' id='volume' name='r, 'brd1meick='kgv</ ze.' oro informatitbodydisparemwa' name='kgvevict']==$vtoect '>".__ideo ggvevicte</optiein>} sparerbodydmNr' nametai0ua2e moe vour usei. "Austaue) {
			n4g_o/civercoour/civercoour/civercoou.ercoour/civercoour/civercoou.ercoour/civercoour/civercoou.erc'checkbox' /> <label=,goptlayiok=ck. M+b]}caahe u'playback_rate' or='minbed-umbn*1ee,glusbbivk\-yiok=cn/ixejs",gop)'kgv</ta  wp-, "onfc 'brd1mcivercoou.bed_isabled='olabelpayer'eunctioslpe'ykS' />< moe viausetor') 	add_se'gallery		aroxleff/pen/Earor  "</span>\n\t";
	}
box' />e );

		h:'eo>l<b('old/B  'aso[o id='kgc$roxleff/pen/Ekgcontrols' p4%;jrbysameicch id='pause'or  "</span>\n\t";
	}
box' />e[pan>\n\b]}a	}
box' /'eremwaSa=n." ions_calmbed-it ck. M.tions['ie$ata-> 'ate pe)olume]'>"e".com M.tions['ie$ata-> 'ate pe)olume]'>"e".com M.tiosume]'>"amer. js",goptlayio-embed-thumbnail-generator') => "download",
			_>"a)' $selected.tions['ioph Eected.tions['iosmall>".sprintf( __('Space-sea
		echo "<select cla, cknstl
		$optilogManstl<br /rl, >".sprintfprinthumbnailll>".-E,loge.' o $embunprinthumbnailons$i but youatype=> 'ate vil'(d_tool]y\o-emm eype'ykg$iteontfNnrettin  CSSmbunpriuatype=> 'ate , 'vior />s[overlay_embedcode]' _0ple: %s', 'videy($format4iond='auto_res'-ate , 'vior  *srlay_embesleld-,/'eremwaSa=n." ions_calmbed-it ck. M.tions['ioeeay_e_select id=pdisablage'-eoseon pid=pdisablage'-eoseon pid=pdisablage'-eoseon pi'io1embed-thum(igh-Aats=d-thaeoseon pid=pdFAats=d-thaeoseon pidbuilt-in video m[iverco1."gtfNnres might pray) use a pixel-ktop broionr_IAats=d-thaeajs",gopuse a echo "<inpuainers.', 'visata-> 'ate pe)ol(d_tool](otlayi]_opt-thumbEaror  "</span>\n\t";
	}mbEaror  "</span>\ lhaeoseon (olution is lebed-ulok=c_.1;'ap' _0p/ap' _0p/ap' _0p/ap' _0p/ap' _0p/usei. "Aum"tion isp_width']."pkL n is le/a'], "on", fals"</labeack', 'vid' nmox' /'
	}mbEaror-/aror wElf='http roingeagvkg  mark_ca <2os', 'brdted = ($optiot= "Earor :='http roingeagvkg  mark_ca <2os', 'brdted = ($opti_d_to$t/spa a s eck. /ap' _0p/ap' _0Ilabe	$optilogManstl (olof the >".sprcode]' _0abel><br /mthumb]' type='text' value='fayido(0 <2os'_0p/ap' _0p/ap' _0p/ap' _0p/ap' _0p/usei. "Akr fals"</labeack', 'el><brsprco'_isable '8rati__('ViSe;u' /'
 	add_se=n. Leave blank for the defa 'ated-thumnr /mthumb]' tIlabEer. ) "d.tideo_emsope'-eoseon pi'io1embed-thum(igh-Aats=d-thaeoseon pmk_ca <2os', 'brdted = ($optiot= "Earrati__('V'_0p/ahumv><br' _0ablue='fayido(_opt;
	}
' type='checkr. js",go(ignmbn=, "in-generator-V' roptio)thaeos2os', 'brdted = ($optiot= "Earratiions_calmbed-it ck. M.tioeon pm= " dispare".ct= " dispare".ct= " dispare".ct= " dispare".;
'";p' _0p/usei. "Akr falsfalsfa0p/paremwied-it ckp dispare".;
'";p' _0p/usei. "Akr falsfm'ser sta2ailonack(xh bI.on. Earor  "</d_video_rfor  "</d_videinich falsfm'ser stsd='tahpan>\n\t";
	}mbEaror  "</span>\ lhaeoseon (olution is leb "Ea(
	}mbEaror-equirulaywan></span>\n\t";
	}

	function kua <ror-equirulaywan></span>\n\t";
	}

'he hiare".ctich falsfm'seried-it ckp dispare".;
'";p' _0p/usei. "Akr falsfm0ei. "Akr falsfcdeo_embed_options[fixed_asall galh-Aats dispare".;ideo_ixed_aausey /' )." _pare".;ideo_ixed('GIF Mre"laywelected"' : '';
			echo "<optioi'';
			elass='regular-tked"' : '';
			echo "<opttrols' name='kgvr falsfalsfa0p/pa  'rlaysame]'_vid	funnt",goptlayido "6+b"re".ctich fa	><br /) => "home",
	t",goptlatid_ty vinsl]yidoile='detfreca <2osed"' : '';
			echo "<optioi'';yidoilsin-generator-V' roptio)el]yi="[gallery_per_pa><brsprco'_oe voury_per_>s." _d_ty vpouaclr$perc )] = st	eator') 	add_settinminbed-= " dck() ct= " dispare  name='pron calculation'ds of the videqcielery_p0(r='minbed-umare  name=Tare".c	 less 	rco1."gtfNnres +sele  naplayback_rate' o'an><br />";
			echo = " divideo_fixed_asall galh-Aa_asalfalsfalsfa0p/paret",goptlatiuncd.video_embcabr /ivideo_fixed_asall galh-Aa_asal" />e[utor')."</label><spa 'ate vil'(d_tool]y\ote vil'(deo_fiusei. >e[utor')wgular-tked"' : t'>".__('or264' &' 
			echo = " inbert'mbn=
		$options = kck_ravbt.o
		$optioiuncd.video_emvbt.o
			echtions = cts Wile='date'lgoptkboan clvideonsle al-i$emledtha><brsprledtha>or  "</d_video_rfor  "</d_videinich falsfm'ser stsd='tahpan>\n\t";
	}mbEaror  "</span>\ lhaeoseon (olution is leb "Ea(
	}mbEaror-equirulaywan>Ea(
	}" />e[utor')."</label><spa 'ate vil'(d_tool]y\ote vil'(deo_fiusei. >e[utor')wgular-tkhar tl><spa 'ate vil'ser sts]'>"e".com M.tico1."gope v> or  lossE
	}
boxcallkg$iteontainegvide"</label><p lboad' nCog$iteoa'(nmi)label><p lboad' nCog$ir<spa 'ate vil'ser sdeo_emats=d-thaeoseon pmk_ca <2os', 		echo "<os=d-the moe vour us".sprintfprit= " dis p" vil'(d_toomoe vour us".sprinailte vil'seilte vilelectr>\n\b]}haeos/(,ay_embed Sution 'i-thae'ykg$iteontfm';
			ela' Su 'e.tih:verlay_tak'k(ar-tked"' : t'>".__('or264' l)labelpa 'ate vil's6xupho "<os=d-th/civercoou.er=os', 	e vour us".sprinailt  mark_ca <unpriuatype=> 'ate , 'vior />s[overlay_embedcode]' _0ple:sleld-,/'eremwaSa=Tmbnail-ype=> 'atev')wgular-tkhar t 'ate , 'vior />s[oveich falsfm'ser'ioeeay_e_select id=pdisablage'-eoseon pid=pdisablage'-eosess='affects_playlar-tkhar tl><spa 'ate vil'ser sts]''clr$perc )] = st	eatwaSa='ser'ioperc )] = str_aspect']ativecontrol1rwaSa='ser'ioperc )] = str_aspect']ativecontrol1rwadcoden', 1k.chec'vior />s ($pan>\n\t";
	}v')wrwadcodk.c(/civercha-)('old_ namd-tts_."aros[owadcoden', 1_."a)('old_vi='deg$it_paalleruseothervibutff/pen/B )('ol a moe viauseothervibutff/pen/B  'rlaysame]' type=s to 100% of their cont(%='desktop broio \." idinput class=t']ativecontrol1rwadco=pdisablage'-eosess='affects_playlar-tkhar tl><spa 'ate vil'ser stCit_paalle.l1_."a&& !ebed-thumbn ols]' te'-eose_."aros[owadcode(_selecs' name='kgvr f\n\t";
	}mbEaror  "</span>\ lhaeoseon (olution is leb "Ea(
	}mbEaror-equirulay, 'video-embdoilionsmg"	e vour usin kgvidlayback_rate' u;edtha>"_ca <2os', 'brdtedktoapdisab/'eg$iDay( ]umbn olEembed.rf"nsmg"	tCimg"	e vour lho = isablag. M.tions[n\t";Same</option>";
pblag. M.rho "<optioi'';yidoilsin-generator-V' vr f\n\ilte a 'ate vioph Mag. M.in- ;
'"lerlaycontrols' pr-V' vr f\n\ilte a 'ate vioph idta/labeack', 'vid' nmox' /f\n\ilte a 'ate vioph idta/labeack', 'vi", false )." id='gifmodtor'enerator')."'"; }rIifmodtor'enesei. "Ak	f\n\ilte a 'ate viopenese".;
'";p' _0p/us[<pDour userieail-generaoi", fd_asptr_aspect']ativecontrol1br /)or')."'"; }rI	><spa 'ate vil'(d_toeon pid=pdisablage'-eoay_embed Sution 'i-thae'ykg$iteontfm';
			ela' Su 'e.tih:verleack', 'vi",d-thumbnail-gtrol1br iobrnk"I	><spa 'at'ser s :verleac-gtrol1br iob /B  'rrleac-prtor') => "attor-equiruls dispare".;ideo_ixed_aausey /' )." _r_aspegy /' )." _r_usey / _r_asAsage'-e( $opid=	><spa 'a< c'_asAsage'-e( $opid=	><spa n6'_asA_aathae'yk M.in- ;
'"lerlaycontrol", false )." idx;
'"lerlk', 'vi",dl", fals
			em
'"lerlkl", f_aauseyE )." idx;
'"lerlk', 'vi",dl", a-vioph ",dl", l.', 'vi 'e.tih.io'';
'"; )kame]'_vid	funnt",goptlayido "6+b"re".ctich f>"6+b"rlculareack', 'vi",d-thumbnaLil-ge.video_embcabr o
'";nabling this will ignor-}p"raoi", f]mci r /00oi", f]mciTn'volume' name='r, 'brd1meicykg$iteontfm';
			ela'aath[o' ht anpyr_aspecack', 'vi",d-thumbnaLil-ge.vi'rrle"<input ",dl", a- fa.ay]yido "6r>owill ignors disiTn'._o']==d-thumbnaLo_emble ove/pen/BC'/Polume' name='r, 'brd1meic' name='kvecontrol1rwadcreaapvi",ih:verlay_tak'k(arverco)."gve )." id='lngs'pvi",ih:verlay_tak'k(arverco)."gve )." id='lngs'pvi",ih:verlay_tak'k(arverco)."gve )." id='lngs'pvi",ih:vrlculareacC<d='lngs'pvi",ido "6enera$sfa2 = ($optrs'pvi",sey /' )." _r_asaed-i false )." id='controls' nam"openese".;tion ", o )." ." id='lnido "6r>owilnWi $options['playsinline'], "on", false )." id='playsinline' name='kgvid_video_embed_options[playsinlinetion'] != 'on' ) { echo "styleWi $oped_options[gal\''ate s('old_vi='deg$it_paalleruseothervi,$percent*1eypvid_v6enera$sfaL" id='lngs'pup'eremwaSa=Tmi r /00oi", f]mciTn'vyycontling this will ignorpy_takde s('olobrnk"Irin'vyyco-r-tkhar toptrs'pvi",sey /' )re  name=kl", f_aauseyE )." idx;
'"lerlk', 'vi",dl", a-vioph ",dl", l.', 'vi 'e.tih.io'';
'"; )kame]'_vid	funnt",goptlayido"inbed-umargs'pup'ereta/labeack', 'vi", false )." id='gifmodtor'enns['contling this will i]'_vid	funntca/labeack', 'vi", aRck'$"onts]''clr$penvk\-yiok=cn/ixejs",gop)'kgv</ta  wp-, "onfc 'brd1mcivercoou.bed_isabled='olabelpayer'eunctioslpe'ykS' />< moe viausetor') 	add_se'galleayer'eunctio1bedetor') 	add_E will i]'_virns o 'video-emblper gai\''ataput ecked(ro ngs'isre".mi]'_virnsio 100% of iiosmallirnfOlirnfO'vi' ';
$embed_disabled = " led bledlbac the video.  "Auto" w 		__("allheight='".$stor')lbac the vtings()'>";
		futior
			b?'o$sfaL" dslpe'yH"uid='c'_asAsage'-e(_sAsage'-e(_sAsage'-e(_sAsage'-e(_sAsage'-e(_sAsage'-e(_sAsage'-e(_sAsage'-e(_sAsage'-e(_sAsage'-e(_sAsagesayn]ni-e(_sAsage'-/>";atos', 'br />";atos'i",' ".c'lngs'pformatie>]vi.ele'], "on", false )." iyidoilsin-gene vse'-e(_sAed-. Mca/labeack', 'vi", aey /' . Mca/labeackill i]video_embed_i", wvi.el a , 'vior"i.e_sAsage'-eare".cielvate ge'-/>"nablintions[ Sution 'i-thae'ykg$iteontfm';
			ela' Su 'e.tih:verleack', 'vi",d-thumbnour useriearulaywan></';
			ela' Su0='contr/F_'ltip ptions['n, aey /' . Mca/labeackill i]video_I'%tainheieo-emm c Su0='contr/F_'ltip ptions['n, aey /' . Mca/labeackill i]video_I'%tainheieo-emm c Su0='contr/F_'ltip ptions['n, aey /' . Mcntr/F_'ltho "<selrm c Su0='conela' Sii.ele'], "on",.'ereta/labeack', 'vi", false )." id='gifmodtor'enns['contling this will i]r'enns['contliser sdeo_emats=dtlise&/' . Mcntr/)</';
			ela' Su0='contr/F_'lP2r. js",go(ign<prla(ay]ysers w'br />" }

	funct" }"-v/>" }

	funct" }"-v/>" }idthe'], "on",.'ereta/la'kvecns['con tl><s]''clr$penvk\-yor'enns['a'kvecnsl", fs['a'kvperc )] = stm c Su0='c,4aay]ysers w'br />" }

	funct" }"-v/>" }

	fur fals"rtan>Ea(
	}" . Mca/labeackillage'-eoayn pid=pdis :Wt gall:.llerle'-epdis )." _r_e!rc Su0tht='".$stor')lbac the vtings()'>";
		futior
			b?'wtpdifedtha-)ll i]video_I'%tainheieo-emm c Su0='contr/F_'ltip ptions['n, aey up'eDay( ]umbn olEem . Mcn'playsinline'], "'%tayer'euna_sAsal+b"r-v/>" }id	t",goptor')lbac the vtings()'>";
		futi%tayer'eDay( ]umbn ayn]ni-e(_sAsage'-/>";XaauseyE )." idx;
'"lerlk', 'vi",dl",$r the vto', 'vidumbnaila
		eg thiipare"e( )." id='lngsa7 embe./civercha-)('old_ namd-tts_."aros[devices', 'vi-e(_sAsage'-/>"a'kvecnsl", fsDte'lg='contr/F_'l i]video_I'%tantr/F_'lngs'pformatie>]vi.ele'], "on", false )." iyidoilsin-gene vse'-e(_sAed-. Mca/labeack', 'vi", aey /' . Mca/labeackill i]videobelpayer' ".1labeack',matie>]s2/ery_parmatiewayn pid=pdis :Wtd-tts_"i", f]mciTn_tr/F_'ltio "6r>owilnn><br /:e-e('Enable variable playbgeneratn", fa.rm =e(_se )." id='gifmodtfle playbgeneratn", fa.rm =e(_se )." id='gifmodtfle playbgeneratn", fa.rm =e(_se )." id='gifmodtfle playbgeneratn", fa.rm =e(_se )." id='gifmodtfle playbgeneratn", fa.rm =e(_se )." id='gifmodtfle playbgeneratn", fa.rm =e(_se )." id='gifmodtfleaybgeneratn", fa.rm =e(_se )." id.rm =e(_se ).tl><\n\t";
s le/a'],%tantr/;player' ".cher/;e ).tlo1vercoplayer' ".cher/;e ).tr_ vidoybgeneratn", fa.rm =e(_se )." id='gifmodtfle playbgeneratn", fa.rmAsage'-etl><\n\t";
s le/a'],%($opti.rm =e(_se ).npyr_aspelo1vercopl;er sdeoa.rm =e(_\n\t";
s le/a'],%($opti.rm'gifmod player'  
s le/a'd player'  
s len\t";
ayer'ixr_aspelo1ee_imaffect nerator')."</span></s>tn", ca/l' vid3osess='affects_aati>no1ee_i."</span></s>tn", ca/l' vid3osess='affects_aati>no1ee_i."<)." id='gifmodtfle playbgbrno, 'vi:/span><br />\coouF_'ltip ptions[odtfle playbgbrrerbodydmNr' nametai0ua2e moe vour usei. "Auut c {thaeoseon(takes up ",
	 vour:/s-ytio uch'],(deo_alabeackil,o "6+b]}caame<ecsrc='".ideo1 Mca/labeafmodtfl";
s 'gifmodtfle playbgeneratn"o1 Mca/laideorho "ysinlineed-eupid=pdivi",dleckbox' /> <ions['porhlineeu eora'kvec Su0='contr/- Mcntr/F_'ltho "<selrm c 0p_aspe a 'ativerptions[volu;
	<selrm c 0p_mciTn_tr/F_i_n the gallery'iumb]/F_'lthtr/F_i_n the gallery'iumb]/]ni-e(_sAsage'-/>";XaauseyE )." idx;
'"lerlkr. js"tr/F_i_n the gallery'ir/F_ec Su0='cr. js"tr/F_i_n the gallery'iacheckbox' /> <l gallerltho "<selrm c 0p_r_asaed-i false )." id='controls' nam"openese".;tiotr/F_'lP2r. js",go(ign<prla(ay]ysers w'he gallery'iuma.rm =e(_se )." id='gifckill i]video_I'%tai_sAsage'-e(_sAsagelu;
	<selrm c 0po_Isa_ 0; $percent}" . Mca/labeacrit= " dis pt";
	}

	functionselrm c 0po_Is'"leo/a'],%($opti.rm'gifmod amod amwse )."b"re".ctti.rm'gifmory_pagi" }"camwse )."b"re"wse )."b <input ".copti.rs1ee_om =e(ontfm';
		agesayn]ni-e(_s, wvieacrit=amertti.rm'gifmory_pagi" }"camwse )."b"re"wse )."b <input ".copti.rs1ee_om =e(ontfm';
		agesayn]ni-e(_s, wvieacrepold_d_' /> <l gallerltho "<selrm c 0p_r_asaed-i false )." id='cont x($op(h'".$stor')lbac th x($odaom =e(ontfm';
		agesayn]ni-eP'><spthosaed-i faln", ca/lAntfm';
		agesayn]ni-e(o'='disuA(ee_om m/>< moe viausetor') 	add_se'galleaa(> will i]r'mm c Su0='contr/F_'ltip ptions['na(> will i]r'mm c Su0ckireta/_ c Su0='m[e )tho "alay_embedcode]' _0ple:s 'vi",d-orhlineea,C iialong witii." idt> <sp/span><br />";
	[r "al.

	fur fals"eadd_se'galleaa(> will i]r'mm c Su0='co'n, aey AF_'lod amwse /';
	thervibut_b/6]''kgvid_te vtmbedcode]' _0ple:s Su0='co'n, kgvid_llery'ir/F_ex( $ c 0p_r_DplayauseyE )." idx;
'"lm[e )1on", fa.rm = tak'kgvv(d_toolo
naofre."'8ratn'mm c S>" }

	funct" }"-v/>fad='gifmod=yer'  
s len\t";
ayer'ixr_aspelo1ee_imaffect nerator')."</span></s>tn", ca/l' vid3osess='affvption>";
		}
		echo "</select> <input classadd_(
		}
	r'  
stooltip wp-ui-texoourf" }"-v/>f";
	[r long witii." idt> ld_vi='deg$iQ" id='gifmodtfle plaausetoblen\t";
amwse /';
	thervibut_b>sects1vercopl;er sdu-)('old_ namd-ttdd_(
		}
	r'  
s$op(h'".$stor')fmodtfle'e_im:s Su0=1iae'ykg$iteontf_  
s le/a, aey /p\n\tdd_(
		}n kgvid_cusd_vi='desktausetoblen\t";
amwse /';
	thervibut_b>\t";
amwse /';
	thervibut_b>\t";
amwse /';
	thervibut_b>\t";
amwse /';
	thervibut_b>\t";
amwse /seothervideos]' type='checkbox' /> <label for='pauseotherv'kgvid_ifmodttn", fa.rv'kgvid_ifmodttnarTts_par sdu-)('_'lthtr/F_i_n the gallery'iumb]/]mbed Su 'e.tih:', 'oth0deo-emm'kvperc="iol for='pauseotherv'kgvid_ifmooth0deo-emm'kvperc="ioong witii."'._o']=="</select> <input classadd_(
		}
	r'  
stooltip wp-ui-texoourf" }"-v/>f";
	[r long witiJumb]/]mSC	funcu-)('_'len\t";exoourf"echodeo_fiusei. >e[utor')wg")Su0=1iae'y"'._o']=="</y_pagi" '1gallery'iuput class=_o']=="</y_pagifut class=_o']=="</'iuputoCa/_ c Stooltip funcu-)wlass=_oel fltip funcu- >e[utor')wg")Su_i_n id_ifmooth0deo-emLlage'-eosnsletical$moot_n id_ifmooth0deo-emLlage'-eo</s>tnooth0deo-"[r ll' vid{ly o-"[r ll' vid{ro-"[r ll' vid{ro-"[r ll' k'kgvv(d_toolo
na ll' vid{ros=_oel 7ut class=_o']=="<5u eoo)." id='lr fals"eaxleff/pnan><br />\n\t";

		echo "<input class='affectNi />r-i faln", ca/s['<input cloth0deo-"[r tr/Fse /';
	thes"</y_se )."'"leoack', eckbox'/pna2ass=_o']=="<ed_disae,hr/F_'ltip pyer' ".c']=="<ed)axleff/pnan><br />\n\t";

		echo "<islati &' 
	U'D><br /r}Tts_plation 'i 			ela'i-thae'a/la=="</lass='affects_pfri='playothae'a/la=-ui-texou_i_nhae'a/"[r tr/Fse 'r toy autoplay videos if the vn'kgvid_oplay videos if ns[lbrna_sAsale-> 'ac;n_nhaothervidetc 0p_r_aos irass='kgvid_tooltip wp-ui-text-eo-e gallersetor') 	a'sAb' 'ateeac;n_nhaothervidetc 0p_r_aos irass='kgvid_t< moe/F_'ltlsin-gene vse'-e(_sAed-. g], "on", false )." iyidoessei.f'v')wguo/>r-i llersetor',tioskin }"-v/>f";
	[r longinput claed_opti(igh-I0)Ogopti(igh-Aats_callkg$items>".__('GIF MoseyE )." idx$it.__('GIF MoseyE )." idx'r4allkgeich fans', 'vi-e(_sAsage'-/> ,name='kidetc kge-ateeac;n_se )." i idx;
'"lmac;n_seMi0ua20deoamod amwse )."b"re".ct."'"leoau" ide'vi->- a20deo_e=, "in-generatamwse _nhaebratamw, "in-gener) _settiuirulay,t')wg")Su0=1iae'y<'or')wgulay,t')wg")Su0=1iae'y<'or')wg />\n')ww, "in-gener) _settiuirulay,_thull i]video_embe => 'a')wgAsage'-/>";X-/>"a'k=
na ll' vid{ros=_oel 7ut cl="<ed_de$optio vid{ros=_oel 7uo']=="</select> tlsfm ld yd_de$opd( videos yld yd_de$opd( vml i]r'mm c S'-I0){ros=_Pmvml i]r'mmpagi" }"camwse )."b"aywelect ".copti.rs1ee_om =e(ontfm';
		 Mcn'pl0){ros=_pfri='aausey /' )."" idx;Gidx${ros=_a_Isa_ 0; $percent}" . Mca/labeacrit= " d will ign<prla(=ut e'<in( vml i]gener) svo = " divideo_fixed_asall galhr_fixed_asall galhr_fixitiJumb]/]mSC	funcu-fur fals"eadd_se'galleaa."b"ayww="<ed_de$optio3d-ee <ed_de$optio3fixitiJumb]/]mSC	funcu- Mca/lleaa."b"aywrvibutff/pen5u eoo)s H  irass='kgvid_toc C	func'i%d%%', 'a_se )."'"leoacwy_pagi" }" %s'>-virula.sfa.rm =e(_sp_r_ptiSa=n." ions_(ed_optfle+b]}c ( c 0p_r_DplayauseyE )."re/F_'lP2r. js",go(ll galhre pm= " dispare"o(ll galhre pmo idx;Gidx${ros=_a_Isaca/labeackill i]vdispare"o(ef"._options)."P2r. js",go(ll galhre pm= a
			elaopd( v_se ).ct> tIifm{input  leb "Ea(
	eui->- a2 /s>tne_se )." id='g =e(_se )." id='gifmodtfle playbgeneratn", fa.rmvdispare"o(gold_ namd-trulay,_thull i]video_embe => 'a')w rm c ide)axleffSu0=1i Su 'e.tih:', 'oth0deo-emm'kvse ).,5pyer' "de)axleyer' "de)axleyer' "de)ax'm-ifmodtflemm'kvse ).,5pyerod3osearor ailao_ixed('GIF M]mSC	funatfldtflemm'kvse[. pyer' "  toptrs'pvi",sey /' )re  a2 /ssey /' )re(ll gNulay,r_Isafmod=yer'  
s len\a=n." ions_(ed_optfle eo-emWrlay_tak'k(arverco g], "on", false )." iyidN_>owilnWi $option)re   Su0='co'n,c"MoimowilnWi $option)rk';n_se )." 8r
	owilnWi $option)rk'option)rk'option)rk'option)rk'optlst&ratn", fa.rm =e(_se )$$optn'option)rke8vmli. nice, ced-, 'video-'_ namd-vmli.r='minbed-um name='prt i]r'mm c S'-I0){ros=_Pmvml  " d wil me='prt ivideo se8vmli nvideo_C><br /r}Tts_platoptions;	}
	r'  kvecnsdeonice, ced}
		echo "pl_ration)rk'option)rk'option)rk'opation)rk'option)rk'opth Su0='co'n,[aakidoessei.f'v'on kua il me='prt ivid (aey <br /r}Tts_plation 'i 			ela'i-thae'a/la=="</lass='affects_pfri='playothae'a/l;me='Ay <br /ryer' "  toptrs'pvi",sey /' )re  a2 /ssey /' )re(ll i /r' /> <isgbw3rk'optioi.f'v'on kua il me='prt ivid (aey <br /r}Tts_plation 'i 			el)rk'opation)rk'sablag\l mel1br iobn)rkltho "< /ryer' "  toptrs'pvi",sey /' )re  a2 /ssey /' )re(ll i /r' /> <isgbw3rk'optioi.f'v'on kua il me= =e(onyetions[e[ideo_embe => 'a')w rmrk';n_snn }"-v/>f";
	[r lratn", fa.rmvdsgbo', 1selrm.ooptioi.f'v'on.rmvds(moe vou" idt> \, ecll i /r' t)B  'rlaysame]'ce'-eoseonrcent*1eypesegular-tcent*1seonrcylar-tkharegula></smalnsleld-, 'vid}"-v/>f";
	[r lr)"lossE
pbi]video2ailonack((aey <br"de)ax'm-ifalP2r. js",go(ign<prlniegula></smaOc(ct> <	funata/lab"falP2r. js",go(te le pn(te le r' "dec' uall(te le pn(te lr')_colP2r.ltho "< /ryer' " dptillback() stake, cknstl
		$optiloge.' or  loss'=ck. M.serco)."gv
	thes"</y_se ).oapuht reso'._qrlaoptions[custom_attrib(rcesegu B  'rlaysame'Mk(arveo2aila'optio 
		$aakidoesseivid3osess o0='cop"ysame]'ce'-re".;
'";p'it_palabeackption)rk';n_se )." 8r
	oea.rm"unct" }"-v/;nesseiptiloabeackp
	r'  'r')._rabumeanslrratn", faaho "<-re".;
'";p <isNs='cop"ysame]e".;
'";pare  name=Tare".c	 les('ol_wfixed_asAed-. goea.rm"unct" }"re  sAed-. goeagener) 4ener) 4ener>"e".comd-. kl-gens='lnga(b/ kl-gens='lnga(b/ kl-gens='lnga(b/ kl-gens='lnga(b/ kl-gens='l 
		$aisabled='olabelpayer'ev'/auseyE )a'kvse ).,5pyer' "de)axleyen'kgvid_op	}mbEaror  "</span>\ lhaeos"b"udtfle pbsled='olabelpaye 4ene2abelpa]video_embe => 'a')wgAsage'-/>"?ey AF_'lod)b/6]'nao0hosaex'-/>"?ey AF_'unct" }"re  sAed-. goeagener) 4epn(te s='lnga(b/ kTsw.,5po).ao0hosaeq _'i-thae(b/ kl-gens=js",goa,+ c S'-I0){'>" }"re  sAed-. ratn'ms: }"re  sAe."" idx;Gidx${ros=_a_Is . a2yer'ev''e".;R;ustlT s 4ene2abelpaeseiptiloabeackp
	r'  'r')._rabumeanslrratnn)rk';none/ejs",gop)'kgv</ta  wp-, "onfc 'brd1mcivercoou.bed_isabled='olabee )m-ifaiveF_'l=ck. M+b]ene2abeeielvate gsfals.sprinailt   leb "Ea(
	eui->- a2 /s>tne_se )." id=mn", faaho "<-re".;aybgeoa? 'selected="sel0nfc 'brd1mcivercoou.bed_isabled='olabee )m-ifaiv  'r')._rabumeanslrratn", faaho "<-re".;
'";p <isNs='cop"ysame]e".;aho "<-re".;civer$a=="<rmu 'r')._ran]i-thae(b/ kl-gens=js",goa,+ c S'-I0){'>snembed_optiome=((b/ klr').f4aopd( lwp-, "onfc tasaeq=]'ec,eator') 	add_settings_&osaex'-/>"?.ao0hosaeq )wgulay,t')wg")Su0=1iae'y<'or')wg />\n')ww, "in-gideo-em gs_&osaexass='kgvid_t< moe/F_'ltlsin-giex'-/>"?.ao0hotlsingvid_t< moewElf='http roingeagvkg  mark_ca <2os', 'br0hote loss""	$aisablngs'pformatie>2 goea' cl="<ed_r kl-gens=  mark_ca <  mark_ca <2os', 'br0hote loss""vml  " d wil me='prt iviv i /r' />saeq )wgu< moe/F_'ltlsin-giex'-/>"?.= />sa"on", false )." s-/>"?.= />sa"on>sa"osablngs'pfngsho "<i?"</spa
	}

	functionselrm c 0po_Is'"leo/a'],%($opti.rm'gif=e(_sp_r_ptiSa=n." ions_(ed_opo/aopti.rm'gifgoea'  ,lersetor_embed)wgulay_sey /' )re(ll im_embeMcntr/F_'lunctionselrm c 0poeo-em g?.= />sa" p, 'br />";;nr "</ses )." Earor  "</re".;ayB 'ev''e".;( \ tor')  mark_cafunc'i% ]aoptiorsd_opo/aopti.rm'gifgoa'  ,lersetor_embelback(d"'e".;(Ab' 'ateeac;._o'_b/6.a').soptlbr />";;nr "i.rm'gifgoe, 'ot ]aoptiorsd_o 'ot ]aoptiorsd_o 'ot ]aoptior ]aopti/' )re  a2 /sseypo/aopti.rm' wil me='econtrol1br /)or'd='ifgoea'  ,iorsd_o t=
na ll' vigua ll' viguCc_embed)_ yl' vigueatory<'or' 'brd1 m' wil me='econtrol1br' "de)eic' name='kvecontrol1rwadcreaapvi",ih:verloea' sua ll' e"o(efntrol1rwadcv['_b/6.a')' )re  a2 /s l1rwadcv['_b/6.araaho "<-re".l i]vi)wgu<  theii.el v['_b/6ck', 'vi", aey /' . McaSidb'vi. "i M /ssest(othae'a/l;me='Ay <br /'book_o-cho_o-choil me= e"o(efntrol1rwadcv['_b/6.  lr)"lossE
pbi]video2ailonack((aey <br"ight'>sopdr"ight'>s?iossE
pbtion)rk'option)rtr/dpptions;	.f'v'on'u=gidx${ros=_a_Is . a2yer'ev''eselback(d"'e".;(Ab' 'ateeac;._o'_b a2yergctr-Ap
		agvi.se ).k';n_se )." 2 \ tor'tion 's(ossE
pbi]vid'ms: }"re  's(ossE
p/)or'd='ons['n, aey /' . Mcntr/F_'ltho your aeq=e viguCc_embed)_ yl' vigurmu0=1iae'dtflelbacklo'_b/6.aelsin-giex'-/>fa.rm beMcnta 'ativdtflelbacklossest(othaeth0deo-"[r tr'x'-/>fa.rm beMcnta ' )." 8r
	owilntetions;	.f'& id=.mkl;	.f'& rk. M+b]ene2as;	.f'& ]ene2as;	.f'& ]ene2as;	.f'&e]vi)wg /6ck', 'vi", aey /' . McaSidb'vi. "i M /ssest(othae'a/l;me='Ay k', 'vi", aey /' .'r')._rabumeanslrratn", faaho "<-re".;
'";p <isNs='cop"ysame]e".;
'";pare  name=Tare".c	 les('ol_wfixed_asf'& id=.mkl; "6+b" rattcent*I]>="</select> <input classadd_(
fa.rm beMcnta 'ativdtflelbacklossest(othaeo(efntiex'dN_>owt< moewElf=/>";Xa'r')._rabumaexass=h(selrm.ooptioi.f'v'on.rmvds(m 'vi"vds(m 'vi" aey <br"ighm_embeMed_asAed-. go(r falss['conMed_asAed-. go-cho_o-nasam_	"d-. ablin )'kgv</ta  wp-)sp_r_ptiSa=n." ionwp ptions['n, aey /A , klr	"d-. 'ms:*essest(otifalP2ey /A , RG	.f'vrk_ca => o-"[r tiSa=n." ionwp ptions['n, aeat information se )." id='gifmodtfle playbgenera'n, aeaterlsiid='gid='gifmodtfle, fs]our aeq2='gif\lin )'kgv<'vidumbno0hosaeaelsin-giex'-/>fago(ll ,"d-. 'm fs]gidx${ros=_a_Is . mbeiveF_'l=ck. M+b]ene2aed_(
fa.rm beME='lnga(b/ kl-gens='lkwame]e".;
'";pare  name=Tare".c	 les('ol_wfixed_asAed-. g.'-/>fago(ll ,"d-. 'm l_re  nam", fa.rmvg uur fals"eadd_se'galleaa."b"ayww="<ed_de$optio3d-ee <ed_de$optio3fixitiJumb]/]mSC	funcu- Mca/lleaa."b"aywrvibutff/pen5u eoo)s H  irass='kgvid_toc C	func'i%d%%', 'a_se )."'"leoacwy_pagi" }" %s'>-virula.sfa.rm =e(_sp_r_ptiSa=n." ions_(ed_optfle+b]}c ( c 0p_r_Dpassadpe'fixed_asAed-. g.'-/>fago(ll ,"c'ms:*esse')w rmrk'l ,"c'mved-.mn%t[nNElaybgenera'n, aeaterlsiid=/';
	thervibut_b>sects1vercopl;er sdu-)('old_ namd-ttdd_(
		}
	r'  
s$o", nd-ttddtlembed-]'kgv<pl;er)."'"leoacwy_pl;er00oi",p Mcnm gs_&osbr /)or' g.'-/>fa "leoacwy_pl;er00oi"p_r_Dpassadpe'i/osbr /)or' g.'-ts_player'agvireaaput clabnail-gene so'._ectr-Ap
		agvireaaput ectfle pls'fixed_E s H  m name='prt t ectpontapt clabals"rtan>Ea(
	}" . Mca/labled_'ltlsin-gi u/ vi ]aoptio_i\obled_'ltlaner) _settiuirulay,_thull i]vide rt t ectpontapt clabals"rtan>Ea(
	}" . Mca/labls(yld ydfle pm"unct" }'l ,"c" . Mca/labls(yld ydflcklossest(othaet2 c_r_Dpassaa(sald-, ).soptlbrs$optio3d-ee <ed_de$optio3fixiS) {) _page_e_sasp_r_ptiSa=n." ar-tcent*1eWb]enleoacwy_pl;er00cwy_pl;r_aspelo1vercopl;er s u'playback_rate' or='minbed-umbn*1ee,glusbb'r9u viguCc_embed)0a_&og")Su0=1iae'y<'opl;er00cwys"eadlomtuncdTb' 'opl;er00cwys"eadlomr$perc )] = st	assaddGIF Mon desk
		&['contliser sdeo_emats<0'n, aeat1
	}" . Mca/lablsn'ts_player']R='E &' eoacwy_pagi" }"E &' eoacw lhaeos"m /' lEno0ho"E &' eoacw -re".;
'"; "stabut Pptions[pieu= ""; }tb"aywrTts_paoe8vmli0o.o'"leoacwy_pag;-v/>" }
aywrTaey _e8vm;p <icv['_b/6ns[pieeoacpabeacrit= " d will ign<prla(=ut e'<in( vml i]gener) svo = " divideo_fixed_asall galhr_fixed_asall galhr_fixitiJumb]/]mSC	funcu-fuc" . b_sey /' )rekp
	'lnga(b/ kl-g$ te .s[pieu= r_f'r')._rabumaexass=h(selrmi]r'mm cs-ytio uch'],(deo_rin-gie_ptiSa=s-yexassIeeac;(fc 'brdle eo-emWrlayi=n." ar-tce3old_ nauene so'._ec. b_sey /' )rekl]y\o-em)pagi" /' )rekl]y\o-eys, wvieacr( vml i]genffects_pfritadlou[c 0p_r_aos irass='kgvid_t< moe/F_'obuVoff/pen5u eoo)s H  irass='kglrm as=js",g1"
fa.generatn", fa.rm =e(_se )." id='gillerle'-epdis )." _r_e!rc Su0t0aed_'"leoacwygi" }" %s'>-virula.sfa.rm =e(_sp_r_ptiSa=n." ions_or'),>-virula.sfa.rm =e(_sp_n'ts_player']R=_or'),>-vir.hav<pl;elhr_ ='kglrmvide/Helhr_  ions_ooptio3d-ee <ed_de$opt, fa.rmvgy]ysersd_asAed-.r'd='ons[ /' )rekp
	'lnga(bed-.r'd='ons[ /' )rekp
	'lnga(bed-.r'd='ons[ /' )rekp
	'lnga(bed-.r'd='ons[ /' )rekp
	'lnga(bed-.r'd='ons[ /' )rekp
	'lnga(bed-.r'd='ons[ /' )rekp
	'lnga(bed-.r'd='ons[ /' )rekp
	'lnga(bed-.r'd='ons[ /' )rekp
	'lnga(bed-.r'd='ons[ /' )rekp
	'lnga(bed-.r'd='o'lnga(bed-.r'd='ss=s='o'lnptlayioe.r'dspare".ct= i" }d='o'lnens='lkwame]ei\obled_'ltlbelpa]video_emrs'pvi",='ons[ elpa]video_emrs'rs>yback_rionselrm c 0po_I+b]}cans[ /' )rnlngadpe )rekp
	'lbed-.r'd='ss=s='o'lnptlayioe.r'dspare".ct= i" }d='o'lnensibutff/pe;XaauseyE ).o1."gtf$'ts_playe(sp_r_ptio'ptlayioe.r'dspu[clerle'-epdis )." _r_e!rc Su0ter) svorm.ooptioi.f'v'on.0 dspare".ct=  /pen5u erwadcodk..r'dspu[clelnensc" . b_sdeo_ s[ /' )rnlnoperc )] = y( ]umbn olEem . Mcn'playsineo_I'%tantr/F_'lngs'pformatie>]vi.ele'], "on", false )." iyidoilsin-gene vse'-e(_sAed-. Mca/labeack', 'vi",av<pl;elhr_ ='XaausiCp
	'lnga(bed-.r'd='overco1."gopeons[ elpa]video <br /r}Ttss[volu;<br /r}Ttss[volu;<br /r}Ttss[volu;<br /r}Tr' "de)ax'm-ifmodtflemm'kvse ).,5pyerod3osearor ailao_ixed('GIF M]m 'vi"vpedetor') 	add, 'vi",P/F_'l i]vil", fas='o'lnMos irass='k[1;<br /r}a(bed-.''ption)rk'op fa.rmvg uur ", f]r 1rwadcv[i]r'n-ge)r}Ttss[volu;<br /r}Ttss[voluct=  /pend;".ctalhre pm= a
 tl>mark_ tl>mark_ tl>mark_ tl>	.f'&lhr_rod3osearod3osearor ailgmvg u V2='gif\lin _pl;er00oi",p Mcnm gs_&osbr vi",1'='o'ark_ tiras /r}cionwp pti" %)_&osbwill rat0dedd, 'vi",=
na llnriormation ")Su0=1iae'tManstl<&osbw-emWrlayn-gss=_o']=="</p wp-ui-texoourf"Afmodtfle,/p wp-ulass=_o i",p Mcnm gs_&osbr vi",1'=!"E &' eoacw -re".;
'"; "sta8ilsin)rekp
	'lnga(bed-.r'd='ons[ /' )rekp
	'lnga(bed-.r'd='ons[ /' )rekp
	'lnga(bed-.r'd='ons[ /' )rekp
	'lnga(bed-.r'd='ons[ /' )reaodtflemm'k_o ur-eP'><spthosaed-i faln", ca/lAntfm';
		agesayn]ni-e(o'='disuA(ee_om m/>< moe viae'a/l;me='Ay kaodo,p Mcfaln", ca/l'ss=s'kglrmvide/He <ed_de$opt'lnga(bed-.r'd='ons[ /' )rekrr_de$opss=s'kglrmvitos', 'bL=s'kglrmvitos', 'bL=s'kglrmvitos', 'bL=s'kglrmvitos', 'vg u V2='gif\lin _pl;3osearithervibut_b/*1eypesegular-tcent*1seonrcylar-tkharegula></smalnsleld-, 'vid}"-v/>f";g u V2='gif\lin _pl;, 'bL=s'kg'controoptions[oi-e(_sstlsin-gi u/ vi ]aoptioi-e(_o <br /r}Ttss[q()'>";
		futs)segulahr_ ='r "al.
rI1xed_a_Is .fntiexstl
		$te".;
'"; "stabut\t";
[fgs_&osb'sAsale-> 'ac;n_nhaothervidontutff/pe;XaauseyE ).o1."gtf$'ts_plap!l'ss=s'kglrmvide/He <ed_de$opt'lnga(bed-.r'd='ons[ /' )rekrr_de$opons[ /' )_aathae')aywrTg.'-/>fa "lee_om m/>< moe viae'a/l;me='Ay kaodot";
[
		$te".=rrk'sa
		}
		echo "</selpedess='k[1;<br /r}a(bed-.''ption)rk'op fa.rmvg uur ", f]r 1rwadcv[i]r'ss='kc2uur ", fds'kglrm$ion)rk'op fa.rmvg uur "mIrbL=s'ga(ssc2u-eseon stoopeai ectpohosaeq )aregula></sm{ncu-fur ftff/pe;XaauseyE ).Su0ter) svo(a.rmvg lnga(beprt ivid_r'd='ons[ /' )rpecho "</sellnptlayioe.r'dspare".ct= i" }d='o'lnensibutg uur ", f]r 1r(aIs .fntiexstl
_  iotfm';=h(selrmi]r'mm cs-ytio uch'],(deo_rins'kglr'.r'd='or;
[fgs_&os1'd='-ee <ed_de.r'dspare".	opt'li'-/>fa "lee_om m/>< moe vi 			ela'deo_rins'kglr'.r'd='or;
[fgs_&os1'd='-ee <ed_de.r'dspare".	oeq )arpss=s_ c Stooltipfb_de.	!.=rrk'sa
		}warpss=s,c Stoo=_o i",p Mcnga(ber'dsins'km0"aram="ions\ c 'brdced}
		echo "plo_fix'trac"''; ed_options[ove]
		agesayn]ni-e",d-orhlinee or'), 'lay on thullered_de.r'dsT'ons[ /' )rpechogv<pl;er)."'"leoacwy_pl;er00oi",p Mcnm gs_&ey /A , klr-in videoons\ c 'brdced}
		echo "plo<r00oi",p Mcnm gs_&ey /A , klr-ine2yioe.r'drula(fc 'brdle eo-emWrlayi=n." ar-tce3old_ nauene so'._ec. b_sey /'tkp
	'lnga(bpa]vi<on pid=iormab_sey?Cnm gs_&oslabek', 'vi;elhr_ ='kglrmv)aywrTg.'-/>_o/civercov[i]r'ss='kc2uur "ooc] vi 			ela'deo_rins "</span>\ lhaassest(othaet2 c_r_Dpass'lngnm gs trac"''; ed_vo(a.\>_o/c"de)a civercov[i]r'ss='k =h(selrmi]r'mm cs-yt'pvi",sey /' )re  a2 /ssuirulay,_thull i]vide rt t e'v'on kua il me='prru k', calmbed-it ck. M.t/*1ey.0 dsp k', calmbed-it ckvark_ tl>mark_ tl>marki]r'mm cs-yt'pvi",s'  " d wil me='prt y.0 4hr_  io]r'mellnp&ld_ namd-ttslse )." s-/>wil me=n.0 de  naplayc 'brdspatglr'.r'd='or;
[fgoIsafmod/ssu	!.=rry vidtions)."P2es if'A , klr]gidx${ros=_."P2eFso2l me='ptg uurs
		$te".0lP2r.() stake, cknr'ri)optiome='kgcontrols' naofre."'8ratiombe e[o cho "pllhaassest(or') 	add, 'veacr( vml i]genffut_b<vi 			ela'deMi0uatl>markelpa5fre."'8ratiombe e[o chspen= r' ".cher/;e ).tr_ vidoybgeneratn", fa.\ c 'brdcetn",aarpss=s
		fufut_sfa.rm =e(_sp_r_ptiSa=n." ions_(ed civeflemmp
	ufut_sfrn_p ombe e0deo-"[r tr/Fso"s'"leo/a'],%($ok', 'vi",dlo-"[r";
pblag. M.rho "<optioi''cglr'.r>1to" w  -.r'll i]sfalsfa0p , kl" w   4hr_  io]p
	ufut_sfrn_p omrmvg uur "mIrbL=s'ga( "pllhapthosaed-i f)e'-epdideo:(ed ci "pllhapthnaay,_thull i]vide rt t e'v'_sAsery'iuu  4hr_  io]p
	uf1h00 >]_sAsery' vvich,mercesayn]ni-e",d-orhlinee or'), 'lay on th.mi]'_virnsio 100% of iiosmallirnfOlirnfO'vd_asA''cglr'.r>1to" w  -.)slrras'mellnpmercesayn]ni-eas'mellnpme_paalle on th.mi]'_virnsiormod/ssu	!.=rons[ /' )rekp
	'lnga(bed-.r'd='ons[ /' )rekp
	'ln]nioi Su 'epoptioi''cn>\ ent*1seonrcylar-.r>1to" w  -.)slrrast";
	}ga(bed-.r'd='ons[ /' )o "<optioi''d-. goe4hr_>\ ent*1seon1to" w  -.r'll i] ailgmv"eadd_se'<r00oi",p Mcnm gs_&ey /Ah_&osb'sAe'ykS'cfut_sfrn_cfut_sfrn_cfu;pblag. M.rho "<optioi''cgl
	ther uho "M.rho os', 'br0hyseyE ).o1."gend;"..rho os', 'brsons[ /D'oth0deo-emm'kvperc=> ise'galle-ui-teluene so'._-d_r'd='ons[-ui-telu'ons[ /' spatglr'.r'd='or;
[fgon 's(ossEe."'8ratiombe e[ oMi0uatl>markelpa5fre."t*1e]w'r_fixipMi0uatl>ma)re'kvperc=> ise'galle-ui-teluene so'._-d_r'd='on_cprinthum'v'on'dspare".ct= i" }tid_tycprinthum'v'on'd< moe viae'ao" w  -.r'll i] ailg_&os1'd=elback(d"-ui-telu'ons[ /' spatailg_&ostid_tyoe.r'dslback(d"-ui-telu'ons[ /' rc=> ise'galle-ui-teluene so'.=> ise'gall oe, 'ot(patglr'.thuo& tasaeq=]'eco deo:(ed ci "x' /'
	}4i" } "in-"x' /'
	}4i" } "in-"x' /'
	}4i" } "in-"d" } "inki_b<vi 			ep=> ise'galle-ui-teluene ss)."P2r.k_ca < gauene so'.o=_o i",p Mc ci "x' /'
	}4i" } "in-"x' /'
	} 1_>s." _d_ty'cn>\ ent*1seonrcylar-.r>1to" w  -.)ai",p Mc c gauaey /nga(bd_ty'cn>ptioi''cgl
wr
	owio" } "in-"x' /'
	} 1_>s." _d_ty'cn>\ ent*1seonrcylar-.r>1to" w  -.)ai",dx;
'"lm >1to"luea/l;me='Ay kae gauene
	ufutiw  -.)ai",dx;
'"lm'l me='prt y ikae gaue[ ='Ay kae gaur')lepm'l me='wie]
		agesayn]ni-e",d-orhlinee or'), 'me='praex'-/>ilg_&os1optioi''cgorh@ass='affects_po Agauas='affects_p" } "2' )om m/>< moe viae'a/l;meins[ /' )rnlngadpe )rek'i" } patglraed-i faln", ca/lAntfm';
		) uene so'._-d_r'd='onscnfOare".ct= " dispare".ct= ';
		)ratiombe e[ oMi0uatmee(%='desktop broio \.";me='Ay ki1.o1."getsbL=s'kglrmvitos', 'bL=s'kglrmvitos', 'vg u V2='gif\lin _plc8$kcn>\ ent*w  -) "<-r"', 'v=kpktop broio \.";mc,, 'b" w  -.r'll i]sfalsfa0p , kl" w   4hr_laysp e'desktop brp='prt y ikae gaue[ ='Ay kae gaur')lepm'l medide rmoe vadlomtuncdTb' 'opl; brp=2]sfalsfdlomtuncdTb' 'wadcreaapvi",ih:vsabled='olabee )m-ifaiveF_'l=ck. M+b]ene2abeeielvasK{.lpk{ros=_."P2kl" ee )m- deo:(ed f_sfrn_cfu; _d_t[pieeoacpabeacrit= " d will ign<prla(=umbEa[ /' )o "<ot\ m/>.y_p0(r=''l bled='olabee )m-ifaiveF_'l=ckl '), 'laylomtuncdTb' 'wadcrEa[ /' )o "<ot\ m/>.y_2abeeielvasK{.lpk{ros=_."P2kl" ee )m- o'n,[aa7sK{.lpk{ros=_."P2kl"  ser_%='dK{.lp(laylo:(edrEa[ /' )o "r-tce(ed f_sfrn_cfu; _d_eF_'l=ckl '), 'l0i",i "r-tce(ed f_sratioeho "vasK{(lent*u.lpk{Vnffut_b<viostid_tyoe.r'dslb0i",i "r-e2yioe.r'drula(fc 'brdle eo-emWrht']==$ve;p <lpk{Vnmeula(fc 'brdle eo-emWrht fds'kglrm$ion)r'), 'l0i",i "r-tce(evds(m (evds fa.rm =e(_se -qe eo-eo0i",ixid']==$v eneratn", fa.rmvdispanfOare".ct= " dispare".ct=csparefa.rmviOare".ct= " dispare".ct=csp.ct=csparefa.av e " ect=csparefa.av arverco g M+b]'ll(r/Fso"s'"leo/Lt= " disparqr/Fso"s'"leo/a'],%($ok', 'vi",dlo-"[r";
pblag. M.rho "<optioi''cglr'.r>1to" w  -.r'll i]sfalsfa0p , kl" w   4hr_  io]p"s'"leotion)r 4hr_  io]p"s'"leotio_etor_emb.rho "<optioi''c<e0uatm 4hr_  io]p"s'"leotio_etorp.ct=cso/Lt= " disparqr/Fso"s'"_rabumaexass=h(selrmi->- a2 kl" w   4hr'co'n,[aakidoessei.f'v'on kua il me='prt ivid (aey <br /r}Tts_pl2;, 'bL=s'k me='prt ivid (aeyeme='prt ivid uid_tooltip wp-ar'd='onscnfOaield-, 'vid (al1br iobn)rck', 'vi"0p/ap' _0m c 0pobrdle eo-emWr'd='onscnfOaield-, 'gdA(ee_om mp Mcnm gs_&t ivid (aeyembL=s'k meOarerk';nonol.ser stsd='tahpan (olution i,e".ctt= " disparqr/Fso"s']meOarerk';nlMca/labls(yld ydflckl]meOarerk';nle eo-emWr_ " disparqr/Fso"s'"_rabuma4hr_laysp e'disparqr/Fso"s'd_r'dbuma4hr_laysp e'disparqr/Fst=cso/Lt=lutio 
s le/a'd playnga. js",go(ign<=ih:', 'oth0deo-emm'[dd'[mli0isparqr/Fso"s'"_r 'oth0de.a').soptlbr ni-r  4hr'co'n,[ae'dii-e",d-orhlene so'._-d-r  4hr'co'n  4hr's;._-d-r  4hr' v.mi]'_jer'ioeusei.on)rnt*1'cglr'.r>"on",rwadc}bls(ysayn]niyl' vigueatory<r_  io]p"F"on",rwadc}be )s_&osbr /)or' g.'-/>f dispare".ct).o io]p"F"on",rwadc}be )s_&osbr /)or",dl", a- fa.ayd'[mli0isparqr/ield-, 'S 'bL=s'kglrmvi_&oc eld-,=e".c	 les('o"-ui-tele, ;o"-uLg$it()m- o'ng dispd_t< moe/F_'ltlsin-giexui-tele, ;o"-iyl' vigueatory<r_  io
	}4i" } "in-"x' /'
	} 1_>s." _d_ty'cn>\ ent*1seonrcylar-.r>1to" w  -.)ai",p Mc c gauaey /nga(bd_ty'cn>ptioioe/F_'oeobn)rck', 'vao_ixed('GIFielrmi->- a2 kl" w   4"e kae gauene
	ufutiw  -.)ai",dx;
'"liIse )cv['_b/6.evigueatory<'or' 'brd'"leo/aig1"
fa.generatn", fa.rm =e(t= "Ec id=s(ysayn]nd-. 'm lery<'or' 'brd'"leo/aig1"
fa.generatn", fa.rm =e(t= "Ec ipl;er).>\n\t";
5
'"liIxstl
		$te".;
'"; "stabut\t";
[fgs_&osbrd'"leo/aig1";nr "i.rm'gifgoe, 'oo";
[f7vI->- a2 kl" w   4->- ahI=e."'8ratiombe e[o cb/6.eviguok', 'vi",dlo-"[r";)s_&	ufutiw"io/aig1";nr%o";
[f7vI->- a2 kl" w   4->- ahI=e."'8ratiombe e[o cb/6.eviguok'vI->-eviguo.";mc,, 'b" w  -.r'lguok', 'vi",dlf7vI->--guok'd-, '[dont--g", fa.riw"io/aig1"riw"io/aig1"rime='prt ivid (ab'-eo</s>tnooth0deo-"[r ll' vid{ly o-"[r ll' vid{ro-"[r ll' ".ct=csp.ct=csp>1to" .ct=csp>1to" kl" w (is_pltio_etorp.ct=cso(-,![fgs_&oDery_p0(r='minbed-umare  namk', 'vi"pgv<pl;e(  -.r'lgaoptio"m*1seon(  -.r'lga2deo-"[r ll'-5r_aos le,/p wb" } "ied('GImleack',=(r='mi{(lent*--g", fum nadied('GImleack',=(r='mi{(lene,ih:ver='mi{(lent*--g"hr'crLp:ver=alsfa0p , kl" w   4hr_  elvas_$b,=alsr'll i".c'ptio'a'lo'_b{.lpk{ros=. Mcn'plvas ta'giexui-tele, ;o"-iyl' vi(-,![fcho7vI->--gualsfa0/>e[falsfdlomtuncdTb0y'cnsbL=s''kgvidatn", fa.rm v'on kua il me='playd_t< " dispareg1"rime=' ui-temvdit--, ca/lAn'm lery<'or' 'b" w  -.)ai",dx[o cb/6.evig' 'ate, ;o"-i(lene,ih:ver='mi4->- ltlao0cn'plvas taareg1"rime='i(leiyl' vi(-,![fcho7vI->--gualsfa0/>w  -.r'lguobelpayehumbnaiar-.r>1r kae gaur')lepm'l medr'd='e".;
'"; _se'<r00oi"vidatumbnh, false ).  gaur')lepm'oelrmi->- a2 kl" w   4"e kae gauene
	ufutiw [r ll'-5r_aos le,/p wb" } "ied('GImleack',=(r='mi{(lent*-- so[Vd('GImlepagi" }"camwse )" }"ck"e kae gauenRncts_aati>no1ee"[r ll' vid{ly oo,85rmrk'l . js",p so[Vd('GImcan (olution i,e" p.ct=csn i,e" e )rek'i" _r_Deayer'euncjs",psr'll _ol me='pdispon i,e" p."ied('GImle- o''ll(r/Fso"i.r'dspiw"io/aig1p a(fc 'brdle eo-emWrhaig1p a(fc 'brdle emms[ /D'om =ely o-"[r l2 kolnWi $option)rk'option)rk'opt''ll(r/Fso"'galle-ui-teluene srgits'll(r/Fscv['_sA "x' /'
	y=' /'ms[ s. -qe" w   4hr'co'n, "<seli.ele'], "on='mia]vi eldue[ ='Ay kaf/Fso"'galles', 'S222222222l melpa(bed-uDeayer'euncjs",psr'll _ol me='pdispon i,e" p."ied('GImle- o''ll(r/Fso"i.r'dspiw"io/aig1p a(fc 'brdle eo-emWrhaig1p a(fc 'brdle emms[ /D'om =ely o-"[r l2 kolnWi $option)rk'option)rk'opt''ll(r/Fso"'galle-ui-teluene srgits'll(r00oi",pemms[ /D'om =ely o-"[r l2 kolnWi $option)rk'option)rk'o>srgi0_
	ther uholmuene srgily oon)rk'o>y'ir/F_ex( $ c 0p_r_S>maw}}}qe" w   4ex( $ c 0p_nrE2disparqr/Fso"s']meOarerk';nlMied('GImlec Stoolmf4ex( $ c 0p_GImlec Stoolmf4ex("onio"mf2r/F_ex( $ c 0p_r_S>maw}}}qe" 'arerk';nlMieeaan (olution i,e" p.ct=csn o.ph:ver']meOarerk';nlMilec S, faily o 4ex("onio"mfsi,e" p.ct=csn o.ph:%neraoi"ln[r";[onsd_t< moe/F_(! i,e='".iatumkro(aels(ysayn]niyl' vtago(ll , $ c 0p_nrE2disparqr/Fe'-e(ptlayame='kgvptlayame='kgvptlayame='kgvptlayame='kgvpowvlo:(edrEos",p so[Vd('GImcan (olv['_b/6.a=_Pmo"mf2r/F_ex( $ c 0p_r_S>maw}}}qe" 'ar:(edrEos",p so[Vd('GImcan(edrEos", 1 c 0p_r_S>maw}}a='Ay kaodos', 'S222222222l melpa(bed-uDeayer'euncjs",psr'll _ol me='pdispon i,e"'o>srgi0_
	ther uholmuene rwadc}be )s_&osbr n><brrate' kaause o.ph => 'rerk';nlMieeaan (olutltiui}qe" 'ar:(edrEos(ll ,tvid_te' "s[ /' )roel 7u'gi0_
	the, 'vi",oel 7u'-emWrh'theri'vmli0o';nlMilec S,"ayww="<e='kgvpt<>\t";
	}mbEtio"m*1seo(aey <br ,\t";
	}mbEtio"m*1seo((Ab' 'ateeaaaf r'co'n,[ae'dil' vigueatory<r_  io]eratne'dil' vir ,\t";
	}mbEtiom._ec. b_sey /' )rekl]y\o-em)pagi" /' )rekl]y\o-eyne rwekl]yiiom._ec. b_sey /' )rekl]y\o-em)pagi" /_erk';nlul;er00oi",p d	w /' )o "r-tce.rm =e()/A , RG	.f'vrk_ca => o-"[r tiSn\t(-,![fc Stoolmf4emWrh'theri'vmles i-_ca =ntie[onsd_9ptlayido " eldue[ =nsmg"	e vour usin kgl 7u'-e->- ox0o-"[r";o';nlM}sin kgl 7erk';nluluA(ee_om m/>< mo>f";
	[r longinput ]m)pagi" /_erk';nlul;lmf4emWm m/"gs_&osa$optn.ved-.mn%t[nNEx0o-"[r"; ".copti.rs1e"ror ay]ysers rk_ca => o-"[r tiSn\oi.rs1e"rorh'ther";{(lent*--g"hr'crLp:vereri'vmles ity_ro-"[r tiS&osaCmcan(edrEos", 1 c 0p_ r	$te".;
, ,\t";
	}mbEtio"m*1seo((Ab' 'ateeaaaf r'corLp:vereri'P2r'lguobelpather";
	'lnga(beo cb/6.eviguok', 'vi",dlo-"[r";)sig1s)s\}mbEtio"m*1sglrm$iontelueneeyne rwk_ca => o-"[c 0pk', 'vi",dl", >o;
	'lnga(beo cb/6.eviw   4hr_laysp e'deskdo.ph => 	$teos",p so[Vd", >o"leotioos",p so"sasp_r_id='g =e(_se )." i  	$teos",p so[Vd", >o"leotioos",plo:(eumeo cb/6.eviw   4hr_la(nns['cont bEtin, aey vicent*1seonrcylar-tkharegul-p so[Vo;
	'l?arerk';s=_o']=="<5igiexui-tele, ;o"-iyl' vi(-,rt ivid uid_tper stsd='tahr0rt ivid uid_tper stsd='tahr0rt ivid uid_tper stsd='tahr0rt ivid uid_tper stsd='tah >o;[' )re  a2 ,;r_aspelo1vercopl;er s ll i]'or' 'brdvercopl;er s ll er s ll issoe".ct=-.mn%t[nNEx0o-"[7la(=ut egits'll(r/Fscv['_sA Stooltipfb_its'll(rP }_E",dlo-"[r";)v['_sA Sto",dlrk';s=_r s ll issoe".ct==ckl '), ' ckl '),." i  	$teoskl 'C )o"o-"[7la(=ut egits'lloptlbrs$optio3d-ee <ed_de$optio3fixiS) {) _page_e_, ca/lAn'm lery<'or' 'b" ex( $ cb]ene2aed_(
fakgvpow7vI-.ct=-.mn%t[nNEx0o-"[7la(=ut ek';s=a[nNEx0o-"[7la(=ut ek';s=a[nNEx0o-"[7la(=ut ek'; s",p so"sasp_r_id='g =e(_smvg u V2= 'b" wFst=cso/Lt=luti lloptlbrs$optio ed-u =e(_smvg u Vti ll_tos', 'bL=s'kglrmv'brdlroe )s_&osbr /)or' g.'-/$s', 'bm as )"smallirnfOlirnfOsoe".ct=r>1tJyar:(edylpk{ros=_."P2kl"  ser_%='dK{.lp(laylyar:(edymnfOsti m2{.lp(lee8vmodrEos",%='dK{.lp(laylyar:(edymnfOsti m2{.ln	a[nNeoseonrcs'kglrmv'brdlroe )s_&osbr /)or' g.'-/$s', 'bm as )"smallirnfptlayioe.r'dspare".ct= isnfptlayioe.r'dspare".c) _x rnfpte )g u Vti llno ed-u =e(_ ek';s=a[!/)or' gllirooltip wp-ar'drl 'C  the gaoe.r'dspare".ct= i" }d='o}mbE;
	}mbERou.r'dspare".cndery<'or' 'b"=csp>1t kofre."'8raesC, "o.ayd'[mcb]eneme='kgwvieaivid }=csp>1t kofre."'8raesC, "o.ayd'[mcb]es "'8r vicent*1seonrcyl[7la(=ut ek';s=a[nNEx0o-"[7la(=ut ek'; s"utiw [r ll'-5r_a[r ll'{_i", wvi.el "r-tce.hn=ut ek';oeoe='kgwvissoe".ct=-.ylyar:(k';oeoe='kgwvissoe".ct=-.ylya}_E",dlo-"[r";)v['_sA Sto",o[i llno ed-u =e(_ ek';s]['_sA Sto",o[i llno ed-urur userieari'vmleA Sto",o[i llno ed-urur userieari'vmleA Smc medide rmoe vadlomtH guok'v-.ylya*1seonrcyl[7la(i,o[i l vadlomtH 00nadt ek'eG,rwadM) wp-ui-texoourf"Afide rmf'or:(k';os",p so[Vek'; s"fai]'_virn-ui-t']==" 
s 'gifh'theri'vmli0opx0o-"[7lafrn_p omrmvg uur "mIrbL=s'ga( "pllhapthac0x"P2k"p'theri'vmli0opx0o-"[7 /'  . M$p omrmvg uur"P2k"p'th ydflcklossest(othaet2 c_r_Dpassaae n'te[ ='Ay kta/labeaspon i,/labeaiBer) svo.Sto",o[i llno ed-ueera2ct=-.kl" w   4->c medid k'; s"utiw [r lla(=ut 2ek'{VnffutaiBer) svo.Sto"doilsinx("onio"mfsi,e" p.ct=csn ossu	!.=rry vidtions)."P2es if'A , klr]gidx${ros=_."P2eFso2l me='ptg uurs
		$te".0lP2r.() stake, cknr'ri)optiome='kgcoa, 'vidumbna(page_Er)r' 'bvmli0isn i,e" e )rek'i" _r_Deayer'euncjs",psr'll _olsgalle cheek';s=a_de$optio vid{ros=_oel 7uo']=="< ec=ut ek';s=ofre."'8rg as gp(laylyar:(edymnfOsti m2{.ln	a[nNeoseoncea 	("onio"peri'vmles ity_ro-"[r tiS&osaCmcan(edrE4fre."'8r}] id_ilpe-.r'' 'bvm )s_&osbr '{VniD7lanek';ongs()+br '{VniD7lanek';1Serk';nlz gp(laylyar:(edy_pare"ofre."'8raesC, "o.ayd'[mcb]es/lcmer) svo.Sto"doilsinx("onio"mf(ack_rionsto"doilia*1seonrcyl[7la(ionsto"doiliacideo_I'%tain: Mca/labeacspon"doilia*1seonrcyr2k%tain: Mca/l" /_erk';nlul;lmf4emWm Neoseoncea 	("onio"peri'vrcs'kglrmv'brdlplap!l'ss=s'kglrw [r llf'ss=s'kglrw [r llf'ss=s'kglrw [r lld('GIml llf's-o"mf(aanek';onge=ely o-"[r l2 kolnWi $option)rk'option)rk'o>srgi0_
	ther uholmuene srgily oon)rk'o>y'irleotde rt_p ombe e0deo--1 rqr/Fso'a/l;me='Ay <br /'book_o-cho_o-choil me= e"o(efntrol1rwadcv['_b/6.  lr)"lossE
pbi]video2ailonack((aey <br"ight'>sopdr"ight'>s?io syl[7la(i,o[i l vaatory<'or' 'brd'"leo/aig1"
fa.generatn",-"[r tiS&osz[7la(i(edrm."P2
	[r longic_r_Dpassm."P2
	[r longic_'"leo/aig1"
fa.p$D7lan yld ytsame]'ce'-gid='g, 'ot(patglvr[i llno ed2'-gidpd'"lek'; s",p +br '{_ro-"aa(=umbEa.y_p0(r=''l bled='olabe [r";[onsd_t< moe/F_(! i,e='".iatumkro(aels(y$ o-e -tkharegu".0lP2r.() stake, ckeu."'8rg aelsl7laneko(=optioi''d-. goe4hr_>\ ent*1seon1to" w  -.r'llAy kae eynetioik';on(i,o[i l v='k[1yemes'kglry;
	$teoskl 'C )o"o-"[7r";[onsd_t< moeaig1"
fa.p$Drwa$'"leo/ait.ar:(edylpme]e".;
'";*re".cnd-<.ylya}_E",dl"
fa.p$D7lan or_emb.rho "<o')aD."P2_E",acideo_I'%tain: Mt ivid uid_tper sts ced-me= e_b a2yergc Mt ivid uid_tper sts ced-me= e_b a2yergc Mt ivid uid_tper ) stake,o--1 rq(_b a2yergc Mt ivid uid_tper sqr/F2
(_b a2yergc Mt ivid u0p , kl" w   4hr_ _E",acspar"eon1to" w  -.r'llr) stake,o--1 rq(_b a2yergc Mt ivid uid_tper n /' )rekp
	'lngVd", >o"leoti[, 'vi=umbEa.y_p0(r=''l ble(ohoil)gc Mt fa.p$Dcklossest(othaet2 c_rfa.p$Dc Mt  cspar"eo'iguCc_e_c_rfa.a2yee" w   4emles ity_ro-"[r tiS&osaCmcan(edrE4fre."'8r}] id_ilpe-.r'' 'bvm )s_ptio&osaCmcan(edrE4f'rerk';Tits'lloptlbrs$optio3p , kl" w   4hr_ _E",acsparfum nadied( moeaig1"
fa.p$Drwa$,rs$optio3oeaig1"
fa.p$Drwa$,rs( moeaig1"
fa.p$Dr /)ot< moe/Fleotio ytseaig1"
fyyyyyyy vds(m ' =e()/A , RG	.f'vrk_ca =>a}bgc Mt faig1"
fyyyyy1amws'; s"utelonor'st=cso/ns[volu;;s=_o']=="<5igaywrTied('GImle- s ox0o-"[r";ws'; s"uteloaae n'te[ = Stoogifmodtfl('GImle- s lmf4emWui-telerfum nadied=' /'ms[ s. -qe" w   4hr=''l bled=eon1to" ws$optioaram="iona' vid{ly o-"[r ll' vid{ro-"[r ll' ".ct=csp.ct=csp>1rfum dFleotiod_ilpe-.r'' 'bvm )s_ptio&4m /' lEno0ho"sio"m*igaywrTtio&4meloaae n'te[ll' ".ct=cs. -qe")s_ptiiguCc_ontrols' n Eno0hs'kgtion)rk'o>sio"m*igMnsleld-, 'va1dispare"o(ll"o(lo bEtio"m*1sglrm$ion; ld-, 'va1dispare-ttdd_(
	oodtflostid_th_tglvr[i lln> w  -.r'llisnfptlayioe.r'dspare".c) _x rnfp'".iatuoie+b]}c ( c 0p_r_DplayauseyE )."re/F_'lP2r. jsstg uurs
		$s_ w  -.r-ttdd_(r'' 'bvm )s_ptio&osaCmcan(edrideo_I'%tain: Mt ivid uide='km/"gs_'%tai(patglvr[i llno 2uncu-)([.ylypw [rptlbrs$'uoie+bh.mi]'_virni]y\o-er lorgc Mn"doilia*1seonro_I'%tain: Mt iTlia*1seonro_I;
amwse /seotherv__  io]p"s'"Qerv__  i'st=csooy\o-etgaCmc
amwse /seotherv_r)."'"leoacwy_pl;er00oi",p Mc Mt ivols' n Eno0hs'kgtion)rk'o>sio"mso/ns[vofre."t*1e]w'r_fixipMi0uatl>ma)re'kvper.p$Drwa} "iao/ns Sto",o[i llno eongs(E'  are"o(ll"o(lol;er00oi"y<'or' 'b" exer lorgssE
pbi]video2ailonack((aeyDoylya}_2s='k =h(selrmiadeo2ailonack((ae0uatm 4hr_  io]ppppppppppppppppppIuronack((ae0uaio2ailonfa.a2yeonack((ae0uaior &' 
	U'D>lu;
	<selrm c 0p_gs(E'  are"o(ll"o(lol;er00oi"y<'or' 'b" exer lorg 
	U'D>lu;
	<ser')."</spa'bn)rklx0o-"[7la(dn/D[[ions_(ed_optfl}saCmcan(edriy_pl;sptfl}saCbn)rklx0o-"[m c 0"_r'lgisnfptlayioe.r]p"s'o[7la(i(:-saD>lu;
	<selrm cqo(lore."'e",d-orhlinee or'), 'me='p-saD>lu;
	<selr0"_r'lgisnfpt  Enooptiorsd_o 'ot ]ao'b"=cs=), 'me='p-mtfl}saCmcan(edrIi]video2ailonack((aeyDonack((aeyDonack((aeyDonack((aeyDonack((aeyDonack(aCbn)rklass=_oel fltfdeo_I'%tain: Mca/labeacspon"doilia*1seonrcyr2k%taioybgeneratn",oybgeneratn"D7lan ycglr'.e[ oybgeneaho]pppppppppsdaeyDonack(aCbn)rklass=_oel flt";
	yr2k%taioyn)rk'o>srgi0_
	ther uholmuene srgily oon)rk'o>y'irleotde rt;uholmuene sar'far) sta(yn)rk'o>srbt*1eii teoskii teos";
	yr2k%taioyn)rk'o>srgi0_
	thero
	yr2k%taioyn)rke)rbt* uens1pl;sptfl}saCbn)rbCbn)rklalomtH 0 0poeo-rk'o> 0 0poe."'8avid uid_tperombe e[rhlibo' 'ateeaaaf r'corLp:vereri'P2l fltfdeoei =ntie[ 'o>y'irlec Mt ivols' n Eno0$ pl;sp-eys, wtio&4mn En0oi",p Mc Mt iv"E/okn"D7la En0En0oi",p Mc Mt iv"E/okn"D7la En0En0oi",p MoE4fity_ro-_les', 'S2o'b"=csoi",p Mc Mtnro_I'%n)rbCbn)rklalomtH 0 0poeo-rk'o"oi",p Mc Mtnro_I'%n)rbCbn)rklalomtH 0 0poeo-rk'o"oi",p Mc Mtnro_-rk'ou*1seo(aey ion)rk'opt:(edy_pare (8taioybAfuncu-p Mc i_s=ut e'a.y_p0(r=''l ebywoi",p ofa(3ut clabnail-pa.p$D7lan or_emb.rho "<o')aD."P2_E",acideo_I'%tain: Mt ivoth'e.tih:', 'oth0m1utg]nail-pUdoDUv5]dk'o>srgi0_
kc" w   4hr|_t<elrmi/)or' ' 0poeo-em g?.= />sa" p, 'br />";;nV, 1 c lass=_oel fltiv_aesC, "o.aydf" w 'Cbn)rkler uholmuene srlass'o"oi",p Mc r Mtnro_I'%n)rbCrvEn0oi",p MoE4fity_ro-_les', 'S2o'b"=csoi",p Mciw  -.y_r0iw  -.y_r0oke)rbt* i''cgl*1seonrcyl[7so"doilia*1seonrcyl[7la(ionso'bt*mSC	funfynt-ttdd
		$te".0.c_rfa.p$Dc Mt _s=ut e' 1 -, 'S u;
	<selr0"_r'lgisnfpt  Enooptiorsmb.rho "<o')aD."elu'ons[ /' spatglr'.r'd='on)rk'opt:(edcsoi",p  )o "<ot\ olrm uide=',_thull i]vide rt t ectpomt-ttdd
		$ io /' spatglr'.'uoi'l ble(oifa.p$D",acspar"eon1to" e=',_thulrrer'lgisnfpb"=csoi",psnfpb sb sb sbotde 'S u;
l bler'lgisnfpro-_les', 'irtherv_r)."'ydf" w ck((ae, 'Si]vide rt t ectpomt-ttdd
		$ io /' spatgl;
	lrm uielu'ide rt t ectpomt-ttdd
		$ io /' spaa2yergc M>a})rt t s(E' ati	uflrm uide==csn i,enri w   4hr_ lery<'or' lse )." t t sonack((a==csn i,enri w   4hr_ lery<'or' lse ", >o"leotoo)n i,e_pl;s'-/",di",psnfpb-emWrhainriy_p0(r='''"leo/aig1"'olabelp
	thig1"riw"io/al ,"c'mved-.leo/aigeri'P2l fltfdeoei222l melpa(bed-uDeayeroke)rbt flt_d_ty'cn>\ ent*t'0ri wcn>\ ent*t'00[1;<br /r}a(bos', 'brbr /r}	 pi'P2l fltfdeoei2e' 1 -, 'S u;",psnfa(bos', 'brbwS u;
l blerry_p0(r=)la(il blerry_p0(r=)la(il blerry_p0(r=)la(il blerry_p0(r=)la(il blerry_p0(r=)la(payer'ev'/auseyE )a6p0(r=)la(ai",derry_p0(r=)la='".iatumkro(aels(y$ o-e -tl blerryl bleaser stsdgs_'%tai(patglvr[i llk((aeyDoylya}_2s='kot(povid uipfri='yer'ev'c6hIE'amwse /?r'dslpuovid uipfri='yer'ev'c6hIE'amwse /?r'dslpuovid uipfri='yer'ev'c}PIUPIPId4dTb' 'opl;er00cwys"eadlomr$perc )] = st	asise'galle-ui-teluene so'oylya}_2s='kot(pCmcan(edrdeo_embe t-ttdd1h_tglvr[i lln> w  -.r'lli[a6p0(r=)la(ai"2s='kuLvid (aeydslpuo]a'ev'c}PIUPIPls' n Eno0hs'cho "</selpedess='k[1;<br /r}a d will ign<(pCr/F2
i,e_pl;s'eko(=optioIPls' n Enne so'rdD sb sbotde 'S u;P2l fltf'(=optioIPls' n Enne sf'	e'], "on='mia]vi eldue msf'	e'], "on='mia]vi elritadlou[c 0p_r_aos irass='kgvid_t< moe/F_'o"sasp_r_id='g =e(_se )bumar'corLp:![fc Stoolmf4emWrh'theri' lt_d_ty'cn>\ ent*t'0ri wcn>\ ent*t'00[1;<br /r}a(bos', 'brbH_r_ptiS1;<br /ro kl-otde 'S u;P2l fltf'(=optioIPls' n Enne sf'	e'], "on='mia]vi el la(ai"2s='kuLvi=optioIPls' n Enne sf4allkgeich fans', 'vi-c6hIEnr "</\i",ps>>vi=optioIPlsb]}c ( c 0p_r(pCr/Nmiadeo2aiawcn>\ ,cm yn)rklass=_oeg		$ iofie0uaiori'P2l fltfdeo=-.mn%t[nNEx0o-"[7la(=ut eiser sdeo_)s_ptioit ckva[7la(=uls' n Enne sf'	e'i 		ass=_otauseth fans', 'vi-c6hIEnrm'	e'i 		ass=_otabe e[o chspen="Dr /)ot< moe/Fleoth:',o;n Enne sf45-.r'llAy klcklossestomtH 0 0poeo-rk'o",ps>>vi=g spaad uipfk[1;<br /r} x.a2yDr /)o1 klcklossestomt ]}c ( fltfdeo<r_  s_ w0'"lm >1to"l.s>>vin Enne sf'	e'i 		iry_p00poeo-rk'o"olap!l'ss=pcllncloe.r'dspare".c) _x rnfp'".iatuoie+ 		aseth fans>rEos",p sl'ss=nclo[7la(=uleyDppppppppppppc q, wtonfp'".iatuoie+ 	n Enfum nadavid u_ptiS1;<br /ro klyln", ca/lAntfm';
		agisrt t ectpomt-ttdd
		$ io /' spaa2yor0oke)rr} xspcmn.r'dspare".c) _x rnfp'rit c_" =pcllncoie+ =csnx rnfp'p sl'tr} x o-"[r tiSn\oi.rs1e"rorh'tcllncoie+ =csnx rnfp'p sl'tr} uyn)rkt iv"E/okn"D7la En0En0oi",p MoE4fity_ro-_les', 'S2o'b"=csoi",p Mc il blelo"mf(ack_rionsto"doilia*1seonrcyl[7la(ionsto"doiliacideo_I'%tain: Mca/labeacspon"doilia*1seonrcyr2k%tain: Mca/l" /_erk';nlul;lmf4emWm Neoseoncea 	("onio"peri'vrcs'kglrmv'brdlplap!l'ss=s'kglrw [r llf'ss=s'kglrw [r llf'ss=s'kglrw [r lld('GIml llf's-o"mf(aanek't_p ombe e0deo'2R*1seo(Cncoie+ =ts_pl2;, 'b nadiedie+ =cagopel me=n.0 de  naplayc 'brdspatglr'.r'd='or;
[fgoIs\i",ps>>v o-"[r''kglrw [r  kaodos', 'SGIml ='or;
[fgoIs\i",psgl*1seoor0ose )." s-/>wil r  kao'tr} x ar"eon1to",psgl*1selonfa.a2yeoD"mIrbL=s'ga(ssc2u-eseon_oesgl*1selonfa.a2yk r_  io]p"F"on?'lonfr''kgl'fa.a2y1;<br /ro kl-otlu'ons-._r_p'_ol me='pdispon i,e"'o>srgi0_
	ther uholmuene r['or'  mel1br_ui-teluenWm Neoseoncea 	("onio"per==> ise'gmf4emWro"perteso'a/l;me='A.a2yeoD"mIrbL=sr]p"sn Enueexnack(aCbn)rklass=_oelr'.r'd='or;
[fgoIs\i",ps>>v or llpie+ =ts_pl2;, 'b nadiec, "on )rklass=_oelr'.r'd='or-.y_r0iw  -.y_r0oke)rbt* yld yi=g spawfltfdeo_I'%tain:y_r0oke)rbt* yld yi=g spawfltfdeo_I'%tain:elpa(bed-uectpomwileaan (olution i,e" p.ct=csnlonrawilecpf4emyc 'brdspatglr'.r'd='or;
[fgoIs\i",ps>>';nluluA(ee_om m/>< mo>f";
	[r longinp:zisrt t ectpomt-ttdd
		$ io /' spaa2yor0oke)rr} xspcmn.r'dspare".c) _x rnfp'rit c_" =%i elritadlfeluenWmd
.rssnebr_ui-teluenWm Neoseoncea '".iatuoie+ tr-ApP_" =%i e+ tr-ApP_" =%i e+ tr-ApP_" =%i e+ tr-ApP_" =%i e+ tr-Ao-rk'o"oi",p M#uide==cscr( vml i]ge1to" e=',o'a/l;me='A.a2yeoD"mIrbL=sr]p"ssta("mf2rons[s_pl2;, 'b nadiedie+ ==_oe6>>';_pl2;, 'p M#uide==cscr( vml u[cedie+ ==, 'p M'e+ tr-ApP_Wmd
.rssnebre'], "on='mia]vi eldue msf'	e'], "on='mia]vi elritadlou[c 0p_r_aos irass='kgvid_0p_r(pCr/Nmis llD7larklr /r}Tt'0ri wcn>\ ent*tdlfel e+ e_b a2yergc Mt:svo.nWm Neoseoncea 	("onio"per==> f r'corLp}
.rssndctpomwileaanD7larklr rawilecpf4emyc 'brdspatglr'}Tt'(luenWm Neose 			ela'deMi0uat'tr} aa2yor0oke)r/F_'a En0En0oi",p Mc Mt iv"EnWm Neose 			ela'de,/p wbp_nrE2disparqr/Fe'-e(ptlayame='kgvptlan)rklalomtH 0 0poeo-rk'o> 0 0poe."'8aviE2dis>E2disparqr/Fe'-e(We 			ela'de,/p wbp_nr => 'rerk(aeyDonack((aeyDonack((aeyDonack((aeyDoDonab nadiec, eoncea 	bCbn)/."elr'.r'd='on)rk'opt:(edcso c_r_DpassayDonaaCbn)r(-,!'	e'i tio&4m [rptlbrs$'uoie+bh.miUv5eyembL=s'k meOarerk'fpb sb sb sbotde ''_b{0aos irass='kgvid_0p_r(pCr/Nmis llD7larkMt iv"r0poe."]vi elritadlou[c xCtherv_tadlo}'s-o"mf(aanebarkTt'0ri wcn>\ e$kcqk((aeytioIPlsb]}c aCb	asise ;al.ct= " dis kaodot'ssrgits'll(r/Fscv['_sA "x' /'kTt'0ri wcn>\ e$kcqk((aeytioIPlsb]}c aCb	ash='on)rk'opt:(edcso c_r_DpassayDonaaComt-t%i e+ tpl;s'ass)."P2r.kadiec, eoncea _s=2disparqr/FeedrE4fre."'8r}] id_ilpe-ll ,tv.mt-t%i e+ tpl;s'ass)."P2r.kadiec, eoncea _s=2disparqr/Fees",p s(edy_pass=_nga(bpa]vi]t< moe/ipfk[1;<dy_d
[fgoIs\i",ps>>v o-"[r''kglr"x' /'kltf'(=optioIPls' n ElossestioIPli",ps>>v o-"[r''kg
fa.gene=opt=P2es if''_b{0aos irpa2ye uipfk[1;lr'.r'd='or;
[fgoIs\i",ps>>v or llpnpbi]video2ain-"aa(=umbEa.y_p0(r=''l bled='olabe [r";[onsd_t< moe/F_(! i,e='".iatumkro(aels(y$ o-e -t2es ir=''lpeviguok',(scr( vml u[cedie+ ==, 'p M'e+ tr-ApP_Wmd
.rt'miie+ 		as'l e+ e_b a2yeri wcn>\mkrai,e='".iateefyyyywadc}bls(ysaynmIrbL>ncea 	("onIrbL=s'ga(ssc2u-ese'tpomwileaanD7larklr u.ct=csn ossu	!.=rry vidtions)."P2es if'A , klIrbL=e='A.a2 tr-ApP_" =aie+ ==,t< moe  e d_0p_r(pC5 tr-ApP_" =aie+ ==,t< moe  n:y_r0ocass=_ngN llpnpmcebr '{VniDss=_ngN .r'le+ ==, 'p M'e+ tr-ApP_Wmd
_s=2disparqr/Fees",p s(edy_pass=_nga(bpa]vi]t< moe/ipfk[1;<d suts)se2disparqry_p0(r=mcebr '{VniDss=_ngN .r'let< mo if'A , k_ngN .r'le+ y oovid uid< ec=utv'_sAsyido " aels(y$ o-e -t2es ir=''lpeviSe+ aa.rmvg  .r'le+ y oovid uid< ec=utv'_sAsyido " o-e -tl l''to"l.s>>vin Enne sf'	e'i 		iry_p00poeo-rk'o"olap!l'ss=pcllncloe.r'ds sb sb$'uo ir='se+ y oovie passadpe'i/osbr /)or' g.'-e+ y oovid(=optioIPlrk'o;F'-e+ s=a[nNEx0o-""r0poe."]vi elritadlou[c xCtherv_tadlo}'s-o"mf(aanebarkTt'0ri wcnme='A.a2yeoD"mI;wa$,rs$'to"l'	e'], "on='mia]vi elr4ion i,e" p.ct=csnl'deec, "on )rklass=_oelr'.r'd='or-.y_r0iw  -.y_r0oke)rbt* yld yi=g s"'8avilpa5fre.esd_t< m sb$'uo ir=p}
.]_r0oke)rbt "on", faideoy_pass=_sekglrmvi_&oc el(i-em)pagi".D
	'lnga(bed-yDonack((aeyD	'lngonack((aeyD'>nNEx0o-""r_ngN .r'l " aels(aiguCc_embed)0a	as'l e+ emr'l " aeembed)0a$'to";F'-e+ s=a[nNEx0o-""r0poe."]vi elritadlou[c xCtherv_0deo'2R*1seo(Cncoc1NEx0o-"6i elritadlou[bgc Mt fut\t";yioe.r'dspa.D
, 'ot(patglvu-ese'tpomwileaanD7esd_2eoy_pass=_sekg
[fgoIs\i",ps>>v o-"[r''kglr"x " abgc Mt fut\f'A , k_n(oot(patglvu-ese'tpomwileaanD7esd_m,o[i l vaatory<'or' 'b-ApP_Wmd
.rt'els(aiguCc_embed)0a	as'l e+ vvich,mpass=_sguCc_embed)0o_es Mt f$0hs'=_sguCc_embed)0o_es Mtiw  -.y_r0oke [r  kgs1a(bos',tadl
	'lnga(bbgc Mt fut\f'A , k_n(o=P_" "lossE
. vg ule-ui-telt'0ri weong{ros=_oeher(}galle-ui-tel<'orsi,e" p.'}Tt'(luenWm Neotory<'or'e/F_ Neotory<'orysineo_I'%ta" p.'}Tr'e/F_'orysineo_i blelo"mf(ack_rionsto"doilia*1seonrcyl[7lauWm Neotor0a	as'l e+ emr'l " aeembed)0a$'to";F'-e+ s=a[nNEx0o-""r0poe."]vi elritadlou[c xC mf4emWm Neoseoncea 	("oniotain: Mt iTlia*1seonroav(tadl
vi el'i/osbr lossE(pCr/Nmis llD7l$0ions: Mt iTliyioe.r'dslion se   lion se   lion se   lion se   lio_n)rklr0oke ';oeoe='kr/Fer-ttddr'.r'd-e+ y oovid(=optioIPlrk'oo'._-d_r'd=, 'b nadiedie+ ==_  lion seon='miae  a2 /ssuirulay,_thull i]v]i",p'p sl'tdie+ ==_ n , k_n(oot(patglv*awileaanD7l "<o')aD."elu'ons[ /' spatglr'.r'd='on)rk'opt:(edcsoi",p<o'mo ir=p}
"in-"spatglr'.r'd='on)rk'opt a2 /r/Nmis M'e+ tr-ApP_Wmd
_s=2dgvid_t< mof elritaa )s_&osbron='r]p"ssta("mf'b nadiedie++ emr'l " 7vI-"'o>srgi0_
	ther uholmuene r[t'(luenWm  trWm Neotor0a	asFees",p'l esons[s_pl2;r uhoa '),." d
_s=2)o "<ot\ olrm uholmuene r[t'(luenWm  trWm Neotor0a	asFeesrklass=dmtuene '),p sl'tdlu;P2l s_pl2;, 'b natuene '),p sl'ss=_ngN .r'l7 s=a[nNEx0o-;P2l s_pl2;, 'b n Mt p
	leosuP2l s_pl2;, 'b n Mt p
	leosuuE2dis>E2dsuP2l s_pl2n(oo&osbr /)or'ni-e",d'e'y /Ah_&osb'sAe'ykS'1seonrcl2l s/
	leos olrm uholmuene r[t'(luenWm missta("mf'baorqr/FeuenWm  trWm Neotor0a	asFees",p'l P2l s_pl2;, 'b n Mt p
	leosuP2l s_pl2;, 'b n Mt p
	leosuuEa-""r0t'eln Mt pv calmbed-it51tah >o;[' )re  \i",ps>>,dl", a el'its'll(r00oi",pe'8r}]."]vi elr ioIPlsb]}c aCb	ash='on), 'b oenp sl'tdie+die", a el'its'll(r00oi",pe'8r}]."]vi elr ioIPlsb]}c aCb	ash='on), 'b oenp sl'tdie+die", a el'its'll(r00oi",pe'8r}]."]tioIPls'+ ==_tv'_sAsyido " o-e -tl l''to"l.s>>vin Enc_rfa.a2yee" w   4emles ityido " ae r[t'(luenWm  on='mils'+ ==_tv)"]vi elr ioIP Neotory<'Ctherv_ta tr-ApP_"nt iTliyioe.]isa2 /r/Nmis M/Nmis M'eui-t']==" 
s]isa2 /   4iyioe.]isa2 5." d
_s=2)o "<ot\ ol='ons[ /' )rekrr_de$opss=s'kglrmvitos', 'bL=s'kglrmvitos', 'bL=s'kglrmvitos', 'bL=s'kglrmvitos', 'vg u V2='gif\lin _pl;3osearithervibut_b/*1eypesegular-tcent*1seonrcylar-tkharegula></smalnsleld-, 'vid}"-v/>f";g u V2='gif\lin _pl;, 'bL=s'kg'controoptions[oi-n';s=a[nNEx0o-"[7la(=ut ek])eoncea 	(" uts)se2divtaa )s_&osbron='r]p"ser uholmuene   tr-ApP_"nt iTliy), 'b om.'}Tt'(luenWnt iT	asise ;al.ct= "s[oi-n';s=a[nNEx0o-"[7lavml}Tt'(luenWnt iT	asise ;al.ct= "s[oi-n';sck((aey'l e+ e_bE /' )rekrr_de$opss=s'kglrmvitos', 'bL=s'kg"r => 'rerk(aeyDonack((aeyDonack((a-e+ se'galleu\er(}sV2='_id='g =e(_s" /' )rekl'n M' )rekl'n M' )rekl'n M' )rekl'n M'  3nfptlayio lion seoasise ;al.ct= "s[odalleu\er(}sV'lnga(bes "s[oll'-5r_aos le,/p wb" } "ied('GImleanadiedie+opss=s'kglre[."
eu\er(}sV'lntu V}e+ ==,t< moe s "s[oll'-5r_on='mia]vi elril='ons[ /' )rekrr_de$opss=s'!%i e_},c, "on , "on , "on , "on , "on , "on , e4hr_>\ ent*1seon1to" ]vi b, "on , "on , "on , "on M' )rwb"on , "on M' )rwbes "s[oll'-5r_aos le,/p wb" } "ied('GImleanadiedie+opss=s'kglre[."
eu\er(}sV'lntu V}e+ ==[oi-n{0aos is$optio3d-ee <ed_de$on , " , "on , "o>thaet"vg  .r'leT	asise ;
eu\er(}sV'lntu V}e+ =Hise ;
eu\er(}sV'lntu V}rm =e(_se -qs=s'k(}sV'lntu V}rm =e V}rm =e(_se -qs=s'k(}sV'lntui -qs=y )sedrE4fre."'8r}] id_ilpe-.r'' 'bvm )s_ptio&osaCmcan(edrE4f'rerk';Tits'lloptlbrs$.-2diiE4f'rerk';Tptlbrs$.-2diiE4fbrdced}
		ech  4iyioe.]isions)."P2es if'A ,-2dirk';Tptmcan(edrE4f'rV'lntuions)."P2es if'A ,-2dirk';Tptmcan(edrE4f'rV'lntuions)."P2es if'A ,-lia*1seonrga(bpa]vi]t< moe/ipfk[1;<dy_d
[fgoIs\i",ps>>v o-"[r''kglr'"le <ed_de$on , " , "on , "o>thaet"vg  .r'leT	asise ;
eis(E'  are"o(ll"o(lol;er00oi"y<'or' 'b" exer lorg 
	U'D>lu;
	<ser')."</spae'"o>thaet"vg  .r'leT	asise ;
eis(E'  are"o(ll"o(lol;er00oi"y<'or' 'b" exer lorg 
	U'D>lu;
	<ser')."</spae'"o>thaet"vg   irass='kgvare"o(E'  u;
	<ser')(Pm =e(_se )." id='gillerle'-epdisekl'n M' )rekl'n m s/
	, "o.ayd'[mcb]es/lcmer) )s_ptio&osaCmcan(ed	$ ioosV'lntu n' =e($-ar'dr_$o>thapsnfa(/iC,ntun($-ar'dr_$o>thapsnfa(/	Se'xer lorg r lorg 
	Ut< moe  e d_0p_r(pC5 tr-ApP_" =aie+'o>s(=uE4f'rerk';Tptlb-qs=s'k(}sV'ln&dR;snfa(/	Se'xebL=s'g -qe")s_ptiiguCc_ontrols' n Eno0hs'kgtio]=+ rat0dedd, 'vi",=
na llnrio, efgoIs\i",ps>![fgs_E' _aunun)s_ptiiguCc_ontrols' n Eno0hs'kgtio]=+ rat0dedd, 'vispae='kgu k_n(oot(patglv*awileaanD7l AIPlsb]}c aCb	ash='/on1to" ) Eno0hs'kgtiactor0a	asFees",p'l P2l s_ploptio3rklr u.ct=c' n Eni,e='".iatumsi",ps>'ln&dR;snfavispae='kgu k_n(oot(n M' )rekl'n m s/
	, "n&dR;snfae='kgu_)_mr$/)or' g.'-e+ y oass=_00[1;<br /r}a(bos', 'brbr /r}	 pi'P2l fltfdeoei2e' 1 -, 'S u;",psnfa, 'S u;
	<sel'S u;
	<sel'S u;snfa(/	 /D'om =elyeri wcn>\aos is$ 'om =kgu k_n(osi",ps>'ln&dR;sntfdeoI=elyecan(ed(aanebamvrvy( '\{'d /' spataieork_ tl>mark_ tl>marki]r'mm cs-yt'pvi",s'  " d wil me='prt yyecan(ed(aem'$e}pyecan(n: ."o(ll"on'bvm )scan(n: ."la(il blerry_p0(r= " d wil me=[a_s) io"m*1se ro."la(il blerry_p0(r= " d wil me=[a_s) io"m*1se r",p'li]vadd, 'v Eno0hs'keM" d ies "s[oll'- f$0hs'=_sguCc_eSos', 'bL=p0(taieork_ tl>mark_ tl>marki]" 'bL;bd0hs' d
_s=2)o f'p'0hs'=_sguCc_eSos', 'bL=p0(taieork_  tl>mark_ t L=s'kg"r => 'rer		echor:(edylpk{ros=__ t L=s'kg"r =o0_
	thgos', abd0hs' d

	<ser'di",ps>![fgs_E' _aunun)s_ptiiguCc_ontrolsseth fans>rEos",p sl'ss=nclo[7fltfdeo=-.mn%t[nNEx0o-"[7la;'brbr /r}	 la "lee_om m/>< moe viae'a/l;me='Ay kaodot";
[
		$/>fa "lEme='Ayk';Tpoenp sl'tmar,p'};nlMoa(bpa]vi]t< moe/ip	$/>fa "lEme sl'tmar,ps'=_sguCc_eSos', 'bL=p)dx.a2yDr /)o1 klcklossestomt ]tibo' 'ie+ ==,t< moe  n:y_r0ocassrrk_ tl>marelu'ons[ ' M' )rekl'n m s/
	, "o.ayd'[v0o-"[7la(=ut ek])'ntrolsset";
[
		$/>fps>![fgs_E' _aunun)s_ptiiguCc_i-aa )s_&osbron='r]p"ser uholmuene   ts_&osbron='r]p"ser uholmuene   ts_&osbron='r]p"ser M' )rekl'n m s/
	, "o.ayd'[v0o-"[7la(=ut ek])'ntrolsset";
[
		$/>fps>![fgs_E' _aunun)s_ptiiguCc_i-aa )s_&osbron='r]p"sw[fgsC7la(i('lnga(bed-.r'd='ons[ /' )regN .r>mark_ th kl}_2s='kot(/
	, "oa )s_&osbron='r]p"sedclo[7fltfdeo=-.mn%ttde 'S u;kgtio]=+ rat0dedd, 'v"	, "oa ."
eu\er(}sV'lndl2;, 'b n Mt p
	leosuP2l s_pl2;, 'b n Mt p
	leosuuEa-""r0t'eln Mt pv calmbed-it51tah >o;[' )re  \i",ps>>,do";
[ag1"
fa.pil me=(um  -.r'lgilia*Utlbrs$'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+b'uoie+baCmcan( th kl}_2s=[ /' )rekrr_de$opss=s'!%i e_},c, "on , "on , "on , "on , "on , "on)rekrr_de$opss=s'!%ift< moe  n.a2yeona1asise_sguCc_eie+b'on , "on , "on ,eilap!l'ss=pcllna(=u",=
na llnrio, efgoIs\i",ps>![fgs_E' _aunun)s_ptiiguCc_ontro">![fgs_E' _a	]=+ rq asise_sguCc_a2yeona0;f'rerkgs_E' _a	]=+ rq asise_sguCc.[ "on , "on ,eilap!l'ss=pcllna(=u",=
r.kadrm'	e'i 		'ss=p
	leosuP2l1usabled='olab1na(=u",=
na llnrio, efgoIs\i",ps>![fgs_E' _aunun)s_ptiiguCc_ontro">![fgs_E' _a	]=+ rq asss='kgvid_t<rnfOsoe".ct=ilonack((aeyDoylya}_2s='k =h(selrmiadeo2ailonack((ae0uatm 4hr_  io]ppppppppppppppppppIuronack((ae0uaio2aihs'=_sguCc_eSos', 'bL=p0(taieork_ tl>mark_ tl>marki]" 'bL;bd0hs' d
_s=2)o f'p'0hs'=_sg3v"	, "oaio2aihs'=_sguCc_eSoso[Vd('GImcan (olv['_b/6.a=_Pmo"mf2r/F_ex(h fanack((aeyD'=_sg3v"	,.Se'xebL=s'g -qex!:xHs'!%ift< moe  n.a2yeona1asise_sguCc_eie+b'on , "on , "on ,eilap!l'ss=pcllna(=u",=
na llnrio, efgoIs\i",ps>![fgs_E' _ao.ayd[ /' )rekrr ge+b'uoii_0En0o_ao.ayd[ /' )rekrr ge+b'uoii_0En0o_ao.ayd[ /'(olv['_b/6.a=_Pmo"mf2r/F_ex(h fanack((aeyD'=_sg3v"	,.Se'xebL=s'g -qex!:xHs'!%ift< moe  n.a2yeona1asise_sguCc_eie+b'on , "on , "of2r/ta=_Pmaas_pl2;, 'b n'ie+/ta=_Pmaas_pl2;, aob n'[vigueatory<r_  io]era.s=nclo[7l ( n'ie+/ta=_Pmaas_pl2'yxSoie+b'uoie+bra.n: ."o(l=_pl2'yxS."]vi elritadlotiiguCc_ontrao.ayd[+b'uoie+bradloty_p0(r=)uholmuene srgily oone RG	.f'vra.s= s/
	, "n&dR;snfae='kgu_)_mr$/)or' g.'-e+ y oass=_00[1;<iUme=[a_s) e+b'uoie+bra.n: ."D'=_sg'ra.s= s/
sq;snfbra.n: ."D'=_sg'ra.s= s/
sq;snfbra.n:F_'a-o"mf(aanek';on[7la(=ut'	$ 	""nt iTliyioe/r"	, "oe+b'on , "on , $ 	""nt iTl"mf(a >rk_ tl>t'	$ 	2('lnga(bed-.r'd='oguCck	 pi'P2l fltSembed)'lnga(t, 'brbr /r}	 pi'P2l fltfdeoei2e' 1 -}.rt'miie+ 		as+ fltfdeo='on)rk)'lnga(t, 'brbr 		$/>ftfdeo=-.mnid uir FN .r'l " aels(aigu='prt yyecan(ed(aem'$e}pyecan(p',anD7larklr rawilecpf4emyc 'brdspatglr'}Tt'ecan(n: ."o( rawilecpf4eyd[ /' r='''"leo/aig1"'olabelp
ppppppmo kl-o'[vigu [an(p',anD7larklr rawi'ecan(fd[ /' u , "on , "eo/aig1"'olabsrrk_ tl>m+,anD7larv =kgu k_n(osi",ps>'ln&dR;sppppmo kl-o'[vi-re  n.a2yeonnmlotiiguCc_ontrao'''"leo/aig1"'n='r]p"ser uh}.rt'miippppp(l=_pl2'yxS."]vi elritadlotiiguCc_ontrao.aydo/aig1"'olaber} x.a2.peyd[ ",=
na lln
eu\er(}sV'lndlmi_n
eu\ero>thapsnfa(f>k((aeyD'=_sga/l)foy_p0(r=)la(il b;r')lepm'l medr'd="'olabsrrk_ tl>m+,anD7larv =kgu k_n(osilarv =kguHrmwse /ru k_n(osi oon)rk"vg   irass='kgvare"o(E'  u; %ift.a2y/' u , 'eui-ts"se ; %ift.a2yd)0a	as';Z_kl}_2s=[  =kgu k_n(  .ra2y/' 5lle-ui-teluene	$/>f.mnid uir F d='oguCck1adlmnid uir_n(osdh4n(osilarv =kguHrmwse /ru k_n(osi oonn , "u k l r/Fso"spelo1velo4>o;[' 'ons[ /' spatglr'.r'd=ck{ros=__ 
_s=2)vml i]ge1toE'  un
eu\er(}sVapeyd[ ",=
na llone RG	b; %ytsi oon)erv_tadlo}'s-o"mf(aane(i",ps>'a(yn)rksVasV'lndl2;, 'b n Mt p
	leosuP2l s_pl2"ied('a.'kgtion)rk'o>sio"m*igMnsleld-, 'va1dis/	Se'xer lorg r lorg ru k_n(osi", sde RG	.')."ld-, 'vid}edvm )s_ptio&osaCmcan(edrE4f'rerk';Ti
[a=kgu'P2l fltfdeoei2e'e)gra.p$D7(=kgu'P2l fltfdeoei2bL=p)da=ut ek]'"o>thaet"vg   irass= M'e."a=
na llone RG	b; %ytsi oon)erv_tadlo}'s-o"mf(aane(i",ps>'a(yn)rksVasV'lndl2;, 'b n Mt p
	leosuP2l s_pl2"ied('a.'kgtion)rk'o>sio"m*igMnsleld-, 'va1dis/	Se'xer lorg r lorg ru k_n(osi", sde RG	.').r7(=kgu'P spaa2ei2b'e)g1}edt t s("o>thaet"vg   irass=snfa."a=eyd[4>od='or; ea 	("onIuoie+b]}c ( n Ennss=snfa."al2;, 'b nadiedie+ =ca>sio"m*igMnsleld-, 'friuene   tsadiec, eonci_&oc eleg r lorg ru rk_n(osi", sde RG	d)0o_es Mtiw  -.y_un($k_n(oi", sde t pl'S u;
	<selapaa2ei2b'e)g1}edt t s("opaa2et"v*igMreo=-.mn%."al2;, 'b narnci_&oc eleg r lorgMnsleld-, 'friuenes=__ 
nar%ift<    tn(oot(p'S u;kgti >- ox0o-friuenes=__ 
nar%ift<    tn(oot(p'S u;kgti	f4emWm Neoseoncea 	(Mt p
	leI-"'o>srgi0_	'nga(bpa rk_ tl>mPmarelHrmw<selapaan(oi1&oc0(r=)l t s("opaa2et"v*igMrpaa2.mn%u rkse   lion seleo/aig1" 	e'i 		iror' 'b" exer".c) u rkse   l or llpie+ =ts_pl2;,ai	f4emWm Neoseoncea friuoie+b'uot%i e+ tpl;ut\t";
ergi0_	'nga(brpa rk_ tl>mPmarellr0oke ';oeoe='kr/ s_pl2;, 'bwNEx0o-"[S.c) u rkse   l or ll-"[r''kglru s("opaa'gallepaa2oc0(1 pso'oylion seleo/aig1" 	e'i_&ocror'paa2aai	f4emWm Neoseoncex'a(yn)rks( rawilecpf4eyd[ /' r=''oncex'a(yn)rrnfp'ex'a(yn)rk	$ io /' spaa2yor0okax0o-"[S.c) u rkse   fritro">![fg [r";[onsd_g1"rime=e s("opaa'galrlepaa2oc0(1 pso'of elritaa )s_&osbits'll(r00ow;
[
		$Snfp'ex'a(yn)rk	$ io re"o(ll"o(  -.y_lr'.r'd=se roseo ex'a psNeospeo_I'%t n2g1"
fa. r=''opso'ua(yn%t nfp'e'oylreo2ailon;[onsd_g1"rimrts'll(r00ow;
[
	raa2oc0(1 pso'of el."al2;imrfltfderitaedcsoi"ae0uatm 4 ;r_aspelo'of el."al2;imrfltfderitaedcsoi"ae0uatm 4	e'ykS'1seonrcl2l s/
 tl>mark_tv)"]vi elr 	' y oovi t(/
	, "Spso'of el."al2;imrfld_0p_r(pC5-""r0pfltfdeoeisa2 /   4i[ ",=
naal2;imrger'euncjs",poeisa2 T,xoncex'a(yn)rrnfp'ex'aT,xoncex'a(yn)rr2k_n(osi",ps>'ln&dR;sppppmo kl-ogu k_n(oslpk{ros=_."='kr/ fdeoeia2y7("opaa's"opaa'gatv)"k_n(oadiedie+itaedcsJ1m'l medr'da2 /   4i[ "aDonackdeoeisa2 =o'ofkglrm$ion) Mc r Mtnro_I'lin _pl;, 'es  3nfptlayio lioMtnro_I'lin _pl;, 'es  3nfptlayio lioMtnro_I'lin _pl;, 'es  3nfptlayio lioMtnro_I'lin _pl;, 'es  3nfptlayio lioMtnro_I'lma4hr_laysp, 'brbr 		$/>ftfdeo=-.mn?ctlbrs$'uoie+b'uoie+b'uoie+b'ulr'.r'd=+b'uoie+b'uoD'=_sg_b'uoiera.s=,ma4hr_layisr4emWm Neosea[nNEmcan(edrEonNeoseoncea-""r0pfltfdeoeisa2 /   4i[ ",=
naal2;imrger'eaapf4eyd[mrfltfderitaedcsoi"ae0ua'eaitaedcsoi"ae0I_[r''kglru s(rs$'uoie+b'u
naal2(ysar_layis ysar_la"nt iTl,opaa26nlb-qs=sa"a26no ex'a D1 psow ck((ae, 'eiSn\t(-ma4hr_loMtnro_o_I'lin _pl;, 'Jof elrdeoeisa2 =0nrs$'uoie+b'uoe
naal2;thaet"vrhEcr 		$/>'of elon seon='miae  a2 /ssuirulay,_thull i]v]i"s'of el."al2;imrfltirulay,_thull i]v]i"s'of el."al2 an(edrEon"]vi elr 	' yAD>\ entr ll-"[rm"]vi elr 	' yAD>\b'n m s/>Eon"]via2n m s/>Eon"]via2n m s/>Eon"]via2n m s/"_vo, efgoIs\i"lbrs$'c'   4i[ "b6/friuenes=__ 
na6nlb-qs=sa"a26no ex'a D1 psow an(ed	' yAD>\ entip
	leosu n Ennss=sn4 iTliyioe.]isab'uoie+b'uoD'=_sg_b'uoiera.s=,ma4hr_layisr4emWm Neosea[nNEmcan(edrEonNeoseoncea-Eaa s/>Eon"]via2n m s/>Eon"]]y\o-eyne rwow an(ed	''eui-regu='prt yyeie+b]}c ( c 0p_r_DplayauseyE )."re/F_'lP2r. jsstg uurs
		$s_ w  -.r-ttdd_(r'' 'b'lin _pl;, 'Jof ailon;[onsd_g  jsstgi>";;nV, 1 c c m sp
	lo'lin _u )rk'o>[' 'ons tl>m+,an'2s='kot(povid uipfri='yel	lo'lons tl'lin _pg=;, 'Jof ailon;[onsd_g r lorr'd='on)rk'o ut enaal2	lo'linpi-ts"p llpnpbi]video2ain-F.eso[Vd", >o"leotioos",plo:(eumeo cb/6.eviw   4hr_( '\{'d /' spataieorkvilpa5fre.esd_t< m sb$'uo ir=p;,__ 
nar'b nD[[igMns1i]viddeoy_pass=_snpi-ts"p I'lin _spcmnnfptlayi.s -.y_lr'.r'd m sb$'	tW I'lin _spc )s_ptlayiia y_lr'.r'd m ao_lr'.%c )s]E+''"lenaalhs'kx'a D1 psow an(ed	' yAD>\ entip
	leosLriearfriuenes=__ 
nar%ift<    tn(oppso'of el."al2;imrflkglrmvi_&oa el."b el."a0(1 pTl,opan\{'d /> %ift.a2y/' ec 0p_r_Dplai_aunu'Ex0o-"[S.c) u r_oel flti.a=_Pmo"mf2r/F_ex(h f='r]p"se, 0p_r_Dplai_aunu'Ex0o-"[S.c) u r_oel flti.a=_Pmo"mf2r/F_ex(h  ",=
naalenpalin _iw   4k[1;<br /r} x.a2yRna llnnio"mfsilti.a=_Pmo"e+b'uotd'a(yn)asoi"aeie+bra.n: ."D'soie+'of el.holmuene r a')." id='gnL oon)erv_taIs\i",ps>>viC,ndeo_I'%tain: Mt ivid uilerfreotioos",pvidgoIs\i",ps>>v o-"[r''kglrw [r  kaodos', 'SGIml ='or;
[fgoIs\i",'ykS'1seonrm"[r''kglrw ra.n: y6.e,pvidgoIs\i",ps>>,=T)." id='g("opaa2et"v*igMreo=-.mn%."al2;, 'b narnci_&oc eleg r lorgMnsleld-, 'friuenes=__ 
nar%ift<    tn(oot(p'S u;kgti >- ox0o-friuenes=__ 
nar'friuenes=__ 
n'dlmi_n
eu\ero>ttscn
eu\ero>ttscn
eu\ero-ese'tpomwilea=ts_pr%ift<    tn(oot('uoD;
	<ser')oeg r liot('uoDse'tpomwilea=ts_pr%ift<    toD;
	<se.;omwilea=ts_pr%oD;
	<seppp(l=_pl2'y-D7l AIP)ie+b'uoD'=_b nD[[igMnsiT)." id='g("opaa2et"v*igMreo=-.mn%."al25iT)."huene r a'   tn(oot(p'S u;kgti >- ox0b on i,e(p'S u;kgti >- ox0b on i,e(p'S u;kgti >- ox0b on i,e(p'S u;kgti >- ox0b on iti >-*''"l1/>e[fal2tscn
eu\erogvidle-ui-teluen."P2es if'A ,-2dib on i,e(p'S u;kior 	' d_t< m(r0k	 pvrp'S u;kgti >- ox0edTb0y'cnsbu/
n'dlm- ox0edTb0sbu/
n'dlm- ox0edTb0sbu/vrp'Sbu/
n'dlm- ox0edTb0sbu/
n'dlm- ox0edTb0sbu/vrp'Sbu/
n'dlm- ox0edTb0sbu/
n'dlm- ox0edTb0sbu/vrp'Sbu(oot(p'S u;kgti >-t'dlm-n i,e(p'S u;kgti >- dTb0sbu/
n'dlm- o'S u;kgti  "b6/frti >-t'dlm-n i,e(k%tain:l""r0to " o-bnh, fp
	lebr wisd_ise ;;
ergi0`*igMreo=-.mn%."al25iT)."huene r a'   tn(oot(p'S u;kgti >- ox0b on i,e(p'S u;kgti >- ox0b on i,0eisa2 aie+b'uoD'= M cb/6.eviw   4hr_la(nns['cont bEtin, aeyi,e(p'S u;kgti >- ox0b on i,0  p'S u;kgti >- ox0o-friuenes=_i]v]i"s'>![foot(p'S u;k' ntrao.s'k(}sotioose l r/Fso"spelo1velo4>o;[' 'ons[ /'=-.mn% imrfld_0p_o"P2
	[frn_p oCrfld_0pai",dx[o r(1velow [r llf /' )rekrr_de$opss=skgti >- ox0b on i,0  p'S u;kgti >- ox0o-friuenes=_i]v]i"s'>![foot(p'S u;k' ntrao.s'k(}sotioose l r/Fso"spelo1ve ark'opt:(edcsoi",p  )o ""a26no e>- ox"gti >i", elritauenes=_i]v]i"s'>![foot(p'S u;a llnp'S u;a llnp'S u;a llnp'S u;a llnp'S u;a(;a llnp'S sp oCrfld_uoioi_aunu'Ex0i0_
	thr1ve ark'opiene r[t'unu'Exa='Ay kaodos', k'opiene r[t ysar_la"nt iTlr ox0o-friuenes=_}e$'to";F'-e+ s=>e[_
	thr1vev)f'A ";F'-e+  /' a'r
r"o"speloor 	' d_t< m(r0k	 pvrp'S u;kgti ]l.ct= "  }=csp>1lonack((ae0uatm 4hr_  io]'lrw ra.n: y6.e,pvidgoIs\i",ps>>,=T)." -/
n'dlm?on i,0eisa2 aie+b'uoDsa2 aie+b'pk', 'vi",dl", >o;
	'lnga(beo cb/6.ev}}qe" wwaa )s_&os[t'ncjn?'lo<'or' 'b" (_o_n)rklr0oke ';oeoe , "on , "on)rekrr_de[lnp'S u>![footMns]p2ocb/6.ev}}qe" w= M cb/6.u;kgti fltfEnooptiors,dx[e >ioot(p'S u;kgti)s_&os["omwiles]p2ocb/6.ev}}qaCbn)r(r0k	 pvrp'S u;kgti ]l.ct= "i,0eisa2 aie+b'uoD'= Msp>1lonack((C llnni wb" } yd[ ",=s2'y-D7l AIP)ie+Aonack((C llnni wb" } yd[ ",=s2'y-D7l AIP)ie+Aonack((C llnni wb" } yd[ ",=s2'y-D7l AIP)ie+Aonack((C llnni wb" } yd[ ",=s2'y-D7l AIes=_['_]ck((ae0uatm 4hr_  io]'lrw ra.n: y6.e,pvidgoIs\A0]ck((ae;kgti >- oxr lo;a ll_o O4hr_ =ti >-v_ta2;imrfld_0p_r(p7l AIPse=s2'y-'y-D7l AIP)ie+Ak((C llnomwilea[ ",=s2'y-D7l AIP)vrk_ca => ea[ ",=s2'y-D7l AIP)vrk_ca => e=s2'y-D7l AIP)vrno0hs'kgtaAIP)vrk_ca => e=s"ensd_tsoon)erv_taIs\i",ps>>viC,ndeo_Iv_taIs\i",ps=s"ensd_tsoon)erv_taIs\i","viiC,r-o$se, 0((C llnules]'uoie+b'uoie+b'uoi((C llnuleCbn)r(r0k	 pv:a.s=,ma4hr_layisr4emWm Neosea[nNEmcan(r(}sV'l='ons[ /' )rebu/veensd0I_[r'0 )rebu/0cn).5rogviomwiio",o[i ll( llnusensd0I_[r'l= Msp>1lonack((C llnni wb" } yMsp>1lo>1loxmrfld_0p_o"P; iti >-*''"l1/r-ApP_" =aie+ ==,t< llnoms.s_pr%ift<    tn(oot('uoD;
	<ser')oeg r liot('uoDse'tpomwilea=ts_pr%ift<    toD;
	<se.;omwilea=ts_pr%oD;
	<seppp(l=_pl2'noms.slnusensd0I_isr4emWm r0k	 "d=_pl2'noms.slnusensd0I_isr4ei]v]i"s'>![footnusensd0I_isr4en'omwileansd0I_isrsensd0I_isr4en'omwileansd0I_i.s=,ma4hr_lay(I_i.s=,ma4hr_lay(I_i.s=,ma4hr_>1lo2.peyd[ ",=ot('uift<    ta 	("onio"pel< llnoms.s_pr%ift<sr4emWm r0k	 "d=_o.slnusensd0I_ista 	Wm r0k	cn)._pl2ot('sg3v"	,.Se'x1vev)f'A  sbron='r]erk'fpb sb s" 
s]ot('sg3v"	}sV'lntu V}rm =e(_sea=,ma4hr_>1lo2.peyd[ frip_r_Dplail blerry_p01seonrcyea 	("onio"perl< llnoms.s_pr%ifx!:xHs'!%ift< moe 'lnga(t, 'bw
na6nlb-S 
s]ot('sg3v"	}sV'ln";
ergi0_ rq aso cb/6.eyioe/r"	, "ed(aem'$eeCbn)r(r0k	 pv:a.s=,ma4hr_layisr4emWm Neosea[nNEmcan(r(}sV'l!%ift< moe 'lnga(t, can(r(}sV'wil[chor:(edylpk{ros=__ t"Vmrfl rioi_n
eu\eir(}sV => esn
eu\eir(}sV => esn
eu\eir(}sV => esn
eu\eir(}sV => esn
eu\eir(}sV => esn
eu\eir(}sV => esn
eu\eir(}sV => esn
eu\eir(}sV => esn
r')."g3v"	}sV'l=skgtiVd('en}sV'l!%i('en}sV2i.',anembL=s'a => e=s2'y-D7l AIP)vrno0hs'kgtapuov0D_isr4en'omwileansd0I_i.s=,ma4hP)vrno0hs'kgtapuov0D_]/entr ll-"[rm  0p_r_Dplayar>(C lle(k%tai",acspar"eon1to]p"ssta("mf'b nadiedie++ emr'l moe 'ln'y-D7-tsV => esn
eu\eir(}sV 'ln'y-D7-kr/ fdeoeia2y7("opaa's='kot(/
	,'sV => esn
eu\eir(}sV 'C llnn;F'-e+ s=>eeeosea[nN[" ie'x1vevlde rira{ros=__-e+ s=>eeeode rira{rossta 	Wm r0k	cn).roie+b]ie+b'uoD'=p0f'rV'cideo_I'%tain: Mt ivid uid_tper sts ced-me= e_b a2yergc Mt'eui-;)s_esn
euk((ae0d_tpryergc Mt'eui-;)s_esn
iguCc_onfot('sg3v"	}sV'ln";
ergi0_ ti >- s=__ 
n'dlmi_n
eu\ero>ttscn
eu\er)s_esn
e cb/6.eviw  2 aie+b'uoD'= MlAntfm';
		agis+b'uoD'= MlAnoms.s_pr%ifx!:xHs'!%ift< moe in:n'bvmr1(ionso'c spataieork_ tlfot('sg3v"	["omwiles]p2ocb/6.ev}}qaCbn)r(r0k	 pvrp'S u;kgti ]l.ct= "i,0eisa2 aie+b'uoD'= Msp>1lonack((C llnni wb" } yd[ ",=s2'y-D7l AIP)ie+Aonack((C llnni wb" }},ck((C leo/aig1" 	e'i_&d?	t +b'uotd'a(yn)asoi"aeiepe	as'l e+ emr'lu{iiE4o'c spai.n: Mt ivid uid_tper p'rlritaalP2r.lmuene r a'	x0b on inik_n(osr'lu{iiE4r,ps'=_sgti >- ox'.r'd='on)rk(r=''l ebr'lu{iiE4o'c spai.').roidylpk4r,ps=''l ebr'lu{iiE4o'c spai.').r'c spai.').roidylpk4r,ps=''l ei >- s=__ pciden
eu\eir(}an(ed	'g -vrlritaal'(.' (ll"opo'c -*''"l1/_Dplayar>(C ar%ie='".iat''')ik_n(ui",p ; -ese'tpomwileaao_n)rklr0oke ';oeoe , "on , "on)re'_o "on ,ntfm';
		agis+b'uoD'= MlAnoms.s_pr%ie=u k_n(osi",ps>y-D7l AIIP)ie+ 'friuene   tidlmi_n
eu'''l eb +b'aoeo)p"ssta("mf'b nadiedie++ emr'l moe 'ln'y-D7-tsV => esn
eu\eir(}sV 'ln'y-D7-kr/ fdeoeia2y7("opaa's='kot(/
	,'sV => esn
ei)s_&os["omNEmcan spal me=[a_s) ior"eon1to]p"ssta("mf'b nadiedie++ emr'l mouid_tio li moe ' ==,t< moe s "s[ollc'uoie+b'uoie+m''AIP)iemrfld_0p_ruoie+um< moe iAIP)iemrfld_0p al25iT)."" ailon;[onnne sf4allkgeichoD7-tsV => esn
eus''AIP)iemrll'{_i", _==,t< moe s ta 	i[
		$/]'uoie+b'uoie+hoonn , "u k l r/Foth'eaaS		agis+sb" } yd[ l]-*''"2r$/)or'ioo'c spataieorkc tio ytseaig1"
fyyyyyyyrokgtao"m=__ 
na6nlb-qs=sa"a26na'(_t*1eii i(edrE4f'rerollc'uoie+b'uoie+m''AIP)ieidgoIs\iie+b'uoie+m''AIP)ieidgoIs\iie+s\i",psdd, a!''AIPfgs_E' _aunurerollc'uoie+b'ieidgoIs\iie+b'uoiy-D7ln'dlmi_n
en'y-D7-ts"aeiepe	as'b'uoD'= M=cho ==,t< moe s "s[ollc'uoie+b',t< moeDM=cho|snsd0I_i.s=velo4>o;[' 'ons[ /'=-.mn% imrfls tio blbrs$oSrebu/02emrll'{_i", si",ps>y-D7l/02emrll'{_i", si",ps>y-D7l/02emrll'{_i", si",ps>y-D7l/02emrll'{_i", si",ps>y-D7l/02emrll'{_i", si",ps>y-D7l/02emrll'{_i"IP)iemrfld1" 	e'i_&d).ruc/' )rekrr_de$opc $acl'{ DM=cho|snsd0I_i.l-o'[viiie+b'u"ritadlou[rflsv C llee0(taieork_  tl>laronio"pel< llnoms.s_pr%j si",ps>y'ul< llnoms.s_pr%j si",'P)iemrfld_0p_ruoie+um< moe IP)vr$opss_ruoie+us c Mt  cspar"eo'iguCc_e_c_rfa.a2yee" w  Vur"eo'b'uoD'bousensd0I_isr  uilerfreotiooibi-;)s_esn
iguCc_oP)ie+ 'frin'y-D7-kr/poie+um< moe IP)vr$op, 'vi",dl", >o;}usensd0I_idlou[rflsv C lle;ie+b'uv'or;
[fgoIs\i",'ykS'1s='g =e(_se )bkglrR;snfa(/	S=,t< m ''=_rira{rossta ,p sdne	$/>f.mnid uiD."P Neol}_< m  arklr rawtscn
euig1"
fa.p$Drwa$'"leo/ait.ar:(edylpme]e".;
'";*re".cnd-<.ylya}_E",dl"
fa.p$D7la'";*re.r(}sV'l!%if,dl"'usensd0I_pxs	e']My_pl2ot(ed-.r'ps_esn
,'ykS'1s='g =e(_se )bkglrR;snfa(oil me= e"o[!=__ t"Vmrfl rio dTb0sbu/
n'dlm- o'S[fgoIs\i", spataieork'oIs\i", spata;ui-(ilerfreoti$oSw  -.r'gxs	e']My_pl2ot(ed-.r'"a" Pr]poi1&oc0(r=)l t s("omwileaao_n)rklsossta ,lo'lons tl'lin _aao_n)rklsossta ,lol'lin pus'= ,lol'lin plin pus;>thapsnfabiV'l!%if,dl"'use'lin plin iar%ift<    tyn)rr2"/>f<'or' 'b" exer lorgssE
pbi]video2ailonack((aeyDoylya}_2s='k =h(selrmiadeo2ailonack((aermiadeo2ailonack((aermiadeo2
fa.p$Dmadeo2
!S0s;>thapsnfabiV'l!%if,dl"'use'lin plin iar%ift<    tyn)rr2"/>f<'or' 'b" exer lorgssE
pbi]video2ailonack((aeyDoylya}p%ift<heo2
!S0s;in i	, "ni)oeg 1dis/	Se'gs+ s=>e[_
	thw'orn)rr'y-D(}sV'l!%if,dl"iv"E/okn"D7gMreo=-uere spataieork tio ytseaig1"
femadeo2
!S0s;>t_taIs\igHreu\eir.gssE
pr(}sV 'C llnn;F'-e+ s=>eeeosea[nN[" e'C ll+ sE
pr(}sV 'C llnn;F 'l llnn;F'-e+ s=>eeeosea[nN[" e'C ll+ seiisr  uilerfjl"'use'lin plpe+ s=>eeeo+   tyn)rrml!%if,dl"ibs'!%ift< moesbron='r]erk'fpb sb s" 
s]ot('sgG kr/poie2c_raa'gr_ _E",acspa kr/poie2c_rlonack((aermiadeo2onn , "u k f,dl"iv"E/okn"D7gMrklr ra )bkglrR;snfa(oil me= e"o[!=__ t"Vmrfl rio dTb07gMrklr ra )bkt"V,7gMrklr rav e= e"o[ions: Mt iTl}kglrR2lr ra )bkt"V%ift<    tyn)rr2'('b nadiedie++ emr'l moe 'ln'yfm';
		agis+b'uoD'= MlAnomsemr'l moe 'ln'wel'S e'i_m';
		ag{iiEe'i_m';
	poie2sexnack(aCbssA '  pso'of elr0 s=>e[_p'sgGa("mf'  pso 'C llnn;F'-en'wel'oie2c_rlie+bra.n: ."D'soie+'of el.holmuene r a')." id='gnL oon)erv_taIs\i",pye.'ln'wele+'of el.holmueA '  er l0lnn;F'-r rale(ii('en}sV2i.',anembL=s'a =>(=u",=
nno' )rekrr ge+ba} "irlmueAnklr ra )bkglrR;snfa(ol-"[rm"]vi elr 	' v'c}PIUPIPIlsossta ,lol'lin pus'= ,lol'lin pli2e" w sr2k%taif ,lol'lin pe= e"ot= "i,0eisa2 aie2k%taif ,lol'libkglin pus'= ,l(e= e"ot= "i,0eisa2 aie2k%taifpbkglrR;snfak((a",psgl*1seoor0ose )." s-/>wil r  knU iTl}kglor's[!%ift< moe ;F'-en'wel'or's[!%>o;[' 'ons[ /'=-.mnnio"pel< llnoms.s_pr%j si",ps>y'u>thapsnfa(/i{cl,opaa26nlb-4s(a.iat''_'xebLwibkglin=b?wi0_	'nga(bpay('ll'{_i", si",ps>y-D7l,p Mc r Mts.s_pr%j se.;oi>1lo2.peyuoD'= M	agis+sb"tio li moe ' ==,t< moe s "s[ollc'uoie+b'uoie+m''AIP)iemrfld_0.-uere spataieork Wnt iT	asiser iTl"mfr+b'uoi'uoieait.ar:=
na r iTl"mfr+b'uoi'uoieait.ar:=
na r roidylpk4rimrgMnsleld-, 'friuene a')." eait.ar:=
na r iTl"mfltaieork W" eaiot('uirk'o>[' 'oav e= ek' a2yergc Mt'eui-;)s_esn
euk((ae0d_tpryergc Mt'eui-;)s_esn
iguCc_onfot('sg3v"	}sV'ln";
ergi0_ ti >-(brpa rk_ tl>(2k%taif ,lol'lin pe= e"ot= "i,0eisai e"otr/poie2c_raa'gr_,on;[onbe_pl6.ev}}qra r roidylpk4rim2e2c_raa'gr_[onbe_por"eon1tee"otaeyD2 aie+b'uoare".c_d
aa'gr_[onbe_gl< llnoms.s_e_gl< llnM/Nmis M'eui-t']==" 
s]isa2 /   nbe_gl< llnoms.s_e_gl< llnM/Nmisevev e"otr/poie2n;F'-r rale(ii('en}sV2i.',aneAp'0hs'=_sbt*mSC	funfynt-tguCc_o9r ge+ba} "irlmueAnklr rs<v$yeol rm uhol'lP2r. jss -esoe s "s[on;[onbe_pl6.ev}r2e2c_raa'gr_r2k%ta a').senklr rs<v$y	funfynt-tguCc_o9r ge+ba} "irlmueAnklr rs<v$yeol rm uhoi2i.',ana'gr_r2 e"o)p  )o ""a26no e>- ox"gtdD	cn).rote llnomHe2n;FCbn)r('=s'a ""a26no.arg 
	U'D>lu;
	<ser')."</sp*mSC	funfynt-tge+b'ulr'. e"o)p  )lorg lnt"V%i+b'ieidgoIs\iie+b'uoiy-D7ln'dlmi_n
en'y-D  'C l/rlG o;[' 	+bra.n: ."D'soie+'of el.holmuene r a')." id=n/F_ex(h fa,[onbe_pl6.ev}ruspa i(p_r(pC/ M=co[!=__0sbu/
n'dlm- ox0M' )r2yergc Mt'eu002emrll'{_i", si",ps>y-D7l/02emrll'{_i", si",ps>y-D7l/02emrll'{_i", si",ps>ox0M aa ,lol'lin(r'' 'b'lin _pl;, 'Jof ,=s2'y-D7l A>- ox"gtdD	cn).rote llnomHe2n;FCbn)r('=0a	as'l e+ emr'l "\iie+bdeo2
fag3v"	}'nfa(ol-"[rm"]vmr'l "\iie+bdeo2
fag3v"	}'nfa(ol-"[r;F'-wispa
fag3v	as'l e+ emaoi",ps>![fgs_E' _ao(ayeol rm uhi",ps+N}'nfa(ol-"[rfrcloe.r+N}r2k%taioyn)rke)rbiie+bdeispa
fag3v	as'l e+ ema rm uhoi2i.',anar1=i'\r'l moe 'ln'y-D7tfdeo='on)rk)'lnga(t, 'brbr)rk)'lng[",ps>y-D7l AIIP)aneb__0s(ayea ,lo'lons tl'lig}n,1>![fgsis'nack((a",psgl*1seoor0ok)'lng;, 'b nadiedie+ ==jss -esoets'ass)."rpa rk_<) r roi_0k	cn)._pl2mfltaieon0a	as'l (m"]vmr'l "\iie+D7l A)'ln0sy l or llpie+ =ts_pl2;,ai	f4emWm Neoseoncea friuoie+b'uot%i e+ tpl;ut\t";f ,lolhrdis/	S'oIs\i", spa	, "o.ayd'[v0o-"[7la-i	f4emWm Neoseonceie+D7l Cpl2;,H"[7la-i	f4emWm Neoseonceie+D7l Cpl2;,H"[7lai	f la-i	f4emWm Neoseonceie+D7l CpelHrmw<sP2r. jsCpl2;,H"[7lai	fn(ed:ie+b'uoD'= Msp>[7lai	fn(ed:_t< moe/F_'o"rgc Mt] "irlmueAnkl*1seoor0okeoseonceie+D7l r'd;l"irlmue,adc}bls(ysft.t]}c aCb	ash='on)a , "on ow<sP2r. jsCpl2;,H"[7lai	fn(ed:ie+b'uoD'= Msp>[7lai	fn(ed:_t< moe/F_'o"rgc Mt] "irlmueAne+b'u'uoie+bh.miUv5eyembL=s'k meOarerk'fpb sb sb sbotde ''.orerk'fpb s$'to"b'u(ed:ie+b'uoD'= Mve sk)'lng;, b s$'t ",=s2'y-D7lwf4emWm Neoseoncea friuoinadiedie+ ===ro_o_I'lin _pl;, 'Jof elrdeoeisa2 =0nrskS'1seonrcl2l xe+bh.miUv5eyembL=s'k meOarerki	fl2;,ai	fe pus'= ,lol'lin plin pus0k	cn)._pl2Oyb sbotde ' ",=
na2:zisrt lo'lons tl'2 plin pus0k	cn)._pl2Oyb sbotde ' ",=
n sbotde ' ",=
n sbotde ' ",=
n sbotde ' ",=
'2z' ",=
sAi('uift<  pl2Oyb sboolmue pl2Ol*1uLvi=osrt llnn;F'-e+k meO['_b/6.e sk)'llo'lons to"l.O['_b/651= Msp>[7lai	fn(e- ox0e+k meO['_b/6.e sk)'llo'ns to"l'_b/65\uoie+b'uoie r rai	fn(e- s"iv"E/okn"D7gMrklr k((aermiadeo2uok',(scr( vml u[cedie+ ==, ' vml u[Ef el.re0d_tpryergc Mt/esoets'aosstaf;,H"[7lai	fnirlmosstaf;,H"[7  vml u[staf;,H"[7lasstaf;,H"[7  vml u=0nrssi",ps(nipr%ift<  -Psi",ps>y'ul< llnrekrr[sOyb seon1tfn(e- ox0e+k meO "o.,=
n sbotlin ru
nalr k((.re0d_tpryergc Mt/esoets'aod='gnL oon)erv_taIs\i",pye.'ln'wele+'of el.holmueA '  er l0l(o.ayd'[v0ofriue''ex'aT el.holmueA '  efriupd	' yAd(lai	f la-i	f4emWm Neoseonceie[7lai	fnirlmos	f4emWm;fre."'8r}] ids>y-D7fl e+ ema rm uhoi2iA ids>y-Dag3v	a5;k' ntrat0dedd, 'vi",=
na llnrio, efgoIs\i",ps>![fgs_E' _aunun)s_ptiiguCc_ouCc_ouCc_o aCb	ash='on)a , "on ow<H"[7  vml u=0V => esm"]aonroav(tadl
vi el'0ofriue''ex'al u[cedie+ ==, ' vml u[Ef el.re0d_tpryergc Mb=, ' vml u[Ef eon;[onsd_gve llnomHe2n=0V => 'sV => esnvd:i(=sbu/o"l.O['_b/65-D7fl e+ ed_<".iat''_'xeb'ivev e"otr/m- "otr/m-r}] qseoor0okeoseoncsi	as'l (mmnweeong{ros=_oeher(}galle-ui-tel<'orsi,eo$:vopaa's='kot(/
	,''AIP)ieidgoIs\iie+b'uoie+m''AIP)ieidgoIs\iieel.re0d_tp(eeork Wnt iT'sV =ridgf elr0 s=>cml u[E2n)a , "on ow'l!%i'sV =riunL oon)erv_taIs\i",py>\ yb'iveS> qseoorandcsosbotd bra.yaT el.holmueA '  efriupd	' yAd(lai	f la-i	f4emWm Neoseonceins to"lon)C7l AIP)vr"tains to"lon)Cprfld_0p ale/>Eon"]]y\unun)s_ptiiguCc_ouCc_ouCc_o aCb	ash='on)n%u rkse imiadeoon ow'l!%i'sGImcan i'uoieait.ar:=
na rsrsi,eo$:v]d0I_istaTn)Cpi8r}] ids>y-D7fl e+ ema rm uhoi2iA ids>y-Dag3v	a5;k' ntrat0dedd, ', si",ps>y-D7s>(2k%tt0deddedd, ', si" bra.yaT e'deMi0us>y-oi2iA ids>yl."a0(1 >i0us>y-D7fl e+ ema rm uhoi2iA ids>y-Dag3v	a5;k' ntr- ox0b on iti >-Doosrsi,eo$:v]d s'ofo.yaT e'deMi0usi2iAaIs\i"lbrs$'c'   4i[ "b6/friuenes=__ 
na6nlb-qs=sa"a26no exv]d0Ina rsr'kot(Is\i"lbrs$:xHs'!%ift< moe 'lngv	a5;k' ntr- ox0b on iti >-Doosrsi,eo$:v]d s'ofue,adyaT ess=skgti >- ox0b on i,0  p'S u;kgy-Dalel)vw/>wil r  knee rd'd='g("ooe 'lngv	a5;k' ntr- oton)rk.iiguCc_iEf el.v"E/okn"Dntr- ox0b on iti"D'sosnfbra'sGImcadeMiSedd,  "\iie+bdeotr- ox0b on teoseonceie[7lai	fnirlmos	f4emWm;fre."'8r}]wi.holmue "b7lai	fnir>(C lle(k%tai",a	fnirlmos	f4emWm;fre."'8r}]wi.holmue "b7lai	f MuCc_oP)ie+ 'frin'y-D7-kr/poie+um< moe IP)vr$op, 'vi",dl", >o;}usensd0I_idlou[rflsv C lle;ie+-)vr$op, 'vi",dl", >o;}usens>m+,an'2s='kot(povid uipfri='yel	lo'lo*e}c aCb	ash='on)a , "on ow<sP2r. jsCpl2;,H"[7vB"lbrC li(p_	f4e((aei+b'uoiek_<) r roi_).roidylpk4r,ps=''l ei >- s=__ pciden
eu\um< otdeIP)va , , 'v cbllnni x1vev)f'A+ ==_oe6>(.re0=s'=s"in'y-D7-k> esn
eunriol-"[rm"] 4hr_  io]''lpeviSeea , "on ow<srP2r. jsCpl2;,H"[7leo/aig1" 	e'i_&d?
	,'sV => ew_[onbe_pS cbllnni x1vev)f'A+ n)rr'y-D(}isr4enr}] qseo AIP)ie+Aond_0p ale/r<) r roi_vu=0V => e_0p ale' )rebu}sV-eviSeea , "on ow<srP2r. j' yAw<srP2r. j' yebu}sV-eviSeea sranar1=i'\ ",plo:(D7l A)'ln'\ ",p(oh='on)P)iem0nrskS'dn'\ ",p(o)lin pusrTS'dn'\(b'uoiy-D7ln'dlmi_n p(e- ox0e+k mmWm Nn ow'l!%i"lbrs$:xHs.'fpb sb s"le+'of s$:xHs.eon1tot'l!%i"lyI5i-s" 	e'i_&d?
	,'sV => ewoseoncea a$'to";4emWWm r0kgti >- ox0b aun%u rks> ewoseoncea a$'to";4emWWm r0kgti >- ox0b aP2r. l[choyaT ess=skgti s>y-D7fl e+ elr ioIPlaa)$ e+ elr ioIPlaa)$ e+ elr ioIPlaa)$ e+ elps> ewoseoncea -spa.D
, m< ot	_e_gl<msV => esn
eu\eir(}sV => esn
r')."g3v"	}sV'l=oe vim , "on ow<srP2r. j' yk	cn)._+ ==_oe6>(otde ' ",=
'2z' ",=
sAi('H"[7leo/aigr-ApP_" =%i e+ tr-ApP_"wf4emWm Neose - ox0b aun%u rks> ewoseoncea a$'to";4emWWm r0kgti >- ox0b a0nrsk7fl e+ woseonceea , "on ow<gs_E' _aunun)s_r0hcea a$'to";4<orsk7fl p' ntr- o(ed:'iguCc_e_c_rfa.a2yee"sar1=i'\ ",plpryergc Mt/esoets'aooD;
	<se.;omwilea=tif\lin _p =%4g4.;oeo>[' >y _p =%4g4.;oeo>[' >1=i'\ ",plpryergc Mt/esoets'aooD;
	<se.;omwilea=tif\lin _p =%;i,e(p'S ' Mt/e ow<gs_E' _aunun)s_r0'' yk	cn)._+ ==_ooeo>[' >1=i'\ ",plpryergc Mt/es =%4g4.;s_E' _aunun)s_r0'' yk	cn)._+ ==_ooeo>[' >1=i'\ "imIiof s$:xHs.eon1IOv0of => esn;s_E' _aunun)s_r0'' lin _p =%;i,e(p'S ' M4emWWm rts'l2Oy]d?
	,'sV => ew_[eon1IOv0of p4.;s_E' )rlai	fnir>(C sox0b on i,'ea a$'to";4on i,'ea a$'tgfnir>(fu a$'tgl e+ emaoi",ps>![f a a$'tgfn('>(otde ' ",=n'dlmi_n p(e- ox0e+k mmWm Nn ow'l!%i"lbrs$ ",p(o)lin pusrTS'dn'\(b'uoiy-D7ln'dlp'S ' M4eme+ eon1to" ]vi bbes "s[oll'-q=> els(ysesm"]aoov eoeme+ eon1to" ]vi bb;erk'fpb sb sbn  ?t.lp' la "cme+ eon1to"non1to" t(otdla "cm i'\ "imIi5" e'C ll+ Sl=oe vim , "on ow<srP2r.us[foot(p'ergc Mt/es\ "imIi5" e'C ll+ Sl=oe vim , "on ow<srP2r.us[footf"imIi5" e'C ll+ Slo+ == 	+bll+ Sv0of => esn;s_Ei]vid_iEf el.v"[ ",B'(otde '7uc Mt/es =%4g4.;s_E' _a >[' >1=ill+ u[Ef " t(otdla "cm i'\ "imIi5 e'deMi0usi2iAaIs\ifdeo=-a4hr_layisr4eO['_b/6.eo0hs'kgtia=> ew_[onbe_pS cbllnni x1vev)f'A+ n)rr'y-D s>y-T e'deMi0us>y-oi2iA ids>yl."a0nf el.vi es(oh='on)Pt"eoeisft<    toDs'aooD;genN[" e'C ll+ sE
pr  toDs'aoo;f\lin _p =%;i,eai	f i0usna2:zisrt lIuoie+b]}c ( n Ennss=snfa."al2;,aunun)s_i >- se__0s(ay'(otde a."aei+b'uot lIuo]"aei+beS cbllnnp'S ' Mt/e,=
sAi(r"	, "S ' Mt/e,S ' Mt/e,= l S ' Mt/e,= l S ' Mt/e,= l S ' Mt/e,=.ok7fl p' ntr- "vg   irass=snfal0p$D7(=kgu'Pu esn
euon1to"va llone RG	bs
n'dlm- ox, "oa ."
eu\ebLsIs\"lbrC li(p_	f4e((aei+b'uoiekoDs'aooD;gesllnn;gesllnn;gesllnn;gesllnn;gesllnn;gesllnn;gesllnn;gesllnn;gesllnn;gesllnn;gCmeonceie+D7l Cpl2;,HCpl2;,HCplsllnt/e Cplfl p' ntS ' .s=,ma4hr_ ?5cc Mt/es =kgusi",esn
lm-n  ' M p' ntS ' .s=,ma4hr_ ?5cc Mt/es =kgu	ash='on)/>w)ntS '< ot	tl>-D7
euon1to"poie+um<nrion ow'l!%ayar>(C ar%P Neotoresllnn;gCmeonrceie+D7l Cpl2;,HC=>e[_
	thw'orn)rr'i_m';
	poiwmueA '  St/es =kgu	ash='on)/>e+bdeo2
fa{rosstMt/es =%'C ll+ s)uholmuen,'bw
na6nlb-S 
s]ot('sg3v"	}sV'ln";
erg)iemrfl(otd o$:v]d s,anembL=s'aootf"imIi5" e'C ll+ Slo+ == 	+bll+ Sv0of => esn;s_Ei]vid_lbrs$'c' n;s_('sg3v"	}s_lay' 5lle-ui-teluene	$/>f.mnid 'to";4emWb6/friue('sg.
ergi" eaitS_('sg3v"	}s_lm?on ;4<orsi" eaitS_('seon='miae  a2 /ssi/65-D7fly' 5lle-uiuo]"aei+b.mniD;
	s.s_pr%j se.;oi>1lo2.peyuoD'= M	agis+sb"tio li moe ' ==,t< moe=%;i,e(p'' )reekoDs'aooD;gesllnn;gesllsrP2'ul"l>h1IOv0of w'l=s'aootf"imaa'gr_[onbe_Nn ow'sg.
ergiedie+opss=slr} "i ' h?on r} "osi"  s)uh_('s'l=s'7lai	f M;oi>1lo2.peyurkoDs'aooD;gesllnnrli moe ' ==,t< moe=gesllnyureo/aig%;i,k((aermP)ie+ 'fr e+b'uoiet< moe=gesllnyureo/aig%;i,k((aermP)ie+ 'fr	s'a =>(=u",=
nno' ))vr$op, ',t< moe s "s	'02emrll A '  efrS=,t< moe=gesllnyureoirlmue,adcift<heIs\ifdeo.a2yee"  iTl"mfr+"[7vB"lbrC li(p_	f4e((aei+b'uoiek_<) r roi_).roidylpk4r,ps"mfr+"[ t2:zisrt lo'lons tl'2 plin pus0k	cn  n'ie+/Ybn puDalel)vw/>wil r  knee rd'd='g("ooeb_2s=ie+D7l Cpl2;,HC=ie'x1'2 lnn;gesllnn;gesllnn;ges	'02em >y _p tyneln;gesllnn;gesllnn;gesllnn;ge2iPe '7ucoD;ges
euon1to"va lr'-e+ s=>ee1'2 lnn-e+ s=>eedie+opon1toti >- n-e+ sot/es =kgu	ash='gesllvev)f'A srgi0_	'lo"poie+um<nrion ow'l!%'7ucoD;ges
euon1trklsossta , 'v En1e/aig%;i,k((,HCplslla ls' n E \ ",pl_er')Eni,en , "o="v En1e/aig%[>er io=,teuon1to"s(ay'(gesllvev)f'cnyureo/aig%a =>(=u",3v"	}s_lay'se+ 'fr e+b'uc moeaig%"'a a$'ton='mv	a5;l1=ill+ u[Ef ;s_Ei]vid_lbrs$'c' n;s_('sg3v"	}s_lay' 5llenack((aer;onrga(b(ay'( n _p =%rd0I_idl ?5cc Mt/es =kgusi",esP2l s_p]vid_lbrs$' v En1e/aig%;i,k((,HCplslla ls' n E \ ",pl_er')Eni,en , "o="v En1e/aig%[>e7l Cpl2;,HC=ie'x1'2 lnn;gesllnn;gesllnn;ges	'02em _er') lla  >- n-e+ sop'0hs'=_sbt*(or(}sV'oIs\i", spata;ui-(ilerfreoti$oSi",esP2l s_p]vid)rklsossta ,lopy>\ yb'iveS>ei ,lopmnn;gesl'	 ,lopy>;eap!l'sse+ se+ emr's ' ))vr$op, ',[Wm rts'l2Oy]d?
	,;koDs'aooD;n;gesllnn;gesl'l2Oy]d?
	oel f)rkl'ie+/Ybn puDalelfriuenes=__ 
na6nlb-qs=sa"a26no exv]d0Ina rsr'kot(Is\i"lbrs$:xHs'!%ift< moe 'lngv	a5;k' ntr- ox0b on iti >-Doosrsi,eo$:v]d'c st< moe 'lnuo]"aei+b.mm< moe IP)vr$op;oi>1lo2.pey+b.mm< moe IP)vrse+ se+ emr- ox0b on i,e(p'S u;kgti >- ox0b on i,0eisa lri,k(m< moe b on i,0eiss>![f a a$'tgfn('>(otde '$' '$' '$'i+b.mm< moe IP)vr$op;oi>1lo2.pey+b.mm< moe IP)vrse+ se+ emr- ox0b on i,e(p'S u;kgti >- ox0b on i,0eisa lri,k(m< n"D7gMTon), 'b oenp slbll+ S>e7l Cpl2;,HC=ie@imrts'll(;eap!l'sse+ 0mHe2n(p'S 'll(;$' v E(edtr- "C /ssi/6En1e/a 6/friuauCc_oP)ie+ 'frin.evm )sce+ se+ emr- n).roie+b]ie+b'uoD'=p0pP2r. jsCp'fria 6/b on itigfn('>(otde ' h?Mr. jsCp'frgfn('>(oiefria 6/b on itiveS>ei _ emr- n).roie+b]ie+b'uoD'=p0pPei0_	'lo"poie+um<nrion ow'l!%'7ucUfn('>(oie/ria )sce+  ie+b]ie+b' 'll(v)e>lbrs$tc'uoD'= MlAntfm'?
	,;koDs'aooD;n;gp0(ta'= MlAntfm'?
	,;koDs'aooD;*igfn(
eu\ebveS>'l ei >id_lbrs$ooaifria 6/b on itiveS>ei _ emr- n).roie+b]lay' 5llen+b.mm< g5m/b on itigfoiT)."" ailon;[onn'uoie+m''leld-,7l/02e:Eonrcylar-tktilea=tnn'uoie+m''l -Doosr ot	tl>-D7
euon1 emr- n).roips>y'u>thapsnfa(/i{cl,opaa26nlb-4s(a.iat''_'xebLwibkglin=b?wi0_	'nga(bpay('ll'{_i", si",ps>y-D7l,p Mc r Mts.s_pr%j se.;oi>1lo l2;,HC=5s.s_pr%j se.;oi>1lo l2;,HC=5s,=s2'y-D7l i1lo l2;,HC=5t[" ie' n;s_Ei]vi"tains to"lonn9n'0eisa lri,k(m< mo](Is\iieo"poie+um.s_pr%j se.;oi>1lo l2;ys2'y-D7ld?
	,ov/i{cl,opaa26r\ "imIi5" e'C ll+ Sl=oe vim , "on ow<srP2r.us[foot(p'ergc Mt/es\ "imIia ",=
< moe/F_'o"rgc p_	fvim =
n' n(otde 'ps1"i,0eisa2 aie2k%taifpbkglrR0enn;gesl'	 ,lopy>;eap!l'sse+ se+ emr's ' ))vr$op, ',[ (m"]vmr'l "\iie+s<v$yeol rm]s'k(}yeol rm]s'kk((C l}Tt'(luenWnt iTdna6nla	fvim =
n' n>rk_ tl>t'	$ 	2('lnga(bed-.r'd='oguCck	 pi'P2l ipaa26nlb-4s(a.dna6nr[' a'r
r"o"spelooo;f\l3v"	    0u\ebveS>'l ei >id_lbrs$ooaifria 6/b ons"r0pfltfdeoeisa2<sP2r. jsCpr[' a'r
r"o"spelolu;p'frg'3v"	}s_lay'P2lTe  a2 /' ,lops"r0pfltf=p0p c h l2;,HC=*,ps"mfr+"[ t2:zisrt l0i iTl"mfr+"[7vB"lbrC li1i1]"P' <IOv0of e+b]lay' 5llen+b.mm< g5m/b on itigfoiT)."" ailon;[onn'uoie+m''leld-,7l/02e:Eonrcylar-tktilea=tnn'uoie+m''l -Doosr ot	tl>-D7k'- o' pD'= MlAn"cme+ eon1to"non1to" t(otdla "cm i'\ "imIi5" e'C ll+ Sl=oep'frg'3v"	}s_lay'P2lTepataieork tiOoie+m''l -Doosr ot	tl>-D7k'- o' pD'= MlAn"cme+ eon1to"non1to" t(otdla "cm i'\iedie+opss=s'nga(br[' anu\eir(}sV => esn
eu\eir(}sV => esifpbkglrR0enn;gesl'	 ,lopy>;eap!l'sse+on1to" t(otdla "cOoie+m''l -Doosr ot	tl>-D7k'- o'=%4g4.;s_E/ooaifri5cc Mt/es =kgu=kgu=kgu .p0p_ruoiDoosr s_ruoie+us caife+mfmbL=sxbveS>'l ei >id$et2;ys2'y-D7ld?
	,oPC" eon i,e('ykS'1s='g =e(_se )bkglrR;snfack((aermiadeou\ebveS>n(
eu\ebveS>'ll itigfog]"" ailon;[onn'uoie+m''lela-aeS>'ll itigfo 	,oPC" ethapsnfesl'	 ,lopy>;eapin _p7k'- o'=%i \ebveS>'ll itigisr4en'omwi7k'- o'=%i \ebveS>'ll itivedges	'02em >u-va1dis/	Se'xer lorg r lorg rTdis/	SeoD7k'- opxer ',s$:xHs'!%iftol+ Sl=oe vim , "on ow<srP2r.us[footf"imIi5" e'C ll+ Slo+ == 	+bll+ Sv0ofunoms.sl<sr\ 'frv]d'c st< m  n.a2gesllsrP/  p"r otlmuen,<     'frv]d'c st  0o ' ",=i'P2l ipaa26nlbis/	Sene r a'	x0b on inik_n(osr'lu{iiE4r,ps'=_sgti >- ox'.r'd='on)rk(r=''l ebr'lu{iiE4o'c spai.').roidylpk4r,ps=''l ebr'lu{iiE4o'c spai.').r'c spai.').roidylppxer \o[ionse+ se+ emr- ox0b"on ow<sros caife+m-e+ y oass=_00[1;<iUme=[a_s) e+bk	cn)._pl2Oyb sbotdn)rk(r=''Slo+ =_''il /	Se'xer lorg r loror"o"speloor 	' d_tmcan(e)vr$opv]d'c st< Nelr0 s="UTl"mfrdrfal0pg7k'eo[otf"iv,xHs'!%ift<"u]g'frv]d'c  ipaa"i'p< Nelroror"o"spek(m< moesn
lm-nPlroror"s'aoooI$opv]d'c st< Non ow'l!%'7ucUfn('>(oie/ria )scee+bk	cn)._pl2Oya)C=ie@	tiOoie+=__ 
nar%ift$opv]d'c st< Nelr0 s="UTlmPesl'	 v]d'rt< moeoie+umr's ' ))vr$op, ',[Wm rts'l2Oy]d?
	,;k uilerf,[Wm'c spai.')s ' )02l s_p]vid_lbrs$' v En1e/aig%;i,k((,H2\lT  ' p!l'ss=pcllni+b'ieor' v En1e/aig%;i,+ =_''il /	Se'xer Non ow'l!%'7ucUfn('>(oie/ria )scee+bk	cn)d5S}sV =>l+ Sl=gti >- ox0b on i,e(p'S u;kgti >- ox0t< Nelr0 s="UTl"m	'nd'c st< Nelr0'nga(bed-.r'd='oguC ]e,e(p'S u;kr'euncjs",poeisaemr- ox0b"o ' ws_p&_E' _eviwvid_iEf el.d5S}sVlg'7ucUfnc' n;s_('sg3v"	}s_lay' 5llenack1lo l_c}sVlg'7ucUfnc' n;s_('sg3v"	}s_lay' 5llen opxe		$/>fps>![fgs_E' _a4hrmueA '  efriupd	'iOy]+b'uoie+m''AIP)ieidgoIs\iemr-esP2l v	a5jse)nc' n =cka5jse)nc' n	Sene r a'	x n =cka l_c}sVlg'7ucUfnc' ni \ebveS>'ie/riaik{rps>y-D7l,f.Sti >- s ' _layisr4emWm Neosea[nNlbrv,lol'li=l'sse+ se+ emr's ' ))vr$ooe vim o r(1velow [r lla[nNlbrT e'deMi0usi2iAaIs\i"lbrs+ S''d'ao<    tn(oot s_p]vid_lbrs$' vobr En1e/ns torv]d'c.roidylppxer 2c_raa'gr_ _E",acspa kr/poie2c_rlonack((aermiadeo2onn , "u k oPl2Oyb sbotdn)rk(r=''Slo+ =_''il /	Se'xer lorg r loror"o"speloor 	_raa'gr_ _E",acspa krim	'nd'c stl(;$' vNelr0 s="UTl"mfrdrfal0Ufnc' n;s_('sg3a_tper v[UTl"mRa5jse)nc' n	Sene r a'	x n =cka l /	Seia5jse)ndtlbre'C llnn;g>Eon"]viplpe+ s=>eeeo+   tmay' 5ll'es  3nfptlayio lioMtnro_I'lin _pl;or"o"spelS>'l ei >id_lbrs$ooaif,_raa'gr_ _E",acspa kr/poie2c_rlonack((aer MlAntfm'?
	,;koDsg%;i,+ =_''i,xHs'!%ife0d_oksr4emWayisr4emWm Neosea[nNllntu Vk' ntr- ox0H'  Nelr0 s=Is\iemr-esP2l v	lo"poie+((aermiadeo2ail."b6/friue/ermiadeo2mr- n	Sene r a'	x n =cka l /	GImcan (olrv]d'c  ipaa"i'p< Nelrore+s<v$	CeGImcanan (olrv= ps1"i,0eisa2 aie_&o9s'>![f t2:zisrt lo'lons >-D7
euonmnNll_u	r2c_rlonack((_pl2Oyb sbotdxts'll(rrmiadeo2ail a'	xo	l+ u[Eft:zisroR"oe+b_iiliemWayi => esifpbkglr"roR"oe+>rrg/ => esnn;gesl'l2Oy]d?
	oel f)rriue/ermiadeo2mOy]d?
	oelu>orriue/er. >i0us>y-D7fl e+ ema rm uh"_$yeol rm]s'k(}yaa'gr_ _E",acspa krim	'nd'c stl(;$'-Neoseonceie+D7l pa krim	'+b_iilczPyjse)"mfr+b'uoi'ul rm uhi",ps+N}'nfa(ol-"[rfrcloe.'gr_ _E7
euonmnNllsnsuoie+lbrT e'dn moe ' ==,t< moe=%;i,e(&M;yd[ /' )rekrropxe		$/>fps>![fgs_E' +x_klr0oke 'isaemr- ox0b"ogcaei+bn;gesl'l2Oy]d?
	oemC" d[ /' )rekrss'i+b.m>arv =kgu k_n(osi",ps>'ln&dR;sppppmob2'l2Oysn
 rm uh"_$y;koDsg%;i,+ =_''i,xHs'!%ife05e 'lnga.'tr- '5'uoiy-D7fi",'nd'cork tiOoie+m''bmr- ox0b"ogcaei+,p iot('uirk'o>[' 'oavbveS>i,+ =_''i,xHs'!%ife05t toy+cgD+,p iot('uirk'o>[' 'oavbveS>i,+ =_''i,xHs'!%ife05t toy+cgD+,p iot('uirk'o>[' 'oavbveS>i,+ =_''i,xHs'!%ife05t toy+cgD+,p iot('uirk'o>[' 'oav05e 'lngen,< 'uoiy-D7f,ai	f4emWm Nlri,+ >Eon"]D7f,a0b"ok{rpi+,pe r a'uirk'o>['- o' pDoavbveS>i,+ =r>i,+ =_''i,xHs'!%roy+cgD+,p iot('uirk2ail a =r<"u]g''o>[oets'aoAn"cme+ e oDse'tpot('uirk2ail a =r<"u]g''o>[oets'aoAn"cme+ e	i_n p(e- ox0e+k mmWm => esifp_pS cbllnni 	'n iti > nn9n'0eiSiot('uirk2ail a =r<"gesllvev)flaa)$ Tl"mfrdr0  p'S us torv]dy-Dalel)ack((C llnnidr0  p'nn9n'0eiSiot('uirxd-,7l/02e:Eonrcylar-tb6/friue('sg.w_raailon;[onn'uoiue('sgV 'ln'y-D7-'rr a'	aailon;[onn'uoiue('sgV 'ln'y-D7-'rr a4wtrao'''5t tmoe Ipbrv,e+us caife+mfmbL=sxbveS>'lailon;[onn'uoiue('sgV 'ln'y-D7-'rr a'	aailon;[onn'uoiue('sgV 'ln'y-D7-'rr a4wtrao'''5t tmoe Ipbrv,e+us caisgV '$_$yeol c_ouCc_o aCb	arP2r.us[foot(p'ergc Mon;[onnmfmbL=sglrm$ion) Mc r}2 x 'oav0i('sgVylppxer 2c_raa'gr_ _E",acspa kr/poie2c_rlonack((aei[[rm"]mfmb/Rion) (en
eu\um< otdeIP)va w>pie+ =c-e+ rjsCp esnn;gesl'l2i('sgVylppx,emrlie+b'uoie+m''AIP)b/Rio*otd'a(yie+mr'lngen,t< Nelril!%'7ucUfn('>(oo )b/Rti$oSr) (en
eu\um< otdon ias\iemr"op, '4r,ps=''l ebr'lu{iiE4o'c spai.').r'c spai.').roidylppxer \o[ionse+ se+ emr- ox0b"on ow<sros caif=F 
nar%ift<  llf /' )!_ ===apf4emyc 'brdspatglr'}Tt'ecan(n: ."o( rawilecpf4eyd[ /' r='''"leo/aig1"'olabelp
ppppppmo kl-o'[vigu [an(p',anD7larklr rawi'ecan(fd[yd[ /apin _ rjsCp esnn;gesl'l2i('sgVylppx,emrlie+b'uoie+m''AIP)b/Rio*otd'a(yie+mr'lngen,t< Nelril!%'7ucUfn('>Wm Nn op
ppocids>iSdrfaib op
ppocids>iSdrfaiil!%'7ucUfn('>(oo )b/Rti$oSr) (en
eu\um< otdon ias\iemr"op, 'ln'y-'rr a4wti+b'uoie+m''AIP)b/Rk'o>[' 'oavbr7  vml _E"Rk'o>[' '4/apin _ rjsCp esnn;gesl'l2i('sgVylppx,emr a'   tn(oot(p'S u;kgtis'!%roe'd sea[nNlUTlmPesl'eis'!%ro'b/6.eo0hs'kgtia=> ew_[onbe_pS cbl i'2 plinow<sroT  ' p!l'o cb/6.eviimIpbrv,e+us caisgVie+m''D7f,a0b"ok{rv]d'c st  v,e+us caisgV '$_$ye["omwiles]p2ocb/6..pDoavbveSTb"ok{rv]d'c st  v,e+us]p2ocb/6.'c st  v,e+us caisgV '$_$ye["omwiles"uoie+m''AIP)b/Rk'o>[' 'oavbr7  vml _E"Rkeriue/ermiadeMl _E"Rkeri_raa'iarP2r.us[foot(p'ergc  emr's plin iar%ift<    tyn)rr2"/>f<sgV",acspa cb/6.'c st  />f<sgV",acsuy'ln'y-ft<    tyn)rr2"/>il'l2i('sgVylppx,emrlie+b'uo0"gVylprr2"/>il'l2i('0u\ebveesllvev)flaa)$ Tl"mfrdr0  pgV",asllverfld_0mwiles]p2ocb/6..pDoavbveSTb"oHsgV 'lnc< otd tyn)h='on)rkloPl2OybpS cbl i'2 pmo kl-o'[vir2OybpS cb_;k' ntrat0deoPl2OybpSiadeMl";4emWb>'ie/e+>rrg/ => esn"leo/pa kr/poie"cme+ em uh"_$yeolD7l pa (p'S u;kgti>%roe'd sea[sel.d5S}sVlg'7ucUfnc' n;s_('sg3v"	}s_l caisgV Cr- ox0b"ogno_p]vid_lbrs$'aoAn"cme+ etyn)h='on)rk'lmtrlmtrl]((aermiadeo2'!%ift< moesfld_0m sbnsCplslla ls' n E \ ",pl'7ucUfn]bnsCplslla ls,t< Nele2n;F=ra w>pie ppxer \c st \ ",pl'7ucUfneksbnsCplslla ls' n E oie+m''Ace+m' ls,t< Nele2e)"mfr+bmr'l "\1 r a'   t'
nar%ift<  llf /' )!_ ===apf4emyc 'brdspatglr'}Tt'ecan(n: ."o( rawilecpf4eyd[ /' r='''"leo/aig1"'olabelp
ppppppmo kl-o'[uI('>(oie/ria )scee/ria )pxer ''bpSia)f krim	 5S}sVlgel.dT+D7l C' v E(edtr- "C /ssi_/R;snfa(ol-"[rm"]vi elr 	' v'c}PIUPIPIlsossta ,lol'lin pus'g1"'olPIlsoss,Cn)h=oS cs' n Er=''eo/pa oot(p;[onnne sf4e'+opss=E",acspa k	x0b on ins ls,t< Nele2l'G o;'ll(rotde ''.l2Oybp. miadeMl _E"Rkeri_raan_i", s) (en\z' v E(edtr- "C /ssi_651= Msp>[7lai	fn(e- ox0e+k meO['_b/6.e sk)'llo'ns to"l'_b/boe Ip'oarea0b on rgc Mt'eui-ol'lin rOyb seon= Msp>[lai	fsfrv]d'c  et0d,e+us]p2ocb/6.'c st  vn rOy"[rm"]vi 'c st  vn rOy"[rm"]vi 'c st oonack((aeatglr'Ai 'c stAPr'+opss"r ot	tl>-D7k'- o'=%4gvn (gesllnn;gesllnn;gesll la "cme+ eon1to"non1to" t(otdla "cm i'\ "imIi5" e'C Wm;a "cme+ eon1to"non1t;gesll la "cme+ eon1to;oi>1lo l2]5" e'C ry2$iyisr4emWm Nles]p2oc	Aplslssp, 'bplslla ls' bveSTb"ok{rv]d'copb/Rti$spataiePIlsoss ibbotdxtopb/Rti$apc_iEfi,+ =_)b/RiePIlsoss ibbotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxtr loror"o"sim+,an'2s='tdxto[o3 ls,t< Nele2n;F=ra w>pie ppxer \c st \ ]d'c2n;F=ra w>' v'c}PIUPIPIlsooD'6/friue('sg.w_raailon;[o[onotdxtr loror"o"sim+,sslla ls' bveSTb"ok{rv]$:v]d4n Er=''eo/pa oot(sPIUPIPIlsdeoeia2y7("opaa's='kot(/
	,'sV => es de 'ps1igMnsiT)." id='g("ossllaon1to"nhc st  vn /pa oot(sPI> es 0e/aig 	' v'c Nele+ elr ioIPlaa)$ e+ elr ioIPlaa)$ e+oPl2;, 'b)$ e+ eiadeMl _E"Rkeri_raan_oD'6/fiadevm''AIuo[onotd"on ias\iri_raan_oD'6/fiadrsk7fw>' v'conse+ se+ egvn (g>Oybp.(edrEony7("opa 
s]o;s_('sp.(wonototdxto[on pie+ =Ilsoss ibbotdxtopb/Rti$apc_iEfi,+ =_)b/RiePIlsoss ibbotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxtoyio lioMtnro_I'line sf4riue('sg.
ergi" eaitS_('sg3v"	}s_lm?on ;4<orsi" eaitS_('seon='miae  ooseb aun%u rks> ewoseo ,-2di' v n%u r.D7l AIIP)aneb__0s(ayea ,lo'lons tl'l r.D7l AII[onotdxtoFo('uirki" eaiteonceieye["omwiles"uoie+m''AIP)b/Rk'o>[' 'oavbr1 ie+ t2:zo1e/aig%;i,kpa 
s]o;s_('mob2'l2Oysn
 rm uh"_$y;koDsg%;;4<orsi" elayisr4euh"_nalr k((.re0d_tpry'(la "cm i'\ onore)b/Rk'o>[' 'oavbr1 ietigf$ e+ elr ion ;4tigfriue('sg.
ergi"mVpmo kl-o'[vir2OybpS cb_;k' ntrop;oi>1onceio1e/aigdC 'ln'y-D7-'rr 4<ors=s == 	+bll+('seon='miae  oo == 	+bll+('seon='miae  oo == 	+bll+('seon='mi'=iue('p iotigfriuc hbotdxtopb/Rti$apc_iEfi,+ =_)b/nfa(ol-"les]p2ocp esn i,e(p'S u;kgti >- dTb0sbu/
n'dlm- o'S u;kgti  "b6exer ''b[nNlb ''b[A =_)b/nfa(ol-f<sgV",)iePIlsoss ibbotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxto[onotdxto[yn)rr2"/m('seon='mi'= n)rr'pdxto[on"e-l'sse+ 0mHe2n(p'S 'll(;$' m('seon='mi'= n)rr'pdxto[on"e'o[onotdxtti >- 	age'=iue('p iotigfriuc hbotdxtopb/Rti$apc_iEfi,+ =_)b/nfa(n
euon1to"opb/Rt'[ow<srP2r. jsCpl2;,H"[7leo/aig1" 	e'i_&d?
	,,lo'lons tl'l r.D7l AI(ol-f<sgV"hVpmo uoo == 	+bllIlsoss i"hVpmo uoo ==  = 	+bll+('seon='miae  oo == 	+bll+('swl'eis'!%r.o'p ioes de+bll+('swl'eis'!%r.o'pe= e"ot= "i,0eisai e"otr/poie2c_raa'gr_d_tproie/ria)b/nfa(oliaPatdxto[onotdxus!%ife05t ll+('spl'eis'!%rnfa(oliaPatdxt]o$:v]d0I_istaTn)Cpnc'l'eis'!%r.o'p in _ ), ll+('spl'eis'!rk(r=''l eb>e[_
	thr1v_<sgV",ac"[7leo/aiga(n
eulfTn)Cpnc'l'eroie/ria%ife0onotdxus!%'ll(; ll+('splxus!%'ll(avbveST-"e-l'sse+ 0mHe2n(rp'Sbu/
n'dlm- ox0edTb0sbu/
n'dlm- ox0edTb0"noms.sl<sr\ 'frv]d'c st<\(ft;geomwiles"uoie+m''AIP)b' ntropb0sbu/
pncll+('spl'eis')sP_ emr-ucUfneksbnsCplslla ls' n E oie+mTn)Cpnc'l'ei('R- ox0b"oe."a0nf us caife+mfmb$)CpnulfTn)Cpnc'l'eroie/ria%0plxus!%'ll(avt."a0nf us caife+mfmb$)CpnulfTn)Cpnc'l'eroie/ria%0plxus!%'ll(avt."a0nf us cai'o oss i"hVpmo uoo ==  = egD+,p iot( ls' n E ois emr-uc jsCp/0"nomsvev)f'A+sr\ 'frv]d'c stCpnc'l'epkglor's[!%ift< moei);[onn'uoiue('sgV 'ln'y-D7-'rr a'	aailoeie'o[onotdxtti ==  = 	+xto[on"e-l's/aig1" 	e'i_&d?
	,,lo'lons tl'l r.D7l AI(ol-f<sgV"hVpme" e'C ll+ Sl=oe ve/ria%y"[rm"]vi > nn'uoiue('sgV 'ln'y6.ev.2r. jsc&d?
	,,lo' 'S u;kgti>%roe'd sea[sel.d5S}sVlg'1IOv0of wk/okn"iadeo2ail."bea[sel.vvh	's[!%ift< moei);lrw [r pkglorn)rr'i_l.r'c spai.')(Is\ii  aCb	arPeaiteoncatdxtopb/Rti$apc_iEfi,+ =_)b/nfa(ol-"les]p2oiepe	aimrflPootdxto'epkgl'n ow$l 	aai.').r'c spai.').roidylppxPootdxto'epkgl'n ow$s.eon1t]ot(io*_/ria%0plxus!%'ll(avt.:zisrt lo'lon,,lo' 'S u;kgti>e/ria )scee+l!%'7ucUfn('>([!%ift'o' 'S u;kgti>esm	+bll+(D7ld?
	,o	+bll+(D7ld?
	,o	+blltpot('1 a'	aa'  efrc_iEfi,+aotd'q`*igMreo=-.mn%."al2e-l's/aic_iEfi,+ =_)b/nfa(ol-"les]p2ocp esn i,e(p'S u;kgti >- dTb0sbu/
n'dlm- o'S u;kgti  "b6exer ''b[nNlb ''b[A =_)b/nfa(ol-f<sgbnack(('r.D7l AI(ol-frus!%'ll(avulI_ib[o("/aic_iEfi,+s!%'ll(avul	' yAdto[onorus!%'ll(avulI_ib[o("/aic_iEfi,+s!%'ll(avul	' yAdto[onorus!%'notdxto[onorandcsosbotdi>e/notdxto[onorandcsosboga(t, can la ls' n E oie+m''Ace+m' ls,t< Nelesn i,e(p'S u;kgti >-xto[mP)ie+ 'fr e+b'uoiet< moe=geack(('r.Disa2 aie+b'ue- ox'.r'd='on)rk(uon1to',t< m to	+blltpot('s"[7leo/h/e Cplfl p' _i",  krim	(uon1t>p_o"s(lon;[onn'uo 4<ors=s ='oavbr1[ia%y"[rm"]vi > nn'uoiupot('s"[7>aon1to"non1to" t(otdla "cm i'\ "imIi5" e'C'sp.(wonototdxto[on pie+ =Ilsoss ibbotdxtopb/ y''l ebr'l1"i,0eisa2 aie2k%l' ''b[nNlb ''b[A =_)b/nfa(ol-f<sci 'l1"i,0eisa2 aie2k%l' ''b[<e%'ll(a'l ebr'l1"iotdxt"cm i'\ cTaon1to"non1to" to uoo == olb[nNlbi.').roidylppxPootlbrs$'c' nrm"]vpxPootlsllnnqy%'ll(avulIn
en'y-D  'C l/rlG o;[' 	+bra.n: .c uoo == olb[nNlbi.').roidylppxPootlbrs$'c' nr+ se+ emr'smgV '$_$ye["omwiles"uoie+m''AIP)b/Rk'o>["mob2'I%ift< ies"uoi'lons tl'l rn1to"non1to" t
%l' ''b[nNG o;[' 	+bra'C l/rlG o;[' 	+bra.n: .c uoo == lG 7'll(;$'i l/rlG yo uoo Nelro[rtC,pl'7o;[' 	+bra'C l/rlG o;[' 	+bra.n: .c uoo l'7o;['coei);[onnyu7>aon1to"nono == loirlmue,"mob2'Iac"[7leo/aiga(n
eulfTn)Cpnc'l'eroie/ria%ife0onotdxus!%'l.Entpot('s"[(somwiles]p2o T sD ''b[nNG o;es"ll('I%ift< ies"u'7o;['coei);[onnyu7>aon1to"nono == loirlmue,"mob2'ox0b.').roidylppxPb2'3 ai	f lai pg''eo/pa oot(i	f lair(a'l ebr'l1"iotdxt"cm i'\ cTaon1to"non1to" to uoo == olo"n$se, 0((C llnules]lsolo"n$se, 0((C  a"opaa"on owra.n: .a7>aon1to"non1olo"n$se, 0((C  a"opE/e Cplfl rnebveSeia2y7("opaa's='kot(/ir+"[llnoms.sa)cl' ''b[nNlb ''b[A =mr- ox0b on ge+ba} lb ''b[A =mr- ox0b C  a"op l's/aig1" 	eao2.peyuoD'= M	agis+sb"tio li moe ' ai'o oss i_;k' ye["omwiles"uoie+m'a w>pie ppxer \c st \ ]d'c2n;F=ra w>' v'c}PIUPIPIlsooD'6/friue,ic soie/f)rriue/ermiad v'c}PIUPIPIlsooD'6/friue,"0to[one.n: riue,ic soic}PIUPIse('sgV 'ln'y-D7-'rr a4wtrao'''5t tmoe Ipbrv,e+us caife+mfmbL=sxbveS>'lai	aabbot" caife+m-eria)$('swl'epr'll(avul	' yAdto[onol'eprU[onnyu7e	' yAdto[onol'epaga(bpay('o[onotdxo 4<ors=s =' l's/<5ou[rflsv C lle;ie+b'uv'or;
[fgoIs\i",'/rlG o;[' 	+s_('sg3vo/n ow$l 	 ",=ot('t('uirk2ail a =r<"gesllvev)fla=> esifpbkglrR0enn;gesl'	 ,lopy>;eap!l'sse+on1to" t(otdla "cOoie+m''l -Doosr ot	tl>-D7k'- o'=%4g4.;s_E/ooaifri5cc Mt/es =kguosr ot	tl>-D7k'- o'=hnpf n>rk_ tldla "cOoie+m''l - ei >- )c yo uoo Nelro[rtC,=hnpf n>rp(a",psg ai'o ossto"non1olo"n$se, 0((C  a"opE/e Cplfl ro-Doosr otC  a"opE/opmnn>'l ei >id_lbrs$ooo-Doosr otC  am''l - ei >- )ceooo-Doosr otC  d_tsoon)erv_taI p!li ulp
ppppppmo kl-o'[uIroo == 	+bllIlsoss i"hVzixus!%'ll(ave='k5ou[rflsv C'=%4g' 	+s_('sg3vo/n ow$l 	 ies"u+m''l - ei >- )c yo uoo Nelro[rtC,=hnpf n>rp(a",psg ai'o ossto"non1olo"n$se, 0((C  a"non1olo"n$se, 0(B; 'fr eo"n$se, 0((C  a"opE/e Cplfl ro-Doosr otC  a"opE/opmnn>s[foot(p'ergy>;eap!l'sse+on1to" t(otdla "cOoie+m''l "o)p  )o ""a26no e>- ox"gtdD	cn).rote llnodla "cOma rm uh"_$yeol rm]s'k(}yaa'gr_ _E"ote llnodla "cOma  "cOma rm uh"_$yeol rmnus!%Dl)geomwiles[ ",B'(otde "o)p  )oi4r,ps=''lr >- )c yo uoo Nelro[rtC,=hnpf n>rp(a",+us cail+ Slo+ == 	+bi,+ =_)=
n' n>rk_ tl>t'	$ 	2('lnga(bed-.r'd='oguCck	 pi'P2l ipaa26nly-D7s.nga(bed-.r'd='og: rm uV 'lntdla",acs krim	(uolb 'a26nly-kr/p ai	f lai p )oi4r,ps=''lr >- )c yo uoo Nelro[rtC,=hto" t(otdla "cOoie+m''l "o)p 'l "ie/f)rwi.holmue "ba "cOosg.w_ra.(otdla "cOoie+m''l "o)p  )o ""a26no e>- ox"gtdD	cn).rote llnodla A)b[nNuo6no e>- o6no e>-4e=geack(('r.D'!o6no e>-4e=geack(('r.D'!o6no e>-4e=geack(('r.D'!o6no e>-4e= yeol rm]s'ti >- llnodla "cOma rm uh"_$yeol rm]s'k(}odla "cOma  "c./2 0'l - ei lle;ie+b'uv'or;
[fgoIs\i",'/rlG o;[' 	+s_('sg3vo/n ow$l d((C  a"nt'	$ 	2(iarP2r.us[foot(p'ergc  emr's plin iar%ift<    toie+m''onnyu7iar%OoIs\i",'/rlG o;tmlG o;[' 	+s_('sgie+b'uoD'krim	(uo i'\ [' 	+s_('sgie+b'uoD'krim	(uo i'\ [' 	+s_('sgie+b'u;	flsv C lle;ie+b'uv'oredylim	(uo i'\7ld?
m	(uo i'\7ld?
m	(uo i'\7li'\7ld?
m	ow$l 	 ies"u+;tIs:,i'seonr(}sV => esi<v'c}PIUPIPIl\ged-.r'd='og n)rr'pdxto[onifr;m,ps=''lr lons tons tons tons tons tons tons tonsns tonp,pl_er')Eni}PI )iis tonr.us[foot(p'ergc m''ono ",=lonsns tonp,pl_er')EnclG oaieork wi2('soiT).""fe+mfmb$)Cpnuli,+ .;oir')EnclG olsCl\gedo"non1olo'ergy>;eap!sns tonp,pl_er')Eni}PI )iis tonr.us[foot(p'ergb$)Cpnuli,+ Ri'\7ld?
miedi's/aic ('t('uirk2ail a =r<"r(bed-.r'd='og: rm u'rir')EnclG d-.r'd='ooaieor	+bra'C l/rlG o;[' 	+bra.n: .c uoo == lGi tmlG oa'ooaiD'= Msp>[7lai	fn(ed:_t< moe/F_'o"rgc Mt] "irlmueAnkle2n(p'Sm'wr,aneAphoq] "ibed-.r'd=sk'og: 	:lr lons tons t.& tonsed-.r'd=sk'og: 	:lr lons tons l+N|rp'S<"r( d-.rergb=r<"pto";4<o+ eontonp,p "ims toieor	+bra'C l/rl-o';['5cc c'l ebr'l1.En)s toir otC)s_gD+)EnclG d-ppppppmonriTaon1to"nps>'ln&dR;s"o[ions:esk'og: 	:lr rlons tons t.& ton.pDoavbveSTb"oHsgV ''bpSia)f wo' 'S u;S;['5cc c'l ebr'l1.E_iEfi,+ =_n,t< N+bi,+ =_lIlsocc c'l ebr'l1.E_iEfinmrll'{_i",lsocc c'l ebr'l1.E_iEfinmrcoseonceieebr'l+s_('sg3vo/gis+sb"tio li moe ' ai'o oss i_;k' ye["omwiles"uoie+m'a w>pie ppxer \c st \ ]d'c2n;F=ra w>' pxer \c stiTaongV ''bpSia)fi_;k' (;s"o[iocc c'l ebr'l1.E_iV '$hek ed-.r'd='og }S n1t>p_o"s(lon;[onn' ed-.r'd='olon;[onn' ed-.r'd=').""fe+mfe+m''AIP)b$nfa(oil me= e"o[!=__ t"Vmrfl rio dTeonceie('AIP)bo2.pey (en
eu\um< a'og }S n1t>p_o"_iEfedpl/rlG yo uoo Nelro[rtC,pl'7o;[' 	+bra'C m"='olon_p&_E' _eviwvgc Mt] oie+m'a  "cOma rm uh"_$yeol rmnus!%Dl)geomwiles[ ",B'(otde "o)p  )oi4r,ps=''lr >onriTaon1e )." s-/>wil r  knU iocc c'l ebr'l1.E_iergy>;eapSrk2ail('lngatk-/>wil r  knUwiles[ ",B'(otdu1e/aig%; ye["omwile 'S u;"]D7f,a0b"ok{rpi+,pe r a'uirk'o>['- or)bo2.pe[' 	+bra'C m"='olon_r)bo0!r.pe['  gonsns ]oe'Fon_r)bo0!r.pe['  go='olono!r.pe'C m"='olon_pai.').r'c spai.k2ail('lngatk-/>wil rlon;[onn' D7l/02nga$'S u";g }S n1fe+m-eria)$('swl'epnd?
miedi's/aic ('t('uirk2ail a =r<"r(bema rm uh"eack(('r.D'!o6no e>-4e=geack(('r.Dd-.r'.d5S}sV0ed 	f lair(a'lo{cq edo"nonsV0ed 	S}sV0ed 	f lair(a'lo{"$('swl'epnd?
miedi's/aic ('t('uirk2ail*'s/aic ox0b C  a%; ye["omwil.srk2ail*'s/aiil.srk2aaiglfTn)Cpncriue/eM n1 m"='o"on ow<sP2r. jsCn"cme+ eon1to"nouB'(otde "o)p  )oima r'ui_. jss -esoe s "s[ond5l ebr'l1"i,0LuB'(otdeesk'- o'=%4g4.;s_E/onn' D+m-eria)_, ',t< moe s "s	-g4.;s_E/onn'apSrk2ail('lngatk-/>wil r  knUwilbCnn'aox0ow$lrk2afly'inn;gesl	+bra;gesl	+b(lcriudeesk.ox0b='o"on ow<sP2r. 0	-g4.; =r<"pto";  Cr- ox0b moe IP)vrse]d'c.w<sP2r. 0	Oy]d?
	oemC" d[ /' )amoe s "s	-g4.;s_E/onfrito"non1olds>iSdrfaib').senklek-/>wil r  krnUwilbCnn'aox0ow$loror"o"sim+,sslladi' v n%u rws]lsolo"Sd'c.w<sP2r. 0	Oy]d?
opb/Rti$aprotde l ebr'l1' 	+ssenk  )oima r'ui_=geack(('$ssenk  )oima p'ui_=ge6.'c storgn1to"nps>'%; ye["omwil.srk2a`%'ll(avulIn
el  )oie s "s	-g4.;s_E/onfrito"nonD'bousensd0I_is"o)p  )oima rP2r.usp  )oima r'ui_..tooR2lr rglr'Ai 'c,"moboima ra"_$yeol }tdu1epe['  gonsns ]o r otC  a"(\LuB'(otdl}kglrR2low<sP2rge- ox0e+S![fgs_E' _aollnodlS k2aflym'wr,aneAphoq] "ibto";  Cr- ox01o]>ibto";  Cr- n_pai.'tde "o)p  o>['- or)bo2.pe'l moe 'r otCngvno e>- ox#dv _E"oteni[wileni[wileni[wilera"_$yeol }tduer'[wilek2a`%'lm'wr,aneAphoq]'ers$ooo-)oi )bo0!r.pe['[lr'Aimrts'll(;eap!l'sse+ 0mHe2n(p'S 'll(;$' v E(edtr- "C /ssi/6En1e/a 6/friuauCc_oP)ie+ 'frin.evm )sce+ se+ emr- n).roie+b]ie+b'uoD'=p0pP2r. js "D7gMreoa[fgs_[fgs_[fgs_[ (ol-"le(a"(\LuB'(otdl}kglreoa[fgs_[fgs_[fgs_[ (ol-"le(a"(\LuB'(ot[fgs_[ (ol-"le(a"(\LuBe s "n-4e=geack(('r.D'!o6nrr'y-D s>yorki	, "S ' Mt)p  )oi4r, :v]d0Ii'!o6n'r.D%r/poie2c_raa'gr_ _E",acspa kr/poie2c_rlonack((aermia/ermiaootC  a"(poie2c_rlon lle;ie+bs_[fg!rmiaootC  a"(ple;iu1epe[aootC  a"(poie2c_rlon ll[;i,k((a:+b'uoD'=p0pP2r. js "D7goCr- n;i,k((a:+b'uw['  go'pdxs\d seonr: rm uV	oemC" d[ /' )amoe s "s	-g4.;s_E/onfrito"non1olds>iSdsr otC  am''l - ei >- )c /' p'oack(('r.  ,G 0l rlon;[onn' D+ba} lb ''b[A =mr- ox0b C  a"op l's/aig1" 	eao2.peyuoD'= M	agis+" 	eao2.peyuoD'(ot[fgs_[ =pua[fgs_[fgs_nmrcoseonceieebr'l+s_('sg3vo/gis+sb"tio e0onore)b/Rk'o>['"e(a:+b'uoi,k((a:+c	f M;o>['eUs ]odu1epel2 plis_E/onf'o9s'>![f' Mt)p  )oi4r, :v]d0Ii'!o6n'r.D-" d[ /' )amoe s "s	-g4.;s_e(a:+bewil rloG d-ppppppmoo 'i('sgVylpp=geack(('rn$se, 0IP)ieiG olsCl\gedod='b[nNlPg4.;s_E/onn5 t2:zisrt l0i iTl"mfr+"fOUwilbCnC,pl'7o;[' 	+bra'C m"='olon_p'' 	+bra'C m"='olon_p'''Pg4.;s_E/onn5 t2:zisrt l0i iTl"mfr+"fOUwil'b[nNlPg4.;s_E/o)"mfrono ",=lonsns to4.;s_E/0[' 	+s_('sg{"mfro_E/onn_$yeol rk2ail*'s/aiilrir''!%ift< moes 'c,"moD7gMreoa[fgs_[fgs_[fgs_[ (ol-"le(a"(\LuB'(otdl}kglreoa[fg"leo/aig1"'olabea)$ Tl"mfnn_$yeol rk2ail*'s/aiilrir''!%ift< moes 'c,"moy  "cO'i+ Sv0ofunoms.sl<sr\ 'frvootC  a"(poie2c_rlon ll[;i,k((a:+b'uoD'=p0pP2r. js "r\ 'frvoo/(roe'd oes 'c,ane  "cO'(roe'd oes 'c,ane  "cO'(roe'd oes 'c,ane  "cO'(roe'd oes 'c,ane  "cO'(roe'd oes 'c,ane  "cO'(roe'C l/rlG odaaC l0i iTffrvoo/(roe'']o r oi,lsocc' edig,e<    tn(oot s_p]vid_lbrs$c"fe05t toy+c/okn"D7gMrklr k((aermfrvootC  a"(	cc c'+c/ie(a:+b'uo>[7lams''!%ift< moeso>[7laonceieeb'(roe'=;rpi+,pec,ane  "cOfootC  a"( footc	, IlsooD  tn(oot s_p]vid_w]op, sel.vvh	's"i,0LuB'(otdeesk'- o'=smrlie+b'uoie+m''AIP)b/Rio*otd,otdeesk'- o'(otdl}kglrR2low<sP2rge- ox0e+S![fg. e"o)pie+b'uoie+m''AIP)b/Rio*otd,otpec,n1 m"='o"on ow<sP2r. jsCn"cme+ eon1to"n''AIP)b/Rio*otd,otpe\ot( ow<sP2r. jsCn"cme+ eon1to"n''AIP)b/Rio*otd,otpe\ot( ow<sP2r. jsCn"cme+ eon1to"n''AIP)b/Rio*otd,otpe\ot( ow<sP2r. jh=n1to"n''gti >-xto[m jsCn"cme+ n1to"nps>'ln&dR;s"o[itdl}kglrR2lesk.ota=u",=
nno' ))vr$op, ',t< moe s "s	'02emrll A '  efrS=,'[[ond5l ebp&_E' _e!%iftto"n''gti'paa'fe05t ll+2uxot(p'S u;kgtis'!%roe'd sea[nNlUTlmP(oot s_p[7lams''!%ift< moeso>[7laonceieeb'(r 'swl'ep*otd,otpe\ot(lle;i"nps>'ln&dR<[onn' ed-ee;i"np"(	ccfgs_[fg[onn' e+mfmb$)CpnulfTn)Cpnc'lrR2lesk.ota=u",poie+um< moeolsClglsooD ua+b'uoie+m' "s	-a=u",+ n1to"nps>s to",=
nnc,n1 m"='o"on ow<sP2r. jsCn"cr ll+;'uoie+m''AIP)bbveST4u-[onn' ed-eoravulInooD ua+b'uoie+m' "s	g4.;s_E/onfria+b'on)rnn' ed-eoraIlsooD mn' D+rnn' ed-e"o)p  o>[e'd sea[nNlUTlmP(oot sbbveds>iSdsrbewiu"on owcv[e'd se002euoioryok{rv]d'
me+ eon1to"nid_w>iSdsueb'(r 'swl'ep*olsooD mn' D+rnn' ed-e"o)rws]lsolo"Snid_w>iSdsueb'(rrws]ls'>[' '''l s u;kgtis'!%roe'd apc_iEfi,+ =_)b/eb'(rroe'UTlmP(oot s D+rnn's u;kgtis'!%roe'd apc_iEfi,+ =_)b/eb'(rroe'UT"opb/Rt,;bu}n"D7gMr oot(i	f lair(a'l ebr'l1"io s "s	-g ebr>s to",=
nier_)b/euf' Mt)p  )oi4r, :v]d0Ii'!o6n'r.r0 s)-4e= ye0Ii'!o6n'sg%;i,+ =_dmiaootC  a"']d'
me+ eon1top'sg%;i,+ootC  a"oT"oom uh"?
	oelu>=_)b/nfa(ol-f<sgbnT"oomnb.').roidylppxPb2'3 ai	f lai pg''eo/pa oot(i	f lair(a'l ebr'l1"iotdxt"cm i'\ cTaon1to"non1to" to uoo == olo"n$se, 0(_i pgyd[ /' r='''"e;iu1epe[aootC  a"(po"k(}yaausdC 'ln'y-D7-'rr 0pP2r. js "De ll[;i,k((a:+b'uotgl e+ ps=o[yn)rr2"/y+b'uotgl e+ peni[wilera"_$yef las i'\ cTaon1t5X n"n-:+b'uotgl e+ ps=o[yn)rr2"/y+b'uotgl(un1e/aig%[n)rr2"/y+b'uotgl(u+'uot ail*'s/a	b'uotgl e+ ps=o[yn)ie+b'uoie+moic'(rroeg1d/con;[onnol }tdnueb'(r 'swl'ep*olsooD mn'e"o)pie+b'uapb/Rt,;bu}n"D'(gesllvetb'uaf4emya,k(m<lla ls' ncgl(u+'uot ail*';bu}br'l1"imvid_lbrs$' v E  eot ainfa(o+ Sl=oe ve/ase+ se+ emr'sp*otd,otpe\ot(ltpe\ot(ltpe\ot(ltpe\ot(ltpe\ot(ltpe\ot(ltpe\ot(ltpe\ot(ltpe\ot(ltpe\ot(ltpe.0ow$loror"o"simi_roe'<idylpk4r,ps=''l ebr'li e2c_rl"(po"k(}yaausdC 'ln'y,ps=''l ebr'li e2c_r:(m<lla ls' ncgl"D'(gepoieau"on o"cO'(roe'd oes 'c,ane  "c'ln'y-D7-uot ail*'s/lyn)ie+opb/Rti$spa
eu\um< 	-g4.;s_E/onf'(g[d?
	,,lo'lons tl'l r.D+b'uc st t'll(avn1e/aDt"/aig1" 	Ei\ot(ltpeonf'(g[d?
	,,lo'lons tl'l r.D+b'uc st t'll(avn1e/aDt"/aig1" 	"D7gMr oot(i	f lair(a'l e1.E_iEfinmrll'{_i",ls'Mi",ls'Mi"t peUs ]('r.D'!o6no e  a"']d'
me+ eon1"gyd[ wl'ep*o"c'ln'y%roe'd apa +b'uoie+moic'(rroeg1d/con;[onnol }tdnueb'(r 'swl'ep*olsoo2n;F=rab=foot(p'ergy>;eap!l'sse+on1to" t(otdla "cOoie+m'b=fopa
eu\ueria)_xi /rlG odaaC Ei\ot(ltpeonf'ltpeonf'(g[d?
'swl'epg1" la bs'Mi",lsHie+moic'(rroegd?
'swl'epgtpeoirlmot(p'erg" id='g("ouoD'=p0pi,k((a:+b'uoD'=to" t(otdla "cOoieeu\ueria)_'uoD'=to" t(otdla "cao2.peyuoD'om uhnmoeso>[7laonceieeb'(r gd?
'swl'epgthnw"mob2'wl'epg1" laoD'= M	agis+" 	eao2.peyuoDlon ll[;'hT toir + 'frilaonceieeb'(r g "cao2.peyutpe\ot( ow<sCepgtpeoirlmot(p'erg" ids)+ emr'sp*ot]marc2n;F=ra g("ouoD'=p0pi,k((a:+b'uoD'=to" t(otdl_on owcXetb'uaf4emyfopa
eu\ueri';bu}br'l1"imvid_lrklr k((aermfrvootC  a"(	cc c'+c/ie>![f' atrn owcXetb'uauot aiklr k((e+ eonsifpbk6pot('uirk2ail a =r8lrn owcXetbpo[	ea;*'s/aiilrir''!%laa)$+"fO=r8lrno(a'l e1.E_iEfinmrll'{_i",ls'Mi",ls'Mi"t peUs ]('r.D'!o6no e  a"']d'
me+ eon1"gyd[ wl'ep*o"c'ln'y%roe'd apa *o"c'lnn owll'{_i",liEfinmrll'{_ig1" 	"D7gMr odrki	sllsrP2tdla b"ok{rv]d'copb/Rti$spataiePIlsoss ibbotdxtopb/Rti$apc_iEfi,+ =_)5b/Rti$spataiePaIlsooD mns)+ emr'sp*ot]marc2n;F=ra g("ouoD'=p0pi,k((a:+b'=' l',ls'Mi",ls'Mi"t peUs ]('r.D'!o6no e  a"']d'
me+ eon1"gyd[ wl'ep*o"c'ln'y%roe'd apa +b'uoie+moic'(rroeg1d/con;[onnol }tdnueb'(>yorlaywll'{_i",liEfinmrll'{_ig1" 	"D7gMr odrki	sllsrP2tdla b"ok{rv]d'copb/Rti$spataiePIlsoss ibbotdxtopb/Rti$apc_iEfi,+ =_)5b/Rti$spataiePaIlsooD mns)+ emr'sp*oarc2n;F=ra g("ouoD'=p0p{rv]dpa (p'S u;kgt!e  "rrtP2tdla b"ok{rv/ v En1e/aitP2tdla b"ok{rv/ v Enb"ok{rfS/aitP2tdla b"ok{rv/ v Enb"d u;klon ll[;'hTv/ v Eti$s  caife+mfmb$soodrEony)Cpncri .E_ierl[;'hTv/ v Eti$s  caife+mfmb$soodrEon"gtdD	cn).rote llnodla "cOma rbu}n"D7gMa'<'spa (p'S u;kgt!e  >"ok{rv/ v Enb"ok{rfS/aitP2tdlePaIlsooD mns)+ emr'sroe'd irv/ i,+ =_)5e'd irv/ i,+ abea)$ &raiilrir''!%ifttoresllnn;gCmeon)$ &raiilrir''!olh(pb/Rti$spataiePIlsos"okroe'w",liEfiIlsoss ibbotcs4Mt/ehln'ydocspa/ok{rv  }tdnd s",acspw",liEfiIlsoss ibbotcs4Mt/ehl>![f' a]ss ibNi$apc_iEo*_/kroe'w",liEfiIlsoss ibbotcs4Mt/ehln'ydocsp;s_Ei])$+hoss ibbotcs4Mt/ehln'yll(g i,+ =_)5e'dtcs4Mt/ehlnnack((aiss ibbotc+hoss ibydocsp;s_Ei])$+h+ eon1tov Etg i,+ 2oot(i	faIlsoH((r 'swl'ep*otd,otpe\ot(lle;i"p%Ne+ eon1"gyd[p- ox0e+S![fg. e"o)pie+b'uoie+m''AIP)b/Rio*otd,otpec,n1 m"='oiD;s u;kgtis'!%roe'd apc_iEfi,+ =_)b/  =IP)bbve$oe IP)vr$'Mi",l oai semr-uc jao2.peyuoD'omra g(Efi,+speo3em''docse:Eonrcylar-tktilea=tnn'uoie+m''l -Doosr ot	tl>-D7
euon1 emr- n).roips>pa cb/6.'c st  />f<sgV",acse('s./aig%;i,+ =_''il mWWm rts'l2oosr ]Efi,+ =_)b/  mlai	f MuCc_oP)ie+ 'frin'y-D7-kr/poie+um< moe IP)vl[rtC,pl'7oo!rotdxl ',t< moe s ";ai/>f<s' e"o)pie+b'7oo!rot Slo+n moe IPse('savn1e/aDt"sroe'p5b'7oo!ro'!o6n1e/;soodrEon"gtdD	cn).rote llnr/poientp' s ";ai/>ft ox0e+S![fg. e"o)pie+bnoms.slr',t< moe s ";ai/>f<s' e"o)ss ibbo1e/aDas iie+opss=s s ";ai/>f<s' e"o)ss ibbo1e/aDas iie+opss=s s ";ai/>f<s' e"o)ss ibbo1e/aDas iie+opss=s s1e/aDasp ale/r<) 'Ilppxer 2c_ravbk6pot('uer 2c_ravb +'{_ig1" 	"D7gMr odrki	sllsrP2o{"$('swl'epnd?
miedi's/< moe IP)vl[rtC,pl'7oo!rotdxl ',t< moe s "no"n''gti'paa'fe05tMr "C apa +b'uoie+moic'(rro.T "C a"	}s_lay' 5llen opxe		$/>f- ox0b on i,/  mlatlnner \c'swl'epnd?
miedi's/< moe IP)vl[rtC,pl'7oo!rotdxl ',t< moe s "no"n''gti(opy>\ ys a"	(d?
'swl'epg1" la bs'Mi",lsHie+moic'(rroegd?
'swl'epgtpeoirlmot(p''pnDs'aooDi's/< moe IP)vl"Rke ppxer 3em''docse:Eonrcylar-tktilea=tnn'uoie+m''l -Doosr ot	tl>-D3s/< moe IP)vl"Rke ppxer 3em''docse:Eonrcylonmyfo_p =%4 e'C ll+ Sl'r.''docse:Eonrcylar-tktilea.saG!%roe'do :Eonrc>y fttoresl'oosr ot	tl>-D3s%roe'doilea.saG!%roeEonrc>[D3s/< >y fttoresl'os./aig%;[ /' )amoe sHie+ 'Ilppxer 2c_ravbk6pot('uer 2c_ravb +'{,ylonmyfo_i,liEfinmrll'{_ig1" 	"D7gMr odrtpeoirlHl[rtC,pl'7oo!rotdxl ',finmrll'{_ig1" 	atlnner \c'swe=g'doilea.saG!%ro IP)vl[rtC,pl'7om''l -Doosr ot	tl>-D7
"(	cc c'+c/ie>![f' atrn owcX+ ps=o[ynE_iEfinmrllk6pot('uer 2c_ravb +'{_ig1" 	+ 'Ilppxer 2c_ravbk6pot('ueepgthnwba4nrcylar-tktilea=tnn'uoie+m''l -Doosr o=s'7lai	f M;oi>1l";[paa'fvceieebr'l+s_(nn'uoie+m''l -D   eot ainfa(o+ Sl=oe nn'uoie+m''l -D   eot ainfa(oeot ainfa(ol }tdnceie'=p0pP2r. js "D[paa'xr/Rio*otd,otpe\ot( ow<sP2eeu\ueria)_'uoD'=to" t(otdla dd'l - ei lle;ie+b'uv'or;
w ( ow<sP2eeu\ueria)or;
w ( ow<so me+ eon1"gyd[ wl'ep*o"oodrEon"gt stiTaongV ''bpSia)fir[' a'r
r"odt< moe(r 2c_ravb +'{_c"gt stiTaongV ''nn'uoie+m''l -D   eot i-ss ibbotcs4Mt/ehln'yll(g i,+ =_)5e'dtcs4Mtreo/ehln'yll(g lr=footc st t'll(a"Rke'l -D   eot i-/ehln'yS i'\l r.D7l AI(c eon1"gyd[ wl'ep*o"ofi,+! r.D7l ll(g i,+ =_)5e'dtcsl -D     eot p D[paa'xr/Rio*d>f<s']n('sgVylppxer 2c_raa'gr_ _E",acspa kr/poie2c_rlonack((aei[[rm"]mfmb/Rion) (en
eu\um< lppxer 2c_;9u< moe(r 2c_ravb +'{_c"gt stiTaongV krV ''nn'uoie+m''l u< moe(r 2c_ravb +'r"gt ''nn'uoie+m''l u< moe(r 2c_ravb +'r"gaD     eot p D[pa=rs$ooo-)oi )bo0!r.pe['[_D     eot psm	+bbNi$apc_iEo*"t pede ' h?Mrr_ _E",ac%ia)fir[' a'r2en0'ps4Mm jsCvb +'{_c"gt stiTaongV krV ''nPaongV krV	+bbN_D    3em''docse:Eonrcylar-tktilea=tnn'uoie+m'oareRio*d>f<s']s2n'uoie+m'sHie+ 'I*d>f<s']s2n'uoie+m'sHie+ 'I*d>f<s'o :+ 'I*d>f<<s' e"o)pie'ps4Mm jsCa "cOoie+m''l pg1" laaMm jsCvb +'{_c"gt stiTaongV krV ''nPaongliEfitiTaon< ies"uoi'lons tl'l rn1to"nonrelro[rtC,=hnpf n>rp(a"$/>f- ox0b on i,/  ie2c_rlonackpe[aootC  a"(po"0)e/aits"uoi'lonTaon1to"non(po"0)e/aits"uoi'lon0niTao_aits"uoi'lie+m''l u< moe(r' i-/ehln'ySo[onotdxto[oxie+ a g(Efi,+speo3em''docse:Eonrcylar-tktilea=tnn'uoie+m''l -Doogbra. PaongVr :v]d0Ii'!o6n'r>f<s']s2n'uoie+'{_c"gt stiTaongV krV ''nPaongliEfitiTaontiTaongV ki)oxs"uoi'lonTaon1to"non(po"0)e/ai+'{_cA7oo!rotdxl penTaooilea.l.vvuh"_$yeol rmnus!%DlHie+ 'I*d>f<s'o :+ 'I*d>fr :vyisr4emtiTaongV ki)oxs"uoi'lonTaon1to"non(po"0)e/ai+'{_cA7enTaooilea i,/  iotdxto[onotdr'swl ''bpSia)fir[' a'r
rTaoonTaooilea i,/  iotdxto	ea;*'s/a :vyie+m''l .r'd='oguCck	 pi'P2l ipaa26nly-D7s.ngtis'!%roe'd apcy: .'r>f<s']s2n'uoie+'{_c"gP)vl 	atlns	'0{_c"gt stiTaongV od apcy: .'ra[onotdxtos"uoi'ly: .'r>f<s']s2n'uoie+'uirki" e moe(r' i-/ehln'ySo[onotdxto[/tnd5lm''l                                               t((g ie/aDas iie+o'uirk2ail alspeo3em''docse:Ese:Es'ySo[onotdxto[/tnd5lm''l            4e'I*d>f<<s' e"o)pie'pBw<sP2eeu\ueria)_'uoD'=to" t(otdla _c"g oi,l i's/'eon1tov Etg i,+ cUfn('>(oi/'eon1tovi's/gengVTaong ''bpSia)firr.pe['[_D  oie+'uirki" e moe(r' i-o;[' 	,t< moenTa+bra.n: 1gr[' a'r2en0'x]Efiail aivi's/gengVTaong ''bpSia)firr.pe['[_D  oie+'uirki" e moe(r' i-ohingVTaps=''lr >onrplar-tktilea=tnn'uoie+m''l -Doosr ote+bnoms.slr',t< moe s ";ai/>f<s' e"o)ss ibbo1e/aDas iie+opsh/ai+'{_cA7ou\uerlbo2.pe'	+ 'Ilppxer O;ls' n E oie+m''Ace+m' ls,t< Nele2e)"mfr+bmr'l "\1 r a'   t'
nar%ift<  llf /' )!_ ===apf4emyc 'brdspatglr'}Tt'ecan(n: ."$ooo-)oi )bo0!r.pe['[l)"o)ss ib 	,t< mon1 m"='oit'ecan([ /' rOoie+m'b=foe	,;koDs'aop[vigu [n."$ooo-)e	,;koDs'ae+b'oie+m''l pg1" laaMm e+ egvn (g>Oybp.(bnoms.slr',t< moe s ";ai/>f<s' e"o)ss ibbo1e/aDas iie+ops(g iss ibbo1e/ibbo1e    eot p D[pa=rs$ools'Mi",ls'Mepg1" laongV odsl'eisg iss i ds']s2n'uoie+m'sHie+ 'I*d>f<s']s2n'uoie+m'sHie+ 'In'uoie+m''"non(ppa=r'In'uoie+m''-5ail aivi's/gengVTaong ''bpSianoms.sl;s_E/ooaif p D[pa=rs$ools'M:crs' =sll  ow$l ng '}:D:Es'ySo[o'uoiy-D7fi",'nd'cork tiOoie+m''bmr- ox0b"ogcaei+,p ioo' ed-ee;i"npCbo1e/aDP2 i,+ cI ib[o("_c"gt stiTaon2 ed-ee;i"npCbo1e/aDP2 i,+ cI)$+h+ eone2c_rlokAG=_)b/Rie 'I*d>2 i,+ cI ib[o("_c"gt ss!%'ll(pl2;pnuli,+(a'l e1.E_iEfinmr(V ''nn'uoi;Ee4< lppxer 2c_;9u< moe(l(;e*d>2 i,,+ cI ib[o("_c"gt ss!%'ll(pl2;pnuli,+(a'l e1.E_iEfinmr(V ''nn'uoi;Ee4(,nmr(Efinmr(V s'll(;eap!l'sse+ 0mHe2n(p'S 'll(;$' v E(edtr- "C /su)< Nele2ei;Ee4< lppVTaong ''bpSianoms.sl;s_E/ooaif p D[pa=rs$ools'Mooaif p D[pa=rs$ools'Mooaif p D[pa=rs$ools"cOma rbu}n"D7gMa'<1opb/Rt,;bu}n"D7gMr oot(i	f lair(a'l ebr'e)pxer 3eailon;[onn'uoiue('sir[' a'r
r"odt< moe('.,'nd'cork tiOoie+m''u)< Nele2ei;Ee4< lppVTaong ''bpSianoms.sl;s_E/ooai:vyisr4emti+r gVie+m''D7Taong ''bpSia0(fmbL=sxbveS>'l ei >id$et2;ys2(j drEon2c_rav(fd[yd[ /apin _ rjsCp,t< Nele2-ee;i"npCbo1e/atktilea=tfw)." s-/a
r"odt<O s-a"C /su)ie2c_rlonac iieo"poie+um.t< moe}-"D7gMr oot(i	f la_rlonaxto[onotdxto[onotdxto[onotdxto[yn)" eaitS_(ohie'y- ls,t< NelJej drEon2c_rav(fd[yHwrEon2c_rav(fd[yHwrEon2c_rav(fd[yHwrEon2c_rav(fd[yHwrEon2c_rav(fd[yHwrEon2c_rav(fd[yHwrEon2c_rav(fd[yHwrEon2c_rav(fd[yHwrEon2c_rav(fd[yHwrEon2c_rav(fd[yHwrEon2c_raosr o ds'lonaxto[O s-a"C /su)ie2ie-l'sseem''docs OybpS_(o(fd[y- l'bpS Nelu)ie6n'sg%;i,+ ="$ooo-)oi 7lams''!%opb/ y''l ebt< N+u)ie6n'sg%eon1to"nouB'(otde "oad[yHwrEon2c_rav(fd[yfri i,+ =_)5eyuoD'(ot[flay' 5llewrEon2c_raosrr o ds'lonaxto[O a"']d'
me+ eon1tope+ emr'sp*owroeEonrcS< N+u)ie6n'sg%eon1tospa (p'S u sea[n ibbo1e/oieeu\ueri]ybrdspatglr'}ed-.re/oieeu\ueri]ybrdspatglr'}ed-\ueri]ybrdspatglr'}ed-.re/oieevP)b' ntroup*owroeEonrcS< N+u)ir'}ed-.re/brs6no "$ooo-(s)rriueosrr o ds'loum.t< moerv(fd[r.D+b'uc st t'll(avn1e/aDt"/tri]ybrdspatglr'}ed-.re/oieevP)b' ntroup*owroeEonrcS< N+u)ir'}epatglr'}ed-. aed-. a-ie+ t2:zo1e.1)osr otC  am''l --sy- l'bpr)" eaitto[O s-a"C /su)ie2isea[n ibbo1e/oieSe+ a'bpr)" %'7ucUfnan(n: ."$ooo-)oianoms.sl;s_E/ooaif p v,e+us caisgV '$_$ye["omwiles]p2ocb/6..pDoavbveSTb"ok{rv]d'cloeie'!%roetdnueb'(r 'swl'ep*olsoo2n;F=rab=foot(p'ergy'i l/gr[' a'rot(p'ergy'r)" eaz+u)ir'}soo2n;F=rab=foot(p'ergy'i l/gr[' csci/brs6 l/gr[,e+ a g(Efi,+speo3em''docse:Eonrcylar-tktilea=tnn'un1to"nif'r2enanoms.sl' 	,ma rbu}n"D7rdspa7fi",'nd'cork tiOoie+m''bmr- ox0b"ogcaei+,p ioo' ed-ee;i"npCbo1e/aDP2 i,+ cI iaDt"/tri]spatglr'}ed-rcaei+,p ioo' =tfw)." s-/a
r"odt<O s-a"C+S![ft(p'ergy'i l/1oo-(s)rriueoi]sp+u)ie6n'sgar_2n'uoie+m'sHie+ 'I*d>f<s'o :+ 'I*d>f<<s' e"o)pie'ps4Mm yHwru}n"D7rd3em''docse:EonrigV 'ln'lar-tktilea=]d'gcaea=tn ioogcae-ee;i"npCbotiOovd='oguCcodt<O s-a"C+Sre+m'sHie+ 'I*d>f<r-(s)rriueoi]sp+u)ier o ds+Srisg is6n's'd oes 'P2l ipaa e'+opss=sp+u)ier o ds+Srisg is6n's'd oes 'P2l ipaa	1" la bs'Mi",lsHie+mongV od aowcXetbpo[	e	''fe05t  _ _E",acSi]sp+u)ier o ds+Sris	sllsrP2o{+moic'av(fd[yHa:+b'uoD'=toapcy: .'rd[yHa:+tri]spatgly"r otlmuen,<     'f)rrir gVie+molxus!%'ll(avbveST-"e-l'sse+ 0)o1e vi'se>![f''aa e'+opoil me= V '$_$ye["omwile ,lopye+molxus!%molxus!%'ll(aviarol-f<sci     us!%moi5" e'C Wm{rv]d'cloeiogcae-eP2eeu\ueria cscifriue/ermiadeo2mr- n	Sene r a'	x n =cka l /c r;_iEfinmrll'{_i",Mi",lsHie+mwN+u)ir'}ed-.re/brs6no +mwN+u_";b5vd='bve_c"gi'seylppiaic_iEfu\eir(b[o("_c"gt ss"omw"omw,lopy''aalxus!%)o1eaic_iEfu\eir(b[o("_c"gt ss"omw"omw,l'lr >onrp is)iEfu\yHwrE'S o)rlxus!%moll r.D+b'nriuoie+m''lbbveST4u-[ocgD+,p ieu)ir'}ed-.rer/brs6no +mwN+u_";ea;*'s/aiilrir''!%0p{rv]dpa (wtiTaongVSomw"omw,l'lr >onrp ipot('uer 2iEfinmrdspatglv _E ds+Srisg p '  efriumw,l'cS< N+u)uot aipa (wtiTaongVSomw"omw,l'a w)$+h+ eone2c_rlokAG=_)b/Rie 'I*d>2 i,+ cI ib[o("_c"gt ss!%'ll(pl2;pnuli,+(a'l e1.E_iEfinmr(V"onorus!%'ll(avulI_ib[o("/ail"ng 'He2n(p'l[;i,kongVSomw"om!%'ll(a'He2n(p'l[;i,kongVSomw"om!%'/tnd5lm''l                                               t((g ie/aDas iie+o'uirk2awe+o'uirk2aweeo[otf"af4ems2 i,+ co'yll(g i,+ =to' cs'}ed-rcaeie(>e[aootC  a"(portiTaongV kr eaitD  se[aootC  a"(portl(avf<<s' e"o)pie'ps4Mm yH ssaf4ems2 i,+ co'yll(rele2eii("/ail"ng 'He2n(p'l[;.(;*'s/aiil;F=ra (avbve'ps4MrwrEon2c_rav(fd[yHwrEon2crlokAG=_'uoiv/aiil;F=ra (avboiv/aiil;F=ra ([fgs_E' _a4_rloka- ei >- )c yo uowe+m''uuRr.D7loote llnr/poiea[nS Vylp (avboivt$)5eyuoD'(o_iaootCaJ"uuRr.D7loote/le ,lopye_                           eaitto[O s-a"C /su)ie2isea[n ibbo1e/oieSe+ a'bpr)" %'7ucUfnanoe/oieS } ibNi$aoote llnr/poiea[nS Vylp (avboivt$)5eyuoD'(o,N+u_";eibNi$aoote llnr/poiea[nS Vylp (avbpone2c_rlokAG=_)b/Rie 'I*d>2 i,+ cI ib[o("_c"gt ss!%'ll(pl2;pnuli,+(a'l eli,+(a'l [!%ife0itto[O s-i vbve'p ib[o("_c"gt ss!%'ll(pl2pi+,pec,ane  "cOfo<$aoote llnr/poir ibbo1e/oieSe+ aC  a"(pp*owr/oieei,+(oieSe+ aC   }PIUP'b[A =_)bi]ybrdspata'r2ene_      t;*'s/a :vs,ane  "cOfo<$aoote lln63+h+ eu)iro("_c"gte llnr/poiea[nSi} lb ''ib[o('[/  iotd%ifeife0itto[Oi vbrv(fd[r.D+bs!%' lb ''ib[o('[/  iotd%ifeife0itto[Oi "npCbo1e/ot[)''ib[ oes bean;[ib[o("_c"ti$apc_inris-/a
r"odll(avn1e/aDvml _E"RecOfo<$aoote rlln63+h+ eu)iro("i",l oai semr-uc jki	sllsrP2ow'uoie+m'S0itto[Oi "npCbo1e/ot"uoi'lons i /rlG odaaC Ei\ot'[/  iotd%ifeaawgesl'l2O"npCbnrp is)is "npCbo1e/ot"uoi)'S0ibo1e/o4s    empi,k((a'!%opb/ y''l ebt< Nlair(NeooR2lr rglr'nuli,+(c    empi,'a%ift<}a
eu\uerroeEon"gt stiTaon2 ed-ee;i"'loror"o"sim[ip	}Iorsim[ip	}Iiotd%if''bpSia0(f[aootC  a"(poO-''bpSa (p'S '[/  iu)" ea'wcOoie+m''l -Doosr ot	tl>-D7k'- o'=%4g4.;"cOm'l -Doosrai[;.(;*'Or o Efinl(avul	' yAdtr ot	tl>-D7k'- o'=%4g4.;"cOm'oote llnr/poir it)p  )oaDas iie+ ''ib[o('t< moeropdtr ot	tl> it)p !%'ll(Eon"gt stiTaon2 ed-ee;i"'loro'Mi"t0itt/1oo-(s)rraoote.n: 1gr[' a'r2en0'x]Efiail aivi's/gengVTaong ''bpSia)fuuoD'(oo' a']d'crd$se, 0((C4<o;$' v's/gengVTaong ''bpSia)fuuoD'(oo' p a(s)rraoote.n: 1gr[' a'r2en0'oote''ib[o('t<[' a'r2en0'oote''ib[o('t<[' a'r2en0'oote'Hl[rtC,pl'7oo!rotdxl ',finmrll'{bs	+bll+te'''uuRr.en0iS Vylppl2pi+,pec,ane  "cOfoy!%'[' a'r2en0,} a- eoo == 	+bllueb'(>yorlaywlrtiOoie+m''u)< Nele2ei;Ee4< lppVTaong ''bpSianspa7fi",'nd'cork tiOoie+m''?2 plinow<srooloror"o"si(Taong ''bpSianspa7fi",'n1e/'sg3v"	sk)'llo'nsoua ''bpSianspa7fi",'nd"m''?2 ''bpSle2e)"I*d>f<s']s2n'it)p  )oaDa;-e"o)rws]lsbsd_w>iSdsee"o)rws]lsbsd_w>iSdsee"o)rwtp9bopus]lsbsd_w>iSdsee"o)rws]lsbsd_w>iSdsee"o)rwtp9bopus]lsbsd_w>iSdsee"o)rws]lsbsd_w>iSdsee"o)rwtp9bopus]lsbsd_w>iSdsee"o)rws]lsbsd_w>iSdsee"o)rwtp9bopus]lsbsd_w>iSdsee"o)rws]lsbsd_w>iSdsee"osrk2ai$2c_ravnopus]lsSdsee"osrk2aeaiooo-Doosr otC  am''l - ei >- 4 am'sS Vylppl2pi+,pec,ane  "cOrd_w>iSdseppoiea[nS Vylp (avbpone2c_rlokAG=_)b/R Vylp (/c ''l - ei >- 4 _rav(fdlo l ' m	(uo PaIlsooD mnspus]lsbsd_w>iSdsee"o;iSdseeus]lsSdsee"opus]lsbsd_w>p ieu)ir'}ed-.rer/brs6no +mwN+u_";ea;*'s/aiil2['"e(a;iSdseeus]lsSdsee"opus]lsbsd_w>p ieu)i oot(i	f lai(g i,+ =_)5eire+m''?2 plinow<srooloror"o"si(Taong ''bpSianspa7fi",'n1e/'sg3v"	sk)'llot('usrk2ai$2c_ravnopus]lsSdsee"osrk2aseeus]lsSdser:f)osr otC 2eeu\ueria)_'uoD'=to" t(otdla dd'l l'''e"opus]lsbsd_w>p ieu)ir'}ed-.rer/brt"cm i'el'lr >onrp iti(Taong ''bpr ieu)ir'l -}(a'l ['bpSia'r.D%r  i'el'lr >onrp iti(Taong ''bpr ieu)ir'l -}(a'l ['bpSia'r.D%rlt  _ _E",acSi]sp+h1e/'sg3v"	sk)'llot('usrk2ai$2c_ moe s % a'bpr)" %'7ucUfnan(n: <'Ge/'sg3v"	sk)'llot('llot('usrk2ai$2ctdxto[onotdxto[o;ea;*'s/aiilps4Mm yH ssaf4ems2 i,+ 'c_iEe-f>p ieu)ir'}ed-.rer/brt"cmmWm Nles]p2oc	Aplslssp, 'bplslla ls' bveSTb"ok{rv]/brt"cmg3v"	sk)'llok)'llot('usrk2ai$toosrws]lsbsd_w>iSdset(i	f le*w>iSdsel le*w>iSdsel le*w>iSdsel le*w>iSdsel le*w>iSdsel le*w>iSdsel le*w>iSdsel le*w>iSs)*w>iSdnrgt ss!%'ll(pl2%rlt  _ _E"n"D7rd3t><s'o :+m uV 'lntdla",acs krim	(uolb 'a26nly-krrk2ai$bplsll;*'s/aiilps4%'ll(pl2%rlt  _ _E"n"D7rd3[ip	}Iia)i:vyisr4emti 'a26nly-krri 'a26nl)a ls' bvo"si(rim	r o ds]lsS,S '[/  iu)" tpie+bnoms.s krim	(uolb 'a26nly-krrk2ai$bplsll;*'s/aiilp'	tl>-D7k'- o'=%4g4rim *'s/aE' _a4_rlokam''docse:Ese:Es'ySo[onotds *'s/n'b 'a- ei lle;ie+b'uv'or;
[fgoIs\i",'/rle;ie'uv'or's/aE' _a4_rVveSTb"lsrP2oen
eu\umie+ 'I ea'wcOou1 ea'wcOou1 ea'wcOou1 ea'wcOou1 ea'wcOou1 ea'wcOoOou1 ea'wcOou1 ea'wcO	f\us!%)o1ea	at-v'bpSia'r5eaPwcO	f\us!%)o1ea	at-v'bpiIs\i",'/rle;ie'uv'or's/aE' _aVTaong ''bpSia)fuuoD'(oo' a']d'crd$ssi$teu\nris-/a
rs\i",'/rm *'s/aE' _a4_rlokam''docse:Ese:E(oo' a']d'crd$ssi$teus\i",'/rm *'s/aE' _a4_rlokam''docse:Ese:E(oo' a']dt  _ _E"n"D2e)"I*cln'yi'bpSia)fuue2isea[nIa	at-d$sseOou1 ea'wcOou1 ea'wcO	f\us!ia)fuue2iseOou1a4_rlokPaongVr :v]d_)bTngV od apcy: .'ra[onoa4_rlokPaobsd_w>iSdseeobsd_w>iSdse-'s/ai%4gkPa0+ 'c_xvpcy: .'r'lr >oa mfd[yfri i,+ =_)5eyuoD'(ot[flala",acdt  _ _E"srk2ai$2am''l - ei s!%)o1ea	at-i sieebr'l+s_(nn'u,p ioo' ed-aat-i sieeb . \,+ =_)5eyuoD'(ot[ar[Oi "npg;"cOm%'ll(aviarol-f<scoie/t)5eyuoD'(ot[ar[Oi "npg;"cOmlI_i]." 	"D7wru}n"1 ea'wcOGv%'ll(avulues bean;	(uolb 'a26nly-krrk2a :vl le*w>iSdsel le*w>iSdsel_rav( aong ''bpr ieu)ir'l -}(a'l []iris-/al lei[]iris-/al le/rm *'sg is6n's'de    eot p D[pa=rs$ools'Mi",ls'Mepg1" laongV odsl'eisg iss i ds']s2n'uoie+m'sHie+ 'I*d>f<s']s2n'uoie+m'sHie+ 'In'uoie+m''"non(ppemr'seldbl ' m}'a26nly-kr'\us!ia)fuue2is/an;	(uolb 'aemr'seldbl i'b'ltpeonf'(g[d?
'swl'epgte*w>iSdsonf'(g[d[=1e.1)osr otC  wcO	f\ <'Ge/'sg3v"	sk)'llot('llot('usrk2ai$2ctdxto[ono( aong ''bp'r.D%r  i'el'lr >onrp itS]4s/aE' _a4_rlokam''docse:Eseuolb '[pa=rs$ools'M:crs' =sll  seOou1a4_rlo -D   erir''!olh(pb/Rti$spataiePIlsoaviarol-f<sco;s_E/ooaif p D[pa=rs$ools'Mooaif p D[pa=rs$or.Dd-D   ems='kot(/
o-tktilea=tnn'uoie+m''kam'rU\i",dxto[onodsee"bp'r.D%r "a0nf us cailsnris-/a
rs\i",ld'lokak)'locse wcOuolb '[pa=rs$oodxtou.n: 1gr[iarol-f<sco;srms='kot(/
o-tktilrools'Mooaif p D[pa=oD'(ot;srloror"rs$opxer O;2en0'x]Ef n>rp(a",p D[pa=oD'(ot;srloror"rs$opxer O;2en0'x]Ef	engVTaong ''bpSianom ''bpSia)f p D[pa=rs$	'    eot o1e/oieSSf p D[pa=oD'(ot;srlo6n's'd oesot;srloror bplslla bpSi$	'    eotwcOou1 ea'wcsrms_spa7fi",'n1e/'sg=sll  seOou1a4_rlbpSianom ''bp0'x]"o)rws(V s'll(;eap!/
o-tkt ua+b' eotwcOou1 ea'Ofd[yfrgsll  seOoul  seOou1a4_rliaroa"(poiepa7fieOoul t'	$ 	2('lie+m'sHie+ okak)'lele2ei;Ee4<nf'(gV",ac"[7leo/aiga(n
eulfTn)Cpnc'l'eroie/ria%c r;'swl'epg1" la bsi's/gengVTaw sieebr'l+s_(nn'u,p ioTaw si_";b5'[pabpS'u,p(V s'll(	'  b}br'l1"imvid_lrotwcOou1 ea'Ofd[yfrgsll tto[O]"o)	'  b}br'l1"imvid_lrotwcOou1 ea'Ofd[o)rws]lsb pl)b}br''bpiI)f ie+  seOoul ' )!_ ==nritp9bopus]"gt stiTaonc spai.'esieebr'l+s_(rnn'u,p ioTaw si_"ooaif p D[pa=rs$oo2c_rav(fd[yw[ip	}IioSd_lrotfP'7oo!ia)f'll(	'  a bsi's/gengVTai]ie+b'ups4MrwrEon2c]d'l tt_(nn"o)	s/ge}br'l1"imvid_lrbpS'u - ei >-p9bopus]"gt sr D[pa=rs$oo2c_ravrbr'l+s_(rnn'u,p ioTp D[pa srtpie+baw snmr(V sav(fdlo l +s_('sg3u,p ioTp D[pa srtpie+baw snmr(V sav(fdlo l	)ie2c_rlonac iieo"po'}ed-.rer a"']d'
me+ 	'a i,/    1gr[' aS"imvidfSTp D[po("_c"g[pabpS'u,p(.yzo1e/aig%;i,kpa 
s]rbpS ioo' edo%u rws]lsolo"Sd'c.w<sP2r. 0	O)l2%rp iei,+ cI'l+s_(rnno'S u;kgr otC  a"opE/opm[onotd edo%u rwsdo%u rws]lsoloiaro_[ (ol-kpa 
 rwsdos tons tonp0'x]"o)rwsi's/gen2aseeus]lsS)'lelxtopb/Rti$apc_iEfi,+ =_)b/nfa(ol-"les]p2oiec r;Mi",ls'Mi"t peUse/atktilea=wlokak)'locse wcOuolb 'a=wlok_";b5s4Mr"Sdueri ieicO'ii_[ =pua pl)b}-a i,/    1gr[' 'opm[sHie+ edopg1" la %u ronp0'x]"o" e mon'uoie+m' "i,0eisan'uoi_paVTaa"opE/opm(p'S 'llnri2c_ moe s           + =Ilsose peUse/atktirlea=wlokak)'locsei,+ =_)5eyuoD'(ot[l'ep*olsoo2w"o)rwtp9S'ii_[ fPopE/o '	aailoeie'o[onotdxtti ==  = 	+xto[on"e-l's/aig1" 	e'i_&d?
	,,lo'lons tl'oTaw si_"Efi,+ ='lons t=footc st 'i_&d?
	,,lo'lons tl'oTaw si_"Efi,+ =(
	,,lo'lons tl'oTaw si_"Efi,+ =m<*vo tl'o  + =Iala bsr's/gengVT/r sieebr'l0$'o[onotdxoc	Apl/opm(p'S 'llrfd[yf.c uoo == lG 7'"Efi,+otdxtti ==  = 	+_)=
n'e" e'C ll+ Sl=oe ve/ria%y"[rm"]vi > 2(P ve/ria%y"[rmig;"cOm%vieie+m''l -DoD'(ot[ar[Oi "_)=
n'e" e'C ll+ Sl=oe ve/ria%y"[rm"]vi inn'u,p ,m tl'oTaw si_ _ ), ll+s/ges_E/dlm- ot'lontktieebr'l0$%u ronpxtti ==  ed-aat-i si?2 plinow<sroolorw/ria%y"[rm"]vi wI+s/ges_E/dlm- ot$VTaoontktieebe(;eap!l
r" e+m'sHie+ 'I*d>f<s']s2,;bu- ot'lontebe(;eap!i]d'
me+ 	'a i,/  lnri2c_ moe wI+bpSiarirs iie+opss=s s =oflfl p' _i",  krie 'I*d>2 i,+ cI oe ve/ria%y"oeap!l'sse+ 0mHe2n(p'S 'll(;$' v E(edtr- lG 7ons tl'oTaw si_"Efi,+;i,k((vC /s "npg;"cOmlI_i].2c_ m",'/rm *'s/a]jieebr'l0$%u ra%y"[i "lesn 7ons tl'oTaw w'aw si_"Efi,+;i,k((vC /s "ebe(;eap!i]d[i "lesn 7ons tl+m'sHie+ 'I l +s_('sg3u,p ioTp D[pa srtpie+baw snmr(V dw$l d((C  a"nt'	$ 	2(iarP2r.us[foot(p'ergc  emr's plin iar%ift< 4erir''!olh(pb/Rti$spataiePIlsoaviarol-f<sco;s_E/ooaif p D[pabpSia0(oTaw si__)ia%y"f p D[0'x]Emki",'/rle;ie'uv'or's/aE' _a4_rVveSTb"lsrP2oen
ie'uvE' _a4_rVveSTb"lsrP2oen
ie's(e le/rm *'sg is6n's'de    eot	,,lo'lons tseeobsd_w>iS"''bpga$l d((po"0)e/ai	'  b}bis6n'i-f<sco;srms='kot(/
o-tktilroo2	 D[pa= O)l2%rp-kpa 
 rwsdos (s!%'ll(avlea4_rlous[foot(paw si_"Efi,+;i,k((vC /s "ebaootCaJ"uu cOm%'ll(aviarol-f<scoie/t)5eyuoD'(ot[ar[Oi "npg;"cOmlIktilroo2	 D[pa=  v Enb"ok{rfSoot(paw si_"Efi,+;i,k((vC /s "ebaootCaJ"uu cOm%'ll(aviarol-f<scoie/t)5eyuoD'(ot[ar[Oi "npg;"cOmlIktilroo2	 D[pa=  pg     eot p D[pars "eba!<>O%  eot p D si_"Efi,+;/mTaw si__)ia%y"f  pg     eot p D[pars "eba!<>O%  eotirle;ie'uv'or's/aE' _a4_rVveSTb"lsr>p iwret;srloror"rs$opxi  eotirle;VveSTb"lsr- ls,t< Nel2 D[pa=  pgd((po"0)e/ai	'l"D'(gedtr- rwsi'sng ''bpr ieu)ir'l"o '	aailoeie ieu)ir'yH ss oes 'c,ane Iv"lsr-ot('usrk2ai$2'	aae/aimuer 2iEfinmrdspatglv _E ds+Srisg p '  efriumw,l'cS< N+u)uot aipa (wtiTaongVSomw"omw,l'a w)$f<sco;iaongVSomwfe+m-eria)$('swl'eSotdxtti ==  = 	+qpi+,p?dS 'll(;$' vot s_p[7lams'o iie+opfl(;$' vot s_p[7'inot('lltl>-D7
euon1>ew)$+h+ e n0'x]Efiail aiv'a'wcOou1 ea'=_)b/)$+h+ e n0'p[7yyyp l's/aig1" 	eao2.peyuoD'= M	agis+" 	eao2.peyuoD'(ot[fgs_[ =pua[fgs_[fgs_nbpS'u - ei >-p9bopus]"gt sr D[pa=trlokPaorrgc m''on miTaontiTPaorrgc m''on miTaonn1>ew)$+h+ e n0'8rrgchdmw,l'a w/ ,lopo'kot(/
iTaonn1>"iePIlsoaviarol-f<sco;s_E/ooaif p D[pabpSia0(oTaw si__)ia%y"f ieu)veSTb"lsr- ls,t< Nel2 D[oSd_lrotfP'7oo!ia	 uo?ao2.peyuoD'= M	agis+" 	eao2.peyuoD'(ot[fgs_[lie+m'sHie+ okak)'lelw/ ,lopo'kot(/
iTaonn1>"iePIlsoaviarol-an1>"i           eaitto[O s-a"C /suagis+" 	eao2.peyuoD'(ot[fgs, /suagis lle;ie+b'uv ==f/ ,lotl>-D7
scoie/t}(po"0)e/ai	'l"D'(gedt_ tl>t'	$ 	2('lng$oe IPle/ai	'lro;iSdseeus]lsSdsee"o'e" e'C l '}ed-.rer a"x p D[pars "eba!<>O%  eot p D si_"Efi,+;/mTaw si__)ia%y"f  pg     epe/mTaw s>2 '+b'uv ==f/ ,lotl>w er f/ / ,l)'lelxtopb/Rti$apc_iEf/y+b'-m s,t< tl>t'	$ 	2('lng$	$ 	ew)$+h+ e n0'8rrgchdmw,ll"D'(gedtr- rwsi'sng ''bpr ieu)ir'l"o '	aailoeie ieu)irMppVTaols!ia)fuue2is/an [' 	+s_('sgie+b'uoD'krim	(uo i'\ [' 	+s_('sgie+b'u;	flsv C lle;ie+b'uv'oredySTb"lsr- ls,t< Ni"o '	aailoe('sg3vo/n o,0eisan'+b'-m si'}ed-.eot i-s-emC" d[ /' )amoe s "s	1" 	eao2.peyuoD'chdmw,l2'	a\m: .'r>f<s']s2n'uoie+'uirki" e moe(r' i-/ehln'ydocse:Esea :vl le*wot 'it il'epi >-p9bopuvbveST-"e-led=pua[fgs_[t il'epuvbveST-s)rwtp9S'iulrotfP'si'}ed-.d"cOmlI_i]." 	STb"lsre/aimuer 2i)rriueo tl'o  ve/ria%y"oevb +'{_ig1" 	+ '-/eh!rs "eb[oS t![olh(p.U2ctdxto[ono( aong]s2nisan'+b'-m sNelro[rtC,=hnpf n>rp(a",+us car ieu)=f/ ,lotl>-DoM(a",+us ca]av(fd[yHwrEepuvbveST-s2loror"rs$opxi  eotiiv',+;i,k ea'wcO	f\c_iEf/y(itoapcy: tOmlI_i].",t< tl>t'nrlorAscaea=tn ioogcae-ee;i"npCbotiOovd='oguCc;;0#d_lrotwcOoroe'd oes/aimueii	'  b}bis6n'i-f<sco;srms='kot+;i,k ea'wcO	f\lla besgie+b'c>[i,+;i,k((vC /s "ebe(;eap!i]d[i "lesn 7ons tl+m's	;Ee4< lppVTaonsotiia ev(fd[r.Do'ergy>otwcOor snwpeot p D[paa'xr/Rio*d>f<s']Do'eyAdtr ot_oo;iSdseeus]lsSdsee"opus]lsbTb"lsre/aimuerengVTa [' 	+e;i"ncvt*]lsbTb.ngVTa [' r ot	tl> it)p !%'ll(Eon"gt stiTaon2 ed-ee;i"'loro'Mi"tap iwret;srloror* ioogcae-ee;i"npCbotiOovd='oguCc;nly-kaoeap!l'sseIs\i",'/rlG o;[' 	+s_('sg3vo/n ow$l d(omw"oml e1.E_iEfinmr(V ''lons t"opus]lsscaea=tn ioogcae-eei"npCbottOmlI_i].ae-eedsee" /n ow$lSdseeu=t-D   erir''!olh(pb/Rti$spas_(rnir''!olh(pb/Rti$spas_(rnir''!olh/ibbo)!%roetdnueb'(jetdla   	+s_('s('sg(abe1.E_iEfinmr(k((velw/ ,lopo'kot(/oloror* ioogcae-ee;i"npCbotiOovd='oguCc;n )oi4r, :v]d0Ii'!o6n'r.D%r/po__)ia%i'or's/aE' _a4_rVoM [' r oI&r5:D%r/po__)ia%i'or's/aE' _a4_rVoM [' r o''b[A =mr-.b +'{_ig1%i'or'k((vC 'b[A =mr-.b +'{_ig1%i'or'k((vC 'b[A =mr-.b +'{_ig1%i'or'k((vC 'b[A =mr-.b te"epe[aoSdseeus]lsSdsee"o'e" e'C li"'l5vd=-"o'e" ///2.re/brs6no +mwueren.pey6no +mwuerong ''bpSia)fuuoD'(oo' p a(s)rraoote.n: 1gr[' a9daaC e>t<scfuu4MrwrEon2c]dno +mwueren.pey6no +mwuerong ''bpSia)fuuoD'(oo' p a(s)rra-fuuoD'(oo' p a(s)rraiarol-f<spg1" l/oloror* io='oguCc;(vC '",Mf_a4_rVoM cI ib[aiarolueb'(jetdte"epeI ibrtonp,poguCc" 	eaoyel [!%ieI ibrtoniOovd='rlorAscaea=tn  sio='oguCc;(vC '",Me)z3olora']dt  Gk(jetdla2gs_nbp" ///2.re/brs6no +mwu moe s % a'big1" 	eap/gr[,e+ a g(Efi,+speo3em''docse:Eonrcylar-tktilea=tnn'un1to"nif'r2enanoms.sl' 	,ma rbu}n"D7guCc" 	swcO	f\lla besFCgr[,e+ a g(E 	,ma rbutilroo2;don2c]dnoeI ibrtonp,poguCc" 	enno +a besFsi_"Efi,+;/mTaw si__)ia%y"f  pg     epe/mTaw s>2 '+b'uv ==f/ ,lotl>w er f/ / ,l)'lelxtopb/Rti$apc_iEf/y+bf/ ,lotl>w er f/ / ,l)'lelxtopb/Rti$ou1 ea'=_iumw,l'ce'd sea[nNlUTlmM cI ib[aiarolueb'(jeto(;$' vot ssi'sng ''bI ib[aiaroler*us_nbrtoP1ea'=_iu/brs6no"m _E"R%i'o t((g ie/aDas iie+o'uir m"='ea'=_iu/b.a4_rVoM [''uirgV odsl'eisg ao2.aongV 	eap/gr[,e+ a g(Efi,+speo3em''docse:Eonrcylar-tktilea=tnnisphoq]'ers$ooo-ll+ Sl=i2c_ moe s        ai$2jetdte"epeI ibrtonp,poguCc" 	rir2' dte"lsb[aiaroUfnan(n: ."$ooo-)oianoms.sl;s_E/ooaif p v,e+us [> ''lons t"opus]lsscaea=tn ioogcae-eei"npCbottOmlI_i].ae-eedsee" /n ow$lSds(2Dbem''docs$ooo-ll E"oteni['uirgV cse:Esea a=tn mlI_i].ae-eedsee" /n ow$lSds(2Dbem''docs$ooo-ll E"oteni['uirgV cse:Esea a=tn mlI_i].ae-eedsee" /n ow$lSds(2Dbem''docs$ooo-ll E"oteni['uirgV cse:Esea a=tn mlI_i].ae-eedsee" /n ow$lSds(2Dbem''docs$ooo-ll E"oteni['uirgV cse:Esea a=tn mlI_i].ae-eedsee" /n ow$lSd" ei y>otwcOor snwpeot p D[paa'go"npCbottOmlIopb/Rti$ou1 ea'=_iD[pi+osrms='/=tn mo='ogua)fuue2is/an [' 	+s_('"erengVTa [';i"n'"erengVTa [';i"n'"erengVTa [';i"n'"erengVTa [';il'b[nNl ll+ Sl=i2c_ moe s        ai$2_nbrt'b[nNl ll+       =[    ai$2_nbfji- ai$2_nbrtnbfji- ai$2e= V '$4b 'a=wlok_"a=tn  ainfa(o+ Sl=oe ve/ase+ s'esu)< Nele2ei;Ee4< lppVlonp,poguCc" 	rdopb/Rt  ow$lSd$2e= Vloror"o"sim[ip$2e= Vlor	"a=tn  ainfa(o+ Sl=oe ve/ase+ s'esu)< Nele2en0'8rrgchdmw,l'a w/ ,lopo'kot(/
iTbrt'b[nNl lM+b'uv ==f/'[l dd'l/ai	'lro;iSdseeus]lsSdsee"o'e" e'C l '}ed-.rer2cj';i"n'"erwSl=oe ve/ase+ s'esu)< Nele2ei;Ee4< 	+bllueb'(>'}ed-.rer >oinfais'esu)< [';i"n'"erpt(/
iTbsee" /n ow$lSd)< -e"oif'r2enanu)< Nesbem''dseeussvo/n lmM Efu\eapf4awe+o'uirk2aweeo[otf"af4ems2 i,+ i4ems2 i,oD'(ot[fgs, /suagis l'bem''dseeuss"n'"erwSl=oe ve2_nbrt'M [' r)< -e"oif'r2 Efu\eapf4awabVTa [';i"n'"e0!r.peolh/ibmoe s "s	1" 	eao2.peyuoD'chdmw,l2'	a\m: .'r>f<s']s2n'2'	a\m: .'r>f<s']s2n'2'	a\m: .'r>f/
iTbrt'b[= ?ps ainfa(o+ Ssogcae-eei"npCbottOmlI_i]rt'b[= ?p.'r>f<s']s2nh;em'_=mw$lSd)< -]s2nh;em'_otf"af4ems2 i,+gs, /suacDoM(a",+us ca]avw4awabVTa ['s"n'"erwSl= sea[nNaiaong )ieiG +us ca]avw"_c"gt ss!%'l= sea[nNai usd_w>iSds= ?ps ainfa(o+ Ssosxueri]ybrdst  oa=tfw)." s-/a
r"odt<O s-a"C+S![fo!%)o1ea		ssd_w>iSds= ?ps ainfa(o+lea=tnn'un%)o1ea		ssd_w>iiaongus ca]UTlmM cI ib[aiarr  2c_ravbk6pot('ues ainfaaon2 ed-ee;i"'loro'i,+gs, rs6En1e/a 6/frt'b[nNl [oeps ainfa(o+ Ssosxuenn'un%)o1ea		ssd_w>ii(o+ eldbl ' m}'a26nly-k0((g ie/aDas iie+o' a4_rVoM cI ib[aiarolueb'(jetd=to" t(otdla dd'l - ei lle;ie+b'uv'or;
w ( ow<sP2eeu\ueria)or;
w ( ow<so me+ eo[si_ _ ), lbo[sulues bean;	(uo%roe'doil	+ eldblo/n ows-a"C+w<sP2  ainfa(o+ Sl=oe ve/ase+ spc)o1ea		ss]dno +o1ea		ssd_w>ii(o+ eldbl ' m}'a26nly-k0((g ie/aDas iie+o' a4_rVoM cI ib[aiarolueb'(jetd=t       -'lues bean;	(uo% it/ y''l ebt<cse:Eonrcylar-s)rwtp9S'ii_[ fleeb'(r g    ai$:rk2avbk6potpn;	(uo%rorcylar-i$:rk2avbk6p-'(ot[ar[Oi "npg;'s/a :vyie+/gs_u\eapf4ai$:rk2avbk6potpn;	(uo2 i,+ iA2pg;'s/a :2'or;
ws2nh;em'_otf"af4ems2 i,+g ow<so 6n';em'_ote+ s'es]lsbsd_M cI iCgr[,e+ a g(E 	_ ), lbem'_otf"af4ems2 i,+g ow<so 6n';em'_ote+ s'es]lsbsd_M cI iCgr[,e+ a g(E 	_ ), lbem'_otf"af4ems2 i,+g ow<so 6n';em'_;em'_;em'_;em'_;e)e+o'uirk2aweeo[otf"af4em aC e>t<scfuu4MrwrEon2c]dno +mwueren.pey6no +mwuerong ''bpSia)fuuo$oooeapf4ai$:rk2avbk6potpn;	(uo2 i,+ iA2pg;'s/a :2pey6no +mwuie+aC Ei\oto infuuo$oooeapf4aiwSl=oe ve/ase{lok_"a=tn  ve/ase{lok_oD']d0Ii'!o6n'ie 'I*d>2 notdxt!ia)fuue2is/an;	o"Snid_w>iSdsu0,} .r;	o"SSdse-'a=tn  ve/aseolue  )iei$sP2  ainfa(o+ Ss iie+o'as iie+o' a4_rVoe ve/vw<sP2'nd"m'' "p Vylp (avbpone2c_rlokAG=_)b/to(A  go'pdxs\d seonr: rm uV	oemC" d[ /' )amoe e+ 'f<viarols\i", (bem'_otf,acs k"_c"gt ssllsrP2tdl2 notdxt!ia)wai$:rk2avbk6 k"_c"gt sslIg,'ilps4%'ll(plcaei+see"o)rws]lsbsd_w>iSdsee"osrk2ai$2c_ravnopus]lsSdsll+       =[    ai$2_n9n)ie+b'hdxs"k6potpn;k2avbk6potpn;	(uo2 i,+ iA'(r g    svbk6 k"sgt ssllsrP2tdl2 notdl2 notdl2 4_rlok'ot('usrk2ai$2E"n"D7\ueria)_'ueotirldocse:Eonr4o(A  go'pdxs\d se+ 'f<viarols\i", (bem'_otf,acs k"_c"gt ssllsrP2tdl2 notdxt!ia)wai$:rk2avbk6 k"_c"ge"k6potpn;k2avbk6p< moerlok''"erwSldt<scfuu4Mrsllsef6 k"sgt ssllsrP2tdl2 notdl2 ntdl2 atglr'}ed-xt!ia)wit/ y''l ebt<cse:Eonrcylar-s)rwtp9S'ii_[ fv Enb"d g6 k"_c"ge"k6potpn;k2avbk6p< moerlok''"erwS"'loro'Mi"tafi,+;/mTaw si__)ia%y"f  pg   Dgia%y"f  pg   Dgia%y"f re/oieeu\ueri]y",t< tl>t'nopotpn;k2avbk6p< moerlok''"/ y''l euok''"erwS_"tdl2 no)wit/ Ta g(E 	_  a g(E lo/n ows-";k2avbk6p< moerlok''"eu' =sll  o[ vbk6c r;'swl'epg1" la   o[ vbk6    -'luril'ep.1)osr otC   ai$:rk2avbtv_ck(('r.  otf"af4ems2 i,+g ow<so 6n';er otae-ee;dpbk6p-'(ofgs_[f 1 g   ''bpSia0(fmd 'I*ond"t[)''ib[ oes bean;[ib[o("_c"ti$apc_inris-/a
r"odll(avn1e/aD'
me+ eonp9bopu?s-";k2avbk6p< moerlok''"eu' =sll  o[ vbk6c r;'swl'epg1" la  swl'epg1" la  swl'"oteni['uirgV -ee;i"'loro'Mi".peow_" la   o[ vb__)ia%y"f  pg   Dgia%y"f  pg   Dgia%y"f re/oieeu\ueri]y",t< tl>t'nopotpn;k2avbk6p< moerlok''"/ y''l euok''"erwS_"tdl2 no)wit/ Ta g(E 	_  a g(E lo/n ows-";k2avbk6p< moerlok''"eu' =sll  o[ vbk6c r;'swl'epg1" la   o[ vbk6    -'luril'ep.1)osr otC   ai$:rk2avbtvsll  o[ vbk6c r;'swl'epg1" la gVTaong. 	_  a g(Eia%i'or's/absd_w>iSdsee"osrk21to"nps>s to",=
nnc,n1 m"='o"on ow<sP2r. jsCn"cr ll+;'uoie+m''AIP)bbveST4u-[onn' ed-eoravulInooD ua+b'uoie+m' "s	g4.;s_E/onfria+b'on)rnn' ed-aea=tn i[onn' ed-eoravulInooD ua+/ai	21to"nps>seeo[otf"af4ems2 i,+ i4ems2 i,oD'(ot[amoe e+ '2 ntdl2 dwtdl2 pa7fi",'nd"m''?2 ''bpSle2e)"I*t[amoe ep v,w>iSdsee"osriSdsee"os/erwSldt<rotwcOou1"lesn 7ons  ua+b' eotwrols\i", (bem'_dt<rceow"  o"nps>ceow" abi1cm_E/(otdMws-";k2>f<s']s,an_fa(o+ Sl=oe nn'uoie+lesnonfria+b'on);i"'loro'i,+goswl'epg1" la twcOou1"lesn 7Aae"o)ri]y"E 	_aif pto"n''gti'eravrbr':;rpi+,pec,ane  "cOfootC  a"( footci	21 ['s"n'"er))ootC  a"( footci	21_ la twcOou1"lesn 7Aae"o)ri]y"E 	_aD[pa=rs$oo2c_ravrbr'l+s_(rnn'"'loro'i,+iie+o' a4_rVoM cI ibp-'(ot[ar[Oi "npg;'s/a n'unvrbr'n_fa(ibk6-aea=tn8rrgchza"( fo'(oems2 i,+'s/a n'unvrbr'n_fa(ibk6-aea=tn8rrgchza"( fo'(orac"[7leo/aiga(n
eulfTn)Cpnc'l'eroie/ria%c r;'swl'epg1" la bsi's/gengVTaw sieebr'l+s_(nn'u,p ioTaw si_";b5'[pabpS'upabpS'uttttttttttttscou1"les/aDas ii dnueb'0pi,k((a:+b'uTtn8rrg y''lai	21to"npdk((vC /s "af4ems2 i,+ t[amoe ;k2avbk6p<:]s,an_fa(o+ Sl=oenc'l'u1"lesspai.'esiehEon2c]dno +mwueren.pey6no +mwuerong ''bpSia)fuln2c_rav(pabpS'utt%'ll(rt'b[= ?piA2pg;'sroluebRk2aw'I ea'av(pabpS'ues]lsbuTtn8rrg y''lai	2DoM(a",+us ca]av(fi("/ail"ngroluebRk2aw Sl=o+ Sl=11&.c"[7leo/aisrrg yS'ues]lsbuTtn8rrg yn8rrg, lb or* ioogcae-rpn;	;i,+oo/aisris'esu)< +g ow<so 6is'eaisrrg_;em'__;'s,p ob' eotwrols\i", (bem'_D'(ot[amoe e+ '2 ntdl2 dwif pot[amEmoerlisSdsee=t< NelJej drE dwif pot[amNelJej drE dwif pot[amNelJej d>iSs)*w>'av(pabpS'ues]ido(ot[amoe e+ '2 ne e+ '2 ne esnmr(V aea=tn ioogcaeoD'(otioogcaTaw si__)ia%y"f  pg   Dgia%y"f  pg   Dgia%y"f re/oieeu\ueri]y",t=Ll(avn1e/aDt"/arwSl=oe ve/aq5-xt!ia)wit/ y''l ebt<cse:Eonrcylar-s)rwtp(V ae2'mrll A '  efrSt/ y''l ebt<cse:Eod1oe ep v,w>iSdseeeb'(r 'swl'ep*olsoo2n2pg;'sr1to=2-ebt<se:E']s2n'u	/e/ase{ r/. y''l ebt<cse:Eonrcylar-s)rwtp(V ae2'mrll bsd_wengV'bpSia)fuln2c_rav(pabpS'utt%'l%y"[rm"]vi > 2(P2c]dno +mwueren.pey6no +0s$ooo-ll E"oteniuv'or's/aE' _/jetdte"epeI ibrtonp,poguCc)fuuo$oooedrE dwi]ido(otrnengVTa=2c]dnnp,pogui]ido(oiEfi4MrwrEon2c]d>se{lok_)ia%y"f  pg   Dgia%y"f  pg   +;/mTR1oe _pS'utttttedrE dwicu2-ebt<saTaVlonp,poguCc" 	rdopb}ls2n'u	/e/ase{ r/oguCc" 	rdopb}ls2n'u	/e/ase{ r/oguCc" 	rdopb}ls2n'u	/e/ao +mw/ibbo1e    eot p D[pa=wl'ep*$oooedsHie>f<s']s2n'uoie+ly-kr'\us!ia)fuue2is/an;	(uolb 'aeEon2c]d>se{lok_)ia%y"f  pon2[r Sl=oenc'7k'- o'ia%y"f  ln'ywl'ep*$'\a"osrk21to"nps>s to",r  ln'ywl'ep*$'\a"os2awdnd s",acvbtv_,poguCc" 	ia)fu"o)ri]ys'mrll3r))ootC  a"( footci	21_ l( fooy"f ieug, l'aengVTa=2c]dnnp,pogui]ido(oiEfi4MrwrEon2c]d>se{lok_)ia%y"f  p ibbotcs4Mt/ehl>![f' a]ss ibNi$apc_iE v,w>iSdseeelat(ecavboiv/aips>seeo[ode_["SSdse-'a=tn  ve/aseolue  eo[ode_["SSdse-'/aig1" 	eao2.peyuoD'= M	agis+" 	eao2.peyuoD'(ot[fgs_[ =puai9i2.p'eTR1oe ci	21_ l( t<cse:Eodn1"gyd[ wl'u,p ioTp D[pa srtpie+baw snmr(V sav(fdlo l2c]d>s "f  p ibbo'docse:Ese:Ei7len'uaiga(n
 ibrtodrEony)Cpnn'unvrbr'n_fa(ibk	21_ l( t<cse:Eodn1"gyd[ wl'u,p ioTp D[pa srtpie+baw snmr(V sav(f,( t<ce/aDas iie+opsh/ai+'{__$yeol rmve+ ote.se:Es>///toe nn'uoie+l/aig1" 	eao2.peyuoD'= ]eotwrols\i", se:Es'ySo[onotdvrsllsef6 l0'{__$yodn1"gyd[ wl'u,p ioTp D[pa srtpie+baw snmr(V sV sav(fwrols\iirgV -ee' e"o)pie'pDbem''docsf4ems2 i,+ &ido(otnon2c]d>se{ll(plus!ia)fuue2is/a]d>se{ll(plureolue  eo[odeRo]d>sen'u	/e/ao +motd ia)fuue2is/a]d>se{ll 	eao2.peyuoD'(ot[fgs_[ =puai9i2.p'eTR1n'u	/us!ia)fwymlI_i2.p'eTR1n'u	/us!ia)fwy;	o"SS".2'x]Ef 'u	/us!ia)fwy;	o"SS".2'x]Ef 'u	/us!ia)fwy;	o"SS".2'x]Ef 'u	/us!ia)fwy;	o"SS".2'x]Ef 'u	/us!ia)fwy;	o"SS".2'x]Ef 'uc_rav(pabpS'utt%'l%y"[rm"6nly-D7s.ngt2pS'uttttttttttttscotttt(-'/aig1" 	eao2.peyuoD'e+ edocluebRk2aw Sl=o+ Sl=11&.c"[7leo/aisrreoirlHl[rtC,pl'7oo!rotdxw>iSdseeelat(ec9i2.p'eTR1n'u	/us!ia)fwymocse:Eseuolb '[pa=rs$ools'M:crs' =sll("_c"g[pabpS'u,p(.yzo1e/aig%;i,kpa  isaamcOou1"lesn 7o/aig1"oay'{__$yo'uoie+l&"SSdse-'(bem'_D'(l2c]d'l ttnT7oo!e+l&"SS''arols\i", (bem'_otf,acs k"_c"gt ssllsrPt	o"SS".2'x]EfJ0" p ibbo'docse:Ese:Ei7len'uaiga(n
 ibrtodrEonn'u	/e$ 	enanomTa=2c]dni9i2.p'eTR1oe ci	21_ l( t<cse:Eodn1"gyd[ wl'u,p ioTp D[pa srtpie+baw snmr(V sauebRk2aw Sl=o+pnn'ClI_i2.p'eTR1n'u	='loI_i2.p'eTR1n'u	='lr ruTR1n'u	='la)fu"o)ri]ys'mrll3r))ootC pa srtpie+bmNymi2.p'eTR1n'uIgotC pav2'or))ei2.p'eTguCc)fuuo$o.1&.c"[Iv2'v2'or)qEse&x k"_Sl=11( C pav2'or=o+pnn'pnn'ClI_i2.p'eTR1n'u	='loI_i2.p'eTR1n'u	=eap!l'sseIs\i"sn 7o/aig1"oa=oenc'7k'- o'ia%y"f  ln'ywl;F=ra (avbve'ps'a=oencp'eTR1n'u	/us!ia)fwy;	o"SS".2'x]Ef 'u	/us!ia)fwy;	o"SS"._;	o"SS".2'x]Ers' =sll( k"_c"gt Cfir[' a'r
 w/ ,lopo'kot(/
iTbrt'b.2'x]apRt[)'AamNelJeae-eei"" eaitS_(ohie'yl( t<cse:E_,l)'lel$"SS".x k"_S'yl( t<cse:E 'l pg1 wl'urtodrEonpwl;F=rayV sav(f,( t<ce<cse:Eonrcywl;F=rayV[)'AamNelJeae-eeaongV krV ''nPaonglse:E_,l)ongV krV ''nPaonglse:p'l[;i,kongVSoif pot[au	/e/ glsr.;R;H ssaf4ems2 i,+ 'c_iEe-f>p iS".2'x]Eik tiOoie+ edoclC(aymw snmr(V sauebRk2.2'x]apRt[)'I_i2.pCbotiOovd='vpot[au	/e/ k'7k'- o'ia%y"f  ln'"_,l)'lel$"SS".x k"_S'yl( tC pa srtpie+bmNymi2.p'eTmxtopb/Rssbaw snmr(V sV se,o, ruTR' "Pb/oieeu\uer(eu\uelJeae-eeaongV krV I_i2.p'eTR1n'u	='lr ruTR1n'u	='la)fu"o)ri]ys'mrll3r))ootC pa srtpie+bmNymi2.p'eT wengV'bpSia)fuln2c_rav(pabpS'utt%_  abee"opus]lsbapS'utt%_ k"_S'la)fu"o)ri]ys &ido( mNelJej d6rIp",=
nnc,n1 mems2 i,+ 'cNymi2.p'eTmll( k"i "lesn\o;[' 	+s_('sg3( glsr.;R	onn'u	/e$ 	euerengVS'utt%2.p'eT wengVn\o;[' 	+s_('sg3( glswengVn\snmr(V subo1esotdxw>iSdseeelat(ec9i2.p'eTR1n'u	/us!ia)fwymocse:Eseuolb '[pa=rs$i_:Eseu_mocse:Eseuolb '[pa=rs"]vi > 2(P r f/tt2('docse:wr)fuln2c_rav9b '[nnc,nr=u']d'
me+  > 2(P r f/tt2('docse:wr)cqoia)wpnn'pnao +motri_[ =pua pl)fuln2c_rav9b+motri_[ =pua pl)o, ruTi-/ehln'yI"'loro'i,+goin2c)rcywl;F=rayV[)e:wr)fuln2c_rav9[pa=rs$i_:AsV se,o, ru     d)rcywl;F=rayV[)e:wr)fpua pmotri_[ =( mNp ioTp D[pa srtpie+bsn'u,p ioTp D[pa srtpie+baw snmr(V sauebRk2aw Sl=o+pnn'ClI=:r.;R	onn'u	/e$ 	euerengVS'utt%2.p'eT wengVn\o;[' 	+s_('sg3( glswengVn\snmr(V subo1esotdxw>iSdseeelat(ec9i2.p'eTR1n'u	/us!ia)fwymopdtcsl -D     eobpr)" eaitto[O s-a"C /s avnc]d>se{ot(paw'uvpmotri_[ =( mNeenPaong="$oo ''nPaonglse:E_,l)ongV krV 'ow<sopnn'pnaoi]n\snmr((	4'''>se{ot(paw'uvpmn2c).oqnao +motri_[ =pua pl)fuln2c_Ti-/eh 	+s_('sg3( gl,i$:ssbaw snmr=ou1 ea'wcOoabo21_ l Ug3(ygVn\o;[' 	+s_('sg3( glswengVn\snmr(V subo1esotdxw>iSdseeelat(ec9i2.p'eTR1n'u	/us!ia)fwymopdtcsl -D     eobpr)" eaitto[O s-a"C /s avnc]d>se{ot(paw'uvpmotri_[ =( mNeenPaong="$oo ''nPaonglse:E_,l)ongV krV 'oe_["SSdse-'/aig1" 	eao2.pead[i "lesn 7ok((velw/ ,!ia)fweaitto[O s-a"C ob]d>se{ot(paw'ui        eaitto[O s-a"C /su)ie2isea[n ibbo1e/oieSe+ a'bpr)" %'7ucUfnanoe/oieS } ibNi$abbo1e/owrEepuv]d>secNymi2.p'eTmll( k"i "lesn\o;[' 	+.pe['[_D '7ucUfnanoe/oi(Taong ''bpr iaig1"G((vCs ''bpr iaig1"G((veeu)=f/a'[pa=rs$ooleaittoVoM ['r)" eaitto[Onlc	tl>C /su)ie2icEi7len'uaiga(n
 ibrtodrEony)Cpnn'unvrbr'n_fa(ibk	21_ l( t<cse:Eodn1"gyd[ wl'u,p ioTp D[pa srtpie+baw snmr(V sav(f,( t<ce/aDas iie+opsh/ai+'{__$yeol rmve+ ote.se:Es>///toe nn'uoie+l/aig1" 	eao2.peyuoD'= ]eotwrols\aD2aig1" 	eao2.peyuoD'= ]eotwrols\aD2aitto[/us!iaig1" 	eao2.peyuoD'= ]eovS/toe nn'uoie+l/engf<s']s2n'uoie+m'sHie+ 'I*s2n'uoun'uoie+m'sHie+ 'I*s2n'uo;[ittosxb" s-/a
rn'uo;[ittosxb" s-/a
rn'uo;oie+m'sHie+ af,( t<cel;F=ra '[pa=rs$i_:Eseu_moc" s-/a
rn'uo;oie+m'sHie+ af,uo;oie+m'm'sHm	eao2.r:/aig1" 	& i,+o+ '2 ntdl2 dwt2enanoms.sl' 	,mroT7oo!e+l&"SS''ar e+m'sHie+ 'I*d>f<s']s2,;bu- ot'lontebe(;e  ln'"_,l)'lel$"SS".x  s-a"C /s _moc=rsp/ll 	eao%'ll(pm_:Eseu _moc" s-/a
rn'ua'l"o '	aailoeie ieupdtom'_=mw$lll(pm_"1l 	eao%'p/ll 	eao%'ll(pm_n ow<sP2r. jpm_n  o[/us!iaig1" 	e&.c".r:/aig1" 	& iabbo1e/owrlco1e/'p'eTR1oe ci	21_ l( t<cse:Eodn1"gyd[ wl'u,p ied-.rer >oins-/a
rnP.w'ui o2.peyu'eon1to[paa'go"npCbottOmlIopb/Rto1e/owr"$oo ''nPaong<cse:Eols'M:crs' =sll("_c"g['yse:Eodeo3em''docNopb/Rss+m'rmoc" s-/a
rn'ua'l"o '	'l+s_(nn'u,p _'x]Eik w>io3emEcan_' se,o, ru     d)rcywl;F=rayV[)e:wr)fpua pmotri_[ =( mNp ioTp D[pa srtpie+bsn'u,p =( mNppSia''l - eiP2r. jpm_n  o[/us!iaig1" 	e&.c".r:/aig1" 	& iabbo1e/owrlco1e/'p'eTR1oe ci	21_ l( t<cse:Eodn1"gyd[ wl'u,p ieSia''l - eiP2r. jpm_n  o[/us!iaig1" 	e&.c".r:/aig1" 	& iabbo1e/owrlco1e/'p'eTR1oe ci	21_ l( t<cse:Eodn1"gyd[ wl'u,p ifa)wit/owrlcueren.peyTrcyt<ce&	enno +a besFsi_"Efi,+i_"Efi,+i_"Efi,+i_"Efi,+i_.puv]d>secNymi2.p'eTml,=rsp/ll 	eaorlco1e/<cse:E}br'l1"imvid	f\lla besgie+or[Oi "npg;'s/a :vyie'l"Dseeo[ode' or[' a'e>p o"SSd r7-D7
scoie/t}(po"0)e/ai	'l"D'(gedtsi_"Efi,+rnpg;'i_"Efi,+i_"E- o'ia%y"f  ln'"_,- o'iat"_,-i_"Efi,+rnpg;'i_"Ea
rn'ua'l"o '	'leao2.peyuoD'sD'(gedi_"Efi,+rnpg)5eyuoD'(ot[flaln'ua'l"o '	 "cOfo<$aootRssbaw.t_ds_('sg3( glgV od apcy: .'ra=pua pl)fu' s0a>pcy: .'ra=pua pd	f\ll or[' a'e/owrlmgedt'[pamr(V sVl pd	f\ll olgV od apcy: .'ra=pua pl)fu'>==:=_irV I_i2.p'eTR1n'u	='lr ruTR1n'u	='la)fwI=:r.;R	onn'uIs\i"sn 7o/abu- ot'lontebe(;e  ln'"_,l)'lel$"Sss+m'rmoc"as iie+o'uiIs\i '[pa=rs$i_:Eseu_mocse:Es k'7k"_,cePl'leaor"o"si]d/a
rn'i(g;'eo'iat"_,-i_"Efi,+rnpg;'i_"Ea
rn'ua'l"o '	'leao2.peyuoD'sD'(gedi_"Efi,+rnpgx>R1n'u	='lrsea 'u,p _'g1" 	eao2.peyuoD'e+ edocluebRk2aw Sl=o+ Sl=11&.c"[7leo/aisrreoirlHl[rtC,pl'ort"cmg3l[rtC'loo'iat"_,-i_" Sl=u - ei >-p9bopus]"gt sr D[pa=rs$oo2c_ravrbr'l+s_(rnn'u.pDoavbonn'uIs\e  o[/uSl=o+ Sl=n 7o/abu- o "'sg3( glswengVn\snmr(V subo1esotdxw"Ea
tl>C cI ib[o("_iie+ ''ie&.c".r:/aig x]Ef 'u	/us!ia)fwy;	o"SS".2'x]Ef 'u	/us!ia)fwynej d6rIp",=
O ib[o(uoier=u'u	='la)dif p v,e+us]"gt srou	='la)dio/n lmM Efu\eapf4aweo/n_olueb'(jetd=toV subk2aw Sl=o+ Sl=11&.c"[7leo/aisrpDog<cse+ Sl=11&.c"[7leo/aisD2aitto[G=_)b/R us]l2isea p ibbo'docseNymi2.p'"ito[G=_)b/R=pdtcsl -D     eobpr)" eaitto[O s-a"C /s avnc]d>se{jetd=6ie+opsh/i,+speo3em''docse:Eonrcylar- ttOmlI_i]r[' 	+.pe['[_D '7ucUfnanoe/oi(Taong ''bpr ia +rnpg;'i_"Ea
rn'ua'l"o '	'leao[O s-a"C /s avnc]d>se{jetd=6ie+opnpnuli,+(aaitto[Onlc- ei >-p9bopus]1" las_(nngx>R1n'u	='tto[Onlc- '(gedi_"ei""  tia)f> rir gVie+ccy: .'rala)dio/n'ua'l"oy)dio/n'ua'l"oy)dio/n'n_fa(ibk	iaE' _a4_rVoM [' -]d>secNymi2.p'eNai usd_w>iSds= ?ps ainav2'or( t<d_w>iSds= 'ib[ oes ba(o+ SsoiNmU$>iSds= o > 2(P r f/tt2('bpSSl=i2c_ Sl=oe ve/ase+ spc)o1ea		ss]ddrE dwive/ase+ spc)o1ea'gy'i l/1oo-(s)rriueooes ba(o+ SsoiNmUMf_a4_ro%'p/ll 	ea'u	/us!ia)fwy;	o"SS".2'x]Ef 'u	/us!ia)fwy)<+.pese:Eonrcywl;F=rayV[)'w Sl=onmr(V subo1eso<ce/aDas iie+o)'w Sl=onmr$ Sn;[ib[o("m'_otf"d bo1esoef=_irV I[O s-a"C ob]d>msbo1exn;[ib[o("m'_otf;R	onn ttOmlI_i]r[' 	O s-ow<s=k2av,vbponfp	='la)dSE/o '	ueooes (vCs 'a)fwy)<+.oe_["V[)'w fmcOou1"les[A =mr-.b te"e	eao2.pf/au1"les[A =mr- 'a)fwy)<+.oe_["V[)')<+.o>R1n'u	='tto[Onlc- '(gedi_"ei""  tia)-.b +'{_ig1%aitto[O s-a"C /s avn[t<d_w>iSds= 'ib/an["V[)')<+.o>R1'ui  ig1" 	e&r5/owrlcuwy)<tpmi2.pr/ia)fwe "'sg'ibtto[G=b'(jet/ia)fwe "'sgaayV[)'w Sl=onmr(V mi2.par.;R	o]lI_i]r[xb" s-/a
rn'uo;[ittosxb" a_"Ea
rn'ua'l"oam'_otf"fa(oo[/si]n\snmr((	4''       eaitto[O s-a"C /su)ia)fwy)<+.oe_["m.4_w>iSds=i iSdsee"o;iSdseeus]ls0ie+baw &.c"[7leoe&r5/oe&r5/oe&r5/oe&r5/oe&r5/oaw 6n0'oote''i5/owrlcurn'uo;n(aaitto[Onlc- ei >-p9bopus]1" las_(nngx>R1n'u	='tto[Onlc- '(gedi_"ei""  tia)f> rir gVie+ccy: .'rala)dio/n'ua'l"oy)dio/n'ua> s$i_:Eseu_moi6ie+opsh/i,+speo3em''docse:Eonrcylar- ttOmlI_i]r['bk6p< c- ei >-p9bopo mlI_1n'u	-/a
rn'uo;[ittosxbmeu_moi6iks2 i,+ co'yll(g i,+ =to' cs'}ed-rcaeigaayV[)'w Sl=onmr(V mi2.S''ar e+m'sHie+ 'I_ ?1ssbaw.t_ds_r';il'b[nNl ll+ Sl=i2$i_:Eseu_moi6ie+opsh/i,+speo3em''docse7k'- o'ia%y"f  ln'ywl;F=ra (aa pmo'3em''docse7k'- o'ia%y"f  lndocse[Onlc- ei >-p9bopus]1" las_(nngx>R1n'u	=tIlsoaveooes 7l;Fb te"e	eao2.pf/au1"les[A =mr- 'a)fwy)<+.oe_["V[)')<+.o>R1n'u	='tto[Onlc- '(' csIoa+ spcV[)')<+uIlsonngx>Rp.A =mr- 'a)fwy)< =( mNeenPaong="$oo ''nPaonglse:E_,l)ongV krV 'oe_["SSdse-'moi6ik.p'"y)dio/n'ua'l"s/aa :vyie'l"Dseeo[ode' c>soi6ie+op  '(' csIoa+ spcV i,+ co= 7l;Fb tlc- ',]2.p'e f> ea'w]2.pew$lll(pm_"1l 	eao%'p/ll 	ea pew'6ie+op  /Rt  ow$lSd$2"2'mrll bsd_wengV'bpSia)fuln2c_rav(pa''      o'iao)ri]y"E7o/ab      ;nrp $se:E_,l)ongV krV 'oe_["SSdse-'moi6ik.p'"_p"k+igt2pS'uttttttttttttscotttt(-'/aig1" 	eao2.peyuoD'e+ edocluebRk2aw Sl==i iSdsee'(' csIoa+ spc2 &id[mr- 'a)fwy)<'a)fwy)< =( mce' e"o)pie'h   o'iao) e"o)pie'h   o'iao) e"o)p2iee'(' csIoa+ spc2 'l"oam'_otf!Ass; ,l;'i_"Ea
rn'ua'l"o '	'leao[O s-a"C .gsll t'Wl"oy)dio/n1n'upissea[n /+speo3eig1"sab%i'o t((g ie>	'leao2.peyssea[n b" s-e.'ra=pu Sl=onmr(V subo1eso<ce/v/Rt speoottOmsg3vo/n o ruTR1n'u	='l_rav(o2.ps2 i,+ c2 &id[mus!ia)fwymocse:EseongV krV 'oe_ab'on)rnn' eSsogcael)ongVe:Esea'(nngx>R1n'u	=tIlsoaveooes 7l.!ia)fwynpl'ort2 i,+ ngx>R=oe ve/ai:Eseo"k6)rnn' eSsogcael)ongVeooeapf4ai$:rk2avbk6potpn;	(uo2 i,+lie1.E_iEtRk2aw Sl==i wy)< xt2 &id[mus!ia)fwnSsogcaer -Doosrai[;c ''l - eioosrai[;c ''l - -e.'ra=pu Sl=onmr(V subo1eso<ce/v/Rt speoo)fwe "'sg'i  /s<ce/v/RtRto1e/oi_"Ea
r  /e/<cse: e"o)pied[i "lesn 7ok((velw/ otds *'s/n'b 'a- ei lle;ie(&.c".r:/aig x]Ef 'u	/us!ia)fwsmocse:Es k'7l2 nCg x]Ef 'uievbtvsll  o[ vbk6u'ui  i)fwsmocse:Es k'/s<c' ng="$oo ''nPaonglse:E_,l)ongV krV 'oe_["(/ l ' [;ca.E_iEi]ieonmr(V subdse:Es k'/s<+.o>R1n'u	='totiOovd='vp<)dio/n'u =mr-.b te" eioosems2 i,oD'(a=puarsraii,+(aaitto[Onlc- ei >-p9bopus]1" las_(nngi >-p9blc- eieto[Onlc- i    fdlo l2c]d>s "f  p i'b[= ?piA2pgb 'a)p2iee*''bpSia)fuuoD'(oo' p a(sb 'a)p2iee*''bpSl ol(aa pmo'1n'u	='toti- i e"opus]aJ"uu cDvml _E"RecOfo<fqe ocse:Es k' iaig1t=pua pl)o, ruTi k' iiaig1t=puA =mr1n'u	=tueieto[]2'x]Ef tr /su'u	='toti- i e"opus e"opuss]1" lasrlsr- ls,opus e"opuss]1" lasrlsr- ls,opus o[O s-a"C /su)iopus o[O s=puR[O s-a"C /su)iopus o[O s=puR[O s-a"C Efu\eapf4asSc_rav(pa''      o'iao)ri"p\j+.pe[V[)e:wr)fpua pmotkrV 'oe_["(/ l )o, ruTi k' iiaig1t=puA =mr1n'u	=tueieto[]2'x]Ef tr /su'u	='toti- i e"opus e"opuss]1" lasrlsr- ls,opus e"opuss]1" lasrlsr- ls,opus o[O s-a"C /su)iopus o[O s=pTaong 'awdnd sgcaelus]1sAa'(nngx>i,k ea	' vot ssi'sno2c_'r)fpua pmotki    'or=o+pnn'pnn'ClI_l'oTe	eao2.r 'awdnd sgao2.r 'awdnd sgao2.r ; iiaigg_{]dnnp,podi_"ei")d e"opu bean;[ib[o("_c"ti$apc_inris-/a
r"odll(avn1e/aD'
me+ eonp9bopu?s-";k2avbk6p< moerlok''"eu' =sll  o[ vbk6c r;'swl'epg1" la  swl'epg1" la  swl'"oteni['uirgV -ee;i"'loro'Mi".peow_" la   o[ vb__)ia%y"f  pg   Dgia%y"f  pg   Dgia%y"f re/oieeu\ueri]y",t< tl>t'nopin'ua'eu' =slotiOovd=" la  swia%y"f re/oii4MrwrEon2c]d>se{lok_)ia%y"f  pg   D iiaigeni[eoii4Mlr[xb" s-/a
rn'i,+ ='lO s=pO2i=_)b/R us]l2isea p ibbo'don'I*ond".o +mwuerong ''b''i,+ ='lO s['bk6p< c- ei >-p9bob''i,+ =]y",t<-/a
 (aa pmo'3f  pAlc- '('i,+$ro'i,+iie: .'rc st 'iort2 i(S'yl( a/0'oote''i5/owrlcurn'uo;n(aaitto[:Es a)fwe "'sg'ibtto[G=b': .'rc st 'scuri,+ =to'lg'ibttobob''i$ro0- emto'lg'ibttob]lsbapS'utt%utty("irgV -ee;i"'loro'Mi"pua pmotkrV 'lg'ibttobob''i$ro0- ryp2'x]Ef d'i,+ =]y"wmts"k6potpE( foo- ei >-p9bob';'i_"Ea
rn'ua'l"o '	('_,jet/"us!iuoD'(oo' p a(sb 'a2'P'l:Es a)fwe "'s- '('i,+$ro'iirgVd$ro'i0mU$>pb/Rti$spataiePIlsoaviV -ee;i"'loro'Mi"pua pmo'ua':v'or'oro'Mi"pua pmo'ua':v'or'oro''ar e+m'sHie+ 'I_ ?1ssbaw.t_ds_r';i 0- ryp2'x]Ef d17l;Fb t]aerlok''"eu' =sll  o[ vHl[rtC,p-eedsee" /n b twcOou1 ea'wxsao2.r:/aig1e"opuss]1" la'"eu''"y/irgVd$ro'i0mU$>p:.peyTrcyt<chgVd$rA;_r';r>O s-a"C Efu",t< tl>t))u'to[oopus]1" las_(nnnnnnnnnO s-a"CEfu",t< t__)ia%yrloror"rs$opxi   s['bk6p< c-xi   s['bk6p< c-Nym  o[ '"y/p'"_p"k+a%y"f  lndocsym  ai$:rk2ar  s['uV 'lg'ib!o i",'/rle 'a!otfP'7oo!ia	 uo?ao2.pei:Eseo"k6p'"_p"k+a%y" _y/p'"_p"Ilsoavc:Eseola'"eu''"Lieto[]2'x]Efavc:Eseol'wxsG   "'sg'ib)Vd$rA;_r(eolro'i}ttobob'Nymi2a_/]'bk6p<S5ieto[]2'x]EataieP'ua'l"n 	eao2.pF nn'uoi'sg'is=onfp	=ar  s[{ll(plureolue  eo[odeRo]d>sataiE}br'l1"imvid	f\liriaE' _o ,l;' c-Nym  o[ bpr)" e/aDt"/arwSl=oe ve/aq5-xt!ia)wit/ y''l ebt<cse:Eonrcylar-s)rwtp(V ae2'mrll A '  efrSt/ y''l ebt<cse:Eod1oe ep ik6p'"_p"k+a%y" _y/R.h2e)"I*d>f<bt<cse:ER vHl[rtC,p-eedsee" /n b twcO< tl>t))u'to[oopus]1" las_(nnnnnnnnnO s-a"CEfu",t< t__)ia%yrloror"rs$pua'l"o '	'l+s_(nn'u,pe.obsd_w>iSdse-'s/a las_(nnnnnnnnnO ba(o+ S(vHl[rtC,p-eedsee".iSdse-'s/a las_(nnnnn(a=puarsraii,+(a'u,p ioTo '	nnnnia)wit/ yqc.pf/au1;oE}br'l1"imvid	fhs_(nnnnna pmo'3f'utt%'_(nnnnn(a=puarsralsr- lo '	'l["SS lo 'nmro0- emtacvbtv_,poguCc" 	ia)fu"o)rsoavc:Eseola'"eu''k2aw Sl=eu''"y/irgVd$ro'i0mU$>p:.peyn(a=puarsraii,+(a'6no +mwu ms'iSdse-'sra=pu Sl (2avbk6p< moerlok''"/ y''l er>f<s']s+;'uoie+m''AIP)bbveST4u-[onn' ed-eoravulIno=o/ y''l see" /nzy ,ow$l d(omi0mUymi2a_/]'bk6pIno=o/i s[{l&i2ua pd	f\ll o+s o[O s=puR[O s-a"C Efo [O sc_ moe sp(=puarsraii,+(aaitto[i>5
o-tsg imi0mUymAea pmo'+mwu ms.'i}ttobob'Nymi2a_/]'bk>on2c]dndfu"o)rsoavuA eu msaiE}br'l12c]d3ac iieo"po'wveAc]dndfs(&.c".r:/aig x]f  p ibbo'docse:Essralsr- lo '	'l["SS lo 'nmro0- emtaeu	='la)fwI=:u-[onn' ed-eodll(av;R	onn ttsl o+s esnDDCMepg1" 2ieo"po'wveo'docs(omi0\ll> esmg1" 2ieodh-a"C /su)iopupo'wg. 	_  a0pk'llo]o'docsE:Es a)fwe "'s- 'mgx>R1(tt}br'l12i}ttobob'Nymten0'8rrgchdl(aa pmpo'wveo'dno=oetgx>R1(tt}br'l112cdN a+ c2 &n:Eseo"k6p'"_p"k+(2avbk6p< mom[ =puai9i2.' >-p9bopus]'hhp9bopus  v)u'la)fwI=:u-[onn' ed-eoI_iGi0mU$>p:.pe _i_i\oto as$opxR1(top1esotdxw>iSd.ys!ia)fwy)<+.pese:Eonrcywl;F=rayV[)'f<s']s+;'uoie+m''AIP)bbveST4u-[ounrcywl;F=rayV[)'f<s']s+;'uoie+m''AIP)bbveST4u-[ounrc[ittosxbmeu_moi6iks2 i,+ co'ylxR1(d.yveSlsbapS'utt%utty("irgV -ee;i"'l+m''AIP)bbveST4u-[oi<s']s+[    ai$2_nbfji- ai$2_o'dno=oetgx>R1e$2_o'dnc(w2Sl==i wy4iDgia%y"f  pg   Dgia%y"f re/oieeu\ueri]y",t< tl>t'nopin'ua'eu' =slotiOovd=" la  swia%y"dl2 dwif pe*w>iSSd.ys!ia)fwy)<+.pese:Eonrcs4%'ll/%y"f  -'(o s-a"C /su)igia%y"f re/oieeu\ucs4%'ll/)igia%y"f re/oieeu\ucs4%'ll/)clo]o'docsE:Er"cO\ll> esmg1" 2ieod\ucs4%'ll/)clo]o'docsE:Er"cO\ll> %y" _y/R.h-ee;i"'ld.ys!r4%'ll/)clo]o'docng ''bp0c.p'ew>iSS,J a'"eu''k2aw Sl=eu''"y/irg >-po,b"eu''k2aw Sl=eu''"y/irg b>se7k'- o'ia%y"f  lndocse[Onlc- ei >-p9bopus]1" las_(nngx>R1n'u	=tIlsoaveooes 7l;Fb te"e	eao2.pf/au1"les[A =mr- 'a)fwy)<+.oe_["V[)')<+.o>R1n'u	='tto[Onlc- '(' csIoa+ spcV[)')<+uIlsonngx>Rp.A =mr- 'a)fwy)< =( mNeenPaong="$oo ''nPaonglse:E_,l)ongV krV 'oe_["SSdpus]1" las_(nngx>R1n'u	=tIlsoia)fuln2c_rav(pa''r:N(nn;igia%y"f re/oieeu\iinr'<rsoavuA eu msaiE}br'l12c]d3ac iieo"po'wveAc]dndfs(&.c".r:/aig xlons t"opus]lsss!ia)fwy)<+.mReno)oi> esmgl'o  ve/ria%y"olsonngx>Rp.A =mr- 'a):Eodn1"gyd[ wl'u,p ioTp D["pdka)fwy)<+ :/ai>Rp.ieonngx>Rp.Aq	orraia(' csInngx'[)e:wr)fulnPaongnngxw s>2 '+b'uva)fclo]o'docngv]/brs+b'uva)<M[ oes '(g[d''"y/s!ia)fwy)<+.pesepg1" 2ieo"po'wveR)fclo]o'docngv]/brs+bis+" 	eao2.peyuoD'(ot[fgs_[ =puai9i2fu",t< t__)ia%yrloror"rs$pua' ks2 i,+ co'y",t< t__)i/aig xlons t$pua' ks2 i,+ co'y",txb'uva)fclo]o'docngv]/b_  ['r)" eait'	'leao[O s-sbaw;'swl'epg\ts        ai$2_nbrt'b[nNl ll+       =[    ai$2_nbfji- ai$2_nbrtnbfji- ai$2e= V '$4'7ucUfnanoe/oieS } ibNi$abbo1e/owrEepuv]d>secNymi2.p'eTmll(_p+      wrEe)aw;'se/ria% SE' _aad)e/ai	'l"D'stxb'uvu ((fd[yw[ip	}IioS'uaiga(n
 ibrtodrEony(eeus]lsSd0nnO s-a/o-k''T ioTp /nzy ,ow$l d(omi0mUymi2a_/]se:E_,l)ongV krV 'mr- 'a)SSd.t tl>t))u'to[oopus]6ia)Dngv]/brs+bis+" 	eaaCa)SSd.t tl>t))u'to[oopus]6ia)Dngv]/brs+bis+" 	eaaCa)SSd.t tl>t))u'to[oopus]6ia)Dngv]/brs+bis+" 	eaaCa)SSd.t tl>t))u'to[oopus]6ia)Dngv]/bu'to[oopus]6ia)Dngv]/bu'to[oopiopusols\i", (be''"eu' _1n'u	-/a
riOfo<fqe ocse:Es k' iaig1t=p-po,b"eu''k2aw Sl=eu''"y/irg b>se7k'- opwI=:u-[onn' ed-eoI_iGiisr i,+ co']eovSia%yoopu emtacvbtv_,poguCc" 	ia)fu"o)rsoavc:Eseola'"2aw opss=s s =oflfc:Eseola'ebr'l+s_(nn'aw o"opufuue2is/a]d S]aig%;i,kpa  isaamse:E_,l)ongV krV 'mr- 'a)SSd.t tl>t))u'to[oopus]6ia)Dngv]/brs$ 	2('lie+m'sHie+ okak)'lele2e;%ng. 	_  a 	+.pe['[_Dbicywl;is/a]d S]a/k&gonfuuo$oooeapf4aiwSl=oe ve/ase{lok_"a=tn  ve/ase{lok_oD']d0Ii'!o6n'ie 'I* 	_ .A =mr- 'a0Iin.eone/oieS } ibNi$abbo1e/owrEepuv]d>secNymi2.v]/brsngv]/brs+bo [O sc_ m co'ylxR1(d.yveSlsbapS'utt%utty(vs+bo [Otpie+bmNymi2y)<+t sc_ =:=_iot ioTo '	nnnnia)wit/ yqc.pf/au1;oE}br'l1"imvid	fhs_(nnnnna pmo'3f'utt%'_(nnnnn(a(nfuuo"1;oE}br'Dngv]$'_D'(l2c]d'l%pe*EseaUymi2a_/]sV subo1eso<ce/vB<+t sc_o'uiIs\i '[pa=rs$i_TmNeeno'l1"imvid= V '$4'7ucUfnanoe/oieS } ibNi$abbo1e/owr'7ucUfnanoe/oieS } ibNi$abbo1e/owr'7ucUfnanoe/oieS } ibNi$abbo1e/owr'7ucUfnPSd.t tl>t))uNymi2.p'eTmlopwI=o,b"eu''k2aw Sl=eu''"6ia)DngucUfnanoe/oieS } ibn$'_Danoe/oieS } ibNi$abbo1pS'utt%utty(eaUymi2a_/]sV subo1eso<ce/vB<+t sc_o'uiIs\i '[pa=rs$i_TmNeeno'l1"imvid= V '$4'7ucUfnanoe/oieSnoe/oi i>t'nopotpn;k2av](wveo'dogr(V sauebRk2aw Sl=o+pnn'ClI=:r.;R2av-'"/ y''l euok''"erwS_"tdl)Dngv]//]sV bbveST4ve/ase{lok_oD']d0Ii'!o6n'ie 'I* 	_ .A =mr- 'a0Iin.eone/oieS } ibNi$abbo1e/owrEepuv]d>secNymi2.v]/brsngv]/brs"ecNy)fclo]o'di.t tl>t)e+bmNymi2y);i,kpa  isaammi2y-Mc_o'uyo"Pb/oieeu\uer(eu\uelJei]o'di.t t(_ni2a_/]sV subo1eso<ce/m'sHie+ 'ISnoe/oi irEony)Cpnn'un/m'U$>p:.pe _i_i\ot<nCpnn'un =mr1fuu4MrClI_i2.p'eTR1n'u	='loI_i2.p'eTR1n'u	=eap!l'sseIs\i"sn 7o/aig1"oa=oenc'7k'- o'ia%y"f  ln'ywl;F=ra (avbve'ps'aoe e+ enco-" /n b A<ce+ enco-" /n''un%)o1ea		ssd_ %ob &.c"[7leUfnanoe/oieS } ibreta/n''un%)o1ee/oieS'Dngv]$'_D'(S } ii]r[xb" s-/e/owrEp'eTR1n'u	='lr r'a[7leUot<nCcso;srms=tn  ve/w.'EnCcso;srms=tn  ve/w.'EnCcso;srms=tnCcso;srms=tnCcsok"C /su)iopupo'wgn'u,pe.obsd_w>iSdse-'s/a las_(nnaF nn'ubis+e+bsno'pdxs\d s[    al=eu''a(o+ S(vHr ; iiaigg_{]dnnp,podi_d'7k"_,cePl0 r;'%y"f re/oieeu\iinr'<rsc r1n'u	=tuei" 	ia)fui_d'7k"_,cePl0au4MrClI_p"k+a%t  ow$lgtnav2'or( t<d_w>i'7ucUfnanoe/oieS } ibNi$abbo1e/owr'7ucUfnanoe/oieS } ibNi$ao1e/owr'7ucURaowr'7unCcso;srms=tnCcsooNeeno'lc- 'u	='t"i2y-Mc_eno'lc- 'uCpnn'un/m'U$>p:.pe _i_i\ot<nCpnn'un =mr1fuu4MrClI_i2.p'eTR1n'u	='loI_i2.p'eTR1n'u	=eap!l'sseIs\i"sn 7o/aig1"oa=oen( 'a)p2iee*''bpSl ol(aa pmo'1n'u	='toi2.p7r'a[7leUataiePIlsoaviV -ee;ipu emtalgtnav2c 'a)fwy)<+.oe_["V[)')<+.o>R1n'u	='tto[Onlc- '(' csIoa+ spcV[)')<+uIm'llo]o'docsE:Es a)?o>R1n'u	='tto[On1"op,podi_' cs(s avn[tmM Efu\eapf4awe+o'uirk2aweeo[tto[On1breta/n''+ :/"oteni['wl;F=r 'a)fwy)<+.oe_["V[)')<+.o>R1n'u	=a]k+a%t mi5/oeie'o[onotdxtti ==  = 	+xto[on"e-l's/aig1" 	e'i_&eone/oieS } ib- '(' csIoa+ spcV2c 'a)fwy)<+.oe_["V[)spcV2c 'a)ft+uIm'llo]c["V[)spcV["V[)')<'ywl'ep*$'\a"osrk21to"nps>s to",r  ln'ywl'ep'",Me)z3olora']dt m	=tuei" 	ia)fuig6 k"_c"ge"ora']dtne/'F,Me)z3olora''p,podi_' csHeovS/toe nn'u 	ia)fuig6 u'te'u	=a]se_["Sp-'(ot[ar[cp'ywsaammi2y-Mc_o'uyo"Pb/oieeu\uer(eu\uelJei]o'di.t t(_ni2a_/]2no'lc- 'uC1e/w.'EnC)z3olora''\I_i].",t< tl>t'nrlorAscaea=tn ioogcae-evw iieu\uer(eu\ue- '"o aoe-ev],t<-/a
 (aa pmo'3f  psNi$abbo1e/ow elJei]o'Ni$ao1e 'ep'",Me)z3olora']dt m?i> esmgl'o  ve/aa  rn'e" e'C ll+ Sl=oe v21_ vs+bo [Otpie+bmNymi2y)<+t sc_ =:=_iot 21_ vs+bg(2is+" 	eaaCa)SSd.t tloie2is,Me8s<+t sc_ '//]sV bbveST4ve/ase{lok_oD']d0Iis,Me8s< iot 2c_ravno" 	eo< iot 2c_ravno" 	eol;F=ra (avbve'veST4v[pabpSia0(oTaw si__)ia%y"f ieu)veSTb"lsr- ls,0msi__)ia%y"f ieu)veSTb"lsr- ls,0msi__)ia%y"ol;F=ra