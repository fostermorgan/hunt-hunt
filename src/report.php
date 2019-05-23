<?php

  include_once "db_connection.php";
  session_start();
  $page = 'report';

  $database = new Connection();
  $conn = $database->openConnection();

  $lat;
  $long;
  $locationName;

  # grab all the users so we can populate the drop-downs on the page.
  $statement = $conn->prepare("SELECT * FROM users;");
  $statement->execute();
  $user_tuples = $statement->fetchAll();

  $statement = $conn->prepare("SELECT * FROM locations;");
  $statement->execute();
  $location_tuples = $statement->fetchAll();

  $message1 = "";
  $message_type1 = "";

  $statement = $conn->prepare("SELECT * FROM animals;");
  $statement->execute();
  $animal_tuples = $statement->fetchAll();

  function verifyHuntForm(){
    global $message1;

    if($_POST['date'] == ""){
      $message1 = 'You must select a date.';
			return false;
		}else if($_POST['locationDropdown'] == ''){
      $message1 = "You must select a location.";
			return false;
    }else if($_POST['animalDropdown'] == ''){
      $message1 = "You must select an animal.";
			return false;
    }

		return true;
  }
  function verifyAnimal(){
    global $message1;
    global $message_type1;
    global $animal_tuples;

    if($_POST['animalSearch'] == '') {
      $message1 = "Please type in an animal!";
      $message_type1 = "danger";
      return false;
    }
    foreach($animal_tuples as $animal){
      if($animal['animalName'] == $_POST['animalSearch']){
        $message1 = "Animal already exists, please select it from the dropdown menu.";
        $message_type1 = "danger";
        return false;
      }
    }
    return true;
  }

  function addHunt(){
    global $conn;
    global $isEmpty;

    $username = $_SESSION['username'];

    // get userID so we can insert their id into the uploaded hunt
    $updateStatement = $conn->prepare("SELECT user_id FROM users WHERE username='$username'");
    $updateStatement->execute();
    $result = $updateStatement->fetch();
    $user_id = $result['user_id'];


    $location_id = substr($_POST['locationDropdown'], 8);

    $animal_id = substr($_POST['animalDropdown'], 6);

    if(isset($_POST['isSuccess'])){
      $isSuccess = 'true';
    }else{
      $isSuccess = 'false';
    }

    // echo "date : ". $_POST['date'] . 'issuccess: ' . $isSuccess . " locationid: " . $location_id . " userid" . $user_id . " animalid: " . $animal_id;

    $statement = $conn->prepare("INSERT INTO hunts (huntDate,isSuccess,location_id,user_id,animal_id) VALUES (:huntDate,:isSuccess,:location_id,:user_id,:animal_id);");
    $statement->bindValue(":huntDate",$_POST['date']);
    $statement->bindValue(":isSuccess",$isSuccess);
    $statement->bindValue(":location_id",$location_id);
    $statement->bindValue(":user_id",$user_id);
    $statement->bindValue(":animal_id",$animal_id);

    $insert_success = $statement->execute();
		if(!$insert_success){
			$message1 = "Unable to add new hunt to database: " . $statement->errorCode() . ".";
			return false;
		}
    if($_FILES['photo']['size'] !== 0){
      //select hunt_id of the new hunt just added so we can save the photo uploaded on harddrive
      $updateStatement = $conn->prepare("SELECT hunt_id FROM hunts WHERE user_id='$user_id' ORDER BY hunt_id DESC;");
      $updateStatement->execute();
      $result = $updateStatement->fetch();
      $hunt_id = $result['hunt_id'];

      //store file as images/hunt + hunt_id + .jpg
      move_uploaded_file($_FILES['photo']['tmp_name'],"images/hunt" . ((int)$hunt_id) . ".jpg");

    }
    return true;

  }

  if(isset($_POST['addHunt'])){
    if(verifyHuntForm()){
      addHunt();
      $message1 = "Hunt has been successfully added!";
      $message_type1 = "success";
      header("Location: hunts.php");
    }else {
      $message_type1 = "danger";
    }
  }

  if(isset($_POST['addLocation'])){
    global $lat;
    global $long;
    global $locationName;

    if(verifyLocation()){
      //add new location to database
      $statement = $conn->prepare("INSERT INTO locations (locationName,longitude,latitude) VALUES (:locationName,:longitude,:latitude);");
      $statement->bindValue(":locationName",$locationName);
      $statement->bindValue(":longitude",$long);
      $statement->bindValue(":latitude",$lat);
      $statement->execute();
      //set message
      $message1 = "Location has been successfully added!";
      $message_type1 = "success";
    }else {
      $message_type1 = "danger";
    }
  }

  if(isset($_POST['addAnimal'])){
    if(verifyAnimal()){
      //add animal to database
      $statement = $conn->prepare("INSERT INTO animals (animalName) VALUES (:animalName);");
      $statement->bindValue(":animalName",$_POST['animalSearch']);
      $statement->execute();
      //set message
      $message1 = "Animal has been successfully added!";
      $message_type1 = "success";

    }
  }

  function verifyLocation(){
    global $conn;
    global $message1;
    global $lat;
    global $long;
    global $locationName;
    $distArray = array();
    $updateStatement = $conn->prepare("SELECT * FROM locations;");
    $updateStatement->execute();
    $location_tuples = $updateStatement->fetchAll();
    $locationAddress;

    if($_POST['locationSearch'] !== ''){// if the location they enter is within 20 miles of a different location, prompt them to use a dropdown

     foreach($location_tuples as $location){
        $dest1 =  str_replace(' ', '', $location['locationName']);
        $dest2 = str_replace(' ', '', $_POST['locationSearch']);
        $locationAddress =  str_replace(' ', '', $_POST['locationSearch']);
        $details = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=" . $dest1 . "&destinations=" . $dest2 . "&key=AIzaSyAL2wti_wS8G_3VMWmLuV7Ih2MZZu7ZErs";
        $json = file_get_contents($details);
        $details = json_decode($json, TRUE);

        array_push($distArray, str_replace(',', '', $details['rows'][0]['elements'][0]['distance']['text']));
      }
      if($locationAddress != ''){
        $coords = "https://maps.googleapis.com/maps/api/geocode/json?address=" . $locationAddress . "&key=AIzaSyAL2wti_wS8G_3VMWmLuV7Ih2MZZu7ZErs";
        $json = file_get_contents($coords);
        $coords = json_decode($json, TRUE);
        $lat = $coords['results'][0]['geometry']['location']['lat'];
        $long = $coords['results'][0]['geometry']['location']['lng'];
        $locationName = $coords['results'][0]['address_components'][0]['long_name'] . ', ' . $coords['results'][0]['address_components'][2]['long_name'];
        // echo "lat: " . $lat . " Long: " . $long . " Location Name: " . $locationName;

      }

      foreach($distArray as $dist){
        if($dist <= 2){
            $message1 =  "Location is close to a previously added location. Select one from the dropdown menu.";
            return false;
        }
      }
      return true;
    }else{
      $message1 =  "Please type in a location. ";
      return false;
    }
  }


?>

<!-- TODO: only be able to add one hunt per day?? database problems -->
<!-- TODO: use ajax to search for an animal/location and have auto suggestions based on existing database values
              if not, then add it to the database-->
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="hunt.css">


    <title>Hunt Hunt | Report</title>
  </head>
  <body>
<!--color scheme hex codes:brown #663300	darkyellow #CC9900	dark green #333300	orange #CC6600 -->
<!-- p5.js libraries? -->
    <?php include 'nav.php' ?>
    <div class="container">


      <?php if($message1 !== ""){ ?>
        <div class="alert alert-<?php echo $message_type1; ?> ">
          <?php echo $message1; ?>
        </div>
      <?php } ?>

      <!-- <div class="card row center green text-light mx-auto"> -->
      <div class="row">
        <!-- <div class="col-12 col-lg-12 yellow"> -->
        <div class="col-12">
          <div class="jumbotron yellow">
            <h1 class="display-3 text-green">Landed a successful hunt?</h1>
						<h3 class="text-center text-green">Submit your hunt here!</h3>
          </div>
        </div>
      </div>

        <div class="col-12 card m-3 p-3 pb-0 mb-0 mx-auto green">
					<form method="post" enctype="multipart/form-data">
            <div class="row">
  						<div class="form-group col-12 col-lg-3">
  							<label for="date" class="text-light">Date</label>
  							<input type="date" class="form-control" id="date" name="date" placeholder="mm-dd-yyyy">
  						</div>

              <div class="form-group col-12 col-lg-3">
                <label for="locationDropdown" class="text-light">Select a Location: </label> <br>
                <select style="width: auto; margin: auto;" class="form-control float-left mr-0" name="locationDropdown">
                  <option value=""></option>
                  <?php
    							foreach($location_tuples as $location){ ?>
                    <option value="location<?php echo $location["location_id"]; ?>"><?php echo $location["locationName"]; ?></option>
    							<?php } ?>
    						</select>
              </div>
              <!-- AddLocations button to trigger modal -->
              <div class="form-group col-12 col-lg-2">
  							<label for="location" class="text-light">Don't see it?</label>
  							<button type="button" class="form-control green btn btn-light yellowText" id="location" name="locationSearch" data-toggle="modal" data-target="#addLocation" >Add Location</button>
  						</div>

              <div class="form-group col-12 col-lg-2">
                <label for="animalDropdown" class="text-light">Select an Animal: </label>  <br>
                <select style="width: auto; margin: auto;" class="form-control float-left" name="animalDropdown">
                  <option value=""></option>
                  <?php
    							foreach($animal_tuples as $animal){ ?>
                    <option value="animal<?php echo $animal["animal_id"]; ?>"><?php echo $animal["animalName"]; ?></option>
    							<?php } ?>
    						</select>
              </div>
              <div class="form-group col-12 col-lg-2">
  							<label for="animalSearch" class="text-light">Don't see it?</label>
  							<button type="button" class="form-control green btn btn-light yellowText" id="animalSearch" name="animalSearch" data-toggle="modal" data-target="#addAnimal">Add Animal</button>
  						</div>

              <div class="form-group col-12 col-lg-4">
  							<label for="photo" class="text-light">Have a photo of your hunt? Upload it here.</label>
  							<input type="file" class="form-control-file text-light" id="photo" name="photo">
  						</div>

              <div class="form-check ml-3 col-md-6 col-lg-3 pt-3 pr-5 mr-5">
                <input type="checkbox" class="form-check-input" id="isSuccess" name="isSuccess">
                <label for="isSuccess" class="text-light">Was it a success?</label>
              </div>
              <!-- TODO //ask if anmial isn't on there and they would like to add it -->
              <div class="form-group ml-5 pt-5 col-6 col-sm-6 col-md-6 col-lg-3 text-right">
                <button type="submit" class="btn btn-light text-dark yellow" name="addHunt">Submit!</button>
              </div>

            </div>


					</form>
      </div>
    </div>
    </div>
    <!-- Add Locations Modal -->
    <div class="modal fade" id="addLocation" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Location</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post">
            <div class="modal-body col-12">
  							<label for="locationSearch">Location Name: </label>
  							<input type="text" class="form-control-file locationSearch" id="locationSearch" name="locationSearch">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-light text-dark yellow" name="addLocation">Add Location</button>
            </div>
          </form>

        </div>
      </div>
    </div>
    <!-- Add Animal Modal -->
    <div class="modal fade" id="addAnimal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add Animal</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post">
            <div class="modal-body col-12">
  							<label for="animalSearch">Animal Name: </label>
  							<input type="text" class="form-control-file animalSearch" id="animalSearch" name="animalSearch">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-light text-dark yellow animalSubmit" name="addAnimal">Add Animal</button>
            </div>
          </form>
        </div>
      </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script>
    //on dismall of modals, clear the values
    $('#addAnimal').on('hidden.bs.modal', function () {
      // location.reload();
      $('.animalSearch').val("");
    });
    $('#addLocation').on('hidden.bs.modal', function () {
      $('.locationSearch').val("");
    });

    </script>
  </body>
</html>
