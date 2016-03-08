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
class Estilotu_Servicios_FrontEnd {
	
	private $en_edicion;
	private $servicio;
	private $servicio_meta;
	private $servicios_categoria;
	private $post_id;
	private $fecha_seleccionada;
	private $dia_seleccionado;
	private $table_name;
	private $provider_id;
	private $old_version = false;
	
	
	
	public function __construct() {
		
		add_shortcode("et_ver_lista_servicios", array ($this , "listar_servicios" ) );
		
	}
	
	/* *********************************************** */
	/* MOSTRAR EL CALENDARIO EN EL FRONT */
	/* *********************************************** */
	public function ver_calendario_servicios () {	
		
	    $this->post_id = get_the_ID(); 
	    $this->provider_id = get_the_author_meta('ID');
		
		$this->servicio_meta = get_post_custom($this->post_id) ;
		
		$url_guardar = add_query_arg( 'accion', 'guardar', bp_core_get_user_domain( get_current_user_id() ) . "citas" );	
		
		$disponibilidad = unserialize($this->servicio_meta['disponibilidad_servicio'][0]);
						
		/* ******************************************** */
		/* EN CASO QUE SE TENGA LA ESTRUCTURA VIEJA 	*/
		/* ******************************************** */
		if ( isset($this->servicio_meta['bloque_lunes'][0]) )	
			$cuposLunes 	= unserialize( unserialize( $this->servicio_meta['bloque_lunes'][0] ) ) ;
		
		if ( isset($this->servicio_meta['bloque_martes'][0]) )	
			$cuposMartes 	= unserialize( unserialize( $this->servicio_meta['bloque_martes'][0] ) ) ;
	
		if ( isset($this->servicio_meta['bloque_miercoles'][0]) )		
			$cuposMiercoles = unserialize( unserialize( $this->servicio_meta['bloque_miercoles'][0] ) ) ;
		
		if ( isset($this->servicio_meta['bloque_jueves'][0]) )	
			$cuposJueves 	= unserialize( unserialize( $this->servicio_meta['bloque_jueves'][0] ) ) ;
		
		if ( isset($this->servicio_meta['bloque_viernes'][0]) )	
			$cuposViernes 	= unserialize( unserialize( $this->servicio_meta['bloque_viernes'][0] ) ) ;
		
		if ( isset($this->servicio_meta['bloque_sabado'][0]) )	
			$cuposSabado 	= unserialize( unserialize( $this->servicio_meta['bloque_sabado'][0] ) ) ;
		
		if ( isset($this->servicio_meta['bloque_domingo'][0]) )	
			$cuposDomingo 	= unserialize( unserialize( $this->servicio_meta['bloque_domingo'][0] ) ) ;	
		/* ******************************************** */
		
		
		//  ************************
		// 	REVISO POR VACACIONES
		//  ************************
		global $wpdb;
	    	    
		//$tablename = $wpdb->prefix . "bb_vacations";
		//$sql = $wpdb->prepare( "SELECT * FROM $tablename WHERE vacation_provider_id = %d", $provider_id );
		
		//$vacaciones = $wpdb->get_results($sql, OBJECT );
		
		/*
		if ($vacaciones):
			
			$dias_vacaciones = array();
			
			foreach ($vacaciones as $vacaion) :
				
				$start = strtotime($vacaion->vacation_start);
				$end = strtotime($vacaion->vacation_end);
			
			
				for ($i = $start; $i <= $end; $i += 24 * 3600):
	
			        $dias_vacaciones []= date("Y-m-d", $i);
			        
				endfor;
							
			endforeach; 
			
		endif;
		*/
				
		if ( !isset($this->servicio_meta['disponibilidad_servicio']) && ( isset($cuposDomingo) || isset($cuposLunes) || isset($cuposMartes) || isset($cuposMiercoles) || isset($cuposJueves) || isset($cuposViernes) || isset($cuposSabado) ) ):
		
			$this->old_version = true;
		
		endif;
		
		//  ************************
		
		if ( is_user_logged_in() ):
			?>		
			<input id="calendario_servicio" type="text">

			<div class='Contenedor_Cupos'>
				<h2>Selecciona un día para ver los cupos disponibles</h2>
				
				<h4>Cupos disponibles</h4>
				<div class="lista_cupos_disponibles"></div>
			
			</div>
			
			<!--
			<?php if ($vacaciones): ?>
			vacaciones = <?php echo json_encode( $dias_vacaciones ); ?>;
			vacaciones = JSON.stringify(vacaciones);
			<?php endif; ?>
			-->
		
		<?php
		else:
		
			echo "<h3>Debes iniciar tu sesion o registrirte para poder reservar el servicio</h3>";
		
		endif;
		

		$datatoBePassed = array(
			'id_servicio' 	=> $this->post_id,
			'url_user'		=> $url_guardar,	
			'old_version'	=> $this->old_version
		);
		
		wp_enqueue_script( 'et_mostrar_calendario_fe');
		wp_localize_script( 'et_mostrar_calendario_fe', 'php_vars', $datatoBePassed );
		
		wp_enqueue_script( 'et_datetimepicker');
				
		wp_enqueue_style('et_datetimepicker');
					
	}
	
	
	/* *********************************************** */
	/* AJAX CARGA LOS CUPOS DISPONIBLES DE UN SERVICIO */
	/* *********************************************** */
	public function et_cargar_cupos_func( ) {
		
		$this->id_servicio 			= $_POST['id_servicio'];
		$this->fecha_seleccionada 	= $_POST['fecha_seleccionada'];
		$this->dia_seleccionado		= $_POST['dia_seleccionado'];
		$this->old_version			= $_POST['is_old'];
		
		$this->servicio_meta = get_post_custom($this->id_servicio) ;
		
		$activo = false;
		
		$dias = array("domingo","lunes","martes","miercoles","jueves","viernes","sabado" );
		$dia = $dias[$this->dia_seleccionado];
		
		if ( $this->old_version) :
			
			// SI ESTOY USANDO EL ARRAY VIEJO
			if ($this->servicio_meta['et_meta_dias_activo_' . $dia][0] == "on"):
				
				$activo = true;
				
				$bloque = unserialize(unserialize($this->servicio_meta['bloque_' . $dia][0]));
				
				$disponibilidad[$dia]["bloque"] = $bloque;
				
				
				foreach ( $disponibilidad[$dia]["bloque"] as $key => $value ):
			
					foreach ( $value as $key2 => $value2 ):
						
						if ($key2 == "et_meta_hora_inicio"):
							$time_in_24_hour_format  = date("H:i:s", strtotime($value2));
		
							$disponibilidad[$dia]["bloque"][$key][$key2] = $time_in_24_hour_format;
							
						endif;
						
					endforeach;
										
				endforeach;

				
			
			else:
				$activo = false;
			
			endif;
						
		else:
			
			if ( isset( $this->servicio_meta["disponibilidad_servicio"][0]  ) ):
				$disponibilidad = unserialize($this->servicio_meta['disponibilidad_servicio'][0]);
				
				if ($disponibilidad[$dia]["activo"] == "on" || $disponibilidad[$dia]["activo"] == "off" )
					$activo = true;
				else
					$activo = false;
					
			endif;	
			
		endif;
		
		
		if ( $activo ):
						
			global $wpdb;
			global $current_user;
			
			$this->table_name = $wpdb->prefix . "bb_appoinments";
			$user_ID = get_current_user_id();
			
			$citas = $wpdb->prepare("SELECT appoinment_time , appoinment_user_id FROM $this->table_name WHERE appoinment_date = %s AND appoinment_service_id = %d AND (appoinment_status = 'confirm' OR appoinment_status = 'hold' )" , $this->fecha_seleccionada , $this->id_servicio ); 						
			$citas = $wpdb->get_results($citas , ARRAY_A);
			
			// Creo un array para las horas duplicadas de este dia
			$ocupado = array();
			foreach ($citas as $key => $value){
			    foreach ($value as $key2 => $value2){
			        
			        if ( $key2 == "appoinment_time") {
				        $index = $value2;
				        if (array_key_exists($index, $ocupado)){
				            $ocupado[$index]++;
				        } else {
				            $ocupado[$index] = 1;
				        }	
					}
			    }   
			}
			
			// Creo un array para las horas que el usuario ya tiene reserva
			$reservado = array();
			foreach ($citas as $key => $value){
			    
			    foreach ($value as $key2 => $value2){
			        
			        if ( $key2 == "appoinment_user_id" && $value2 == $user_ID ) {
				        $index = $value["appoinment_time"];
				        if (!array_key_exists($index, $reservado)){
				            $reservado[$index] = true;
				        }	
					}
				}	
			}
		 
			
			$disponibilidad[$dia]["ocupado"] 	= $ocupado;
			$disponibilidad[$dia]["reservado"] 	= $reservado;
							
			ob_start("ob_gzhandler");
				$return = $disponibilidad[$dia];					
			ob_end_clean();
				
		
		else:
			ob_start("ob_gzhandler");
			$return = 0;
			ob_end_flush();
		endif;

		exit( json_encode($return) );

		
		
	}
	/* *********************************************** */
	
		
	public function ordenar_por_hora ($a, $b) {
		$t1 = strtotime($a);
	    $t2 = strtotime($b);
	    
	    return $t1 - $t2;
	}
	
	/* */ 
	/* LISTAR SERVICIOS POR MEDIO DEL SHORTCODE 	*/
	public function listar_servicios( $atts, $content = null ) { 
		
		/* Extraigo las propiedades  */
		extract( shortcode_atts( array(
			
			//'categoria' => ''
			
			) , $atts , 'listar_servicios' ) 
		);
		
		/* SE DEBE DEFINIR PREVIAMENTE EL ID PROVIDER */
		global $wp_query;
	
		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$estado = ( get_query_var('estado') ) ? get_query_var('estado') : null;
		$pais = ( get_query_var('pais') ) ? get_query_var('pais') : null;
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
		
		$servicios = new WP_Query($args);
	
		if ($servicios->have_posts()): 
			
			$estado_seleccionado = ( get_query_var('estado') ) ? get_query_var('estado') : null;
			$pais_seleccionado = ( get_query_var('pais') ) ? get_query_var('pais') : null;
			$categoria_seleccionada = ( get_query_var('categoria') ) ? get_query_var('categoria') : null;
			
			if ($estado_seleccionado)
				echo ( "<h1>Servicios en " . $estado_seleccionado) . "</h1>"; 
			
			if ( isset($accion) && $accion == "editar"): ?>
				<ul id="Servicios" class="three-column">
			
			<?php 
			else: ?>
			<div id="Servicios" class="posts-listing responsive-cols kleo-masonry per-row-2 no-meta masonry-listing">
		
			<?php 
			endif; 
		
			while ($servicios->have_posts() ): $servicios->the_post(); 
				
				$opciones_servicio = get_post_custom( ); 
				
				$tipo = $opciones_servicio['et_meta_tipo'][0];
				
				if ( isset($opciones_servicio['et_meta_precio'][0]) )
					$precio = $opciones_servicio['et_meta_precio'][0];
						
				if ( isset($opciones_servicio['et_meta_precio_moneda'][0]) ):
					$moneda = $opciones_servicio['et_meta_precio_moneda'][0];
					
					switch ($moneda){
						case "VEF":
							$moneda = "Bsf";
						break;
						
						case "USD":
							$moneda = "$";
						break;
						
						case "EU":
							$moneda = "&euro;";
						break;
						
						default:
							$moneda = "Bsf";
					}
					
				else:
				
						$moneda = "Bsf";
				
				endif;
						
				if ( isset($opciones_servicio['et_meta_precio_visibilidad'][0]) )
					$visibilidad_precio = $opciones_servicio['et_meta_precio_visibilidad'][0];
				
				if ( isset($opciones_servicio['et_meta_usar_mapa'][0]) )
					$user_mapa = $opciones_servicio['et_meta_usar_mapa'][0];
				
				if ( isset($user_mapa) && $user_mapa == true ):	
					if ( isset($opciones_servicio['et_meta_pais'][0]) )
						$pais = $opciones_servicio['et_meta_pais'][0];	
					
					if ( isset($opciones_servicio['et_meta_ciudad'][0]) )
						$ciudad = $opciones_servicio['et_meta_ciudad'][0];
						
					if ( isset($opciones_servicio['et_meta_estado'][0]) )
						$estado = $opciones_servicio['et_meta_estado'][0];	
					
					if ( isset($opciones_servicio['et_meta_direccion'][0]) )
						$direccion = $opciones_servicio['et_meta_direccion'][0];	
				endif;
				
				if ( isset( $opciones_servicio['et_meta_hora_inicio'][0] ) )
					$hora = date( "g:i a", strtotime($opciones_servicio['et_meta_hora_inicio'][0]) );
				else
					$hora = null;
				
				
					?>
					
				<div class="post-item servicios type-servicios status-publish has-post-thumbnail hentry servicios-categoria-entrenamiento-y-fisioterapia">
					<div class="post-content animated animate-when-almost-visible el-appear start-animation">
						<div class="post-image">
							<?php if ( has_post_thumbnail() ): ?>
								
								<a href="<?php echo get_permalink(); ?>" class="element-wrap">
									<?php the_post_thumbnail('large'); ?>
									<span class="hover-element">
										<i>+</i>
									</span>
								</a>
								
							<?php			
							else: ?>
							
							<a href="<?php echo get_permalink(); ?>" class="element-wrap">
								<img src="http://www.estilotu.com/wp-content/plugins/js_composer/assets/vc/vc_gitem_image.png" alt="EstiloTu sin imagen">
								<span class="hover-element">
									<i>+</i>
								</span>
							</a>
							
							
							<?php 
							endif; ?>
						</div>
						
						<div class="post-header">
							
							<?php the_title('<h3 class="post-title entry-title">','</h3>',true); ?>
							<p><a href="/usuarios/<?php echo get_the_author_meta('user_login'); ?>"><?php echo get_the_author_meta('user_login'); ?></a></p>
							<p><?php echo $tipo;  ?></p>
							
							<?php 
							if ( $tipo == "evento" ): 
								if ( isset($opciones_servicio['et_date_from'][0]) )
									$fecha_inicio = $opciones_servicio['et_date_from'][0] ;
								
								if ( isset($opciones_servicio['inicio_horario'][0]) )
									$hora_inicio = $opciones_servicio['inicio_horario'][0] ;
									
								if ( isset($opciones_servicio['et_date_to'][0]) )
									$fecha_fin = $opciones_servicio['et_date_to'][0] ;
									
								if ( isset($opciones_servicio['fin_horario'][0]) )
									$hora_fin = $opciones_servicio['fin_horario'][0] ; 
							
								echo ("<p class='label_calendar'><i class='fa fa-calendar fa-fw'></i>Fecha inicio: " . $fecha_inicio . "</p>"	);
								echo ("<p class='label_calendar'><i class='fa fa-calendar fa-fw'></i>Fecha fin: " . $fecha_fin . "</p>"	);
							
							?>
							
								
							
							<?php endif; ?>
													
							<?php if ( $tipo == "cupos" ) : ?>
								<div class="dias masonry">
									<ul>
									<?php if ($opciones_servicio['et_meta_dias_activo_lunes'][0] == 'on' ): 	 ?>
										<li class="dia_activo dia_circulo">LU</li>
									<?php else: ?>
										<li class="dia_inactivo dia_circulo">LU</li>
									<?php endif; ?>
									
									<?php if ($opciones_servicio['et_meta_dias_activo_martes'][0] == 'on' ): 	 ?>
										<li class="dia_activo dia_circulo">MA</li>
									<?php else: ?>
										<li class="dia_inactivo dia_circulo">MA</li>
									<?php endif; ?>
									
									<?php if ($opciones_servicio['et_meta_dias_activo_miercoles'][0] == 'on' ): 	 ?>
										<li class="dia_activo dia_circulo">MI</li>
									<?php else: ?>
										<li class="dia_inactivo dia_circulo">MI</li>
									<?php endif; ?>
									
									<?php if ($opciones_servicio['et_meta_dias_activo_jueves'][0] == 'on' ): 	 ?>
										<li class="dia_activo dia_circulo">JU</li>
									<?php else: ?>
										<li class="dia_inactivo dia_circulo">JU</li>
									<?php endif; ?>
									
									<?php if ($opciones_servicio['et_meta_dias_activo_viernes'][0] == 'on' ): 	 ?>
										<li class="dia_activo dia_circulo">VI</li>
									<?php else: ?>
										<li class="dia_inactivo dia_circulo">VI</li>
									<?php endif; ?>
									
									<?php if ($opciones_servicio['et_meta_dias_activo_sabado'][0] == 'on' ): 	 ?>
										<li class="dia_activo dia_circulo">SA</li>
									<?php else: ?>
										<li class="dia_inactivo dia_circulo">SA</li>
									<?php endif; ?>
									
									<?php if ($opciones_servicio['et_meta_dias_activo_domingo'][0] == 'on' ): 	 ?>
										<li class="dia_activo dia_circulo">DO</li>
									<?php else: ?>
										<li class="dia_inactivo dia_circulo">DO</li>
									<?php endif; ?>
									</ul>
								</div>
							<?php endif; ?>
							
						</div>
							
						<div class="post-info">
							
							 <?php the_excerpt(); 
								
							if ($visibilidad_precio == "public"): ?>
								<p class="label_precio"><i class="fa fa-money fa-fw"></i> <?php echo $moneda ?> <?php echo (number_format($precio ,0 , ",",".") ); ?> </p>
							<?php endif; ?>
							
							
							<?php if ( isset($user_mapa) && $user_mapa == true ): ?>
								<p class="label_direccion"><i class="fa fa-map-marker fa-fw"></i> <?php echo $direccion . "<br>" . $estado . ", " . $ciudad . " - " . $pais; ?></p>
							<?php endif; ?>
						</div>
							
						
						
						<div class="post-footer">
							<?php if ( isset($accion) && $accion == "editar"): ?>
			
								<a href="<?php echo add_query_arg( array('tipo_servicio' => $tipo , 'id_servicio' => get_the_ID() ) ) ?>" class="btn btn-primary btn-lg" id="<?php echo get_the_ID() ?>">Editar</a>
								
							<?php else: 
			
								if ( $tipo == "cupos" || $tipo == "evento" ) : ?>
									
									<a href="<?php echo get_permalink(); ?>" class="vc_gitem-link vc_general vc_btn3 vc_btn3-size-lg vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-block vc_btn3-icon-left vc_btn3-color-violet" title="Reservar" id="<?php echo get_the_ID() ?>"><i class="vc_btn3-icon fa fa-ticket"></i>Reservar</a>
								
								<?php 
								elseif ( $tipo == "online" ) : ?>	
									
									<?php if ( is_user_logged_in() ): 
	
										$current_user = wp_get_current_user();
										?>	
	
										<a href="<?php echo add_query_arg('id_servicio' , get_the_ID() , '/usuarios/'.$current_user->user_login.'/consultas-online/#consulta' ) ?>" id="<?php echo get_the_ID() ?>" class="vc_gitem-link vc_general vc_btn3 vc_btn3-size-lg vc_btn3-shape-rounded vc_btn3-style-modern vc_btn3-block vc_btn3-icon-left vc_btn3-color-violet" title="Reservar" id="<?php echo get_the_ID() ?>"><i class="vc_btn3-icon fa fa-comment"></i>Consulta Online</a>
	
									<?php else: ?>
										<a href="/login" class="btn btn-primary btn-lg" id="<?php echo get_the_ID() ?>">Consulta Online</a>
									<?php endif; ?>
									
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
					
				</div> 
			
			<?php 
			endwhile;
			?>			
			<style>
				.pagination {
				clear:both;
				padding:20px 0;
				position:relative;
				font-size:11px;
				line-height:13px;
				text-align: center;
				}
				 
				.pagination span, .pagination a {
				display:inline;
				margin: 2px 2px 2px 0;
				padding:6px 9px 5px 9px;
				text-decoration:none;
				width:auto;
				color:#fff;
				background: #555;
				}
				 
				.pagination a:hover{
				color:#fff;
				background: #3279BB;
				}
				 
				.pagination .current{
				padding:6px 9px 5px 9px;
				background: #3279BB;
				color:#fff;
				}
				
			</style>
			
			</div>
			
			<div class="pagination-nav clear">
				<?php et_pagination($servicios->max_num_pages); ?> 
			</div>
			
			<?php 
			wp_reset_postdata(); 
		
		else: ?>
		
			<h2>No hay servicios</h2>
		
		<?php 
		endif; 
			
	}
	
	
}