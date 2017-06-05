var jq = jQuery;
jq(document).ready( function() {

	var map;
	var arrMarkers = [];


	// Print the map
		var map = L.map('map').setView([43.6044292, 1.4438121000000592], 15);
		map.zoomControl.setPosition('bottomright');
	// load a tile layer
	L.tileLayer('https://api.mapbox.com/styles/v1/aymericdl2/cj1ypxtb0000g2sqry16pzm0o/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYXltZXJpY2RsMiIsImEiOiJjajF5b2dhMzIwMDBmMzNuenBsMXRpeWVoIn0.EGdbIzhLpXPATY5FzxdsHg', 
		{}).addTo(map);
	// print_results(listAubaines);

	jq("#map_category-selector").on('rendered.bs.select',function(){

    	var category= jq(this).val();

    	// if(jq(".category-button-activated").length !== 0){
     //        jq(".category-button-activated").removeClass('category-button-activated');
     //    }
     //    jq("#category-button-"+category).addClass('category-button-activated');

    	jq(".loading-map").show();

    	// remove marker on the map
		for (var id2 in arrMarkers) {
		    map.removeLayer(arrMarkers[id2])
		}
		arrMarkers = [];

	    jq.ajax({
	        type: "POST",
	        url: loadMarkerUrl,
	        data: { category: category, action: loadMarkerUrl },
	        success: function(data){
	        	jq(".loading-map").hide();
	        	print_results(data);
	        },
	        failure: function(errMsg) {
	            console.log(errMsg);
	        	jq(".loading-map").hide();
        	}
   		});
    });


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
			var title = listAubaines[i].title;
			var message = listAubaines[i].message;
			var authorName = listAubaines[i].author.username;
			var authorImageName = listAubaines[i].author.image_name;
			var authorDescription = listAubaines[i].author.description;
			var authorLati = listAubaines[i].author.lati;
			var authorLongi = listAubaines[i].author.longi;
			var authorAddressDisplayed = listAubaines[i].author.address_displayed;

			infos_deal = {
				"author": authorName,
				"address": authorAddressDisplayed.split(",")[0],
				"img": "<img src='"+avatarDir+authorImageName+"'>",
				"title_display": title,
				"subtitle": message,
				"teaser": authorDescription,
				"hours_open": "",
			};

			addMarkerByAdress(i, infos_deal, authorLati, authorLongi);
		}	    
	}

    function addMarkerByAdress(id, infos_deal,latti,longi){

			var infobox_content;
			var marker_icon;
			var deal_type=1;
			if(deal_type==1){
			infobox_content =   '<div class="dealMap" >' +
		                                '<div class="dealMap-header">'+
	                                        '<div class="dealMap-title">'+infos_deal.title_display+'</div>' +
	                                        '<div class="dealMap-subtitle">'+infos_deal.subtitle+'</div>' +
	                                        // '<div class="dealMap-hours">'+infos_deal.start_hour+' &rarr; '+infos_deal.end_hour+'</div>' +
		                                '</div>' +
		                                '<div class="dealMap-content">' +
		                                    '<div class="dealMap-author">'+ 
		                                        '<div class="dealMap-author-details">'+
		                                    		infos_deal.img +
		                                            '<div class="dealMap-name">'+infos_deal.author+'</div>' +
		                                            '<div class="dealMap-address">'+infos_deal.address+'</div>' +
		                                            '<div class="dealMap-hours_open">'+infos_deal.hours_open+'</div>' +
		                                            // '<div class="dealMap-contact">'+infos_deal.phone+'</div>' +
		                                        '</div>'+
		                                        '<div class="dealMap-description">'+infos_deal.teaser+'</div>'+
		                                    '</div>'+
		                                '</div>' +
		                            '</div>';		   				
			}
			else{
				infobox_content =   '<div class="dealMap" >' +
		                                '<div class="dealMap-content">' +
		                                    '<div class="dealMap-author">'+
		                                        '<div class="dealMap-author-details">'+
		                                        	infos_deal.img +
		                                            '<div class="dealMap-name">'+infos_deal.author+'</div>' +
		                                            '<div class="dealMap-address">'+infos_deal.address+'</div>' +
		                                            '<div class="dealMap-hours_open">'+infos_deal.hours_open+'</div>' +
		                                        '</div>'+
		                                        '<div class="dealMap-description">'+infos_deal.teaser+'</div>'+
		                                    '</div>'+
		                                '</div>' +
		                            '</div>';
			}
	    icon='https://aubaineapp.fr/wp-content/uploads/2017/04/map_marker.gif'; 
		marker_icon = L.divIcon({
		  className: 'css-icon',
		  html: '<span class="marker-map marker-map-'+deal_type+'"></span>',
		  iconSize: [18, 18],
		  popupAnchor: [0,-4]
		});
		var ll = L.latLng(latti, longi)
		var marker = L.marker(ll,{icon:marker_icon});
		marker.addTo(map);

		infobox_option={
			'className' : 'custom_infobox',
			maxWidth : 560
		}
		marker.bindPopup(infobox_content,infobox_option);
		arrMarkers[id] = marker;
    }

}); //document ready