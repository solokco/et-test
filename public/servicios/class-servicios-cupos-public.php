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
class EstiloTu_ServicioCupos extends Estilotu_Servicios {
	
	private $tablename_asesoria; 
	
	public function __construct() {
		global $wpdb; 
		
		parent::__construct();
		add_action( 'bp_template_content', array ($this , 'ver_servicios' ) );
	}
	
}
