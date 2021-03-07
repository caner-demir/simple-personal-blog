<?php 
    $name = $_POST['user'];
    $password = $_POST['password'];

    include_once 'includes/db_connection.php';
    $sqlLoginQuery = "SELECT * FROM table_users WHERE name = '$name' AND password = '$password';";
    $resultLogin = mysqli_query($conn, $sqlLoginQuery);

    $num = mysqli_num_rows($resultLogin);

    if($num == 1){
      session_start();

      $_SESSION['username'] = $name;
      header('location:controlarticles.php');
    } else{
      header('location:login.php');
    }
?>