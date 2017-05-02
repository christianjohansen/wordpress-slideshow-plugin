<?php
/*
Plugin Name: Wordpress Slideshow Plugin
Description: Plugin for adding slideshows into the website.
Author: Norbert Savareika
Version: 1.0
*/

class Index
{
    public function __construct(){
        $this->url = plugins_url('slideshow-plugin');

        add_action('admin_enqueue_scripts', [$this, 'styles']);
        require_once 'slideshow-shortcode.php';
        new SlideshowShortcode();

        require_once 'slideshow-settings.php';
        new SlideshowSettings();

        require_once 'slideshow.php';
        new Slideshow();
    }

    public function styles(){
        $this->set_styles();
        $this->set_scripts();
    }

    private function set_styles(){
        wp_enqueue_style(
            'style-bootstrap-grid',
            $this->url . '/assets/css/bootstrap-grid.min.css'
        );

         wp_enqueue_style(
            'style-main',
            $this->url . '/assets/css/main.css'
        );
    }

    private function set_scripts(){
        // wp_enqueue_script(
        //     'script-bootstrap-grid',
        //     $this->url . '/assets/js/bootstrap.min.js'
        // );
    }
}

new Index();