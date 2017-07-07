var jq = jQuery;
jq(document).ready( function() {

	var map;
	var arrMarkers = [];


	// Print the map
	var map = L.map('map').setView([43.6044292, 1.4438121000000592], 16);
	map.zoomControl.setPosition('bottomright');
	// load a tile layer
	L.tileLayer('https://api.mapbox.com/styles/v1/aymericdl2/cj1ypxtb0000g2sqry16pzm0o/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYXltZXJpY2RsMiIsImEiOiJjajF5b2dhMzIwMDBmMzNuenBsMXRpeWVoIn0.EGdbIzhLpXPATY5FzxdsHg', 
		{}).addTo(map);
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

		for (var i = 0; i <=listAubaines.length-1; i++) {
			var id = listAubaines[i].id;
			var permanent = listAubaines[i].permanent;
			var start = listAubaines[i].start;
			var end = listAubaines[i].end;
			var message = listAubaines[i].message;
			var category = listAubaines[i].category;
			var place = listAubaines[i].place.title;
			var image = listAubaines[i].place.image_header;
			var authorLati = listAubaines[i].place.lati;
			var authorLongi = listAubaines[i].place.longi;

			infos_deal = {
				"place": place,
				"start": start,
				"end": end,
				"category": category,
				"image": "<img src='"+avatarDir+image+"'>",
				"message": message
			};
			if(permanent){
				addMarkerByAdress(id, 3, infos_deal, authorLati, authorLongi);
			}
			else{
				addMarkerByAdress(id, 1, infos_deal, authorLati, authorLongi);
			}
		}	 	    
	}

    function addMarkerByAdress(id,deal_type, infos_deal,latti,longi){

		var infobox_content;
		var marker_icon;
		if(deal_type==1){
			infobox_content =   '<a target="_blank" href="'+viewUrl.replace("toreplace", id)+'" class="dealMap" >' +
                                    '<div class="image">'+
                                    	infos_deal.image +
                                    '</div>' +
	                                '<div class="content">'+
	                                	infos_deal.message+
                                    '</div>' +
                                    '<div class="place">'+
                                    	'<img src="'+imageDir+'location.png" alt="" class="location">'+
                                    	infos_deal.place+
                                    '</div>' +
                                    // '<div class="dates">'+
                                    	// 'du '+infos_deal.start+' au '+infos_deal.end;
                                    // '</div>' +
	                            '</a>';
			var marker_icon = L.icon({
			    iconUrl: imageDir+infos_deal.category+".png",
			    className: 'marker-map marker-map-'+deal_type+' marker-map-'+infos_deal.category,
			    iconSize:     [35, 35], // size of the icon
			    iconAnchor:   [17.5, 17.5], // point of the icon which will correspond to marker's location
			    popupAnchor:  [0, -10] // point from which the popup should open relative to the iconAnchor
			});	   				
		}
		else{
			infobox_content =   '<a target="_blank" href="'+viewUrl.replace("toreplace", id)+'" class="dealMap" >' +
                                    '<div class="image">'+
                                    	infos_deal.image +
                                    '</div>' +
	                                '<div class="content">'+
	                                	infos_deal.message+
                                    '</div>' +
                                    '<div class="place">'+
                                    	'<img src="'+imageDir+'location.png" alt="" class="location">'+
                                    	infos_deal.place+
                                    '</div>' +
	                            '</a>';
			var marker_icon = L.icon({
			    iconUrl: imageDir+infos_deal.category+".png",
			    className: 'marker-map marker-map-'+deal_type+' marker-map-'+infos_deal.category,
			    iconSize:     [30, 30], // size of the icon
			    iconAnchor:   [15, 15], // point of the icon which will correspond to marker's location
			    popupAnchor:  [0, -10] // point from which the popup should open relative to the iconAnchor
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
		arrMarkers[id] = marker;
		jq('#deal-'+id).hover(function(){
			marker.fireEvent('click'); 
		});
    }

    jq('.nav a').on('click', function(){
    	if(jq('.navbar-toggle').css('display') !='none'){
	    	jq('.navbar-toggle').click() 
		}
	});

	jq(".show-map-button").click(function(){

		if(jq( window ).width()<=650){
        	jq(".navbar-nav li.active").removeClass('active');
		}
        jq("#map_wrapper").css({"z-index":"4"});

    });

    jq("#menu-button-accueil").click(function(e){

    	e.preventDefault();
        jq("#menu-button-accueil").addClass("active");
        jq("#map_wrapper").css({"z-index":"2"});

        if(jq(".navbar-nav li.active").length !== 0){
            var old_column=jq(".navbar-nav li.active").removeClass('active').attr('id').replace('menu-tab','home_block');
        }
        
    });

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