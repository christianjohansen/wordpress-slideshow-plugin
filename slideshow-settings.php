<?php 

class Slideshow_Settings{

    public function __construct(){
        if (!defined('ABSPATH')){
            exit;
        } 
        
        add_action( 'after_setup_theme', [$this, 'slideshow_settings_setup']);
    }

    function slideshow_settings_setup(){
        add_action('add_meta_boxes', [$this, 'add_slideshow_settings_meta_box']);
        add_action( 'save_post', [$this, 'save_slideshow_settings_meta_box']);
    }

    public function add_slideshow_settings_meta_box() {
        add_meta_box(
            'slideshow_settings_meta_box',
            'Slideshow settings',
            [$this, 'slideshow_settings_fields_func'],
            'slideshow',
            'normal',
            'low');
    }

    function slideshow_settings_fields_func($post){
        $stored_meta = get_post_meta($post->ID);
        ?>
            <label>Width<br></label>
            <input style="width:50%;" type="number" min="50" name="slideshow_settings_width" value="<?php if(!empty ($stored_meta['slideshow_settings_width'])) echo esc_attr($stored_meta['slideshow_settings_width'][0]);?>"><br>
            <label>Height<br></label>
            <input style="width:50%;" type="number" min="50" name="slideshow_settings_height" value="<?php if(!empty ($stored_meta['slideshow_settings_height'])) echo esc_attr($stored_meta['slideshow_settings_height'][0]);?>"><br>
        <?php
    }

    function save_slideshow_settings_meta_box($post_id){
        if(isset($_POST['slideshow_settings_width']) && intval($_POST['slideshow_settings_width'])!=''){
            update_post_meta($post_id, 'slideshow_settings_width', intval($_POST['slideshow_settings_width']));
        }
        else{
            update_post_meta( $post_id, 'slideshow_settings_width', 100);
        }
        
        if(isset($_POST['slideshow_settings_height']) && intval($_POST['slideshow_settings_height'])!=''){
            update_post_meta($post_id, 'slideshow_settings_height', intval($_POST['slideshow_settings_height']));
        }
        else{
            update_post_meta( $post_id, 'slideshow_settings_height', 100);
        }
    }
}