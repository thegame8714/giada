<?php
/**
 * Created by PhpStorm.
 * User: fabiosalimbeni
 * Date: 02/01/15
 * Time: 15:03
 */

class Shortcodes {

    function __construct() {

        if(is_admin()) {

            $this->admin_init();

        }

        $this->init();

    }

    function admin_init() {


    }

    function init() {

        add_shortcode('section', array($this,'sc_section'));



    }

    function sc_section($atts,$content) {

        extract(shortcode_atts(
            array(
                'id' => '',
                'bgcolor' => 'silver',
                'xtraclass'=>'',
            )
        ,$atts));

        $data .= '<section id="'.$id.'" class="content '.$bgcolor.$xtraclass.'">';

                $data .= '<div class="container">';

                    $data .='<div class="row">';

                        $data .= '<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">';

                            $data .='<div class="sub-container">';

                                $data .= '<div class="content">';

                                    $data .= do_shortcode($content);

                                $data .= '</div>';

                            $data .= '</div>';

                        $data .= '</div>';

                    $data .= '</div>';

               $data .= '</div>';

        $data .= '</section>';

        return $data;


    }



} 