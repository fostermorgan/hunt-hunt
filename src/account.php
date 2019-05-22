<?php

  include_once "db_connection.php";
  session_start();
  $page = "account";
  $database = new Connection();
  $conn = $database->openConnection();
  global $message;
  global $message_type;

?>

<!-- make account a modal/dropdown that you can login, or create new account,
        if logged in, can see your successful hunts + stats for that user on a seperate page
        update full name option, -->
          <!-- <div class="form-group">
            <label for="name">Full name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="First Last">
          </div> -->


<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="hunt.css">


    <title>Hunt Hunt | Account</title>
  </head>
  <body>
<!--color scheme hex codes:brown #663300	darkyellow #CC9900	dark green #333300	orange #CC6600 -->
<!-- p5.js libraries? -->

    <?php include 'nav.php' ?>

    <div class="container">
      <?php if($message !== ""){ ?>
        <div id="jsErrorMessage" class="alert alert-<?php echo $message_type; ?>" role="alert"><?php echo $message; ?></div>
      <?php } ?>

			<div class="row mx-auto">

        <div class="col-12">
          <div class="jumbotron yellow">
            <h1 class="display-3 text-green"><?php echo $_SESSION['username']; ?>'s Profile!</h1>
          </div>
        </div>

          <?php
            $user_id = $_SESSION['user_id'];
            $updateStatement = $conn->prepare("SELECT * FROM hunts INNER JOIN users ON hunts.user_id=users.user_id
                                          INNER JOIN animals ON hunts.animal_id = animals.animal_id
                                            INNER JOIN locations ON hunts.location_id = locations.location_id
                                              WHERE hunts.user_id='$user_id' ORDER BY huntDate DESC");
            $updateStatement->execute();
            $userHunts = $updateStatement->fetchAll();
            $nHunts = 0;
            $nSuccess = 0;
            foreach($userHunts as $hunt){
              $nHunts += 1;
              if($hunt['isSuccess'] == 'true'){
                $nSuccess += 1;
              }
            }

            if($nHunts > 0){ ?>

        <div class="col-12 card green text-light pt-3">

          <table class="table text-light table-bordered">
            <thead class="yellow text-dark">
              <tr>
                <th scope="col">Hunt</th>
                <th scope="col">Date</th>
                <th scope="col">Location</th>
                <th scope="col">Animal</th>
                <th scope="col">Successful?</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $count = 1;
               foreach($userHunts as $hunt){
                $success;
                if($hunt['isSuccess'] == 'true'){
                  $success = 'Yes';
                }else{
                  $success = 'No';
                }

                $dateToString = $hunt['huntDate'];
                $dateSplit = explode("-", $dateToString);
                $stringDate = $dateSplit[1] . '-' . $dateSplit[2] . '-' . $dateSplit[0];
                $text = 'A' . $success . $hunt['animalName'] . ' hunt.'; ?>
                <tr>
                  <th scope="row"><?php echo $count; ?></th>
                  <th ><?php echo $stringDate; ?></th>
                  <td><?php echo $hunt['locationName']; ?></td>
                  <td><?php echo $hunt['animalName']; ?></td>
                  <td><?php echo $success ?></td>
                </tr>

              <?php $count++; }?>
            </tbody>
          </table>
        </div>
        <h4 class="mt-3 mb-5">Hunt Success Rate: <?php  echo $nSuccess/$nHunts *100 . '%'; ?></h4>



        <?php } else {?>
        <div class="col-12 card green text-light pt-2 mb-5 ">
          <h3 class="text-center">You have yet to register a hunt! Once you start registering, you can view your hunts and stats here.</h3>
        </div>
        <?php } ?>

			</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
