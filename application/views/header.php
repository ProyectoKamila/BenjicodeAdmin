<!DOCTYPE html>
<html>
  <head>
    <base href="<?php echo base_url(); ?>" />
    <meta charset="UTF-8">
    <title>Benjicode </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <link href="./master/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <link href="./master/plugins/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <!--<link href="./master/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />-->
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    <!--     folder instead of downloading all of them to reduce the load. -->
    <!--<link href="./master/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />-->
    <link href="./master/build/less/main.less" rel="stylesheet" type="text/less" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.5.1/less.min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<body class="skin-black-light sidebar-mini">
  
     <div class="wrapper">
       
         <header class="main-header">
        <div class="debug">
          <?php  //debug($this->user,false);?>
          <?php  //debug($this->user->information,false);?>
        </div>
        <!-- Logo -->
        <a href="<?php echo base_url("");?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"> <img src="./images/admin/minilogo.png" alt="Benjicode" class="img-responsive" title="Benjicode"></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src="./images/admin/logo.png" alt="Benjicode" class="img-responsive" title="Benjicode"></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu notifications-menu tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-danger">4</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 4 messages</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- start message -->
                        <a href="#">
                          <div class="pull-left">
                            <img src="./master/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
                          </div>
                          <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li><!-- end message -->
                       <li>
                        <a href="#">
                          <i class="fa fa-users text-aqua"></i> 5 new members joined today
                        </a>
                      </li>
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>
                            Design some buttons
                            <small class="pull-right">20%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">20% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->
                    </ul>
                  </li>
                  <li class="footer"><a href="#">Ver todas las notificaciones</a></li>
                </ul>
              </li>
             
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="http://www.pknetmarketing.com/images/<?php echo $this->user->information->picture; ?>" class="user-image" alt="User Image" />
                  <span class="hidden-xs"> <?php  echo $this->user->information->name; ?>  <?php  echo $this->user->information->last_name; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="http://www.pknetmarketing.com/images/<?php echo $this->user->information->picture; ?>" class="img-circle" alt="User Image" />
                    <p>
                      <?php  echo $this->user->information->name; ?> <?php  echo $this->user->information->last_name; ?> - <?php  echo $this->user->information->user; ?>
                      <small>Miembro  desde <?php  echo $this->user->information->created; ?> </small>
                    </p>
                  </li>
                  
                  <!-- Menu Body -->
                  <!--<li class="user-body">-->
                  <!--  <div class="col-xs-4 text-center">-->
                  <!--    <a href="#">Followers</a>-->
                  <!--  </div>-->
                  <!--  <div class="col-xs-4 text-center">-->
                  <!--    <a href="#">Sales</a>-->
                  <!--  </div>-->
                  <!--  <div class="col-xs-4 text-center">-->
                  <!--    <a href="#">Friends</a>-->
                  <!--  </div>-->
                  <!--</li>-->
                  <!-- Menu Footer-->
                  </li>
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="http://pkaccount.com/p/account.php" target="_blank"class="btn btn-default btn-flat">Perfil</a>
                    </div>
                    <div class="pull-right">
                      <a href="./signout" class="btn btn-default btn-flat">Cerrar Sesion</a>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>

        </nav>
      </header>
