<?php
// 
echo <<<HTML
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow mb-4">
  <div class="container">
    <span class="navbar-brand d-flex align-items-center" href="#">
HTML;

if (isset($_SESSION["image"])) {
  echo "<img src='../imgs/users/{$_SESSION['image']}' class='userimg' >";
}
if (isset($_SESSION["name"])) {
  echo "<span class='ms-3'>{$_SESSION['name']}</span>";
}

$activeClass = $_SESSION['role'] === 'user' ? 'active' : '';


echo <<<HTML
    </span>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="nav mt-2 ">
HTML;

if ($_SESSION['role'] === 'user') {
  echo <<<HTML
        <li class="">
          <a class="btn btn-outline-dark mx-2  border-0" href="../orders/myorders.php">My Orders</a>
        </li>
HTML;
} else if ($_SESSION['role'] === 'admin') {
  echo <<<HTML
        <li class="">
          <a class="btn btn-outline-dark mx-2  border-0" href="../orders/allOrders.php">All Orders</a>
        </li>
HTML;
}

if ($_SESSION['role'] === 'admin') {
  echo <<<HTML
        <li class="">
          <a class="btn btn-outline-dark mx-1 border-0 " href="../orders/makeOrderAdmin.php">Manual Order</a>
        </li>
        <li class="">
          <a class="btn btn-outline-dark mx-1 border-0" href="../products/productTable.php">Products</a>
        </li>
        <li class="">
          <a class="btn btn-outline-dark mx-1 border-0" href="../users/usersTable.php">Users</a>
        </li>
        <li class="">
          <a class="btn btn-outline-dark mx-1 border-0" href="../checks/checks.php">Checks</a>
        </li>
HTML;
}

echo <<<HTML
        <li class="">
          <a class="btn btn-danger border-0 ms-5" aria-current="page" href="../login/logout.php">Logout</a>
        </li>
HTML;

echo <<<HTML
      </ul>
    </div>
  </div>
</nav>
HTML;
