<?php
require("../db.php");
$obj = new DB();
$data = $obj->getData("product")->fetch_all(MYSQLI_ASSOC);
$rooms = $obj->getData("rooms", "1", "room_no")->fetch_all(MYSQLI_ASSOC);
session_start();
$image = "../imgs/users/" . $_SESSION['image'];
$userName = $_SESSION['name'];
$userID = $_SESSION['id'];
$lastOrderID = $obj->getData("orders", "user_id={$userID} order by id desc limit 1", "id");
$lastOrderID=$lastOrderID->fetch_assoc();
if($lastOrderID!=null){
  $lastOrderID=$lastOrderID['id'];
  $lastOrder = $obj->getData("orders_product as o_p, product as p", "p.id = o_p.product_id 
  and o_p.order_id={$lastOrderID}", "p.name,p.image")->fetch_all(MYSQLI_ASSOC);
}
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
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="#">
        <?php
        if (isset($_SESSION["image"])) {
          echo "<img src='{$image}' class='userimg' >";
        }
        if (isset($_SESSION["name"])) {
          echo "<span class='ms-2'>{$userName}</span>";
        }
        ?>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./myorders.php">My Orders</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link " aria-current="page" href="#">Home</a>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="./myOrders.php">My Orders</a>
      </li>
    </ul>
  </div>
  </div>
  </nav>
  <section class="row g-0 mt-4 justify-content-evenly">
    <div class=" col-7 h-50 row ">
      <div class="last row h-50 mb-2">
        <?php
        if(isset($lastOrder)){
          echo "<h4 class='text-center'>Your last order was: </h4>";
          for ($i = 0; $i <  count($lastOrder); $i++) {
            echo "<div class=' card col-3 mb-2  text-center'>";
            foreach ($lastOrder[$i] as $key => $value) {
              if ($key == 'image') echo "<img class='h-75 w-75' src='../imgs/products/$value'>";
            }
            foreach ($lastOrder[$i] as $key => $value) {
              if ($key == "name") echo "<h4>$value</h4> ";
            }
            echo "</div>";
          }
        }else{
          echo "<h4 class='text-center'>No last Order to Show</h4>";
        }
        ?>
      </div>
      <hr>
      <div class="allproduct row">
        <?php
        foreach ($data as $row) {
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

        ?>
      </div>
    </div>
    <div class="reset col-4">
      <div class="card">
        <div class="card-header">
          <p>order list</p>
        </div>
        <div class="card-body">
          <form action="orderControl.php" method="post">
            <div class="table-responsive">
              <table class="table table-striped ">
                <thead>
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
              for ($i = 0; $i < count($rooms); $i++) {
                echo "<option value='{$rooms[$i]['room_no']}'>{$rooms[$i]['room_no']}</option>";
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
<script>
  let cards = document.querySelectorAll(".productcard img");
  let table = document.querySelector(".table-striped tbody");
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
    }
    if (type === "quantity[]") {
      input.classList.add("quantity");
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