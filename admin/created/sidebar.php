         <div class="iq-sidebar">
            <div class="iq-sidebar-logo d-flex justify-content-between">
               <a href="index.php">
               <img src="../assets/images/my/logoinverted.png" class="img-fluid jy-sidebar-logo" alt="JobYaari">
               </a>
               <div class="iq-menu-bt-sidebar">
                     <div class="iq-menu-bt align-self-center">
                        <div class="wrapper-menu">
                           <div class="main-circle"><i class="ri-more-fill"></i></div>
                           <div class="hover-circle"><i class="ri-more-2-fill"></i></div>
                        </div>
                     </div>
                  </div>
            </div>

            <?php $filename = basename($_SERVER['PHP_SELF']) ?>
            <div id="sidebar-scrollbar">
               <nav class="iq-sidebar-menu">
                  <ul id="iq-sidebar-toggle" class="iq-menu">

                     <li <?php if($filename=='index.php'){echo 'class="active"';} ?> >
                        <a href="index.php" class="iq-waves-effect"><i class="ri-dashboard-3-fill"></i><span>Admin Dashboard</span></a>
                     </li>

                     <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Master's Entry</span></li>  <li <?php if($filename=='addblog.php' || $filename=='bloglist.php'){echo 'class="active"';} ?>>
                        <a href="#blog-info" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-article-fill"></i><span>Blogs</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="blog-info" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <li><a href="bloglist.php"><i class="ri-file-list-fill"></i>All Blogs</a></li>
                           <li><a href="addblog.php"><i class="ri-add-box-fill"></i> Add Blog</a></li>
                        </ul>
                     </li>
                     <li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Management</span></li>

                       <li <?php if($filename=='editprofile.php' || $filename=="settings.php"){echo 'class="active"';} ?>>
                        <a href="#account-settings" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-profile-line"></i><span>Account Settings</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="account-settings" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <li><a href="editprofile.php"><i class="ri-edit-box-line"></i>Edit Profile</a></li>
                           
                        </ul>
                     </li>
                    <li><a href="lockscreen.php" class="iq-waves-effect"><i class="ri-file-lock-fill"></i><span>Lock Screen</span></a></li>
                    <li><a href="logout.php" class="iq-waves-effect"><i class="ri-login-box-line"></i><span>Log Out</span></a></li>
                  </ul>
               </nav>
               <div class="p-3"></div>
            </div>
         </div>
