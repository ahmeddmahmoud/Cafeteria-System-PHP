<?php
// 
echo <<<HTML
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="#">
HTML;

if (isset($_SESSION["image"])) {
    echo "<img src='../imgs/users/{$_SESSION['image']}' class='userimg' >";
}
if (isset($_SESSION["name"])) {
    echo "<span class='ms-2'>{$_SESSION['name']}</span>";
}

$activeClass = $_SESSION['role'] === 'user' ? 'active' : '';


echo <<<HTML
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link $activeClass" aria-current="page" href="../orders/makeOrderUser.php">Home</a>
        </li>
        
HTML;

if ($_SESSION['role'] === 'user') {
    echo <<<HTML
        <li class="nav-item">
          <a class="nav-link" href="../orders/myorders.php">My Orders</a>
        </li>
HTML;
}

if ($_SESSION['role'] === 'admin') {
    echo <<<HTML
        <li class="nav-item">
          <a class="nav-link" href="../orders/allOrders.php">Manual Order</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../orders/makeOrderAdmin.php">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../users/usersTable.php">Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../orders/allOrders.php">Checks</a>
        </li>
HTML;
}

echo <<<HTML
        <li class="nav-item mx-4">
          <a class="nav-link $activeClass btn btn-danger text-light" aria-current="page" href="../login/logout.php">Logout</a>
        </li>
HTML;

echo <<<HTML
      </ul>
    </div>
  </div>
</nav>
HTML;
