<?php
include_once('../includes/dbcon.php');
date_default_timezone_set('Asia/Calcutta');
session_start();
error_reporting(0);

$output = array();

function make_blog_slug($title){
	$slugsource = trim(html_entity_decode(strip_tags($title), ENT_QUOTES, 'UTF-8'));
	if(function_exists('iconv')){
		$convertedslug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slugsource);
		if($convertedslug!==false && trim($convertedslug)!=""){
			$slugsource = $convertedslug;
		}
	}
	$slug = strtolower($slugsource);
	$slug = preg_replace('/&+/', ' and ', $slug);
	$slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
	$slug = trim($slug, '-');
	$slug = preg_replace('/-+/', '-', $slug);
	if(strlen($slug) > 150){
		$slug = substr($slug, 0, 150);
		$slug = trim($slug, '-');
	}
	if($slug==""){
		$slug = "blog-" . md5($title);
	}
	return $slug;
}

function make_unique_blog_slug($conn, $table, $slug, $updateid){
	$originalslug = $slug;
	$counter = 1;
	while(true){
		$sql = "SELECT blogid FROM $table WHERE slug='$slug'";
		if($updateid!=""){
			$sql = $sql . " AND blogid!='$updateid'";
		}
		$result = $conn->query($sql);
		if(!$result || $result->num_rows == 0){
			return $slug;
		}
		$slug = $originalslug . "-" . $counter;
		$counter++;
	}
}

function blog_datetime($datevalue){
	if($datevalue==""){
		return date("Y-m-d H:i:s");
	}
	$timestamp = strtotime($datevalue);
	if($timestamp===false){
		return date("Y-m-d H:i:s");
	}
	return date("Y-m-d H:i:s", $timestamp);
}

function blog_short_description($content){
	$shortdescription = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($content), ENT_QUOTES, 'UTF-8')));
	if(function_exists('mb_substr')){
		return mb_substr($shortdescription, 0, 220, 'UTF-8');
	}
	return substr($shortdescription, 0, 220);
}

function ensure_blog_relation_tables($conn){
	$conn->query("CREATE TABLE IF NOT EXISTS blogcategories (blogcategoryid INT NOT NULL AUTO_INCREMENT PRIMARY KEY, category VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY category_slug (slug)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	$conn->query("CREATE TABLE IF NOT EXISTS blogtags (blogtagid INT NOT NULL AUTO_INCREMENT PRIMARY KEY, tag VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY tag_slug (slug)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	$conn->query("CREATE TABLE IF NOT EXISTS blogcategorylink (blogcategorylinkid INT NOT NULL AUTO_INCREMENT PRIMARY KEY, blogid INT NOT NULL, blogcategoryid INT NOT NULL, UNIQUE KEY blog_category_unique (blogid, blogcategoryid)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	$conn->query("CREATE TABLE IF NOT EXISTS blogtaglink (blogtaglinkid INT NOT NULL AUTO_INCREMENT PRIMARY KEY, blogid INT NOT NULL, blogtagid INT NOT NULL, UNIQUE KEY blog_tag_unique (blogid, blogtagid)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function decode_blog_values($value){
	$values = json_decode($value, true);
	if(!is_array($values)){
		$values = array();
	}
	return $values;
}

function save_blog_terms($conn, $blogid, $values, $mastertable, $masterid, $namefield, $linktable, $linkid){
	foreach($values as $value){
		$value = trim($value);
		if($value==""){
			continue;
		}
		$cleanvalue = strip_tags(trim(mysqli_real_escape_string($conn,$value)));
		$slug = mysqli_real_escape_string($conn, make_blog_slug($value));
		$sql = "SELECT $masterid FROM $mastertable WHERE slug='$slug'";
		$result = $conn->query($sql);
		if($result && $result->num_rows > 0){
			$row = $result->fetch_assoc();
			$termid = $row[$masterid];
		}else{
			$sql = "INSERT INTO $mastertable ($masterid,$namefield,slug,created_at) VALUES (DEFAULT,'$cleanvalue','$slug','".date("Y-m-d H:i:s")."')";
			$conn->query($sql);
			$termid = $conn->insert_id;
		}

		if($termid!=""){
			$sql = "INSERT IGNORE INTO $linktable ($linkid,blogid,$masterid) VALUES (DEFAULT,'$blogid','$termid')";
			$conn->query($sql);
		}
	}
}

function replace_blog_terms($conn, $blogid, $categories, $tags){
	ensure_blog_relation_tables($conn);
	$conn->query("DELETE FROM blogcategorylink WHERE blogid='$blogid'");
	$conn->query("DELETE FROM blogtaglink WHERE blogid='$blogid'");
	save_blog_terms($conn, $blogid, $categories, 'blogcategories', 'blogcategoryid', 'category', 'blogcategorylink', 'blogcategorylinkid');
	save_blog_terms($conn, $blogid, $tags, 'blogtags', 'blogtagid', 'tag', 'blogtaglink', 'blogtaglinkid');
}

function get_blog_terms($conn, $blogid, $mastertable, $masterid, $namefield, $linktable){
	$list = array();
	ensure_blog_relation_tables($conn);
	$sql = "SELECT m.$namefield FROM $mastertable m INNER JOIN $linktable l ON m.$masterid=l.$masterid WHERE l.blogid='$blogid' ORDER BY m.$namefield";
	$result = $conn->query($sql);
	if($result && $result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$list[] = $row[$namefield];
		}
	}
	return $list;
}

if(isset($_POST['task'])){
	$mytask = $_POST['task'];

	if($mytask=="login"){
		$mymobileno = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['mobileno'])));
		$mypassword = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['password'])));

		$sql = "select * from emplogin where mobileno='$mymobileno' and password='$mypassword' and freeze='0'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			$output['eid'] = md5($row['eid'])."Tx1oRc".md5($row['eid']);
			$output['response'] = "loginsuccess";

			$eid = $row['eid'];
			$_SESSION['auser'] = $row['eid'];
		}else{
			$output['response'] = "loginfailed";
		}

		echo json_encode($output);
	}

	if($mytask=="unlock"){
		$mypassword = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['password'])));
		$eid = $_SESSION['nduser'];

		$sql = "select * from emplogin where eid='$eid' and password='$mypassword' and freeze='0'";
		$output['sql']=$sql;
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$output['response'] = "loginsuccess";

			$_SESSION['auser'] = $eid;

			$_SESSION['nduser'] = "";
		}else{
			$output['response'] = "loginfailed";
		}

		echo json_encode($output);
	}

	if($mytask=="updateemployee"){
		$mymobileno = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['mobileno'])));
		$myemail = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['email'])));
		$mypassword = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['password'])));

		$eid = $_SESSION['auser'];

		$sql = "Select * from emplogin where eid='$eid'";
		$result = $conn->query($sql);
		if($result->num_rows > 0){
			$row = $result->fetch_assoc();
			$myfirstname = $row['firstname'];
			$mylastname = $row['lastname'];
		}
		    $uploadedFile = '';
		    $uploadStatus = 1;
		    if (!empty($_FILES["dp"]["name"])) {

				$uploadDir = __DIR__ . '/../uploads/employee/';
				
			
				// Create folder if not exists
				if (!is_dir($uploadDir)) {
					mkdir($uploadDir, 0777, true);
				}
			
				// Check writable
				if (!is_writable($uploadDir)) {
					chmod($uploadDir, 0777);
				}
			
				$originalName = $_FILES["dp"]["name"];
			
				// REAL extension from uploaded file
				$fileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
			
				$newFileName = md5($mymobileno) . "m" . md5($myfirstname) . "1." . $fileType;
			
				$targetFilePath = $uploadDir . $newFileName;
			
				$allowTypes = array('jpg', 'png', 'jpeg');
			
				if (in_array($fileType, $allowTypes)) {
			
					if ($_FILES["dp"]["error"] == 0) {
			
						if (move_uploaded_file($_FILES["dp"]["tmp_name"], $targetFilePath)) {
			
							$output['uploaded'] = "true";
							$uploadedFile = $newFileName;
							$uploadStatus = 2;
							$output['response'] = 'uploaded';
			
						} else {
			
							$uploadStatus = 0;
							$output['response'] = 'move_uploaded_file_failed';
			
							// DEBUG
							$output['tmp_name'] = $_FILES["dp"]["tmp_name"];
							$output['target'] = $targetFilePath;
						}
			
					} else {
			
						$uploadStatus = 0;
						$output['response'] = 'php_upload_error';
						$output['error_code'] = $_FILES["dp"]["error"];
					}
			
				} else {
			
					$uploadStatus = 0;
					$output['response'] = 'imageextensionerror';
				}
			
				$output['uploadedstatus'] = $uploadStatus;
			}

			if($uploadStatus==0 || $uploadStatus==1){
				if($mypassword==""){
					$sql = "UPDATE emplogin SET mobileno='$mymobileno',role='Admin',email='$myemail' WHERE eid='$eid'";
				}else{
					$sql = "UPDATE emplogin SET mobileno='$mymobileno',password='$mypassword',role='Admin',email='$myemail' WHERE eid='$eid'";
				}
				$output['sql']=$sql;
				if($conn->query($sql) === TRUE){
					$output['response'] = "recordupdated";
				}else{
					$output['response'] = "error";
				}
			}else if($uploadStatus==2){
				if($mypassword==""){
					$sql = "UPDATE emplogin SET mobileno='$mymobileno',role='Admin',email='$myemail',dp='$uploadedFile' WHERE eid='$eid'";
				}else{
					$sql = "UPDATE emplogin SET mobileno='$mymobileno',password='$mypassword',role='Admin',email='$myemail',dp='$uploadedFile' WHERE eid='$eid'";
				}
				$output['sql']=$sql;
				if($conn->query($sql) === TRUE){
					$output['response'] = "recordupdated";
				}else{
					$output['response'] = "error";
				}
			}

		echo json_encode($output);
	}

	if($mytask=="addpatient"){
		$mypatientid = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['patientid'])));

		$mycategory = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['category'])));

		$myfirstname = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['firstname'])));
		$mylastname = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['lastname'])));
		$mymobileno = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['mobileno'])));
		$myalternatecontactno = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['alternatecontactno'])));
		$mydob = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['dob'])));
		$mybloodgroup = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['bloodgroup'])));
		$myoccupation = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['occupation'])));
		$mylanguagepreference = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['languagepreference'])));
		$myemail = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['email'])));
		$mystreetaddress = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['streetaddress'])));
		$mypincode = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['pincode'])));
		$mycity = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['city'])));
		$mygender = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['gender'])));

		$myupdate = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['update'])));
		$myupdateid = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['updateid'])));


		if($myupdate == "true"){
		    $uploadedFile = '';
		    $uploadStatus = 1;
		    if(!empty($_FILES["dp"]["name"])){
		        $uploadDir = '../uploads/patient/';
		        $fileName = basename($_FILES["dp"]["name"]);
		        $targetFilePath = $uploadDir . md5($mymobileno)."m".md5($myfirstname)."1".".jpg";
		        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

		        $allowTypes = array('jpg', 'png', 'jpeg');
		        if(in_array($fileType, $allowTypes)){
		            if(move_uploaded_file($_FILES["dp"]["tmp_name"], $targetFilePath)){
		                $uploadedFile = md5($mymobileno)."m".md5($myfirstname)."1".".jpg";
		                $uploadStatus = 2;
		            }else{
		                $uploadStatus = 0;
		                $output['response'] = 'imageerror!!!';
		            }
		        }else{
		            $uploadStatus = 0;
		            $output['response'] = 'imageextensionerror';
		        }
		    }

			$cid = $_SESSION['cid'];

			if($uploadStatus==0 || $uploadStatus==1){
                 $myyear = date('Y', strtotime($mydob));
                 $nowyear = date("Y");
                 $myage = $nowyear - $myyear;
				$sql = "UPDATE patientdetails SET mobileno='$mymobileno',firstname='$myfirstname',lastname='$mylastname',category='$mycategory',dob='$mydob',alternatemobileno='$myalternatecontactno',emailaddress='$myemail',streetaddress='$mystreetaddress',pincode='$mypincode',city='$mycity' , gender='$mygender' , bloodgroup='$mybloodgroup', languagepreference='$mylanguagepreference' , occupation='$myoccupation' , fullname='".$myfirstname . ' ' .$mylastname. "',age='$myage' WHERE pid='$myupdateid'";
				$output['sql']=$sql;
				if($conn->query($sql) === TRUE){
					$output['response'] = "recordupdated";
				}else{
					$output['response'] = "error";
				}
			}else if($uploadStatus==2){
				$myyear = date('Y', strtotime($mydob));
                 $nowyear = date("Y");
                 $myage = $nowyear - $myyear;
				$sql = "UPDATE patientdetails SET mobileno='$mymobileno',firstname='$myfirstname',lastname='$mylastname',category='$mycategory',dob='$mydob',alternatemobileno='$myalternatecontactno',emailaddress='$myemail',streetaddress='$mystreetaddress',pincode='$mypincode',city='$mycity' , gender='$mygender' , bloodgroup='$mybloodgroup', languagepreference='$mylanguagepreference' , occupation='$myoccupation',dp='$uploadedFile' fullname='".$myfirstname . ' ' .$mylastname. "',age='$myage' WHERE pid='$myupdateid'";
				$output['sql']=$sql;
				if($conn->query($sql) === TRUE){
					$output['response'] = "recordupdated";
				}else{
					$output['response'] = "error";
				}
			}
			echo json_encode($output);

		}else if($myupdate == "false"){

		    $uploadedFile = '';
		    $uploadStatus = 1;
		    if(!empty($_FILES["dp"]["name"])){
		        // File path config
		        $uploadDir = '../uploads/patient/';
		        $fileName = basename($_FILES["dp"]["name"]);
		        $targetFilePath = $uploadDir . md5($mymobileno)."m".md5($myfirstname)."1".".jpg";
		        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

		        // Allow certain file formats
		        $allowTypes = array('jpg', 'png', 'jpeg');
		        if(in_array($fileType, $allowTypes)){
		            // Upload file to the server
		            if(move_uploaded_file($_FILES["dp"]["tmp_name"], $targetFilePath)){
		                $uploadedFile = md5($mymobileno)."m".md5($myfirstname)."1".".jpg";
		                $uploadStatus = 1;
		            }else{
		                $uploadStatus = 0;
		                $output['response'] = 'imageerror!!!';
		            }
		        }else{
		            $uploadStatus = 0;
		            $output['response'] = 'imageextensionerror';
		        }
		    }

			$cid = $_SESSION['cid'];

			$sql = "SELECT * FROM clinicpatientlink WHERE cid='$cid' order by cpid DESC LIMIT 1";
			$result = $conn->query($sql);
			if($result->num_rows > 0){
				$row = $result->fetch_assoc();
				$pid = $row['pid'];
				$sql = "SELECT * FROM patientdetails where pid='$pid'";
				$result = $conn->query($sql);
				if($result->num_rows > 0){
					$row = $result->fetch_assoc();
					$patientidpattern = $row['patientid'];

			    	$patientidfirstcharacter = substr($patientidpattern, 0, 1);
			    	$patientidno = substr($patientidpattern, 1);
			    	$mypatientidno = $patientidno + 1;
			    	$mypatientidno = sprintf('%03d',$mypatientidno);
			    	$mypatientid = $patientidfirstcharacter . $mypatientidno;

				}else{

				}
			}else{
				$sql = "SELECT * FROM clinicoptions where cid='$cid' and optionname='patientidpattern'";
			    $result = $conn->query($sql);
			    if($result->num_rows > 0){
			    	$row = $result->fetch_assoc();
			    	$patientidpattern = $row['optionvalue'];

			    	$patientidfirstcharacter = substr($patientidpattern, 0, 1);
			    	$patientidno = substr($patientidpattern, 1);
			    	$mypatientidno = $patientidno + 1;
			    	$mypatientidno = sprintf('%03d',$mypatientidno);
			    	$mypatientid = $patientidfirstcharacter . $mypatientidno;
			    }
			}
			if($uploadStatus!=0){
				if($mydob==""){
					$myage = "";
				}else{
					$myyear = date('Y', strtotime($mydob));
	                $nowyear = date("Y");
	                $myage = $nowyear - $myyear;
				}

				$sql = "INSERT INTO patientdetails (pid,mobileno,freeze,firstname,lastname,patientid,category,dob,alternatemobileno,emailaddress,streetaddress,pincode,city,gender,bloodgroup,languagepreference,occupation,dp,fullname,age) VALUES (DEFAULT,'$mymobileno','0','$myfirstname','$mylastname','$mypatientid','$mycategory','$mydob','$myalternatecontactno','$myemail','$mystreetaddress','$mypincode','$mycity','$mygender','$mybloodgroup','$mylanguagepreference','$myoccupation','$uploadedFile','".$myfirstname . ' ' .$mylastname. "','$myage')";
				$output['sql']=$sql;
				if($conn->query($sql) === TRUE){
					$sql = "SELECT * FROM patientdetails order by pid desc limit 1";
					$result = $conn->query($sql);
					if($result->num_rows > 0){
						$row = $result->fetch_assoc();
						$lastid = $row['pid'];
						$eid = $_SESSION['duser'];
						$sql = "INSERT INTO clinicpatientlink (cpid,cid,pid,eid,freeze) VALUES (DEFAULT,'$cid','$lastid','$eid','0')";
						if($conn->query($sql) === TRUE){
							$output['response'] = "recordadded";
							$output['imgsource'] = $uploadedFile;
						}else{
							$output['response'] = "error";
						}
					}
				}else{
					$output['response'] = "error";
				}
			}
			echo json_encode($output);

		}
	}

	if($mytask == "deletepatient"){
		$myeid = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['eid'])));
		$mycid = $_SESSION['cid'];

		$sql = "DELETE FROM patientdetails where pid='$myeid'";
		if($conn->query($sql) === TRUE){
			$sql = "DELETE FROM clinicpatientlink where pid='$myeid' and cid='$mycid'";
			if($conn->query($sql) === TRUE){
				$output['response'] = "recorddeleted";
			}else{
				$output['response'] = "error";
			}
		}else{
			$output['response'] = "error";
		}

		echo json_encode($output);
	}

	if($mytask == "refreshpatientlist"){
		$output['list'] = '<div class="col-sm-12">
	         <div class="iq-card">
	            <div class="iq-card-body d-flex justify-content-between" id="">
	               <div class="table-responsive" style="max-width:100%;">
	                  <table id="patienttable" class="table table-striped table-bordered" style="max-width:100%;width:100%;cursor:pointer">
	                     <thead>
	                        <tr>
	                           <th width="3%"></th>
	                           <th width="7%">Sr. No</th>
	                           <th width="25%">Name</th>
	                           <th width="10%">Gender</th>
	                           <th width="10%">Mobile No.</th>
	                           <th width="10%">City</th>
	                           <th width="15%">Status</th>
	                           <th width="20%">Options</th>
	                           <th style="display:none;"></th>
	                        </tr>
	                     </thead>
	                     <tbody>';
	                     	$mycid = $_SESSION['cid'];
	                     	$eid = $_SESSION['duser'];
	                     	$sql = "select * from emplogin where eid='$eid'";
	                     	$result = $conn->query($sql);
	                     	if($result->num_rows > 0){
	                     		$row =$result->fetch_assoc();
	                     		$role = $row['role'];
	                     		if($role == "Admin"){
	                     			$sql = "SELECT * FROM clinicpatientlink WHERE cid='$mycid'";	
	                     		}else{
	                     			$sql = "SELECT * FROM clinicpatientlink WHERE cid='$mycid' and eid='$eid'";	
	                     		}
		                     	$result1 = $conn->query($sql);
		                        if($result1->num_rows >0){
		                        	$pno = 0;
		                        	while($row1=$result1->fetch_assoc()){
		                        		$pno++;
		                        		$mypid = $row1['pid'];
				                        $sql = "SELECT * FROM patientdetails WHERE pid='$mypid'";
				                        $result = $conn->query($sql);
				                        if($result->num_rows >0){
					                          $row = $result->fetch_assoc();
				                              $output['list'] = $output['list'].'
				                              <tr>
				                                 <td></td>
				                                 <td>'.$pno.'</td>
				                                 <td>'.$row['firstname']. ' '. $row['lastname'].'</td>
				                                 <td>'.$row['gender'].'</td>
				                                 <td>'.$row['mobileno'].'</td>
				                                 <td>'.$row['city'].'</td>
				                                 <td>';
				                                 if($row['freeze']=="0"){
				                                 	$output['list'] = $output['list']. "Active";
				                                 }else{
				                                 	$output['list'] = $output['list']. "Deactive";
				                                 }
				                                 $output['list'] = $output['list'].'</td>
				                                 <td><button onclick="editpatient('.$row['pid'].')" type="button" style="" class="edit btn btn-primary mb-3">Edit</button>&nbsp&nbsp<button type="button" onclick="setdeleteaction('.$row['pid'].');" class="delete btn btn-danger mb-3" data-toggle="modal" data-target=".deletemodal">Delete</button></td>
				                                 <td style="display:none;">'.$row['pid'].'</td>
				                              </tr>';

				                        }
		                        	}
		                        }
	                     	}else{

	                     	}
	                     $output['list'] = $output['list'].'</tbody>
	                  </table>
	               </div>
	            </div>
	         </div>
	      </div>';

		echo $output['list'];
	}


	if($mytask=="addblog"){
		ensure_blog_relation_tables($conn);
		$blogtable = "blogs";
		$mytitle = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['title'])));
		$rawcontent = $_POST['content'];
		$myshortdescription = mysqli_real_escape_string($conn, blog_short_description($rawcontent));
		$mycontent = trim(mysqli_real_escape_string($conn,$rawcontent));
		$mycreatedat = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['created_at'])));
		$myupdate = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['update'])));
		$myupdateid = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['updateid'])));
		$mycategories = decode_blog_values($_POST['categories']);
		$mytags = decode_blog_values($_POST['tags']);
		$mydate = blog_datetime($mycreatedat);
		$myupdatedate = date("Y-m-d H:i:s");
		$myslug = make_unique_blog_slug($conn, $blogtable, make_blog_slug($mytitle), $myupdateid);
		$mymetatitle = $mytitle;
		$mymetadescription = $myshortdescription;

		$uploadedFile = '';
		$uploadStatus = 1;
		if(!empty($_FILES["featured_image"]["name"])){
			$uploadDir = __DIR__ . '/../uploads/blog/';
			if(!is_dir($uploadDir)){
				mkdir($uploadDir, 0777, true);
			}
			if(!is_writable($uploadDir)){
				chmod($uploadDir, 0777);
			}

			$originalName = $_FILES["featured_image"]["name"];
			$fileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
			$newFileName = md5($mytitle . date("YmdHis") . rand(1000,9999)) . "." . $fileType;
			$targetFilePath = $uploadDir . $newFileName;
			$allowTypes = array('jpg', 'png', 'jpeg', 'webp');

			if(in_array($fileType, $allowTypes)){
				if($_FILES["featured_image"]["error"] == 0){
					if(move_uploaded_file($_FILES["featured_image"]["tmp_name"], $targetFilePath)){
						$uploadedFile = $newFileName;
						$uploadStatus = 2;
					}else{
						$uploadStatus = 0;
						$output['response'] = 'imageerror';
						$output['upload_error'] = 'move_uploaded_file_failed';
						$output['target'] = $targetFilePath;
					}
				}else{
					$uploadStatus = 0;
					$output['response'] = 'imageerror';
					$output['upload_error'] = $_FILES["featured_image"]["error"];
				}
			}else{
				$uploadStatus = 0;
				$output['response'] = 'imageextensionerror';
			}
		}

		if($uploadStatus!=0){
			if($myupdate == "true"){
				if($uploadStatus==2){
					$sql = "UPDATE $blogtable SET title='$mytitle',slug='$myslug',short_description='$myshortdescription',content='$mycontent',featured_image='$uploadedFile',meta_title='$mymetatitle',meta_description='$mymetadescription',created_at='$mydate',updated_at='$myupdatedate' WHERE blogid='$myupdateid'";
				}else{
					$sql = "UPDATE $blogtable SET title='$mytitle',slug='$myslug',short_description='$myshortdescription',content='$mycontent',meta_title='$mymetatitle',meta_description='$mymetadescription',created_at='$mydate',updated_at='$myupdatedate' WHERE blogid='$myupdateid'";
				}
				$output['sql']=$sql;
				if($conn->query($sql) === TRUE){
					replace_blog_terms($conn, $myupdateid, $mycategories, $mytags);
					$output['response'] = "recordupdated";
				}else{
					$output['response'] = "error";
				}
			}else{
				$sql = "INSERT INTO $blogtable (blogid,title,slug,short_description,content,featured_image,meta_title,meta_description,status,views,created_at,updated_at) VALUES (DEFAULT,'$mytitle','$myslug','$myshortdescription','$mycontent','$uploadedFile','$mymetatitle','$mymetadescription','published','0','$mydate','$myupdatedate')";
				$output['sql']=$sql;
				if($conn->query($sql) === TRUE){
					$lastid = $conn->insert_id;
					replace_blog_terms($conn, $lastid, $mycategories, $mytags);
					$output['response'] = "recordadded";
					$output['imgsource'] = $uploadedFile;
				}else{
					$output['response'] = "error";
				}
			}
		}
		echo json_encode($output);
	}

	if($mytask == "refreshbloglist"){
		ensure_blog_relation_tables($conn);
		$blogtable = "blogs";
		$output['list'] = '<div class="col-sm-12">
	         <div class="iq-card">
	            <div class="iq-card-body d-flex justify-content-between" id="">
	               <div class="table-responsive" style="max-width:100%;">
	                  <table id="blogtable" class="table table-striped table-bordered" style="max-width:100%;width:100%;cursor:pointer">
	                     <thead>
	                        <tr>
	                           <th width="3%"></th>
	                           <th width="7%">Sr. No</th>
	                           <th width="20%">Image</th>
	                           <th width="25%">Title</th>
	                           <th width="15%">Categories</th>
	                           <th width="15%">Tags</th>
	                           <th width="15%">Date</th>
	                           <th width="10%">Status</th>
	                           <th width="10%">Views</th>
	                           <th width="20%">Options</th>
	                           <th style="display:none;"></th>
	                        </tr>
	                     </thead>
	                     <tbody>';
		$sql = "SELECT * FROM $blogtable order by blogid DESC";
		$result = $conn->query($sql);
		if($result && $result->num_rows > 0){
			$pno = 0;
			while($row=$result->fetch_assoc()){
				$pno++;
				$blogid = $row['blogid'];
				$title = htmlspecialchars($row['title'], ENT_QUOTES);
				$status = htmlspecialchars($row['status'], ENT_QUOTES);
				$views = htmlspecialchars($row['views'], ENT_QUOTES);
				$createdat = $row['created_at'];
				$categorylist = htmlspecialchars(implode(", ", get_blog_terms($conn, $blogid, 'blogcategories', 'blogcategoryid', 'category', 'blogcategorylink')), ENT_QUOTES);
				$taglist = htmlspecialchars(implode(", ", get_blog_terms($conn, $blogid, 'blogtags', 'blogtagid', 'tag', 'blogtaglink')), ENT_QUOTES);
				$image = $row['featured_image'];
				if($image==""){
					$imagehtml = '<img src="../assets/images/blog-placeholder.png" style="height:55px;width:55px;object-fit:cover;border-radius:4px;">';
				}else{
					$imagehtml = '<img src="../uploads/blog/'.$image.'" style="height:55px;width:80px;object-fit:cover;border-radius:4px;">';
				}
				$output['list'] = $output['list'].'
				<tr>
					<td></td>
					<td>'.$pno.'</td>
					<td>'.$imagehtml.'</td>
					<td>'.$title.'</td>
					<td>'.$categorylist.'</td>
					<td>'.$taglist.'</td>
					<td>'.$createdat.'</td>
					<td>'.$status.'</td>
					<td>'.$views.'</td>
					<td><button onclick="editblog('.$blogid.')" type="button" style="" class="edit btn btn-primary mb-3">Edit</button>&nbsp&nbsp<button type="button" onclick="setdeleteaction('.$blogid.');" class="delete btn btn-danger mb-3" data-toggle="modal" data-target=".deletemodal">Delete</button></td>
					<td style="display:none;">'.$blogid.'</td>
				</tr>';
			}
		}
		$output['list'] = $output['list'].'</tbody>
	                  </table>
	               </div>
	            </div>
	         </div>
	      </div>';

		echo $output['list'];
	}

	if($mytask == "getblogdetails"){
		$blogtable = "blogs";
		$myblogid = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['blogid'])));
		$sql = "SELECT * FROM $blogtable WHERE blogid='$myblogid'";
		$result = $conn->query($sql);
		if($result && $result->num_rows > 0){
			$row = $result->fetch_assoc();
			$output = $row;
			$output['created_at_input'] = "";
			if($row['created_at']!=""){
				$output['created_at_input'] = date("Y-m-d\TH:i", strtotime($row['created_at']));
			}
			$output['categories'] = get_blog_terms($conn, $myblogid, 'blogcategories', 'blogcategoryid', 'category', 'blogcategorylink');
			$output['tags'] = get_blog_terms($conn, $myblogid, 'blogtags', 'blogtagid', 'tag', 'blogtaglink');
			$output['response'] = "true";
		}else{
			$output['response'] = "false";
		}
		echo json_encode($output);
	}

	if($mytask == "deleteblog"){
		ensure_blog_relation_tables($conn);
		$blogtable = "blogs";
		$myblogid = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['blogid'])));
		$sql = "DELETE FROM $blogtable where blogid='$myblogid'";
		if($conn->query($sql) === TRUE){
			$conn->query("DELETE FROM blogcategorylink WHERE blogid='$myblogid'");
			$conn->query("DELETE FROM blogtaglink WHERE blogid='$myblogid'");
			$output['response'] = "recorddeleted";
		}else{
			$output['response'] = "error";
		}
		echo json_encode($output);
	}

	if($mytask == "deleteblogall"){
		ensure_blog_relation_tables($conn);
		$blogtable = "blogs";
		$myblogids = $_POST['blogids'];
		$noofrecordsdeleted = 0;
		if(is_array($myblogids)){
			foreach($myblogids as $myblogid){
				$myblogid = strip_tags(trim(mysqli_real_escape_string($conn,$myblogid)));
				$sql = "DELETE FROM $blogtable where blogid='$myblogid'";
				if($conn->query($sql) === TRUE){
					$conn->query("DELETE FROM blogcategorylink WHERE blogid='$myblogid'");
					$conn->query("DELETE FROM blogtaglink WHERE blogid='$myblogid'");
					$noofrecordsdeleted++;
				}
			}
		}
		$output['response'] = "recordsdeleted";
		$output['noofrecordsdeleted'] = $noofrecordsdeleted;
		echo json_encode($output);
	}

	if($mytask == "getblogoptions"){
		ensure_blog_relation_tables($conn);
		$output['categories'] = array();
		$output['tags'] = array();
		$sql = "SELECT category FROM blogcategories ORDER BY category";
		$result = $conn->query($sql);
		if($result && $result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$output['categories'][] = $row['category'];
			}
		}
		$sql = "SELECT tag FROM blogtags ORDER BY tag";
		$result = $conn->query($sql);
		if($result && $result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$output['tags'][] = $row['tag'];
			}
		}
		$output['response'] = "true";
		echo json_encode($output);
	}

	if($mytask=="getpatientnames"){
		$myfield = strip_tags(trim(mysqli_real_escape_string($conn,$_POST['field'])));
		$output['list']="";
		$mycid = $_SESSION['cid'];
		$eid = $_SESSION['duser'];
	 	$sql = "select * from emplogin where eid='$eid'";
	 	$result = $conn->query($sql);
	 	if($result->num_rows > 0){
	 		$row =$result->fetch_assoc();
	 		$role = $row['role'];
	 		if($role == "Admin"){
	 			$sql = "SELECT * FROM clinicpatientlink WHERE cid='$mycid'";	
	 		}else{
	 			$sql = "SELECT * FROM clinicpatientlink WHERE cid='$mycid' and eid='$eid'";	
	 		}

	 		$result = $conn->query($sql);
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$mypid = $row['pid'];
					$sql = "SELECT * FROM patientdetails WHERE pid='$mypid' AND fullname LIKE '%".$myfield."%' ";
					$output['sql'] = $sql;
					$result1=$conn->query($sql);
					if($result1->num_rows > 0){
						$row1 = $result1->fetch_assoc();
						$output['list'] = $output['list'] . '
						<li class="list-group-item contsearch">
					    	<a href="javascript:void(0)" class="gsearch" style="color:#333;text-decoration:none;">'.$row1['firstname'] . " " . $row1['lastname'].'<span style="display:none;">'.$row1['pid'].'</span><span style="display:none;">'.$row1['gender'].'</span><span style="display:none;">';
					    		if($row1['dob']!="0000-00-00"){
							    	$mydob = $row1['dob'];
							    	$myyear = date('Y', strtotime($mydob));
							    	$nowyear = date("Y");
							    	$myage = $nowyear - $myyear;
					    		}else{
					    			$myage = "";
					    		}
					    	$output['list'] = $output['list'] . $myage;
					    	$output['list'] = $output['list'] . '</span><span style="display:none;">'.$row1['mobileno'].'</span><span style="display:none;">'.$row1['emailaddress'].'</span><span style="display:none;">'.$row1['city'].'</span><span style="display:none;">'.$row1['dp'].'</span></a>
					    </li>';

					}
				}
			}
	 	}else{

	 	}

		echo $output['list'];
	}

}
 ?>
