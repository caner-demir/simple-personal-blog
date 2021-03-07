<?php 
    include_once 'includes/db_connection.php';
    if(isset($_GET['id']))
    {
        $ID = $_GET['id'];
        $sqlQuery = "SELECT * FROM table_content where ID =" . $ID . ";";
        $result = mysqli_query($conn, $sqlQuery);
        $row = mysqli_fetch_assoc($result);

        $sqlCommentQuery = "SELECT * FROM table_comments where ContentID =" . $ID . " and IsApproved = 1;";
        $resultComments = mysqli_query($conn, $sqlCommentQuery);
    }
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
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/clean-blog.css" rel="stylesheet">
    <link href="css/post-comment-section.css" rel="stylesheet">
    <link href="css/post-rate-section.css" rel="stylesheet">

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
<style>
body {
    background-color: #eee;
}
</style>
</head>

<body>

    <!-- Navigation -->
    <nav id="navigation-bar"></nav>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <?php 
        if(!empty($row['Image'])){
            echo "<header class='intro-header' style='filter: grayscale(60%); background-image: url(img/". $row['Image'] .")'>";
        } else {
            echo "<header class='intro-header' style='background-image: url(". "img/post-bg.jpg" .")'>";
        }
    ?>
    
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="post-heading">
                        <h1><?php echo $row['Title']; ?></h1>
                        <span class="meta">Posted on <?php echo date("F j, Y", strtotime(str_replace('-','/', $row['Date']))); ?>
                        </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Post Content -->
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1" style="text-align: justify;">
                    <p> 
                    <?php 
                        $strings = explode("\n", nl2br($row['Content']));
                        foreach ($strings as $index => $element) {
                            if ($index == 4) {
                                echo "<img src=img/" . $row['Image'] . " style='width: 100%; height: auto; display: block; margin-left: auto; margin-right: auto;'" . $element;
                                //echo "<span class='caption text-muted'>Image text here</span>";
                            } else {
                                echo $element;
                            }
                         } 
                    ?>
                    </p>
                </div>
            </div>
        </div>
    </article>

    <hr style="height:2px;border-width:0; color:gray; background-color:gray; width:800px; margin-top: -25px">
    <div class="container" style="width: 900px; margin-top: -25px">
        <div class="be-comment-block" style="border-color: #eee">
            <?php 
                $sqlCountQuery = "SELECT Count(*) FROM table_comments where ContentID =" . $ID . " and IsApproved = 1;";
                $resultCount = mysqli_query($conn, $sqlCountQuery);
                $commentCount = mysqli_fetch_array($resultCount);
            ?>
            <h1 class="comments-title">Comments (<?php echo $commentCount[0]; ?>)</h1>
        <?php
            while ($commentRows = mysqli_fetch_assoc($resultComments))
            {
        ?>
            <div class="be-comment">
                <div class="be-img-comment">    
                <?php
                    echo "<img src='https://bootdey.com/img/Content/avatar/avatar" . rand(1, 8) . ".png' alt='' class='be-ava-comment'>";
                ?>
                </div>
                <div class="be-comment-content">
                    
                        <span class="be-comment-name">
                            <?php echo $commentRows['Name']; ?>
                        </span>
                        <span class="be-comment-time">
                            <i class="fa fa-clock-o"></i>
                            
                            <?php echo date("F j, Y H:i", strtotime(str_replace('-','/', $commentRows['Date']))); ?>
                        </span>

                    <p class="be-comment-text">
                        <?php echo $commentRows['Comment']; ?>
                    </p>
                </div>
            </div>
        <?php
            }
        ?>
            <form class="form-block" method="post">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group fl_icon">
                            <div class="icon"><i class="fa fa-user"></i></div>
                            <input class="form-input" type="text" required="" placeholder="Your name" name="name_section"  
                            style="border-width: 1px; border-color: #000">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 fl_icon">
                        <div class="form-group fl_icon">
                            <div class="icon"><i class="fa fa-envelope-o"></i></div>
                            <input class="form-input" type="text" required="" placeholder="Your email" name="email_section" 
                            style="border-width: 1px; border-color: #000">
                        </div>
                    </div>
                    <div class="col-xs-12">                                 
                        <div class="form-group">
                            <textarea class="form-input" required="" placeholder="Your text" name="comment_section" 
                            style="border-width: 1px; border-color: #000"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <span class="field-label-header" style="margin-left: 15px; font-size: 110%;">Your Rating:</span><br>
                            <div class="stars" style="position: absolute; top: -20px; left: 150px">
                                    <input class="star star-5" id="star-5" type="radio" name="star" value="5" /> 
                                        <label class="star star-5" for="star-5"></label> 
                                    <input class="star star-4" id="star-4" type="radio" name="star" value="4" /> 
                                        <label class="star star-4" for="star-4"></label> 
                                    <input class="star star-3" id="star-3" type="radio" name="star" value="3" /> 
                                        <label class="star star-3" for="star-3"></label> 
                                    <input class="star star-2" id="star-2" type="radio" name="star" value="2" /> 
                                        <label class="star star-2" for="star-2"></label> 
                                    <input class="star star-1" id="star-1" type="radio" name="star" value="1" /> 
                                        <label class="star star-1" for="star-1"></label>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary pull-right" name="comment_botton">
                    <?php  
                        if(isset($_POST['comment_botton']))
                        {
                            $sender_name = $_POST['name_section'];
                            $sender_email = $_POST['email_section'];
                            $sender_comment = $_POST['comment_section'];
                            $sender_rating = $_POST['star'];

                            $sqlInsertQuery = "INSERT INTO table_comments (ContentID, Name, Email, Comment, Rating) VALUES
                             ('$ID', '$sender_name', '$sender_email','$sender_comment' , '$sender_rating')";

                            mysqli_query($conn, $sqlInsertQuery);
                        }
                    ?>
                </div>
            </form>
        </div>
    </div>
    <hr style="height:1px; border-width:0; color:#000; background-color:#000; margin-top: -20px">

    <!-- Footer -->
    <footer style="padding: 15px">
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
