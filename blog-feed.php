<?php
include_once('admin/includes/dbcon.php');
header('Content-Type: application/json; charset=utf-8');

function clean_output($value){
   return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function get_terms($conn, $blogid, $mastertable, $masterid, $namefield, $linktable){
   $items = array();
   $blogid = mysqli_real_escape_string($conn, $blogid);
   $sql = "SELECT m.$namefield FROM $mastertable m INNER JOIN $linktable l ON m.$masterid=l.$masterid WHERE l.blogid='$blogid' ORDER BY m.$namefield";
   $result = $conn->query($sql);
   if($result && $result->num_rows > 0){
      while($row = $result->fetch_assoc()){
         $items[] = $row[$namefield];
      }
   }
   return $items;
}

function search_date_value($search){
   $search = trim($search);
   if($search==""){
      return "";
   }

   $monthnames = 'january|jan|february|feb|march|mar|april|apr|may|june|jun|july|jul|august|aug|september|sep|october|oct|november|nov|december|dec';
   $looksLikeDate = preg_match('/^\d{4}-\d{2}-\d{2}$/', $search) || preg_match('/\b\d{1,2}\b.*\b('.$monthnames.')\b.*\b\d{4}\b/i', $search) || preg_match('/\b\d{1,2}[\/-]\d{1,2}[\/-]\d{4}\b/', $search);
   if(!$looksLikeDate){
      return "";
   }

   $formats = array('j M Y', 'd M Y', 'j F Y', 'd F Y', 'Y-m-d', 'd-m-Y', 'd/m/Y');
   foreach($formats as $format){
      $date = DateTime::createFromFormat($format, $search);
      if($date){
         return $date->format('Y-m-d');
      }
   }

   $timestamp = strtotime($search);
   if($timestamp !== false && preg_match('/\d/', $search)){
      return date('Y-m-d', $timestamp);
   }

   return "";
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 6;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;
$tag = isset($_GET['tag']) ? intval($_GET['tag']) : 0;
$datefilter = isset($_GET['date']) ? trim($_GET['date']) : '';

$where = "(b.status='published' OR b.status='Active')";

if($search!=""){
   $searchsafe = mysqli_real_escape_string($conn, $search);
   $datesearch = search_date_value($search);
   $datecondition = "";
   if($datesearch!=""){
      $datesafe = mysqli_real_escape_string($conn, $datesearch);
      $datecondition = " OR DATE(b.created_at)='$datesafe'";
   }
   $where .= " AND (b.title LIKE '%$searchsafe%' OR b.short_description LIKE '%$searchsafe%' OR b.content LIKE '%$searchsafe%'$datecondition)";
}

if($category>0){
   $where .= " AND EXISTS (SELECT 1 FROM blogcategorylink bcl WHERE bcl.blogid=b.blogid AND bcl.blogcategoryid='$category')";
}

if($tag>0){
   $where .= " AND EXISTS (SELECT 1 FROM blogtaglink btl WHERE btl.blogid=b.blogid AND btl.blogtagid='$tag')";
}

if($datefilter!="" && preg_match('/^\d{4}-\d{2}-\d{2}$/', $datefilter)){
   $datefiltersafe = mysqli_real_escape_string($conn, $datefilter);
   $where .= " AND DATE(b.created_at)='$datefiltersafe'";
}

$sql = "SELECT b.* FROM blogs b WHERE $where ORDER BY b.created_at DESC, b.blogid DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$cards = '';
$count = 0;

if($result && $result->num_rows > 0){
   while($row = $result->fetch_assoc()){
      $count++;
      $image = $row['featured_image'] ? 'admin/uploads/blog/'.$row['featured_image'] : 'admin/assets/images/blog-placeholder.png';
      $date = $row['created_at'] ? date('M d, Y', strtotime($row['created_at'])) : '';
      $categories = get_terms($conn, $row['blogid'], 'blogcategories', 'blogcategoryid', 'category', 'blogcategorylink');
      $link = 'blogdetails/' . urlencode($row['slug']);

      $cards .= '<article class="blog-card">
         <a class="card-image" href="'.$link.'">
            <img src="'.clean_output($image).'" alt="'.clean_output($row['title']).'">
         </a>
         <div class="card-body">
            <div class="card-meta">
               <span>'.clean_output($date).'</span>
               <span>'.intval($row['views']).' views</span>
            </div>
            <a href="'.$link.'" class="card-title">'.clean_output($row['title']).'</a>
            <p>'.clean_output($row['short_description']).'</p>
            <div class="chip-row">';
               foreach(array_slice($categories, 0, 2) as $categoryname){
                  $cards .= '<span class="chip">'.clean_output($categoryname).'</span>';
               }
      $cards .= '</div>
            <a class="read-more" href="'.$link.'">Read More</a>
         </div>
      </article>';
   }
}

echo json_encode(array(
   'html' => $cards,
   'hasMore' => $count == $limit,
   'count' => $count
));
?>
