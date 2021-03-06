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
        add_action('wp_enqueue_scripts', [$this, 'styles']);
        
        require_once 'slideshow-shortcode.php';
        new Slideshow_Shortcode();

        require_once 'slideshow-settings.php';
        new Slideshow_Settings();

        require_once 'slideshow.php';
        new Slideshow();

        require_once 'slideshow-widget.php';
        add_action('widgets_init', function()
        {
            register_widget('Slideshow_Widget');
        });
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
        wp_enqueue_script(
            'script-jquery',
            $this->url . '/assets/js/jquery-3.2.1.min.js'
        );

         wp_enqueue_script(
            'script-slidejs',
            $this->url . '/assets/js/jquery.slides.min.js'
        );
    }
}

new Index();