<?php
if((isset($_COOKIE["auserlogin_id"])) && (!empty($_COOKIE["auserlogin_id"]))) {
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

    <title>JobYaari | Forget Password</title>

    <!-- Fevicon -->
    <link rel="shortcut icon" href="examassets/images/favicon.ico">

    <!-- Start CSS -->
    <link href="examassets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="examassets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="examassets/css/style.css" rel="stylesheet" type="text/css">
    <!-- End CSS -->

</head>

<body class="xp-horizontal">

    <div class="xp-authenticate-bg"></div>
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
                                <h3 class="text-center mt-0 m-b-15">
                                    <a href="index-2.html" class="xp-web-logo"><img src="examassets/images/logo-default.svg" height="40" alt="logo"></a>
                                </h3>
                                <div class="p-3">                                
                                    <form action="#">
                                        <div class="text-center mb-3">
                                            <h4 class="text-black">Forgot Password</h4>
                                            <p class="text-muted">Remember Password? <a href="./index.php">Sign In</a> Here</p>
                                        </div>
                                        <p class="text-muted text-center m-b-30">We’ll send you instructions via email to help you reset your password.</p>
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="email" placeholder="Email" required>
                                        </div>                          
                                        <button type="submit" class="btn btn-primary btn-rounded btn-lg btn-block">Send Email</button>
                                    </form>
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

</body>
</html>