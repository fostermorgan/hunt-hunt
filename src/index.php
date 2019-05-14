<?php
  $page = 'home';

?>

<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="hunt.css">


    <title>Hunt Hunt</title>
  </head>
  <body>
<!--color scheme hex codes:brown #663300	darkyellow #CC9900	dark green #333300	orange #CC6600 -->
<!-- p5.js libraries? -->
    <?php include 'nav.php' ?>
    <div class="container-fluid">
      <div class="card jumbotron col-12">
        <h5>This is Hunt-Hunt...</h5>
      </div>

      <!-- bottom google map and search tool -->
      <div class="row footer mx-auto">
        <div class="card col-4">
          <div class="card-header row">
            <input id="address" class="form-control col-8" type="search" placeholder="Search" aria-label="Search">
            <button id="searchMap" class="white font-weight-bold btn dark col-3 mx-auto" type="submit">Search</button>
          </div>
          <div class="card-body row">
            <ul class="nav navbar-nav">
              <li><button class="btn btn-primary">Eau Claire</button></li>
              <li><button class="btn btn-basic">Chippewa</button></li>
              <li><button class="btn btn-basic">Menominee</button></li>
              <li><button class="btn btn-basic">La Crosse</button></li>
            </ul>
          </div>
        </div>

        <div class="col-8">
          <div class="card footer" id="map" style="width:100%; height:400px;"></div>
        </div>
      </div>
      <!-- <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="white font-weight-bold btn my-2 my-sm-0 dark" type="submit">Search</button>
      </form>
      <div class="white rounded-more">

      </div> -->
       <!-- <div class="row">
         <div class="card col-4">

         </div>
       </div> -->

      </div>
    <script>
      function myMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: {lat: 44.8113, lng: -91.4985}
        });
        var geocoder = new google.maps.Geocoder();

        document.getElementById('searchMap').addEventListener('click', function() {
          geocodeAddress(geocoder, map);
        });
      }

      function geocodeAddress(geocoder, resultsMap) {
        var address = document.getElementById('address').value;
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            resultsMap.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
              map: resultsMap,
              position: results[0].geometry.location
            });
          } else {
            alert('Search was not successful for the following reason: ' + status);
          }
        });
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAL2wti_wS8G_3VMWmLuV7Ih2MZZu7ZErs&callback=myMap"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
