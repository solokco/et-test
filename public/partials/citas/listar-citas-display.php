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
?>

<!-- Viene de class-citas-listar-public -->
<div id="Lista_Citas">
	<div class="col-sm-8 wpb_column column_container">
		<div class="Citas">
			<div id="mensaje" style="display:none"><h2 class="Centrar">No hay citas pautadas para este d&iacute;a: <span></span></h2></div>
			
			<?php
			// SEPARA EN GRUPOS DE DIA
			// wp_enqueue_script ('et_asistencia_y_pago_participante');
			
			$group_date=array();
			foreach($citas_pautadas as $key => $item) {
			   $group_date[$item->appoinment_date][$item->appoinment_time][] = $item;
			}
			// SEPARA EN GRUPOS DE DIA
							
			foreach ($group_date as $key_bloque => $bloque):
				
				$dia_servicio = $this->convertir_fecha($key_bloque); ?>
				
				<div id="Bloque_<?php echo $key_bloque ?>" class="Bloque">
					<header class="header_dia"><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
						<h2><?php echo $dia_servicio ?></h2>
						
						<?php if ($this->es_proveedor): ?>
							<form method="post" name="eliminar_cita_dia" action="<?php the_permalink(); ?>" id="formulario_eliminar_cita" enctype="multipart/form-data">
								<input type="hidden" name="fecha" value="<?php echo $key_bloque ?>">
								<input type="hidden" name="status" value="cancel">
								<input type="submit" class="button btn-purple" value="Cancelar las clases del dia <?php echo $dia_servicio ?>">
							</form>
						<?php endif; ?>
						
					</header>
					
					<?php 
					foreach ( $bloque as $key_hora => $hora ): ?>
										
						<div class="Hora <?php echo $key_hora ?>">
							<header class="header_hora">
								<h3><?php echo date('H:i A', strtotime($key_hora)); ?></h3>
								
								<?php if ( $this->es_proveedor ): ?>
									<form method="post" name="eliminar_cita_hora" action="<?php the_permalink(); ?>" id="formulario_eliminar_cita" enctype="multipart/form-data">
										<input type="hidden" name="fecha" value="<?php echo $key_bloque ?>">
										<input type="hidden" name="hora" value="<?php echo $key_hora ?>">
										<input type="hidden" name="status" value="cancel">
										<input type="submit" class="button btn-purple" value="Cancelar la clase de las <?php echo date('H:i A', strtotime($key_hora)); ?>">
									</form>
								<?php endif; ?>
							</header>
							
							<div class="citas">
								<?php foreach ( $hora as $key_cita => $cita ):							
									if ($cita->appoinment_status == "confirm" )
										$status_cita = "Confirmada";			
									elseif ($cita->appoinment_status == "cancel" )
										$status_cita = "Cancelada";	
									else
										$status_cita = "En Espera";
																			
									$user = get_userdata($cita->appoinment_user_id); ?>
									<div class="usuario">
										<div class="usuario_avatar">
											<a href="/usuarios/<?php echo $user->user_login ?>"><?php echo get_avatar( $cita->appoinment_user_id, 80 ); ?></a>
										</div>
										
										<div class="usuario_datos">								
											<h5> <?php echo ($user->first_name . " " . $user->last_name); ?> - <a href="/usuarios/<?php echo $user->user_login ?>"><?php echo $user->user_login ?></a></h5>
											<h6> <?php echo $user->user_email; ?></h6>
											<p>Status: <?php echo $status_cita; ?></p>
									
											<?php 
											foreach($servicios as $servicio): 
												if ($cita->appoinment_service_id == $servicio->ID): ?>
													<p>Servicio: <span class="servicio_reservado"><a href="<?php echo post_permalink($servicio->ID); ?>"><?php echo $servicio->post_title; ?></a></span></p>
													<?php break;
												endif;
											endforeach; 
											?>
										</div>
										
										<div class="usuario_opciones">
											<div class="usuario_status">
												
												<?php if ($this->es_historial == false): ?>
													<?php if ($status_cita == "Cancelada" || $status_cita == "En Espera" ): ?>
																								
														<form method="post" name="confirmar_cita_individual" action="<?php the_permalink(); ?>" id="formulario_eliminar_cita" enctype="multipart/form-data">
															<input type="hidden" name="fecha" value="<?php echo $key_bloque ?>">
															<input type="hidden" name="hora" value="<?php echo $key_hora ?>">
															<input type="hidden" name="id_cita" value="<?php echo $cita->appoinment_id ?>">
															<input type="hidden" name="status" value="confirm">
															<input type="submit" class="button btn-purple" value="Confirmar">
														</form>
			
													<?php endif; ?>
													
													<?php if ($status_cita == "Confirmada" || $status_cita == "En Espera"): ?>
														
														<form method="post" name="cancelar_cita_individual" action="<?php the_permalink(); ?>" id="formulario_eliminar_cita" enctype="multipart/form-data">
															<input type="hidden" name="fecha" value="<?php echo $key_bloque ?>">
															<input type="hidden" name="hora" value="<?php echo $key_hora ?>">
															<input type="hidden" name="id_cita" value="<?php echo $cita->appoinment_id ?>">
															<input type="hidden" name="status" value="cancel">
															<input type="submit" class="button btn-purple" value="Cancelar">
														</form>
													<?php endif; ?>
												<?php endif; ?>
											</div>
																					
											<?php if ( $this->es_proveedor ): ?>
												 <div class="smart-forms">											
													<div class="usuario_opciones">
														<form method="post" name="formulario_usuario_opciones" class="formulario_usuario_opciones" id="formulario_usuario_opciones_<?php echo $cita->appoinment_id ?>" enctype="multipart/form-data">
		
															<div class="section">
															    <div class="option-group field">
															    
															        <label class="switch">
															            <input type="checkbox" name="asistencia" class="appoinment_user_assist boton_<?php echo $cita->appoinment_id ?>" id="<?php echo $cita->appoinment_id ?>" value="asistencia" <?php checked( $cita->appoinment_user_assist , 1 ); ?>>
															            <span class="switch-label" data-on="SI" data-off="NO"></span>
															            <span> ¿Asistió? </span>
															        </label>                     
															    </div><!-- end .option-group section -->
															</div><!-- end section -->
															
															<div class="section">
															    <div class="option-group field">
															    
															        <label class="switch">
															            <input type="checkbox" name="payment" class="appoinment_user_pay boton_<?php echo $cita->appoinment_id ?>" id="<?php echo $cita->appoinment_id ?>" value="payment" <?php checked( $cita->appoinment_user_paid , 1 ); ?>>
															            <span class="switch-label" data-on="SI" data-off="NO"></span>
															            <span> ¿Pagó? </span>
															        </label>                     
															    </div><!-- end .option-group section -->
															</div><!-- end section -->
																												
														</form>
													</div>
												 </div>
											<?php endif; ?>
										</div>	
											
									</div>
								<?php endforeach; ?>		
							</div>
						</div>
		
					<?php 
					endforeach; 
					?>
				</div>
			<?php 
			endforeach;	 ?>
		</div>
	</div>
	
	<div class="col-sm-4 wpb_column column_container">
		<div class="boton_fullwidth">
			<?php if ( $this->es_historial  ): ?>
				<a class="btn boton_copia_1 btn-primary btn-lg btn-special boton_fullwidth" href="<?php echo add_query_arg( array('servicios' => 'futuras' ) ) ?>"><i class="icon-tags"></i> Citas pendientes</a>
	
			<?php else: ?>
				<a class="btn boton_copia_1 btn-primary btn-lg btn-special boton_fullwidth" href="<?php echo add_query_arg( array('servicios' => 'historial' ) ) ?>"><i class="icon-tags"></i> Historial de mis citas</a>
				
			<?php endif; ?>
		</div>
		
		<div class="Calendario">
			<input id="calendario_post" placeholder="Calendario" name="fecha" />
			
			<?php 
			$servicios_proveedor = Estilotu_Miembro::listarServicios($current_user->ID);
			
			if ($servicios_proveedor->have_posts()): ?>
				<div class="" id="Servicios">
					<ul class="lista_servicios" id="lista_servicios_<?php echo $current_user->ID; ?>">
						<h2>Filtrar por Servicio</h2>
							<li class="elemento_lista_servicio">
								<a class="Ver_Todo">Ver todos</a>
							</li>
								
						<?php while ($servicios_proveedor->have_posts() ): $servicios_proveedor->the_post(); ?>
							<li class="elemento_lista_servicio">
								<a class="FiltrarServicio" id="<?php echo the_ID(); ?>"><?php the_title('<span>', '</span>'); ?></a>
							</li>
						<?php endwhile;?> 
					</ul>
				</div>
				<?php 
				wp_reset_postdata(); 
			else: ?>
				<h4>No hay servicios</h4>
				<?php 
			endif; ?>
			
		</div>
		
	</div>

	
</div>


<!--
<script> 
	jQuery(document).ready(function() {
		var logic = function( currentDateTime ){
			var fecha_seleccionada = new Date(currentDateTime);
		
			var day = fecha_seleccionada.getDate();
			var month = fecha_seleccionada.getMonth()+1;
			var year = fecha_seleccionada.getFullYear();
		
			fecha_seleccionada = year + '-' +
								(month<10 ? '0' : '') + month + '-' +
								(day<10 ? '0' : '') + day;
			
			jQuery(".Bloque").fadeOut("slow");
			
			if (jQuery("#Bloque_" + fecha_seleccionada).length ) {
				jQuery("#Bloque_" + fecha_seleccionada ).fadeIn("slow");
				jQuery("#mensaje").fadeOut("slow");
			} else {
				jQuery("#mensaje span").html(fecha_seleccionada);
				jQuery("#mensaje").fadeIn("slow");
			}
			
			
		};
		
		jQuery(".lista_servicios").on("click", ".Ver_Todo" ,function(){
			jQuery(".usuario").fadeIn("slow");
		});
		
		jQuery(".lista_servicios").on("click", ".FiltrarServicio" ,function(){
			servicio = ( jQuery(".FiltrarServicio").text() );
			
			jQuery(".servicio_reservado").each(function(i, obj) {
				var superior = jQuery(".servicio_reservado").closest(".usuario");
				
				if ( jQuery(this).text() == servicio ) {
					jQuery( superior ).fadeIn("slow");
				}
				
				else {
					jQuery( superior ).fadeOut("slow");
				}
			});		
		});
		
		
		jQuery("#calendario_post").datetimepicker({
			inline:true,
			lang:'es',
			minDate:'+1970/01/02',
			step:15,
			timepickerScrollbar:true,
			dayOfWeekStart: 1,
			timepicker:false,
			format:'Y-m-d',
			formatTime:'H:i',
			onChangeDateTime:logic,
			scrollMonth: false
		});

	});	
</script>
-->
