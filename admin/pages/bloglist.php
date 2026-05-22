<?php include('../created/header.php'); ?>
<?php include('../created/sidebar.php'); ?>
<?php include('../created/pageheader.php'); ?>
<?php include('../created/datatableheader.php'); ?>

<link rel="stylesheet" href="../examassets/plugins/summernote/summernote-bs4.css">

<div class="modal fade bd-example-modal-xl" tabindex="5" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content" style="max-height:100vh;">
         <div class="modal-header">
            <div style="width:100%;text-align:center;">
               <center><div><a onclick="showopt1();" id="opt1" class="btn-primary" style="cursor: pointer;border:1px solid #7d7d7d;padding:7px;padding-left:4%;padding-right:4%;border-radius: 9px;border-right: 0px;background-color:#7d7d7d;color:white;">Add Blog</a></div></center>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" style="max-height:100vh;overflow-y:scroll;background-color: #eff7f8;">
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
                                          <input type="text" class="form-control" id="title" placeholder="---" autocomplete="off">
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
                                    <button type="button" id="saveblogdatamodal" onclick="submitmodal();" class="btn btn-primary">Save changes</button>
                                 </form>
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

<div class="container-fluid">
   <div class="row">
      <div class="col-sm-12">
         <div class="iq-card">
            <div class="iq-card-header d-flex justify-content-between">
               <div class="iq-header-title" style="width:100%;">
                  <h4 style="display:inline-block;" class="card-title">Blogs List</h4>
                  <button style="float:right;" type="button" class="btn btn-outline-primary" data-toggle="modal" data-target=".bd-example-modal-xl" onclick="clearmemory();"><i class="ri-bill-fill"></i>Add New</button>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row" id="bloglist">

   </div>
</div>

<?php include('../created/pagefooter.php'); ?>
<?php include('../created/footer.php'); ?>
<?php include('../created/datatablefooter.php'); ?>

<script src="../examassets/plugins/summernote/summernote-bs4.min.js"></script>

<script type="text/javascript">
var table;
var update = false;
var updateid;

$(document).bind("contextmenu",function(e){
   return false;
});

$(document).ready(function(){
   $("#loadingbar").show();
   refreshlist();

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
      dropdownParent: $('.bd-example-modal-xl'),
      width: '100%'
   });

   $("#tags").select2({
      tags: true,
      tokenSeparators: [','],
      placeholder: 'Select or type tags',
      dropdownParent: $('.bd-example-modal-xl'),
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

function setSelectValues(selector, values){
   addSelectOptions(selector, values);
   $(selector).val(values).trigger('change');
}

function refreshlist(){
   var emytask = "refreshbloglist";
   $.ajax({
      type: 'POST',
      url: './ajax.php',
      data: {task: emytask},
      success: function (data) {
         $("#loadingbar").hide();
         $("#bloglist").html(data);
         readydatatable();
      },
      error: function (jqXHR, exception) {
         ajaxerror(jqXHR, exception);
      },
   });
}

function readydatatable() {
   table = $('#blogtable').DataTable({
      searching: true,
      paging:true,
      select: true,
      info: true,
      keys: true,
      responsive: true,
      select: {
         style: 'single'
      },
      keys: {
         keys: [ 13, 38, 40, 32, 46, 78, 89 ]
      },
      dom: '<"pull-left"B"<clear>"><"pull-right"f<"dall">>rtip',
      lengthMenu: [
         [ 10, 25, 50, -1 ],
         [ '10 rows', '25 rows', '50 rows', 'Show all' ]
      ],
      'columnDefs': [
         {
            'targets': 0,
            'searchable': false,
            'orderable': false,
            'checkboxes': {
               'selectRow': true
            },
            'render': function (data, type, full, meta){
               return '<input type="checkbox" class="dt-checkboxes tblcheckboxid" onclick="checkcheckbox();">';
            }
         },
         {
            'targets': 1,
            'searchable': false,
            'orderable': true,
         },
         {
            'targets': 9,
            'searchable': false,
            'orderable': false,
         },
         {
            'targets': 10,
            'searchable': false,
            'orderable': false,
            'visible':false,
         }
      ],
      buttons: [
         {
            extend: 'print',
            text: '<u>P</u>rint',
            key: {
               key: 'p',
               altKey: true
            },
            exportOptions: {
               columns: ':visible'
            }
         },{
            extend: 'copyHtml5',
            text: '<u>C</u>opy',
            key: {
               key: 'c',
               altKey: true
            },
            exportOptions: {
               columns: ":visible"
            }
         },{
            extend: 'excelHtml5',
            text: 'E<u>x</u>cel',
            key: {
               key: 'x',
               altKey: true
            },
            exportOptions: {
               columns: ':visible'
            },
            title: 'Blog List',
            autoFilter: true
         },{
            extend : 'pdf',
            text: 'PDF (<u>O</u>)',
            key: {
               key: 'o',
               altKey: true
            },
            exportOptions: {
               columns: ':visible'
            },
            title: 'Blog List'
         },
         {
            extend: 'colvis'
         }
      ]
   });

   $('#blogtable').on('key-focus.dt', function(e, datatable, cell){
      table.row(cell.index().row).select();
   });

   $('#blogtable').on('click', 'tbody td', function(e){
      e.stopPropagation();
      var rowIdx = table.cell(this).index().row;
      table.row(rowIdx).select();
   });

   window.onkeydown = function(e) {
      return !(e.keyCode == 32 && e.target == document.body);
   };

   $('#blogtable').on('key.dt', function(e, datatable, key, cell, originalEvent){
      if(key == 46 ){
         if ((!$('.deletemodal').is(':visible')) && (!$('.bd-example-modal-xl').is(':visible'))) {
            var myindex = cell.index().row;
            myindex = myindex%10;
            $(".delete:eq("+myindex+")").click();
         }
      }else if(key === 78){
         if(($('.deletemodal').is(':visible')) && (!$('.bd-example-modal-xl').is(':visible'))) {
            $('.deletemodalno').click();
         }
      }else if(key === 89){
         if(($('.deletemodal').is(':visible')) && (!$('.bd-example-modal-xl').is(':visible'))) {
            $('.deletemodalyes').click();
         }
      }else if(key === 13){
         if ((!$('.deletemodal').is(':visible')) && (!$('.bd-example-modal-xl').is(':visible'))) {
            var myindex = cell.index().row;
            myindex = myindex%10;
            $(".edit:eq("+myindex+")").click();
         }
      }else if(key === 32){
         if ((!$('.deletemodal').is(':visible')) && (!$('.bd-example-modal-xl').is(':visible'))) {
            var myindex = cell.index().row;
            myindex = myindex%10;
            if($(".dt-checkboxes:eq("+myindex+")").prop("checked") == true){
               $(".dt-checkboxes:eq("+myindex+")").prop("checked", false);
            }
            else if($(".dt-checkboxes:eq("+myindex+")").prop("checked") == false){
               $(".dt-checkboxes:eq("+myindex+")").prop("checked", true);
            }
            checkcheckbox();
         }
      }
   });
}

function checkcheckbox(){
   var noofchecked = $('.tblcheckboxid:checked').length;
   if(noofchecked > 1){
      $(".dall").html('<div class="dt-buttons pull-right" style="display:inline;"><button class="btn btn-danger" tabindex="0" type="button" onclick="setdeleteallaction();"><span>Delete All</span></button></div>');
   }else{
      $(".dall").html('');
   }
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
      formData.append('update', update);
      formData.append('updateid', updateid);

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
               refreshlist();
               $("#alertsuccessmessage").html("<strong>Success</strong> New Blog Added in Records!!!");
               $('#alertsuccess').fadeIn('slow', function(){
                  $('#alertsuccess').delay(2000).fadeOut();
               });
               clearblogfields();
            }else if(mydata=="recordupdated"){
               refreshlist();
               $("#alertsuccessmessage").html("<strong>Success</strong> Record Updated!!!");
               $('#alertsuccess').fadeIn('slow', function(){
                  $('#alertsuccess').delay(2000).fadeOut();
               });
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

function editblog(blogid){
   updateid = blogid;
   update = true;
   resetfields();

   $("#opt1").text("Edit Blog");
   $("#leftheading").text("Edit Blog");

   var emytask = "getblogdetails";
   $.ajax({
      type: 'POST',
      url: './ajax.php',
      data: {task: emytask, blogid : updateid},
      dataType: 'json',
      success: function (data) {
         if(data.response=="true"){
            $('.bd-example-modal-xl').modal('show');
            $("#title").val(data.title);
            $("#created_at").val(data.created_at_input);
            setSelectValues("#categories", data.categories);
            setSelectValues("#tags", data.tags);
            $('#content').summernote('code', data.content);
            if(data.featured_image==""){
               $(".profile-pic").attr("src","../assets/images/blog-placeholder.png");
            }else{
               $(".profile-pic").attr("src","../uploads/blog/" + data.featured_image);
            }
         }else{
            alert("Blog Does Not Exist!!!!");
         }
      },
      error: function (jqXHR, exception) {
         ajaxerror(jqXHR, exception);
      },
   });
}

function setdeleteaction(emid){
   $('.deletemodal').modal('show');

   setTimeout(function () {
      $('.deletemodalyes').focus();
   }, 600);

   $("#deletemodalyes").off('click').on('click', function(){
      deleteblog(emid);
   });
}

function deleteblog(emyid){
   var emytask = "deleteblog";
   $.ajax({
      type: 'POST',
      url: './ajax.php',
      data: {task: emytask, blogid: emyid},
      dataType: 'json',
      success: function (data) {
         if(data.response=="recorddeleted"){
            refreshlist();

            $("#alertsuccessmessage").html("<strong>Success</strong> Record Deleted!!!");
            $('#alertsuccess').fadeIn('slow', function(){
               $('#alertsuccess').delay(2000).fadeOut();
            });

            $('.deletemodal').modal('toggle');
         }else{
            $("#alertdangermessage").html("<strong>Error!!!</strong> Record not Deleted!!!");
            $('#alertdanger').fadeIn('slow', function(){
               $('#alertdanger').delay(2000).fadeOut();
            });
            $('.deletemodal').modal('toggle');
         }
      },
      error: function (jqXHR, exception) {
         ajaxerror(jqXHR, exception);
         $('.deletemodal').modal('toggle');
      },
   });
}

function setdeleteallaction(emid){
   $('.deletemodal').modal('show');

   setTimeout(function () {
      $('.deletemodalyes').focus();
   }, 600);

   $("#deletemodalyes").off('click').on('click', function(){
      deleteall();
   });
}

function deleteall(){
   var deleteids = [];
   var totalnoofcheck = $('.tblcheckboxid').length;
   for(i=0;i<totalnoofcheck;i++){
      if($(".dt-checkboxes:eq("+i+")").prop("checked") == true){
         deleteids.push(table.cells({row:i,column:10}).data()[0]);
      }
   }

   var emytask = "deleteblogall";
   $.ajax({
      type: 'POST',
      url: './ajax.php',
      data: {task: emytask, blogids: deleteids},
      dataType: 'json',
      success: function (data) {
         if(data.response=="recordsdeleted"){
            refreshlist();

            $("#alertsuccessmessage").html("<strong>Success</strong> "+data.noofrecordsdeleted+" Record Deleted!!!");
            $('#alertsuccess').fadeIn('slow', function(){
               $('#alertsuccess').delay(2000).fadeOut();
            });

            $('.deletemodal').modal('toggle');
         }else{
            $("#alertdangermessage").html("<strong>Error!!!</strong> Record not Deleted!!!");
            $('#alertdanger').fadeIn('slow', function(){
               $('#alertdanger').delay(2000).fadeOut();
            });
            $('.deletemodal').modal('toggle');
         }
      },
      error: function (jqXHR, exception) {
         ajaxerror(jqXHR, exception);
         $('.deletemodal').modal('toggle');
      },
   });
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

function resetfields(){
   clearblogfields();
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
   resetfields();
});

hotkeys('ctrl+s', function (event, handler){
   event.preventDefault();
   if((!$('.deletemodal').is(':visible')) && ($('.bd-example-modal-xl').is(':visible'))){
      $("#saveblogdatamodal").click();
   }else{
      $("#universalsearch").focus();
   }
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
   clearmemory();
   $('.bd-example-modal-xl').modal('show');
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

hotkeys('alt+s', function (event, handler){
   event.preventDefault();
   $(".dataTables_filter input").focus();
});

hotkeys('ctrl+a', function (event, handler){
   event.preventDefault();
   $(":checkbox").prop("checked","true");
   $(".dall").html('<div class="dt-buttons pull-right" style="display:inline;"><button class="btn btn-danger" tabindex="0" type="button" onclick="setdeleteallaction();"><span>Delete All</span></button></div>');
});

hotkeys('ctrl+enter', function (event, handler){
   event.preventDefault();
   if($('.bd-example-modal-xl').is(':visible')){
      $("#saveblogdatamodal").click();
   }else{
      clearmemory();
      $('.bd-example-modal-xl').modal('show');
   }
});

hotkeys('esc', function (event, handler){
   event.preventDefault();
   $(":checkbox").removeAttr('checked');
   $(".dall").html('');
});

hotkeys('ctrl+p', function (event, handler){
   event.preventDefault();
   table.button('0').trigger();
});

hotkeys('ctrl+c', function (event, handler){
   event.preventDefault();
   table.button('1').trigger();
});

hotkeys('ctrl+x', function (event, handler){
   event.preventDefault();
   table.button('2').trigger();
});

hotkeys('ctrl+d', function (event, handler){
   event.preventDefault();
   table.button('3').trigger();
});

function showopt1(){
   $("#opt1").css("background-color","#7d7d7d");
   $("#opt1").css("color","white");
   $("#addblog").show();
}

function clearmemory(){
   update = false;
   updateid = "";
   resetfields();

   $("#opt1").text("Add Blog");
   $("#leftheading").text("Add New Blog");
   clearblogfields();
}

$(document).on('show.bs.modal', '.bd-example-modal-xl', function (e) {
   setTimeout(function(){
      $("#title").filter(':visible').focus();
   }, 600);
});

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
