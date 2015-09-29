<div class="content-wrapper">
              <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            EMPRESA
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-book"></i> </a></li>
            <li class="active">Cuentas por cobrar</li>
          </ol>
    <div class="container">
        <div class="row">
          <div class="aux">
          <div class="content col-lg-3 col-md-3 col-sm-12 col-xs-12 text-center">
            <div class="box">
              <div class="ctap-botones">
                <div class="col-lg-12 col-md-12 col-sm-4 col-xs-12">
                  <button type="button" onclick="mostrarcta();"  class="bot btn1 btn btn-primary btn-social">Cuentas Por Cobrar
                    <i class="fai2 fa fa-book"></i>
                  </button>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-4 col-xs-12">
                  <button type="button" onclick="mostrarcrono();" class="bot btn2 btn btn-primary btn-social">Cronograma
                    <i class="fai2 fa fa-table"></i>
                  </button>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-4 col-xs-12">
                  <button type="button" onclick="mostrarpendiente();"  class="bot btn3  btn btn-primary btn-social">Pendiente
                    <i class="fai2 fa fa-exclamation-triangle"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          </div>



 <!-- TABLAS -->
 <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
    <!-- CUENTAS POR COBRAR -->
        <section id="cuentas" class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Cuentas Por cobrar</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table  class="cuadro1 table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Numero Factura</th>
                        <th class="hidden-xs">Nombre del cliente</th>
                        <th class="">Monto</th>
                        <th class="hidden-xs">Fecha</th>
                        <th class="hidden-xs">Editar/Eliminar </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for ($i = 0; $i<5; $i++) { ?>
                      <tr>
                        <td>010215454</td>
                        <td class="hidden-xs">Maikol</td>
                        <td class="">Bs. 5.000,00</td>
                        <td class="hidden-xs">24/09/2015</td>
                        <td>
                          <button type="button" onclick="mostrareditar();" class="bot btn4 btn btn-primary"><i class="fai fa fa-pencil-square-o"></i></button>
                          <button type="button" onclick="mostrareliminar();" class="bot btn5 btn btn-primary"><i class="fai fa fa-trash-o"></i></button>
                        </td>
                      </tr>
                     <?php  } ?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
        
        <!--  CRONOGRAMA  -->
        <section id="cronograma" class="content">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">CRONOGRAMA</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table  class="cuadro1 table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Numero Factura</th>
                        <th class="hidden-xs">Nombre del cliente</th>
                        <th class="">Monto</th>
                        <th class="hidden-xs">Fecha</th>
                        <th class="hidden-xs">Editar/Eliminar </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for ($i = 0; $i<5; $i++) { ?>
                      <tr>
                        <td>0102154</td>
                        <td class="hidden-xs">Jose</td>
                        <td class="">Bs. 20.000,00</td>
                        <td class="hidden-xs">24/09/2015</td>
                        <td>
                          <button type="button" class="bot btn btn-primary"><i class="fa fa-pencil-square-o"></i></button>
                          <button type="button" class="bot btn btn-primary"><i class="fa fa-trash-o"></i></button>
                        </td>
                      </tr>
                     <?php  } ?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
        </section><!-- /.content -->
        
        
        <!--  PENDIENTE  -->
        <section id="pendiente" class="content">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">PENDIENTE</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table  class="cuadro1 table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>Numero Factura</th>
                        <th class="hidden-xs">Nombre del cliente</th>
                        <th class="hidden-xs">Monto</th>
                        <th class="hidden-xs">Fecha</th>
                        <th class="hidden-xs">Editar/Eliminar </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php for ($i = 0; $i<5; $i++) { ?>
                      <tr>
                        <td>010215454</td>
                        <td class="hidden-xs">Kevis</td>
                        <td class="hidden-xs">Bs. 10.000,00</td>
                        <td class="hidden-xs">24/09/2015</td>
                        <td>
                          <button type="button" class="bot btn btn-primary"><i class="fa fa-pencil-square-o"></i></button>
                          <button type="button" class="bot btn btn-primary"><i class="fa fa-trash-o"></i></button>
                        </td>
                      </tr>
                     <?php  } ?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
        </section><!-- /.content -->
        </div>
        </div><!-- /.row -->
    </div><!-- /.container -->
    </section>
</div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script>
// CUENTAS POR COBRAR 
    function mostrarcta() {
            console.log('Mostrar active');
            $('#cronograma').slideUp();
            $('#pendiente').slideUp();
            $('#cuentas').slideDown();
            $(".btn").removeClass("bot-active");
            $(".btn1").addClass("bot-active");
        }
  
    function mostrarcrono() {
            console.log('Mostrar siguiente');
            $('#cuentas').slideUp();
            $('#pendiente').slideUp();
            $('#cronograma').slideDown();
            $(".btn").removeClass("bot-active");
            $(".btn2").addClass("bot-active");
        }
        
    function mostrarpendiente() {
            console.log('Mostrar siguiente');
            $('#cuentas').slideUp();
            $('#cronograma').slideUp();
            $('#pendiente').slideDown();    
             $(".btn").removeClass("bot-active");
            $(".btn3").addClass("bot-active");
        }
        
        // EDITAR/ELIMINAS
  function mostrareditar() {
            console.log('Mostrar active');
            $(".btn").removeClass("bot-active");
            $(".btn4").addClass("bot-active");
        }
  function mostrareliminar() {
            console.log('Mostrar active');
             $(".btn").removeClass("bot-active");
            $(".btn5").addClass("bot-active");
        }
  </script>