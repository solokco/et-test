<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       mingoagency.com
 * @since      1.0.0
 *
 * @package    Estilotu
 * @subpackage Estilotu/public/partials
 */
	
/* **************************************************** */	
/* SI NO ESTA LOGEADO */
/* **************************************************** */
if (!is_user_logged_in() ): ?>
	<section class="container-wrap main-color">
		<div class="section-container container">
			<div class="row">
				<div class="col-sm-6 wpb_column column_container">
					<div class="wpb_wrapper">
						
					</div> 
				</div> 
			
				<div class="col-sm-6 wpb_column column_container">
					<div class="wpb_wrapper">
					<h1>Para poder Realizar una consulta online primero debes:</h1>
						<?php echo do_shortcode( '[kleo_button title="Iniciar Sesi&oacute;n" style="primary" icon="user-1" tooltip_position="left" tooltip_action="hover" size="lg" el_class="kleo-show-login"]' ); ?>
						<a href="/registro/" class="btn btn-primary btn-lg"><i class="icon-user-add"></i>Reg&iacute;strate</a>
					</div> 
				</div> 
			</div>
		</div>
	</section>

<?php
/* **************************************************** */

/* **************************************************** */
/* SI ESTA LOGEADO */
/* **************************************************** */
else: 	
	if ( $current_user->ID == $servicio->post_author )
		$es_proveedor = true;		
		
	/* SI VIENE EL _POST LO GUARDO EN LA BD */	?> 
	
	<section class="container-wrap main-color">
		<div class="section-container container">
			
			<div class="row servicio-consulta-online">
				
				<!-- HEADER -->
				<div class="col-sm-12 wpb_column column_container border-bottom">
					<div class="wpb_wrapper Centrar">
						<h2 name="consulta" id="consulta"><?php echo $servicio->post_title; ?></h2>
					</div>
				</div>
				
				
				<!-- COLUMNA IZQUIERDA -->
				<div class="col-sm-5 wpb_column column_container">
					<div class="wpb_wrapper">
						<?php if ( !$es_proveedor ): ?>
						<div class="Proveedor" id="proveedor-<?php echo $servicio->post_author; ?>" >
							<a href="/usuarios/<?php echo $proveedor->user_login ?>/#consulta"><?php echo get_avatar( $servicio->post_author, 150 ); ?></a>
							<h3>
								<?php echo $proveedor->display_name; ?> 
							</h3>
		
							<?php $sobreTi =  bp_get_profile_field_data( array('field'   => 'Sobre ti' , 'user_id' => $servicio->post_author    ) );
							echo wpautop($sobreTi) ?>
							<a href="/usuarios/<?php echo $proveedor->user_login ?>/#consulta" class="btn btn-primary btn-lg"><i class="icon-user-add"></i>Ir al perfil de <?php echo $proveedor->display_name; ?> </a>
						</div>
						<?php endif; ?>
						
						<?php 
						if ( $es_proveedor && Estilotu_Miembro::validar_miebro() ): ?>
															
							<div id="usuarios_clientes">
									
								<h3 class="Centrar">Usuarios solicitando asesoria para:</h3> 
								<h2 class="Centrar"><?php echo $servicio->post_title; ?> </h2>
								
								<?php $clientes = $this->listar_usuarios_consulta_online( $this->id_servicio );				

								if ($clientes == null):
									echo "No hay usuarios solicitando este servicio";
								
								else:
								
									foreach ($clientes as $key=>$cliente):
										
										$arr_params = array( 'id_servicio' => $this->id_servicio, 'id_usuario' => $cliente->asesoria_user_id ); ?>

										<a href="<?php echo add_query_arg( $arr_params , '#consulta') ?>">
											<div class="usuario">												
												<?php 
												echo get_avatar( $cliente->asesoria_user_id, 50 );
												
												$user_info = get_userdata( $cliente->asesoria_user_id ); 
												echo $user_info->user_login;
												?>	
											</div>								
										</a>
									<?php 
									endforeach; 
								
								endif; ?>
									
							</div>
						<?php 
						endif; ?>
						
					</div> 
				</div> 
				
				<!-- CHAT -->
				<div class="col-sm-7 wpb_column column_container">
					<div class="wpb_wrapper">					
													
						<form name="consulta-form" id="consulta-form" class="standard-form" action="<?php echo add_query_arg('id_servicio' , $this->id_servicio ) ?>" method="post">
							
							<?php if (isset($id_cita) ): ?>
								<h2><?php echo $consulta_online[0]->asesoria_titulo ?></h2>
								<input type="hidden" name="asesoria_titulo" placeholder="Nombre de tu consulta" value="<?php echo $consulta_online[0]->asesoria_titulo ?>" maxlength="70" readonly />
								
							<?php else: ?>	
								<input type="text" name="asesoria_titulo" placeholder="Nombre de tu consulta" maxlength="70" />
							
							<?php endif; ?>	
																												
							<div class="Consultas" id="Consulta_">
								<?php foreach ($consulta_online as $key => $consulta): ?>
									<div class="Consulta" id="Consulta_<?php echo($key); ?>">
										<header>
											<?php 
											if ($consulta->asesoria_autor == $servicio->post_author ): ?>
												<div class="imagen_proveedor">
													<?php echo get_avatar( $servicio->post_author, 64 ); ?>
												</div>
											<?php endif; ?>
											
											<h4 class="fecha"><?php echo($consulta->update_time); ?></h4>
											
											<?php 
											if ( isset($this->id_usuario) ): 
												if ($consulta->asesoria_autor == $this->id_usuario): ?>
													<div class="imagen_usuario">
														<?php echo get_avatar( $this->id_usuario , 64 ); ?>
													</div>
												<?php endif; ?>
											<?php
											else:
												if ($consulta->asesoria_autor == $current_user->ID): ?>
													<div class="imagen_usuario">
														<?php echo get_avatar( $current_user->ID, 64 ); ?>
													</div>
												<?php 
												endif; ?>
											<?php 
											endif; ?>
												
										</header>
											
										<div class="Contenido_consulta">
											<?php echo($consulta->asesoria_texto); ?>
										</div>	
																		
									</div>
								<?php 
								endforeach; 
								?>
							</div>
							
							<textarea id="nueva_consulta" class="gui-textarea" name="asesoria_texto" placeholder="Escribe aqu&iacute; lo que deseas consultar"></textarea>
						
							<input type="submit" class="btn btn-primary btn-lg" name="wp-submit-consulta" id="submit-consulta" value="Enviar" tabindex="100" />								
							<input type="hidden" name="id_servicio" value="<?php echo $this->id_servicio; ?>" />
							<input type="hidden" name="id_provider" value="<?php echo $proveedor->ID; ?>" />
							<input type="hidden" name="id_usuario" value="<?php echo $this->id_usuario; ?>" />
							<input type="hidden" name="id_cita" value="<?php echo isset($id_cita) ? $id_cita : "" ?>" />
							<input type="hidden" name="action" value="post" />
							<?php wp_nonce_field( 'guardar_consulta_online' , 'nonce_consulta_online' ); ?>
						</form>
						

					</div> 
				</div> 
			</div>
		</div>
	</section>	
<?php endif; ?>

<!-- ****************************************-->
<!-- ANIMA LA PAGINA AL ANCHO DE LA CONSULTA -->
<!-- ****************************************-->
<script>
var jump=function(e) {
   if (e){
       e.preventDefault();
       var target = jQuery(this).attr("href");
   }else{
       var target = location.hash;
   }

   jQuery('html,body').animate(
   	{scrollTop: jQuery(target).offset().top + -30},2000,
	   	function() {
	       location.hash = target;
	   	}
   	);
}

jQuery(document).ready(function() {
    if (location.hash){
        setTimeout(function(){
            jQuery('html, body').scrollTop(0).show();
            jump();
        }, 0);
    }
});
</script>
<!-- ****************************************-->