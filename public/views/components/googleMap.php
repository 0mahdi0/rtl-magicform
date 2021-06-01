<?php 
$payload = $item->payload;
$id = $item->id;
$googleSettings = json_decode(get_option("magicform_google_settings"));
?>
<div class='mf-form-group mf-form-group-<?php echo esc_attr($id); ?>'>
<style>
      <?="#".$id?> {
        height: <?=$payload->heightValue?>px;
        width: 100%;
       }
    </style>
<?php if(isset($googleSettings->googleApiKey) && $googleSettings->googleApiKey){ ?>
  <?php magicform_getInputDescription($payload, "top") ?>
  <input type="hidden" id="<?php echo esc_attr($id) ?>_map" name="<?php echo esc_attr($id) ?>_map"/>
  <input type="hidden" id="<?php echo esc_attr($id) ?>_map_link" name="<?php echo esc_attr($id) ?>_map_link">
<div id="<?php echo esc_attr($id) ?>"></div>
<script>
    function initMap() {
        var pos = {lat: <?=$payload->latitude?>, lng: <?=$payload->longitude?>};
        document.getElementById('<?php echo esc_attr($id) ?>_map_link').value = "http://maps.google.com/maps?&z="+<?php echo intval($payload->zoom)?>+"&mrt=yp&t=r&q="+pos.lat+","+pos.lng
        showInfo(pos);
        var map = new google.maps.Map(
            document.getElementById("<?php echo esc_attr($id) ?>"), {zoom: <?=$payload->zoom?>, center: pos}
        );
        
        var infowindow = new google.maps.InfoWindow({
          content: "<?=$payload->markText?>"
        });

        var marker = new google.maps.Marker({
          position: pos,
          map: map,
          draggable: true
        });
        marker.addListener('click', function() {
          infowindow.open(map, marker);
        });

        map.addListener('click', function(e) {
            marker.setPosition(e.latLng) 
            var latLng = e.latLng;
            document.getElementById('<?php echo esc_attr($id) ?>_map_link').value = "http://maps.google.com/maps?&z="+this.getZoom()+"&mrt=yp&t=r&q="+e.latLng.lat()+","+e.latLng.lng()
            map.panTo(e.latLng);
            showInfo(e.latLng);
        },marker);

        marker.addListener("dragend",function(e){
          marker.setPosition(e.latLng) 
            var latLng = e.latLng;
            document.getElementById('<?php echo esc_attr($id) ?>_map_link').value = "http://maps.google.com/maps?&z="+map.getZoom()+"&mrt=yp&t=r&q="+e.latLng.lat()+","+e.latLng.lng()
            map.panTo(e.latLng);
            showInfo(e.latLng);
        },map);
    }
    
    function showInfo(latlng) {
      var geocoder = new google.maps.Geocoder();
      geocoder.geocode({
        'latLng': latlng
      }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[1]) {
            // here assign the data to asp lables
            document.getElementById('<?php echo esc_attr($id) ?>_map').value = results[1].formatted_address;
          }else
            document.getElementById('<?php echo esc_attr($id) ?>_map').value = ""
        }else
        document.getElementById('<?php echo esc_attr($id) ?>_map').value = ""
      });
    }
</script>
<script async defer
    src="<?php printf(esc_url("https://maps.googleapis.com/maps/api/js?key=%s&callback=initMap&language=%s"), esc_html($googleSettings->googleApiKey), esc_html(strtolower($formSettings->selectLanguage)) )?>">
</script>

<?php } else {?>
    <div className="alert alert-warning">
        <strong>You have to set the api key </strong> <a target="_blank" href="<?=get_admin_url()?>admin.php?page=magicform_settings&sub_page=google">Settings Page</a>
    </div>
<?php } ?>
<?php magicform_getInputDescription($payload, "bottom") ?>
</div>