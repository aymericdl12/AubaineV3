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
	print_results(listAubaines,listPartners );

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

	function print_results(listAubaines,listPartners){

		for (var i = 0; i <=listPartners.length-1; i++) {
			var id = listPartners[i].id;
			var authorName = listPartners[i].username;
			var authorImageName = listPartners[i].image_name;
			var authorCategory = listPartners[i].category;
			var authorDescription = listPartners[i].description;
			var authorLati = listPartners[i].lati;
			var authorLongi = listPartners[i].longi;
			var authorAddressDisplayed = listPartners[i].address_displayed;

			infos_deal = {
				"author": authorName,
				"address": authorAddressDisplayed.split(",")[0],
				"img": "<img src='"+avatarDir+authorImageName+"'>",
				"teaser": authorDescription,
				"hours_open": "",
				"category": authorCategory,
			};

			addMarkerByAdress(id, 3, infos_deal, authorLati, authorLongi);
		}

		for (var i = 0; i <=listAubaines.length-1; i++) {
			var id = listAubaines[i].id;
			var title = listAubaines[i].title;
			var message = listAubaines[i].message;
			var category = listAubaines[i].category;
			var authorName = listAubaines[i].author.username;
			var authorImageName = listAubaines[i].author.image_name;
			var authorDescription = listAubaines[i].author.description;
			var authorLati = listAubaines[i].author.lati;
			var authorLongi = listAubaines[i].author.longi;
			var authorAddressDisplayed = listAubaines[i].author.address_displayed;

			infos_deal = {
				"author": authorName,
				"address": authorAddressDisplayed.split(",")[0],
				"category": category,
				"img": "<img src='"+avatarDir+authorImageName+"'>",
				"title_display": title,
				"subtitle": message,
				"teaser": authorDescription,
				"hours_open": "",
			};

			addMarkerByAdress(id, 1, infos_deal, authorLati, authorLongi);
		}	 	    
	}

    function addMarkerByAdress(id,deal_type, infos_deal,latti,longi){

		var infobox_content;
		var marker_icon;
		if(deal_type==1){
			infobox_content =   '<div class="dealMap" >' +
	                                '<div class="dealMap-header">'+
                                        '<div class="dealMap-title">'+infos_deal.title_display+'</div>' +
                                        '<div class="dealMap-subtitle">'+infos_deal.subtitle+'</div>' +
	                                '</div>' +
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
			var marker_icon = L.icon({
			    iconUrl: imageDir+infos_deal.category+".png",
			    className: 'marker-map marker-map-'+deal_type+' marker-map-'+infos_deal.category,
			    iconSize:     [35, 35], // size of the icon
			    iconAnchor:   [17.5, 17.5], // point of the icon which will correspond to marker's location
			    popupAnchor:  [0, -10] // point from which the popup should open relative to the iconAnchor
			});	   				
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
		jq('#deal-'+id+' .message').hover(function(){
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

   

}); //document ready