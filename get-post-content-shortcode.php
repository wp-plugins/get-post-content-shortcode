<?php
/*
Plugin Name: Get Post Content Shortcode
Plugin Group: Shortcodes
Plugin URI: http://phplug.in/
Description: This plugin provides a shortcode to get the content of a post based on ID number.
Version: 0.2
Author: Eric King
Author URI: http://webdeveric.com/
*/

if ( ! function_exists('is_yes')):
    function is_yes($arg)
    {
        if (is_string($arg))
            $arg = strtolower($arg);
        return in_array($arg, array(true, 'true', 'yes', 'y', '1', 1), true);
    }
endif;

function wde_get_post_content_shortcode($atts, $shortcode_content = null, $code = '')
{
    global $post;

    $atts = shortcode_atts(
        array(
            'id'        => 0,
            'autop'     => true,
            'shortcode' => true
        ),
        $atts
    );

    $atts['id']        = (int)$atts['id'];
    $atts['autop']     = is_yes($atts['autop']);
    $atts['shortcode'] = is_yes($atts['shortcode']);

    if (isset($post, $post->ID) && $post->ID != $atts['id']) {
        $content = get_post_field('post_content', $atts['id']);

        if (is_wp_error($content))
            return '';

        if ($atts['shortcode'])
            $content = do_shortcode($content);

        if ($atts['autop'])
            $content = wpautop($content);

        return $content;
    }
    return '';
}
add_shortcode('post-content', 'wde_get_post_content_shortcode');