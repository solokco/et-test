<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       mingoagency.com
 * @since      1.0.0
 *
 * @package    Estilotu
 * @subpackage Estilotu/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Estilotu
 * @subpackage Estilotu/admin
 * @author     Carlos Carmona <ccarmona@mingoagency.com>
 */
class Estilotu_Admin {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/estilotu-admin.css', array(), $this->version, 'all' );

	}


	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/estilotu-admin.js', array( 'jquery' ), $this->version, false );

	}
	

	public function validar_dependencias() {
		
		if ( is_plugin_active( 'paid-memberships-pro/paid-memberships-pro.php.php' ) ) {
			deactivate_plugins( 'estilotu/estilotu.php');
		}
	} 
	
		
	/* *************************************************************************** */
	/* AGREGA EL TIPO DE USUARIO  */
	/* *************************************************************************** */
	public function et_registrar_tipo_usuario() {
	    bp_register_member_type( 'profesional', array(
	        'labels' => array(
	            'name'          => 'Profesionales',
	            'singular_name' => 'Profesional',
	        ),
	        'has_directory' => 'profesionales'
	    ) );
	    
	    bp_register_member_type( 'establecimiento', array(
	        'labels' => array(
	            'name'          => 'Establecimientos',
	            'singular_name' => 'Establecimiento',
	        ),
	        'has_directory' => 'establecimientos'
	    ) );
	    
	    bp_register_member_type( 'usuario', array(
	        'labels' => array(
	            'name'          => 'Usuarios',
	            'singular_name' => 'Usuario',
	        ),
	        
	    ) );
	}
	/* *************************************************************************** */
	
	
	/* *************************************************************************** */
	/* VALIDATE  */
	/* *************************************************************************** */
	public function validate($input) {
    // All checkboxes inputs        
	    $valid = array();
	
	    //Cleanup
		/*
	    $valid['cleanup'] = (isset($input['cleanup']) && !empty($input['cleanup'])) ? 1 : 0;
	    $valid['comments_css_cleanup'] = (isset($input['comments_css_cleanup']) && !empty($input['comments_css_cleanup'])) ? 1: 0;
	    $valid['gallery_css_cleanup'] = (isset($input['gallery_css_cleanup']) && !empty($input['gallery_css_cleanup'])) ? 1 : 0;
	    $valid['body_class_slug'] = (isset($input['body_class_slug']) && !empty($input['body_class_slug'])) ? 1 : 0;
	    $valid['jquery_cdn'] = (isset($input['jquery_cdn']) && !empty($input['jquery_cdn'])) ? 1 : 0;
	    $valid['cdn_provider'] = esc_url($input['cdn_provider']);
		*/
	    
	    return $valid;
	}
	
	public function options_update() {
    	// register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}


}
