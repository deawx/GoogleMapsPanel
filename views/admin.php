<?php

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Recep Realm"');
    header('HTTP/1.0 401 Unauthorized');
    die('Yetkisiz olan giremez.');
}else{
	if(($_SERVER['PHP_AUTH_USER'] != "admin") 
		|| ($_SERVER['PHP_AUTH_PW'] != "123654")){
			die('Yetkisiz olan giremez.');
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="https://googlemapspanel.herokuapp.com/">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#e31d1a">
<title>Yönetim - Size En Yakın MİM</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="http://bootswatch.com/simplex/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css">
<link rel="stylesheet" type="text/css" href="views/admin-map.css"/>
</head>

<body>
<p>&nbsp;</p>
<div class="container">
  <div class="row">
    <div class="col-xs-12 col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">1) Haritadan bir yer seçin</div>
        <div class="panel-body">
          <input id="pac-input" class="controls" type="text" placeholder="Google Haritalar'da ara">
          <div id="map"></div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-md-7">
      <div class="panel panel-default">
        <div class="panel-heading">2) Detayları Oluşturun</div>
        <div class="panel-body">
          <form action="admin/lokasyon/kaydet" method="post" enctype="application/x-www-form-urlencoded" class="form-horizontal" id="frmLokasyonKaydet">
            
            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="baslik">Başlık</label>
              <div class="col-md-6">
                <input name="baslik" type="text" required="" class="form-control input-md" id="baslik" placeholder="Başlığı buraya girin." />
              </div>
            </div>
            
            <!-- Textarea -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="adres">Adres</label>
              <div class="col-md-6">
                <textarea class="form-control" id="adres" name="adres" required="required"></textarea>
              </div>
            </div>
            
            <!-- Select Basic -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="sehir">Şehir</label>
              <div class="col-md-6">
                <select id="sehir" name="sehir" class="form-control">
                  <option value="-1">Seçiniz</option>
                  <option value="35">İZMİR</option>
                  <option value="45">MANİSA</option>
                  <option value="9">AYDIN</option>
                  <option value="20">DENİZLİ</option>
                  <option value="48">MUĞLA</option>
                </select>
              </div>
            </div>
            
            <!-- Select Basic -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="ilce">İlçe</label>
              <div class="col-md-6">
                <select id="ilce" name="ilce" class="form-control" disabled="disabled" >
                  <option value="-1">Şehir Seçiniz</option>
                </select>
              </div>
            </div>
            
            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="telefon">Telefon</label>
              <div class="col-md-6">
                <input id="telefon" name="telefon" type="text" placeholder="" class="form-control input-md" required="required" />
              </div>
            </div>
            
            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="web">Web Sitesi</label>
              <div class="col-md-6">
                <input id="web" name="web" type="text" placeholder="" class="form-control input-md" required="required" />
              </div>
            </div>
            
            <!-- Multiple Radios -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="icons">Simge Seçin</label>
              <div class="col-md-4">
                <div class="radio">
                  <label for="icons-0">
                    <input type="radio" name="ikon" id="ikon-0" value="https://s14.postimg.org/x9wk5v3i9/1473274794_map_marker.png" checked="checked">
                    <img src="https://s14.postimg.org/x9wk5v3i9/1473274794_map_marker.png" /> </label>
                </div>
                <div class="radio">
                  <label for="icons-1">
                    <input type="radio" name="ikon" id="ikon-1" value="https://s21.postimg.org/pkfbjd7h3/1473274798_Location.png">
                    <img src="https://s21.postimg.org/pkfbjd7h3/1473274798_Location.png" /> </label>
                </div>
              </div>
            </div>
            
            <!-- Button (Double) -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="btnKaydet"></label>
              <div class="col-md-8">
                <button id="btnKaydet" name="btnKaydet" type="submit" class="btn btn-success">Kaydet</button>
                <button id="btnYeni" name="btnYeni" type="reset" class="btn btn-default">Yeni</button>
                <button id="btnSil" name="btnSil" type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;Sil</button>
              </div>
            </div>
            <input id="lat" name="lat" type="hidden" value="" required="required" />
            <input id="lng" name="lng" type="hidden" value="" required="required" />
            <input id="id" name="id" type="hidden" value="" />
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <div class="table-responsive">
        <table id="table">
        </table>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> 
<script type="text/javascript" src="views/il_ilce.js"></script> 
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.js"></script> 
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/locale/bootstrap-table-tr-TR.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script> 
<script src="https://github.com/makeusabrew/bootbox/releases/download/v4.4.0/bootbox.min.js"></script> 
<script>
	//sabitler.
	var map, $marker;
	var $default_location = {
	    lat: 38.43019407828687,
	    lng: 27.143611907958984
	};

	//harita yükle.
	function initMap() {
	    $(function() {
	        map = new google.maps.Map($('#map')[0], {
	            center: $default_location,
	            zoom: 12
	        });
	        google.maps.event.addListener(map, 'click', function(e) {
	            placeMarker(e.latLng);
	        });

	        var input = $('#pac-input')[0];
	        var searchBox = new google.maps.places.SearchBox(input);
	        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

	        map.addListener('bounds_changed', function() {
	            searchBox.setBounds(map.getBounds());
	        });

	        searchBox.addListener('places_changed', function() {
	            var places = searchBox.getPlaces();

	            if (places.length == 0) {
	                return;
	            }

	            var bounds = new google.maps.LatLngBounds();
	            places.forEach(function(place) {
	                if (!place.geometry) {
	                    console.log("Returned place contains no geometry");
	                    return;
	                }
	                if (place.geometry.viewport) {
	                    bounds.union(place.geometry.viewport);
	                } else {
	                    bounds.extend(place.geometry.location);
	                }
	            });
	            map.fitBounds(bounds);

	        });

	    });
	}

	function setLatLng(lat, lng) {
	    $(function() {
	        $('#lat').val(lat);
	        $('#lng').val(lng);
	    });
	}

	//marker yerleştir.
	function placeMarker(location) {
	    if (!$marker) {
	        $marker = new google.maps.Marker({
	            position: location,
	            map: map,
	            draggable: true,
	            title: 'Yeni Lokasyon'
	        });
	    } else {
	        $marker.setPosition(location);
	    }
	    setLatLng(location.lat, location.lng);
	    google.maps.event.addListener($marker, 'dragend', function(e) {
	        setLatLng(e.latLng.lat(), e.latLng.lng());
	    });
	}

	var $table = $('#table');
	$(document).ready(function(e) {
	    $table.bootstrapTable({
	        url: 'admin/lokasyon/liste',
	        pagination: true,
	        search: true,
	        clickToSelect: true,
	        singleSelect: true,
	        maintainSelected: true,
	        idField: "id",
	        uniqueId: "id",
	        columns: [{
	            visible: false,
	            field: 'id',
	            title: 'ID'
	        }, {
	            visible: true,
	            field: 'baslik',
	            title: 'Başlık'
	        }, {
	            visible: true,
	            field: 'adres',
	            title: 'Adres'
	        }, {
	            visible: true,
	            field: 'il_adi',
	            title: 'Şehir'
	        }, {
	            visible: true,
	            field: 'ilce_adi',
	            title: 'İlçe'
	        }, {
	            visible: false,
	            field: 'ilce',
	            title: 'İlçe'
	        }, {
	            visible: false,
	            field: 'sehir',
	            title: 'Şehir'
	        }, {
	            visible: true,
	            field: 'telefon',
	            title: 'Telefon'
	        }, {
	            visible: true,
	            field: 'web',
	            title: 'Web'
	        }, {
	            visible: false,
	            field: 'lat',
	            title: 'Lat'
	        }, {
	            visible: false,
	            field: 'lng',
	            title: 'Lng'
	        }, {
	            visible: true,
	            field: 'ikon',
	            title: 'İkon',
	            formatter: function(value, row) {
	                return '<img height="16" src="' + value + '" />';
	            }
	        }, ],
	        onClickRow: function(row, $element, field) {
	            $('tr', '#table').removeClass('info');
	            $($element).addClass('info');

	            $('#id').val(row.id);

	            $('#baslik').val(row.baslik);
	            $('#adres').val(row.adres);

	            $('#sehir').val(row.sehir);
	            $('#sehir').trigger("change");
	            $('#ilce').val(row.ilce);

	            $('#telefon').val(row.telefon);
	            $('#web').val(row.web);
	            $('#lat').val(row.lat);
	            $('#lng').val(row.lng);

	            $('input:radio[name=ikon]').filter('[value="' + row.ikon + '"]').prop('checked', true);

	            placeMarker({
	                lat: parseFloat(row.lat),
	                lng: parseFloat(row.lng)
	            });
	            map.setCenter({
	                lat: parseFloat(row.lat),
	                lng: parseFloat(row.lng)
	            });
	        }
	    });
	    var opts = {
	        success: function(responseText, statusText, xhr, $form) {
	            bootbox.alert("Lokasyon kayıt edildi.");
	            $table.bootstrapTable('refresh');
	        }
	    };
	    $('#frmLokasyonKaydet').ajaxForm(opts);

	    $(document).on('click', '#btnYeni', function() {
	        $('#id').val('');
	    });

	    $(document).on('click', '#btnSil', function() {
	        if ($('#id').val() != '') {
	            bootbox.prompt({
	                title: "Lokasyon silinecek. Onaylamak için 'evet' yazın.",
	                value: "hayır",
	                callback: function(result) {
	                    if (result !== null) {
	                        if (result == 'evet') {
	                            $.post('admin/lokasyon/sil/' + $('#id').val(), {}, function(response) {
	                                $table.bootstrapTable('refresh');
	                                bootbox.alert("Lokasyon silindi.");
									$('#frmLokasyonKaydet')[0].reset();
									$('#id').val('');
	                            });
	                        }
	                    }
	                }
	            });
	        }
	    });
	});
</script> 
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGOquRFvlef5QX6pTgBtQXpMitNcJQwp8&libraries=places&callback=initMap" async defer></script>
</body>
</html>