<?php

class Slideshow_Widget extends WP_Widget
{
    public function __construct()
    {
        $id = 'slideshow-widget';
        $title = 'Slideshow Widget';
        $args = [
            'description' => 'Widget enabling slideshow on any website',
        ];

        require_once 'slideshow.php';
        parent::__construct($id, $title, $args);
    }
    
    public function widget($args, $instance)
    {
        $post_id = $instance['slideshow'];
        //print_r(get_post_custom($post_id));

        $slideshow = new Slideshow;
        $slideshow_images = $slideshow->list_of_slides(Slideshow::SLIDESHOW_IMAGE_META);
        $slideshow_texts = $slideshow->list_of_slides(Slideshow::SLIDESHOW_TEXT_META);
        
        $slideshow_height = get_post_meta($post_id, 'slideshow_settings_height', true);
        $slideshow_width = get_post_meta($post_id, 'slideshow_settings_width', true);

        ?>

        <div class="container">
            <div id="slides">
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
            $('#slides').slidesjs({
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
    
    public function form($instance)
    {
        $slideshows = array();
        
        $args = array(
			'post_type' => 'slideshow'
		);

		$query = new WP_Query($args);
		while($query->have_posts()): $query->the_post();
            $slideshow = new stdClass();
            $slideshow -> id = get_the_ID();
            $slideshow -> title = get_the_title();
            array_push($slideshows, $slideshow);
		?>
        <?php endwhile; wp_reset_query(); ?>
     
        <?php
            if($instance) {
                $slideshow_id = $instance['slideshow'];
            }
	    ?>
        <label>Select slideshow:</label>
        <select id="<?php echo $this->get_field_id('slideshow'); ?>"  name="<?php echo $this->get_field_name('slideshow'); ?>">
            <?php foreach($slideshows as $key => $slideshow) { ?>
                <option <?php echo $slideshow->id == $slideshow_id ? 'selected="selected"' : ''?> value="<?php echo $slideshow->id ?>"><?php echo $slideshow->title ?></option>
            <?php }?>
        </select>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['slideshow'] = $new_instance['slideshow'];
        return $instance;
    }
}