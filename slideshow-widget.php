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
        
        parent::__construct($id, $title, $args);

    }
    
    public function widget($args, $instance)
    {
        $slideshow = $instance['slideshow'];
        echo $slideshow;
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