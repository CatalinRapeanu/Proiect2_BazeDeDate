<?php 
require_once 'connection.php';  

session_start();

if(isset($_COOKIE['username']) && isset($_COOKIE['password']))
{
  $username = $_COOKIE['username'];
  $password = $_COOKIE['password'];
}
else
{
  $username = "";
  $password = "";
}

if(isset($_REQUEST['Login']))
{
  $user = $_REQUEST['username'];
  $pass = $_REQUEST['password'];  

  $session = $client->startSession();
  $session->startTransaction();

  try{
    $filter = ['username' => $user, 'password' => $pass];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $client->executeQuery("proiect.conturi", $query); 
    
    $res = 0;
    foreach($cursor as $document){
      $res++;
      $data = $document;
    }

    // $cursor->rewind(); 

    if($res>0)
    {  
      $name = $data->username;
      $admin = $data->user_type;

      $_SESSION['username'] = $name; 
      $_SESSION['ADMIN']=$admin;

      if(isset($_REQUEST['rememberme']))
      {
        setcookie('username',$_REQUEST['username'],time()+60*60);
        setcookie('password',$_REQUEST['password'],time()+60*60); 

      }
      else
      {
        setcookie('username',$_REQUEST['username'],time()-60*60);
        setcookie('password',$_REQUEST['password'],time()-60*60); 
      }
      header('location:index.php');
    }
    else
    {
      $msg = "Introduceti date valide.";
    }

    $session->commitTransaction();
  }catch(Exception $e){
    $session->abortTransaction();
    echo "<script>alert('Tranzactia nu a avut loc!')</script>";
    echo "<script>alert('".$e->getMessage()."')</script>";
  }

  $session->endSession();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>User Login</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-dark">
            <div class="container px-4 px-lg-5">
                <a class="navbar-brand" style="color: white;" href="index.php">Shooters</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link" style="color: white;" aria-current="page" href="index.php">Acasa</a></li>
                        <li class="nav-item"><a class="nav-link" style="color: white;" href="about.php">Despre noi</a></li>
                        <li class="nav-item dropdown" >
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">Bauturi</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="produse.php">Toate bauturile</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="populare.php">Shot-uri</a></li>
                                <li><a class="dropdown-item" href="noi.php">Cocktail-uri</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
        </nav>
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Login</h1>
                    <form method="post">
                            <input type="text" name="username" placeholder=" Username" class="form-control mb-3" style="width: 50%; margin-left: auto; margin-right: auto;">
                            <input type="password" name="password" placeholder="Password" class="form-control mb-3" style="width: 50%; margin-left: auto; margin-right: auto;">
                            <label><input type="checkbox" name="rememberme" value="1">Remember Me</label><br/>
                            <button class="btn btn-success mt-3" name="Login">Login</button>
                    </form>
                    <p>Ce frate n-ai cont? Ia apasa <a href="registerform.php">aici</a>.</p>
                </div>
            </div>
        </header>
        
        <div class="bg-dark"><br/><br/><br/><br/><br/></div>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
