<?php

session_start();

if((isset($_SESSION["auser"])) && (!empty($_SESSION["auser"]))) {
    include_once('../includes/dbcon.php');
    
    $eid = $_SESSION['auser'];
    $sql = "select * from emplogin where eid='$eid'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
      $row =$result->fetch_assoc();
      $role = $row['role'];
    }else{
      $role = "";
    }
    
} else {
  ?>
    <script>
        window.location.href="../index.php";
    </script>
  <?php
  exit();
}

date_default_timezone_set('Asia/Calcutta');

?>

<!doctype html>
<html lang="en">
   
<head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Job Yaari | Admin Portal</title>
      <link rel="shortcut icon" href="../assets/images/my/favicon.png" />
      <link rel="stylesheet" href="../assets/css/multiple-select.min.css">
      <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="../assets/css/typography.css">
      <link rel="stylesheet" href="../assets/css/style.css">
      <link rel="stylesheet" href="../assets/css/my.css">
      <link rel="stylesheet" href="../assets/css/responsive.css">
      <link href='../assets/fullcalendar/core/main.css' rel='stylesheet' />
      <link href='../assets/fullcalendar/daygrid/main.css' rel='stylesheet' />
      <link href='../assets/fullcalendar/timegrid/main.css' rel='stylesheet' />
      <link href='../assets/fullcalendar/list/main.css' rel='stylesheet' />
      <link rel="stylesheet" href="../assets/css/flatpickr.min.css">

    <style>
.btn:focus{
  box-shadow: 2px;
}


.ms-choice{
  border:0px;
}

div.ms-parent.form-control{
  line-height: 25px;
}

.modal {
  display: none;
  position: fixed;
  z-index: 999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0,0,0,0.4);
}

.modal-contentdelete {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 25%;
  left:30%;
}

.modal-contentsave {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 25%;
  left:30%;
}

.modal-contentblank {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 25%;
  left:30%;
}
.modal-contentdeletesuccess {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 25%;
  left:30%;
}

.modal-contentedit {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 25%;
  left:30%;
}

.modal-contenteditsuccess {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 25%;
  left:30%;
}

.closeblank {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.closeblank:hover,
.closeblank:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

.closesave {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.closesave:hover,
.closesave:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

.closedelete {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.closedelete:hover,
.closedelete:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

.closedeletesuccess {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.closedeletesuccess:hover,
.closedeletesuccess:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

.closeedit {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.closeedit:hover,
.closeedit:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

.closeeditsuccess{
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.closeeditsuccess:hover,
.closeeditsuccess:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

</style>
   </head>
   <body class="sidebar-main-menu">
      <div id="loading">
         <div id="loading-center">

         </div>
      </div>

           <div id="alerts" style="position: fixed;margin-top:1%;z-index:1055;position: fixed;right:0;text-align:right;">
            <div id="alertprimary" style="display:none;" class="alert alert-info alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <div id="alertprimarymessage"><strong>Loading!</strong> Validating your Credentials!!!</div>
              <div class="progress">
                  <div class="progress-bar progress-bar-striped active" role="progressbar"
                  aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    100%
                  </div>
                </div>
            </div>

            <div id="alertdanger" style="display:none;" class="alert alert-danger alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <div id="alertdangermessage"><strong>Failed!</strong> Mobile Number of Password is Incorrect!!!</div>
            </div>

            <div id="alertsuccess" style="display:none;" class="alert alert-success alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <div id="alertsuccessmessage"><strong>Success!</strong> You are logged in!!!</div>
            </div>
         </div>

          <div id="alerts" style="position: fixed;margin-top:40%;z-index:1055;position: fixed;right:0;text-align:right;">
            <div id="alertprimary2" style="display:none;" class="alert alert-info alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <div id="alertprimarymessage2"><strong>Loading!</strong> Validating your Credentials!!!</div>
              <div class="progress">
                  <div class="progress-bar progress-bar-striped active" role="progressbar"
                  aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    100%
                  </div>
                </div>
            </div>

            <div id="alertdanger2" style="display:none;" class="alert alert-danger alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <div id="alertdangermessage2"><strong>Failed!</strong> Mobile Number of Password is Incorrect!!!</div>
            </div>

            <div id="alertsuccess2" style="display:none;" class="alert alert-success alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <div id="alertsuccessmessage2"><strong>Success!</strong> You are logged in!!!</div>
            </div>
          </div>

          

      <div class="wrapper">
