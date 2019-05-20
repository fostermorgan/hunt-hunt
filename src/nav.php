<nav class="navbar navbar-expand-lg navbar-dark col mb-3">
  <!--color scheme hex codes:brown663300	darkyellowCC9900	dark green333300	orangeCC6600 -->

  <a class="navbar-brand" href="index.php">Hunt-Hunt</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php if(isset($_SESSION['username'])) { ?>
        <li class="nav-item <?php if($page === "report"){ echo 'active';}?>">
          <a class="nav-link" href="report.php">Report</a>
        </li>
      <?php } ?>
      <li class="nav-item <?php if($page === "hunts"){ echo 'active';}?>">
        <a class="nav-link" href="hunts.php">Hunts</a>
      </li>
      <!-- TODO add functionality where you can view your hunts-> maybe new tab or just in hunt.php -->

    </ul>
    <div>
      <ul class="navbar-nav navbar-right">
        <li class="dropdown">
          <a href="" class="dropdown-toggle text-light" data-toggle="dropdown"><b><?php if(isset($_SESSION['username'])){echo $_SESSION['username'];}else{echo 'Login';} ?></b>&nbsp;<span class="caret"></span></a>
          <ul class="dropdown-menu p-3 m-3">
            <?php include 'loginModal.php' ?>
          </ul>
        </li>
      </ul>
    </div>
</nav>
