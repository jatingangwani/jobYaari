<?php 
session_start();


if(isset($_SESSION['auser']) && !empty($_SESSION['auser'])){
    $auser = $_SESSION['auser'];   
    $_SESSION['auser'] = "";
    $_SESSION['nduser'] = $auser;
}else if(isset($_SESSION['nduser']) && !empty($_SESSION['nduser'])){
    $auser = $_SESSION['nduser'];
}else{
    ?>
    <script type="text/javascript">
        location.href="../index.php";
    </script>
    <?php 
}

include_once('../includes/dbcon.php');

 ?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Neon is a Responsive Bootstrap 4 Admin Dashboard, UI Kits with SCSS.">
    <meta name="keywords" content="admin template, dashboard template, ui kits, web app, crm, cms, responsive, bootstrap 4, html, sass support, scss">
    <meta name="author" content="xPanther Solutions">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <title>JobYaari | Lock Screen</title>

    
    <link rel="shortcut icon" href="../assets/images/my/favicon.png">

    
    <link href="../examassets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../examassets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="../examassets/css/style.css" rel="stylesheet" type="text/css">
    

</head>

<body class="xp-horizontal">
    <div class="xp-authenticate-bg">
        <div id="alerts" style="position: fixed;margin-top:10px;margin-left:80%;">
            <div id="loggingin" style="display:none;" class="alert alert-info alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Loading!</strong> Validating your Credentials!!!
              <div class="progress">
                  <div class="progress-bar progress-bar-striped active" role="progressbar"
                  aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    100%
                  </div>
                </div>
            </div>

            <div id="loginfailed" style="display:none;" class="alert alert-danger alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Failed!</strong> Password is Incorrect!!!
            </div>

             <div id="blankfield" style="display:none;" class="alert alert-danger alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Failed! </strong> Fields Cannot Be Blank!!!
            </div>

            <div id="loginsuccess" style="display:none;" class="alert alert-success alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Success!</strong> You are logged In!!!
            </div>
        </div>
    </div>
    
    <div id="xp-container" class="xp-container">

        
        <div class="container">

            
            <div class="row vh-100 align-items-center">
                
                <div class="col-lg-12 ">

                    
                    <div class="xp-auth-box">

                        <div class="card">
                            <div class="card-body">
                                <h3 class="text-center mt-0 m-b-15">
                                    <a href="#" class="xp-web-logo"><img style="padding:0px;margin:0px;" src="../assets/images/my/newlogo.png" height="60" alt="logo"></a>
                                </h3>
                                <div class="p-3"> 
                                    
                                        <div class="text-center mb-3">
                                            <h4 class="text-black">Lock Screen</h4>
                                            <p class="text-muted">Not You? Go to <a href="./index.php">Sign In</a> Here</p>
                                        </div>
                                        <div class="xp-user-logo text-center mb-3">
                                    <?php 
                                       $sql = "SELECT * FROM emplogin WHERE eid='$duser' LIMIT 1";
                                       $result = $conn->query($sql);
                                       if($result->num_rows > 0){                  
                                          $row = $result->fetch_assoc();
                                       }

                                       if($row['dp']==""){
                                          echo '<img src="../assets/images/user/';
                                          
                                          echo '12.png';
                                          echo '" class="rounded-circle img-fluid" alt="user-img">';
                                       }else{
                                            echo '<img src="../assets/images/user/';
                                            if($row['gender']=="Male"){
                                            echo '12.png';
                                            }else if($row['gender']=="Female"){
                                            echo '11.png';
                                            }else{
                                            echo '12.png';
                                            }
                                            echo '" class="rounded-circle img-fluid" alt="user-img">';
                                       }

                                       ?>
                                        </div>
                                        <p class="text-muted text-center m-b-30">Enter your password to access the profile.</p>

                                        <div class="form-group">
                                            <input type="password" class="form-control" id="password" placeholder="Password" required autofocus="true">
                                        </div>                          
                                        <button type="" id="unlockbtn" onclick="unlock();" class="btn btn-primary btn-rounded btn-lg btn-block">Sign In</button>
                                    
                                </div>
                            </div>
                        </div>
        
                    </div>
                    

                </div>
                
            </div>
            
        </div>
        
    </div>
    



            
    <script src="../examassets/js/jquery.min.js"></script>
    <script src="../examassets/js/popper.min.js"></script>
    <script src="../examassets/js/bootstrap.min.js"></script>
    <script src="../examassets/js/modernizr.min.js"></script>
    <script src="../examassets/js/detect.js"></script>
    <script src="../examassets/js/jquery.slimscroll.js"></script>
    <script src="../examassets/js/horizontal-menu.js"></script>

    
    <script src="../examassets/js/main.js"></script>
    


<script type="text/javascript">

    $(document).bind("contextmenu",function(e){
        return false;
    });

    function unlock(){
        var validation = formvalidate();
        if(validation == true){
            var mypassword = $("#password").val();
            var mytask = "unlock";
            $.ajax({
              type: 'POST',
              url: './ajax.php',
              data: {task: mytask, password:mypassword},
              dataType: 'json',
              success: function (data) {
                var mydata = data.response;

                if(mydata=="loginsuccess"){
                    $('#loginsuccess').fadeIn('slow', function(){
                        $('#loginsuccess').delay(2000).fadeOut();
                    });
                    gotodashboard();
                }
                else
                {
                    $('#loginfailed').fadeIn('slow', function(){
                        $('#loginfailed').delay(2000).fadeOut();
                    });

                    $("#password").val(""); 
                }
              },
              error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                alert(msg);
            },
            });
        }else{
           $('#blankfield').fadeIn('slow', function(){
                $('#blankfield').delay(2000).fadeOut();
            });
        }
    }


    function gotodashboard(){
        setTimeout(function(){
            window.location.href = "./index.php";                
        },1500)
    }


    function formvalidate(){
      var mypassword = $("#password").val();

      var output = true;
      var focus = "";

      if(mypassword==""){
        if(focus==""){
            focus = "#password";
        }
        output = false;
      }

      $(focus).focus();
      return output;

    }


    $('#password').keypress(function(e) {
      if (e.which == 13) {
        $('#unlockbtn').click();
        return false;
      }
    });
</script>

</body>

</html>
