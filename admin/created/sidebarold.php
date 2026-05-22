<li class="iq-menu-title"><i class="ri-subtract-line"></i><span>Master's Entry</span></li>
                     
                     <li <?php if($filename=='diseaseentry.php' || $filename=='editdisease.php' || $filename=='disease.php'){echo 'class="active"';} ?>>
                        <a href="#ui-elements" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-apps-fill"></i><span>Disease Entry</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="ui-elements" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <li><a href="disease.php"><i class="ri-list-check"></i>All Disease</a></li>
                           <li><a href="diseaseentry.php"><i class="ri-add-box-line"></i>Add Disease</a></li>
                        </ul>
                     </li>
                     <li <?php if($filename=='medicineentry.php' || $filename=='editmedicine.php' || $filename=='medicine.php'){echo 'class="active"';} ?>>
                        <a href="#forms" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-device-fill"></i><span>Medicine Entry</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="forms" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <li><a href="medicine.php"><i class="ri-list-check"></i>All Medicine</a></li>
                           <li><a href="medicineentry.php"><i class="ri-add-box-line"></i>Add Medicine</a>
                        </ul>
                     </li>
	                     <li <?php if($filename=='medicinediseasegroupentry.php' || $filename=='editmedicinediseasegroup.php' || $filename=='medicinediseasegroup.php'){echo 'class="active"';} ?>>
                        <a href="#forms-wizard" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-file-word-fill"></i><span>Medicine-Disease Group</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="forms-wizard" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <li><a href="medicinediseasegroup.php"><i class="ri-list-check"></i>All Groups</a></li>
                           <li><a href="medicinediseasegroupentry.php"><i class="ri-add-box-line"></i>Add Group</a>
                        </ul>
                     </li>
                     <li <?php if($filename=='diagnosisentry.php' || $filename=='editdiagnosis.php' || $filename=='diagnosis.php'){echo 'class="active"';} ?>>
                        <a href="#charts" class="iq-waves-effect collapsed" data-toggle="collapse" aria-expanded="false"><i class="ri-bar-chart-2-fill"></i><span>Diagnosis</span><i class="ri-arrow-right-s-line iq-arrow-right"></i></a>
                        <ul id="charts" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                           <li><a href="diagnosis.php"><i class="ri-list-check"></i>All Diagnosis</a></li>
                           <li><a href="diagnosisentry.php"><i class="ri-add-box-line"></i>Add Diagnosis</a>
                        </ul>
                     </li>
