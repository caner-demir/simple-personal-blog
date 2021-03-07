<?php 
    include_once 'includes/db_connection.php';
    //$sql_query = "SELECT * FROM table_content;";
    //$result = mysqli_query($conn, $sql_query);


    //define total number of results you want per page  
    $results_per_page = 4;  

    //find the total number of results stored in the database
    $query_nums = "SELECT *  FROM table_content";    
    $result_nums = mysqli_query($conn, $query_nums);  
    $number_of_result = mysqli_num_rows($result_nums);  

    //determine the total number of pages available  
    $number_of_page = ceil ($number_of_result / $results_per_page);  

    //determine which page number visitor is currently on  
    if (!isset ($_GET['page']) ) {  
        $page = 1;  
    } else {  
        $page = $_GET['page'];  
    }  

    //determine the sql LIMIT starting number for the results on the displaying page  
    $page_first_result = ($page-1) * $results_per_page;  

    //retrieve the selected results from database   
    $query = "SELECT *FROM table_content ORDER BY Date DESC LIMIT " . $page_first_result . ',' . $results_per_page;  
    $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Personal Blog</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/clean-blog.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.checked {
  color: orange;
}
body {
    background-color: #eee;
}
</style>


    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <!-- Navigation -->
    <nav id="navigation-bar"></nav>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('img/post-bg.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>Personal Blog</h1>
                        <hr class="small">
                        <span class="subheading">Programming and Technology</span>

                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            <?php
                while ($rows = mysqli_fetch_assoc($result))
                {
            ?>
                <div class="post-preview">
                    <a href="post.php?id=<?php echo $rows['ID']; ?>">
                    
                        <h2 class="post-title">
                            <?php echo $rows['Title'];?>
                        </h2>
                        <br>
                        <img src=" 
                        <?php 
                            if(!empty($rows['Image'])) {
                                echo "img/" . $rows['Image']; 
                            } else {
                                echo "img/home-bg.jpg"; 
                            }
                        ?>
                        " style="width: 100%; height: 200px; display: block; margin-left: auto; margin-right: auto; 
                        object-fit: cover; cursor: pointer;">                        
                        <br>                    
                        <h3 class="post-subtitle" style="text-align: justify;">
                            <?php echo substr($rows['Content'], 0, 250) . "..."; ?>
                        </h3>
                    </a>
                    <p class="post-meta">Posted on 
                        <?php echo date("F j, Y", strtotime(str_replace('-','/', $rows['Date']))); ?>
                        <?php echo " - User Rating: ";
                            for ($x = 0; $x < floor($rows['Rating']); $x++) {
                                echo "<span class='fa fa-star checked'></span>";
                            }
                            for ($x = 0; $x < 5 - $rows['Rating']; $x++) {
                                echo "<span class='fa fa-star'></span>";
                            }
                            echo " " . number_format((float)$rows['Rating'], 2, '.', '');
                        ?>
                    </p>
                </div>
                <hr style="height:1px;border-width:0; color:gray; background-color:gray; width:750px">
            <?php
                }
            ?>
                    
                <!-- Pager -->
                <ul class="pager">
                    <li class="next">
                        <?php 
                        if ($page < $number_of_page) {
                            echo '<a href="index.php?page=' . intval($page+1) . '">Older Posts &rarr;</a>';
                        }

                        if ($page > 1) {
                            echo '<a href="index.php?page=' . intval($page-1) . '">&larr; Newer Posts</a>';
                        }
                        ?>
                        
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <hr style="height:1px; border-width:0; color:#000; background-color:#000;">

    <!-- Footer -->
    <footer style="padding: 30px">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <ul class="list-inline text-center">
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-github fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="copyright text-muted">Copyright &copy; My Website 2020</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <!-- Theme JavaScript -->
    <script src="js/clean-blog.min.js"></script>

    <!-- Load Navigation Bar -->
    <script src="js/loadNavs.js"></script>

</body>

</html>
