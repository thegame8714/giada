<?php

/**
 * MetaBox class.
 */
class MetaBox {

	var $meta_box_info;
	
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
	
		$this->meta_box_info = array();

	}

	/**
	 * admin_init function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_init() {
		
		add_action('add_meta_boxes', array($this, 'add_new_meta_box'));
		
		add_action('save_post', array($this, 'save_meta_fields'));
		
		add_action('admin_head', array($this, 'load_scripts'));
		
	}
	
	/**
	 * load_scripts function.
	 * 
	 * @access public
	 * @return void
	 */
	function load_scripts() {
	
		global $pagenow;
	
		$meta_boxes = $this->meta_box_info;
		
		foreach($meta_boxes as $meta_box) {
		
			$post_type = $meta_box['post_type'];
			
			if($pagenow == 'post-new.php' && $_GET['post_type'] == $post_type) {
		
				wp_enqueue_script( 'metabox', get_bloginfo('template_directory') . '/includes/admin_js/metabox.js', array( 'jquery' ), null, true );
			
			}
			
			if($pagenow == 'post.php') {
			
				$this_post_type = get_post_type($_GET['post']);
				
				if($this_post_type == $post_type) {
				
					wp_enqueue_script( 'metabox', get_bloginfo('template_directory') . '/includes/admin_js/metabox.js', array( 'jquery' ), null, true );
				
				}
				
			}
		
		}
		
	}

	/**
	 * add_new_meta_box function.
	 *
	 * @access public
	 * @return void
	 */
	function add_new_meta_box() {

		$meta_boxes = $this->meta_box_info;
		
		foreach($meta_boxes as $meta_box) {
		
			foreach($meta_box['boxes'] as $box) {
			
				$post_type = $meta_box['post_type'];
				
				if(is_array($post_type)) {
				
					foreach($post_type as $type) {
						
						add_meta_box($box['id'].'_meta', $box['name'], array($this, 'show_meta_box'), $type, $box['position'], 'high', array('fields' => $box['fields'], 'box_id' => $box['id']));
						
					}
					
				} else {
			
					add_meta_box($box['id'].'_meta', $box['name'], array($this, 'show_meta_box'), $post_type, $box['position'], 'high', array('fields' => $box['fields'], 'box_id' => $box['id']));
				
				}
				
			}
			
		}

	}

	/**
	 * show_meta_box function.
	 *
	 * @access public
	 * @return void
	 */
	function show_meta_box($post, $box) {

		$data .= '<input type="hidden" name="'.$box['args']['box_id'].'_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

		$data .= '<table class="form-table">';
		
			$data .= $this->meta_fields($box['args']['fields'], $post);
		
		$data .= '</table>';
		
		echo $data;
		
	}

	function load_editor($id) {

		$args = array(
			'textarea_name' => 'name'.$id,
			'textarea_rows' => 20,
			'wpautop' => false
		);
						
		ob_start();
		
		wp_editor(null, 'editor'.$id, $args);

		$editor = ob_get_contents();

		ob_end_clean();

		$data .= '<div class="repeatable-editor-bg" id="editor-bg-'.$id.'" style="position:fixed;display:none;z-index:100050;background:#000;opacity:0.3;top:0;left:0;right:0;bottom:0;filter:alpha(opacity=70);"></div>';
		
		$data .= '<div class="repeatable-editor-inner" id="editor-inner-'.$id.'" style="position:fixed;display:none;z-index:100050;width:670px;top:52px;padding:30px;background:#fff;">';

			$data .= '<form id="form-repeatableeditor-'.$id.'" method="post" action="">';
		
				$data .= $editor;

				$data .= '<hr />';

				$data .= '<input type="submit" class="button-primary save-editor-content" id="submit-repeatableeditor" value="Insert content" /> <a class="button close-editor-window" style="color:#bbb">Cancel</a>';

			$data .= '</form>';

		$data .= '</div>';

		return $data;

	}

	/**
	 * meta_fields function.
	 *
	 * @access public
	 * @param mixed $arr
	 * @param mixed $post
	 * @param mixed $prefix
	 * @param mixed $wp_version
	 * @return void
	 */
	function meta_fields($arr, $post) {

		$sortable_bar = '<span class="sort-bar" style="display:block;height:20px;border:solid 1px #ccc;background:#f0f0f0;padding:10px;margin:10px 0">Drag to re-order</span>';

		foreach ($arr as $meta_box_field) {

			$meta = get_post_meta($post->ID, $meta_box_field['id'], true);

			if($meta_box_field['rich_editor'] == 1) {

				$class = "rich-editor";

			}
			
			$data .= '<tr class="'.$meta_box_field['class']. '">';

			if ($meta_box_field['name']) {

				$data .= '<th style="width:20%"><label for="'.$meta_box_field['id'].'">'. stripslashes($meta_box_field['name']).'</label></th>';

			}
			
			$data .= '<td class="field_type_'.str_replace(' ', '_', $meta_box_field['type']).' '.$class.'">';

				switch ($meta_box_field['type']) {
	
					case 'text' :
		
						$data .= '<input type="text" name="'.$meta_box_field['id'].'" class="'.$meta_box_field['class']. '" value="'.$meta.'" style="width:97%" /><br />'.''.stripslashes($meta_box_field['desc']);
		
					break;
		
					case 'textarea' :
		
						if ($meta_box_field['rich_editor'] == 1) {
						
							ob_start();
							
							wp_editor($meta, $meta_box_field['id'], array('textarea_name' => $meta_box_field['id']));
							
							$data .= ob_get_contents();
							
							ob_end_clean();
		
						} else {
		
							$data .= '<textarea name="'.$meta_box_field['id'].'" class="'.$meta_box_field['class'].' text" class="'.$meta_box_field['class']. '" rows="8" style="width:97%">'.$meta.'</textarea>'.'<br />'.stripslashes($meta_box_field['desc']);
		
						}
		
					break;
						
					case 'checkbox':
					
						$data .= '<input type="checkbox" name="'.$meta_box_field['id'].'" class="'.$meta_box_field['class']. '"';
						if($meta == 'on') {
						
							$data .= ' checked="checked"';
							
						}
						
						$data .= ' /><br />'.stripslashes($meta_box_field['desc']);
						
					break;
					
					case 'select':
										
						$data .= '<select name="'.$meta_box_field['id'].'" style="width: 97%">';
						
						$data .= '<option value="" selected="selected">Please select an option</option>';
						
						foreach ($meta_box_field['options'] as $option) {
						
							if($meta == $option['val']) {
							
								$data .= '<option value="'.$option['val'].'" selected="selected">'. $option['label']. '</option>';
								
							} else {
						
								$data .= '<option value="'.$option['val'].'">'. $option['label']. '</option>';
							
							}
							
						}
						
						$data .= '</select><br />'.stripslashes($meta_box_field['desc']);
						
					break;
						
					case 'upload' :

						if(!isset($meta_box_field['button_text'])) {

							$button_text = 'Upload Image';

						} else {

							$button_text = $meta_box_field['button_text'];

						}
					
						$data .= '<input type="text" class="upload_field" name="'. $meta_box_field['id']. '" value="'. $meta . '" size="30" style="width:80%" /><input class="upload_image_button button-secondary" type="button" value="'.$button_text.'" /><br />'. stripslashes($meta_box_field['desc']);
						
					break;
						
					case 'repeatable' :
		
						$data .= '<input type="hidden" id="' . $meta_box_field['id'] . '" class="repeatable_field_name" value=""/>';

						if($meta_box_field['sortable'] == true) {

							$data .= '<div class="sortable">';

						}
						
						if(is_array($meta)) {
						
							$count = 1;
							
							foreach($meta as $key => $value) {
							
								$data .= '<div class="repeatable_wrapper">';

									$data .= '<input type="text" class="repeatable_field" name="' . $meta_box_field['id'] . '[]" value="' . $meta[$key] . '" style="width:90%" />';
																		
									$data .= '<a href="#" class="remove_repeatable button-secondary">x</a><br />';
										
									if($meta_box_field['sortable'] == true) {

										$data .= $sortable_bar;

									}
								
								$data .= '</div>';
								
								$count++;
								
							}
							
						} else {
						
							$data .= '<div class="repeatable_wrapper">';

								$data .= '<input type="text" class="repeatable_field" name="' . $meta_box_field['id'] . '[]" value="' . $meta . '" style="width:100%" />';

								if($meta_box_field['sortable'] == true) {

									$data .= $sortable_bar;

								}

							$data .= '</div>';
							
						}
						
						$data .= '<button class="add_new_field button-secondary">Add New</button><br />' . stripslashes($meta_box_field['desc']);

						if($meta_box_field['sortable'] == true) {

							$data .= $sortable_bar;

							$data .= '</div>';

						}
		
					break;
					
					case 'repeatableselect' :
		
						$data .= '<input type="hidden" id="' . $meta_box_field['id'] . '" class="repeatable_field_name" value=""/>';

						if($meta_box_field['sortable'] == true) {

							$data .= '<div class="sortable">';

						}
						
						if(is_array($meta)) {
						
							$count = 1;
							
							foreach($meta as $key => $value) {
							
								$data .= '<div class="repeatable_select_wrapper">';
								
									$data .= '<select name="'.$meta_box_field['id'].'[]" style="width: 97%">';
									
										$data .= '<option value="" selected="selected">Please select an option</option>';
								
										foreach ($meta_box_field['options'] as $option) {
										
											if($meta[$key] == $option['val']) {
									
												$data .= '<option value="'.$option['val'].'" selected="selected">'. $option['label']. '</option>';
												
											} else {
										
												$data .= '<option value="'.$option['val'].'">'. $option['label']. '</option>';
											
											}	
																			
										}
									
									$data .= '</select>';
																		
									$data .= '<a href="#" class="remove_repeatable_select button-secondary">x</a><br />';
										
									if($meta_box_field['sortable'] == true) {

										$data .= $sortable_bar;

									}
								
								$data .= '</div>';
								
								$count++;
								
							}
							
						} else {
						
							$data .= '<div class="repeatable_select_wrapper">';
							
								$data .= '<select name="'.$meta_box_field['id'].'[]" style="width: 97%">';
								
									$data .= '<option value="" selected="selected">Please select an option</option>';
								
									foreach ($meta_box_field['options'] as $option) {
										
										if($meta[$key] == $option['val']) {
								
											$data .= '<option value="'.$option['val'].'" selected="selected">'. $option['label']. '</option>';
											
										} else {
									
											$data .= '<option value="'.$option['val'].'">'. $option['label']. '</option>';
										
										}	
																		
									}
								
								$data .= '</select>';

								if($meta_box_field['sortable'] == true) {

									$data .= $sortable_bar;

								}
							
							$data .= '</div>';
							
						}
						
						$data .= '<button class="add_new_select_field button-secondary">Add New</button><br />' . stripslashes($meta_box_field['desc']);

						if($meta_box_field['sortable'] == true) {

							$data .= '</div>';

						}
		
					break;
					
					case 'coursetabselect' :
		
						$data .= '<input type="hidden" id="' . $meta_box_field['id'] . '" class="repeatable_field_name" value=""/>';

						if($meta_box_field['sortable'] == true) {

							$data .= '<div class="sortable">';

						}
						
						if(is_array($meta)) {
						
							$count = 1;
							
							foreach($meta as $key => $value) {
							
								$data .= '<div class="repeatable_select_wrapper">';
								
									$data .= '<select name="'.$meta_box_field['id'].'[]" style="width: 97%">';
									
										$data .= '<option value="" selected="selected">Please select an option</option>';
								
										foreach ($meta_box_field['options'] as $option) {
										
											if($meta[$key] == $option['val']) {
									
												$data .= '<option value="'.$option['val'].'" selected="selected">'. $option['label']. '</option>';
												
											} else {
										
												$data .= '<option value="'.$option['val'].'">'. $option['label']. '</option>';
											
											}	
																			
										}
									
									$data .= '</select>';
																		
									$data .= '<a href="#" class="remove_repeatable_select button-secondary">x</a><br />';
										
									if($meta_box_field['sortable'] == true) {

										$data .= $sortable_bar;

									}
								
								$data .= '</div>';
								
								$count++;
								
							}
							
						} else {
						
							$data .= '<div class="repeatable_select_wrapper">';
							
								$data .= '<select name="'.$meta_box_field['id'].'[]" style="width: 97%">';
								
									$data .= '<option value="" selected="selected">Please select an option</option>';
								
									foreach ($meta_box_field['options'] as $option) {
										
										if($meta[$key] == $option['val']) {
								
											$data .= '<option value="'.$option['val'].'" selected="selected">'. $option['label']. '</option>';
											
										} else {
									
											$data .= '<option value="'.$option['val'].'">'. $option['label']. '</option>';
										
										}	
																		
									}
								
								$data .= '</select>';

								if($meta_box_field['sortable'] == true) {

									$data .= $sortable_bar;

								}
							
							$data .= '</div>';
							
						}
						
						$data .= '<button class="add_new_select_field button-secondary">Add New</button><br />' . stripslashes($meta_box_field['desc']);

						if($meta_box_field['sortable'] == true) {

							$data .= '</div>';

						}
		
					break;
					
					case 'repeatabletextarea' :
		
						$data .= '<input type="hidden" id="' . $meta_box_field['id'] . '" class="repeatable_textarea_name" value=""/>';

						$data .= $this->load_editor($meta_box_field['id']);

						if($meta_box_field['sortable'] == true) {

							$data .= '<div class="sortable">';

						}
						
						if(is_array($meta)) {
						
							$count = 1;
							
							foreach($meta as $key => $value) {
							
								$data .= '<div class="repeatable_textarea_wrapper">';

									$data .= '<textarea name="'.$meta_box_field['id'].'[]" data-editor-id="'.$meta_box_field['id'].'" class="'.$meta_box_field['class'].' repeatable_textarea_field text" rows="8" style="width:97%">'.$meta[$key].'</textarea>';
																									
									$data .= '<a href="#" class="remove_repeatable button-secondary">x</a><br />';
										
									if($meta_box_field['sortable'] == true) {

										$data .= $sortable_bar;

									}
								
								$data .= '</div>';
								
								$count++;
								
							}
							
						} else {
						
							$data .= '<div class="repeatable_textarea_wrapper">';

								$data .= '<textarea name="'.$meta_box_field['id'].'[]" data-editor-id="'.$meta_box_field['id'].'" class="'.$meta_box_field['class'].' repeatable_textarea_field text" rows="8" style="width:97%">'.$meta.'</textarea>';

								if($meta_box_field['sortable'] == true) {

									$data .= $sortable_bar;

								}

							$data .= '</div>';
													
						}
						
						$data .= '<button class="add_new_textarea_field button-secondary">Add New</button><br />' . stripslashes($meta_box_field['desc']);

						if($meta_box_field['sortable'] == true) {

							$data .= '</div>';

						}
		
					break;
					
					case 'repeatabletextareawithtitle' :
		
						$data .= '<input type="hidden" id="' . $meta_box_field['id'] . '" class="repeatable_uploadwithtitle_field_name" value=""/>';

						$data .= $this->load_editor($meta_box_field['id']);

						if($meta_box_field['sortable'] == true) {

							$data .= '<div class="sortable">';

						}
												
						if($meta) {
						
							$count = 1;
							
							foreach($meta as $field) {
							
								$data .= '<div class="repeatable_textareawithtitle_wrapper">';
								
									$data .= '<input type="text" class="repeatable_textareawithtitle_field title_field" name="' . $meta_box_field['id'] . '[]" value="' . $field['title'] . '" style="width:100%" />';
									
									$data .= '<textarea class="repeatable_textareawithtitle_field textarea_field text" data-editor-id="'.$meta_box_field['id'].'" name="' . $meta_box_field['id'] . '[]" rows="10" style="width:100%">' . $field['textarea'] . '</textarea>';
																										
									$data .= '<a href="#" class="remove_repeatable button-secondary">x</a><br />';
										
									if($meta_box_field['sortable'] == true) {

										$data .= $sortable_bar;

									}
									
								$data .= '</div>';
								
								$count++;
								
							}
							
						} else {
						
							$data .= '<div class="repeatable_textareawithtitle_wrapper">';
							
								$data .= '<input type="text" class="repeatable_textareawithtitle_field title_field" name="' . $meta_box_field['id'] . '[]" value="" style="width:100%" />';
									
									$data .= '<textarea class="repeatable_textareawithtitle_field textarea_field text" data-editor-id="'.$meta_box_field['id'].'" name="' . $meta_box_field['id'] . '[]" rows="10" style="width:100%"></textarea>';
															
								if($meta_box_field['sortable'] == true) {

									$data .= $sortable_bar;

								}

							$data .= '</div>';
							
						}
						
						$data .= '<button class="add_new_textareawithtitle_field button-secondary">Add New</button><br />' . stripslashes($meta_box_field['desc']);

						if($meta_box_field['sortable'] == true) {

							$data .= '</div>';

						}
		
					break;
							
					case 'repeatableupload' :
		
						$data .= '<input type="hidden" id="' . $meta_box_field['id'] . '" class="repeatable_upload_field_name" value=""/>';

						if($meta_box_field['sortable'] == true) {

							$data .= '<div class="sortable">';

						}
						
						if(is_array($meta)) {
						
							$count = 1;
							
							foreach($meta as $key => $value) {
							
								$data .= '<div class="repeatable_upload_wrapper">';

									$data .= '<input type="text" class="repeatable_upload_field upload_field" name="' . $meta_box_field['id'] . '[]" value="' . $meta[$key] . '" style="width:80%" /><button class="button-secondary upload_image_button">Upload File</button>';
																		
									$data .= '<a href="#" class="remove_repeatable button-secondary">x</a><br />';
										
									if($meta_box_field['sortable'] == true) {

										$data .= $sortable_bar;

									}
								
								$data .= '</div>';
								
								$count++;
								
							}
							
						} else {
						
							$data .= '<div class="repeatable_upload_wrapper">';

								$data .= '<input type="text" class="repeatable_upload_field upload_field" name="' . $meta_box_field['id'] . '[]" value="' . $meta . '" style="width:80%" /><input class="button-secondary upload_image_button" type="button" value="Upload File" />';

								if($meta_box_field['sortable'] == true) {

									$data .= $sortable_bar;

								}

							$data .= '</div>';
							
						}
						
						$data .= '<button class="add_new_upload_field button-secondary">Add New</button><br />' . stripslashes($meta_box_field['desc']);

						if($meta_box_field['sortable'] == true) {

							$data .= '</div>';

						}
		
					break;
					
					case 'repeatableuploadwithtitle' :
		
						$data .= '<input type="hidden" id="' . $meta_box_field['id'] . '" class="repeatable_uploadwithtitle_field_name" value=""/>';

						if($meta_box_field['sortable'] == true) {

							$data .= '<div class="sortable">';

						}
												
						if($meta) {
						
							$count = 1;
							
							foreach($meta as $field) {
							
								$data .= '<div class="repeatable_upload_wrapper">';
								
									$data .= '<input type="text" class="repeatable_upload_field title_field" name="' . $meta_box_field['id'] . '[]" value="' . $field['title'] . '" style="width:50%" />';
									
									$data .= '<input type="text" class="repeatable_upload_field upload_field" name="' . $meta_box_field['id'] . '[]" value="' . $field['file'] . '" style="width:30%" />';
									
									$data .= '<button class="button-secondary upload_image_button">Upload File</button>';
									
									$data .= '<a href="#" class="remove_repeatable button-secondary">x</a><br />';
										
									if($meta_box_field['sortable'] == true) {

										$data .= $sortable_bar;

									}
								
								$data .= '</div>';
								
								$count++;
								
							}
							
						} else {
						
							$data .= '<div class="repeatable_upload_wrapper">';
							
								$data .= '<input type="text" class="repeatable_upload_field title_field" name="' . $meta_box_field['id'] . '[]" value="" style="width:50%" />';
								
								$data .= '<input type="text" class="repeatable_upload_field upload_field" name="' . $meta_box_field['id'] . '[]" value="" style="width:30%" />';
								
								$data .= '<input class="button-secondary upload_image_button" type="button" value="Upload File" />';

								if($meta_box_field['sortable'] == true) {

									$data .= $sortable_bar;

								}
							
							$data .= '</div>';
							
						}
						
						$data .= '<button class="add_new_upload_field button-secondary">Add New</button><br />' . stripslashes($meta_box_field['desc']);

						if($meta_box_field['sortable'] == true) {

							$data .= '</div>';

						}
		
					break;
						
				}

			$data .= '<td></tr>';

		}
		
		return $data;

	}
	
	/**
	 * save meta fields function.
	 *
	 * @access public
	 * @param mixed $post_id
	 * @return void
	 */
	function save_meta_fields($post_id) {

		global $post;
		
		$meta_boxes = $this->meta_box_info;
				
		foreach($meta_boxes as $meta_box) {
			
			foreach($meta_box['boxes'] as $box) {
	
				if (!isset( $_POST[$box['id'].'_meta_box_nonce'])||!wp_verify_nonce($_POST[$box['id'].'_meta_box_nonce'], basename(__FILE__))) {
		
					return $post_id;
		
				}
		
				if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		
					return $post_id;
		
				}
		
				if ('page' == $_POST['post_type']) {
		
					if (!current_user_can('edit_page', $post_id)) {
		
						return $post_id;
		
					}
		
				} elseif (!current_user_can('edit_post', $post_id)) {
		
					return $post_id;
		
				}
						
				foreach ($box['fields'] as $meta_box_field) {
		
					$old = get_post_meta($post_id, $meta_box_field['id'], true);
					
					$new = $_POST[$meta_box_field['id']];

					$find = array(
						'<p>',
						'</p>'
					);

					$replace = array(
						'',
						PHP_EOL
					);

					$new = str_replace($find, $replace, $new);

					if($new) {
					
						if($meta_box_field['type'] == 'repeatableuploadwithtitle') {
							
							$new_val = array();
							
							$count = 1;
							
							$this_count = 1;
							
							foreach($new as $item) {
														
								if($this_count == 3) {
							
									$this_count = 1;
								
								}
								
								if($this_count == 1) {
															
									$new_val[$count]['title'] = $item;
								
								}
								
								if($this_count == 2) {
								
									$new_val[$count]['file'] = $item;
									
									if(($new_val[$count]['title'] != null) && ($new_val[$count]['file'] != null)) {
									
										$count++;
									
									}
								
								}
								
								$this_count++;
								
							}
													
							$new = $new_val;
							
						}
						
						if($meta_box_field['type'] == 'repeatabletextareawithtitle') {
							
							$new_val = array();
							
							$count = 1;
							
							$this_count = 1;
							
							foreach($new as $item) {
														
								if($this_count == 3) {
							
									$this_count = 1;
								
								}
								
								if($this_count == 1) {
															
									$new_val[$count]['title'] = $item;
								
								}
								
								if($this_count == 2) {
								
									$new_val[$count]['textarea'] = $item;
									
									if(($new_val[$count]['title'] != null) && ($new_val[$count]['textarea'] != null)) {
									
										$count++;
									
									}
								
								}
								
								$this_count++;
								
							}
													
							$new = $new_val;
							
						}

						if ($new && $new != $old) {
			
							$id = update_post_meta($post_id, $meta_box_field['id'], $new);
			
						} elseif ('' == $new && $old) {
			
							$id = delete_post_meta($post_id, $meta_box_field['id'], $old);
			
						}

					} else {

						$id = delete_post_meta($post_id, $meta_box_field['id'], $old);
					}
							
				}
			
			}
		
		}

	}
	
}