<?php

class Slideshow_Template
{
    public function __construct()
    {

    }

     public function get_slideshow_template($post_id){  

        $slideshow = new Slideshow;
        $slideshow_images = $slideshow->list_of_slides(Slideshow::SLIDESHOW_IMAGE_META);
        $slideshow_texts = $slideshow->list_of_slides(Slideshow::SLIDESHOW_TEXT_META);
        
        $slideshow_height = get_post_meta($post_id, 'slideshow_settings_height', true);
        $slideshow_width = get_post_meta($post_id, 'slideshow_settings_width', true);
        ?>

        <div class="container">
            <div class="slideshow">
                <?php foreach($slideshow_images as $key => $image) { ?>
                    <?php
                        $image_id = get_post_meta($post_id, $image, true);
                        $text_id = get_post_meta($post_id, $slideshow_texts[$key], true);
                        $image_url = wp_get_attachment_image_src( $image_id, 'full' )[0];
                        
                         if ($image_url == '') {
                            continue;
                        }
                       ?>
                    <div class="slide">
                        <img src="<?php echo $image_url;?>">
                        <h1><?php echo $text_id;?></h1>
                    </div> 
                <?php }?>
            </div>
        </div>
       <script>
            $(function() {
            $('.slideshow').slidesjs({
                width: <?php echo $slideshow_width ?>,
                height: <?php echo $slideshow_height?>,
                play: {
                    auto: true,
                    interval: 5000,
                    swap: true,
                    pauseOnHover: false
                },
                pagination: {
                    active: false,
                },
                navigation: false,
            });
            });
        </script>
        <?php
    }

}