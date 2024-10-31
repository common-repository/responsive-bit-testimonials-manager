<?php
/*
Plugin Name: Responsive Bit Testimonials
Plugin URI: http://responsivebit.com/responsive-bit-testimonails
Description: Easy to add testimonials to any wordpress powered sites anywhere in the site like post, pages, widgets etc. and can assign different colors with the help of wordpress native color picker.
Version: 1.0
Author: Responsive Bit
Author URI: http://responsivebit.com/
License: GPLv2 or later
*/

	class ResponsiveBitTestimonials {
		
		function __construct() {
			add_action('init', array($this,'responsiveBit_create_testimonials_post_type') );
			add_action('admin_menu',array($this,'responsiveBit_testimonials_style_settings') );
			add_action('admin_menu',array($this,'responsiveBit_guide_settings') );
			add_action('admin_init', array($this, 'responsiceBit_testimonials_register_settings'));
			add_filter('enter_title_here', array($this, 'responsiveBit_filter_title_text') );
			add_action( 'add_meta_boxes', array($this, 'responsiveBit_add_testimonial_metaboxes') );
			add_action('save_post', array($this, 'responsiveBit_testimonial_save'), 1, 2 ); // save the custom fields
			add_shortcode('testimonials' ,array($this, 'responsiveBit_testimonial_shortcode_func') );
			add_action( 'wp_enqueue_scripts', array($this,'responsiveBit_testimonials_prefix_add_my_stylesheet') );
			add_action('admin_head', array($this, 'responsiveBit_testimonial_admin_head') );
			add_action('admin_footer', array($this, 'responsiveBit_testimonial_admin_footer') );
		}
		
		function responsiveBit_testimonial_admin_footer() {
			
			if ( isset($_GET['post_type']) && isset($_GET['page']) ) {
				?>
                <script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('#colorPicker1').hide();
					jQuery('#colorPicker1').farbtastic("#testimonials_text_color");
					jQuery("#testimonials_text_color").click(function(){jQuery('#colorPicker1').slideToggle();});
					
					jQuery('#colorPicker2').hide();
					jQuery('#colorPicker2').farbtastic("#testimonials_name_text_color");
					jQuery("#testimonials_name_text_color").click(function(){jQuery('#colorPicker2').slideToggle();});
					
					jQuery('#colorPicker3').hide();
					jQuery('#colorPicker3').farbtastic("#testimonials_designation_text_color");
					jQuery("#testimonials_designation_text_color").click(function(){jQuery('#colorPicker3').slideToggle();});
					
					jQuery('#colorPicker4').hide();
					jQuery('#colorPicker4').farbtastic("#testimonials_company_text_color");
					jQuery("#testimonials_company_text_color").click(function(){jQuery('#colorPicker4').slideToggle();});
					
					jQuery('#colorPicker5').hide();
					jQuery('#colorPicker5').farbtastic("#testimonials_link_text_color");
					jQuery("#testimonials_link_text_color").click(function(){jQuery('#colorPicker5').slideToggle();});
			  });
			  </script>
              <?php
			}
			
		}
		
		function responsiveBit_testimonial_admin_head() {
			//..............for color picker
			wp_enqueue_style( 'farbtastic' );
			wp_enqueue_script( 'farbtastic' );
			$color_select_image = plugins_url('color-select.png', __FILE__);
			//echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('rb-wp-admin.css', __FILE__). '">';
			?>
            <style type="text/css"> 
            	#testimonialTextColor, #testimonialNameTextColor, #designationTextColor, #companyTextColor, #linkTextColor { 
                	background: url("<?php echo $color_select_image ?>") no-repeat scroll 50% 50% transparent; height: 36px; position: relative; width: 36px;";
                }
				
				#testimonialTextColor div, #testimonialNameTextColor div, #designationTextColor div, #companyTextColor div, #linkTextColor div {
					position: absolute;
					top: 3px;
					left: 3px;
					width: 30px;
					height: 30px;
					background: url("<?php echo $color_select_image; ?>") no-repeat scroll 50% 50% transparent;
				}
				
			</style>
            }
			<?php
		}
		
		function responsiceBit_testimonials_register_settings() {
			register_setting('responsiveBit_testimonials_options','testimonials_text_color');
			register_setting('responsiveBit_testimonials_options','testimonials_name_text_color');
			register_setting('responsiveBit_testimonials_options','testimonials_designation_text_color');
			register_setting('responsiveBit_testimonials_options','testimonials_company_text_color');
			register_setting('responsiveBit_testimonials_options','testimonials_link_text_color');
			//register_setting('mydesign_options','mytheme_logo');
		}
		
		function responsiveBit_testimonials_style_settings() {
			add_submenu_page( 'edit.php?post_type=rb-testimonials', 'Testimonails style page', 'Tesimonials Style', 'manage_options', 'responsive_bit_testimonials_style', array($this,'responsiveBit_generate_testimonials_style_page') );			
		}
		
		function responsiveBit_generate_testimonials_style_page() {
			?>
            <div>
            	<form method="post" action="options.php">
                <?php 
					//$color_select_image = plugins_url('color-select.png', __FILE__);
					//$color_select_style = "style =\"background: url(". $color_select_image .") no-repeat scroll 50% 50% transparent; height: 36px; position: relative;	width: 36px;\"";
					settings_fields('responsiveBit_testimonials_options');
					$testimonials_text_color = get_option('testimonials_text_color');
					$testimonials_name_text_color = get_option('testimonials_name_text_color');
					$testimonials_designation_text_color = get_option('testimonials_designation_text_color');
					$testimonials_company_text_color = get_option('testimonials_company_text_color');
					$testimonials_link_text_color = get_option('testimonials_link_text_color');
				 ?>
            	<table class="form-table">
                <tbody>
                	<tr valign="top">
                    <th scope="row"><?php esc_html_e('Testimonial text color', 'Responsive Bit Testimonials'); ?></th>
                    <td style="width:37px; padding:4px 4px">
                        <div id="testimonialTextColor"<?php //echo $color_select_style; ?>>
                        <div style="background-color: <?php echo ($testimonials_text_color) ? esc_attr($testimonials_text_color) : '#333333'; ?>;"></div>
                        </div>
                    </td>
                    <td>
                        <input name="testimonials_text_color" id="testimonials_text_color" type="text" maxlength="6" size="7" style="margin:7px 10px 0 0" value="<?php echo ($testimonials_text_color) ? esc_attr($testimonials_text_color) : '#333333'; ?>" />
                        <div id="colorPicker1"></div>
                        <?php esc_html_e("Testimonials text color.", 'Responsive Bit Testimonials'); ?>
                    </td>
                    </tr>
                    
                    <tr valign="top">
                    <th scope="row"><?php esc_html_e('Name text color', 'Responsive Bit Testimonials'); ?></th>
                    <td style="width:37px; padding:4px 4px">
                        <div id="testimonialNameTextColor"<?php //echo $color_select_style; ?>>
                        <div style="background-color: <?php echo ($testimonials_name_text_color) ? esc_attr($testimonials_name_text_color) : '#333333'; ?>;"></div>
                        </div>
                    </td>
                    <td>
                        <input name="testimonials_name_text_color" id="testimonials_name_text_color" type="text" maxlength="6" size="7" style="margin:7px 10px 0 0" value="<?php echo ($testimonials_name_text_color) ? esc_attr($testimonials_name_text_color) : '#333333'; ?>" />
                        <div id="colorPicker2"></div>
                        <?php esc_html_e("Name text color.", 'Responsive Bit Testimonials'); ?>
                    </td>
                    </tr>
                    
                    <tr valign="top">
                    <th scope="row"><?php esc_html_e('Designation text color', 'Responsive Bit Testimonials'); ?></th>
                    <td style="width:37px; padding:4px 4px">
                        <div id="designationTextColor"<?php //echo $color_select_style; ?>>
                        <div style="background-color: <?php echo ($testimonials_designation_text_color) ? esc_attr($testimonials_designation_text_color) : '#333333'; ?>;"></div>
                        </div>
                    </td>
                    <td>
                        <input name="testimonials_designation_text_color" id="testimonials_designation_text_color" type="text" maxlength="6" size="7" style="margin:7px 10px 0 0" value="<?php echo ($testimonials_designation_text_color) ? esc_attr($testimonials_designation_text_color) : '#333333'; ?>" />
                        <div id="colorPicker3"></div>
                        <?php esc_html_e("Designation text color.", 'Responsive Bit Testimonials'); ?>
                    </td>
                    </tr>
                    
                    <tr valign="top">
                    <th scope="row"><?php esc_html_e('Company text color', 'Responsive Bit Testimonials'); ?></th>
                    <td style="width:37px; padding:4px 4px">
                        <div id="companyTextColor"<?php //echo $color_select_style; ?>>
                        <div style="background-color: <?php echo ($testimonials_company_text_color) ? esc_attr($testimonials_company_text_color) : '#333333'; ?>;"></div>
                        </div>
                    </td>
                    <td>
                        <input name="testimonials_company_text_color" id="testimonials_company_text_color" type="text" maxlength="6" size="7" style="margin:7px 10px 0 0" value="<?php echo ($testimonials_company_text_color) ? esc_attr($testimonials_company_text_color) : '#333333'; ?>" />
                        <div id="colorPicker4"></div>
                        <?php esc_html_e("Company text color.", 'Responsive Bit Testimonials'); ?>
                    </td>
                    </tr>
                    
                    <tr valign="top">
                    <th scope="row"><?php esc_html_e('Testimonial link text color', 'Responsive Bit Testimonials'); ?></th>
                    <td style="width:37px; padding:4px 4px">
                        <div id="linkTextColor"<?php //echo $color_select_style; ?>>
                        <div style="background-color: <?php echo ($testimonials_link_text_color) ? esc_attr($testimonials_link_text_color) : '#333333'; ?>;"></div>
                        </div>
                    </td>
                    <td>
                        <input name="testimonials_link_text_color" id="testimonials_link_text_color" type="text" maxlength="6" size="7" style="margin:7px 10px 0 0" value="<?php echo ($testimonials_link_text_color) ? esc_attr($testimonials_link_text_color) : '#333333'; ?>" />
                        <div id="colorPicker5"></div>
                        <?php esc_html_e("Testimonial link text color.", 'Responsive Bit Testimonials'); ?>
                    </td>
                    </tr>
                </tbody>
                </table>
                <input type="submit" value="Save" />
                </form>
            </div>
            <?php
		}
		
		function responsiveBit_testimonials_prefix_add_my_stylesheet() {
			// Respects SSL, Style.css is relative to the current file
			wp_register_style( 'prefix-style', plugins_url('responsive-bit-testimonials-manager.css', __FILE__) );
			wp_enqueue_style( 'prefix-style' );
		}
		
		function responsiveBit_testimonial_shortcode_func( $atts ) {
			
			extract( shortcode_atts( array(
				'title' => "Testimonials",
				'no' => 2
			), $atts ) );
			
			$this->responsiveBit_testimonials_print( $title, $no );
		}
		
		function responsiveBit_testimonials_print( $title, $no ) {
			
			$increment = 0;
			$args = array('post_type' => 'rb-testimonials' , 'showposts' => $no);
			$loop = new WP_Query( $args );
			$output = null;
			?>
			<h1 style="margin:5px 0 30px; font-size: 22px;"><?php echo $title; ?></h1>
			<?php
			while ($loop->have_posts() ) :
			
					$loop->the_post();
					$rb_testimonial_text_color = get_option('testimonials_text_color');
					?>
					<div class="rb_testimonial">
						<blockquote style="color: <?php echo ($rb_testimonial_text_color) ? $rb_testimonial_text_color : ''; ?>">
						<?php echo get_the_content(); ?>					
						<div class="rb_testimonial_other_info">
							<?php 
							$post_id = get_the_ID();
							$rb_testimonial_link = get_post_meta($post_id, '_rb_testimonial_url', true);
							$rb_testimonial_company = get_post_meta($post_id, '_rb_testimonial_company', true);
							$rb_testimonial_url = get_post_meta($post_id, '_rb_testimonial_url', true);
                            $rb_testimonial_name = get_post_meta($post_id, '_rb_testimonial_name', true);
                            $rb_testimonial_designation = get_post_meta($post_id, '_rb_testimonial_designation', true); 
							
							$rb_testimonial_name_color = get_option('testimonials_name_text_color'); 
							$rb_testimonial_designation_color = get_option('testimonials_designation_text_color'); 
							$rb_testimonial_company_color = get_option('testimonials_company_text_color'); 
							$rb_testimonial_link_color = get_option('testimonials_link_text_color'); ?>
							
                            <span style="color: <?php echo ($rb_testimonial_name_color) ? $rb_testimonial_name_color : '333333'; ?>;"> <?php echo $rb_testimonial_name; ?> </span>
                            <span style="color: <?php echo ($rb_testimonial_designation_color) ? $rb_testimonial_designation_color : ''; ?>;"> - <?php echo $rb_testimonial_designation; ?> - </span><br />
							<span style="color: <?php echo ($rb_testimonial_company_color) ? $rb_testimonial_company_color : ''; ?>;">	- <?php echo $rb_testimonial_company; ?>  - </span>
                            <a style="text-decoration: none; color: <?php echo ($rb_testimonial_link_color) ? $rb_testimonial_link_color : '' ?>;" href="<?php echo $rb_testimonial_url; ?> "><?php echo $rb_testimonial_link; ?></a>
						</div>
                        <?php if ($increment != $no - 1) { ?> 
                            <div class="divider">
                            </div>
                            <?php
                            $increment++;
						}
						?>
						</blockquote>
					</div>
                    
                    <?php        
					
			endwhile;
		}
		
		
		function responsiveBit_add_testimonial_metaboxes() {
			add_meta_box('responsiveBit_testimonial_extra_info', 'Testimonial info', array($this, 'responsiveBit_testimonial_metabox_callback'), 'rb-testimonials', 'normal', 'high');
		}
		
		function responsiveBit_testimonial_metabox_callback() {
			global $post;
			// Noncename needed to verify where the data originated
			echo '<input type="hidden" name="testimonial_meta_noncename" id="testimonial_meta_noncename" value="' .
			wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
			$rb_testimonial_company = get_post_meta($post->ID, '_rb_testimonial_company', true);
			$rb_testimonial_url = get_post_meta($post->ID, '_rb_testimonial_url', true);
			$rb_testimonial_name = get_post_meta($post->ID, '_rb_testimonial_name', true);
			$rb_testimonial_designation = get_post_meta($post->ID, '_rb_testimonial_designation', true);
			$rb_print_meta_box_callback = '';
			$rb_print_meta_box_callback .= '<table class="form-table">';
				$rb_print_meta_box_callback .= '<tr>';
                	$rb_print_meta_box_callback .= '<td>Name</td>';
                    $rb_print_meta_box_callback .= '<td><input type="text" name="_rb_testimonial_name" size="90" value="';
					$rb_print_meta_box_callback .= $rb_testimonial_name;
					$rb_print_meta_box_callback .= '" />';
                $rb_print_meta_box_callback .= '</tr>';
				$rb_print_meta_box_callback .= '<tr>';
                	$rb_print_meta_box_callback .= '<td>Designation</td>';
                    $rb_print_meta_box_callback .= '<td><input type="text" name="_rb_testimonial_designation" size="90" value="';
					$rb_print_meta_box_callback .= $rb_testimonial_designation;
					$rb_print_meta_box_callback .= '" />';
                $rb_print_meta_box_callback .= '</tr>';
                $rb_print_meta_box_callback .= '<tr>';
                	$rb_print_meta_box_callback .= '<td>Company</td>';
                    $rb_print_meta_box_callback .= '<td><input type="text" name="_rb_testimonial_company" size="90" value="';
					$rb_print_meta_box_callback .= $rb_testimonial_company;
					$rb_print_meta_box_callback .= '" /></td>';
                $rb_print_meta_box_callback .= '</tr>';
                $rb_print_meta_box_callback .= '<tr>';
                	$rb_print_meta_box_callback .= '<td>URL</td>';
                    $rb_print_meta_box_callback .= '<td><input type="url" name="_rb_testimonial_url" size="90" value="';
					$rb_print_meta_box_callback .= $rb_testimonial_url;
					$rb_print_meta_box_callback .= '" /></td>';
                $rb_print_meta_box_callback .= '</tr>';
			$rb_print_meta_box_callback .= '</table>';
			
			echo $rb_print_meta_box_callback;
            
		}
		

		
		// Save the Metabox Data
		function responsiveBit_testimonial_save($post_id, $post) {
			// verify this came from the our screen and with proper authorization,
			// because save_post can be triggered at other times
			if ( !wp_verify_nonce( $_POST['testimonial_meta_noncename'], plugin_basename(__FILE__) )) {
			return $post->ID;
			}
			// Is the user allowed to edit the post or page?
			if ( !current_user_can( 'edit_post', $post->ID ))
				return $post->ID;
			// OK, we're authenticated: we need to find and save the data
			// We'll put it into an array to make it easier to loop though.
			$respnosiveBit_testimonial_meta['_rb_testimonial_company'] = $_POST['_rb_testimonial_company'];
			$respnosiveBit_testimonial_meta['_rb_testimonial_url'] = $_POST['_rb_testimonial_url'];
			$respnosiveBit_testimonial_meta['_rb_testimonial_name'] = $_POST['_rb_testimonial_name'];
			$respnosiveBit_testimonial_meta['_rb_testimonial_designation'] = $_POST['_rb_testimonial_designation'];
			// Add values of $events_meta as custom fields
			foreach ($respnosiveBit_testimonial_meta as $key => $value) { // Cycle through the $events_meta array!
				if( $post->post_type == 'revision' ) return; // Don't store custom data twice
				$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
				if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
					update_post_meta($post->ID, $key, $value);
				} else { // If the custom field doesn't have a value
					//echo "shafaat";
					add_post_meta($post->ID, $key, $value);
				}
				if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
			}
		}
		
		
		function responsiveBit_create_testimonials_post_type() {
			register_post_type( 'rb-testimonials',
			array(
				'labels' => array('name' => __( 'Testimonials' ), 
								  'singular_name' => __( 'Testimonials' ),
								  'add_new' => __('Add New', 'Testimonials'),
								  'add_new_item' => __('Add New Testimonial'),
								  'edit_item' => __('Edit Testimonial' ),
								  'new_item' => __('New Testimonial'),
								  'view_item' => __('View Testimonial'),
								  'search_items' => __('Search Testimonial'),
								  'not_found' => __('No Testimonials found'),
								  'not_found_in_trash' => __('No Testimonials found in trash') ),
				'description' => 'A post type for testimonials and it\'s slug is responsive-bit-testimonails',
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_nav_menus' => false,
				'show_in_menu' => true,
				'show_in_admin_bar' => true,
				'supports' => array( 'title', 'editor', 'author'),
				'public' => true,
				'exclude_from_search' => false,
				'has_archive' => true,
				'register_meta_box_cb' => array($this, 'responsiveBit_add_testimonial_metaboxes')
				)
			);
			
		}
		
		function responsiveBit_guide_settings() {
			add_submenu_page( 'edit.php?post_type=rb-testimonials', 'Testimonails usage guide page', 'Testimonials usage guide', 'manage_options', 'responsive_bit_testimonials_guide', array($this,'responsiveBit_generate_testimonials_usage_guide_page') );			
		}
		
		function responsiveBit_generate_testimonials_usage_guide_page() {
			$responsive_bit_testimonail_guide_print = '<center><h1>Testimonails Usage Page Guide</h1></center>';
			echo $responsive_bit_testimonail_guide_print;
			?>
            <h2>Testimonials posts</h2>
			<p>Simply add a Testimonials post uder Testimonial's menu. In rich text editor type the testimonial and in the metabox under rich text editor simply add the Name, Email, Designation, Company and url. Now you are good to go. It's that easy.</p>
            
            <h2>How to style testimonials posts</h2>
            <p>Simply go into the testimonial style submenu under Testimonials menu and set the colors of your own choice using color picker and you are good to go. It's that easy.</p>
            
            <h2>Widget usage</h2>
            <p>Simply drop the <strong>Responsive bit Testimonials Widget</strong> in any widegtized area and set the tittle and number of posts of testimonials. Youa are done. Simple..............!</p>
            
            <h2>Shortcode usage</h2>
            <p>Simply use [testimonials title='new title' no='3'] and set your own values for title and no. It's that simple. </p>
            
            <h2>For Suggestions</h2>
			<p>For any suggestion and bug, kindly feel free to email us at <strong>support@responsivebit.com</strong>. We will be glad to hear your feedback and any suggestions for improving this plugin.</p>
            <?php
		}
		
		function responsiveBit_filter_title_text($title)
		{
			$scr = get_current_screen();
			if ('rb-testimonials' == $scr->post_type)
				$title = 'Enter Testimonial Title here';
			return ($title);
		}
	}
	
	$responsiveBitTestimonials = new ResponsiveBitTestimonials();
	
	//..................................................................
	//............................Widget class..........................
	//..................................................................
	
	class ResponsiveBitTestimonialWidget extends WP_Widget {

		public function __construct() {
			// widget actual processes
			parent::__construct(
				'responsiveBit_testimonial_widget', // Base ID
				'Responsive Bit Testimonial Widget', // Name
				array( 'description' => __( 'to get Testimonials', 'Responsive Bit' ), ) // Args
			);
		}
	
		public function form( $instance ) {
			// outputs the options form on admin
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = __( 'New title', 'Responsive Bit' );
			}
			if ( isset( $instance['testimonials_numbers'] ) ) {
				$testimonials_numbers = $instance['testimonials_numbers'];
			}
			else {
				$testimonials_numbers = __('2','Responsive Bit');
			}
			?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            
            <label for="<?php echo $this->get_field_id('testimonials_numbers'); ?>"><?php _e( 'Number of Testimonials' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'testimonials_numbers' ); ?>" name="<?php echo $this->get_field_name( 'testimonials_numbers' ); ?>" type="text" value="<?php echo esc_attr( $testimonials_numbers ); ?>" />
			</p>
			<?php 
		}
	
		public function update( $new_instance, $old_instance ) {
			// processes widget options to be saved
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['testimonials_numbers'] = strip_tags( $new_instance['testimonials_numbers'] );
			return $instance;
		}
	
		public function widget( $args, $instance ) {
			// outputs the content of the widget
			extract( $args, EXTR_SKIP );
			$title = apply_filters( 'widget_title', $instance['title'] );
			$no = ( $instance['testimonials_numbers'] ) ? $instance['testimonials_numbers'] : 2;
			
			echo $before_widget;
			if ( ! empty( $title ) ) {
				$increment = 0;
				$args = array('post_type' => 'rb-testimonials' , 'showposts' => $no);
				$loop = new WP_Query( $args );
				$output = null;
				?>
				<h1 style="margin:5px 0 30px; font-size: 22px;"><?php echo $title; ?></h1>
				<?php
				while ($loop->have_posts() ) :
				
						$loop->the_post();
						$rb_testimonial_text_color = get_option('testimonials_text_color');
						?>
						<div class="rb_testimonial">
							<blockquote style="color: <?php echo ($rb_testimonial_text_color) ? $rb_testimonial_text_color : ''; ?>">
							<?php echo get_the_content(); ?>					
							<div class="rb_testimonial_other_info">
								<?php 
								$post_id = get_the_ID();
								$rb_testimonial_link = get_post_meta($post_id, '_rb_testimonial_url', true);
								$rb_testimonial_company = get_post_meta($post_id, '_rb_testimonial_company', true);
								$rb_testimonial_url = get_post_meta($post_id, '_rb_testimonial_url', true);
								$rb_testimonial_name = get_post_meta($post_id, '_rb_testimonial_name', true);
								$rb_testimonial_designation = get_post_meta($post_id, '_rb_testimonial_designation', true); 
								
								$rb_testimonial_name_color = get_option('testimonials_name_text_color'); 
								$rb_testimonial_designation_color = get_option('testimonials_designation_text_color'); 
								$rb_testimonial_company_color = get_option('testimonials_company_text_color'); 
								$rb_testimonial_link_color = get_option('testimonials_link_text_color'); ?>
								
								<span style="color: <?php echo ($rb_testimonial_name_color) ? $rb_testimonial_name_color : '333333'; ?>;"> <?php echo $rb_testimonial_name; ?> </span>
								<span style="color: <?php echo ($rb_testimonial_designation_color) ? $rb_testimonial_designation_color : ''; ?>;"> - <?php echo $rb_testimonial_designation; ?> - </span><br />
								<span style="color: <?php echo ($rb_testimonial_company_color) ? $rb_testimonial_company_color : ''; ?>;">	- <?php echo $rb_testimonial_company; ?>  - </span>
								<a style="text-decoration: none; color: <?php echo ($rb_testimonial_link_color) ? $rb_testimonial_link_color : '' ?>;" href="<?php echo $rb_testimonial_url; ?> "><?php echo $rb_testimonial_link; ?></a>
							</div>
							<?php if ($increment != $no - 1) { ?> 
								<div class="divider">
								</div>
								<?php
								$increment++;
							}
							?>
							</blockquote>
						</div>
						
						<?php        
						
				endwhile;
			}
			else {
				echo __( 'Hello, World!', 'Responsive Bit' );
			}
			echo $after_widget;
		}
	
	}
	//register_widget( 'My_Widget' );
	add_action( 'widgets_init', create_function( '', "register_widget('ResponsiveBitTestimonialWidget');" ) );
	
	//.....................................
	add_action('wp_dashboard_setup', 'responsiveBit_testimonial_mycustom_dashboard_widgets');

    function responsiveBit_testimonial_mycustom_dashboard_widgets() {
    global $wp_meta_boxes;

    wp_add_dashboard_widget('responsiveBit_custom_help_widget', 'Responsive Bit FAQ\'s Plugin Support', 'custom_testimonial_dashboard_help');
    }

    function custom_testimonial_dashboard_help() {
    echo '<p><a href="http://www.responsivebit.com"><img src="'. plugins_url('contact_us.jpg', __FILE__) .'" /></a></p><p style="font-size:13px;padding-bottom: 5px;line-height: 22px;"></p><p style="font-size: 13px;padding-bottom: 5px;line-height: 22px;">For any query or any custom work contact us <a href="mailto:support@responsivebit.com">by email</a>. Our email id is <strong>support@responsivebit.com</strong></p>';
    }
	//.....................................

?>
