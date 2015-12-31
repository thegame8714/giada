<?php

/**
 * Core class.
 */
class Core {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	function __construct() {
	
		if (is_admin()) {

			$this->admin_init();
			
		}

		$this->init();
		
	}
	
	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	function init() {

		$this->register_navs();
		$this->register_sidebars();
        $this->add_img_size();
		add_action('widgets_init', array($this, 'register_widgets'));
		add_theme_support('post-thumbnails');
		add_action('wp_enqueue_scripts', array($this, 'load_core_js'));
		add_action('wp_enqueue_scripts', array($this, 'load_core_css'));
		add_action('init', array($this, 'removeHeadLinks'));
		header("X-UA-Compatible: IE=EDGE");
		add_filter('the_content', array($this, 'shortcode_empty_paragraph_fix'));
		add_filter('next_posts_link_attributes', array($this, 'next_posts_link_attributes'));
		
	}
		
	/**
	 * admin_init function.
	 * 
	 * @access public
	 * @return void
	 */
	function admin_init() {
			
	}
	
	/**
	 * enqueue_core_js function.
	 * 
	 * @access public
	 * @return void
	 */
	function load_core_js() {
	
		wp_deregister_script('jquery');
		wp_register_script('jquery',get_bloginfo('template_directory').'/assets/js/jquery.min.js', false, null);
		wp_enqueue_script('jquery');
		wp_enqueue_script('bootstrap',get_bloginfo('template_directory').'/assets/js/bootstrap.min.js',dirname(__FILE__),array('jquery'),true);
		wp_register_script('bxslider',get_bloginfo('template_directory').'/assets/js/jquery.bxslider.min.js',dirname(__FILE__),array('jquery'),true);
        wp_register_script('app',get_bloginfo('template_directory').'/assets/js/app.js',dirname(__FILE__),array('jquery'),true);
		wp_localize_script('app','ajax_url',array('ajaxurl'=>admin_url('admin-ajax.php')));
		wp_enqueue_script('app');
		
	}
	
	/**
	 * load_core_css function.
	 * 
	 * @access public
	 * @return void
	 */
	function load_core_css() {
	
		wp_enqueue_style('bootstrap',get_bloginfo('template_directory').'/assets/css/bootstrap.min.css');
        wp_register_style('fontawesome', get_bloginfo('template_directory').'/assets/css/font-awesome.css', '', 0,'all' );
        wp_enqueue_style('fontawesome');

        wp_register_style('Josefin','http://fonts.googleapis.com/css?family=Josefin+Sans&subset=latin,latin-ext');
        wp_register_style('Architects','http://fonts.googleapis.com/css?family=Architects+Daughter');
        wp_enqueue_style('Josefin');
        wp_enqueue_style('Architects');
        wp_enqueue_style('app',get_bloginfo('template_directory').'/assets/css/app.css');
		
	}

	function site_title() {

		if(is_page() || is_single()) {

			global $post;

			$data .= $post->post_title.' | ';
		}

		if(is_tax()) {

			global $wp_query;

			$tax = $wp_query->queried_object;
			$data .= $tax->name;

		}

		$data .= get_bloginfo('title').' | '.get_bloginfo('description');

		return $data;
	}
	
	/**
	 * removeHeadLinks function.
	 * 
	 * @access public
	 * @return void
	 */
	function removeHeadLinks() {
	
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head','wp_generator');
		
	}	
	
	/**
	 * register_navs function.
	 * 
	 * @access public
	 * @return void
	 */
	function register_navs() {
		
		register_nav_menu('top','Top Nav');
		register_nav_menu('language','Language Nav');
		register_nav_menu('primary','Primary Nav');
        register_nav_menu('footer_1','Footer first column');
        register_nav_menu('footer_2','Footer second column');
        register_nav_menu('footer_3','Footer third column');
        register_nav_menu('last_menu','Last menu');
		
	}
	
	/**
	 * register_sidebars function.
	 * 
	 * @access public
	 * @return void
	 */
	function register_sidebars() {
				
	}
	
	/**
	 * register_widgets function.
	 * 
	 * @access public
	 * @return void
	 */
	function register_widgets() {
				
	}
				
	/**
	 * shortcode_empty_paragraph_fix function.
	 * 
	 * @access public
	 * @param mixed $content
	 * @return void
	 */
	function shortcode_empty_paragraph_fix($content)
	{  
	    $array = array (
	        '<p>[' => '[',
	        ']</p>' => ']',
	        ']<br />' => ']',
	        ']<br>' => ']'
	    );
	 
	    $content = strtr($content, $array);
	 
	    return $content;
	}
	
	/**
	 * next_posts_link_attributes function.
	 * 
	 * @access public
	 * @return void
	 */
	function next_posts_link_attributes() {
		
		return 'class="pull-right"';
		
	}



    /**
     * add_img_size function.
     *
     * @access public
     * @return void
     */
    function add_img_size() {

        add_image_size('home_button_icon',50,50,false);
        add_image_size('testimonial',200,200,false);
        add_image_size('caption',362,200,false);
    }
	
}