 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
              <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Empresa
            <!--<small>Version 1.0.0</small>-->
          </h1>
          <ol class="breadcrumb">
            <li><a href="./"><i class="fa fa-dashboard"></i> </a></li>
            <li class=""> <a href="./">Empresa</a></li>
            <li class="active"><a href="./company-invite">Invitar Usuario </a></li>
          </ol>
        </section>
        <section class="content">
          <div class="row">
        <!-- search form -->
            <div class="col-md-4 col-xs-12 col-sm-12">
                <div class="box ">
                      <div class="box-header with-border">
                  <h3 class="box-title">Invitar nuevo usuario</h3>
                </div><!-- /.box-header -->
                  <form action="#" method="get" class="-form">
                        <div class="box-body">
                            <div class="input-group">
                              <input type="email" name="search" class="form-control" placeholder="user@proyectokamila.com"  onkeyup="custom_search(value);" autocomplete="off"/>
                              <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                              </span>
                            </div>
                            <p class="help-block">Introduzca el correo del usuario.</p>
                        </div>
                  </form>
                 </div>
                 <div id="box-result-search" class="box hidden ">
                    <div class="box-header with-border">
                      <h3 class="box-title">Resultado de la busqueda</h3>
                      <div class="clearfix"></div>
                      <div class="box-tools pull-right">
                        <span class="label label-danger">1 usuarios encontrados</span>
                       <!-- <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>-->
                        <!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                      </div>
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                      <ul class="users-list clearfix"  id="respuesta">
                        <!--<li>
                          <img src="https://fbcdn-sphotos-c-a.akamaihd.net/hphotos-ak-xap1/v/t1.0-9/11692695_10206696359069208_7289931543412287152_n.jpg?oh=098e33da1dc2374754d11bfaf639270e&oe=566956D3&__gda__=1449639558_379e04c2faa93ac33fbc1e7c6c2106e8" alt="User Image" title="Kevis Rondon"/>
                          <a class="users-list-name" href="#"> Kevis Rondon</a>
                        </li>-->
                      </ul><!-- /.users-list -->
                    </div><!-- /.box-body -->
                   
                  </div><!--/.box -->
                  <div id="while-search" class="hidden">
                    <i class="fa fa-circle-o-notch fa-spin"></i>
                  </div>
              </div>
               <div class="col-md-8 col-xs-12 col-sm-12"  id="box">
                  <!-- USERS LIST -->
                  <div class="box ">
                    <div class="box-header with-border">
                      <h3 class="box-title">Usuarios en este empresa</h3>
                      <div class="box-tools pull-right">
                        <span class="label label-danger">2 New Members</span>
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                      </div>
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                      <ul class="users-list clearfix">
                        <li>
                          <img src="./master/dist/img/user1-128x128.jpg" alt="User Image" />
                          <a class="users-list-name" href="#">Alexander Pierce</a>
                          <!--<span class="users-list-date">Today</span>-->
                          <i class="fa fa-cog fa-lg"></i> <i class="fa fa-ban fa-lg"></i>
                        </li>
                        
                      </ul><!-- /.users-list -->
                    </div><!-- /.box-body -->
                    <div class="box-footer text-center">

                    </div><!-- /.box-footer -->
                  </div><!--/.box -->
                </div><!-- /.col -->
          <!-- /.search form -->
          </div> 
          <!-- /.row -->
          </section>
          <!-- /.section content -->
</div>

