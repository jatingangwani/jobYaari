
         
         <div id="content-page" class="content-page" style="min-height: 108.4vh;">

            

<?php 
   $eid = $_SESSION['auser'];
   
   $sql = "SELECT * FROM emplogin WHERE eid='$eid' LIMIT 1";
   $result = $conn->query($sql);
   if($result->num_rows > 0){                  
      $row = $result->fetch_assoc();
   }

   $sessionname = $row['firstname'];
 ?>


         <div class="iq-top-navbar header-top-sticky">

            <div class="iq-navbar-custom">
               <div class="iq-sidebar-logo">
                  <div class="top-logo">
                     <a href="index.php" class="logo">
                     <img src="../assets/images/my/favicon.png" class="img-fluid" alt="JobYaari">
                     <span>JobYaari</span>
                     </a>
                  </div>
               </div>
               <nav class="navbar navbar-expand-lg navbar-light p-0">
               <div class="iq-search-bar">
                        <form action="#" class="searchbox">
                           
                           <span><a href='./index.php'>
                           <img src="../assets/images/my/newlogo.png" class="jy-top-logo" alt="JobYaari"></a></span>
                           
                        </form>
                     </div>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                  <i class="ri-menu-3-line"></i>
                  </button>
                  <div class="iq-menu-bt align-self-center">
                     <div class="wrapper-menu">
                        <div class="main-circle"><i class="ri-more-fill"></i></div>
                           <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
                     </div>
                  </div>
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                     <ul class="navbar-nav ml-auto navbar-list">
                        <li class="nav-item">
                           <a class="search-toggle iq-waves-effect language-title" href="#"><img src="../assets/images/small/india.png" alt="img-flaf" class="img-fluid mr-1" style="height: 16px; width: 16px;" /> English <i class="ri-arrow-down-s-line"></i></a>
                           <div class="iq-sub-dropdown">
                              <a class="iq-sub-card" href="#"><img src="../assets/images/small/india.png" alt="img-flaf" class="img-fluid mr-2" />English</a>
                           </div>
                        </li>
                        <li class="nav-item iq-full-screen">
                           <a href="#" class="iq-waves-effect" id="btnFullscreen"><i class="ri-fullscreen-line"></i></a>
                        </li>

                     </ul>
                  </div>
                  <ul class="navbar-list">
                     <li>
                        <a href="#" class="search-toggle iq-waves-effect d-flex align-items-center">
                           <?php 
                           if($row['dp']==""){
                              echo '<img src="../assets/images/user/';
                                 echo '12.png';
                              echo '" class="img-fluid rounded mr-3" alt="user">';
                           }else{
                                 echo '<img src="../uploads/employee/';
                                 
                                    echo $row['dp'];
                                 
                                 echo '" class="img-fluid rounded mr-3" alt="user">';
                           }

                           ?>

                           <div class="caption">
                              <h6 class="mb-0 line-height">
                                 <?php 
                                    echo "Mr. " . $row['firstname'];
                                  ?>
                              </h6>
                              <span class="font-size-12">Available</span>
                           </div>
                        </a>
                        <div class="iq-sub-dropdown iq-user-dropdown">
                           <div class="iq-card shadow-none m-0">
                              <div class="iq-card-body p-0 ">
                                 <div class="bg-primary p-3">
                                    <h5 class="mb-0 text-white line-height">Hello <?php 
                                          echo "Mr. " . $row['firstname'];
                                  ?></h5>
                                    <span class="text-white font-size-12">Available</span>
                                 </div>
                                 <a href="editprofile.php" class="iq-sub-card iq-bg-primary-hover">
                                    <div class="media align-items-center">
                                       <div class="rounded iq-card-icon iq-bg-primary">
                                          <i class="ri-profile-line"></i>
                                       </div>
                                       <div class="media-body ml-3">
                                          <h6 class="mb-0 ">My Profile</h6>
                                          <p class="mb-0 font-size-12">Modify your personal details.</p>
                                       </div>
                                    </div>
                                 </a>

                                 
                                 <div class="d-inline-block w-100 text-center p-3">
                                    <a class="bg-primary iq-sign-btn" href="logout.php" role="button">Sign out<i class="ri-login-box-line ml-2"></i></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </li>
                  </ul>
               </nav>

            </div>
         </div>
         

         <script type="text/javascript">

            function refreshpage(){
               location.href="./index.php";
            }

         </script>



<div class="modal fade filtershortcutmodal" tabindex="5" role="dialog" aria-hidden="true" style="z-index:9999;">
   <div class="modal-dialog modal-xl">
      <div class="modal-content" style="max-height:100vh;">
         <div class="modal-header">
            <div style="width:100%;text-align:center;">
               <center><div><a onclick="showopt1();" id="opt1" class="btn-primary" style="cursor: pointer;border:1px solid #7d7d7d;padding:7px;padding-left:4%;padding-right:4%;border-radius: 9px;border-right: 0px;background-color:#7d7d7d;color:white;">Shortcut List</a></div></center>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" style="max-height:100vh;overflow-y:scroll; background-color: #eff7f8;">
            <div class="container-fluid">
               <div id="shortcut-panel">
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="iq-card">
                           <div class="iq-card-header d-flex justify-content-between">
                              <div class="iq-header-title">
                                 <h4 class="card-title">Shortcut List</h4>
                              </div>
                           </div>
                           <div class="iq-card-body">
                              <div class="table-responsive" id="filtershortcutlist">
                                 <div class="row">
                                    <div class="col-lg-6">
                                       <table class="table table-striped table-bordered">
                                          <thead>
                                             <tr>
                                                <th style="font-size:18px;text-align: center;">Shortcut</th>
                                                <th style="font-size:18px;">Description</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <tr>
                                                <td style="text-align:center;font-size:20px;"><code>F1</code></td>
                                                <td style="font-size:16px;">Help Menu</td>
                                             </tr>
                                             <tr>
                                                <td style="text-align:center;font-size:20px;"><code>F2</code></td>
                                                <td style="font-size:16px;">Blog List</td>
                                             </tr>
                                             <tr>
                                                <td style="text-align:center;font-size:20px;"><code>F3</code></td>
                                                <td style="font-size:16px;">Add Blog</td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                    <div class="col-lg-6">
                                       <table class="table table-striped table-bordered">
                                          <thead>
                                             <tr>
                                                <th style="font-size:18px;text-align: center;">Shortcut</th>
                                                <th style="font-size:18px;">Description</th>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <tr>
                                                <td style="text-align:center;font-size:20px;"><code>CTRL + L</code></td>
                                                <td style="font-size:16px;">Lock Screen</td>
                                             </tr>
                                             <tr>
                                                <td style="text-align:center;font-size:20px;"><code>CTRL + Q</code></td>
                                                <td style="font-size:16px;">Logout</td>
                                             </tr>
                                             <tr>
                                                <td style="text-align:center;font-size:20px;"><code>CTRL + S</code></td>
                                                <td style="font-size:16px;">Save</td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>
                              <div id="loadingfiltershortcuttable" style="border-radius:25px;height:90vh;width:90%;z-index:200;position: fixed;background-color: rgba(0, 0, 0, 0.3);display:none;">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
