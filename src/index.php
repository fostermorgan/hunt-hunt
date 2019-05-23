<?php
  include_once "db_connection.php";

  $page = 'home';
  session_start();

  $database = new Connection();
  $conn = $database->openConnection();

  # grab all the users so we can populate the drop-downs on the page.
  $statement = $conn->prepare("SELECT * FROM hunts INNER JOIN users ON hunts.user_id=users.user_id
                                INNER JOIN animals ON hunts.animal_id = animals.animal_id
                                  INNER JOIN locations ON hunts.location_id = locations.location_id ORDER BY huntDate DESC LIMIT 10;");
  $statement->execute();
  $hunt_tuples = $statement->fetchAll();

  // 1) htdocs/myfinalproject/{...all these files...}
  // 2) export database as sql file
  // - container
  //   - row
  //     - col
  //       - cards

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

<!-- TODO add weather wigit for location? -->
    <div class="container">

      <?php if($message !== ""){ ?>
        <div id="jsErrorMessage" class="alert alert-<?php echo $message_type; ?>" role="alert"><?php echo $message; ?></div>
      <?php } ?>


      <div class="row">
        <div class="col-12">
          <div class="jumbotron yellow p-0">
            <h1 class="display-3 text-center">Featured Stories</h1>
          </div>
        </div>
        <div id="carouselExampleIndicators" class="carousel slide green col-12 rounded mx-auto" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
          </ol>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="d-none d-md-block text-center text-light pt-3">
                <h5>Joe,</h5>
                <p>Successful hunt in Wausau, WI.</p>
              </div>
              <img class="d-block w-300 mx-auto card mb-3 rounded-more mt-3" src="images\hunt59.jpg" alt="First slide" height="400">
            </div>
            <div class="carousel-item">
              <div class="d-none d-md-block text-center text-light pt-3">
                <h5>Bobby,</h5>
                <p>Successful hunt in St. Paul, MN.</p>
              </div>
              <img class="d-block w-300 mx-auto card mb-3 rounded-more mt-3" src="images\hunt56.jpg" alt="Second slide" height="400">
            </div>
            <div class="carousel-item card green">
              <div class="d-none d-md-block text-center text-light pt-3">
                <h5>Billy,</h5>
                <p>Successful hunt in Indiantown, FL.</p>
              </div>
              <img class="d-block w-300 mx-auto card mb-3 rounded-more mt-3" src="images\hunt53.jpg" alt="Third slide" height="400">
            </div>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
        <div class="col-12 jumbotron yellow p-0 pt-3">
          <h1 class="display-3 text-center">Recent Hunts</h1>
        </div>
        <div class="col-12 card green text-light pt-3 mb-5">

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
               foreach($hunt_tuples as $hunt){
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
      </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAL2wti_wS8G_3VMWmLuV7Ih2MZZu7ZErs&callback=myMap"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
