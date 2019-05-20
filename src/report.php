<?php

  include_once "db_connection.php";
  session_start();
  $page = 'report';

  $database = new Connection();
  $conn = $database->openConnection();

  # grab all the users so we can populate the drop-downs on the page.
  $statement = $conn->prepare("SELECT * FROM users;");
  $statement->execute();
  $user_tuples = $statement->fetchAll();

  $statement = $conn->prepare("SELECT * FROM locations;");
  $statement->execute();
  $location_tuples = $statement->fetchAll();

  $message1 = "";
  $message_type1 = "";

  function verifyHuntForm(){
    global $message1;

    if($_POST['date'] == ""){
      $message1 = 'You must select a date.';
			return false;
		}else if($_POST['locationDropdown'] == '' && $_POST['locationSearch'] == ''){
      $message1 = "You must input a location.";
			return false;
    }else if($_POST['animal'] == ''){
      $message1 = "You must input an animal.";
			return false;
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

    // if(photo is not empty){//wont need since no photo attribute
    //   $photo = 'images/hunt'. $_SESSION['hunt_id'];
    // }
    //derive location id from POST data to insert into the hunts table
    //if they added a new location, add it to the locations table
    $location_id;
    if($_POST['locationSearch'] != ''){
      //add new location to database
      $statement = $conn->prepare("INSERT INTO locations (locationName) VALUES (:locationName);");
      $statement->bindValue(":locationName",$_POST['locationSearch']);
      $statement->execute();
      $location_name = $_POST['locationSearch'];

      //pull that location_id of the new location added
      $updateStatement = $conn->prepare("SELECT location_id FROM locations WHERE locationName='$location_name'");
      $updateStatement->execute();
      $result = $updateStatement->fetch();
      $location_id = (int)$result['location_id'];
    }else{
      $location_id = substr($_POST['locationDropdown'], 8);
    }


    //derive animal id from POST data to insert into the hunts table
    $animal_name = $_POST['animal'];
    $statment = $conn->prepare("SELECT animal_id FROM animals WHERE animalName='$animal_name'");
    $statment->execute();
    $result = $statment->fetch();
    $animal_id = (int)$result['animal_id'];

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

  }//add a trigger so when user adds hunt, incrments nHunts for that user

  if(isset($_POST['addHunt'])){
    if(verifyHuntForm() && verifyLocation()){
      addHunt();
      $message1 = "Hunt has been successfully added!";
      $message_type1 = "success";
      // header("Location: hunts.php");
    }else {
      $message_type1 = "danger";
    }
  }
  function verifyLocation(){
    global $conn;
    global $message1;
    $distArray = array();
    $updateStatement = $conn->prepare("SELECT * FROM locations;");
    $updateStatement->execute();
    $location_tuples = $updateStatement->fetchAll();
    if($_POST['locationSearch'] !== ''){// if the location they enter is within 20 miles of a different location, prompt them to use a dropdown

     foreach($location_tuples as $location){
        $dest1 =  str_replace(' ', '', $location['locationName']);
        $dest2 = str_replace(' ', '', $_POST['locationSearch']);
        $details = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=" . $dest1 . "&destinations=" . $dest2 . "&key=AIzaSyAL2wti_wS8G_3VMWmLuV7Ih2MZZu7ZErs";
        $json = file_get_contents($details);
        $details = json_decode($json, TRUE);

        array_push($distArray, str_replace(',', '', $details['rows'][0]['elements'][0]['distance']['text']));
      }
      foreach($distArray as $dist){
        if($dist < 20){
            $message1 =  "Location is close to a previously added location. Select one from the dropdown menu.";
            return false;
        }
      }
    }
    return true;
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

					<div class="jumbotron text-dark mt-1 white">
						<h1 id='jumbo' class="display-4">Landed a successful hunt?</h1>
						<p class="lead text-center">Submit your hunt here!</p>
					</div>
        </div>

        <div class="col-12">
					<form method="post" enctype="multipart/form-data">
            <div class="row">
  						<div class="form-group col-3">
  							<label for="date">Date</label>
  							<input type="date" class="form-control" id="date" name="date" placeholder="mm-dd-yyyy">
  						</div>
              <div class="form-group col-3">
                <label for="locationDropdown">Select a Location: </label>
                <select style="width: auto; margin: auto;" class="form-control float-left" name="locationDropdown">
                  <option value=""></option>
                  <?php
    							foreach($location_tuples as $location){ ?>
                    <option value="location<?php echo $location["location_id"]; ?>"><?php echo $location["locationName"]; ?></option>
    							<?php } ?>
    						</select>
              </div>
              <div class="form-group col-4">
  							<label for="location">Or type it in here...</label>
  							<input type="text" class="form-control" id="location" name="locationSearch" placeholder="[city, state]">
  						</div>
              <!-- TODO://make animal a dropdown of all avaible animals in the database -->
              <div class="form-group col-4">
  							<label for="animal">Animal</label>
  							<input type="text" class="form-control" id="animal" name="animal" placeholder="Deer">
  						</div>
              <div class="form-group col-1">
                <label for="isSuccess">Was it a success?</label>
                <input type="checkbox" class="form-control-file" id="isSuccess" name="isSuccess">
              </div>
              <!-- TODO //ask if anmial isn't on there and they would like to add it -->
  						<div class="form-group col-9">
  							<label for="photo">Have a photo of your hunt? Upload it here.</label>
  							<input type="file" class="form-control-file" id="photo" name="photo">
  						</div>
            </div>

            <div class="form-group pt-3 text-center">
              <button type="submit" class="btn btn-dark" name="addHunt">Submit!</button>
            </div>
					</form>
				</div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
