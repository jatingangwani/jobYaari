<?php include('../created/header.php'); ?>
<?php include('../created/sidebar.php'); ?>
<?php include('../created/pageheader.php'); ?>

<?php
date_default_timezone_set('Asia/Calcutta');

function jy_table_exists($conn, $table)
{
   $table = mysqli_real_escape_string($conn, $table);
   $result = $conn->query("SHOW TABLES LIKE '$table'");
   return $result && $result->num_rows > 0;
}

function jy_column_exists($conn, $table, $column)
{
   $table = mysqli_real_escape_string($conn, $table);
   $column = mysqli_real_escape_string($conn, $column);
   $result = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
   return $result && $result->num_rows > 0;
}

function jy_scalar($conn, $sql, $fallback = 0)
{
   $result = $conn->query($sql);
   if($result && $row = $result->fetch_assoc()){
      $value = array_values($row)[0];
      return $value === null ? $fallback : $value;
   }
   return $fallback;
}

function jy_rows($conn, $sql)
{
   $rows = array();
   $result = $conn->query($sql);
   if($result && $result->num_rows > 0){
      while($row = $result->fetch_assoc()){
         $rows[] = $row;
      }
   }
   return $rows;
}

function jy_text($value)
{
   return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function jy_number($value)
{
   return number_format((float)$value);
}

function jy_percent($part, $total)
{
   if((float)$total <= 0){
      return 0;
   }
   return round(((float)$part * 100) / (float)$total);
}

function jy_short($value, $limit = 78)
{
   $value = trim(strip_tags((string)$value));
   if(function_exists('mb_strlen') && mb_strlen($value, 'UTF-8') > $limit){
      return mb_substr($value, 0, $limit - 3, 'UTF-8') . '...';
   }
   if(!function_exists('mb_strlen') && strlen($value) > $limit){
      return substr($value, 0, $limit - 3) . '...';
   }
   return $value;
}

function jy_chart_data($rows, $labelField, $valueField)
{
   $labels = array();
   $values = array();
   foreach($rows as $row){
      $labels[] = isset($row[$labelField]) && $row[$labelField] !== '' ? $row[$labelField] : 'Unassigned';
      $values[] = isset($row[$valueField]) ? (int)$row[$valueField] : 0;
   }
   return array('labels' => $labels, 'values' => $values);
}

$hasBlogs = jy_table_exists($conn, 'blogs');
$hasCategories = jy_table_exists($conn, 'blogcategories') && jy_table_exists($conn, 'blogcategorylink');
$hasTags = jy_table_exists($conn, 'blogtags') && jy_table_exists($conn, 'blogtaglink');
$hasEmployees = jy_table_exists($conn, 'emplogin');

$publishedWhere = "(status='published' OR status='Active')";
$today = date('Y-m-d');
$monthStart = date('Y-m-01');
$lastMonthStart = date('Y-m-01', strtotime('-1 month'));
$lastMonthEnd = date('Y-m-t', strtotime('-1 month'));
$sixMonthStart = date('Y-m-01', strtotime('-5 months'));

$totalBlogs = $hasBlogs ? (int)jy_scalar($conn, "SELECT COUNT(*) FROM blogs", 0) : 0;
$publishedBlogs = $hasBlogs ? (int)jy_scalar($conn, "SELECT COUNT(*) FROM blogs WHERE $publishedWhere", 0) : 0;
$draftBlogs = max(0, $totalBlogs - $publishedBlogs);
$monthBlogs = $hasBlogs ? (int)jy_scalar($conn, "SELECT COUNT(*) FROM blogs WHERE DATE(created_at) BETWEEN '$monthStart' AND '$today'", 0) : 0;
$lastMonthBlogs = $hasBlogs ? (int)jy_scalar($conn, "SELECT COUNT(*) FROM blogs WHERE DATE(created_at) BETWEEN '$lastMonthStart' AND '$lastMonthEnd'", 0) : 0;
$todayBlogs = $hasBlogs ? (int)jy_scalar($conn, "SELECT COUNT(*) FROM blogs WHERE DATE(created_at)='$today'", 0) : 0;
$totalViews = $hasBlogs && jy_column_exists($conn, 'blogs', 'views') ? (int)jy_scalar($conn, "SELECT COALESCE(SUM(views),0) FROM blogs", 0) : 0;
$monthViews = $hasBlogs && jy_column_exists($conn, 'blogs', 'views') ? (int)jy_scalar($conn, "SELECT COALESCE(SUM(views),0) FROM blogs WHERE DATE(created_at) BETWEEN '$monthStart' AND '$today'", 0) : 0;
$categoryCount = $hasCategories ? (int)jy_scalar($conn, "SELECT COUNT(*) FROM blogcategories", 0) : 0;
$tagCount = $hasTags ? (int)jy_scalar($conn, "SELECT COUNT(*) FROM blogtags", 0) : 0;
$adminCount = $hasEmployees ? (int)jy_scalar($conn, "SELECT COUNT(*) FROM emplogin WHERE freeze='0'", 0) : 0;

$monthChange = $lastMonthBlogs > 0 ? round((($monthBlogs - $lastMonthBlogs) * 100) / $lastMonthBlogs) : ($monthBlogs > 0 ? 100 : 0);
$publishRate = jy_percent($publishedBlogs, $totalBlogs);
$draftRate = jy_percent($draftBlogs, $totalBlogs);
$avgViews = $publishedBlogs > 0 ? round($totalViews / $publishedBlogs) : 0;

$monthlyRows = $hasBlogs ? jy_rows($conn, "
   SELECT DATE_FORMAT(created_at, '%b %Y') label, COUNT(*) total, COALESCE(SUM(views),0) views
   FROM blogs
   WHERE DATE(created_at) >= '$sixMonthStart'
   GROUP BY YEAR(created_at), MONTH(created_at)
   ORDER BY YEAR(created_at), MONTH(created_at)
") : array();

$categoryRows = $hasCategories ? jy_rows($conn, "
   SELECT c.category label, COUNT(l.blogid) total
   FROM blogcategories c
   LEFT JOIN blogcategorylink l ON c.blogcategoryid=l.blogcategoryid
   LEFT JOIN blogs b ON l.blogid=b.blogid
   GROUP BY c.blogcategoryid, c.category
   ORDER BY total DESC, c.category ASC
   LIMIT 6
") : array();

$tagRows = $hasTags ? jy_rows($conn, "
   SELECT t.tag label, COUNT(l.blogid) total
   FROM blogtags t
   LEFT JOIN blogtaglink l ON t.blogtagid=l.blogtagid
   LEFT JOIN blogs b ON l.blogid=b.blogid
   GROUP BY t.blogtagid, t.tag
   ORDER BY total DESC, t.tag ASC
   LIMIT 10
") : array();

$topBlogs = $hasBlogs ? jy_rows($conn, "
   SELECT blogid, title, slug, featured_image, status, views, created_at, short_description
   FROM blogs
   ORDER BY views DESC, created_at DESC, blogid DESC
   LIMIT 5
") : array();

$recentBlogs = $hasBlogs ? jy_rows($conn, "
   SELECT blogid, title, slug, featured_image, status, views, created_at, short_description
   FROM blogs
   ORDER BY created_at DESC, blogid DESC
   LIMIT 6
") : array();

$statusRows = array(
   array('label' => 'Published', 'total' => $publishedBlogs),
   array('label' => 'Draft/Other', 'total' => $draftBlogs)
);

$monthlyChart = array(
   'labels' => array(),
   'blogs' => array(),
   'views' => array()
);
foreach($monthlyRows as $row){
   $monthlyChart['labels'][] = $row['label'];
   $monthlyChart['blogs'][] = (int)$row['total'];
   $monthlyChart['views'][] = (int)$row['views'];
}

if(count($monthlyChart['labels']) == 0){
   $monthlyChart['labels'] = array(date('M Y'));
   $monthlyChart['blogs'] = array(0);
   $monthlyChart['views'] = array(0);
}

$categoryChart = jy_chart_data($categoryRows, 'label', 'total');
$statusChart = jy_chart_data($statusRows, 'label', 'total');
?>

<style type="text/css">
   .jy-dashboard-hero {
      position: relative;
      overflow: hidden;
      background: linear-gradient(135deg, #12263f 0%, #0b7285 52%, #2f9e44 100%);
      border-radius: 8px;
      color: #fff;
      padding: 28px;
      box-shadow: 0 18px 35px rgba(18, 38, 63, 0.16);
   }
   .jy-dashboard-hero:after {
      content: "";
      position: absolute;
      right: -80px;
      top: -90px;
      width: 280px;
      height: 280px;
      border: 42px solid rgba(255, 255, 255, 0.1);
      border-radius: 50%;
   }
   .jy-dashboard-hero h2,
   .jy-dashboard-hero p,
   .jy-dashboard-hero a,
   .jy-dashboard-hero div {
      position: relative;
      z-index: 1;
   }
   .jy-kpi-card {
      border: 0;
      border-radius: 8px;
      overflow: hidden;
      transition: transform .18s ease, box-shadow .18s ease;
   }
   .jy-kpi-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 18px 34px rgba(21, 34, 50, 0.12);
   }
   .jy-kpi-icon {
      width: 48px;
      height: 48px;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
   }
   .jy-muted-label {
      color: #6c757d;
      font-size: 12px;
      letter-spacing: .04em;
      text-transform: uppercase;
      font-weight: 700;
   }
   .jy-action-button {
      min-height: 84px;
      border: 1px solid #e7edf3;
      border-radius: 8px;
      padding: 14px;
      display: flex;
      align-items: center;
      gap: 12px;
      color: #263238;
      background: #fff;
      transition: all .18s ease;
   }
   .jy-action-button:hover {
      color: #0b7285;
      border-color: #0b7285;
      transform: translateY(-2px);
      text-decoration: none;
   }
   .jy-action-button i {
      font-size: 24px;
   }
   .jy-post-thumb {
      width: 58px;
      height: 46px;
      border-radius: 6px;
      object-fit: cover;
      background: #edf2f7;
   }
   .jy-status-pill {
      border-radius: 999px;
      padding: 5px 10px;
      font-size: 12px;
      font-weight: 700;
   }
   .jy-progress {
      height: 8px;
      border-radius: 999px;
      background: #edf2f7;
      overflow: hidden;
   }
   .jy-progress span {
      display: block;
      height: 100%;
      border-radius: 999px;
   }
   .jy-report-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 14px;
      padding: 13px 0;
      border-bottom: 1px solid #eef2f5;
   }
   .jy-report-row:last-child {
      border-bottom: 0;
   }
   .jy-empty-state {
      min-height: 170px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      color: #6c757d;
      background: #f8fafc;
      border-radius: 8px;
      border: 1px dashed #d9e2ec;
   }
   @media (max-width: 767px) {
      .jy-dashboard-hero {
         padding: 22px;
      }
      .jy-dashboard-hero h2 {
         font-size: 24px;
      }
      .jy-action-button {
         min-height: 72px;
      }
   }
</style>

<div class="container-fluid">
   <div class="row">
      <div class="col-lg-12">
         <div class="jy-dashboard-hero mb-4">
            <div class="row align-items-center">
               <div class="col-lg-8">
                  <span class="badge badge-light mb-3">Admin Dashboard</span>
                  <h2 class="mb-2 text-white">Job Yaari Content Command Center</h2>
                  <p class="mb-0 text-white-50">Track blog publishing, audience views, taxonomy coverage, and recent activity from one interactive report page.</p>
               </div>
               <div class="col-lg-4 mt-4 mt-lg-0 text-lg-right">
                  <a href="addblog.php" class="btn btn-light mr-2 mb-2"><i class="ri-add-line mr-1"></i>Add Blog</a>
                  <a href="bloglist.php" class="btn btn-outline-light mb-2"><i class="ri-file-list-3-line mr-1"></i>All Blogs</a>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-md-6 col-xl-3">
         <div class="iq-card jy-kpi-card">
            <div class="iq-card-body">
               <div class="d-flex justify-content-between align-items-start">
                  <div>
                     <span class="jy-muted-label">Total Blogs</span>
                     <h3 class="mt-2 mb-1 counter"><?php echo jy_number($totalBlogs); ?></h3>
                     <small class="text-muted"><?php echo jy_number($todayBlogs); ?> added today</small>
                  </div>
                  <div class="jy-kpi-icon iq-bg-primary"><i class="ri-article-line text-primary"></i></div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-6 col-xl-3">
         <div class="iq-card jy-kpi-card">
            <div class="iq-card-body">
               <div class="d-flex justify-content-between align-items-start">
                  <div>
                     <span class="jy-muted-label">Published</span>
                     <h3 class="mt-2 mb-1 counter"><?php echo jy_number($publishedBlogs); ?></h3>
                     <small class="text-muted"><?php echo $publishRate; ?>% publication rate</small>
                  </div>
                  <div class="jy-kpi-icon iq-bg-success"><i class="ri-checkbox-circle-line text-success"></i></div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-6 col-xl-3">
         <div class="iq-card jy-kpi-card">
            <div class="iq-card-body">
               <div class="d-flex justify-content-between align-items-start">
                  <div>
                     <span class="jy-muted-label">Total Views</span>
                     <h3 class="mt-2 mb-1 counter"><?php echo jy_number($totalViews); ?></h3>
                     <small class="text-muted"><?php echo jy_number($avgViews); ?> avg per published blog</small>
                  </div>
                  <div class="jy-kpi-icon iq-bg-info"><i class="ri-eye-line text-info"></i></div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-6 col-xl-3">
         <div class="iq-card jy-kpi-card">
            <div class="iq-card-body">
               <div class="d-flex justify-content-between align-items-start">
                  <div>
                     <span class="jy-muted-label">This Month</span>
                     <h3 class="mt-2 mb-1 counter"><?php echo jy_number($monthBlogs); ?></h3>
                     <small class="<?php echo $monthChange >= 0 ? 'text-success' : 'text-danger'; ?>">
                        <?php echo $monthChange >= 0 ? '+' : ''; ?><?php echo $monthChange; ?>% vs last month
                     </small>
                  </div>
                  <div class="jy-kpi-icon iq-bg-warning"><i class="ri-calendar-event-line text-warning"></i></div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-lg-8">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between align-items-center">
               <div class="iq-header-title">
                  <h4 class="card-title">Publishing & Views Report</h4>
               </div>
               <span class="badge badge-primary">Last 6 months</span>
            </div>
            <div class="iq-card-body">
               <div id="jy-publishing-chart" style="min-height: 330px;"></div>
            </div>
         </div>
      </div>
      <div class="col-lg-4">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between align-items-center">
               <div class="iq-header-title">
                  <h4 class="card-title">Status Report</h4>
               </div>
               <span class="badge badge-light"><?php echo $totalBlogs; ?> records</span>
            </div>
            <div class="iq-card-body">
               <div id="jy-status-chart" style="min-height: 250px;"></div>
               <div class="mt-3">
                  <div class="d-flex justify-content-between mb-2">
                     <span>Published</span>
                     <b><?php echo $publishRate; ?>%</b>
                  </div>
                  <div class="jy-progress mb-3"><span style="width:<?php echo $publishRate; ?>%;background:#2f9e44;"></span></div>
                  <div class="d-flex justify-content-between mb-2">
                     <span>Draft/Other</span>
                     <b><?php echo $draftRate; ?>%</b>
                  </div>
                  <div class="jy-progress"><span style="width:<?php echo $draftRate; ?>%;background:#f59f00;"></span></div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-xl-4">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title">
                  <h4 class="card-title">Quick Actions</h4>
               </div>
            </div>
            <div class="iq-card-body">
               <div class="row">
                  <div class="col-sm-6 mb-3">
                     <a class="jy-action-button" href="addblog.php">
                        <i class="ri-add-circle-line"></i>
                        <span><b>Add Blog</b><br><small>Publish a new article</small></span>
                     </a>
                  </div>
                  <div class="col-sm-6 mb-3">
                     <a class="jy-action-button" href="bloglist.php">
                        <i class="ri-file-list-3-line"></i>
                        <span><b>Manage Blogs</b><br><small>Edit or delete posts</small></span>
                     </a>
                  </div>
                  <div class="col-sm-6 mb-3">
                     <a class="jy-action-button" href="editprofile.php">
                        <i class="ri-user-settings-line"></i>
                        <span><b>Profile</b><br><small>Update admin details</small></span>
                     </a>
                  </div>
                  <div class="col-sm-6 mb-3">
                     <a class="jy-action-button" href="../../index.php" target="_blank">
                        <i class="ri-external-link-line"></i>
                        <span><b>View Site</b><br><small>Open public blog</small></span>
                     </a>
                  </div>
               </div>
               <hr>
               <div class="row text-center">
                  <div class="col-4">
                     <h4 class="mb-0"><?php echo jy_number($categoryCount); ?></h4>
                     <small class="text-muted">Categories</small>
                  </div>
                  <div class="col-4">
                     <h4 class="mb-0"><?php echo jy_number($tagCount); ?></h4>
                     <small class="text-muted">Tags</small>
                  </div>
                  <div class="col-4">
                     <h4 class="mb-0"><?php echo jy_number($adminCount); ?></h4>
                     <small class="text-muted">Admins</small>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-xl-4">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between align-items-center">
               <div class="iq-header-title">
                  <h4 class="card-title">Category Report</h4>
               </div>
               <span class="badge badge-info">Top 6</span>
            </div>
            <div class="iq-card-body">
               <?php if(count($categoryRows) > 0){ ?>
                  <div id="jy-category-chart" style="min-height: 230px;"></div>
                  <?php foreach($categoryRows as $category){ 
                     $width = jy_percent($category['total'], max(1, $totalBlogs));
                  ?>
                     <div class="jy-report-row">
                        <div style="min-width:0;flex:1;">
                           <b><?php echo jy_text($category['label']); ?></b>
                           <div class="jy-progress mt-2"><span style="width:<?php echo $width; ?>%;background:#0b7285;"></span></div>
                        </div>
                        <span class="badge badge-light"><?php echo jy_number($category['total']); ?></span>
                     </div>
                  <?php } ?>
               <?php }else{ ?>
                  <div class="jy-empty-state">
                     <div>
                        <i class="ri-price-tag-3-line d-block mb-2" style="font-size:34px;"></i>
                        Categories will appear after blogs are tagged.
                     </div>
                  </div>
               <?php } ?>
            </div>
         </div>
      </div>
      <div class="col-xl-4">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between align-items-center">
               <div class="iq-header-title">
                  <h4 class="card-title">Tags & Monthly Summary</h4>
               </div>
               <span class="badge badge-success"><?php echo jy_number($monthViews); ?> monthly views</span>
            </div>
            <div class="iq-card-body">
               <div class="mb-4">
                  <?php if(count($tagRows) > 0){ ?>
                     <?php foreach($tagRows as $tag){ ?>
                        <span class="badge badge-light p-2 mb-2 mr-1">#<?php echo jy_text($tag['label']); ?> <?php echo jy_number($tag['total']); ?></span>
                     <?php } ?>
                  <?php }else{ ?>
                     <div class="text-muted">Tags will appear after you add them to blogs.</div>
                  <?php } ?>
               </div>
               <div class="jy-report-row">
                  <span>Blogs this month</span>
                  <b><?php echo jy_number($monthBlogs); ?></b>
               </div>
               <div class="jy-report-row">
                  <span>Blogs last month</span>
                  <b><?php echo jy_number($lastMonthBlogs); ?></b>
               </div>
               <div class="jy-report-row">
                  <span>Total draft/other records</span>
                  <b><?php echo jy_number($draftBlogs); ?></b>
               </div>
               <div class="jy-report-row">
                  <span>Average views</span>
                  <b><?php echo jy_number($avgViews); ?></b>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-xl-7">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between align-items-center">
               <div class="iq-header-title">
                  <h4 class="card-title">Top Performing Blogs</h4>
               </div>
               <a href="bloglist.php" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
            <div class="iq-card-body">
               <?php if(count($topBlogs) > 0){ ?>
                  <div class="table-responsive">
                     <table class="table table-borderless mb-0">
                        <thead>
                           <tr>
                              <th>Blog</th>
                              <th>Status</th>
                              <th class="text-right">Views</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach($topBlogs as $blog){
                              $image = $blog['featured_image'] != '' ? '../uploads/blog/' . $blog['featured_image'] : '../assets/images/blog-placeholder.png';
                              $statusClass = ($blog['status'] == 'published' || $blog['status'] == 'Active') ? 'badge-success' : 'badge-warning';
                           ?>
                              <tr>
                                 <td>
                                    <div class="d-flex align-items-center">
                                       <img class="jy-post-thumb mr-3" src="<?php echo jy_text($image); ?>" alt="blog">
                                       <div style="min-width:0;">
                                          <b><?php echo jy_text(jy_short($blog['title'], 58)); ?></b>
                                          <div class="text-muted small"><?php echo date('d M Y', strtotime($blog['created_at'])); ?></div>
                                       </div>
                                    </div>
                                 </td>
                                 <td><span class="jy-status-pill <?php echo $statusClass; ?>"><?php echo jy_text($blog['status']); ?></span></td>
                                 <td class="text-right"><b><?php echo jy_number($blog['views']); ?></b></td>
                              </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </div>
               <?php }else{ ?>
                  <div class="jy-empty-state">No blog records found. Add your first article to activate reports.</div>
               <?php } ?>
            </div>
         </div>
      </div>
      <div class="col-xl-5">
         <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
            <div class="iq-card-header d-flex justify-content-between align-items-center">
               <div class="iq-header-title">
                  <h4 class="card-title">Recent Activity</h4>
               </div>
               <span class="badge badge-light"><?php echo date('d M, Y'); ?></span>
            </div>
            <div class="iq-card-body">
               <?php if(count($recentBlogs) > 0){ ?>
                  <?php foreach($recentBlogs as $blog){ ?>
                     <div class="jy-report-row">
                        <div style="min-width:0;">
                           <b><?php echo jy_text(jy_short($blog['title'], 54)); ?></b>
                           <div class="text-muted small">
                              <?php echo date('d M Y, h:i A', strtotime($blog['created_at'])); ?>
                              · <?php echo jy_number($blog['views']); ?> views
                           </div>
                        </div>
                        <a href="bloglist.php" class="btn btn-sm btn-outline-secondary"><i class="ri-edit-line"></i></a>
                     </div>
                  <?php } ?>
               <?php }else{ ?>
                  <div class="jy-empty-state">Recent blog activity will show here.</div>
               <?php } ?>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include('../created/pagefooter.php'); ?>
<?php include('../created/footer.php'); ?>

<script type="text/javascript">
   $(document).bind("contextmenu", function(e){
      return false;
   });

   $(document).ready(function() {
      var myElement = document.getElementById('content-page');
      if(myElement && typeof Hammer !== 'undefined'){
         var mc = new Hammer(myElement);
         mc.on("panright", function() {
            $(".wrapper-menu").addClass('open');
            $("body").addClass("sidebar-main");
         });
         mc.on("panleft tap", function() {
            $(".wrapper-menu").removeClass('open');
            $("body").removeClass("sidebar-main");
         });
      }

      $("#alertsuccessmessage").text("Press F1 For Shortcuts");
      $('#alertsuccess').fadeIn('slow', function(){
         $('#alertsuccess').delay(4000).fadeOut();
      });

      if(typeof hotkeys !== 'undefined'){
         hotkeys('f1', function (event){
            event.preventDefault();
            $('.filtershortcutmodal').modal('toggle');
         });
         hotkeys('f2', function (event){
            event.preventDefault();
            window.location.href = "./bloglist.php";
         });
         hotkeys('f3', function (event){
            event.preventDefault();
            window.location.href = "./addblog.php";
         });
         hotkeys('f10', function (event){
            event.preventDefault();
            window.location.href = "./editprofile.php";
         });
         hotkeys('f11', function (event){
            event.preventDefault();
            window.location.href = "./lockscreen.php";
         });
         hotkeys('f12,ctrl+q', function (event){
            event.preventDefault();
            window.location.href = "./logout.php";
         });
         hotkeys('ctrl+l', function (event){
            event.preventDefault();
            window.location.href = "./lockscreen.php";
         });
         hotkeys.filter = function(){
            return true;
         };
      }

      var monthlyChart = <?php echo json_encode($monthlyChart); ?>;
      var categoryChart = <?php echo json_encode($categoryChart); ?>;
      var statusChart = <?php echo json_encode($statusChart); ?>;

      if(typeof ApexCharts !== 'undefined'){
         new ApexCharts(document.querySelector("#jy-publishing-chart"), {
            chart: { type: 'area', height: 330, toolbar: { show: false }, zoom: { enabled: false } },
            series: [
               { name: 'Blogs', data: monthlyChart.blogs },
               { name: 'Views', data: monthlyChart.views }
            ],
            colors: ['#0b7285', '#2f9e44'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: .35, opacityTo: .04 } },
            xaxis: { categories: monthlyChart.labels },
            yaxis: { min: 0, forceNiceScale: true },
            grid: { borderColor: '#edf2f7' },
            tooltip: { shared: true, intersect: false }
         }).render();

         new ApexCharts(document.querySelector("#jy-status-chart"), {
            chart: { type: 'donut', height: 250 },
            series: statusChart.values,
            labels: statusChart.labels,
            colors: ['#2f9e44', '#f59f00'],
            legend: { position: 'bottom' },
            dataLabels: { enabled: true },
            plotOptions: { pie: { donut: { size: '68%' } } }
         }).render();

         if(document.querySelector("#jy-category-chart")){
            new ApexCharts(document.querySelector("#jy-category-chart"), {
               chart: { type: 'bar', height: 230, toolbar: { show: false } },
               series: [{ name: 'Blogs', data: categoryChart.values }],
               colors: ['#0b7285'],
               plotOptions: { bar: { borderRadius: 4, horizontal: true } },
               dataLabels: { enabled: false },
               xaxis: { categories: categoryChart.labels, min: 0, forceNiceScale: true },
               grid: { borderColor: '#edf2f7' }
            }).render();
         }
      }
   });
</script>
