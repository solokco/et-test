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
class Estilotu_PMP {

	private $plugin_name;
	private $version;
	private $user_id;
	private $id_membresia;
	
	public function __construct( $plugin_name, $version , $user_id , $id_membresia ) {
		
		parent::__construct( $plugin_name, $version , $user_id );
		
		$this->id_membresia = $id_membresia;
		
	}
	
	/* ********************************************** */
	/* VALIDO SI USUARIO TIENE PERMISO DE VER SECCION */
	/* ********************************************** */
	public function estilotu_validar_miembro( $id_membresia = false) {
	
		global $current_user;
		
		if (!isset($id_membresia) )
			$id_membresia = array(1,2,3,4,5,6,7);
			
		if (pmpro_hasMembershipLevel( $id_membresia , $current_user->ID ) ):
			return true;
		else:
			return false;
		endif;
	}
	/* ********************************************** */
}