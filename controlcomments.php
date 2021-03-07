<?php 
    session_start();
    if(!isset($_SESSION['username'])){
      header('location:login.php');
    }

    include_once 'includes/db_connection.php';
    $sqlPendingQuery = "SELECT * FROM table_comments WHERE IsApproved = 0;";
    $resultsPending = mysqli_query($conn, $sqlPendingQuery);

    $sqlApprovedQuery = "SELECT * FROM table_comments WHERE IsApproved = 1;";
    $resultsApproved = mysqli_query($conn, $sqlApprovedQuery);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Control Panel</title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/style_panel.css" type="text/css" media="all" />

</head>
<body>
<!-- Header -->
<div id="header">
  <div class="shell">
    <!-- Logo + Top Nav -->
    <div id="top">
      <h1><a href="controlarticles.php">Control Panel</a></h1>
      <div id="top-navigation"> Welcome <strong><?php echo $_SESSION['username']; ?></strong> <span>|</span> 
        <a href="logout.php">Log out</a> </div>
    </div>
    <!-- End Logo + Top Nav -->
    <!-- Main Nav -->
    <div id="navigation">
      <ul>
        <li><a href="controlarticles.php"><span>Articles</span></a></li>
        <li><a href="controlcomments.php" class="active"><span>Comments</span></a></li>
      </ul>
    </div>
    <!-- End Main Nav -->
  </div>
</div>
<!-- End Header -->
<!-- Container -->
<div id="container">
  <div class="shell">
    <br />
    <!-- Main -->
    <div id="main">
      <div class="cl">&nbsp;</div>
      <!-- Content -->
      <div id="content">
        <!-- Box -->
        <div class="box" style="width: 131%">
          <!-- Box Head -->
          <div class="box-head">
            <h2 class="left">Comments Pending Approval</h2>
          </div>
          <!-- End Box Head -->
          <!-- Table -->
          <div class="table">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <th width="45%">Comment</th>
                <th>Article</th>
                <th>Date</th>
                <th>Sent by</th>
                <th width="120" class="ac">Content Control</th>
              </tr>
            <?php
              $boolean = 1;
              while ($rows = mysqli_fetch_assoc($resultsPending))
              {
                $idtemp = $rows['ContentID'];
                $sqlArticleQuery = "SELECT * FROM table_content WHERE ID = '$idtemp';";
                $resultArticle = mysqli_query($conn, $sqlArticleQuery);
                $outputArticle = mysqli_fetch_array($resultArticle);
            ?>
              <tr 
            <?php
              if ($boolean) {
                echo "class='odd'";
                $boolean = !$boolean;
              } else {
                $boolean = !$boolean;
              }
            ?>
              >
                <td><h3><?php echo $rows['Comment']; ?></h3></td>
                <td><h3><?php echo $outputArticle[1]; ?></h3></td>
                <td><?php echo date("d.m.Y", strtotime(str_replace('-','/', $rows['Date']))); ?></td>
                <td width="120"><h3><?php echo $rows['Name']; ?></h3></td>
                <td>
                  <a href='controlcomments.php?delid=<?php echo $rows['ID']; ?>' class="ico del" name="delete_button">Delete</a>
                  <a href='controlcomments.php?approveid=<?php echo $rows['ID']; ?>' class="ico edit" name="edit_button">Approve</a>
                  <?php  
                    if(isset($_GET['delid']))
                    {
                      if($_GET['delid'] == $rows['ID']) {
                  ?>
                        <br><br>
                        <p>Are you sure you want to delete this comment?</p>
                        <br>
                        <button style="padding: 4px; background-color: #ccc;" onclick="location.href='controlcomments.php'" type="button">Cancel</button>
                        <button style="padding: 4px; background-color: #f44336;" onclick="location.href='controlcomments.php?del=<?php echo $rows['ID']; ?>'" type="button">Delete</button>
                  <?php
                      }
                    }
                    if(isset($_GET['del']))
                    {
                      $commentid = $_GET['del'];
                      $sqlDeleteQuery = "DELETE FROM table_comments WHERE id='$commentid'";
                      mysqli_query($conn, $sqlDeleteQuery);
                      echo "<script>window.location = 'controlcomments.php';</script>";
                    }
                  ?>
                  <?php  
                    if(isset($_GET['approveid']))
                    {
                      if($_GET['approveid'] == $rows['ID']) {
                  ?>
                        <br><br>
                        <p>Are you sure you want to approve this comment?</p>
                        <br>
                        <button style="padding: 4px; background-color: #ccc;" onclick="location.href='controlcomments.php'" type="button">Cancel</button>
                        <button style="padding: 4px; background-color: forestgreen;" onclick="location.href='controlcomments.php?appr=<?php echo $rows['ID']; ?>'" type="button">Approve</button>
                  <?php
                      }
                    }
                    if(isset($_GET['appr']))
                    {
                      $commentid = $_GET['appr'];
                      $sqlApproveQuery = "UPDATE table_comments SET IsApproved=1 WHERE id='$commentid'";
                      mysqli_query($conn, $sqlApproveQuery);

                      $sqlIDQuery = "SELECT * FROM table_comments WHERE ID = '$commentid';";
                      $resultID = mysqli_query($conn, $sqlIDQuery);
                      $outputID = mysqli_fetch_array($resultID);

                      $sqlRatingQuery = "SELECT AVG(Rating) FROM table_comments WHERE ContentID='$outputID[1]' AND IsApproved=1";
                      $resultRating = mysqli_query($conn, $sqlRatingQuery);
                      $outputRating = mysqli_fetch_array($resultRating);
                      echo $outputRating[0];

                      $sqlUpdateRating = "UPDATE table_content SET Rating='$outputRating[0]' WHERE ID='$outputID[1]'";
                      $resultUpdate = mysqli_query($conn, $sqlUpdateRating);
                      echo "<script>window.location = 'controlcomments.php';</script>";
                    }
                  ?>
                </td>
              </tr>
            <?php
              }
            ?>
            </table>
          </div>
          <!-- Table -->
        </div>
        <!-- End Box -->
        <!-- Box -->
        <div class="box" style="width: 131%">
          <!-- Box Head -->
          <div class="box-head">
            <h2 class="left">Approved Comments</h2>
          </div>
          <!-- End Box Head -->
          <!-- Table -->
          <div class="table">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <th width="45%">Comment</th>
                <th>Article</th>
                <th>Date</th>
                <th>Sent by</th>
                <th width="110" class="ac">Content Control</th>
              </tr>
            <?php
              $boolean = 1;
              while ($rows = mysqli_fetch_assoc($resultsApproved))
              {
                $tempid = $rows['ContentID'];
                $sqlArticleQuery = "SELECT * FROM table_content WHERE ID = '$tempid';";
                $resultArticle = mysqli_query($conn, $sqlArticleQuery);
                $outputArticle = mysqli_fetch_array($resultArticle);
            ?>
              <tr 
            <?php
              if ($boolean) {
                echo "class='odd'";
                $boolean = !$boolean;
              } else {
                $boolean = !$boolean;
              }
            ?>
              >
                <td><h3><?php echo $rows['Comment']; ?></h3></td>
                <td><h3><?php echo $outputArticle[1]; ?></h3></td>
                <td><?php echo date("d.m.Y", strtotime(str_replace('-','/', $rows['Date']))); ?></td>
                <td width="120"><h3><?php echo $rows['Name']; ?></h3></td>
                <td>
                  <a style="margin-left: 30px" href='controlcomments.php?delid=<?php echo $rows['ID']; ?>' class="ico del" name="delete_button">Delete</a>
                  <?php  
                    if(isset($_GET['delid']))
                    {
                      if($_GET['delid'] == $rows['ID']) {
                  ?>
                        <br><br>
                        <p>Are you sure you want to delete this article?</p>
                        <br>
                        <button style="padding: 4px; background-color: #ccc;" onclick="location.href='controlcomments.php'" type="button">Cancel</button>
                        <button style="padding: 4px; background-color: #f44336;" onclick="location.href='controlcomments.php?del=<?php echo $rows['ID']; ?>'" type="button">Delete</button>
                  <?php
                      }
                    }
                    if(isset($_GET['del']))
                    {
                      $commentid = $_GET['del'];
                      $sqlDeleteQuery = "DELETE FROM table_content WHERE id='$commentid'";
                      mysqli_query($conn, $sqlDeleteQuery);
                      echo "<script>window.location = 'admin_articles.php';</script>";
                    }
                  ?>
                </td>
              </tr>
            <?php
              }
            ?>
            </table>
          </div>
          <!-- Table -->
        </div>
        <!-- End Box -->
      </div>
      <!-- End Content -->
      <div class="cl">&nbsp;</div>
    </div>
    <!-- Main -->
  </div>
</div>
<!-- End Container -->
<!-- Footer -->
<div id="footer">
  <div class="shell"> <span class="left">&copy; 2020 - Personal Blog</span> <span class="right">My Website 2020</span> </div>
</div>
<!-- End Footer -->
</body>
</html>
