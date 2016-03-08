<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       mingoagency.com
 * @since      1.0.0
 *
 * @package    Estilotu
 * @subpackage Estilotu/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Estilotu
 * @subpackage Estilotu/public
 * @author     Carlos Carmona <ccarmona@mingoagency.com>
 */
class Estilotu_Public {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/css/estilotu-public.css', array(), $this->version, 'all' );
		
		wp_enqueue_style( 'smart-forms'			, plugin_dir_url( __FILE__ ) . 'assets/css/forms/smart-forms.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'smart-forms-min'		, plugin_dir_url( __FILE__ ) . 'assets/css/forms/font-awesome.min.css', array('smart-forms'), $this->version, 'all' );
		wp_enqueue_style( 'smart-forms-purple'	, plugin_dir_url( __FILE__ ) . 'assets/css/forms/smart-themes/purple.css', array('smart-forms' , 'smart-forms-min'), $this->version, 'all' );
		wp_enqueue_style( 'smart-forms-addons'	, plugin_dir_url( __FILE__ ) . 'assets/css/forms/smart-addons.css', array('smart-forms' , 'smart-forms-min'), $this->version, 'all' );
		
		wp_register_style( 'et_datetimepicker'	, plugin_dir_url( __FILE__ ) . 'assets/css/servicios/jquery.datetimepicker.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/js/estilotu-public.js', array( 'jquery' ), $this->version, false );
		
		wp_register_script( 'et-jquery-form-min'	, plugin_dir_url( __FILE__ ) 		. 'assets/js/forms/jquery.form.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'smart-forms-steps'		, plugin_dir_url( __FILE__ ) 		. 'assets/js/forms/jquery.steps.min.js', array( 'et-jquery-form-min' ), $this->version, false );
		wp_register_script( 'smart-forms-timepicker', plugin_dir_url( __FILE__ ) 		. 'assets/js/forms/jquery-ui-timepicker.min.js', array( 'et-jquery-form-min' , 'smart-forms-custom' ), $this->version, false );
		wp_register_script( 'smart-forms-custom'	, plugin_dir_url( __FILE__ ) 			. 'assets/js/forms/jquery-ui-custom.min.js', array( 'et-jquery-form-min' ), $this->version, false );
		wp_register_script( 'smart-forms-validate'	, plugin_dir_url( __FILE__ ) 		. 'assets/js/forms/jquery.validate.min.js', array( 'et-jquery-form-min' ), $this->version, false );
		wp_register_script( 'smart-forms-additional-methods', plugin_dir_url( __FILE__ ) .'assets/js/forms/additional-methods.min.js', array( 'et-jquery-form-min' ), $this->version, false );
		wp_register_script( 'smart-forms-cloneya'	, plugin_dir_url( __FILE__ ) 		. 'assets/js/forms/jquery-cloneya.min.js', array( 'et-jquery-form-min' ), $this->version, false );
		wp_register_script( 'servicios_agregar'		, plugin_dir_url( __FILE__ ) 		. 'assets/js/servicios/servicios_agregar.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'et-lista_paises'		, plugin_dir_url( __FILE__ ) 		. 'assets/js/forms/lista_paises.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'et-numeric'			, plugin_dir_url( __FILE__ ) 		. 'assets/js/forms/autoNumeric.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'et-showHide'			, plugin_dir_url( __FILE__ ) 		. 'assets/js/forms/jquery.formShowHide.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'smart-forms-custom-validate', plugin_dir_url( __FILE__ )	. 'assets/js/servicios/servicios_opciones.js', array( 'smart-forms-validate' ), $this->version, false );
		wp_register_script( 'google_maps_api', 'http://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDFoZZgI13xJEg7Jb2DaCiPk_ahZBb3Qqw', array('smart-forms-steps'), '3.15', true);
	    wp_register_script( 'google_maps_marker' 	, plugin_dir_url( __FILE__ ) . 'assets/js/google_maps_marker.js' , array('google_maps_api') );
	    
	    wp_register_script( 'et_mostrar_calendario_fe', plugin_dir_url( __FILE__ )	. 'assets/js/servicios/servicios_mostrar_calendario_fe.js', array( 'jquery' , 'et_datetimepicker' ), $this->version, false );
	    wp_localize_script( 'et_mostrar_calendario_fe', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	    wp_register_script( 'et_datetimepicker', plugin_dir_url( __FILE__ )	. 'assets/js/servicios/jquery.datetimepicker.full.min.js', array( 'jquery' ), $this->version, false );
	    
	    wp_register_script( 'et_front_end_servicios', plugin_dir_url( __FILE__ ) 		. 'assets/js/ajax/et_front_end_servicios.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( 'et_front_end_servicios', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );		
	}
	
	/* **************************************************** */
	/* ENVIO AL USUARIO AL HOME AL HACER LOGOUT */
	/* **************************************************** */
	public function et_go_home() {
		wp_redirect( home_url() );
		exit();
	}
	/* **************************************************** */
	
	/* ********************************************** */
	/* LEE LAS VARIABLES PASADAS POR _GET */
	/* ********************************************** */
	public function et_queryvars( $qvars ) {
		$qvars[] = 'id_prov';
		$qvars[] = 'id_servicio';
		$qvars[] = 'accion';
		$qvars[] = 'status';
		$qvars[] = 'id_cita';
		$qvars[] = 'categoria';
		$qvars[] = 'id_usuario';
		$qvars[] = 'tipo_servicio';
		$qvars[] = 'fecha';
		$qvars[] = 'hora';
		$qvars[] = 'redirect_to';
		$qvars[] = 'estado';
		$qvars[] = 'pais';
		$qvars[] = 'servicios';
		
		return $qvars;
		}
	/* ********************************************** */

}
