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
class Estilotu_Servicios {
	
	private $en_edicion;
	private	$id_servicio;
	private $servicio;
	private $servicio_meta;
	private $servicios_categoria;
	private $post_id;
	private	$fecha_seleccionada;
	public	$table_name;
	
	public function __construct() {
		
		global $wpdb;
		bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
		
	}
	
	/* ************************************************************************ */
	/* RECIBO UNA FECHA Y LA CONVIERTO A ESPANOL					 			*/
	/* ************************************************************************ */
	public function convertir_fecha ($fecha) {
	
		$fecha_final  = date("l, d F Y", strtotime($fecha) );
		
		$days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		
		$fecha_final = str_ireplace($days, $dias, $fecha_final);
		
		$month = array("January","February","March","April","May","June","July","August","September","October","November","December");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	
		$fecha_final = str_ireplace($month, $meses, $fecha_final);
		
		return $fecha_final;
	}
	/* ************************************************************************ */
	
	/* ************************************************************************ */
	/* LISTA LOS SERVICIOS DEL USUARIO QUE SE MUESTRA EN BUDDYPRESS 			*/
	/* ************************************************************************ */
	public function ver_servicios() {
		global $bp;
		global $current_user;
		
		$id_provider = $bp->displayed_user->id;
				
		echo do_shortcode( '[vc_posts_grid loop="size:100|order_by:date|post_type:servicios|authors:'.$id_provider.'" post_layout="grid" columns="4" show_switcher="no" switcher_layouts="masonry,small,standard" show_meta="yes" show_excerpt="yes"]' );
		
	}
	/* ************************************************************************ */
	
	/* ************************************************************************ */
	/* LISTA LOS SERVICIOS DEL USUARIO QUE SE MUESTRA EN BUDDYPRESS 			*/
	/* ************************************************************************ */
	public function agregar_servicios() {
		global $bp;
	
		$id_provider = $bp->displayed_user->id;
		
		if ( Estilotu_Miembro::validar_miebro() ):
			
			global $wp_query;
			global $current_user;

			/* ************************************ */
			/* SI VIENE POST LO VOY A GUARDAR */
			/* ************************************ */
			if ( !empty($_POST) ):
				
				$this->guardar_servicio();			
			
			else:
							
				/* ************************************************* 	*/
				/* SI HAY ID SELECCIONADO ASUMO QUE SE VA A EDITAR 		*/
				/* ************************************************* 	*/
				if ( isset( $wp_query->query_vars['id_servicio'] ) ):
					
					$post_author_id = get_post_field( 'post_author', $wp_query->query_vars['id_servicio'] );

					// Si el usuario actual es igual al autor del servicio
					if ( $current_user->ID == $post_author_id ):
					
						$this->post_id 		= $wp_query->query_vars['id_servicio'];
						$this->en_edicion 	= true;
						
						$this->servicio 			= get_post($this->post_id); 
						$this->servicio_meta 		= get_post_meta($this->post_id); 				
						$this->servicios_categoria	= get_the_terms($this->post_id, 'servicios-categoria');
						
						$disponible = unserialize($this->servicio_meta["disponibilidad_servicio"][0]) ;

					// la persona esta tratando de editar una nota que no le corresponde
					else:
						echo "<h2>Estas tratando de editar un servicio que no te pertenece</h2>";
						exit;
					
					endif;
					
				endif;
				
				/* ************************************ 	*/
				/* SI NO HAY TIPO DE EVENTO SELECCIONADO 	*/
				/* ************************************ 	*/
				if ( !isset($wp_query->query_vars['tipo_servicio']) ):
					require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/servicios/servicios-agregar-display.php' ;

				/* ************************************ 	*/
				/* SI EL TIPO SELECCIONADO ES CUPOS 		*/
				/* ************************************ 	*/
				elseif ( $wp_query->query_vars['tipo_servicio'] == 'cupos' ):
					
					wp_enqueue_script( 'et-jquery-form-min');
					wp_enqueue_script( 'smart-forms-steps');
					wp_enqueue_script( 'smart-forms-validate');
					wp_enqueue_script( 'smart-forms-additional-methods');
					wp_enqueue_script( 'smart-forms-cloneya');
					wp_enqueue_script( 'et-lista_paises');
					wp_enqueue_script( 'et-numeric');			
					wp_enqueue_script( 'et-showHide');
					wp_enqueue_script( 'smart-forms-custom-validate');
					wp_enqueue_script( 'google_maps_api');
					wp_enqueue_script( 'google_maps_marker');
					wp_enqueue_script( 'smart-forms-timepicker');
					wp_enqueue_script( 'smart-forms-custom');
					
					wp_enqueue_script( 'servicios_agregar');
					
					require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/servicios/servicios-agregar-cupos-display.php' ;
				
				/* ************************************ 	*/
				/* SI EL TIPO SELECCIONADO ES EVENTOS 		*/
				/* ************************************ 	*/
				elseif ( $wp_query->query_vars['tipo_servicio'] == 'eventos' ):
				
				/* ************************************ 	*/
				/* SI EL TIPO SELECCIONADO ES ONLINE 		*/
				/* ************************************ 	*/
				elseif ( $wp_query->query_vars['tipo_servicio'] == 'online' ):
				
				endif;
			endif;	

		else:
			echo "<h2>Debes tener un paquete para poder agregar servivios</h2>";	
			
		endif;
		
	}
	/* ************************************************************************ */
	
	
	
	
	
	/* *********************************************** */
	/* AGREGAR O EDITAR UN SERVICIO NUEVO */ 
	/* *********************************************** */
	private function  guardar_servicio () {  
		
		/* *********************************************** 	*/
		/* VALIDO QUE VENGA EL CODIGO NONCE DEL FORMULARIO	*/
		/* *********************************************** 	*/
		if ( !isset( $_POST['publicar_servicio_nonce'] ) || !wp_verify_nonce( $_POST['publicar_servicio_nonce'], 'publicar_servicio' ) ) {
		   print 'Se ha producido una falla, por favor intente m&aacute;s tarde';
		   exit;
		} 
				
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			if ( !is_user_logged_in() )
				return;
								
			global $current_user;
			get_currentuserinfo();
						
			$user_id			= $current_user->ID;
			$post_title     	= wp_strip_all_tags( $_POST['nombre_servicio']		);
			$post_content		= $_POST['description'] ;
			$tipo 				= wp_strip_all_tags( $_POST['et_meta_tipo']	 	);
			$categoria			= wp_strip_all_tags($_POST['cat_servicio']) ;  
			//$tags				= wp_strip_all_tags ($_POST['servicios-etiquetas']	);
			//$tags 			= explode(',', $tags);
			$precio				= wp_strip_all_tags( $_POST['et_meta_precio']	 );
			$precio_moneda		= wp_strip_all_tags( $_POST['et_meta_precio_moneda']  );  
			$precio_visibilidad	= wp_strip_all_tags( $_POST['et_meta_precio_visibilidad']  );  
			
			if ($tipo == "cupos" || $tipo == "evento" ):
				$direccion 		= wp_strip_all_tags( $_POST['ubicacion']['et_meta_direccion']  );  
				$pais 			= wp_strip_all_tags( $_POST['ubicacion']['et_meta_pais']  );  
				$ciudad			= wp_strip_all_tags( $_POST['ubicacion']['et_meta_ciudad']  );  
				$estado			= wp_strip_all_tags( $_POST['ubicacion']['et_meta_estado']  );  
				$zipcode		= wp_strip_all_tags( $_POST['ubicacion']['et_meta_zipcode']  );  
				$usar_mapa		= wp_strip_all_tags( $_POST['ubicacion']['et_meta_usar_mapa']  );  
				$latitud		= wp_strip_all_tags( $_POST['ubicacion']['et_meta_latitud']  );  
				$longitud		= wp_strip_all_tags( $_POST['ubicacion']['et_meta_longitud']  );  
				
				$cerrar_cupo	= wp_strip_all_tags( $_POST['et_meta_close_time']  );  
				
			endif;
			
			
			if ($tipo == "cupos"):
				$max_time 		= wp_strip_all_tags( $_POST['et_meta_max_time']  );
				$disponibilidad	= $_POST['disponible'] ;  
			endif;
			
			if ($tipo == "evento"):
				$fecha_desde 	= wp_strip_all_tags( $_POST['et_date_from']  );
				$fecha_hasta 	= wp_strip_all_tags( $_POST['et_date_to']  );
				$tipo_evento	= wp_strip_all_tags( $_POST['tipo-evento']  );
				
				if ($tipo_evento == "tipo-evento-cupos"):
					$cupos_evento = serialize($_POST['bloques_dias']);			
				
				else:
					$horario_inicio = wp_strip_all_tags( $_POST['inicio_horario']  );
					$horario_fin 	= wp_strip_all_tags( $_POST['fin_horario']  );
				endif;
				
			endif;
			
			$error_array = array();
					
			if (empty($post_title)) 	
				$error_array[]='Seleccione el nombre del servicio.';
			if (empty($post_content)) 
				$error_array[]='Agregue la descripción del servicio.';
						
			if (count($error_array) == 0):		
				/* EDITANDO SERVICIO */
				
				if ( $_POST['servicio_status'] == 'et_updating' ):
					/******************************
					EDITA UN SERVICIO
					******************************/
					$servicio_editado = wp_update_post( array(
						'ID'			=> $_POST['post_id'],
						'post_title'	=> $post_title,
						'post_content'	=> $post_content
						) );
					
					$post_id = $_POST['post_id'];
					wp_set_object_terms( $post_id, array($categoria), 'servicios-categoria' );
					wp_set_object_terms( $post_id, $tags , 'servicios-etiquetas' );
					
					$activity_args = array(
						'component'		=> 'servicios',
						'type' 			=> 'servicio_cupos_editado',
						'user_id' 		=> $user_id,
						'item_id'		=> $post_id,
						'action' 		=> '<a href="'.bp_core_get_user_domain( $user_id ).'">' . $current_user->display_name . '</a> edit&oacute; su servicio: <a href="' . get_post_permalink( $post_id ) . '">' .  $post_title . '</a>' ,
						'recorded_time' => bp_core_current_time(),
						'content'		=> '<div class="et_activity_text">' . $post_content . '</div><div class="et_activity_image_container"><div class="et_activity_image">' . get_the_post_thumbnail( $post_id, 'medium' ) . '</div></div>'
					);
					// ------------------------------------------------------
	
				else:
					/* ****************************	*/
					/* GUARDA UN SERVICIO NUEVO		*/
					/* **************************** */
					$post_id = wp_insert_post( array(
						'post_author'	=> $user_id,
						'post_title'	=> $post_title,
						'post_type'     => 'servicios',
						'post_content'	=> $post_content,
						'post_status'	=> 'publish'
						) );
	
					wp_set_object_terms( $post_id, array($categoria), 'servicios-categoria' );	
					// wp_set_object_terms( $post_id, $tags , 'servicios-etiquetas' );
					
					/* ****************************	*/
					/* INFORMACION PARA EL TIMELINE	*/
					/* **************************** */
					$activity_args = array(
						'component'		=> 'servicios',
						'type' 			=> 'servicio_cupos_nuevo',
						'user_id' 		=> $user_id,
						'item_id'		=> $post_id,
						'action' 		=> '<a href="'.bp_core_get_user_domain( $user_id ).'">' . $current_user->display_name . '</a> agreg&oacute; un servicio nuevo: <a href="' . get_post_permalink( $post_id ) . '">' .  $post_title . '</a>' ,
						'recorded_time' => bp_core_current_time(),
						'content'		=> '<div class="et_activity_text">' . $post_content . '</div>'
					);
					/* **************************** */
				endif;
				
				if ($tipo == "evento"):				
					// BUSCO LOS DATOS DE CUANDO EL SERVICIO VA A OCURRIR	
					$finish_time_gmt = et_convert_time_to_gmt ( $fecha_hasta , $horario_fin );
					
					// ARGS A PASAR PARA EL HOOK
					$args_scheduled = array(
						$post_id
					);
					
					//We pass $post_id because cron event arguments are required to remove the scheduled event
					wp_clear_scheduled_hook( 'estilotu_eliminar_eventos_pasados' , $args_scheduled);
					
					//Schedule the reminder
					wp_schedule_single_event( $finish_time_gmt , 'estilotu_eliminar_eventos_pasados' , $args_scheduled );
				endif;				
					
				/* ********************** 	*/ 
				/* GUARDA LOS META DATOS	*/ 
				/* ********************** 	*/ 
				update_post_meta($post_id, 'et_meta_tipo',		$tipo  );
				update_post_meta($post_id, 'et_meta_precio',	$precio  );
				update_post_meta($post_id, 'et_meta_precio_moneda',	$precio_moneda  );
				update_post_meta($post_id, 'et_meta_precio_visibilidad',	$precio_visibilidad  );
				/* ********************** 	*/ 
				
				
				/* ****************************************************************** 	*/ 
				/* GUARDA LOS META DATOS SI EL SERVICIO ES TIPO EVENTO O CUPOS			*/
				/* ****************************************************************** 	*/ 
				if ($tipo == "evento" || $tipo == "cupos" ):
					update_post_meta($post_id, 'et_meta_usar_mapa',	$usar_mapa  );
					update_post_meta($post_id, 'et_meta_direccion',	$direccion  );
					update_post_meta($post_id, 'et_meta_pais',		$pais  );
					update_post_meta($post_id, 'et_meta_zipcode',	$zipcode  );
					update_post_meta($post_id, 'et_meta_ciudad',	$ciudad  );
					update_post_meta($post_id, 'et_meta_estado',	$estado  );
					update_post_meta($post_id, 'et_meta_latitud',	$latitud  );
					update_post_meta($post_id, 'et_meta_longitud',	$longitud  );
					update_post_meta($post_id, 'et_meta_close_time', $cerrar_cupo  );
				endif;
				/* ****************************************************************** 	*/ 
				
				/* ****************************************************************** 	*/ 
				/* GUARDA LOS META DATOS SI EL SERVICIO ES TIPO EVENTO					*/
				/* ****************************************************************** 	*/ 
				if ($tipo == "evento"):
					update_post_meta($post_id, 'tipo-evento',	 $tipo_evento  );
					update_post_meta($post_id, 'et_date_from',	 $fecha_desde  );				
					update_post_meta($post_id, 'et_date_to',	 $fecha_hasta  );
					
					
					if ($tipo_evento == "tipo-evento-cupos"):		
						update_post_meta($post_id, 'bloques_dias', $cupos_evento  );
					else:
						update_post_meta($post_id, 'inicio_horario', $horario_inicio  );
						update_post_meta($post_id, 'fin_horario',	 $horario_fin  );
					endif;
					
					
				endif;
				/* ****************************************************************** 	*/ 
				
				
				/* ****************************************************************** 	*/ 
				/* GUARDA LOS META DATOS SI EL SERVICIO ES TIPO CUPOS					*/
				/* ****************************************************************** 	*/ 
				if ($tipo == "cupos"):
					update_post_meta($post_id, 'et_meta_max_time',	$max_time  );
					
					update_post_meta($post_id, 'disponibilidad_servicio', $disponibilidad  );

				endif;
				// ------------------------------------------------------
				
				/*********************************************************
				GUARDA LA IMAGEN DEL POST
				*********************************************************/
				if( ! empty( $_FILES ) ) {
					foreach( $_FILES as $key=>$file ) {
						if( is_array( $file ) ) {
				
							if ($key == "imagen_destacada")
								$portada = true;
							else
								$portada = false;
								
							$attachment_id = $this->upload_user_file( $file , $portada );
								
							if ($portada)
								set_post_thumbnail( $post_id, $attachment_id );
							
						}
					}
				}
				// ------------------------------------------------------
	
				
				/*********************************************************
				AGREGA EL SERVICIO AL STREAM
				*********************************************************/
				$activity_id = bp_activity_add( $activity_args );
				// ------------------------------------------------------
				
				if ($_POST['servicio_status'] != 'et_updating')
					echo "<div class='destacado_success'><h4>Muchas gracias, su servicio ha sido agregado con &eacute;xito.</h4></div>";
				
				else
					echo "<div class='destacado_success'><h4>Muchas gracias, su servicio ha sido editado con &eacute;xito.</h4></div>";
				
				return true;
				
			else:
				echo "<div class='destacado_alert'><h4>Tuvimos problemas guardando este servicio.</h4></div>";
				return false;
			endif;
		}
	}
	/* *********************************************** */
	
	/* *********************************************** */
	/* PROCESAR IMAGEN SUBIDA */
	/* *********************************************** */
	private function upload_user_file( $file = array() ) {
	
		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
		
		$file_return = wp_handle_upload( $file, array('test_form' => false ) );
		
		if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
		
			return false;
		
		} else {
		
			$filename = $file_return['file'];
			
			$attachment = array(
				'post_mime_type' => $file_return['type'],
				'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content' => '',
				'post_status' => 'inherit',
				'guid' => $file_return['url']
			);
			
			$attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
			
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			
			if( 0 < intval( $attachment_id ) ) {
				return $attachment_id;
			}
		
		}
		
		return false;
	}
	/* *********************************************** */
	
	
	
}
