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



<!-- Viene llamado por AJAX via cargar_servicio -->
<style>
	
	.smart-forms .radio  {padding-left:0px !important;}
	.smart-forms .frm-row {
    	margin: 0px; 
	    padding: 0px 20px;
	}
	.smart-steps .wizard > .steps {z-index:1;}
	
	#map-canvas img {max-width: none;}
	
	#description_ifr {border-left:1px solid #EEE; border-right:1px solid #EEE;height:300px !important;}
	
	#contenedor_disponibilidad .frm-row {
		margin: 10px;
		padding: 10px 0px 10px 20px;
		background: #F4F4F4;
	}
	
	#contenedor_disponibilidad select {background: #FFF;}
	
	#contenedor_disponibilidad a.clone {background-color:#6925a5; color:#FFF;}
	#contenedor_disponibilidad a.delete {background-color:#aa1d1d; color:#FFF;}
	
</style>
            
<div class="smart-wrap">

    <div class="smart-forms">
        
		<div class="form-body theme-purple smart-steps steps-theme-purple">
			
            <form method="post" action="<?php the_permalink(); ?>" id="formulario_servicio" enctype="multipart/form-data">
				<!-- DATOS DEL SERVICIO -->
                <h2>Servicio</h2>
                <fieldset>
                	
                    <div class="frm-row">

                        <div class="section colm colm8">
                            <label for="nombre_servicio" class="field-label">Nombre del servicio <em> * </em> </label>
                            <label class="field prepend-icon">
                                <input type="text" name="nombre_servicio" id="nombre_servicio" class="gui-input required" placeholder="Nombre del servicio" aria-required="true" maxlength="65" value="<?php echo isset($this->servicio->post_title) ? $this->servicio->post_title : '' ?>">
                                <span class="field-icon"><i class="fa fa-pencil-square-o"></i></span>  
                                <span class="small-text block spacer-t10 fine-grey"> El nombre no debe tener más de 50 caracteres </span>
                            </label>
                        </div><!-- end section -->
                        
                        
                        
                        <div class="section colm colm4">
                            <label for="tipo" class="field-label">Tipo de servicio <em> * </em> </label>
                            <label class="field prepend-icon">
                                <input type="text" name="et_meta_tipo" id="tipo" class="gui-input" placeholder="cupos" value="cupos" readonly>
                                <span class="field-icon"><i class="fa fa-check-square-o"></i></span>  
                            </label>
                        </div><!-- end section -->
                    </div><!-- end .frm-row section -->
                    
					<div class="spacer-t20 spacer-b30">
						<div class="tagline"><span> Costos del servicio </span></div><!-- .tagline -->
					</div> 
                    
                    <div class="frm-row">
                        <div class="section colm colm4 ">
                        	<label for="et_meta_precio_moneda" class="field-label">Tipo de moneda <em> * </em> </label>
                            
                            <label class="field select">
                                
                                <?php $moneda = isset($this->servicio_meta['et_meta_precio_moneda'][0]) ? $this->servicio_meta['et_meta_precio_moneda'][0] : '' ?>

								<select name="et_meta_precio_moneda" id="et_meta_precio_moneda">
						            <option value="VEF" <?php selected( $moneda, "VEF" ); ?>>Bolivares</option>
						            <option value="USD" <?php selected( $moneda, "USD" ); ?>>Dolares Americanos</option>
						            <option value="EU" <?php selected( $moneda, "EU" ); ?>>Euros</option>
						        </select>
                                <i class="arrow double"></i>                    
                            </label>  
                        </div>
                        
                        <!-- ****************** -->
                    	<!-- PRECIO SERVICIO -->
                    	<!-- ****************** -->  
                    	<div class="section colm colm4">
                        	<label for="et_meta_precio" class="field-label">Precio <em> * </em> </label>
                            <label class="field">
                                <label class="field">
		                        	<input type="text" class="gui-input required auto" name="et_meta_precio" id="et_meta_precio" aria-required="true" placeholder="Precio del evento..." data-v-max="9999999" data-v-min="0" value="<?php echo isset($this->servicio_meta['et_meta_precio'][0]) ? $this->servicio_meta['et_meta_precio'][0] : '' ?>">
		                        </label>
                                <b class="tooltip tip-left-bottom"><em> Indique el precio de su servicio.</em></b>
                            </label>
                        </div>
                        <!-- ****************** -->	
                        
                        <!-- ****************** -->
                    	<!-- PRECIO VISIBILIDAD -->
                    	<!-- ****************** -->  
                    	<div class="section colm colm4 ">
                        	<span class="kleo-pin-circle hover-pop animated animate-when-almost-visible el-appear start-animation" data-toggle="popover" data-container="body" data-title="Tipo de precio" data-content="Determina cómo los usuarios verán los precios de su servicios.  <ul><li><strong>Público (recomendado):</strong> Permite a todos los usuarios ver su precio</li><li><strong>Privado:</strong> Los usuarios no podrán ver el precio de su servicio.</li><li><strong>Oculto:</strong> Muestra el precio unicamente si un usuario solicita verlo.</li></ul>" data-placement="left" data-top="-5px" data-left="-15px" style="top: -50px; left: -15px;" data-original-title="" title=""><span></span></span>
                        	
                        	<label for="et_meta_precio_visibilidad" class="field-label">El precio será <em> * </em> </label>
                            
                            <label class="field select">
                                <?php $moneda_visibilidad = isset($this->servicio_meta['et_meta_precio_visibilidad'][0]) ? $this->servicio_meta['et_meta_precio_visibilidad'][0] : '' ?>
							
								<select name="et_meta_precio_visibilidad" id="et_meta_precio_visibilidad">
						            <option value="public" <?php selected( $moneda_visibilidad, "public" ); ?>>Público</option>
						            <option value="private" <?php selected( $moneda_visibilidad, "private" ); ?>>Privado</option>
						            <option value="hidden" <?php selected( $moneda_visibilidad, "hidden" ); ?>>Oculto</option>
						        </select>
                                <i class="arrow double"></i>                    
                            </label>  
                        </div><!-- end section -->
                        <!-- ****************** -->
                    </div>
                    
                    <div class="spacer-t20 spacer-b30">
						<div class="tagline"><span> Opciones de reserva </span></div><!-- .tagline -->
					</div> 
                    
                    <!-- ****************** -->
                	<!-- TIEMPO PARA RESERVA -->
                	<!-- ****************** -->  
                    <div class="frm-row">
                        <div class="section colm colm6 ">
                        	<span class="kleo-pin-circle hover-pop animated animate-when-almost-visible el-appear start-animation" data-toggle="popover" data-container="body" data-title="Días de antelación" data-content="Evita que un usuario solicite un cupo con demasiado tiempo de antelación." data-placement="top" data-top="-5px" data-left="-15px" style="top: -5px; left: -15px;" data-original-title="" title=""><span></span></span>
                        	
                        	<label for="et_meta_max_time" class="field-label">D&iacute;as de anticipaci&oacute;n para aceptar reservas <em> * </em> </label>
                            
                            <label class="field select">

                                <?php $max_time = isset($this->servicio_meta['et_meta_max_time'][0]) ? $this->servicio_meta['et_meta_max_time'][0] : '' ?>
							
								<select name="et_meta_max_time" id="et_meta_max_time">
						            <option value="7" <?php selected( $max_time, 7 ); ?>>7 d&iacute;as</option>
						            <option value="14" <?php selected( $max_time, 14 ); ?>>14 d&iacute;as</option>
						            <option value="21" <?php selected( $max_time, 21 ); ?>>21 d&iacute;as</option>
						            <option value="30" <?php selected( $max_time, 30 ); ?>>30 d&iacute;as</option>
						        </select>
                                <i class="arrow double"></i> 
                            </label>  
                        </div><!-- end section -->
                    	
                        <!-- ****************** -->
                    	<!-- TIEMPO PARA RESERVA -->
                    	<!-- ****************** -->                    	
                    	<div class="section colm colm6 ">
                        	<span class="kleo-pin-circle hover-pop animated animate-when-almost-visible el-appear start-animation" data-toggle="popover" data-container="body" data-title="Cierre de cupos" data-content="Evita que un usuario solicite un cupo minutos antes del servicio. Esto cerrará las reservas y enviará el listado de cupos realizados hasta ese momento" data-placement="top" data-top="-5px" data-left="-15px" style="top: -5px; left: -15px;" data-original-title="" title=""><span></span></span>
                        	
                        	<label for="et_meta_close_time" class="field-label">Tiempo previo para cerrar las reservas <em> * </em> </label>
                            <b class="tooltip tip-left-bottom"><em> Indique el precio de su servicio.</em></b>
                            
                            <label class="field select">
                                <?php $et_meta_close_time = isset($this->servicio_meta['et_meta_close_time'][0]) ? $this->servicio_meta['et_meta_close_time'][0] : '' ; ?>
							
								<select name="et_meta_close_time" id="et_meta_close_time">
						            <option value="1800" <?php selected( $et_meta_close_time,  1800 ); ?>>30 minutos</option>
						            <option value="3600" <?php selected( $et_meta_close_time,  3600 ); ?>>1 hora</option>
						            <option value="5400" <?php selected( $et_meta_close_time,  5400 ); ?>>1 hora 30 minutos</option>
						            <option value="7200" <?php selected( $et_meta_close_time,  7200 ); ?>>2 horas</option>
						            <option value="9000" <?php selected( $et_meta_close_time,  9000 ); ?> >2 horas 30 minutos</option>
						            <option value="10800" <?php selected( $et_meta_close_time, 10800 );?>>3 horas</option>
						            <option value="14400" <?php selected( $et_meta_close_time, 14400 ); ?>>4 horas </option>
						            <option value="18000" <?php selected( $et_meta_close_time, 18000 ); ?>>5 horas</option>
						            <option value="21600" <?php selected( $et_meta_close_time, 21600 ); ?>>6 horas</option>
						            <option value="28800" <?php selected( $et_meta_close_time, 28800 ); ?>>8 horas</option>
						            <option value="36000" <?php selected( $et_meta_close_time, 36000 ); ?>>10 horas</option>
						            <option value="43200" <?php selected( $et_meta_close_time, 43200 ); ?>>12 horas</option>
						            <option value="86400" <?php selected( $et_meta_close_time, 86400 ); ?>>24 horas</option>
						            <option value="172800" <?php selected( $et_meta_close_time, 172800 ); ?>>48 horas</option>
						        </select>
                                <i class="arrow double"></i>  
                            </label>  
                        </div><!-- end section --> 
					</div>
                                                                                  
                </fieldset>
				<!-- DATOS DEL SERVICIO -->
						
						
				<!-- DATOS DEL SERVICIO -->
                <h2>Detalles</h2>
                <fieldset>
                
                    <div class="frm-row">
						<!-- ****************** -->
	                   	<!-- FILA 3 - CATEGORIA -->
	                   	<!-- ****************** -->	
	                    <div class="frm-row">
	  
			                <div class="spacer-t20 spacer-b30">
		                    	<div class="tagline"><span> Categoría del servicio </span></div><!-- .tagline -->
							</div>
	                    
			                <div class="section">
		                    	<p class="small-text fine-grey">A que categoria pertecene su servicio</p>
		                    </div><!-- end section -->
		                    
		                    <label class="field select">
                                <?php 
	                                $args = array(
										'show_option_all'    => '',
										'show_option_none'   => '',
										'option_none_value'  => '-1',
										'orderby'            => 'name', 
										'order'              => 'ASC',
										'show_count'         => 0,
										'hide_empty'         => 0, 
										'child_of'           => 0,
										'exclude'            => '',
										'echo'               => 1,
										'selected'           => isset($this->servicios_categoria[0]->slug) ? $this->servicios_categoria[0]->slug : '0' ,
										'hierarchical'       => 1, 
										'name'               => 'cat_servicio',
										'id'                 => '',
										'class'              => 'categorias',
										'depth'              => 0,
										'tab_index'          => 1,
										'taxonomy'           => 'servicios-categoria',
										'hide_if_empty'      => false,
										'value_field'	     => 'slug'
										); 
	                                wp_dropdown_categories( $args );
	                                
                                ?>
                                <i class="arrow double"></i>                    
                            </label> 
		                    
	                    </div>	
	                    <!-- ****************** -->	
	                    
	                    <div class="spacer-t20 spacer-b30">
	                    	<div class="tagline"><span> Imagen destacada del servicio </span></div><!-- .tagline -->
	                    </div> 
	                    
	                    <!-- ****************** -->
	                   	<!-- FILA 4 - IMAGEN -->
	                   	<!-- ****************** -->	
	                    <div class="frm-row">
		                   <div class="section">
	                            
	                            <?php 
								if ( has_post_thumbnail ($this->servicio->ID) ): 
									echo ( get_the_post_thumbnail( $this->servicio->ID, 'thumbnail') );
		                             
								else: ?>
		                            <label class="field prepend-icon file">
		                                <span class="button btn-primary"> Elegir portada </span>
		                                <input type="file" class="gui-file" name="imagen_destacada" id="imagen_destacada" onChange="document.getElementById('orderupload').value = this.value;">
		                                <input type="text" class="gui-input" id="orderupload" placeholder="Portada del Servicio" readonly>
		                                <span class="field-icon"><i class="fa fa-upload"></i></span>
		                            </label>
		                            <span class="small-text block spacer-t10 fine-grey"> Solo se permiten imágenes tipo JPG y PNG - Máximo de 1MB </span> 

								<?php
								endif;
								?>
								
	                        </div><!-- end  section -->	

	                    </div>
	                    
	                    <div class="spacer-t20 spacer-b30">
	                    	<div class="tagline"><span> Descripci&oacute;n del servicio </span></div><!-- .tagline -->
	                    </div> 
			            
			            
						<!-- ****************** -->
						<!-- FILA 5 - DESCRIPCION -->
						<!-- ****************** -->	        
		            	<div class="section">
		                	<em for="description" id="error_descripcion" class="" style="display:none;">Por favor selecciona la categoría a la que pertenece tu servicio</em>
		                	<label for="description" class="field-label">Descripci&oacute;n <em> * </em> </label>
		                	<?php $content = isset($this->servicio->post_content) ? $this->servicio->post_content : '' ;
		                	
							$editor_id = 'description';
							$editro_settings = array (
								'media_buttons' => false,
								'quicktags'		=> false,
								'wpautop'		=> false
							);
							
							wp_editor( $content, $editor_id, $editro_settings );
	                    	?>
		                </div><!-- end section -->  
	                    <!-- ****************** -->	
	                    
	                    
                    </div>

				</fieldset>
				
				
                <h2>Ubicación</h2>
                <fieldset>
                    
                    <div class="frm-row">
                        <div class="section colm colm12">
	                        
							<div class="section">
							    <div class="option-group field">
							    
							        <label class="switch">
							            <input type="checkbox" class="ShowHide" data-show-id="contenedor_mapa" name="ubicacion[et_meta_usar_mapa]" id="et_meta_usar_mapa" <?php isset( $this->servicio_meta['et_meta_usar_mapa'][0] ) ? checked( $this->servicio_meta['et_meta_usar_mapa'][0], 'on' ) : checked( $this->servicio_meta['et_meta_usar_mapa'][0], 'off' ); ?>>
							            <span class="switch-label" data-on="ON" data-off="OFF"></span>
							            <span> Deseo mostrar la ubicaci&oacute;n de mi servicio </span>
							        </label>										                            
							    </div><!-- end .option-group section -->
							</div>
                        </div>
                    </div>
						
                       
                     <div class="frm-row" id="contenedor_mapa" style="display:none;">    	                    
                        <div class="section colm colm6">
							<label class="field select">							
								<select name="ubicacion[et_meta_pais]" id="et_meta_pais" class="crs-country" >
							    </select>
							    <i class="arrow double"></i>                    
							</label> 
                        </div>
                        
						
						<div class="section colm colm6">	
							<label class="field select">							
								<select name="ubicacion[et_meta_estado]" id="et_meta_estado">
							    </select>
							    <i class="arrow double"></i>                    
							</label>  
                        </div>
                        
                        <script>
							jQuery(function( $ ) {	
								
								populateCountries("et_meta_pais", "et_meta_estado" , "<?php echo !empty($this->servicio_meta['et_meta_pais'][0] ) ? $this->servicio_meta['et_meta_pais'][0] : '' ;  ?>" , "<?php echo !empty($this->servicio_meta['et_meta_estado'][0] ) ? $this->servicio_meta['et_meta_estado'][0] : ''?>" );
								
							});
						</script>
												
						<div class="section colm colm6">
                            <label for="et_meta_ciudad" class="field prepend-icon">
                                <input type="text" name="ubicacion[et_meta_ciudad]" id="et_meta_ciudad" class="gui-input" placeholder="Ciudad" value="<?php echo isset( $this->servicio_meta['et_meta_ciudad'][0] ) ? $this->servicio_meta['et_meta_ciudad'][0] : '' ;  ?>">
                                <label class="field-icon"><i class="fa fa-pencil-square-o"></i></label>  
                            </label>
                        </div>
                        
                        <div class="section colm colm6">
                            <label for="et_meta_zipcode" class="field prepend-icon">
                                <input type="text" name="ubicacion[et_meta_zipcode]" id="et_meta_zipcode" class="gui-input" placeholder="Codigo Postal" value="<?php echo isset( $this->servicio_meta['et_meta_zipcode'][0] ) ? $this->servicio_meta['et_meta_zipcode'][0] : '' ;  ?>">
                                <label class="field-icon"><i class="fa fa-pencil-square-o"></i></label>  
                            </label>
                        </div>
                        
                         <div class="section colm colm12">
							<label for="et_meta_direccion" class="field-label">Dirección del servicio <em> * </em> </label>

		                	<label for="et_meta_direccion" class="field prepend-icon">
		                    	<textarea class="gui-textarea" id="et_meta_direccion" name="ubicacion[et_meta_direccion]" placeholder="Ubicación de su servicio..."><?php echo isset( $this->servicio_meta['et_meta_direccion'][0] ) ? $this->servicio_meta['et_meta_direccion'][0] : '' ;  ?></textarea>
		                        
		                        <label class="field-icon"><i class="fa fa-file-text"></i></label>
		                        <span class="input-hint"> <strong>Hint:</strong> Sea lo m&aacute;s descriptivo posible.</span>   
		                    </label>
						</div><!-- end section -->  
						
						<div class="section colm colm12">
						    <input type="button" class="button btn-purple" style="width: 100%;" value="Colocar ubicación en el mapa" onclick="codeAddress()">
						</div>
						
						<div class="section colm colm12 et_mapa" id="et_mapa">

							<h4>Puedes arrastrar la marca de la ubicaci&oacute;n en el mapa para mayor presici&oacute;n</h4>
							<div id="map-canvas" style="height:500px;"></div>
							
						</div>
						
						<div class="section colm colm6 et_mapa">
							<label for="et_meta_latitud" class="field-label">Latitud</label>
                            <label for="et_meta_latitud" class="field prepend-icon">
                                <input type="number" name="ubicacion[et_meta_latitud]" id="et_meta_latitud" class="gui-input" placeholder="Ingrese la latitud" value="<?php echo isset( $this->servicio_meta['et_meta_latitud'][0] ) ? $this->servicio_meta['et_meta_latitud'][0] : '' ;  ?>">
                                <label class="field-icon"><i class="fa fa-pencil-square-o"></i></label>  
                            </label>
                        </div>
						
						<div class="section colm colm6 et_mapa">
							<label for="et_meta_longitud" class="field-label">Longitud</label>
							
                            <label for="et_meta_longitud" class="field prepend-icon">
                                <input type="number" name="ubicacion[et_meta_longitud]" id="et_meta_longitud" class="gui-input" placeholder="Ingrese la longitud" value="<?php echo isset( $this->servicio_meta['et_meta_longitud'][0] ) ? $this->servicio_meta['et_meta_longitud'][0] : '' ;  ?>">
                                <label class="field-icon"><i class="fa fa-pencil-square-o"></i></label>  
                            </label>
                        </div>
                                               
	                </div>
                    
                                                                                                               
                    
                </fieldset>
                
                <h2>Disponibilidad</h2>
                <fieldset>
				    <div class="section">
				        <p class="small-text fine-grey">Seleccione los días que su servicio estará disponible </p>
				    </div><!-- end section -->
				    
				    <div id="contenedor_disponibilidad">
				       
						<?php 
						for ($dia = 0; $dia <= 6; $dia++): 
							
							switch ($dia) {
								
								case 0:
									$dia_txt = "lunes";
									break;
									
								case 1:
									$dia_txt = "martes";
									break;
									
								case 2:
									$dia_txt = "miercoles";
									break;
									
								case 3:
									$dia_txt = "jueves";
									break;
									
								case 4:
									$dia_txt = "viernes";
									break;
									
								case 5:
									$dia_txt = "sabado";
									break;
									
								case 6:
									$dia_txt = "domingo";
									break;
							}
							
							?>
				            
				            <div class="spacer-t20 spacer-b30">
								<div class="tagline"><span> Bloque del <?php echo $dia_txt; ?> </span></div><!-- .tagline -->
							</div> 
							
				            <div class="option-group field">									
				                <label class="option">
				                    <input type="checkbox" name="disponible[<?php echo $dia_txt ?>][activo]" class="ShowHide" value="<?php echo $disponible[$dia_txt]["activo"] == "on" ? "on" : 'off' ?>" data-show-id="contenedor_<?php echo $dia_txt; ?>" <?php echo isset( $disponible[$dia_txt]["activo"] ) ? "checked" : '' ?> >
									<span class="checkbox"></span> <?php echo $dia_txt; ?>  
				                </label>
				            </div>
				            
				            <div id="contenedor_<?php echo $dia_txt; ?>" class="contenedor_dia">
				                
				                <?php 
								
								if ($disponible[$dia_txt]["activo"] ): 
									$key = 0;
									foreach ( $disponible[$dia_txt]["bloque"] as $cupo ):

						                $hora_final = $cupo["et_meta_hora_inicio"]; ?>

						                <div class="clone_<?php echo $dia_txt; ?> frm-row" id="<?php echo $dia_txt . "_" . $dia . "_" . $key ?>" >

											<!-- HORA -->	
											<div class='section colm colm4 hora_inicio'>	
												<label class='field-label'>Hora de inicio<em> * </em> </label>
												<span class="small-text block spacer-t10 fine-grey"> Cualquier hora menor a 12 es de mañana y mayor a 12 es de tarde </span>
					                            <label for="resultadoHora_<?php echo $dia_txt ?>_<?php echo $dia ?>_<?php echo $key ?>" class="field prepend-icon">
					                            	<input readonly type="text" id="resultadoHora_<?php echo $dia_txt ?>_<?php echo $dia ?>_<?php echo $key ?>" name="disponible[<?php echo $dia_txt ?>][bloque][<?php echo $key ?>][et_meta_hora_inicio]" class="gui-input timepicker" value="<?php echo $hora_final; ?>">
					                                <span class="field-icon"><i class="fa fa-clock-o"></i></span>  
					                            </label>
											</div>												
											
											<div class='section colm colm3 duracion_servicio'>
												<label for="duracion_<?php echo $dia_txt . "_" . $dia . "_" . $key ?>" class='field-label'>Duraci&oacute;n <em> * </em> </label>
												<label class='field select'>
												    <select name="disponible[<?php echo $dia_txt ?>][bloque][<?php echo $key ?>][et_meta_duracion]" class="duracion" id="duracion_<?php echo $dia_txt . "_" . $dia . "_" . $key ?>">
													    <?php for ($i=1; $i < 21 ; $i++):
															$minutos = $i * 15;
															$horas = floor($minutos / 60);
															
															if ($minutos % 60 != 0)
																$cuarto_hora = ($minutos % 60) + " minutos";
															else 
																$cuarto_hora = ""; 
															
															if ($horas < 1) 
																$result = $minutos . " minutos" ; 
																
															if ($horas == 1) 
																$result = $horas . " hora " . $cuarto_hora;
														
															else if ($horas > 1) 
															 	$result = $horas . " horas " . $cuarto_hora;
															?>
															
															<option value="<?php echo $minutos ?>" <?php selected( $cupo["et_meta_duracion"], "$minutos" ); ?> >
																<?php echo $result ?>
															</option>
														<?php endfor; ?>
												    </select>
												    <i class='arrow double'></i>
												</label>
											</div>
									
											<div class='section colm colm3 cupos_disponibles'>
											   <label for="disponible[<?php echo $dia_txt ?>][bloque][<?php echo $key ?>][et_meta_cupos]" class='field-label'>Cupos <em> * </em> </label>
											   <label for="disponible[<?php echo $dia_txt ?>][bloque][<?php echo $key ?>][et_meta_cupos]" class='field prepend-icon'>
											   <input type='number' name="disponible[<?php echo $dia_txt ?>][bloque][<?php echo $key ?>][et_meta_cupos]" class='gui-input required et_meta_cupos' id="cupos_<?php echo $dia_txt . "_" . $dia . "_" . $key ?>" placeholder='Cupos disponibles por evento...' min="0" max="999" value="<?php echo $cupo["et_meta_cupos"];  ?>">
											   <label for='cupos' class='field-icon'>
											   		<i class='fa fa-ticket'></i>
											   	</label>
											   </label>
											</div>	
											
											<div class='section colm colm2 controles'>
												<a href="#" class="clone button btn-primary"><i class="fa fa-plus"></i></a>
												<a href="#" class="delete button"><i class="fa fa-minus"></i></a>														
											</div>
											
											

						                </div>
					                
					                <?php 
									$key++;
									endforeach;
									?>
									
									

						        <?php
						        else: 
						        	
						        	$key = 0; ?>
						        
						        	<div class="clone_<?php echo $dia_txt; ?> frm-row" id="<?php echo $dia_txt . "_" . $dia . "_" . $key ?>" >

										<!-- HORA -->	
										<div class='section colm colm4 hora_inicio'>	
											<label class='field-label'>Hora de inicio<em> * </em> </label>
												<span class="small-text block spacer-t10 fine-grey"> Cualquier hora menor a 12 es de mañana y mayor a 12 es de tarde </span>
					                            <label for="resultadoHora_<?php echo $dia_txt ?>_<?php echo $dia ?>_<?php echo $key ?>" class="field prepend-icon">
					                            	<input readonly type="text" id="resultadoHora_<?php echo $dia_txt ?>_<?php echo $dia ?>_<?php echo $key ?>" value="<?php echo $hora_final; ?>" name="disponible[<?php echo $dia_txt ?>][bloque][<?php echo $key ?>][et_meta_hora_inicio]" class="gui-input timepicker" value="00:00:00">
					                                <span class="field-icon"><i class="fa fa-clock-o"></i></span>  
					                            </label>
										</div>												
										
										<div class='section colm colm3 duracion_servicio'>
											<label for="duracion_<?php echo $dia_txt . "_" . $dia . "_" . $key ?>" class='field-label'>Duraci&oacute;n <em> * </em> </label>
											<label class='field select'>
											    <select name="disponible[<?php echo $dia_txt ?>][bloque][<?php echo $key ?>][et_meta_duracion]" class="duracion" id="duracion_<?php echo $dia_txt . "_" . $dia . "_" . $key ?>">
												    <?php for ($i=1; $i < 21 ; $i++):
														
														$minutos = $i * 15;
														$horas = floor($minutos / 60);
														
														if ($minutos % 60 != 0)
															$cuarto_hora = ($minutos % 60) + " minutos";
														else 
															$cuarto_hora = ""; 
														
														if ($horas < 1) 
															$result = $minutos . " minutos" ; 
															
														if ($horas == 1) 
															$result = $horas . " hora " . $cuarto_hora;
													
														else if ($horas > 1) 
														 	$result = $horas . " horas " . $cuarto_hora;
														?>
														
														<option value="<?php echo $minutos ?>">
															<?php echo $result ?>
														</option>
														
													<?php endfor; ?>
											    </select>
											    <i class='arrow double'></i>
											</label>
										</div>
								
										<div class='section colm colm3 cupos_disponibles'>
										   <label for="disponible[<?php echo $dia_txt ?>][bloque][<?php echo $key ?>][et_meta_cupos]" class='field-label'>Cupos <em> * </em> </label>
										   <label for="disponible[<?php echo $dia_txt ?>][bloque][<?php echo $key ?>][et_meta_cupos]" class='field prepend-icon'>
										   <input type='number' name="disponible[<?php echo $dia_txt ?>][bloque][<?php echo $key ?>][et_meta_cupos]" class='gui-input required et_meta_cupos' id="cupos_<?php echo $dia_txt . "_" . $dia . "_" . $key ?>" placeholder='Cupos disponibles por evento...' min="0" max="999" value="0">
										   <label for='cupos' class='field-icon'>
										   		<i class='fa fa-ticket'></i>
										   	</label>
										   </label>
										</div>	
										
										<div class='section colm colm2 controles'>
											<a href="#" class="clone button btn-primary"><i class="fa fa-plus"></i></a>
											<a href="#" class="delete button"><i class="fa fa-minus"></i></a>														
										</div>

									</div>
						        
						        
								<?php 
								endif; ?>
								       
				            </div>
				            
				        <?php endfor; ?>  
				            
				    </div><!-- end #clone-animate --> 
			    
				</fieldset>                            
                        
	            <h2>Publicar</h2>
	            <fieldset>
	                <div class="section">
	                	<div class="notification alert-info">
	                    	<p>Por favor revise toda su información antes de publicar</p>
	                    </div>
	                    
	                    
	                </div><!-- end section -->
	                
	                <div class="section">
	                
	                    <div class="section">
	                        <div class="option-group field">
	                            <label class="option option-black">
	                                <input type="checkbox" name="generalTerms" value="General Terms" aria-required="true" required>
	                                <span class="checkbox"></span> 
	                                Por favor <a href="#" class="smart-link"> lea y acepte </a> nuestros términos y condiciones                
	                            </label>
	                        </div>
	                    </div><!-- end section -->                                 
	                	
	                </div><!-- end section -->
	                
	                
	                <?php echo $this->en_edicion ? "<input type='hidden' name='servicio_status' value='et_updating'>" : "" ?>
	                <?php echo $this->en_edicion ? "<input type='hidden' name='post_id' value='$this->post_id'>" : "" ?>
					<?php wp_nonce_field( 'publicar_servicio', 'publicar_servicio_nonce' ); ?>
					
					<div class="result"></div>
					
	            </fieldset>
            
			</form>                                                                                   
    
        </div><!-- end .form-body section -->
            
    </div><!-- end .smart-forms section -->
    
</div><!-- end .smart-wrap section -->