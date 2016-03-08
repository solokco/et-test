<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       mingoagency.com
 * @since      1.0.0
 *
 * @package    Estilotu
 * @subpackage Estilotu/public
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
class Estilotu_Miembro extends Estilotu_User {
	
	private $id_membresia = array(1,2,3,4,5,6,7);
		
	/* ********************************************** */
	/* VALIDO SI USUARIO TIENE PERMISO DE VER SECCION */
	/* ********************************************** */
	public function validar_miebro( $id_membresia = array(1,2,3,4,5,6,7) , $user_id = null ) {
	
		global $current_user;

		if ( !isset($user_id) )
			$user_id = $current_user->ID;
					
		
		if (pmpro_hasMembershipLevel( $id_membresia , $user_id ) )
			return true;
		
		return false;
	}
	/* ********************************************** */
	
	/* ***********************************************************************************	*/
	/* AGREGA SECCIONES PERSONALES Y ACOMODA EL ORDEN Y NOMBRES DEL MENU DE BUDDYPRESS		*/
	/* ************************************************************************************ */
	public function buddypress_agregar_botones_menu(){
	    global $bp;
		
		$id_usuario_seleccionado = bp_displayed_user_id();
		
		/* ******************************** */
		/* AGREGA SECCIONES PERSONALES 		*/
		/* ********************************	*/
		bp_core_new_nav_item(
			array(
				'name'                => __( 'Citas', 'estilotu' ),
				'slug'                => 'citas',
				'position'            => 10,
				'screen_function'     => Estilotu_Miembro::validar_miebro() ? array( $this, 'seccion_ver_citas_recibidas') : array( $this, 'seccion_ver_citas') ,
				'default_subnav_slug' => 'citas',
				'parent_url'          => $bp->loggedin_user->domain . $bp->slug . '/',
				'show_for_displayed_user' => false,
				'parent_slug'         => $bp->slug
			)
		);
		
		
		/* ********************************************* */
			/* SI EL USUARIO MOSTRADO ES IGUAL AL LOGEADO */
			/* ********************************************* */
			if ( Estilotu_Miembro::validar_miebro() ):
				
				bp_core_new_subnav_item( 
					array( 
						'parent_id'			=> 'citas',
						'name'            	=> __( 'Citas recibidas' , 'estilotu' ), 
						'slug'            	=> 'citas_recibidas', 
						'parent_url'      	=> $bp->loggedin_user->domain . 'citas/', 
						'parent_slug'     	=> 'citas', 
						'screen_function' 	=> array( $this, 'seccion_ver_citas_recibidas' ),
						'position'        	=> 60
						) 
					);
				
				bp_core_new_subnav_item( 
					array( 
						'parent_id'			=> 'citas',
						'name'            	=> __( 'Citas realizadas' , 'estilotu' ), 
						'slug'            	=> 'citas_realizadas', 
						'parent_url'      	=> $bp->loggedin_user->domain . 'citas/', 
						'parent_slug'     	=> 'citas', 
						'screen_function' 	=> array( $this, 'seccion_ver_citas' ),
						'position'        	=> 60
						) 
					);	
									
			endif;
			/* ************************************** */
				
		if ( $this->validar_miebro( $this->id_membresia , $id_usuario_seleccionado ) ):
	
			bp_core_new_nav_item(
				array(
					'name'                => __( 'Servicios', 'estilotu' ),
					'slug'                => 'servicios',
					'position'            => 10,
					'screen_function'     => array( $this, 'seccion_servicios'),
					'default_subnav_slug' => 'servicios',
					'parent_url'          => $bp->loggedin_user->domain . $bp->slug . '/',
					'show_for_displayed_user' => true,
					'parent_slug'         => $bp->slug
				)
			);
			
			/* ********************************************* */
			/* SI EL USUARIO MOSTRADO ES IGUAL AL LOGEADO */
			/* ********************************************* */
			if ($id_usuario_seleccionado == $bp->loggedin_user->id ):
				
				bp_core_new_subnav_item( 
					array( 
						'parent_id'			=> 'servicios',
						'name'            	=> __( 'Agregar servicios' , 'estilotu' ), 
						'slug'            	=> 'agregar_servicio', 
						'parent_url'      	=> $bp->loggedin_user->domain . $parent_slug . 'servicios/', 
						'parent_slug'     	=> 'servicios', 
						'screen_function' 	=> array( $this, 'seccion_servicios_agregar' ),
						'position'        	=> 60
						) 
					);
									
			endif;
			/* ************************************** */
	
		endif;
		
		bp_core_new_nav_item(
			array(
				'name'                => __( 'Consultas Online', 'estilotu' ),
				'slug'                => 'consultas-online',
				'position'            => 15,
				'screen_function'     => array( $this, 'seccion_consultas_online' ),
				'default_subnav_slug' => 'consultas-online',
				'parent_url'          => $bp->loggedin_user->domain . $bp->slug . '/',
				'show_for_displayed_user' => false,
				'parent_slug'         => $bp->slug
			)
		);
		/* ******************************** */
		
		/* ******************************** */
		/* MODIFICA LA POSICION				*/
		/* ******************************** */
	    if( isset ($bp->bp_nav['media'])){
	        $bp->bp_nav['media']['position'] 	= 50;
	    }
	    
	    if( isset ( $bp->bp_nav['citas']['position'] ) ) {
	        $bp->bp_nav['citas']['position'] 	= 20;
	    }
	    
	    if( isset ( $bp->bp_nav['servicios']['position'] ) ) {
	        $bp->bp_nav['servicios']['position'] 	= 15;
	    }
	
	    $bp->bp_nav['activity']['position'] 	= 5;       
	    
	    $bp->bp_nav['profile']['position'] 		= 1;
	
		$bp->bp_nav['messages']['position'] 	= 60;
		$bp->bp_nav['notifications']['position']= 70;
	
		/* $bp->bp_nav['blogs']['position'] 		= 60; */
		$bp->bp_nav['settings']['position'] 	= 160;
		
		$bp->bp_nav['friends']['position'] 		= 150;
		$bp->bp_nav['groups']['position'] 		= 150;
		// $bp->bp_nav['forums']['position'] 		= 150;
		$bp->bp_nav['following']['position'] 	= 150;
		$bp->bp_nav['followers']['position'] 	= 150;	
		/* ******************************** */
		
		/* ******************************** */
		/* CAMBIA LOS NOMBRES    			*/
		/* ******************************** */
	    $bp->bp_nav['activity']['name'] 	= "Actividad";
	    $bp->bp_nav['profile']['name'] 		= "Perfil";
	
		$bp->bp_nav['messages']['name'] 	= "Mensajes";
		$bp->bp_nav['notifications']['name']= "Notificaciones";
	
		$bp->bp_nav['settings']['name'] 	= "Preferencias";
		
		$bp->bp_nav['friends']['name'] 		= "Amigos";
		$bp->bp_nav['groups']['name'] 		= "Grupos";
		// $bp->bp_nav['forums']['name'] 		= "Foros";
		$bp->bp_nav['following']['name'] 	= "Siguiendo";
		$bp->bp_nav['followers']['name'] 	= "Seguidores";	
		/* ******************************** */
	}	
	/* *********************************************************************************** */
	
	
	/* LLAMO A LA CLASE CONSULTA ONLINE */
	public function seccion_consultas_online() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/servicios/class-servicios-public.php'; 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/servicios/class-servicios-consultaonline-public.php'; 
		
		$consulta_online = new EstiloTu_ServicioConsultaOnline();
		add_action( 'bp_template_content', array ($consulta_online , 'ver_consultas_online' ) );
	}
	
	/* ******************************************************************* */
	/* PREPARA LA SECCION PARA LISTAR LOS SERVICIOS DEL USUARIO */
	/* ******************************************************************* */
	public function seccion_servicios() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/servicios/class-servicios-public.php'; 
		
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-servicios-consultaonline-public.php'; //extends Estilotu_Servicios
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-servicios-cupos-public.php'; //extends Estilotu_Servicios
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-citas-listar-public.php'; //extends Estilotu_Servicios
		
		$citas = new Estilotu_Servicios();
		
		/* LLAMO EL SHORTCODE */
		/* class-servicios-public.php */
		add_action( 'bp_template_content', array ($citas , 'ver_servicios' ) );

		
	}
	/* ******************************************************************* */
	
	/* ******************************************************************* */
	/* PREPARA LA SECCION PARA AGREGAR SERVICIOS */
	/* ******************************************************************* */
	public function seccion_servicios_agregar() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/servicios/class-servicios-public.php'; 
				
		$citas = new Estilotu_Servicios();
		
		//wp_enqueue_style('smart-forms');
		//wp_enqueue_style('smart-forms-purple');
		
		/* LLAMO EL SHORTCODE */
		/* class-citas-public.php */
		add_action( 'bp_template_content', array ($citas , 'agregar_servicios' ) );
		
	}
	/* ******************************************************************* */
	
	/* ******************************************************************* */
	/* PREPARA LA SECCION PARA LISTAR CITAS */
	/* ******************************************************************* */
	public function seccion_ver_citas() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/servicios/class-servicios-public.php'; 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/citas/class-citas-public.php'; //extends Estilotu_Servicios
		
		$citas = new EstiloTu_Citas( "realizadas" );
		add_action( 'bp_template_content', array ($citas , 'ver_citas' ) );
		
	}
	/* ******************************************************************* */
	
	/* ******************************************************************* */
	/* PREPARA LA SECCION PARA LISTAR CITAS RECIBIDAS POR PROFESIONAL 	   */
	/* ******************************************************************* */
	public function seccion_ver_citas_recibidas() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/servicios/class-servicios-public.php'; 
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/citas/class-citas-public.php'; //extends Estilotu_Servicios
		
		$citas = new EstiloTu_Citas( "recibidas" );
		add_action( 'bp_template_content', array ($citas , 'ver_citas' ) );
		
	}
	/* ******************************************************************* */	
	
	
	/* *********************************************** */
	/* LISTAR SERVICIOS DEL PROVEEDOR */
	/* *********************************************** */
	public function listarServicios( $id_provider = null ) {  
		global $wp_query;
		
		$paged 	= ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$estado = ( get_query_var('estado') ) ? get_query_var('estado') : null;
		$pais 	= ( get_query_var('pais') ) ? get_query_var('pais') : null;
		$tipo_servicio = ( get_query_var('tipo_servicio') ) ? get_query_var('tipo_servicio') : null;
		$categoria = ( get_query_var('categoria') ) ? get_query_var('categoria') : null;
			
		$cantidad_servicios = 12;
		
		$query_array = array('relation' => 'AND');
	
		if ( isset($estado) ) {
		    array_push( $query_array, array('key' => 'et_meta_estado', 'value' => $estado, 'compare' => '=') );
		}
		
		if ( isset($pais) ) {
		    array_push( $query_array, array('key' => 'et_meta_pais', 'value' => $pais, 'compare' => '=') );
		}
		
		if ( isset($tipo_servicio) ) {
		    array_push( $query_array, array('key' => 'et_meta_tipo', 'value' => $tipo_servicio, 'compare' => '=') );
		}
		
		$args = array(
			'author' 				=> ( isset($id_provider) ) ? $id_provider : null,
			'servicios-categoria' 	=> ( isset($categoria) ) ? $categoria : null,
			'posts_per_page'		=> $cantidad_servicios,
			'paged' 				=> $paged,
			'post_status'			=> 'publish',
			'meta_query' 			=> $query_array,
			'post_type' 			=> 'servicios'		
			);	
		
		$the_query = new WP_Query($args);
	
		return $the_query;
	}
	/* *********************************************** */
	
	/* ************************************************************* */
	/* NOTIFICAR AL PROFESIONAL QUE LE ACABAN DE CONSULTAR EL PRECIO */
	/* ************************************************************* */
	public function et_notificar_solicitud_precio( ) {  
		
		$id_servicio = $_POST['id_servicio'];
		
	    if ( bp_is_active( 'notifications' ) ) {
			global $bp, $wpdb;
			
			$author_id = get_post_field ( 'post_author', $id_servicio );
			$user_ID = get_current_user_id();
			
	        bp_notifications_add_notification( array(
	            'user_id'           => $author_id ,
	            'item_id'           => $id_servicio,
	            'secondary_item_id' => $user_ID,
	            'component_name'    => 'servicios',
	            'component_action'  => 'solicitud_precio',
	            'date_notified'     => bp_core_current_time(),
	            'is_new'            => 1,
	        ) );
	    }
		
	}
	
		
	public function et_bp_filter_notifications_get_registered_components( $component_names = array() ) {
		// Force $component_names to be an array
		if ( ! is_array( $component_names ) ) {
			$component_names = array();
		}
		// Add 'custom' component to registered components array
		array_push( $component_names, 'servicios' );
		// Return component's with 'custom' appended
		return $component_names;
	}
	
	
	
	// this gets the saved item id, compiles some data and then displays the notification
	public function et_bp_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
		
		// New custom notifications
		if ( 'solicitud_precio' === $action ) {
		
			$servicio = get_post( $item_id );
			$cliente = get_userdata( $secondary_item_id );
			
			$servicio_title = $cliente->user_login . ' solicitó ver el precio de tu servicio: ' . $servicio->post_title;
			
			$servicio_link  = bp_core_get_user_domain( $secondary_item_id );
			
			$servicio_text = $cliente->user_login . ' solicitó ver el precio de tu servicio: ' . $servicio->post_title . '.  Contáctalo aquí.';
			
			// WordPress Toolbar
			if ( 'string' === $format ) {
				$return = apply_filters( 'et_bp_filter', '<a href="' . esc_url( $servicio_link ) . '" title="' . esc_attr( $servicio_title ) . '">' . esc_html( $servicio_text ) . '</a>', $servicio_text, $servicio_link );
			
			// Deprecated BuddyBar
			} else {
				$return = apply_filters( 'et_bp_filter', array(
					'text' => $servicio_text,
					'link' => $servicio_link
				), $servicio_link, (int) $total_items, $servicio_text, $servicio_title );
			}
			
			return $return;
			
		}
		
	}
	
	

}
