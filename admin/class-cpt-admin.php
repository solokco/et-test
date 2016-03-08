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
class Estilotu_Admin_CPT {

	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/* *************************************************************************** */
	/* AGREGA EL EL CTP DE SERVICIOS  */
	/* *************************************************************************** */
	public function registrar_cpt_servicios()  {
		
		$servicio_labels = array(
			'name'               => _x( 'Servicios', 'post type general name', 'estilotu' ),
			'singular_name'      => _x( 'Servicio', 'post type singular name', 'estilotu' ),
			'menu_name'          => _x( 'Servicios', 'admin menu', 'estilotu' ),
			'name_admin_bar'     => _x( 'Servicio', 'add new on admin bar', 'estilotu' ),
			'add_new'            => _x( 'Nuevo servicio', 'book', 'estilotu' ),
			'add_new_item'       => __( 'Agregar nuevo servicio', 'estilotu' ),
			'new_item'           => __( 'Nuevo servicio', 'estilotu' ),
			'edit_item'          => __( 'Editar servicio', 'estilotu' ),
			'view_item'          => __( 'Ver servicios', 'estilotu' ),
			'all_items'          => __( 'Todos los Servicios', 'estilotu' ),
			'search_items'       => __( 'Buscar servicios', 'estilotu' ),
			'parent_item_colon'  => __( 'Servicios padres:', 'estilotu' ),
			'not_found'          => __( 'No se encontraron servicios.', 'estilotu' ),
			'not_found_in_trash' => __( 'No se encontraron servicios en la papelera.', 'estilotu' )
		);
		
		$args = array(
			'labels' 				=> $servicio_labels,
			'description' 			=> __( 'Servicios que ofrece un proveedor de Servicios.', 'estilotu' ),
			'public' 				=> true,
			'publicly_queryable'	=> true,
			'rewrite'            	=> array( 'slug' => 'servicios' ),
			'capability_type' 		=> 'post',
			'show_in_menu'       	=> true,
			'menu_position' 		=> 5,
			'hierarchical' 			=> false,
			'supports' 				=> array('title','editor','author','thumbnail','excerpt','comments','custom-fields'),
			'has_archive'			=> 'servicios',
			'capability_type' 		=> 'post'
			); 
		
		register_post_type('servicios', $args);
	}
	/* ********************************** */
	
	/* *************************************** */
	/* CREA TAXONOMIES DE SERVICIOS */
	/* **************************************** */
	public function registrar_cpt_taxonomies_servicios() {
		
		/* *************************************** 	*/
		/* REGISTRO LA CATEGORIA PARA SERVICIOS		*/
		/* ************************************** 	*/
		$categoria_labels = array(
			'name' 				=> __( 'Categorias'),
			'singular_name' 	=> __( 'Categoria'),
			'search_items'		=> __( 'Buscar en cateogiras'),
			'all_items' 		=> __( 'Todas las Categorias' ),
			'parent_item' 		=> __( 'Categoría superior' ),
			'parent_item_colon' => __( 'Categoría superior:' ),
			'edit_item' 		=> __( 'Editar Categorias' ), 
			'update_item' 		=> __( 'Actualizar categorias' ),
			'add_new_item' 		=> __( 'Agregar nueva Categoria' ),
			'new_item_name' 	=> __( 'Nueva Categoria' ),
			'menu_name' 		=> __( 'Categorias' )
		);
		
		$categoria_args = array(
			'hierarchical' 		=> true,
			'labels' 			=> $categoria_labels,
			'show_ui' 			=> true,
			'query_var' 		=> true,
			'show_admin_column' => true,
			'rewrite' 			=> array('slug' => 'servicios-categoria' )
			);
		
		register_taxonomy('servicios-categoria','servicios', $categoria_args);
		/* **************************************** */
		
		/* ***************************************	*/
		/* REGISTRO LAS ETIQUETAS DE SERVICIOS 		*/
		/* ************************************** 	*/
		$etiquetas_labels = array(
			'name' 					=> __( 'Etiquetas'),
			'singular_name' 		=> __( 'Etiqueta'),
			'search_items' 			=> __( 'Buscar en etiquetas' ),
			'popular_items' 		=> __( 'Etiquetas Popular' ),
			'all_items' 			=> __( 'Todas las etiquetas' ),
			'edit_item' 			=> __( 'Editar etiquetas' ), 
			'update_item' 			=> __( 'Actualizar etiquetas' ),
			'add_new_item' 			=> __( 'Agregar nueva etiqueta' ),
			'new_item_name' 		=> __( 'Nombre de la nueva etiqueta' ),
			'add_or_remove_items' 	=> __( 'Agregar o eliminar etiquetas' ),
			'choose_from_most_used' => __( 'Elegir de las etiquetas mas usadas' ),
			'menu_name' 			=> __( 'Etiquetas' ),
			'separate_items_with_commas' => __( 'Separe las etiquetas con comas' )
		);
	
		$etiquetas_args = array(
			'hierarchical' 		=> false,
			'labels' 			=> $etiquetas_labels,
			'show_ui' 			=> true,
			'public' 			=> true,
			'query_var' 		=> true,
			'show_tagcloud'		=> true,
			'show_admin_column' => true,
			'rewrite' 			=> array('slug' => 'servicios-etiqueta' )
		);

		register_taxonomy( 'servicios-etiqueta', 'servicios' , $etiquetas_args );
		/* *************************************** */
	}
	
	/* ***************************************	*/
	/* GUARDA LOS META DATA						*/
	/* *************************************** 	*/
	private function guardar_cpt_servicios_metas ( $post_id ) {
	    
	    // Bail if we're doing an auto save
	    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	     
	    // if our nonce isn't there, or we can't verify it, bail
	    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'et_meta_box_nonce' ) ) return;
	     
	    // if our current user can't edit this post, bail
	    if( !current_user_can( 'edit_post' ) ) return;
	     
	    // now we can actually save the data
	 
	    // Make sure your data is set before trying to save it
	    if( isset( $_POST['et_meta_tipo'] ) )
	        update_post_meta( $post_id, 'et_meta_tipo', esc_attr( $_POST['et_meta_tipo'] ) );
	        
	    if( isset( $_POST['et_meta_precio'] ) )
	        update_post_meta( $post_id, 'et_meta_precio', wp_kses( $_POST['et_meta_precio'], $allowed ) );
	         
	    /* ES TIPO CUPOS */
	    if( isset( $_POST['et_meta_tipo'] ) && $_POST['et_meta_tipo'] == "cupos" ):
	    	if( isset( $_POST['et_meta_duracion'] ) )
	        update_post_meta( $post_id, 'et_meta_duracion', esc_attr( $_POST['et_meta_duracion'] ) );
	        
	        if( isset( $_POST['et_meta_cupos'] ) )
	        update_post_meta( $post_id, 'et_meta_cupos', esc_attr( $_POST['et_meta_cupos'] ) );
	        
		    if( isset( $_POST['et_meta_max_time'] ) )
		        update_post_meta( $post_id, 'et_meta_max_time', esc_attr( $_POST['et_meta_max_time'] ) ); 
		
		    if( isset( $_POST['et_meta_hora_inicio'] ) )
		        update_post_meta( $post_id, 'et_meta_hora_inicio', esc_attr( $_POST['et_meta_hora_inicio'] ) );
		    
		    if( isset( $_POST['disponible'] ) )
		        update_post_meta( $post_id, 'disponibilidad_servicio', serialize( $_POST['disponible'] ) );    
		        
		    $chk = isset( $_POST['et_meta_dias_activo_lunes'] ) ? 'on' : 'off';
		    update_post_meta( $post_id, 'et_meta_dias_activo_lunes', $chk );
		    
		    $chk = isset( $_POST['et_meta_dias_activo_martes'] ) ? 'on' : 'off';
		    update_post_meta( $post_id, 'et_meta_dias_activo_martes', $chk );
		    
		    $chk = isset( $_POST['et_meta_dias_activo_miercoles'] ) ? 'on' : 'off';
		    update_post_meta( $post_id, 'et_meta_dias_activo_miercoles', $chk );
		    
		    $chk = isset( $_POST['et_meta_dias_activo_jueves'] ) ? 'on' : 'off';
		    update_post_meta( $post_id, 'et_meta_dias_activo_jueves', $chk );
		    
		    $chk = isset( $_POST['et_meta_dias_activo_viernes'] ) ? 'on' : 'off';
		    update_post_meta( $post_id, 'et_meta_dias_activo_viernes', $chk );
		    
		    $chk = isset( $_POST['et_meta_dias_activo_sabado'] ) ? 'on' : 'off';
		    update_post_meta( $post_id, 'et_meta_dias_activo_sabado', $chk );
		    
		    $chk = isset( $_POST['et_meta_dias_activo_domingo'] ) ? 'on' : 'off';
		    update_post_meta( $post_id, 'et_meta_dias_activo_domingo', $chk );    
	    /* ES TIPO CUPOS */    
	    
	    /* ES TIPO ONLINE */    
	    elseif( isset( $_POST['et_meta_tipo'] ) && $_POST['et_meta_tipo'] == "online" ):
	    	if( isset( $_POST['et_meta_asesoria_comentario'] ) )
	        	update_post_meta( $post_id, 'et_meta_asesoria_comentario', esc_attr( $_POST['et_meta_asesoria_comentario'] ) );
	    /* ES TIPO ONLINE */    
	    endif;
	}
	/* *************************************** */
	
	/* *********************************************** */
	/* REGISTRAR VACACIONES */ 
	/* *********************************************** */
	private function estilotu_vacaciones_registrar() {  
		global $wpdb;
		global $wp_query;  	
	    global $current_user;
		
		if (!isset( $_POST['verificar_accion_vacaciones'] ) || !wp_verify_nonce( $_POST['verificar_accion_vacaciones'], 'accion_vacacion' ) ):
		   print 'Se ha producido una falla, por favor intente m&aacute;s tarde';
		   exit;
		endif;
		
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'post' ) :
			if ( !is_user_logged_in() )
				return;
			
			$tablename 		= $wpdb->prefix . "bb_vacations";
			$user_id		= $current_user->ID;
			$fecha_inicio 	= $_POST['et_date_from'];
			$fecha_fin 		= $_POST['et_date_to'];
			
			$data = array( 
				'vacation_provider_id' 		=> $user_id, 
				'vacation_start'		=> $fecha_inicio,
				'vacation_end'			=> $fecha_fin,
				'update_time'	 			=> current_time("Y-m-d H:i:s")
			);
			
			print_r($data);
				
			return ($wpdb->insert( $tablename, $data ) );	
			
		endif;
	}
	/* *********************************************** */


}
