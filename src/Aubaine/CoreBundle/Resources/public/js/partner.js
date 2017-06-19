var jq = jQuery;
jq(document).ready( function() {


    // var map;
    // var arrMarkers = [];
    // var map = L.map('partner-map',{zoomControl:false}).setView([43.6044292, 1.4438121000000592], 16);
    // L.tileLayer('https://api.mapbox.com/styles/v1/aymericdl2/cj1ypxtb0000g2sqry16pzm0o/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYXltZXJpY2RsMiIsImEiOiJjajF5b2dhMzIwMDBmMzNuenBsMXRpeWVoIn0.EGdbIzhLpXPATY5FzxdsHg', 
    // {}).addTo(map);
    // map.scrollWheelZoom.disable();  

    var id = -1;
    var authorName = "Votre boutique";
    var authorImageName = "";
    var authorDescription = "Un café très sympa ou il fait bon vivre. La terrasse est très agréable et les serveurs sont aimable, on recommande plsu plus";
    var authorLati = 43.6044292;
    var authorLongi = 1.4438121000000592;
    var authorAddressDisplayed = "2 place Georges Sand";
    var aubaineTitle = "Happy hour";
    var aubaineMessage = "De 17h à 20h les pints au prix des demis !";

    var author = {
        "id": id,
        "name": authorName,
        "address": authorAddressDisplayed.split(",")[0],
        "img": "<img src='https://aubaineapp.fr/wp-content/uploads/2017/05/le-ministère.png'>",
        "description": authorDescription,
        "hours_open": "",
        "lati": authorLati,
        "longi": authorLongi,
    };
    var aubaine = {
        "title": aubaineTitle,
        "message": aubaineMessage
    };
    
    // addMarkerByAdress(1, author, aubaine);

    

    function addMarkerByAdress(deal_type, author, aubaine){

            var infobox_content;
            var marker_icon;
            if(deal_type==1){
            infobox_content =   '<div class="dealMap" >' +
                                        '<div class="dealMap-header">'+
                                            '<div class="dealMap-subtitle">'+aubaine.message+'</div>' +
                                        '</div>' +
                                        '<div class="dealMap-content">' +
                                            '<div class="dealMap-author">'+ 
                                                '<div class="dealMap-author-details">'+
                                                    author.img +
                                                    '<div class="dealMap-name">'+author.name+'</div>' +
                                                    '<div class="dealMap-address">'+author.address+'</div>' +
                                                    '<div class="dealMap-hours_open">'+author.hours_open+'</div>' +
                                                '</div>'+
                                                '<div class="dealMap-description">'+author.description+'</div>'+
                                            '</div>'+
                                        '</div>' +
                                    '</div>';                       
            }
            else{
                infobox_content =   '<div class="dealMap" >' +
                                        '<div class="dealMap-content">' +
                                            '<div class="dealMap-author">'+
                                                '<div class="dealMap-author-details">'+
                                                    author.img +
                                                    '<div class="dealMap-name">'+author.name+'</div>' +
                                                    '<div class="dealMap-address">'+author.address+'</div>' +
                                                    '<div class="dealMap-hours_open">'+author.hours_open+'</div>' +
                                                '</div>'+
                                                '<div class="dealMap-description">'+author.description+'</div>'+
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
        var ll = L.latLng(author.lati, author.longi)
        var marker = L.marker(ll,{icon:marker_icon});
        marker.addTo(map);

        infobox_option={
            'className' : 'custom_infobox',
            maxWidth : 560
        }
        marker.bindPopup(infobox_content,infobox_option);
        arrMarkers[author.id] = marker;
        marker.fireEvent('click');
    }
    

});