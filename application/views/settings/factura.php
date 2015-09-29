<div class="content-wrapper">
    <section class="content-header">
          <h1>
            Configurar Facturas
            <small>Version 1.0.0</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-cog"></i> </a></li>
            <!--<li class="active">Panel</li>-->
            <li class="active">Configuracion</li>
            <li class="active">Factura</li>
          </ol>
          

<section class="content">
    <div class="row">
        <div class="col-xs-8">
            <div class="box">
                             
                    <div class="box-header with-border">
                     <label>Especifique el numero de factura que desea imprimir.</label>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form">
                     
                          <div class="box-body">
                              <input type="number" min="0" pattern="[0-9]*" class="form-control" placeholder="Colocar numero de factura" id="numerofactura" name="numero" required>
                         
                         <br>
                         </div>
                            
                         <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                          </div>
                      
                    </form>  
                       
                       
                
                       
                       
                       
                       
                       
            </div><!-- /.box-body -->

            
        </div>
                <div class="col-xs-4">
                    <div class="box">
                        <div class="box-body">
                            <label>Seleccione como desea imprimir su factura.</label>
                                    <!-- radio -->
                            <div class="form-group">
                                      <div class="radio">
                                        <label>
                                          <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked />
                                          Imprimir a Media Pagina
                                        </label>
                                      </div>
                                      
                                      <div class="radio">
                                        <label>
                                          <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" />
                                          Imprimir a Pagina Completa
                                        </label>
                                      </div>
                                      
                                        <div class="box-footer">
                                            
                                               <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
    </div>
</div>

    </section>
</div>   

