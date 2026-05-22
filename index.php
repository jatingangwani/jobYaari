<?php
include_once('admin/includes/dbcon.php');

function clean_output($value){
   return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function blog_terms($conn, $blogid, $mastertable, $masterid, $namefield, $linktable){
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

$categories = array();
$tags = array();
$featured = null;
$recentblogs = array();
$totalblogs = 0;

$result = $conn->query("SELECT COUNT(*) totalblogs FROM blogs WHERE status='published' OR status='Active'");
if($result && $result->num_rows > 0){
   $row = $result->fetch_assoc();
   $totalblogs = $row['totalblogs'];
}

$result = $conn->query("SELECT c.blogcategoryid,c.category,COUNT(l.blogid) totalblogs FROM blogcategories c LEFT JOIN blogcategorylink l ON c.blogcategoryid=l.blogcategoryid LEFT JOIN blogs b ON l.blogid=b.blogid AND (b.status='published' OR b.status='Active') GROUP BY c.blogcategoryid,c.category ORDER BY c.category");
if($result && $result->num_rows > 0){
   while($row = $result->fetch_assoc()){
      $categories[] = $row;
   }
}

$result = $conn->query("SELECT t.blogtagid,t.tag,COUNT(l.blogid) totalblogs FROM blogtags t LEFT JOIN blogtaglink l ON t.blogtagid=l.blogtagid LEFT JOIN blogs b ON l.blogid=b.blogid AND (b.status='published' OR b.status='Active') GROUP BY t.blogtagid,t.tag ORDER BY t.tag");
if($result && $result->num_rows > 0){
   while($row = $result->fetch_assoc()){
      $tags[] = $row;
   }
}

$result = $conn->query("SELECT * FROM blogs WHERE status='published' OR status='Active' ORDER BY created_at DESC, blogid DESC LIMIT 1");
if($result && $result->num_rows > 0){
   $featured = $result->fetch_assoc();
}

$result = $conn->query("SELECT blogid,title,slug,featured_image,created_at FROM blogs WHERE status='published' OR status='Active' ORDER BY created_at DESC, blogid DESC LIMIT 5");
if($result && $result->num_rows > 0){
   while($row = $result->fetch_assoc()){
      $recentblogs[] = $row;
   }
}
?>
<!doctype html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Job Yaari Blogs</title>
   <link rel="icon" href="public/assets/favicon.png">
   <link rel="stylesheet" href="public/assets/blog.css">
</head>
<body>
   <header class="site-header">
      <a class="brand" href="index.php">
         <img class="brand-logo brand-logo-wide" src="public/assets/newlogo.png" alt="JobYaari">
      </a>
      <nav class="main-nav" aria-label="Main navigation">
         <a href="index.php">Home</a>
         <a href="contact.php">Contact Us</a>
      </nav>
   </header>

   <main>
      <section class="page-hero">
         <div>
            <h1>Blogs</h1>
            <div class="breadcrumbs">
               <a href="index.php">Home</a>
               <span>/</span>
               <strong>Blogs</strong>
            </div>
         </div>
         <p>Latest job alerts, results, exam updates, and career guides in one clean reading space.</p>
      </section>

      <section class="category-strip" aria-label="Blog categories menu">
         <button class="menu-link active" data-category="">All Blogs</button>
         <?php foreach($categories as $category){ ?>
            <button class="menu-link" data-category="<?php echo clean_output($category['blogcategoryid']); ?>"><?php echo clean_output($category['category']); ?></button>
         <?php } ?>
      </section>

      <section class="blog-shell">
         <section class="results-area">
            <div class="toolbar-card">
               <div>
               </div>
               <div class="search-wrap">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10.8 18.2a7.4 7.4 0 1 1 5.2-2.2l4 4-1.5 1.5-4-4a7.3 7.3 0 0 1-3.7.7Zm0-2.1a5.3 5.3 0 1 0 0-10.6 5.3 5.3 0 0 0 0 10.6Z"/></svg>
                  <input type="search" id="blogSearch" placeholder="Search blog title or keyword">
               </div>
            </div>
            <div class="blog-grid" id="blogGrid"></div>
            <div class="loader" id="blogLoader">Loading more blogs...</div>
            <div class="empty-state" id="emptyState">No blogs found for this search.</div>
         </section>

         <aside class="filters-panel">
            <div class="filter-block">
               <div class="filter-title">Blog Categories</div>
               <div class="filter-list" id="categoryFilters">
                  <button class="filter-choice active" data-category="">All Blogs <span><?php echo clean_output($totalblogs); ?></span></button>
                  <?php foreach($categories as $category){ ?>
                     <button class="filter-choice" data-category="<?php echo clean_output($category['blogcategoryid']); ?>"><?php echo clean_output($category['category']); ?> <span><?php echo clean_output($category['totalblogs']); ?></span></button>
                  <?php } ?>
               </div>
            </div>

            <div class="filter-block">
               <div class="filter-title">Recent Blogs</div>
               <div class="recent-list">
                  <?php foreach($recentblogs as $recent){
                     $recentimage = $recent['featured_image'] ? 'admin/uploads/blog/'.$recent['featured_image'] : 'admin/assets/images/blog-placeholder.png';
                     $recentdate = $recent['created_at'] ? date('d M Y', strtotime($recent['created_at'])) : '';
                  ?>
                     <a class="recent-item" href="blogdetails/<?php echo clean_output($recent['slug']); ?>">
                        <img src="<?php echo clean_output($recentimage); ?>" alt="<?php echo clean_output($recent['title']); ?>">
                        <span>
                           <strong><?php echo clean_output($recent['title']); ?></strong>
                           <small><?php echo clean_output($recentdate); ?></small>
                        </span>
                     </a>
                  <?php } ?>
               </div>
            </div>

         </aside>
      </section>
   </main>

   <footer class="site-footer">
      <div class="footer-copy">
         <p>Copyright <?php echo date('Y'); ?> JobYaari. All rights reserved.</p>
         <br>
         <p>Developed by Jatin Gangwani</p>
      </div>
   </footer>

   <script src="public/assets/blog.js"></script>
</body>
</html>
