<nav class="navbar navbar-expand-lg navbar-dark">
  <!--color scheme hex codes:brown663300	darkyellowCC9900	dark green333300	orangeCC6600 -->

  <a class="navbar-brand" href="index.php">Hunt-Hunt</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?php if($page === "book"){ echo 'active';}?>">
        <a class="nav-link" href="book.php">Book</a>
      </li>
      <li class="nav-item <?php if($page === "plan"){ echo 'active';}?>">
        <a class="nav-link" href="plan.php">Plan</a>
      </li>
      <li class="nav-item <?php if($page === "report"){ echo 'active';}?>">
        <a class="nav-link" href="report.php">Report</a>
      </li>
      <li class="nav-item <?php if($page === "account"){ echo 'active';}?>">
        <a class="nav-link" href="account.php">Account</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
    </ul>

  </div>
</nav>
