var jq = jQuery;
jq(document).ready( function() {

    jq('.input-daterange').datepicker({
        autoclose: true,
        language: "fr",
        todayHighlight: true,
        startDate: "now"
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
                if(form.parent().parent("tr").attr("id") == "current_aubaine"){
                    aubaine = {
                        "message": data.message
                    };
                    addMarkerByAdress(1, author, aubaine)
                }
                form.parent().parent("tr").addClass("success").removeClass("danger");
                form.replaceWith( '<div  class="aubaine-message">'+
                                        data.message+
                                        '<form method="post" class="myAubaines-form-delete aubaine-action" action ="'+ data.delete_link+'") }}">'+
                                            '<input class="form-control" type="hidden"  value="'+data.id_aubaine+'" name="id_aubaine" required="required"  autocomplete="off" />'+
                                            '<button class="btn btn-xs btn-danger my-aubaine-form-delete-submit" type="submit" data-loading-text="...">'+
                                                '<span class="glyphicon glyphicon-trash"></span>'+
                                            '</button>'+
                                        '</form>'+
                                '</div>' );
            }       
        });
    });

    jq(".myAubaines-form-multiple").submit(function(event) {
        event.preventDefault();
        var form = jq(this);
        jq.ajax({
            beforeSend:  function() { 
                form.children(".my-aubaine-form-submit").button('loading');
            },
            url: jq(this).attr('action'), // le nom du fichier indiqué dans le formulaire
            type: "POST", // la méthode indiquée dans le formulaire (get ou post)
            cache: false,
            data: form.serialize(),
            success: function(data) { 
                window.location.reload();
            }       
            // return false; 
        });
    });

    jq(".myAubaines-list").on('submit', '.myAubaines-form-delete', function(event) {
        event.preventDefault();
        var form = jq(this);
        jq.ajax({
            beforeSend:  function() { 
                form.children(".my-aubaine-form-delete-submit").button('loading');
            },
            url: jq(this).attr('action'), // le nom du fichier indiqué dans le formulaire
            type: "POST", // la méthode indiquée dans le formulaire (get ou post)
            cache: false,
            data: form.serialize(),
            success: function(data) { // je récupère la réponse du fichier PHP
                window.location.reload();
                window.scrollTo(0,0);
            }       
            // return false; 
        });
    });

    var map;
    var arrMarkers = [];
    var map = L.map('myAubaines-map',{zoomControl:false}).setView([user.lati, user.longi], 16);
    // map.zoomControl.setPosition('bottomright');
    // load a tile layer
    L.tileLayer('https://api.mapbox.com/styles/v1/aymericdl2/cj1ypxtb0000g2sqry16pzm0o/tiles/256/{z}/{x}/{y}?access_token=pk.eyJ1IjoiYXltZXJpY2RsMiIsImEiOiJjajF5b2dhMzIwMDBmMzNuenBsMXRpeWVoIn0.EGdbIzhLpXPATY5FzxdsHg', 
    {}).addTo(map);
    // map.zoomControl.setPosition('bottomright');
    map.scrollWheelZoom.disable();
    // map.dragging.disable();
    // map.touchZoom.disable();
    // map.doubleClickZoom.disable();
    // map.boxZoom.disable();
    // map.keyboard.disable();
        



    var id = user.id;
    var authorName = user.username;
    var authorImageName = user.image_name;
    var authorDescription = user.description;
    var authorLati = user.lati;
    var authorLongi = user.longi;
    var authorAddressDisplayed = user.address_displayed;
    var aubaineMessage = currentAubaine.message;

    var author = {
        "id": user.id,
        "name": authorName,
        "address": authorAddressDisplayed.split(",")[0],
        "img": "<img src='"+avatarDir+authorImageName+"'>",
        "description": authorDescription,
        "hours_open": "",
        "lati": authorLati,
        "longi": authorLongi,
    };
    var aubaine = {
        "message": aubaineMessage
    };

    if(!aubaineMessage){
        addMarkerByAdress(3, author, aubaine);
    }
    else{
        addMarkerByAdress(1, author, aubaine);
    }

    

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