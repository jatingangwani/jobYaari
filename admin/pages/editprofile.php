<?php include('../created/header.php'); ?>
<?php include('../created/sidebar.php'); ?>
<?php include('../created/pageheader.php'); ?>

<?php
$eid = $_SESSION['auser'];
$sql = "SELECT * FROM emplogin WHERE eid='$eid' LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
   $row = $result->fetch_assoc();
   $role = $row['role'];
}
?>



<div class="container-fluid">
   <div id="addemp">
      <div class="row">
         <div class="col-lg-3">
            <div class="iq-card">
               <div class="iq-card-header d-flex justify-content-between">
                  <div class="iq-header-title">
                     <h4 class="card-title">Edit Profile</h4>
                  </div>
               </div>
               <div class="iq-card-body">
                  <form>
                     <div class="form-group" style="text-align: center;">
                        <div class="add-img-user profile-img-edit">
                        <?php 
                           if($row['dp']==""){
                              echo '<img class="profile-pic img-fluid" id="eprofile-pic" src="../assets/images/user/11.png" alt="profile-pic">';
                           }else{
                                 echo '<img class="profile-pic img-fluid" id="eprofile-pic" src="../uploads/employee/';
                                 
                                    echo $row['dp'];
                                 
                                 echo '" alt="profile-pic">';
                           }

                           ?>
                           <div class="p-image">
                              <a href="javascript:void();" id="eupload-button" class="upload-button btn iq-bg-primary">File Upload</a>
                              <input class="file-upload" id="edp" type="file" accept="image/*">
                           </div>
                        </div>
                        <div class="img-extension mt-3">
                           <div class="d-inline-block align-items-center">
                              <span>Only</span>
                              <a href="javascript:void();">.jpg</a>
                              <a href="javascript:void();">.png</a>
                              <a href="javascript:void();">.jpeg</a>
                              <span>allowed</span>
                           </div>
                        </div>
                     </div>
                     <br>
                  </form>
               </div>
            </div>
         </div>
         <div class="col-lg-9">
            <div class="iq-card">
               <div class="iq-card-header d-flex justify-content-between">
                  <div class="iq-header-title">
                     <h4 class="card-title">User Information</h4>
                  </div>
               </div>
               <div class="iq-card-body">
                  <div class="new-user-info">
                     <form>
                        <div class="row">
                           <div class="form-group col-md-6">
                              <label for="fname" style="width:100%;">First Name: <span id="efirstnamerequiredspan" style="float:right;color:red;display:none;">Required *</span></label>
                              <input type="text" value="<?php echo $row['firstname']; ?>" class="form-control" id="efirstname" placeholder="---" disabled>
                           </div>
                           <div class="form-group col-md-6">
                              <label for="lname" style="width:100%;">Last Name: <span id="elastnamerequiredspan" style="float:right;color:red;display:none;">Required *</span></label>
                              <input type="text" value="<?php echo $row['lastname']; ?>" class="form-control" id="elastname" placeholder="---" disabled>
                           </div>
                           <div class="form-group col-md-6">
                              <label for="email">Email:<span id="eemailrequiredspan" style="float:right;color:red;display:none;">Required *</span></label>
                              <input type="email" class="form-control" value="<?php echo $row['email']; ?>" id="eemail" placeholder="---">
                           </div>
                           <div class="form-group col-md-6">
                              <label for="mobno" style="width:100%;">Mobile Number: <span id="emobilenorequiredspan" style="float:right;color:red;display:none;">Required *</span></label>
                              <input type="text" class="form-control" value="<?php echo $row['mobileno']; ?>" id="emobileno" placeholder="---">
                           </div>
                        </div>
                        <hr>
                        <h5 class="mb-3">Security</h5>
                        <div class="row">
                           <div class="form-group col-md-6">
                              <label for="pass">Password:</label>
                              <input type="password" class="form-control" id="epassword" placeholder="---">
                           </div>
                           <div class="form-group col-md-6">
                              <label for="rpass" style="width:100%;">Repeat Password: <span id="econfirmpasswordsamerequiredspan" style="float:right;color:red;display:none;">Confirm Password must be Same</span></label>
                              <input type="password" class="form-control" id="econfirmpassword" placeholder="---">
                           </div>
                        </div>
                        <hr>
                        <button type="button" class="btn btn-danger" onclick="resetfields();">Reset</button>
                        <button type="button" id="savechangesbtn" onclick="submitmodal();" class="btn btn-primary">Save changes</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include('../created/pagefooter.php'); ?>
<?php include('../created/footer.php'); ?>

<script type="text/javascript">
// ---------------------------------Initializer--------------------------------------

$(document).bind("contextmenu", function(e) {
   return false;
});

$(document).ready(function() {
   var myElement = document.getElementById('content-page');
   var mc = new Hammer(myElement);

   mc.on("panright", function(ev) {
      $(".wrapper-menu").addClass('open');
      $("body").addClass("sidebar-main");
   });

   mc.on("panleft", function(ev) {
      $(".wrapper-menu").removeClass('open');
      $("body").removeClass("sidebar-main");
   });

   mc.on("tap", function(ev) {
      $(".wrapper-menu").removeClass('open');
      $("body").removeClass("sidebar-main");
   });

   $("#eupload-button").click(function() {
      $("#edp").click();
   });
});

// ---------------------------------Page Functions-----------------------------------

function submitmodal() {
   var validation = formempvalidate();
   if (validation == true) {
      var emyrole = "Admin";
      var emyfirstname = $("#efirstname").val();
      var emylastname = $("#elastname").val();
      var emymobileno = $("#emobileno").val();
      var emyemail = $("#eemail").val();
      var emypassword = $("#epassword").val();
      var emytask = "updateemployee";

      var formData = new FormData();
      formData.append('task', emytask);
      formData.append('role', emyrole);
      formData.append('firstname', emyfirstname);
      formData.append('lastname', emylastname);
      formData.append('mobileno', emymobileno);
      formData.append('email', emyemail);
      formData.append('dp', $('#edp').prop('files')[0]);
      formData.append('password', emypassword);
      console.log(formData);
      $.ajax({
         type: 'POST',
         contentType: false,
         cache: false,
         processData: false,
         url: './ajax.php',
         data: formData,
         dataType: 'json',
         success: function(data) {
            console.log(data);
            var mydata = data.response;

            if (mydata == "recordadded") {
               refreshlist();
               $("#alertsuccessmessage").html("<strong>Success</strong> New Employee Added in Records!!!");
               $('#alertsuccess').fadeIn('slow', function() {
                  $('#alertsuccess').delay(2000).fadeOut();
               });
               clearempfields();
            } else if (mydata == "recordupdated") {
               $("#alertsuccessmessage").html("<strong>Success</strong> Record Updated!!!");
               $('#alertsuccess').fadeIn('slow', function() {
                  $('#alertsuccess').delay(2000).fadeOut();
               });
            } else if (mydata == "imageextensionerror") {
               $("#alertdangermessage").html("<strong>Error!!!</strong> Only JPG , JPEG & PNG Are Allowed!!!");
               $('#alertdanger').fadeIn('slow', function() {
                  $('#alertdanger').delay(2000).fadeOut();
               });
            } else if (mydata == "imageerror") {
               $("#alertdangermessage").html("<strong>Error!!!</strong> Unexpected Error!!!");
               $('#alertdanger').fadeIn('slow', function() {
                  $('#alertdanger').delay(2000).fadeOut();
               });
            } else {
               $("#alertdangermessage").html("<strong>Error!!!</strong> Record Not Added!!!");
               $('#alertdanger').fadeIn('slow', function() {
                  $('#alertdanger').delay(2000).fadeOut();
               });
            }
         },
         error: function(jqXHR, exception) {
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
   } else {
      $("#alertdangermessage").html("<strong>Error!!!</strong> Blank Fields Are Not Allowed!!!");
      $('#alertdanger').fadeIn('slow', function() {
         $('#alertdanger').delay(2000).fadeOut();
      });
   }
}

function resetfields() {
   $("#emobileno").val("");
   $("#eemail").val("");
   $("#epassword").val("");
   $("#econfirmpassword").val("");
}

$("#edp").change(function(e) {
   var file = this.files[0];
   var fileType = file.type;
   var match = ['image/jpeg', 'image/png', 'image/jpg'];
   if (!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]))) {
      $("#alertdangermessage").html("<strong>Error!!!</strong> Only jpg, jpeg and png Formats are Allowed!!!");
      $('#alertdanger').fadeIn('slow', function() {
         $('#alertdanger').delay(2000).fadeOut();
      });
      $("#edp").val('');
      return false;
   } else {
      var file = $("#edp").get(0).files[0];
      if (file) {
         var reader = new FileReader();
         reader.onload = function() {
            $("#eprofile-pic").attr("src", reader.result);
         };
         reader.readAsDataURL(file);
      }
   }
});

$('#efirstname').keypress(function(e) {
   if (e.which == 13) {
      $('#elastname').focus();
      return false;
   }
});

$('#elastname').keypress(function(e) {
   if (e.which == 13) {
      $('#eemail').focus();
      return false;
   }
});

$('#eemail').keypress(function(e) {
   if (e.which == 13) {
      $('#emobileno').focus();
      return false;
   }
});

$('#emobileno').keypress(function(e) {
   if (e.which == 13) {
      $('#epassword').focus();
      return false;
   }
});

$('#epassword').keypress(function(e) {
   if (e.which == 13) {
      $("#econfirmpassword").focus();
      return false;
   }
});

$('#econfirmpassword').keypress(function(e) {
   if (e.which == 13) {
      $("#savechangesbtn").click();
      return false;
   }
});

function formempvalidate() {
   var myfirstname = $("#efirstname").val();
   var mylastname = $("#elastname").val();
   var mymobileno = $("#emobileno").val();
   var myemail = $("#eemail").val();
   var mypassword = $("#epassword").val();
   var myconfirmpassword = $("#econfirmpassword").val();
   var output = true;
   var focus = "";

   if (myfirstname == "") {
      $("#efirstnamerequiredspan").css("display", "block");
      if (focus == "") {
         focus = "#efirstname";
      }
      output = false;
   }

   if (mylastname == "") {
      $("#elastnamerequiredspan").css("display", "block");
      if (focus == "") {
         focus = "#elastname";
      }
      output = false;
   }

   if (mymobileno == "") {
      $("#emobilenorequiredspan").css("display", "block");
      if (focus == "") {
         focus = "#emobileno";
      }
      output = false;
   }

   if (myemail == "") {
      $("#eemailrequiredspan").css("display", "block");
      if (focus == "") {
         focus = "#eemail";
      }
      output = false;
   }

   if (mypassword == "" && myconfirmpassword == "") {
   } else {
      if (myconfirmpassword != mypassword) {
         $("#econfirmpasswordsamerequiredspan").css("display", "block");
         if (focus == "") {
            focus = "#econfirmpassword";
         }
         output = false;
      }
   }

   $(focus).focus();
   return output;
}

$('#efirstname').keyup(function() {
   if ($("#efirstname").val() != "") {
      $("#efirstnamerequiredspan").css("display", "none");
   }
});

$('#elastname').keyup(function() {
   if ($("#elastname").val() != "") {
      $("#elastnamerequiredspan").css("display", "none");
   }
});

$('#emobileno').keyup(function() {
   if ($("#emobileno").val() != "") {
      $("#emobilenorequiredspan").css("display", "none");
   }
});

$('#eemail').keyup(function() {
   if ($("#eemail").val() != "") {
      $("#eemailrequiredspan").css("display", "none");
   }
});

$('#epassword').keyup(function() {
   if ($("#epassword").val() != "") {
      $("#epasswordrequiredspan").css("display", "none");
   }
});

$('#econfirmpassword').keyup(function() {
   if ($("#econfirmpassword").val() != "") {
      $("#econfirmpasswordrequiredspan").css("display", "none");
   }
});

$(document).on('keypress', '#emobileno', function(e) {
   if ($(e.target).prop('value').length >= 10) {
      if (e.keyCode != 32) {
         return false;
      }
   }
});

// ---------------------------------Page Functions-----------------------------------

// ---------------------------------Hotkeys--------------------------------------

<?php if ($role == "Admin") { ?>
hotkeys('f1', function(event, handler) {
   event.preventDefault();
   $('.filtershortcutmodal').modal('toggle');
});

hotkeys('f2', function(event, handler) {
   event.preventDefault();
   window.location.href = "./bloglist.php";
});

hotkeys('f3', function(event, handler) {
   event.preventDefault();
   window.location.href = "./addblog.php";
});

hotkeys('f10', function(event, handler) {
   event.preventDefault();
   window.location.href = "./editprofile.php";
});
<?php } ?>

hotkeys('f11', function(event, handler) {
   event.preventDefault();
   window.location.href = "./lockscreen.php";
});

hotkeys('f12', function(event, handler) {
   event.preventDefault();
   window.location.href = "./logout.php";
});

hotkeys('ctrl+l', function(event, handler) {
   event.preventDefault();
   window.location.href = "./lockscreen.php";
});

hotkeys('ctrl+q', function(event, handler) {
   event.preventDefault();
   window.location.href = "./logout.php";
});

hotkeys('ctrl+s', function(event, handler) {
   event.preventDefault();
   $("#savechangesbtn").click();
});

hotkeys('ctrl+enter', function(event, handler) {
   event.preventDefault();
   $("#savechangesbtn").click();
});

hotkeys.filter = function(event) {
   return true;
};

hotkeys('esc', function(event, handler) {
   // event.preventDefault();
});

// ---------------------------------Hotkeys--------------------------------------
</script>
