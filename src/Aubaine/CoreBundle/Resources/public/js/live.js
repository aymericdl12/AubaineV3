var jq = jQuery;
jq(document).ready( function() {

	var map;
	var arrMarkers = [];
	var monthNames = [
	    "janvier", "Février", "Mars",
	    "Avril", "Mai", "Juin", "Juillet",
	    "Août", "Septembre", "Octobre",
	    "Novembre", "Decembre"
	  ];



	// Print the map
	var map = L.map('map', {  minZoom: 13, maxZoom: 30 }).setView([43.6044292, 1.4438121000000592], 16);
	map.zoomControl.setPosition('bottomright');
	// load a tile layer
	L.tileLayer('https://api.mapbox.com/styles/v1/aymericdl2/cj1ypxtb0000g2sqry16pzm0o/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYXltZXJpY2RsMiIsImEiOiJjajF5b2dhMzIwMDBmMzNuenBsMXRpeWVoIn0.EGdbIzhLpXPATY5FzxdsHg', 
		{}).addTo(map);
	// alert(listAubaines.length);
	print_results(listAubaines );

	// Print user position
	if(navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(pos) {
			var circle = L.circle([pos.coords.latitude, pos.coords.longitude], {
					interactive :false,
				    color: '#318CE7',
				    fillColor: '#318CE7',
				    fillOpacity: 1,
				    radius: 5,
				    weight:1,
				}).addTo(map);
			var circle = L.circle([pos.coords.latitude, pos.coords.longitude], {
					interactive :false,
				    color: '#318CE7',
				    fillColor: '#318CE7',
				    fillOpacity: 0.17,
				    radius: 250,
				    weight:1,
				    opacity:1
				}).addTo(map);
		},function() { } );
	}

	function print_results(listAubaines){
		jQuery.each(listAubaines, function(i, val) {
			var id = val.id;
			var permanent = val.permanent;
			var start = val.start;
			var end = val.end;
			var message = val.message;
			var category = val.category;
			var place = val.place.title;
			var image = val.place.image_header;
			var authorLati = val.place.lati;
			var authorLongi = val.place.longi;
			var type = val.type;
			start_date = new Date(start);
			end_date = new Date(end);

			infos_deal = {
				"place": place,
				"start_date": start_date,
				"end_date": end_date,
				"category": category,
				"image": avatarDir+image,
				"message": message
			};
			// alert(Math.floor(start_date.getTime() / 1000));
			if(Math.floor(start_date.getTime() / 1000) <= current_timestamp){
				addMarkerByAdress(id, type, infos_deal, authorLati, authorLongi,1);
			}
			else{
				addMarkerByAdress(id, type, infos_deal, authorLati, authorLongi,0);
			}
		}); 	    
	}

    function addMarkerByAdress(id,deal_type, infos_deal,latti,longi,animated){

		var infobox_content;
		var marker_icon;
		if(deal_type==3){
			infobox_content =   '<a target="_blank" href="'+viewUrl.replace("toreplace", id)+'" class="dealMap" >' +
                                    '<div class="image" style="background-image:url(\''+infos_deal.image+'\')"></div>'+
	                                '<div class="content">'+
	                                	infos_deal.message+
                                    '</div>' +
                                    '<div class="place">'+
                                    	'<img src="'+imageDir+'location.png" alt="" class="location">'+
                                    	infos_deal.place+
                                    '</div>' +
                                    '<div class="dates">'+
                                    	'<img src="'+imageDir+'calendar.png" alt="" class="location">'+
                                    	'Le '+infos_deal.end_date.getDate()+' '+monthNames[infos_deal.end_date.getMonth()]+
                                    '</div>' +
                                '</a>'
		}
		else if(deal_type==2){
			infobox_content =   '<a target="_blank" href="'+viewUrl.replace("toreplace", id)+'" class="dealMap" >' +
                                    '<div class="image" style="background-image:url(\''+infos_deal.image+'\')"></div>'+
	                                '<div class="content">'+
	                                	infos_deal.message+
                                    '</div>' +
                                    '<div class="place">'+
                                    	'<img src="'+imageDir+'location.png" alt="" class="location">'+
                                    	infos_deal.place+
                                    '</div>' +
                                    '<div class="dates">'+
                                    	'<img src="'+imageDir+'calendar.png" alt="" class="calendar">';

			if(infos_deal.start_date.getMonth() != infos_deal.end_date.getMonth()){
			    infobox_content +='du '+infos_deal.start_date.getDate()+' '+monthNames[infos_deal.start_date.getMonth()]+' au '+infos_deal.end_date.getDate()+' '+monthNames[infos_deal.end_date.getMonth()];
			}
			else{
				infobox_content +='du '+infos_deal.start_date.getDate()+' au '+infos_deal.end_date.getDate()+' '+monthNames[infos_deal.end_date.getMonth()];
			}

            infobox_content +='</div></a>';	   				
		}
		else{
			infobox_content =   '<a target="_blank" href="'+viewUrl.replace("toreplace", id)+'" class="dealMap" >' +
                                    '<div class="image" style="background-image:url(\''+infos_deal.image+'\')"></div>'+
	                                '<div class="content">'+
	                                	infos_deal.message+
                                    '</div>' +
                                    '<div class="place">'+
                                    	'<img src="'+imageDir+'location.png" alt="" class="location">'+
                                    	infos_deal.place+
                                    '</div>' +
                                    '<div class="dates">'+
                                    	'<img src="'+imageDir+'calendar.png" alt="" class="location">'+
                                    	'Toute l\'année'+
                                    '</div>' +
	                            '</a>';
		}
		if(animated){
			var marker_icon = L.icon({
				    iconUrl: imageDir+infos_deal.category+".png",
				    className: 'marker-map marker-map-animated  marker-map-'+infos_deal.category,
				    iconSize:     [35, 35],
				    iconAnchor:   [17.5, 17.5],
				    popupAnchor:  [0, -10] 
				});	
		}
		else{
			var marker_icon = L.icon({
			    iconUrl: imageDir+infos_deal.category+".png",
			    className: 'marker-map marker-map-'+infos_deal.category,
			    iconSize:     [30, 30],
			    iconAnchor:   [15, 15],
			    popupAnchor:  [0, -10]
			});
		}

		var ll = L.latLng(latti, longi)
		var marker = L.marker(ll,{icon:marker_icon});
		marker.addTo(map);

		infobox_option={
			'className' : 'custom_infobox',
			maxWidth : 560
		}
		marker.bindPopup(infobox_content,infobox_option);
		// marker.on("click", function() {
		//   jq(".list_wrapper").animate({
		//         scrollTop: jq('#deal-'+id).offset().top
		//     }, 500);
		// });
		arrMarkers[id] = marker;
		// jq('#deal-'+id).hover(function(){
		// 	marker.fireEvent('click'); 
		// });
    }

    jq(".myAubaines-form").submit(function(event) {
        event.preventDefault();
        var form = jq(this);
        jq.ajax({
            beforeSend:  function() { 
                form.children(".form-submit-wrapper").children(".my-aubaine-form-submit").button('loading');
            },
            url: jq(this).attr('action'), // le nom du fichier indiqué dans le formulaire
            type: "POST", // la méthode indiquée dans le formulaire (get ou post)
            cache: false,
            data: form.serialize(),
            success: function(data) {

                // update map marker
                // if(form.parent().parent("tr").attr("id") == "current_aubaine"){
                //     aubaine = {
                //         "message": data.message
                //     };
                //     addMarkerByAdress(1, author, aubaine)
                // }
                form.replaceWith(   '<div  class="message">'+
                                        data.message+
                                        ' <a class="btn btn-xs btn-danger"  href ="'+ data.delete_link+'") }}">'+
                                           '<span class="glyphicon glyphicon-trash"></span>'+
                                        '</a>'+
                                	'</div>' );
            }       
        });
    });

   

}); //document ready