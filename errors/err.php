<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?php echo $_GET['err'] ?></title>
    <!-- stylesheet -->
    <link rel="stylesheet" href="style.css">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <style>
        .container {
            align-items: center;
            padding-top: 135px;
        }

        .text {
            /* width: 30vw; */
            padding-top: 150px;
            text-align: center;
        }

        .image {
            margin-left: 153px;
            /* width: 40vw; */
            text-align: center;
            animation: MoveUpDown 2s ease-in-out infinite alternate-reverse both;

            @keyframes moveupdown {
                0% {
                    transform: translateY(-10px);
                }

                100% {
                    transform: translateY(10px);
                }

            }

        }

        p {
            line-height: 1.5rem;
            font-size: larger;
        }
    </style>
</head>


<body>
    <div class="container">
        <div class="row">
            <div class="text col">
                <?php
                $errorCode = htmlspecialchars($_GET['err']);
                switch ($errorCode) {
                    case '403':
                        $errorMessage = 'Forbidden: You do not have permission to access this resource.';
                        break;
                    case '404':
                        $errorMessage = 'Not Found: The requested resource could not be found.';
                        break;
                    case '500':
                        $errorMessage = 'Internal Server Error: An unexpected condition was encountered while the server was attempting to fulfill the request.';
                        break;
                    default:
                        $errorMessage = 'An unknown error has occurred.';
                        break;
                }
                ?>
                <h1>Error <?php echo $errorCode; ?></h1>
                <div class="mx-auto">
                    <img class="image d-block mx-auto" height="100" src="./err.png" alt="Error image">
                </div>
                <p><?php echo $errorMessage; ?></p>
                <a href="../login/login.php"><button type="button" class="btn btn-lg btn-primary">Go back</button></a>
            </div>
        </div>
    </div>
    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBud7TlRbs/ic4AwGcFZOxg5DpPt8EgeUIgIwzjWfXQKWA3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
</body>

</html>