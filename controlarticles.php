<?php 
    session_start();
    if(!isset($_SESSION['username'])){
      header('location:login.php');
    }

    include_once 'includes/db_connection.php';
    $sqlQuery = "SELECT * FROM table_content;";
    $result = mysqli_query($conn, $sqlQuery);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Control Panel</title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/style_panel.css" type="text/css" media="all" />
<style>
  html {
  scroll-behavior: smooth;
  }
  .custom-file-input {
  color: transparent;
  }
  .custom-file-input::-webkit-file-upload-button {
    visibility: hidden;
  }
  .custom-file-input::before {
    content: 'Choose file...';
    color: black;
    display: inline-block;
    background: -webkit-linear-gradient(top, #f9f9f9, #e3e3e3);
    border: 1px solid #999;
    border-radius: 3px;
    padding: 5px 8px;
    outline: none;
    white-space: nowrap;
    -webkit-user-select: none;
    cursor: pointer;
    text-shadow: 1px 1px #fff;
    font-weight: 700;
    font-size: 10pt;
  }
  .custom-file-input:hover::before {
    border-color: black;
  }
  .custom-file-input:active {
    outline: 0;
  }
  .custom-file-input:active::before {
    background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9); 
}
</style>
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
        <li><a href="controlarticles.php" class="active"><span>Articles</span></a></li>
        <li><a href="controlcomments.php"><span>Comments</span></a></li>
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
            <h2 class="left">Articles</h2>
          </div>
          <!-- End Box Head -->
          <!-- Table -->
          <div class="table">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <th colspan="2">Title</th>
                <th>Content</th>
                <th>Date</th>
                <th width="110" class="ac">Content Control</th>
              </tr>
            <?php
              $boolean = 1;
              while ($rows = mysqli_fetch_assoc($result))
              {
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
                <td colspan="2"><h3><?php echo $rows['Title']; ?></h3></td>
                <td ><h3><?php echo substr($rows['Content'], 0, 250) . "..."; ?></h3></td>
                <td><?php echo date("d.m.Y", strtotime(str_replace('-','/', $rows['Date']))); ?></td>
                
                <td>
                  <a href="controlarticles.php?delid=<?php echo $rows['ID']; ?>" class="ico del" name="delete_button">Delete</a>
                  <a href="controlarticles.php?editid=<?php echo $rows['ID']; ?>#edit-section" class="ico edit" name="edit_button">Edit</a>
                  <?php  
                    if(isset($_GET['delid']))
                    {
                      if($_GET['delid'] == $rows['ID']) {
                  ?>
                        <br><br>
                        <p>Are you sure you want to delete this article?</p>
                        <br>
                        <button style="padding: 4px; background-color: #ccc;" onclick="location.href='controlarticles.php'" type="button">Cancel</button>
                        <button style="padding: 4px; background-color: #f44336;" onclick="location.href='controlarticles.php?del=<?php echo $rows['ID']; ?>'" type="button">Delete</button>
                  <?php
                      }
                    }
                    if(isset($_GET['del']))
                    {
                      $content_id = $_GET['del'];
                      $sqlDeleteQuery = "DELETE FROM table_content WHERE id='$content_id'";
                      mysqli_query($conn, $sqlDeleteQuery);
                      echo "<script>window.location = 'controlarticles.php';</script>";
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
        <?php
        if(!isset($_GET['editid']))
        {
          
          echo "
          <!-- Box -->
          <div class='box' id='edit-section' style='width: 131%'>
            <!-- Box Head -->
            <div class='box-head'>
              <h2>Add New Article</h2>
            </div>
            <!-- End Box Head -->
            <form action='' method='POST' enctype='multipart/form-data'>
              <!-- Form -->
              <div class='form'>
                <p> <span class='req'>max 100 symbols</span>
                  <label>Article Title <span>(Required Field)</span></label>
                  <input type='text' class='field size1' name='articletitle' style='width: 98%' />
                </p>
                <label>Image <span>(Required Field)</span></label>
                <input type='file' name='file' id='file' class='custom-file-input'> </p>
                <p> <span class='req'>max 5000 symbols</span>
                  <label>Content <span>(Required Field)</span></label>
                  <textarea class='field size1' rows='10' cols='30' name='articlecontent' style='width: 98%; overflow-y: scroll;'></textarea>
                </p>
              </div>
              <!-- End Form -->
              <!-- Form Buttons -->
              <div class='buttons'>
                <input type='submit' class='button' value='Submit'  name='addarticle' style='padding: 10px' />
              </div>
              <!-- End Form Buttons -->
            </form>
          </div>
          <!-- End Box -->";
        } else {
          
          $edit_id = $_GET['editid'];
          $sqlEditQuery = "SELECT * FROM table_content WHERE ID = '$edit_id';";
          $resultEdit = mysqli_query($conn, $sqlEditQuery);
          $resultEditArray = mysqli_fetch_array($resultEdit);

          echo "
          <!-- Box -->
          <div class='box' id='edit-section' style='width: 131%'>
            <!-- Box Head -->
            <div class='box-head'>
              <h2>Edit Article</h2>
            </div>
            <!-- End Box Head -->
            <form action='' method='POST' enctype='multipart/form-data'>
              <!-- Form -->
              <div class='form'>
                <p> <span class='req'>max 100 symbols</span>
                  <label>Article Title <span>(Required Field)</span></label>
                  <input type='text' class='field size1' name='articletitle' value='".$resultEditArray[1]."' style='width: 98%' />
                </p>
                <label>Image <span>(Required Field)</span></label>
                <input type='file' name='file' id='file' class='custom-file-input'> </p>
                <p> <span class='req'>max 5000 symbols</span>
                  <label>Content <span>(Required Field)</span></label>
                  <textarea class='field size1' rows='10' cols='30' name='articlecontent' style='width: 98%; overflow-y: scroll;'>" . $resultEditArray[2] . "</textarea>
                </p>
              </div>
              <!-- End Form -->
              <!-- Form Buttons -->
              <div class='buttons'>
                <input type='submit' class='button' value='Submit'  name='editarticle' style='padding: 10px' />
              </div>
              <!-- End Form Buttons -->
            </form>
          </div>
          <!-- End Box -->";
        }
        ?>
      </div>
      <!-- End Content -->
      <div class="cl">&nbsp;</div>
    </div>
    <!-- Main -->
  </div>
</div>
<!-- End Container -->
<!-- Footer -->
<?php 
  if(isset($_POST['addarticle']))
  {
    $title = $_POST['articletitle'];
    $content = $_POST['articlecontent'];

    $name = $_FILES["file"]["name"];
    $tmp_name = $_FILES['file']['tmp_name'];
    $location = 'img/';
    move_uploaded_file($tmp_name, $location.$name);

    $sqlInsertQuery = "INSERT INTO table_content (Title, Content, Image) VALUES ('$title', '$content', '$name')";
    mysqli_query($conn, $sqlInsertQuery);
    echo "<script>window.location = 'controlarticles.php';</script>";
  }
  if(isset($_POST['editarticle']))
  {
    $editid = $_GET['editid'];
    $title = $_POST['articletitle'];
    $content = $_POST['articlecontent'];

    if(empty($_FILES["file"]["name"]))
    {
      $sqlUpdateQuery = "UPDATE table_content SET Title='$title', Content='$content' WHERE ID='$editid'";
      mysqli_query($conn, $sqlUpdateQuery);
    } else {
      $imname = $_FILES["file"]["name"];
      $tmp_name = $_FILES['file']['tmp_name'];
      $location = 'img/';
      move_uploaded_file($tmp_name, $location.$imname);

      $sqlUpdateQuery = "UPDATE table_content SET Title='$title',Content='$content',Image='$imname' WHERE ID='$editid'";
      mysqli_query($conn, $sqlUpdateQuery);
    }
    echo "<script>window.location = 'controlarticles.php';</script>";
  }
?>
<div id="footer">
  <div class="shell"> <span class="left">&copy; 2020 - Personal Blog</span> <span class="right">My Website 2020</span> </div>
</div>
<!-- End Footer -->
</body>
</html>
