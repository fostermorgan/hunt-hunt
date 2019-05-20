<?php
  include_once "db_connection.php";
  session_start();
  $page = 'hunts';

  $database = new Connection();
  $conn = $database->openConnection();

  $message = "";
  $message_type = "";

  # grab all the users so we can populate the drop-downs on the page.
  $statement = $conn->prepare("SELECT * FROM hunts
                                INNER JOIN animals ON hunts.animal_id = animals.animal_id
                                  INNER JOIN users ON hunts.user_id = users.user_id
                                    INNER JOIN locations ON hunts.location_id = locations.location_id;");
  $statement->execute();
  $hunt_tuples = $statement->fetchAll();

  $statement = $conn->prepare("SELECT * FROM animals");
  $statement->execute();
  $animal_tuples = $statement->fetchAll();

  $statement = $conn->prepare("SELECT * FROM locations");
  $statement->execute();
  $location_tuples = $statement->fetchAll();

  if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $statement = $conn->prepare("SELECT *
                                 FROM hunts
                                  INNER JOIN animals ON hunts.animal_id = animals.animal_id
                                    INNER JOIN users ON hunts.user_id = users.user_id
                                      INNER JOIN locations ON hunts.location_id = locations.location_id
                                 WHERE hunts.user_id = '$user_id';");
    $statement->execute();
    $user_hunts = $statement->fetchAll();
  }

  $statement = $conn->prepare("SELECT *
                               FROM hunts
                                INNER JOIN animals ON hunts.animal_id = animals.animal_id
                                  INNER JOIN users ON hunts.user_id = users.user_id
                                    INNER JOIN locations ON hunts.location_id = locations.location_id");
  $statement->execute();
  $animalFilteredHunts = $statement->fetchAll();




  $type = "";
	if(isset($_GET["type"])){
		$type = $_GET["type"];
	}
  $view = "huntView";
	if(isset($_GET["view"])){
		$view = $_GET["view"];
	}
  $huntSelector = "allHunts";
  if(isset($_GET["huntSelector"])){
		$huntSelector = $_GET["huntSelector"];
	}
  $animalSelector = "allAnimals";
  if(isset($_GET["animalSelector"])){
		$animalSelector = $_GET["animalSelector"];
	}


?>
<!-- TODO: be able to click on each hunt to expand a description a user inputs on report -->
<!-- TODO: option to pick either user's hunts or all hunts -->

<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="hunt.css">
    <title>Hunt Hunt | Hunts</title>
  </head>
  <body>
<!--color scheme hex codes:brown #663300	darkyellow #CC9900	dark green #333300	orange #CC6600 -->
<!-- p5.js libraries? -->
    <?php include 'nav.php' ?>
    <div class="container">
      <?php if($message !== ""){ ?>
        <div id="jsErrorMessage" class="alert alert-<?php echo $message_type; ?>" role="alert"><?php echo $message; ?></div>
      <?php } ?>
      <!-- DROP DOWN MENUS -->
      <div class="row pt-3">
        <!-- VIEW SELECTOR -->
        <div class="col-2">
          <form method="get" action="" >
						<!-- onchange="this.form.submit()" submits this mini-form every time something is selected from the dropdown. -->
						<select style="width: auto; margin: auto;" class="form-control" onchange="this.form.submit()" name="view">
							<option value="huntView" <?php if($view == 'huntView') { echo " selected"; } ?>>Default View (Hunts)</option>
              <option value="mapView" <?php if($view == 'mapView') { echo " selected"; } ?>>Map View (Interactive Map)</option>
						</select>
					</form>
        </div>
        <div class="col-2">
        </div>
        <!-- HUNT SELECTOR -->
        <?php if(isset($_SESSION['username'])){?>
          <div class="col-2">
            <form method="get" action="#">
  						<!-- onchange="this.form.submit()" submits this mini-form every time something is selected from the dropdown. -->
  						<select style="width: auto; margin: auto;" class="form-control" onchange="this.form.submit()" name="huntSelector">
  							<option value="allHunts" <?php if($huntSelector == 'allHunts') { echo " selected"; } ?>>All Hunts</option>
                <option value="userHunts" <?php if($huntSelector == 'userHunts') { echo " selected"; } ?>><?php echo $_SESSION['username']; ?>'s Hunts</option>
  						</select>
  					</form>
          </div>
        <?php } ?>
        <div class="col-2">
          <form method="get" action="#">
						<!-- onchange="this.form.submit()" submits this mini-form every time something is selected from the dropdown. -->
						<select style="width: auto; margin: auto;" class="form-control" onchange="this.form.submit()" name="animalSelector">
							<option value="allAnimals">All Animals</option>
							<?php
							# loop through each tuple in our "type" result set and produce one <option> for each.
							foreach($animal_tuples as $animal){ ?>
                <option value="animal<?php echo $animal["animal_id"]; ?>" <?php if($animalSelector == 'animal' . $animal['animal_id']) { echo " selected"; } ?>><?php echo $animal["animalName"]; ?></option>
							<?php } ?>
						</select>
					</form>
        </div>
        <!-- TODO if time add lcoation filter? -->
      </div>
      <?php if($view == 'huntView') { ?>
        <div class="row">
<!-- can have this as card-columns ,but awkward, sass can change stuff -->
<!-- <div class="card-columns">

<!-- Card1
<div class="card">
<div class="card-header">Card 1</div>
<div class="card-body">
<p class="card-text">Text for this card.</p>
</div>
</div> -->
<!--  -->
          <!-- display each successful hunt -->
          <?php
            if($animalSelector != 'allAnimals'){
              foreach ($hunt_tuples as $hunt) {

                    if($hunt['isSuccess'] == 'true' && $animalSelector == 'animal' . $hunt['animal_id']){
                      $dateToString = $hunt['huntDate'];
                      $dateSplit = explode("-", $dateToString);
                      $stringDate = $dateSplit[1] . '-' . $dateSplit[2] . '-' . $dateSplit[0];
          							# we'll create a quick temporary variable for each called $favorite - used shortly to add the "favorite" class name where appropriate.
          					    if(isset($_SESSION['username'])){$currentUserID = $_SESSION['user_id'];} ?>
          							<div class="col-12 col-sm-6 col-md-6 col-lg-5 col-xl-4 mt-3 pt-3 pb-3" id="hunt<?php echo $hunt['hunt_id']; ?>">
          								<div class="card mb-3 green <?php if($favorite) { echo 'favorite'; } ?>">
                            <!-- //TODO card-img-top distorts image but successfuly crops it, check out how -->
          									<img src="./images/hunt<?php echo $hunt['hunt_id']; ?>.jpg" class="d-block w-300 mx-auto m-3 rounded-more img-fluid pl-3 pr-3" alt="Picture of <?php echo $hunt['user_id']; ?>">
          									<div class="card-body pl-0 ml-0 pr-0 mr-0 text-light green ">
                              <!-- TODO: make a drop down to select user's hunts or all hunts, possibly by animal, location etc.  -->
                              <h6 class="card-subtitle float-left pl-3"><?php echo $stringDate; ?></h6>
                              <h6 class="card-subtitle float-right pr-3"><?php echo $hunt['locationName']; ?></h6>
                              </br>
                              <h3 class="card-title mx-auto text-center"><?php echo $hunt['username'] . ' got a ' . $hunt['animalName']; ?></h3>
          									</div>
          								</div>
          							</div>
        				<?php }
              }
            }
            else if($huntSelector == 'allHunts'){
              foreach ($hunt_tuples as $hunt) {

              if($hunt['isSuccess'] == 'true'){
                $dateToString = $hunt['huntDate'];
                $dateSplit = explode("-", $dateToString);
                $stringDate = $dateSplit[1] . '-' . $dateSplit[2] . '-' . $dateSplit[0];
    							# we'll create a quick temporary variable for each called $favorite - used shortly to add the "favorite" class name where appropriate.
    					    if(isset($_SESSION['username'])){$currentUserID = $_SESSION['user_id'];} ?>
    							<div class="col-12 col-sm-6 col-md-6 col-lg-5 col-xl-4 mt-3 pt-3 pb-3" id="hunt<?php echo $hunt['hunt_id']; ?>">
    								<div class="card mb-3 green <?php if($favorite) { echo 'favorite'; } ?>">
                      <!-- //TODO card-img-top distorts image but successfuly crops it, check out how -->
    									<img src="./images/hunt<?php echo $hunt['hunt_id']; ?>.jpg" class="d-block w-300 mx-auto m-3 rounded-more img-fluid pl-3 pr-3" alt="Picture of <?php echo $hunt['user_id']; ?>">
    									<div class="card-body pl-0 ml-0 pr-0 mr-0 text-light green ">
                        <!-- TODO: make a drop down to select user's hunts or all hunts, possibly by animal, location etc.  -->
                        <h6 class="card-subtitle float-left pl-3"><?php echo $stringDate; ?></h6>
                        <h6 class="card-subtitle float-right pr-3"><?php echo $hunt['locationName']; ?></h6>
                        </br>
                        <h3 class="card-title mx-auto text-center"><?php echo $hunt['username'] . ' got a ' . $hunt['animalName']; ?></h3>
    									</div>
    								</div>
    							</div>
  				<?php }
        }}else if($huntSelector == 'userHunts') {
            foreach ($user_hunts as $hunt) {
            if($hunt['isSuccess'] == 'true'){
              $dateToString = $hunt['huntDate'];
              $dateSplit = explode("-", $dateToString);
              $stringDate = $dateSplit[1] . '-' . $dateSplit[2] . '-' . $dateSplit[0];
                # we'll create a quick temporary variable for each called $favorite - used shortly to add the "favorite" class name where appropriate.
                if(isset($_SESSION['username'])){$currentUserID = $_SESSION['user_id'];} ?>
                <div class="col-12 col-sm-6 col-md-6 col-lg-5 col-xl-4 mt-3 pt-3 pb-3" id="hunt<?php echo $hunt['hunt_id']; ?>">
                  <div class="card mb-3 green <?php if($favorite) { echo 'favorite'; } ?>">
                    <!-- //TODO card-img-top distorts image but successfuly crops it, check out how -->
                    <img src="./images/hunt<?php echo $hunt['hunt_id']; ?>.jpg" class="d-block w-300 mx-auto m-3 rounded-more img-fluid pl-3 pr-3" alt="Picture of <?php echo $hunt['user_id']; ?>">
                    <div class="card-body pl-0 ml-0 pr-0 mr-0 text-light green ">
                      <!-- TODO: make a drop down to select user's hunts or all hunts, possibly by animal, location etc.  -->
                      <h6 class="card-subtitle float-left pl-3"><?php echo $stringDate; ?></h6>
                      <h6 class="card-subtitle float-right pr-3"><?php echo $hunt['locationName']; ?></h6>
                      </br>
                      <h3 class="card-title mx-auto text-center"><?php echo 'You' . ' got a ' . $hunt['animalName']; ?></h3>
                    </div>
                  </div>
                </div>
            <?php } }?>
  			</div>
      <?php } }else{?>
        <div class="container">
          <div class="row mt-5">
            <div class="card col-12 mx-auto">
              <div class="card-header row">
                <input id="address" class="form-control col-9" type="search" placeholder="Search" aria-label="Search">
                <button id="searchMap" class="white font-weight-bold btn dark  col-2 mx-auto" type="submit">Search</button>
              </div>
            </div>
          </div>
          <div class="row pt-3">
            <div class="col-12 card mx-auto" id="map" style="width:100%; height:400px;"></div>
          </div>
        </div>



      <?php } ?>
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
