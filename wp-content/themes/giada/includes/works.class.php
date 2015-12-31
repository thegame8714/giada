<?php

class Works {

    function __construct() {

        if(is_admin()) {

            $this->admin_init();

        }

        $this->init();

    }

    function admin_init() {

        add_action('media_buttons', array($this, 'work_media_btn'), 11);

        add_action('admin_footer', array($this, 'work_options'));

        $this->build_meta_box();

    }

    function init() {

        add_action('init',array($this,'register_work_posttype'));

        add_shortcode('work',array($this,'show_work'));

    }

    function register_work_posttype() {

        $labels = array(
            'name' => __('Works', 'giada'),
            'singular_name' => __('Work', 'giada'),
            'add_new' => __('Add New Work', 'giada'),
            'add_new_item' => __('Add New Work', 'giada'),
            'edit_item' => __('Edit Work', 'giada'),
            'new_item' => __('New Work', 'giada'),
            'view_item' => __('View Work', 'giada'),
            'search_items' => __('Search Works', 'giada'),
            'not_found' => __('No Works Found', 'giada'),
            'not_found_in_trash'=> __('No Works Found in Trash', 'giada'),
            'parent_item_colon' => __('works', 'giada'),
            'menu_name' => __('Works', 'giada')
        );

        $args = array(
            'labels' => $labels,
            'singular_label' => __('Work', 'giada'),
            'public' => false,
            'show_ui' => true,
            'publicly_queryable'=> false,
            'exclude_from_search' => true,
            'query_var'  => true,
            'menu_icon' => 'dashicons-format-gallery',
            'has_archive' => false,
            'hierarchical' => false,
            'rewrite' => false,
            'supports' => array(
                'title',
                'thumbnail',
                'editor',
                'revisions'
            ),
            'menu_position'  => 6
        );

        register_post_type('work', $args);

    }

    function build_meta_box() {

        $meta = new MetaBox();

        $meta->meta_box_info = array(
            array(
                'post_type' => 'work',
                'boxes' => array(
                    array(
                        'name' => __('Work list','giada'),
                        'id' => 'picture_list',
                        'position' => 'normal',
                        'fields' => array(
                            array(
                                'name' => __ ('Image','giada'),
                                'desc' => __('Insert the image','giada'),
                                'id' => 'single_image',
                                'class' => 'single_image',
                                'sortable' => true,
                                'type' => 'repeatableupload',
                            )
                        )
                    )
                )
            )
        );

    }

    function get_works($output_type = null) {

        $args = array(
            'post_type' => 'work',
            'post_status' => 'publish',
            'posts_per_page' => '-1'
        );

        $works = get_posts($args);

        switch ($output_type) {

            case 'dropdown':

                $output .= '<select class="work_list">';

                    $output .= '<option class="work_item" name="work_item" value="undefined"><span class="work_item_title">Select a work..</span></option>';

                    foreach($works as $work) {

                        $output .= '<option class="work_item" name="work_item" value="'.$work->ID.'"><span class="work_item_title">'.$work->post_title.'</span></option>';

                    }

                $output .= '</select>';

                break;

            case 'checkbox':

                $output .= '<div class="work_list">';

                foreach($works as $work) {

                    $output .= '<div class="work_list_item"><input type="checkbox" class="work_item" name="work_item" value="'.$work->ID.'"><span class="work_item_title">'.$work->post_title.'</span></div>';

                }

                $output .= '</div>';

                break;

            default:

                $output = $works;

                break;

        }

        return $output;

    }


    function work_media_btn() {

        $popup_id = 'work-popup';

        $title = 'Work Options';

        printf('<a title="%1$s" href="%2$s" class="thickbox button add_media work-button">Insert Work</span></a>', esc_attr($title),esc_url('#TB_inline?width=480&height=450&inlineId='.$popup_id));

    }

    function work_options() {

        $this->work_js();

        $data .= '<div style="display:none;" id="work-popup">';

        $data .= '<h1>Choose from the following options</h1>';

        $data .= '<form id="insert-work-block" method="post" action="">';

        $data .= '<hr />';

        $data .= '<label for="work_list">Which category?</label><br /><br />';

        $data .= $this->get_works('checkbox');

        $data .= '<hr />';

        $data .= '<input type="submit" class="button-primary" id="work_submit" value="Insert shortcode" /> <a class="button" style="color:#bbb;" onclick="tb_remove(); return false;">Cancel</a>';

        $data .= '</form>';

        $data .= '</div>';

        echo $data;

    }

    function work_js() {
        ?>
        <script>

            jQuery(document).ready(function() {

                jQuery('#work_submit').click(function(){

                    InsertWorkShortcode();

                    return false;

                });

            });

            function InsertWorkShortcode() {

                var work = getWorkValue();

                if(work) {

                    work = ' work_items="'+work+'"';

                }


                window.send_to_editor('[work '+work+']');
            }

            function getWorkValue(){

                /* declare an checkbox array */
                var chkArray = [];

                /* look for all checkboes that have a class 'chk' attached to it and check if it was checked */
                jQuery(".work_item:checked").each(function() {
                    chkArray.push(jQuery(this).val());
                });

                /* we join the array separated by the comma */
                var selected;
                selected = chkArray.join(',');
                return selected;


            }

        </script>
    <?php
    }

    function show_work($atts) {

        extract(shortcode_atts(
            array(
                'slider' => false,
                'work_items' => 0,
            )
            ,$atts));

        if($id == 0) {

            $args = array(
                'post_type' => 'work',
                'posts_per_page' => '-1',
                'post_status' => 'publish'
            );

        }
        else {

            $args = array(
              'post_type' => 'work',
              'post_status' => 'publish',
              'post__in' => $id
            );
        }

        $works = get_posts($args);


        if($slider) {

            wp_enqueue_script('bxslider');
        }

          $output .= $this->output_works($works);

        return $output;

    }

    function output_works($works) {

        $count = 0;

        $output .= '<div class="work_wrapper">';

        foreach($works as $work) {

            $work_gallery_images = get_post_meta($work->ID,'single_image',true);

                $output .= '<div class="work_container">';

                    $output .= '<div class="row work_content">';

                        $output .= '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">';

                            $output .= '<div class="work_image">';

                                $main_img = wp_get_attachment_image_src( get_post_thumbnail_id( $work->ID), 'full' );

                                $output .= '<img class="img work_lg_image" src="'.$main_img[0].'" height="460px">';

                            $output .= '</div>';

                        $output .= '</div>'; //close work_image

                        $output .= '<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">';

                            $output .= '<div class="work_details">';

                                $output .= '<div class="row work_text_details_wrapper">';

                                    $output .= '<div class="col-md-12 work_text_details">';

                                        $output .= $work->post_content;

                                    $output .= '</div>';

                                $output .= '</div>';

                            $output .= '</div>';

                            $output .= '<div class="row work_gallery_wrapper">';

                                $output .= '<div class="col-md-12 work_gallery">';

                                $output .= '<div class="col-md-2 work_gallery_single_image">';

                                    $output .= '<img class="img" src="'.$main_img[0].'" height="124px">';

                                $output .= '</div>';

                                foreach($work_gallery_images as $work_gallery_single) {

                                    $output .= '<div class="col-md-2 work_gallery_single_image">';

                                        $output .= '<img class="img" src="'.trim($work_gallery_single).'" height="124px">';

                                    $output .= '</div>';

                                    }

                                $output .= '</div>';

                            $output .= '</div>';

                        $output .= '</div>'; //close work_details

                    $output .= '</div>'; //close work_content

            if($count < 1) {

                $output .= '<div class="slider-pager">';

            }

            $output .= '<a data-slide-index="'.$count.'" href=""><span class="fa fa-diamond"></span></a>';


            if($count < 1) {


                $output .= '</div>';

            }

            $output .= '</div>'; //close work_container;



            $count++;

        }

        $output .= '</div>';

        return $output;

    }


    function footer_images() {

        $args = array(
            'posts_per_page' => '-1',
            'post_type' => 'work',
            'post_publish' => 'published'
        );

        $works = get_posts($args);

        //var_dump($works);

        //TO DO: Create the random selection of the images and position the images on the footer


    }

} 