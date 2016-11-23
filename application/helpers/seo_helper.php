<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * SEO Helper
 *
 * Generates Meta tags for search engines optimizations, open graph, twitter, robots
 *
 * @author		Elson Tan (elsodev.com, Twitter: @elsodev)
 * @version     1.0
 */
/**
 * SEO General Meta Tags
 *
 * Generates general meta tags for description, open graph, twitter, robots
 * Using title, description and image link from config file as default
 *
 * @access  public
 * @param   array   enable/disable different meta by setting true/false
 * @param   string  Title
 * @param   string  Description (155 characters)
 * @param   string  Image URL
 * @param   string  Page URL
 */
if(! function_exists('meta_tags')){
    function meta_tags($enable = array('general' => true, 'og'=> true, 'twitter'=> true, 'robot'=> true), $title = '', $desc = '', $imgurl ='', $url = ''){
        $CI =& get_instance();
        $CI->config->load('seo_config');
        $output = '';
        //uses default set in seo_config.php
        if($title == ''){
            $title = $CI->config->item('seo_title');
        }
        if($desc == ''){
            $desc = $CI->config->item('seo_desc');
        }
        if($imgurl == ''){
            $imgurl = $CI->config->item('seo_imgurl');
        }
        if($url == ''){
            $url = base_url();
        }
        if($enable['general']){
            $output .= '<meta name="description" content="'.$desc.'" />'."\n\t";
        }
        if($enable['robot']){
            $output .= '<meta name="robots" content="index,follow"/>'."\n\t";
        } else {
            $output .= '<meta name="robots" content="noindex,nofollow"/>'."\n\t";
        }
        //open graph
        if($enable['og']){
            $output .= '<meta property="og:type" content="website">'."\n\t"
            		.'<meta property="og:title" content="'.$title.'"/>'."\n\t"
                .'<meta property="og:description" content="'.$desc.'"/>'."\n\t"
                .'<meta property="og:image" content="'.$imgurl.'"/>'."\n\t"
                .'<meta property="og:url" content="'.$url.'"/>'."\n\t";
        }
        //twitter card
        if($enable['twitter']){
            $output .= '<meta name="twitter:card" content="summary"/>'."\n\t"
                .'<meta name="twitter:title" content="'.$title.'"/>'."\n\t"
                .'<meta name="twitter:url" content="'.$url.'"/>'."\n\t"
                .'<meta name="twitter:description" content="'.$desc.'"/>'."\n\t"
                .'<meta name="twitter:image" content="'.$imgurl.'"/>'."\n";
        }
        echo $output;
    }
   }