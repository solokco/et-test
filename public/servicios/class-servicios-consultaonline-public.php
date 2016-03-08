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
class EstiloTu_ServicioConsultaOnline extends Estilotu_Servicios {
	
	private $tablename_asesoria; 
	private $id_servicio; 
	private $id_usuario;
	private $id_provider;
	private $sql;
	
	public function __construct() {
		global $wpdb; 
		
		parent::__construct();		
		$this->tablename_asesoria = $wpdb->prefix . "bb_asesoria";
	}
	
	/* *********************************************** */
	/* VER CONSULTAS ONLINE */
	/* recibe ID del servicio, ID del usuario, ID del proveedor */
	/* ********************************************** */
	public function ver_mis_consultas_online ( $id_servicio , $id_usuario , $id_proveedor ) {
		global $wpdb;
		global $current_user;
		
		if ( isset($id_usuario) ):
			
			$sql = $wpdb->prepare( "SELECT * FROM $this->tablename_asesoria WHERE asesoria_provider_id = %d AND asesoria_service_id = %d AND asesoria_user_id = %d ORDER BY update_time ASC", $id_proveedor, $id_servicio, $id_usuario  );
		
		else:
			
			$sql = $wpdb->prepare( "SELECT * FROM $this->tablename_asesoria WHERE asesoria_user_id = %d AND asesoria_service_id = %d  ORDER BY update_time ASC", $id_usuario, $id_servicio );
			
		endif;
		
		$result = $wpdb->get_results($sql, OBJECT );
			
		return $result;
	}
	/* *********************************************** */
	
	/* *********************************************** */
	/* LISTAR CONSULTAS ONLINE */
	/* ********************************************** */
	protected function listar_usuarios_consulta_online ( $id_servicio ) {

		global $wpdb;
		global $current_user;
				
		$sql = $wpdb->prepare( "SELECT DISTINCT asesoria_user_id FROM $this->tablename_asesoria WHERE asesoria_provider_id = %d AND asesoria_service_id = %d ", $current_user->ID , $id_servicio );
		$result = $wpdb->get_results($sql, OBJECT );
			
		return $result;
		
	}
	/* *********************************************** */
	
	/* **************************************************** */
	/* CREAR EL CONTENIDO DE LAS SECCIONES PERSONALES		*/
	/* **************************************************** */
	public function ver_consultas_online() {
		global $wp_query;
		
		if ( !isset($wp_query->query_vars['id_servicio']) ) :
			global $bp;
			global $wpdb;

			$this->sql = $wpdb->prepare( "SELECT * FROM $this->tablename_asesoria WHERE asesoria_user_id = %d OR asesoria_provider_id = %d GROUP BY id_cita", $bp->loggedin_user->id , $bp->loggedin_user->id );		
			$consultas_online = $wpdb->get_results( $this->sql, OBJECT );
			
			/* ********************************************************* */
			/* VERIFICA SI HAY SERVICIOS ASOCIADOS AL CURRENT USER */
			/* ********************************************************* */
			if ( empty($consultas_online) ):
				echo "<div id='consulta'><h3 class='Centrar destacado'>NO TIENES NINGUNA CONSULTA ONLINE PAUTADA A&Uacute;N</h3></div>";	
	
			else: ?>
				<h2 class="Centrar" id="consulta">Servicios con consultas abiertas</h2>
				
				<style>
					table {width:100%;}
				</style>
				
				<table class="widefat fixed">
					<thead>
						<tr>
							<th>ID</th>
							<th>Titulo</th>
							<th>Status</th>
							<th>Ultima actualizacion</th>
						</tr>
					</thead>
					
					<tfoot>
						<tr>
							<td>ID</td>
							<td>Titulo</td>
							<td>Status</td>
							<td>Ultima actualizacion</td>
						</tr>
					</tfoot>
					
					<tbody>
			
						<?php 
						foreach ( $consultas_online as $key => $consulta ): ?>	
				
							<tr>
								<th><?php echo $consulta->id_cita; ?></th>
								<th><a href="<?php echo esc_url( add_query_arg( array ('id_servicio' => $consulta->asesoria_service_id , 'id_cita' => $consulta->id_cita ) , get_permalink() ) ); ?>"><?php echo $consulta->asesoria_titulo; ?></a></th>
								<th><?php echo $consulta->asesoria_status; ?></th>
								<th><?php echo $consulta->update_time; ?></th>
							</tr>
						<?php
						endforeach;
						?>
							
					</tbody>
					
				</table>				
			<?php	
			endif;
		
		else:
			/* **************************************************** */
			/* SI HAY UN SERVICIO SELECCIONADO						*/
			/* **************************************************** */
						
			global $current_user;
			global $wpdb;

			if ( isset($wp_query->query_vars['id_cita']) ):
				$id_cita = $wp_query->query_vars['id_cita'] ;
				$this->sql = $wpdb->prepare( "SELECT * FROM $this->tablename_asesoria WHERE id_cita = %d AND asesoria_service_id = %d" , $id_cita , $wp_query->query_vars['id_servicio'] );		
				$consulta_online = $wpdb->get_results( $this->sql, OBJECT );				

			endif;						
			
			$this->id_servicio 	= $wp_query->query_vars['id_servicio'];
			$servicio 			= get_post($this->id_servicio);
			$proveedor 			= get_userdata( $servicio->post_author ); 
			$tipo 				= get_post_meta( $this->id_servicio , 'et_meta_tipo', true );
			$this->id_usuario 	= $current_user->ID;
			
			
			if ( isset($_POST) )
				$this->guardad_servicio_online();
				

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/servicios/consultasonline-display.php' ;
		endif;
	}
	/* **************************************************** */
	
	/* *********************************************** */
	/* REGISTRAR SERVICIO ONLINE */
	/* *********************************************** */
	protected function guardad_servicio_online ( ) {
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'post' ):
			
			
			
			if ( !is_user_logged_in() || !isset( $_POST['nonce_consulta_online'] ) || ! wp_verify_nonce( $_POST['nonce_consulta_online'], 'guardar_consulta_online' ) ):
				 exit; 
			
			else:

				global $wpdb;
				global $current_user;
				
				if (empty( $wp_query->query_vars['id_cita']) ) :
					$id_cita_max = $wpdb->get_var("SELECT MAX(id_cita) FROM $this->tablename_asesoria");
					$id_cita_max++;
				else:
					$id_cita_max = $wp_query->query_vars['id_cita'];
				
				endif;
			
										
				$this->id_provider	= wp_strip_all_tags( $_POST['id_provider'] );
				$user_id			= wp_strip_all_tags( $_POST['id_usuario'] );
				$post_titulo		= wp_strip_all_tags( $_POST['asesoria_titulo'] );
				$post_consulta		= wp_strip_all_tags( $_POST['asesoria_texto'] );
				$id_servicio 		= wp_strip_all_tags( $_POST['id_servicio'] );
				$autor 				= $current_user->ID;
				$id_cita			= $id_cita_max;
				
				$data = array( 
					'asesoria_provider_id' 		=> $this->id_provider, 
					'asesoria_user_id' 			=> $user_id, 
					'asesoria_service_id'		=> $id_servicio,
					'asesoria_titulo'			=> $post_titulo,
					'asesoria_texto'			=> $post_consulta,
					'asesoria_autor'			=> $autor,
					'id_cita'					=> $id_cita,
					'update_time'	 			=> current_time("Y-m-d H:i:s")
				);
					
				$wpdb->insert( $this->tablename_asesoria , $data );	
				
				if( FALSE === $result ) :
				
				    echo( "<div class='Centrar destacado_error'><span><i class='fa fa-warning'></i>Ups, se presentó un error y no se guardaron los datos.  Por favor intente más tarde!</span></div>" );
				
				else:
				   
				    echo( "<div class='Centrar destacado_success'><span><i class='fa fa-check'></i>La consulta fue agregada con &eacute;xito!</span></div>" );
					$nombre_servicio = get_the_title( $id_servicio );
					
					if ( $current_user->ID != $id_provider ):
						$proveedor = get_userdata ( $id_provider );
						$to = $proveedor->user_email;
					else:
						$cliente = get_userdata ( $user_id );
						$to = $cliente->user_email;
					endif;
					
					return ;
						 
				endif;
			
			endif;
		endif;	
	}
	/* *********************************************** */
	
	

}
