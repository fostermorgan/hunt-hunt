<?php
  include_once "db_connection.php";
  session_start();
  $page = 'hunts';

  $database = new Connection();
  $conn = $database->openConnection();

  $message = "";
  $message_type = "";


  //for the dropdown filter
  $statement = $conn->prepare("SELECT * FROM animals");
  $statement->execute();
  $animal_tuples = $statement->fetchAll();

  // if(isset($_POST['searchMap']){
  //   recenterMap();
  // }
  $user_id = '';
  if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
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
  $addSuccessesOnly = " WHERE hunts.isSuccess = 'true'";
  $addUserHuntsToQuery = "";
  if($huntSelector != 'allHunts'){
    $addUserHuntsToQuery = " WHERE hunts.user_id = '$user_id'";
    $addSuccessesOnly = " AND hunts.isSuccess = 'true'";
  }
  $addAnimalToQuery = "";
  if($animalSelector != 'allAnimals' && $addUserHuntsToQuery != ''){
    $animalID = substr($_GET['animalSelector'], 6);
    $addAnimalToQuery = ' AND hunts.animal_id = ' . $animalID;
    $addSuccessesOnly = " AND hunts.isSuccess = 'true'";
  }else if($animalSelector != 'allAnimals' && $addUserHuntsToQuery == ''){
    $animalID = substr($_GET['animalSelector'], 6);
    $addAnimalToQuery = ' WHERE hunts.animal_id = ' . $animalID;
    $addSuccessesOnly = " AND hunts.isSuccess = 'true'";
  }
  $query = 'SELECT * FROM  hunts
                      INNER JOIN animals ON hunts.animal_id = animals.animal_id
                        INNER JOIN users ON hunts.user_id = users.user_id
                          INNER JOIN locations ON hunts.location_id = locations.location_id' . $addUserHuntsToQuery . $addAnimalToQuery . $addSuccessesOnly . ';';
  $statement = $conn->prepare($query);
  $statement->execute();
  $hunt_tuples = $statement->fetchAll();

?>


<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="hunt.css">
    <title>Hunt Hunt | Hunts</title>
  </head>
  <body>
    <?php include 'nav.php' ?>
    <div class="container">
      <?php if($message !== ""){ ?>
        <div id="jsErrorMessage" class="alert alert-<?php echo $message_type; ?>" role="alert"><?php echo $message; ?></div>
      <?php } ?>
      <!-- DROP DOWN MENUS -->
      <div class="pt-3">
        <!-- VIEW SELECTOR -->
          <form method="get">
            <div class="row rounded green pt-2 pb-2 mx-auto">
              <div class="col-1 text-light">
                <h3>Filters: </h3>
              </div>

              <div class="col col-sm-4 pt-1">
    						<!-- onchange="this.form.submit()" submits this mini-form every time something is selected from the dropdown. -->
    						<select style="width: auto; margin: auto;" class="form-control" onchange="this.form.submit()" name="view">
    							<option value="huntView" <?php if($view == 'huntView') { echo " selected"; } ?>>Default View (Hunt List)</option>
                  <option value="mapView" <?php if($view == 'mapView') { echo " selected"; } ?>>Map View (Interactive Map)</option>
    						</select>
              </div>
              <?php if(isset($_SESSION['username'])){?>
                <div class="col col-sm-3 pt-1">
                  <select style="width: auto; margin: auto;" class="form-control" onchange="this.form.submit()" name="huntSelector">
                    <option value="allHunts" <?php if($huntSelector == 'allHunts') { echo " selected"; } ?>>All Hunts</option>
                    <option value="userHunts" <?php if($huntSelector == 'userHunts') { echo " selected"; } ?>><?php echo $_SESSION['username']; ?>'s Hunts</option>
                  </select>
                </div>
              <?php } ?>
              <div class="col col-sm-3 pt-1">
                <select style="width: auto; margin: auto;" class="form-control" onchange="this.form.submit()" name="animalSelector">
                  <option value="allAnimals">All Animals</option>
                  <?php
                  # loop through each tuple in our "type" result set and produce one <option> for each.
                  foreach($animal_tuples as $animal){ ?>
                    <option value="animal<?php echo $animal["animal_id"]; ?>" <?php if($animalSelector == 'animal' . $animal['animal_id']) { echo " selected"; } ?>><?php echo $animal["animalName"]; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
					</form>
        </div>
        <!-- TODO if time add lcoation filter? -->
      <?php if($view == 'huntView') { ?>
        <div class="row">
          <!-- display each successful hunt -->
          <?php
            foreach ($hunt_tuples as $hunt) {
              if($hunt['isSuccess'] == 'true'){
                $dateToString = $hunt['huntDate'];
                $dateSplit = explode("-", $dateToString);
                $stringDate = $dateSplit[1] . '-' . $dateSplit[2] . '-' . $dateSplit[0];
    					    if(isset($_SESSION['username'])){$currentUserID = $_SESSION['user_id'];} ?>
    							<div class="col-12 col-sm-6 col-md-6 col-lg-5 col-xl-4 mt-3 pt-3 pb-3" id="hunt<?php echo $hunt['hunt_id']; ?>">
    								<div class="card mb-3 green <?php if($favorite) { echo 'favorite'; } ?>">
    									<img src="./images/hunt<?php echo $hunt['hunt_id']; ?>.jpg" class="d-block w-300 mx-auto m-3 rounded-more img-fluid pl-3 pr-3" alt="Picture of <?php echo $hunt['user_id']; ?>">
    									<div class="card-body pl-0 ml-0 pr-0 mr-0 text-light green ">
                        <h6 class="card-subtitle float-left pl-3"><?php echo $stringDate; ?></h6>
                        <h6 class="card-subtitle float-right pr-3"><?php echo $hunt['locationName']; ?></h6>
                        </br>
                        <h3 class="card-title mx-auto text-center"><?php echo $hunt['username'] . ' got a ' . $hunt['animalName']; ?></h3>
    									</div>
    								</div>
    							</div>
  				     <?php }
             }?>
  			</div>
      <?php } else{?>
        <div class="container">
          <div class="row pt-3 mt-3 mb-5">
          <div id="map" class="col-12 card mx-auto" style="width:100%; height:565px;"></div>
          		<?php
          			require 'hunt.php';
          			$hunt = new hunt;
          			$allData = $hunt->getAllHunts($query);
          			$allData = json_encode($allData, true);
          			echo '<div id="allData">' . $allData . '</div>';
          		 ?>
             </div>
        </div>
      <?php } ?>
    </div>


    <script type="text/javascript">
    var map;
    var geocoder;
    function initMap() {
    	var eauclaire = {lat: 44.8113, lng: -91.4985};
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 6,
          center: eauclaire
        });
        // setLocation();
        var allData = JSON.parse(document.getElementById('allData').innerHTML);
        showAllHunts(allData);

    }

    function showAllHunts(allData) {
    	var infoWind = new google.maps.InfoWindow;
      var buffer = 0;
    	Array.prototype.forEach.call(allData, function(data){
    		var content = document.createElement('div');
        var br = document.createElement('br');
    		var animalName = document.createElement('strong');
        var locationName = document.createElement('strong');


    		animalName.textContent = data.animalName + " in ";
        locationName.textContent = data.locationName;
    		content.appendChild(animalName);
        content.appendChild(br);
        content.appendChild(locationName);

        var hunt_id = data.hunt_id;
    		var img = document.createElement('img');
    		img.src = 'images/hunt' + hunt_id + '.jpg';
    		img.style.width = '100px';
    		content.appendChild(img);
        var lat = parseFloat(data.latitude) + buffer;
        buffer += .001;

    		var marker = new google.maps.Marker({
    	      position: new google.maps.LatLng(lat, data.longitude),
    	      map: map
    	    });

    	    marker.addListener('mouseover', function(){
    	    	infoWind.setContent(content);
    	    	infoWind.open(map, marker);
    	    })

    	})
    }
    </script>

    <!-- For google maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAL2wti_wS8G_3VMWmLuV7Ih2MZZu7ZErs&callback=initMap"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
