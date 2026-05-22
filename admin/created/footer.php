         </div>
      </div>
      <script type="text/javascript" src="../assets/js/hammer.js"></script>
      <script type="text/javascript" src="../assets/js/hotkeys.min.js"></script>
      <script type="text/javascript">
         if(typeof window.hotkeys === 'undefined'){
            window.hotkeys = function(keys, handler){
               keys.split(',').forEach(function(key){
                  document.addEventListener('keydown', function(event){
                     var shortcut = key.trim().toLowerCase();
                     var pressed = (event.key || '').toLowerCase();
                     var isCtrl = shortcut.indexOf('ctrl+') === 0;
                     var targetKey = shortcut.replace('ctrl+', '');
                     if(isCtrl && !event.ctrlKey){
                        return;
                     }
                     if((pressed === targetKey) || (targetKey.length > 1 && pressed === targetKey.replace('esc', 'escape'))){
                        handler(event, {});
                     }
                  });
               });
            };
            window.hotkeys.filter = function(){ return true; };
         }
         if(typeof hotkeys !== 'undefined'){
            hotkeys('f1', function(event){
               event.preventDefault();
               $('.filtershortcutmodal').modal('toggle');
            });
            hotkeys('f2', function(event){
               event.preventDefault();
               window.location.href = "./bloglist.php";
            });
            hotkeys('f3', function(event){
               event.preventDefault();
               window.location.href = "./addblog.php";
            });
            hotkeys('f10', function(event){
               event.preventDefault();
               window.location.href = "./editprofile.php";
            });
            hotkeys('f11,ctrl+l', function(event){
               event.preventDefault();
               window.location.href = "./lockscreen.php";
            });
            hotkeys('f12,ctrl+q', function(event){
               event.preventDefault();
               window.location.href = "./logout.php";
            });
         }
      </script>
      
      <script src="../assets/js/jquery.min.js"></script>
      <script src="../assets/js/popper.min.js"></script>
      <script src="../assets/js/bootstrap.min.js"></script>
      <script src="../assets/js/jslocalsearch.js"></script>
      <script src="../assets/js/jquery.appear.js"></script>
      <script src="../assets/js/countdown.min.js"></script>
      <script src="../assets/js/waypoints.min.js"></script>
      <script src="../assets/js/jquery.counterup.min.js"></script>
      <script src="../assets/js/wow.min.js"></script>
      <script src="../assets/js/apexcharts.js"></script>
      <script src="../assets/js/slick.min.js"></script>
      <script src="../assets/js/select2.min.js"></script>
      <script src="../assets/js/owl.carousel.min.js"></script>
      <script src="../assets/js/jquery.magnific-popup.min.js"></script>
      <script src="../assets/js/smooth-scrollbar.js"></script>
      <script src="../assets/js/lottie.js"></script>
      <script src="../assets/js/core.js"></script>
      <script src="../assets/js/charts.js"></script>
      <script src="../assets/js/animated.js"></script>
      <script src="../assets/js/kelly.js"></script>
      <script src="../assets/js/flatpickr.js"></script>
      <script src="../assets/js/chart-custom.js"></script>
      <script src="../assets/js/custom.js"></script>
      <script src='../assets/fullcalendar/core/main.js'></script>
      <script src='../assets/fullcalendar/daygrid/main.js'></script>
      <script src='../assets/fullcalendar/timegrid/main.js'></script>
      <script src='../assets/fullcalendar/list/main.js'></script>
      <script src="../assets/js/multiple-select.min.js"></script>
   </body>

</html>
