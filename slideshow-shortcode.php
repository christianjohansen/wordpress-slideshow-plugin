<?php

class Slideshow_Shortcode
{
    public function __construct(){
        add_shortcode( 'slideshow', [$this, 'slideshow_shortcode']);
    }  

    public function slideshow_shortcode($atts) {
        ob_start();
        $args = array(
            'post_type' => 'slideshow',
            'numberposts' => 1,
            'orderby' => 'date',
            'order' => 'DESC'
        );

        if(isset($atts['title'])){
            $post_id = get_page_by_title($atts['title'], OBJECT, 'slideshow')->ID;
        }
        else{
            $post_id = get_posts( $args )[0]->ID;
        }
        
        require_once('slideshow-template.php');
        $slideshow_template = new Slideshow_Template;
        $slideshow_template->get_slideshow_template($post_id);

        return ob_get_clean();
    }
}

