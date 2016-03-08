var geocoder;
var map;
var marker;
var image_et;
var position;
var latlng;
var et_lat = jQuery('#et_meta_latitud').val();
var et_lng = jQuery('#et_meta_longitud').val();

function initialize() {
	geocoder = new google.maps.Geocoder();
		
	latlng = new google.maps.LatLng( et_lat , et_lng );		
		
	jQuery('.et_mapa').show();
	
	var mapOptions = {
		zoom: 12,
		center: latlng
	};
	
	image_et = 'http://estilotu.com/wp-content/uploads/2015/03/Marker_ET_64.png';
	
	map = new google.maps.Map( document.getElementById('map-canvas'), mapOptions );
	
	marker = new google.maps.Marker({
		map: map,
		position: latlng,
		draggable:true,
		icon: image_et,
		title:"Aquí va tu servicio!"
	});
	
	google.maps.event.addListener(marker, "position_changed", function() {
		position = marker.getPosition();
		
		jQuery('#et_meta_latitud').val(this.getPosition().lat());
		jQuery('#et_meta_longitud').val(this.getPosition().lng());
		
	});
	
}

function codeAddress() {
	var address = document.getElementById('et_meta_direccion').value;
	address += " "+document.getElementById('et_meta_ciudad').value;
	address += " "+document.getElementById('et_meta_estado').value;
	address += " "+document.getElementById('et_meta_pais').value;
	address += " "+document.getElementById('et_meta_zipcode').value;
	
	jQuery('.et_mapa').show();
		
	geocoder = new google.maps.Geocoder();	
			
	var mapOptions = {
	zoom: 12,
	center: latlng
	};
	
	image_et = 'http://estilotu.com/wp-content/uploads/2015/03/Marker_ET_64.png';
	
	map = new google.maps.Map( document.getElementById('map-canvas'), mapOptions );
	
	marker = new google.maps.Marker({
		map: map,
		position: latlng,
		draggable:true,
		icon: image_et,
		title:"Aquí va tu servicio!"
	});
	
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {

			map.setCenter(results[0].geometry.location);
			
			if (!marker) {
				marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location,
					draggable:true,
					icon: image_et,
					title:"Aquí va tu servicio!"
				});
				
				jQuery('#et_meta_latitud"]').val( results[0].geometry.location.lat() );
				jQuery('#et_meta_longitud').val( results[0].geometry.location.lng() );
																
			} else {
				marker.setPosition( results[0].geometry.location );
				jQuery('#et_meta_latitud').val( results[0].geometry.location.lat() );
				jQuery('#et_meta_longitud').val( results[0].geometry.location.lng() );
			}
			
			
			// CUANDO SE MUEVE EL MARKER
			google.maps.event.addListener(marker, "position_changed", function() {
				position = marker.getPosition();
				
				jQuery('#et_meta_latitud').val(this.getPosition().lat());
				jQuery('#et_meta_longitud').val(this.getPosition().lng());
				
			});

		} else {

			alert("No se pudo colocar la ubicación por el siguiente motivo: " + status);

		}
	});
}


if ( ( typeof(et_lat) != "undefined" && et_lat !== null ) && ( typeof(et_lng) != "undefined" && et_lng !== null) ) {
	google.maps.event.addDomListener( window, 'load', initialize );
}