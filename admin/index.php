<?php
session_start();

if((isset($_SESSION["auser"])) && (!empty($_SESSION["auser"]))) {
    ?>
    <script>
        window.location.href="./pages/index.php";
    </script>
    <?php
    exit();
} else {
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Job Yaari | Admin Login">
    <meta name="keywords" content="Job Yaari, Admin Login, Job Portal">
    <meta name="author" content="Jatin Gangwani">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

        <title>Job Yaari | Admin Login</title>

    <!-- Fevicon -->
    <link rel="shortcut icon" href="./assets/images/my/hmlogo.png">

    <!-- Start CSS -->
    <link href="examassets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="examassets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="examassets/css/style.css" rel="stylesheet" type="text/css">
    <!-- End CSS -->

</head>

<body class="xp-horizontal">
    <div class="xp-authenticate-bg">
        <div id="alerts" style="position: fixed;z-index:999; margin-top:10px;right:0;">
            <div id="loggingin" style="display:none;" class="alert alert-info alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Loading! </strong> Validating your Credentials!!!
              <div class="progress">
                  <div class="progress-bar progress-bar-striped active" role="progressbar"
                  aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    100%
                  </div>
                </div>
            </div>

            <div id="loginfailed" style="display:none;" class="alert alert-danger alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Failed! </strong> Mobile Number or Password is Incorrect!!!
            </div>

             <div id="blankfield" style="display:none;" class="alert alert-danger alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Failed! </strong> Fields Cannot Be Blank!!!
            </div>

            <div id="loginsuccess" style="display:none;" class="alert alert-success alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Success! </strong> You are logged In!!!
            </div>
        </div>
    </div>
    <!-- Start XP Container -->
    <div id="xp-container" class="xp-container">

        <!-- Start Container -->
        <div class="container">

            <!-- Start XP Row -->
            <div class="row vh-100 align-items-center">
                <!-- Start XP Col -->
                <div class="col-lg-12 ">

                    <!-- Start XP Auth Box -->
                    <div class="xp-auth-box">

                        <div class="card">
                            <div class="card-body">
                                <h3 class="text-center mt-0 m-b-15" >
                                    <div  class="xp-web-logo" >
                                        <img src="./assets/images/my/newlogo.png" style="padding:0px;margin:0px;" height="60" alt="logo">
                                    </div>
                                </h3>
                                <div class="p-3">
                                        <div class="text-center mb-3">
                                        <!-- /*<h4 class="text-black" style="font-family: ;">Clinic Login</h4>*/ -->
                                            <!-- <p class="text-muted">New Clinic Registration? <a href="register.php">Sign Up</a> Here</p> -->
                                            <!-- <p class="text-muted">Login & Go to Dashboard</p> -->
                                        </div>
                                        <div class="text-center">
                                            <!-- <p class="text-muted">Login & Go to Dashboard</p> -->
                                        </div>                                        
                                         <!-- <div class="social-login text-center">
                                            <button type="button" class="btn btn-facebook btn-rounded mb-1"><i class="fa fa-facebook m-r-5"></i> Facebook </button>
                                            <button 
                                             class="btn btn-googleplus btn-rounded mb-1"><i class="fa fa-google-plus m-r-5"></i> Google+ </button>
                                        </div>  -->
                                        <div class="login-or">
                                            <h6 class="text-muted">* *</h6>
                                        </div>
                                        
                                        <div class="form-group">
                                            <input type="number" class="form-control" id="mobileno" name="mobileno" placeholder="Mobile Number" required autofocus="true">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                        </div>
                                        <br>
                                        <div class="form-row">
                                            <div class="form-group col-6">
                                                <div class="custom-control custom-checkbox">
                                                  <input type="checkbox" class="custom-control-input" id="rememberme">
                                                  <label class="custom-control-label" for="rememberme">Remember Me</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-6 text-right">
                                              <label class="forgot-psw"> 
                                                <!-- <a id="forgot-psw" href="forgetpwd.php">Forgot Password?</a> -->
                                                <a id="forgot-psw" href="#">Forgot Password?</a>
                                              </label>
                                            </div>
                                        </div>                          
                                      <input type="button" id="login" class="btn btn-primary btn-rounded btn-lg btn-block" Value="Sign In">
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- End XP Auth Box -->

                </div>
                <!-- End XP Col -->
            </div>
            <!-- End XP Row -->
        </div>
        <!-- End Container -->
    </div>
    <!-- End XP Container -->
    <div>
        <input type="text" name="" id="text">
    </div>

    <!-- Start JS -->        
    <script src="examassets/js/jquery.min.js"></script>
    <script src="examassets/js/popper.min.js"></script>
    <script src="examassets/js/bootstrap.min.js"></script>
    <script src="examassets/js/modernizr.min.js"></script>
    <script src="examassets/js/detect.js"></script>
    <script src="examassets/js/jquery.slimscroll.js"></script>
    <script src="examassets/js/horizontal-menu.js"></script>

    <!-- Main JS -->
    <script src="examassets/js/main.js"></script>
    <!-- End JS -->


        <script type="text/javascript">
    
    $(document).on('keypress','#mobileno',function(e){
        if($(e.target).prop('value').length>=10){
          if(e.keyCode!=32)
          {
            return false
          } 
      }});


        function formvalidate(){
         var mymobileno = $("#mobileno").val();
         var mypassword = $("#password").val();

          var output = true;
          var focus = "";

          if(mymobileno==""){
            if(focus==""){
                focus = "#mobileno";
            }
            output = false;
          }

          if(mypassword==""){
            if(focus==""){
                focus = "#password";
            }
            output = false;
          }
          $(focus).focus();
          return output;
        }


        $('#mobileno').keypress(function(e) {
          if (e.which == 13) {
            $('#password').focus();
            return false;
          }
        });

        $('#password').keypress(function(e) {
          if (e.which == 13) {
            $('#login').click();
            return false;
          }
        });

        $('#rememberme').keypress(function(e) {
          if (e.which == 13) {
            $('#login').click();
            return false;
          }
        });

        $( document ).ready(function() {
            
            var cookie_mobileno = getCookie('auserlogin_mobileno');   
            
            var cookie_ps = getCookie('auserlogin_password');
            
            
            if(cookie_mobileno=="" && cookie_ps==""){

            }else{

                $('#mobileno').val(cookie_mobileno);
                $('#password').val(cookie_ps);
                $("#rememberme"). prop("checked", true);
            }
        });


        $('#login').on('click',function(){
            
            var validation = formvalidate();
            if(validation == true){

                var mymobileno=$('#mobileno').val();
                var mypassword=$('#password').val();            

                $('#loggingin').fadeIn('fast', function(){
                    $('#loggingin').delay(1500).fadeOut();
                });

                if($('#rememberme').is(':checked')){
                    var rememberme="true";    
                }else{
                    var rememberme="false";
                }
                var mytask="login";
                $.ajax({
                  type: 'POST',
                  url: 'pages/ajax.php',
                  data: {task: mytask, mobileno: mymobileno, password: mypassword},
                  dataType: 'json',
                  success: function (data) {
                    console.log(data);
                    $("#text").val(data.response);
                    var mydata = $("#text").val();
                    
                    if(mydata=="loginsuccess"){
                        $('#loginsuccess').fadeIn('slow', function(){
                            $('#loginsuccess').delay(2000).fadeOut();
                        });
                        
                        if(rememberme=="true"){
                            setlogincookie(mymobileno,mypassword,data.eid);
                        }else{
                            removelogincookie();
                        }

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
               exit();
            }

        });

        function getCookie(cname) {
          var name = cname + "=";
          var decodedCookie = decodeURIComponent(document.cookie);
          var ca = decodedCookie.split(';');
          for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
              c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
            }
          }
          return "";
        }

        function gotodashboard(){
            setTimeout(function(){
                window.location.href = "./pages/index.php";                
            },1500)
        }

        function setlogincookie(mobileno, pass,mid) {
            
            var d = new Date();
            d.setTime(d.getTime() + (30*24*60*60*1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = "auserlogin_id=" + mid + "; " + expires + "; path=/";
            document.cookie = "auserlogin_mobileno=" + mobileno + "; " + expires + "; path=/";
            document.cookie = "auserlogin_password=" + pass + "; " + expires + "; path=/";
        }

        function removelogincookie() {
            var d = new Date();
            d.setTime(d.getTime() - (30*24*60*60*1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = "auserlogin_id=''; " + expires + "; path=/";
            document.cookie = "auserlogin_mobileno=''; " + expires + "; path=/";
            document.cookie = "auserlogin_password=''; " + expires + "; path=/";
        }
    </script>

</body>
</html>