        <footer class="main-footer">
          <ol class="breadcrumb">
            <li class=""> <a href="./">Planes y Costos</a></li>
            <li class=""> <a href="terminos-y-condiciones">Terminos y Condiciones</a></li>
            <li class="active"><a href="./company-invite">Web del Desarrollador </a></li>
            <li class=""> <a href="./">Documentacion</a></li>
          </ol>
        <div class="pull-right hidden-xs">
          <b>Version Beta</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="http://proyectokamila.com">Proyecto Kamila</a>.</strong> Todos los Derechos Reservado.
      </footer>

      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
          
          <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <!-- Home tab content -->
          <div class="tab-pane" id="control-sidebar-home-tab">
           

          </div><!-- /.tab-pane -->

          <!-- Settings tab content -->
         
        </div>
      </aside><!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>

    </div><!-- ./wrapper -->
<!-- Select2 -->
    <!-- jQuery 2.1.4 -->
    <script src="./master/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="./master/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src="./master/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="./master/dist/js/app.min.js" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="./master/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
    <!-- jvectormap -->
    <script src="./master/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
    <script src="./master/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="./master/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="./master/plugins/chartjs/Chart.min.js" type="text/javascript"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="./master/dist/js/pages/dashboard2.js" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="./master/dist/js/demo.js" type="text/javascript"></script>
    <script src="./scripts/main.js" type="text/javascript"></script>
    <script src="./master/plugins/select2/select2.full.min.js" type="text/javascript"></script>
    <?php //include('./scripts/companyinviteuser.php'); ?>
        <script type="text/javascript" src="">
          /*$(document).on("ready", inviteuser);
          console.log('busqueda');
          function inviteuser(valor){
              $.ajax({
                  url: "<?php echo base_url(); ?>panel/companyInviteUser_ajax",
                  type:"POST",
                  data:{search:valor},
                  success:function(respuesta){
                      alert(respuesta);
                  }
              })
          }*/
      </script>
    
    <script type="text/javascript" src="">
    $(document).ready(function(){
      console.log("documento preparado");
    $(".select2").select2(function(){
      console.log("esta");
    
    });
    });
    </script>
  </body>
</html>
