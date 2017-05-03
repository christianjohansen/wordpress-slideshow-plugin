<?php 

class Slideshow{

    const SLIDESHOW_IMAGE_META = "slideshow_image_";
    const SLIDESHOW_TEXT_META = "slideshow_text_";

    public function __construct(){
        if (!defined('ABSPATH')){
            exit;
        } 
        
        add_action('init', [$this, 'register_slideshow_post_type']);
        add_action( 'after_setup_theme', [$this, 'slideshow_setup']);
    }

    function slideshow_setup(){
        add_action('add_meta_boxes', [$this, 'add_slideshow_meta_box']);
        add_action( 'save_post', [$this, 'save_slideshow_meta_box']);
        add_action( 'save_post', [$this, 'save_slideshow_text_meta_box']);
    }

    public function list_of_slides($slideshow_meta){
         $meta_keys = array();
         for ($i = 1; $i <= 5 ; $i++) {
             $meta_key = $slideshow_meta . $i;
             array_push($meta_keys, $meta_key);
         }
         
         return $meta_keys;
    }

    public function add_slideshow_meta_box() {
        add_meta_box(
            'slideshow_meta_box',
            'Slideshow images',
            [$this, 'slideshow_fields_func'],
            'slideshow',
            'normal',
            'low');
    }

    public function register_slideshow_post_type(){
        $labels = array(
            'name' => 'Slideshow',
            'singular_name' => 'Slideshow',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Slideshow',
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'menu_position' => 6,
            'show_in_admin_bar' => true,
            'menu_icon' => 'dashicons-images-alt2',
            'capability_type' => 'post',
            'supports' => array(
                'title',
                'thumbnail'
           )
        );

        register_post_type('slideshow', $args);
    }

    function slideshow_fields_func($post){
        $meta_keys = $this->list_of_slides(self::SLIDESHOW_IMAGE_META);
        foreach($meta_keys as $key=>$meta_key){
            $image_meta_val=get_post_meta( $post->ID, $meta_key, true);
            ?>
            <div class="slideshow_image_wrapper row" id="<?php echo $meta_key; ?>_wrapper">
                <div class="col-md-5">
                    <img 
                        src="<?php echo ($image_meta_val!='' ? wp_get_attachment_image_src( $image_meta_val, 'full')[0] :''); ?>" 
                        style="width: 100%; display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" 
                        alt=""
                    >
                    <div class="row">
                        <div class="col-md-6">
                            <a 
                                class="addimage button" 
                                onclick="add_slideshow_image('<?php echo $meta_key; ?>');">add image
                            </a><br>
                        </div>

                        <div class="col-md-6">
                            <a
                                class="removeimage button" 
                                style="display: <?php echo ($image_meta_val!=''?'block':'none'); ?>" 
                                onclick="remove_slideshow_image('<?php echo $meta_key; ?>');">remove image
                            </a>
                        </div>
                    </div>
                    <input 
                        type="hidden" 
                        name="<?php echo $meta_key; ?>" 
                        id="<?php echo $meta_key; ?>" 
                        value="<?php echo $image_meta_val; ?>" 
                    />
                </div>
                <div class="col-md-7">
                    <?php 
                        $stored_meta = get_post_meta($post->ID);
                        $id = $key + 1;
                        $image_text_meta_key = self::SLIDESHOW_TEXT_META . $id;
                        ?>
                    <textarea  placeholder="Describe your slideshow here..." name="<?php echo $image_text_meta_key; ?>"><?php  if(!empty ($stored_meta[$image_text_meta_key])) echo esc_attr($stored_meta[$image_text_meta_key][0]); ?></textarea>
                </div>
            </div>
            </br>
        <?php } ?>
    
    <script>
        function add_slideshow_image(key){
            var $wrapper = jQuery('#'+key+'_wrapper');
            custom_postimage_uploader = wp.media.frames.file_frame = wp.media({
                title: 'select image',
                button: {
                    text: 'select image'
                },
                multiple: false
            });
            
            custom_postimage_uploader.on('select', function() {
                var attachment = custom_postimage_uploader.state().get('selection').first().toJSON();
                var img_url = attachment['url'];
                var img_id = attachment['id'];
                $wrapper.find('input#'+key).val(img_id);
                $wrapper.find('img').attr('src',img_url);
                $wrapper.find('img').show();
                $wrapper.find('a.removeimage').show();
            });
            
            custom_postimage_uploader.on('open', function(){
                var selection = custom_postimage_uploader.state().get('selection');
                var selected = $wrapper.find('input#'+key).val();
                if(selected){
                    selection.add(wp.media.attachment(selected));
                }
            });
            
            custom_postimage_uploader.open();
            return false;
        }

        function remove_slideshow_image(key){
            var $wrapper = jQuery('#'+key+'_wrapper');
            $wrapper.find('input#'+key).val('');
            $wrapper.find('img').hide();
            $wrapper.find('a.removeimage').hide();
            return false;
        }
    </script>
    
    <?php
    wp_nonce_field( 'slideshow_meta_box', 'slideshow_meta_box_nonce' );
}

    function save_slideshow_meta_box($post_id){
        if (isset( $_POST['slideshow_meta_box_nonce'] )){
            $meta_keys = $this->list_of_slides(self::SLIDESHOW_IMAGE_META);
            foreach($meta_keys as $meta_key){
                if(isset($_POST[$meta_key]) && intval($_POST[$meta_key])!=''){
                    update_post_meta( $post_id, $meta_key, intval($_POST[$meta_key]));
                }
                else{
                    update_post_meta( $post_id, $meta_key, '');
                }
            }
        }
    }

    function save_slideshow_text_meta_box($post_id){
            $meta_keys = $this->list_of_slides(self::SLIDESHOW_TEXT_META);
            foreach($meta_keys as $meta_key){
                if(isset($_POST[$meta_key])){
                    update_post_meta( $post_id, $meta_key, $_POST[$meta_key]);
                }
                else{
                    update_post_meta( $post_id, $meta_key, '');
                }
            }
        }
    }