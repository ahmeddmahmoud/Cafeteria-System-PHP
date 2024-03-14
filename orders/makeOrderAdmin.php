<?php
require("../db.php");
$obj = new DB();
session_start();
// Check if user is logged in
if (isset($_SESSION['id'])) {
  $name = $_SESSION['name'];
  $user_id = $_SESSION['id'];
} else {
  // Redirect to login page if user is not logged in
  setcookie("msg", "You are not logged in, please login first");
  header("Location: ../login/login.php");
  exit(); // Stop further execution
}

$productData = $obj->getData("product","available=1")->fetch_all(MYSQLI_ASSOC);
$roomsNums = $obj->getData("rooms", "1", "room_no")->fetch_all(MYSQLI_ASSOC);
$userNames = $obj->getData("user", "role!='admin'", "name")->fetch_all(MYSQLI_ASSOC);



?>
<style>
  .userimg {
    width: 50px;
    border-radius: 50%;
    height: 50px;
  }

  .allproduct img {
    cursor: pointer;
    margin: auto;
    display: inline-block;
  }
  .quantityTd{
    text-align: center;
    width: 0% !important;
  }
  .priceTd{
    width: 20%;
  }
  table input{
    cursor: default;
  }
</style>
<link rel="stylesheet" href="main.css">
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <title>Order</title>
</head>

<body>
  <?php include '../components/nav.php'; ?>
  <section class="row g-0 mt-4 justify-content-evenly">
    <div class=" col-7 h-50 row ">
      <hr>
      <div class="allproduct row">
      <h4 class='text-center bg-dark rounded text-light p-1'>Available product </h4>
        <?php
        foreach ($productData as $row) {
          echo "<div class='productcard card col-3 mb-2  text-center'>";
          foreach ($row as $key => $value) {
            if ($key == 'image') echo "<img class='h-75 w-75' src='../imgs/products/$value'>";
          }
          foreach ($row as $key => $value) {
            if ($key == "name") echo "<h4>$value</h4> ";
          }
          foreach ($row as $key => $value) {
            if ($key == "price") echo "<div class='card-footer'>$value</div>";
          }
          echo "</div>";
        }

        // 
        ?>
      </div>
    </div>
    <div class="reset col-4">
      <div class="card">
        <div class="card-header text-center">
        <h4 class="fw-bold">Order Details</h4>
        </div>
        <div class="card-body">
          <form action="orderControl.php" method="post">
            <label for="select" class="text-center">Add Order To</label>
            <select name="userNameByAdmin" class="form-select" id="select">
              <?php
              for ($i = 0; $i < count($userNames); $i++) {
                echo "<option value='{$userNames[$i]['name']}'>{$userNames[$i]['name']}</option>";
              }
              ?>
            </select>
            <div class="table-responsive">
              <table class="table  table-hover ">
                <thead class="text-center">
                  <th>product</th>
                  <th>price</th>
                  <th>+</th>
                  <th>quntitiy</th>
                  <th>-</th>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <div class=" form-floating">
              <textarea class="form-control" name="notes" id="notes" cols="30" rows="4" placeholder="notes"></textarea>
              <label for="notes">Notes</label>
            </div>
            <select name="roomNum" id="" class="form-select my-1">
              <option value="" disabled selected>Select room</option>
              <?php
              for ($i = 0; $i < count($roomsNums); $i++) {
                echo "<option value='{$roomsNums[$i]['room_no']}'>{$roomsNums[$i]['room_no']}</option>";
              }
              ?>
            </select>
            <hr>
            <input type="submit" value="confirm" class="btn btn-outline-success">
          </form>
        </div>
        <div class="card-footer text-center">
          <h3>Total price is --</h3>
        </div>
      </div>
    </div>
  </section>
  <!-- Option 1: Bootstrap Bundle with Popper -->
</body>

</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  let cards = document.querySelectorAll(".productcard img");
  let table = document.querySelector(".table tbody");
  let totalPrice = document.querySelector(".card-footer h3");
  cards.forEach(card => {
    card.addEventListener("click", addRow, {
      once: true
    });
  });

  function addRow(event) {
    let tr;
    tr = document.createElement("tr");
    let productName = createInput(event.target.nextElementSibling.innerText, "name[]");
    let ProductPrice = createInput(event.target.parentNode.lastChild.innerText, "price[]");
    let plusBtn = createButton("+");
    let quantity = createInput("1", "quantity[]");
    let minusBtn = createButton("-");
    tr.appendChild(productName);
    tr.appendChild(ProductPrice);
    tr.appendChild(plusBtn);
    tr.appendChild(quantity);
    tr.appendChild(minusBtn);
    table.appendChild(tr);
    let sum = calcTotalPrice();
    totalPrice.innerText = `Total Price is ${sum}`;
  }

  function createInput(value, type) {
    let td;
    td = document.createElement("td");
    input = document.createElement("input");
    input.type = "text";
    input.name = type
    if (type === "price[]") {
      input.classList.add("price");
      td.classList.add("priceTd");
    }
    if (type === "quantity[]") {
      input.classList.add("quantity");
      td.classList.add("quantityTd");
    }
    input.classList.add("form-control");
    input.value = value;
    input.setAttribute("readonly", "readonly");
    td.appendChild(input);
    return td;
  }

  function createButton(mark) {
    let td;
    td = document.createElement("td");
    let span = document.createElement("span");
    if (mark === "+") {
      span.classList.add("btn", "btn-success");
      span.addEventListener("click", addOne);
    } else {
      span.classList.add("btn", "btn-danger");
      span.addEventListener("click", decraseOne);
    }
    span.append(mark);
    td.appendChild(span);
    return td;
  }

  function addOne(event) {
    event.target.parentNode.nextElementSibling.firstChild.value =
      parseInt(event.target.parentNode.nextElementSibling.firstChild.value) + 1;
    let sum = calcTotalPrice();
    totalPrice.innerText = `Total Price is ${sum}`;
  }

  function decraseOne(event) {
    event.target.parentNode.previousElementSibling.firstChild.value =
      parseInt(event.target.parentNode.previousElementSibling.firstChild.value) - 1;
    if (parseInt(event.target.parentNode.previousElementSibling.firstChild.value) == 0) {
      event.target.parentNode.parentNode.remove();
      let cards = document.querySelectorAll(".productcard img");
      cards.forEach(card => {
        if (card.nextElementSibling.innerText === event.target.parentNode.parentNode.firstChild.firstChild.value)
          card.addEventListener("click", addRow, {
            once: true
          });
      });
    }
    let sum = calcTotalPrice();
    totalPrice.innerText = `Total Price is ${sum}`;
  }

  function calcTotalPrice() {
    let priceInputs = document.querySelectorAll(".price");
    let quantityInputs = document.querySelectorAll(".quantity");
    let sum = 0,
      rowSum = 0;
    // console.log(quantityInputs);
    for (let i = 0; i < priceInputs.length; i++) {
      for (let j = 0; j < quantityInputs.length; j++) {
        rowSum = parseFloat(priceInputs[i].value) * +quantityInputs[i].value;
      }
      sum += rowSum;
    }
    return +sum.toFixed(3);
  }
</script>