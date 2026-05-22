<?php
include_once('admin/includes/dbcon.php');

function clean_output($value){
   return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function article_terms($conn, $blogid, $mastertable, $masterid, $namefield, $linktable){
   $items = array();
   $blogid = mysqli_real_escape_string($conn, $blogid);
   $sql = "SELECT m.$namefield,m.$masterid FROM $mastertable m INNER JOIN $linktable l ON m.$masterid=l.$masterid WHERE l.blogid='$blogid' ORDER BY m.$namefield";
   $result = $conn->query($sql);
   if($result && $result->num_rows > 0){
      while($row = $result->fetch_assoc()){
         $items[] = $row;
      }
   }
   return $items;
}

function blog_url($slug){
   return 'blogdetails/' . rawurlencode($slug);
}

$slug = '';
if(isset($_GET['slug'])){
   $slug = $_GET['slug'];
}else{
   $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
   if(preg_match('#/blogdetails/([^/]+)#', $path, $matches)){
      $slug = urldecode($matches[1]);
   }
}

$slug = mysqli_real_escape_string($conn, $slug);
$blog = null;

if($slug!=""){
   $sql = "SELECT * FROM blogs WHERE slug='$slug' AND (status='published' OR status='Active') LIMIT 1";
   $result = $conn->query($sql);
   if($result && $result->num_rows > 0){
      $blog = $result->fetch_assoc();
      $blogid = $blog['blogid'];
      $conn->query("UPDATE blogs SET views=views+1 WHERE blogid='$blogid'");
   }
}

if(!$blog){
   http_response_code(404);
}

$recentblogs = array();
$categories = array();
$tags = array();

$result = $conn->query("SELECT blogid,title,slug,featured_image,created_at FROM blogs WHERE status='published' OR status='Active' ORDER BY created_at DESC, blogid DESC LIMIT 5");
if($result && $result->num_rows > 0){
   while($row = $result->fetch_assoc()){
      $recentblogs[] = $row;
   }
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

$title = $blog ? $blog['title'] : 'Blog Not Found';
$description = $blog ? ($blog['meta_description'] ? $blog['meta_description'] : $blog['short_description']) : 'The article you are looking for is not available.';
$image = ($blog && $blog['featured_image']) ? 'admin/uploads/blog/'.$blog['featured_image'] : 'admin/assets/images/blog-placeholder.png';
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$basepath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if($basepath=='.'){
   $basepath = '';
}
$baseurl = $scheme . '://' . $_SERVER['HTTP_HOST'] . $basepath;
$absoluteimage = $baseurl . '/' . $image;
$canonical = $blog ? $baseurl . '/' . blog_url($blog['slug']) : $baseurl . '/index.php';
$date = ($blog && $blog['created_at']) ? date('d M Y', strtotime($blog['created_at'])) : '';
$schemaDate = ($blog && $blog['created_at']) ? date('c', strtotime($blog['created_at'])) : '';
$schemaUpdated = ($blog && $blog['updated_at']) ? date('c', strtotime($blog['updated_at'])) : $schemaDate;
$blogcategories = $blog ? article_terms($conn, $blog['blogid'], 'blogcategories', 'blogcategoryid', 'category', 'blogcategorylink') : array();
$blogtags = $blog ? article_terms($conn, $blog['blogid'], 'blogtags', 'blogtagid', 'tag', 'blogtaglink') : array();
?>
<!doctype html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title><?php echo clean_output($blog && $blog['meta_title'] ? $blog['meta_title'] : $title); ?></title>
   <meta name="description" content="<?php echo clean_output($description); ?>">
   <meta name="robots" content="<?php echo $blog ? 'index, follow' : 'noindex, follow'; ?>">
   <link rel="canonical" href="<?php echo clean_output($canonical); ?>">
   <meta property="og:title" content="<?php echo clean_output($title); ?>">
   <meta property="og:description" content="<?php echo clean_output($description); ?>">
   <meta property="og:type" content="article">
   <meta property="og:url" content="<?php echo clean_output($canonical); ?>">
   <meta property="og:image" content="<?php echo clean_output($absoluteimage); ?>">
   <link rel="icon" href="../public/assets/favicon.png">
   <link rel="stylesheet" href="../public/assets/blog.css">
   <link rel="stylesheet" href="public/assets/blog.css">
   <?php if($blog){ ?>
   <script type="application/ld+json">
   {
      "@context": "https://schema.org",
      "@type": "BlogPosting",
      "headline": <?php echo json_encode($title); ?>,
      "description": <?php echo json_encode($description); ?>,
      "image": <?php echo json_encode($absoluteimage); ?>,
      "datePublished": <?php echo json_encode($schemaDate); ?>,
      "dateModified": <?php echo json_encode($schemaUpdated); ?>,
      "author": {
         "@type": "Organization",
         "name": "Job Yaari"
      },
      "publisher": {
         "@type": "Organization",
         "name": "Job Yaari"
      },
      "mainEntityOfPage": {
         "@type": "WebPage",
         "@id": <?php echo json_encode($canonical); ?>
      }
   }
   </script>
   <?php } ?>
</head>
<body>
   <header class="site-header">
      <a class="brand" href="../index.php">
         <img class="brand-logo brand-logo-wide" src="../public/assets/newlogo.png" onerror="this.src='public/assets/newlogo.png'" alt="JobYaari">
      </a>
      <nav class="main-nav" aria-label="Main navigation">
         <a href="../index.php">Home</a>
         <a href="../contact.php">Contact Us</a>
      </nav>
   </header>

   <main>
      <?php if($blog){ ?>
         <section class="detail-banner">
            <div>
               <h2><?php echo clean_output($blog['title']); ?></h2>
               <div class="breadcrumbs light">
                  <a href="../index.php">Home</a>
                  <span>/</span>
                  <a href="../index.php">Blog</a>
               </div>
            </div>
         </section>

         <section class="detail-layout">
            <article class="detail-main">
               <div class="detail-image">
                  <img src="../<?php echo clean_output($image); ?>" onerror="this.src='<?php echo clean_output($image); ?>'" alt="<?php echo clean_output($blog['title']); ?>">
               </div>
               <div class="detail-card">
                  <div class="detail-meta">
                     <span><?php echo clean_output($date); ?></span>
                     <span><?php echo intval($blog['views']) + 1; ?> views</span>
                  </div>
                  <h2><?php echo clean_output($blog['title']); ?></h2>
                  <div class="chip-row detail-tags">
                     <?php foreach($blogcategories as $category){ ?>
                        <span class="chip"><?php echo clean_output($category['category']); ?></span>
                     <?php } ?>
                     <?php foreach($blogtags as $tag){ ?>
                        <span class="chip tag-chip">#<?php echo clean_output($tag['tag']); ?></span>
                     <?php } ?>
                  </div>
                  <div class="article-content detail-content">
                     <?php echo $blog['content']; ?>
                  </div>
               </div>
            </article>

            <aside class="filters-panel detail-sidebar">
               <div class="filter-block">
                  <div class="filter-title">Blog Categories</div>
                  <ul class="sidebar-list">
                     <?php foreach($categories as $category){ ?>
                        <li><a href="../index.php?category=<?php echo clean_output($category['blogcategoryid']); ?>"><?php echo clean_output($category['category']); ?> <span>(<?php echo clean_output($category['totalblogs']); ?>)</span></a></li>
                     <?php } ?>
                  </ul>
               </div>

               <div class="filter-block">
                  <div class="filter-title">Recent Blogs</div>
                  <div class="recent-list">
                     <?php foreach($recentblogs as $recent){
                        $recentimage = $recent['featured_image'] ? 'admin/uploads/blog/'.$recent['featured_image'] : 'admin/assets/images/blog-placeholder.png';
                        $recentdate = $recent['created_at'] ? date('d M Y', strtotime($recent['created_at'])) : '';
                     ?>
                        <a class="recent-item" href="../<?php echo clean_output(blog_url($recent['slug'])); ?>">
                           <img src="../<?php echo clean_output($recentimage); ?>" onerror="this.src='<?php echo clean_output($recentimage); ?>'" alt="<?php echo clean_output($recent['title']); ?>">
                           <span>
                              <strong><?php echo clean_output($recent['title']); ?></strong>
                              <small><?php echo clean_output($recentdate); ?></small>
                           </span>
                        </a>
                     <?php } ?>
                  </div>
               </div>

               <div class="filter-block">
                  <div class="filter-title">Tags</div>
                  <div class="tag-cloud">
                     <?php foreach($tags as $tag){ ?>
                        <a class="tag-choice" href="../index.php?tag=<?php echo clean_output($tag['blogtagid']); ?>"><?php echo clean_output($tag['tag']); ?></a>
                     <?php } ?>
                  </div>
               </div>
            </aside>
         </section>
      <?php }else{ ?>
         <section class="article-shell not-found">
            <h1>Blog Not Found</h1>
            <p>The article you are looking for is not available.</p>
            <a class="clear-button" href="index.php">View all blogs</a>
         </section>
      <?php } ?>
   </main>

   <footer class="site-footer">
      <div class="footer-copy">
         <p>Copyright <?php echo date('Y'); ?> JobYaari. All rights reserved.</p>
         <br>
         <p>Developed by Jatin Gangwani</p>
      </div>
   
   </footer>
</body>
</html>
