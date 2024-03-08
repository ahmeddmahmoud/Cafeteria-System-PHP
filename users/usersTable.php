<?php 
include_once '../db.php'; // Include the DB class file

$db = new DB(); // Create an instance of the DB class

// Pagination Variables
$limit = 6; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $limit; // Offset for database query

// Query to fetch total number of records
$total_records_query = $db->getCount("user");
$total_records = $total_records_query->fetch_assoc()['total'];

// Calculate total pages
$total_pages = ceil($total_records / $limit);

// Query to fetch records for current page
$result = $db->getDataPagination("user u INNER JOIN rooms r ON u.room_no = r.room_no", "1", $limit, $offset);

?>
<!doctype html>
<html lang="en">

<head>
    <title>All Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar d-flex justify-content-between " style="background-color: #e3f2fd;">
        <div class="d-flex justify-align-content-between gap-3 px-5 ">
            <a href="#">Home</a>
            <a href="#">Products</a>
            <a href="#">Users</a>
            <a href="#">Manual Orders</a>
            <a href="#">Checks</a>
        </div>
        <div>
            <a href="#" class="px-5">Admin</a>
        </div>
    </nav>

    <div class="d-flex justify-content-around m-2 align-items-center ">
        <p class="fs-3 fw-bold ">All Users</p><button type="button" class="btn btn-primary">Add User</button>

    </div>

    <div class="mx-5 my-2 text-center ">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Room</th>
                    <th>Ext</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['name']."</td>";
                        echo "<td>".$row['image']."</td>";
                        echo "<td>".$row['room_no']."</td>";
                        echo "<td>".$row['ext']."</td>";
                        echo "<td>
                        <a href='editUserForm.php?id={$row['id']}' class='btn btn-warning'>Edit</a>
                        <a href='deleteUser.php?id={$row['id']}&page=$page' class='btn btn-danger'>Delete</a>
                        </td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mx-auto" style="width: fit-content;">
            <nav aria-label="Page navigation example" class="text-center">
                <ul class="pagination text-center">
                    <?php
                    // Previous Page link
                    if($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?page='.($page - 1).'">Previous</a></li>';
                    }

                    // Page links
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo '<li class="page-item '.($page == $i ? 'active' : '').'"><a class="page-link" href="?page='.$i.'">'.$i.'</a></li>';
                    }

                    // Next Page link
                    if($page < $total_pages) {
                        echo '<li class="page-item"><a class="page-link" href="?page='.($page + 1).'">Next</a></li>';
                    }
                ?>
                </ul>
            </nav>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
