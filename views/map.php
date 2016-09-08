<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo $GLOBALS['base_href']; ?>">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#e31d1a">
<title>Size En Yakın MİM</title>
<link href="views/boilerplate.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="views/site.css?v=8b5d6e7aca86c9e1f9617946e7478a90"/>
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="views/respond.min.js"></script>
</head>

<body>

<div id="en-yakin">&nbsp;</div>


<div id="sizin-icin">&nbsp;</div>


<div id="il-ilce-container">
  <div id="il-ilce-border">
    <table width="95%" border="0" cellspacing="0" cellpadding="3" class="m-10">
      <tr>
        <td><span class="lbl-il-ilce">İl</span></td>
        <td width="50%"><select id="il" name="il" class="select max-width-135">
            <option value="-1">Seçiniz</option>
            <option value="35">İZMİR</option>
            <option value="45">MANİSA</option>
            <option value="9">AYDIN</option>
            <option value="20">DENİZLİ</option>
            <option value="48">MUĞLA</option>
          </select></td>
        <td><span class="lbl-il-ilce">İlçe</span></td>
        <td width="50%"><select id="ilce" name="ilce" class="select max-width-135" disabled>
            <option value="-1">İl Seçiniz</option>
          </select></td>
      </tr>
    </table>
  </div>
</div>


<div id="bulunan-adresler">
  <ul>
    <li><span>Doğanlar Mah.</span></li>
    <li><span>Mevlana Mah.</span></li>
  </ul>
</div>

<div id="detay-cerceve">
    <h2 id="baslik">&nbsp;</h2>
    <p id="adres">&nbsp;</p>
    <table width="100%">
        <tr>
            <td width="50%"><span id="telefon">&nbsp;</span></td>
            <td width="50%" align="right"><span id="web">&nbsp;</span></td>
        </tr>
    </table>
</div>


<div id="harita-cerceve">
  <div id="harita-tasiyici">
    <div id="map"><div id="aGFyaXRh"></div></div>
  </div>
</div>

<div id="aydem">&nbsp;</div>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1652715035045481',
      xfbml      : true,
      version    : 'v2.7'
    });

    // ADD ADDITIONAL FACEBOOK CODE HERE
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script> 
<script src="views/map_il_ilce.js"></script> 
<script>
var map;

var $default_location = {
	lat: 38.43019407828687,
	lng: 27.143611907958984
};

var locations = [
    ['Bondi Beach', -33.890542, 151.274856, 4],
    ['Coogee Beach', -33.923036, 151.259052, 5],
    ['Cronulla Beach', -34.028249, 151.157507, 3],
    ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
    ['Maroubra Beach', -33.950198, 151.259302, 1]
];

function initMap() {
    map = new google.maps.Map($('#map')[0], {        
        center: $default_location,
		zoom: 12
    });    
	setMarkers(map);
}

function initApp() {
    $(function() {
        $.post('admin/lokasyon/liste', {}, function(response) {
            locations = response;
            initMap();
        });		
    });
}

function setMarkers(map) {
    var marker, i;
    for (i = 0; i < locations.length; i++) {
        var location = locations[i];
        /*if (i == 0) {
            map.setCenter({
                lat: parseFloat(location.lat),
                lng: parseFloat(location.lng)
            });
        }*/		
        marker = new google.maps.Marker({
            position: {
                lat: parseFloat(location.lat),
                lng: parseFloat(location.lng)
            },
            map: map,
            icon: location.ikon,
            title: location.baslik,
            zIndex: i
        });
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
				showCustomInfo(locations[i].baslik, locations[i].adres, locations[i].telefon, locations[i].web);
            }
        })(marker, i));
    }
	if(navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {				
			$default_location = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
			map.setCenter($default_location);				
		}, function(){ },
		{
			enableHighAccuracy: false,
			timeout: 30000,
			maximumAge: 0
		});
	}
}

function showCustomInfo(baslik, adres, telefon, web){
	$('#baslik', '#detay-cerceve').html(baslik);
	$('#adres', '#detay-cerceve').html(adres);
	$('#telefon', '#detay-cerceve').html(telefon);	
	$('#web', '#detay-cerceve').html(web);
	$('#detay-cerceve').show();
}

function placesServiceCallback(results) {
	$('ul', '#bulunan-adresler').empty();
	for (var i = 0; i < results.length; i++) {
		if(i==0){
			map.setCenter({
				lat: parseFloat(results[i].lat),
				lng: parseFloat(results[i].lng)
			});		
		}
		//if(i < 3)
		$('ul','#bulunan-adresler').append('<li data-id="' + results[i].id + '"><span>' + results[i].adres + '</span></li>');			
	}
}
$(function(){
	$('#ilce').on('change', function() {
		if(this.value > 0){
			$.post('admin/lokasyon/liste/'+this.value, {}, function(response) {
				placesServiceCallback(response);
			});				
			setTimeout(function(){
				$('#bulunan-adresler').slideDown();
			}, 1000);
		}else{
			$('#bulunan-adresler').hide();
		}
	});
	$(document).on('click', '#bulunan-adresler ul li', function(){
		if($(this).data('id') != ''){
			var id = $(this).data('id');
			var location = $.map(locations, function(e, i){
				if(e.id == id) return e;
			});
			if(location.length > 0){
				showCustomInfo(location[0].baslik, location[0].adres, location[0].telefon, location[0].web);
				map.setCenter({
					lat: parseFloat(location[0].lat),
					lng: parseFloat(location[0].lng)
				});					
			}
		}			
	});	
});
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGOquRFvlef5QX6pTgBtQXpMitNcJQwp8&callback=initApp" async defer></script>
</body>
</html>