<?php
  require($_SERVER['DOCUMENT_ROOT']."/dbconnect.php");
  if(empty($_SESSION['admin'])){
    header('location:index.php');
  }
  if(isset($_POST['logout'])){
    session_destroy();
    header('location:/admin/index.php');
  }
  if(isset($_POST['change_pwd'])){
    $new = mysqli_real_escape_string($db, $_POST['newpwd']);
    $cnew = mysqli_real_escape_string($db, $_POST['cnewpwd']);
    if($new == $cnew){
      $adn = $_SESSION['admin_name'];
      $pwd_q = "UPDATE admin SET password='$new' WHERE admin_name = '$adn';";
      mysqli_query($db,$pwd_q);
    }
  }

  
  $_SESSION['page'] = 'services';

  $tag_qu = "SELECT * from tags WHERE tag_page='philosophy';";
  $restag = mysqli_query($db, $tag_qu);
  $rowtag = mysqli_fetch_assoc($restag);
  $tagnames = $rowtag['tag_names'];
  $taglist = explode(';',$tagnames);

  if(isset($_POST['philosophy_section_add'])){
    $sec = mysqli_real_escape_string($db, $_POST['philosophy_tag_new']);
    array_push($taglist,$sec);
    $tagstr = join(";",$taglist);
    $tag_update = "UPDATE tags SET tag_names='$tagstr' WHERE tag_page='philosophy';";
    mysqli_query($db, $tag_update);
    echo "<script>alert('Added');</script>";
  }
  if(isset($_POST['philosophy_section_del'])){
    for($i=0;$i<count($taglist);$i++){
      if(isset($_POST['philosophy_deltag_'.$i])){
        $t = $_POST['philosophy_deltag_'.$i];
        $rt = "UPDATE aphilosophy SET philosophy_tag='None' WHERE philosophy_tag = '$t';";
        mysqli_query($db, $rt);
        if (($key = array_search($_POST['philosophy_deltag_'.$i], $taglist)) !== false) {
            unset($taglist[$key]);
        }
        $tagstr = join(";",$taglist);
        $tag_update = "UPDATE tags SET tag_names='$tagstr' WHERE tag_page='philosophy';";
        mysqli_query($db, $tag_update);
        echo "<script>alert('Removed');</script>";
        header('location: ayurveda.php');
      }
    }
  }

  if(isset($_POST['add_philosophy'])){
    $n = mysqli_real_escape_string($db, $_POST['philosophy_name_add']);
    $pri = mysqli_real_escape_string($db, $_POST['philosophy_priority_add']);
    $si = mysqli_real_escape_string($db, $_POST['philosophy_sintro_add']);
    $li = mysqli_real_escape_string($db, $_POST['philosophy_lintro_add']);
    $h = mysqli_real_escape_string($db, $_POST['philosophy_hours_add']);
    $c = mysqli_real_escape_string($db, $_POST['philosophy_cost_add']);
    $con = mysqli_real_escape_string($db, $_POST['philosophy_contact_add']);
    $imgs = $_POST['philosophy_img_add'];
    $pdfs = $_POST['philosophy_pdf_add'];
    $xtag = $_POST['philosophy_tag_add'];
    $add_q = "INSERT INTO philosophy(philosophy_name, philosophy_priority, philosophy_shortintro, philosophy_longintro, philosophy_hours, philosophy_cost, philosophy_tag, philosophy_intro_image, philosophy_contact, philosophy_pdf) VALUES ('$n','$pri','$si','$li','$h','$c','$xtag','$imgs','$con','$pdfs')";
    mysqli_query($db, $add_q);
    echo "<script>alert('Added');</script>";
  }

  $servq = "SELECT count(*) FROM philosophy;";
  $rserv = mysqli_query($db, $servq);
  $trow = mysqli_fetch_assoc($rserv);
  $cnt = $trow['count(*)'];
  for($i=0;$i<$cnt;$i++){

    if(isset($_POST['philosophy_update_'.$i])){
        $id = mysqli_real_escape_string($db, $_POST['philosophy_id_'.$i]);
        $n = mysqli_real_escape_string($db, $_POST['philosophy_name_'.$i]);
        $pri = mysqli_real_escape_string($db, $_POST['philosophy_priority_'.$i]);
        $si = mysqli_real_escape_string($db, $_POST['philosophy_sintro_'.$i]);
        $li = mysqli_real_escape_string($db, $_POST['philosophy_lintro_'.$i]);
        $h = mysqli_real_escape_string($db, $_POST['philosophy_hours_'.$i]);
        $c = mysqli_real_escape_string($db, $_POST['philosophy_cost_'.$i]);
        $con = mysqli_real_escape_string($db, $_POST['philosophy_contact_'.$i]);
        $imgs = $_POST['philosophy_img_'.$i];
        $pdfs = $_POST['philosophy_pdf_'.$i];
        $xtag = $_POST['philosophy_tag_'.$i];
        $update_q = "UPDATE philosophy SET philosophy_name='$n',philosophy_priority='$pri',philosophy_shortintro='$si',philosophy_longintro='$li',philosophy_hours='$h',philosophy_cost='$c',philosophy_tag='$xtag',philosophy_intro_image='$imgs',philosophy_contact='$con',philosophy_pdf='$pdfs' WHERE philosophy_id = '$id'";
        mysqli_query($db, $update_q);
        echo "<script>alert('Updated');</script>";
      }
    if(isset($_POST['philosophy_delete_'.$i])){
        $id = mysqli_real_escape_string($db, $_POST['philosophy_id_'.$i]);
        $delete_q = "DELETE FROM philosophy WHERE philosophy_id = '$id';";
        mysqli_query($db, $delete_q);
        echo "<script>alert('Deleted');</script>";
      }
  }

  if(isset($_POST['philosophy_bg'])){
      $philosophybg = mysqli_real_escape_string($db, $_POST['philosophybg']);
      $update_bg = "UPDATE background SET bg_img='$philosophybg' WHERE bg_page = 'philosophy';";
      mysqli_query($db, $update_bg);
      echo "<script>alert('Updated');</script>";
    }

    if(isset($_POST['philosophy_description'])){
        $description = mysqli_real_escape_string($db, $_POST['description']);
        $update_desc = "UPDATE background SET bg_description='$description' WHERE bg_page = 'philosophy';";
        mysqli_query($db, $update_desc);
        echo "<script>alert('Updated');</script>";
      }


?>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

<?php

  $queryl = "SELECT * FROM home WHERE home_tag='0'";
  $resultsl = mysqli_query($db, $queryl);
  $rowl = mysqli_fetch_assoc($resultsl);
  $img_idlogos = $rowl['home_image'];
  $querylogos = "SELECT * FROM images WHERE image_id='$img_idlogos'";
  $rlogos = mysqli_query($db, $querylogos);
  $imglogos = mysqli_fetch_assoc($rlogos);

 ?>

<!-- Favicons -->
<link href="<?=$imglogos['image_path']?>" rel="icon">  <title>Admin - YogaAyurveda</title>

  <!-- Custom fonts for this template-->
  <link href="/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="/assets/css/sb-admin-2.min.css" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
  <?php include($_SERVER['DOCUMENT_ROOT']."/admin/include/sidebar.php")?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php include($_SERVER['DOCUMENT_ROOT']."/admin/include/navbar.php")?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h5 class="h5 mb-0 text-gray-800 text-capitalize">philosophy Background</h5>
          </div>

          <?php
            $bg_q = "SELECT * FROM background WHERE bg_page = 'philosophy'";
            $bg_res = mysqli_query($db, $bg_q);
            $bg = mysqli_fetch_assoc($bg_res);
            $ch_id = $bg['bg_img'];
            $cimg_qu = "SELECT * FROM images WHERE image_id = '$ch_id'";
            $cresimg = mysqli_query($db, $cimg_qu);
            $checkrow = mysqli_fetch_assoc($cresimg);
            $checked_img = $checkrow['image_path'];
            $qimg = "SELECT * FROM images ORDER BY image_id DESC";
            $resimg = mysqli_query($db, $qimg);
           ?>
        <div class="row">
            <div class="col-lg-11">
              <form action="philosophy.php" method="post">
            <div class="card shadow mb-4">
              <!-- Card Header - Accordion -->
              <a href="#collapseBg" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                <h6 class="m-0 font-weight-bold text-primary text-uppercase text-uppercase">Select Background Image</h6>
              </a>
              <!-- Card Content - Collapse -->
              <div class="collapse" id="collapseBg">
                <div class="img-card-body" style="height:25vh; overflow-x: hidden; overflow-y:auto;">
                  <?php
                  echo '&nbsp&nbsp<label>&nbsp&nbsp<input class="options" type="radio" checked="checked" name="philosophybg" value="'.$bg['bg_img'].'" required>&nbsp&nbsp<img src="'.$checked_img.'" alt="" style="width:200px"></label>';
                  while($rowimg = mysqli_fetch_assoc($resimg)){
                    $img = $rowimg['image_path'];
                    if( $bg['bg_img'] != $rowimg['image_id']){
                        echo '&nbsp&nbsp<label>&nbsp&nbsp<input class="options" type="radio" name="philosophybg" value="'.$rowimg['image_id'].'" required>&nbsp&nbsp<img src="'.$img.'" alt="" style="width:200px"></label>';
                    }

                  }
                   ?>
                </div>
              </div>
            </div>
            </div>
            <div class="col-lg-1">
            <button class="btn btn-primary mb-3" type="submit" name = 'philosophy_bg'>
              Update
            </button>
          </form>
          </div>
      </div>

      <div class="row">
          <div class="col-lg-12">
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary text-uppercase">TAGLINE</h6>
              </div>
              <div class="card-body">
                <form action="philosophy.php" method="post">
                  <div class="d-flex justify-content-around">
                      <textarea name="description" class="form-control bg-light border-0 small" style="width:80%;height:8vh"> <?=$bg['bg_description']?> </textarea>
                      <button class="btn btn-primary" type="submit" name="philosophy_description">
                        Update
                      </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h5 class="h5 mb-0 text-gray-800 text-capitalize">Add/Delete Section In philosophy</h5>
          </div>

          <div class="row">
              <div class="col-lg-12">
                <div class="card shadow mb-4">
                  <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary text-uppercase">SECTIONS in philosophy</h6>
                  </div>
                  <div class="card-body">
                    <div class="d-flex mb-3" style="align-items:center;">
                      <?php
                      for($i=0;$i<count($taglist);$i++){
                        if($taglist[$i] != "None"){
                        echo '<label class="text-uppercase" style="font-size:15px;">'.$taglist[$i].'&nbsp&nbsp&nbsp</label>';
                        }
                      }
                       ?>
                      </div>
                    <form action="philosophy.php" method="post">
                      <div class="d-flex justify-content-around">
                          <input type="text" name="philosophy_tag_new" class="form-control bg-light border-0 small" style="width:80%;" placeholder="Enter Section Name">
                          <button class="btn btn-primary" type="submit" name = 'philosophy_section_add'>
                            Add New Section
                          </button>
                      </div>
                    </form>
                    <form action="philosophy.php" method="post">
                      <div class="d-flex justify-content-center text-uppercase">
                          <?php
                          for($i=0;$i<count($taglist);$i++){
                            if($taglist[$i] != "None"){
                            echo '&nbsp&nbsp<label>&nbsp&nbsp<input class="options" type="checkbox" name="philosophy_deltag_'.$i.'" value="'.$taglist[$i].'">&nbsp&nbsp'.$taglist[$i].'</label>';
                              }
                            }
                           ?>

                      </div>
                      <center><button class="btn btn-primary" type="submit" name = 'philosophy_section_del'>
                        Remove
                      </button></center>
                    </form>
                  </div>
                </div>
              </div>
            </div>


          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h5 class="h5 mb-0 text-gray-800 text-capitalize">philosophy</h5>
          </div>

          <!-- Page Heading -->
          <div class="row">

              <?php
                $q_ser = "SELECT * FROM philosophy ORDER BY philosophy_priority ASC;";
                $res_ser = mysqli_query($db, $q_ser);
                $x = 0;
                while($row_ser = mysqli_fetch_assoc($res_ser)){
               ?>

            <!-- Home Heading -->

                <div class="col-lg-6">
                <div class="card shadow mb-4">
                  <a href="#collapse<?=$row_ser['philosophy_id']?>" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                    <h6 class="m-0 font-weight-bold text-primary text-uppercase text-uppercase"><?=$row_ser['philosophy_name']?></h6>
                  </a>
                  <div class="collapse" id="collapse<?=$row_ser['philosophy_id']?>">
                    <div class="card-body">
                      <form action="philosophy.php" method="post">

                        <div class="d-flex justify-content-around mb-3" style="align-items:center;">
                          <label class="text-uppercase" style="font-size:15px;">philosophy NAME</label>
                            <input type="text" name="philosophy_name_<?=$x?>" class="form-control bg-light border-0 small" style="width:85%;" value="<?=$row_ser['philosophy_name']?>">
                            <input type="text" name="philosophy_id_<?=$x?>" class="form-control bg-light border-0 small" style="width:85%;display:none" value="<?=$row_ser['philosophy_id']?>" required>
                        </div>
                        
                        <div class="d-flex justify-content-center mb-3" style="align-items:center;">
                          <label class="text-uppercase" style="font-size:15px;">philosophy Priority</label>&nbsp;&nbsp;
                            <input type="number" name="philosophy_priority_<?=$x?>" class="form-control bg-light border-0 small" style="width:35%;" value="<?=$row_ser['philosophy_priority']?>" required>
                        </div>

                        <div class="d-flex justify-content-around mb-3">
                          <label class="text-uppercase" style="font-size:15px;">Short Intro</label>
                            <textarea class="form-control bg-light border-0 small" name="philosophy_sintro_<?=$x?>"style="width:85%;height:15vh" ><?=$row_ser['philosophy_shortintro']?></textarea>
                        </div>

                        <div class="d-flex justify-content-around mb-3">
                          <label class="text-uppercase" style="font-size:15px;">Long Intro</label>
                            <textarea class="form-control bg-light border-0 small" name="philosophy_lintro_<?=$x?>"style="width:85%;height:15vh" ><?=$row_ser['philosophy_longintro']?></textarea>
                        </div>
                        <?php
                          $ch_id = $row_ser['philosophy_intro_image'];
                          $cimg_qu = "SELECT * FROM images WHERE image_id = '$ch_id'";
                          $cresimg = mysqli_query($db, $cimg_qu);
                          $checkrow = mysqli_fetch_assoc($cresimg);
                          $checked_img = $checkrow['image_path'];
                          $qimg = "SELECT * FROM images ORDER BY image_id DESC";
                          $resimg = mysqli_query($db, $qimg);
                         ?>
                        <div class="card mb-4">
                          <a href="#collapseintroimg<?=$row_ser['philosophy_id']?>" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                            <h6 class="m-0 font-weight-bold text-primary text-uppercase text-uppercase">Select Image</h6>
                          </a>
                          <div class="collapse" id="collapseintroimg<?=$row_ser['philosophy_id']?>">
                            <div class="img-card-body" style="height:25vh; overflow-x: hidden; overflow-y:auto;">
                              <?php
                              echo '&nbsp&nbsp<label class="text-uppercase">&nbsp&nbsp<input class="options" type="radio" checked="checked" name="philosophy_img_'.$x.'" value="'.$row_ser['philosophy_intro_image'].'" required>&nbsp&nbsp<img src="'.$checked_img.'" alt="" style="width:100px"></label>';
                              while($rowimg = mysqli_fetch_assoc($resimg)){
                                $img = $rowimg['image_path'];
                                if($ch_id != $rowimg['image_id']){
                                    echo '&nbsp&nbsp<label class="text-uppercase">&nbsp&nbsp<input class="options" type="radio" name="philosophy_img_'.$x.'" value="'.$rowimg['image_id'].'" required>&nbsp&nbsp<img src="'.$img.'" alt="" style="width:100px"></label>';
                                }
                              }
                               ?>
                            </div>
                          </div>
                        </div>

                        <div class="d-flex justify-content-around mb-3" style="align-items:center;">
                          <label class="text-uppercase mr-2" style="font-size:15px;">Hours</label>
                            <input type="number" name="philosophy_hours_<?=$x?>" class="form-control bg-light border-0 small" style="width:45%;" value="<?=$row_ser['philosophy_hours']?>">
                            <label class="text-uppercase mr-2" style="font-size:15px;">Cost</label>
                              <input type="number" name="philosophy_cost_<?=$x?>" class="form-control bg-light border-0 small" style="width:45%;" value="<?=$row_ser['philosophy_cost']?>">
                        </div>
                        <div class="d-flex justify-content-around mb-3" style="align-items:center;">
                          <label class="text-uppercase" style="font-size:15px;">philosophy Contact Info</label>
                            <input type="text" name="philosophy_contact_<?=$x?>" class="form-control bg-light border-0 small" style="width:55%;" value="<?=$row_ser['philosophy_contact']?>">
                        </div>


                      <div class="row">
                        <?php
                          $ch_id = $row_ser['philosophy_pdf'];
                          $cimg_qu = "SELECT * FROM pdf WHERE pdf_id = '$ch_id'";
                          $cresimg = mysqli_query($db, $cimg_qu);
                          $checkrow = mysqli_fetch_assoc($cresimg);
                          $xpdfx = $checkrow['pdf_path'];
                          $tar = explode("/",$xpdfx);
                          $checked_pdf = array_slice($tar, -1)[0];
                          $qimg = "SELECT * FROM pdf ORDER BY pdf_id DESC";
                          $resimg = mysqli_query($db, $qimg);
                         ?>
                        <div class="col-lg-6">
                      <div class="d-flex justify-content-around mb-3" style="align-items:center;">
                        <label class="text-uppercase" style="font-size:15px;">Change philosophy document</label>
                          <select class="js-example-basic-single custom-select mr-sm-2" name="philosophy_pdf_<?=$x?>" style="width:85%;height:10vh;" required>
                            <?php
                              echo '<option selected value="'.$ch_id.'">'.$checked_pdf.'</option>';
                              while($rowimg = mysqli_fetch_assoc($resimg)){
                                $pdfx = $rowimg['pdf_path'];
                                $tar = explode("/",$pdfx);
                                $pdf = array_slice($tar, -1)[0];
                                if($ch_id != $rowimg['pdf_id']){
                                    echo '<option value="'.$rowimg['pdf_id'].'">'.$pdf.'</option>';
                                }
                              }
                             ?>

                          </select>
                        </div>

                      </div>

                      <div class="col-lg-6">
                          <div class="d-flex justify-content-around mb-3" style="align-items:center;">
                            <label class="text-uppercase" style="font-size:15px;">Change Section</label>
                              <select class="js-example-basic-single custom-select mr-sm-2" name="philosophy_tag_<?=$x?>" style="width:85%;height:10vh;" required>
                                <?php
                                  $ch_id = $row_ser['philosophy_tag'];
                                  echo '<option selected value="'.$ch_id.'">'.$ch_id.'</option>';
                                  $tag_qu = "SELECT * from tags WHERE tag_page='philosophy';";
                                  $restag = mysqli_query($db, $tag_qu);
                                  $rowtag = mysqli_fetch_assoc($restag);
                                  $tagnames = $rowtag['tag_names'];
                                  $taglist = explode(';',$tagnames);
                                  for($i=0;$i<count($taglist);$i++){
                                    if($taglist[$i] != "None"){
                                        echo '<option value="'.$taglist[$i].'">'.$taglist[$i].'</option>';
                                    }
                                  }

                                 ?>

                              </select>
                            </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-around mb-3 mt-5">
                          <button class="btn btn-primary" type="submit" style="width:100%" name='philosophy_update_<?=$x?>'>
                            Update
                          </button>
                        </div>
                        <div class="d-flex justify-content-around mb-3 mt-2">
                          <button class="btn btn-danger" type="submit" style="width:100%" name='philosophy_delete_<?=$x?>'>
                            Remove
                          </button>
                        </div>

                    </form>
                  </div>
                </div>
              </div>
              </div>

            <?php $x++;} ?>
          </div>


          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h5 class="h5 mb-0 text-gray-800 text-capitalize">Add to philosophy</h5>
          </div>

          <div class="row">

            <!-- Home Heading -->

                <div class="col-lg-12">
                <div class="card shadow mb-4">
                  <a href="#collapseAddphilosophy" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                    <h6 class="m-0 font-weight-bold text-primary text-uppercase text-uppercase">Add to philosophy</h6>
                  </a>
                  <div class="collapse" id="collapseAddphilosophy">
                    <div class="card-body">
                      <form action="philosophy.php" method="post">

                        <div class="d-flex justify-content-around mb-3" style="align-items:center;">
                          <label class="text-uppercase" style="font-size:15px;">philosophy NAME</label>
                            <input type="text" name="philosophy_name_add" class="form-control bg-light border-0 small" style="width:85%;" placeholder="Enter Name" required>
                        </div>
                        
                        <div class="d-flex justify-content-center mb-3" style="align-items:center;">
                          <label class="text-uppercase" style="font-size:15px;">philosophy Priority</label>&nbsp;&nbsp;
                            <input type="number" name="philosophy_priority_add" class="form-control bg-light border-0 small" style="width:35%;" value="99" required>
                        </div>


                        <div class="d-flex justify-content-around mb-3">
                          <label class="text-uppercase" style="font-size:15px;">Short Intro</label>
                            <textarea class="form-control bg-light border-0 small" name="philosophy_sintro_add"style="width:85%;height:15vh" >Short Introduction</textarea>
                        </div>

                        <div class="d-flex justify-content-around mb-3">
                          <label class="text-uppercase" style="font-size:15px;">Long Intro</label>
                            <textarea class="form-control bg-light border-0 small" name="philosophy_lintro_add"style="width:85%;height:15vh" >Long Introduction</textarea>
                        </div>
                        <?php
                            $ch_id = 1;
                            $cimg_qu = "SELECT * FROM images WHERE image_id = '$ch_id'";
                            $cresimg = mysqli_query($db, $cimg_qu);
                            $checkrow = mysqli_fetch_assoc($cresimg);
                            $checked_img = $checkrow['image_path'];
                            $qimg = "SELECT * FROM images ORDER BY image_id DESC";
                            $resimg = mysqli_query($db, $qimg);
                         ?>
                        <div class="card mb-4">
                          <a href="#collapseintroimgAdd" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCardExample">
                            <h6 class="m-0 font-weight-bold text-primary text-uppercase text-uppercase">Select Image</h6>
                          </a>
                          <div class="collapse" id="collapseintroimgAdd">
                            <div class="img-card-body" style="height:25vh; overflow-x: hidden; overflow-y:auto;">
                              <?php
                              echo '&nbsp&nbsp<label class="text-uppercase">&nbsp&nbsp<input class="options" type="radio" checked="checked" name="philosophy_img_add" value="'.$ch_id.'" required>&nbsp&nbsp<img src="'.$checked_img.'" alt="" style="width:100px"></label>';
                              while($rowimg = mysqli_fetch_assoc($resimg)){
                                $img = $rowimg['image_path'];
                                if($ch_id != $rowimg['image_id']){
                                    echo '&nbsp&nbsp<label class="text-uppercase">&nbsp&nbsp<input class="options" type="radio" name="philosophy_img_add" value="'.$rowimg['image_id'].'" required>&nbsp&nbsp<img src="'.$img.'" alt="" style="width:100px"></label>';
                                }
                              }
                               ?>
                            </div>
                          </div>
                        </div>

                        <div class="d-flex justify-content-around mb-3" style="align-items:center;">
                          <label class="text-uppercase mr-2" style="font-size:15px;">Hours</label>
                            <input type="number" name="philosophy_hours_add"class="form-control bg-light border-0 small" style="width:45%;" placeholder="Enter Value">
                            <label class="text-uppercase mr-2" style="font-size:15px;">Cost</label>
                              <input type="number" name="philosophy_cost_add"class="form-control bg-light border-0 small" style="width:45%;" placeholder="Enter Value">
                        </div>
                        <div class="d-flex justify-content-center mb-3" style="align-items:center;">
                          <label class="text-uppercase" style="font-size:15px;">philosophy Contact Info</label>&nbsp;&nbsp;
                            <input type="text" name="philosophy_contact_add" class="form-control bg-light border-0 small" style="width:55%;" placeholder="Enter Value">
                        </div>

                      <div class="row">
                        <?php
                        $ch_id = 1;
                        $cimg_qu = "SELECT * FROM pdf WHERE pdf_id = '$ch_id'";
                        $cresimg = mysqli_query($db, $cimg_qu);
                        $checkrow = mysqli_fetch_assoc($cresimg);
                        $xpdfx = $checkrow['pdf_path'];
                        $tar = explode("/",$xpdfx);
                        $checked_pdf = array_slice($tar, -1)[0];
                        $qimg = "SELECT * FROM pdf ORDER BY pdf_id DESC";
                        $resimg = mysqli_query($db, $qimg);
                         ?>
                        <div class="col-lg-6">
                      <div class="d-flex justify-content-around mb-3" style="align-items:center;">
                        <label class="text-uppercase" style="font-size:15px;">Change philosophy document</label>
                          <select class="js-example-basic-single custom-select mr-sm-2" name="philosophy_pdf_add" style="width:85%;height:10vh;" required>
                            <?php
                            echo '<option selected value="'.$ch_id.'">'.$checked_pdf.'</option>';
                            while($rowimg = mysqli_fetch_assoc($resimg)){
                              $pdfx = $rowimg['pdf_path'];
                              $tar = explode("/",$pdfx);
                              $pdf = array_slice($tar, -1)[0];
                              if($ch_id != $rowimg['pdf_id']){
                                  echo '<option value="'.$rowimg['pdf_id'].'">'.$pdf.'</option>';
                              }
                            }
                             ?>

                          </select>
                        </div>

                      </div>

                      <div class="col-lg-6">
                          <div class="d-flex justify-content-around mb-3" style="align-items:center;">
                            <label class="text-uppercase" style="font-size:15px;">Change Section</label>
                              <select class="js-example-basic-single custom-select mr-sm-2" name="philosophy_tag_add" style="width:85%;height:10vh;" required>
                                <?php
                                echo '<option selected value="None">None</option>';
                                $tag_qu = "SELECT * from tags WHERE tag_page='ayurveda';";
                                $restag = mysqli_query($db, $tag_qu);
                                $rowtag = mysqli_fetch_assoc($restag);
                                $tagnames = $rowtag['tag_names'];
                                $taglist = explode(';',$tagnames);
                                for($i=0;$i<count($taglist);$i++){
                                  if($taglist[$i] != "None"){
                                      echo '<option value="'.$taglist[$i].'">'.$taglist[$i].'</option>';
                                  }
                                }
                                 ?>

                              </select>
                            </div>
                            </div>
                        </div>


                      <div class="d-flex justify-content-around mb-3 mt-5">
                        <button class="btn btn-primary" type="submit" style="width:100%" name='add_philosophy'>
                          Add New event to philosophy
                        </button>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
              </div>
          </div>


        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>YogaAyurvedaKarona</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>





  <!-- Bootstrap core JavaScript-->
  <script src="/assets/vendor/jquery/jquery.min.js"></script>
  <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/vendor/bootstrap/js/popper.js"></script>
  <script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="/assets/vendor/select2/select2.min.js"></script>

  <script type="text/javascript">
        $(document).ready(function() {
          $('.js-example-basic-single').select2();
      });
  </script>
  <!-- Core plugin JavaScript-->
  <script src="/assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="/assets/vendor/daterangepicker/moment.min.js"></script>
  <script src="/assets/vendor/daterangepicker/daterangepicker.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="/assets/js/sb-admin-2.min.js"></script>

</body>

</html>
