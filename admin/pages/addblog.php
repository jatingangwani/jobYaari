<?php include('../created/header.php'); ?>
<?php include('../created/sidebar.php'); ?>
<?php include('../created/pageheader.php'); ?>

<link rel="stylesheet" href="../examassets/plugins/summernote/summernote-bs4.css">

<div class="container-fluid">
   <div id="addblog">
      <div class="row">
         <div class="col-lg-3">
            <div class="iq-card">
               <div class="iq-card-header d-flex justify-content-between">
                  <div class="iq-header-title">
                     <h4 class="card-title" id="leftheading">Add New Blog</h4>
                  </div>
               </div>
               <div class="iq-card-body">
                  <form>
                     <div class="form-group" style="text-align: center;">
                        <div class="add-img-user profile-img-edit">
                           <img class="profile-pic img-fluid" src="../assets/images/blog-placeholder.png" alt="Blog thumbnail">
                           <div class="p-image">
                              <a href="javascript:void();" id="upload-button" class="upload-button btn iq-bg-primary">File Upload</a>
                              <input class="file-upload" type="file" name="featured_image" id="featured_image" accept="image/jpg,image/jpeg,image/png,image/webp">
                           </div>
                        </div>
                        <div class="img-extension mt-3">
                           <div class="d-inline-block align-items-center">
                              <span>Only</span>
                              <a href="javascript:void();">.jpg</a>
                              <a href="javascript:void();">.png</a>
                              <a href="javascript:void();">.jpeg</a>
                              <a href="javascript:void();">.webp</a>
                              <span>allowed</span>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
         <div class="col-lg-9">
            <div class="iq-card">
               <div class="iq-card-header d-flex justify-content-between">
                  <div class="iq-header-title">
                     <h4 class="card-title">Blog Information</h4>
                  </div>
               </div>
               <div class="iq-card-body">
                  <div class="new-user-info">
                     <form>
                        <div class="row">
                           <div class="form-group col-md-8">
                              <label for="title" style="width:100%;">Name : <span id="titlerequiredspan" style="float:right;color:red;display:none;">Required *</span></label>
                              <input type="text" class="form-control" id="title" placeholder="---" autofocus="true" autocomplete="off">
                           </div>
                           <div class="form-group col-md-4">
                              <label for="created_at">Date:</label>
                              <input type="datetime-local" class="form-control" id="created_at" autocomplete="off">
                           </div>
                           <div class="form-group col-md-6">
                              <label for="categories">Categories:</label>
                              <select class="form-control" id="categories" multiple="multiple" style="width:100%;"></select>
                           </div>
                           <div class="form-group col-md-6">
                              <label for="tags">Tags:</label>
                              <select class="form-control" id="tags" multiple="multiple" style="width:100%;"></select>
                           </div>
                           <div class="form-group col-md-12">
                              <label for="content" style="width:100%;">Content : <span id="contentrequiredspan" style="float:right;color:red;display:none;">Required *</span></label>
                              <textarea class="form-control" id="content"></textarea>
                           </div>
                        </div>
                        <br>
                        <hr>
                        <button type="button" class="btn btn-danger" onclick="clearblogfields();">Reset</button>
                        <button type="button" id="saveblogdata" onclick="submitmodal();" class="btn btn-primary">Save changes</button>
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

<script src="../examassets/plugins/summernote/summernote-bs4.min.js"></script>

<script type="text/javascript">
$(document).bind("contextmenu",function(e){
   return false;
});

$(document).ready(function(){
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

   $("#upload-button").click(function(){
      $("#featured_image").click();
   });

   $('#content').summernote({
      height: 420,
      placeholder: 'Write or paste blog content here',
      fontNames: ['Arial', 'Arial Black', 'Calibri', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Mangal', 'Noto Sans Devanagari', 'Tahoma', 'Times New Roman', 'Verdana'],
      fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '28', '32', '36', '48'],
      toolbar: [
         ['style', ['style']],
         ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
         ['fontname', ['fontname']],
         ['fontsize', ['fontsize']],
         ['color', ['color']],
         ['para', ['ul', 'ol', 'paragraph', 'height']],
         ['table', ['table']],
         ['insert', ['link', 'picture', 'video', 'hr']],
         ['view', ['fullscreen', 'codeview', 'help']]
      ]
   });

   $("#categories").select2({
      tags: true,
      tokenSeparators: [','],
      placeholder: 'Select or type categories',
      width: '100%'
   });

   $("#tags").select2({
      tags: true,
      tokenSeparators: [','],
      placeholder: 'Select or type tags',
      width: '100%'
   });

   loadblogoptions();
});

function loadblogoptions(){
   $.ajax({
      type: 'POST',
      url: './ajax.php',
      data: {task: 'getblogoptions'},
      dataType: 'json',
      success: function (data) {
         if(data.response=="true"){
            addSelectOptions("#categories", data.categories);
            addSelectOptions("#tags", data.tags);
         }
      }
   });
}

function addSelectOptions(selector, values){
   for(var i=0;i<values.length;i++){
      var exists = false;
      $(selector + " option").each(function(){
         if($(this).val()==values[i]){
            exists = true;
         }
      });
      if(exists==false){
         var newOption = new Option(values[i], values[i], false, false);
         $(selector).append(newOption);
      }
   }
   $(selector).trigger('change');
}

function submitmodal(){
   var validation = formblogvalidate();
   if(validation == true){
      var mytask = "addblog";
      var formData = new FormData();
      formData.append('task', mytask);
      formData.append('title', $("#title").val());
      formData.append('content', $('#content').summernote('code'));
      formData.append('created_at', $("#created_at").val());
      formData.append('categories', JSON.stringify($("#categories").val()));
      formData.append('tags', JSON.stringify($("#tags").val()));
      formData.append('featured_image', $('#featured_image').prop('files')[0]);
      formData.append('update', 'false');
      formData.append('updateid', '');

      $.ajax({
         type: 'POST',
         contentType: false,
         cache: false,
         processData:false,
         url: './ajax.php',
         data: formData,
         dataType: 'json',
         success: function (data) {
            var mydata = data.response;

            if(mydata=="recordadded"){
               $("#alertsuccessmessage").html("<strong>Success</strong> New Blog Added in Records!!!");
               $('#alertsuccess').fadeIn('slow', function(){
                  $('#alertsuccess').delay(2000).fadeOut();
               });
               clearblogfields();
            }else if(mydata=="imageextensionerror"){
               $("#alertdangermessage").html("<strong>Error!!!</strong> Only JPG, JPEG, PNG & WEBP Are Allowed!!!");
               $('#alertdanger').fadeIn('slow', function(){
                  $('#alertdanger').delay(2000).fadeOut();
               });
            }else if(mydata=="imageerror"){
               var uploaderror = data.upload_error ? " (" + data.upload_error + ")" : "";
               $("#alertdangermessage").html("<strong>Error!!!</strong> Unexpected Image Upload Error!!!" + uploaderror);
               $('#alertdanger').fadeIn('slow', function(){
                  $('#alertdanger').delay(2000).fadeOut();
               });
            }else{
               $("#alertdangermessage").html("<strong>Error!!!</strong> Record Not Added!!!");
               $('#alertdanger').fadeIn('slow', function(){
                  $('#alertdanger').delay(2000).fadeOut();
               });
            }
         },
         error: function (jqXHR, exception) {
            ajaxerror(jqXHR, exception);
         },
      });
   }else{
      $("#alertdangermessage").html("<strong>Error!!!</strong> Blank Fields Are Not Allowed!!!");
      $('#alertdanger').fadeIn('slow', function(){
         $('#alertdanger').delay(2000).fadeOut();
      });
   }
}

function formblogvalidate(){
   var mytitle = $("#title").val();
   var mycontent = $('#content').summernote('isEmpty') ? "" : $('#content').summernote('code');
   var output = true;
   var focus = "";

   if(mytitle==""){
      $("#titlerequiredspan").css("display","block");
      if(focus==""){ focus = "#title"; }
      output = false;
   }

   if(mycontent==""){
      $("#contentrequiredspan").css("display","block");
      output = false;
   }

   if(focus!=""){
      $(focus).focus();
   }
   return output;
}

function clearblogfields(){
   $("#title").val("");
   $("#created_at").val("");
   $("#categories").val(null).trigger('change');
   $("#tags").val(null).trigger('change');
   $("#featured_image").val("");
   $('#content').summernote('code', '');
   $(".profile-pic").attr("src","../assets/images/blog-placeholder.png");
   $("#titlerequiredspan").css("display","none");
   $("#contentrequiredspan").css("display","none");
   $("#title").focus();
}

function ajaxerror(jqXHR, exception){
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
}

hotkeys('ctrl+r', function(event, handler) {
   event.preventDefault();
   clearblogfields();
});

hotkeys('ctrl+s', function (event, handler){
   event.preventDefault();
   $("#saveblogdata").click();
});

hotkeys('ctrl+enter', function (event, handler){
   event.preventDefault();
   $("#saveblogdata").click();
});

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

hotkeys('f11', function(event, handler) {
   event.preventDefault();
   window.location.href = "./lockscreen.php";
});

hotkeys('f12,ctrl+q', function(event, handler) {
   event.preventDefault();
   window.location.href = "./logout.php";
});

hotkeys('ctrl+l', function(event, handler) {
   event.preventDefault();
   window.location.href = "./lockscreen.php";
});

hotkeys.filter = function(event){
   return true;
}

$("#featured_image").change(function(e) {
   var file = this.files[0];
   if(!file){
      return false;
   }

   var fileType = file.type;
   var match = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
   if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]))){
      $("#alertdangermessage").html("<strong>Error!!!</strong> Only jpg, jpeg, png and webp Formats are Allowed!!!");
      $('#alertdanger').fadeIn('slow', function(){
         $('#alertdanger').delay(2000).fadeOut();
      });

      $("#featured_image").val('');
      return false;
   }else{
      var reader = new FileReader();
      reader.onload = function(){
         $(".profile-pic").attr("src", reader.result);
      }
      reader.readAsDataURL(file);
   }
});

$('#title').keyup(function() {
   if($("#title").val() != ""){
      $("#titlerequiredspan").css("display","none");
   }
});

$('#content').on('summernote.change', function() {
   if(!$('#content').summernote('isEmpty')){
      $("#contentrequiredspan").css("display","none");
   }
});
</script>
