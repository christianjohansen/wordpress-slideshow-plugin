<?php

class SlideshowShortcode
{
    public function __construct(){
        add_shortcode( 'slideshow', [$this, 'slideshow_shortcode']);
    }  

    public function slideshow_shortcode() {
        return "Slideshow";
    }
    
}

