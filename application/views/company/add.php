<?php //debug($currency[0]); ?>
<div class="content-addcompany" style="background:url(../../images/admin/fondo1.jpg); ">
   <div class="register-box">
      <div class="register-logo">
        <a href="../../index2.html">
          <img src="../../images/admin/logo.png">
        </a>
      </div>
<?php $item = 0;?>
      <div class="register-box-body">
        <!--<form action="/panel/companyAddPost" method="POST">-->
        <form method="POST">
          <div id="formu1">
                 <p class="login-box-msg">Datos de Empresa</p>
          <div id="input-name"  class="form-group has-feedback">
            <input type="hidden" name="tokkenuser" value="<?= $_COOKIE['ip']; ?>"/>
            <label>Nombre</label>
            <input type="text" class="form-control" name="company" id="name" maxlength="50" required placeholder="Ejemplo:Juan" />
          </div>
          <div id="input-di" class="form-group has-feedback">
            <label>Documento de indentidad</label>
            <input type="text" class="form-control" name="di" id="di" maxlength="25" required placeholder="Ejemplo del RIF: V123456789" />
          </div>
          <div id="input-currency" class=" form-group has-feedback">
              <label>Moneda</label>
              <select class="money" name="currency" id="currency">
                <option value="">
                  Seleccione...
                </option>
                <?php foreach($currency as $cy){ ?>
                <option value='<?=$cy['id']; ?>'>
                  <?=$cy['name'].' '.$cy['symbol']; ?>
                </option>
                <?php } ?>
              </select>
          </div>
          <div class="form-group has-feedback">
            <div class="esti">
              <label for="exampleInputFile">Subir Logo</label>
              <input type="file" class="input-logo form-control" name="userfile" id="exampleInputFile">
            </div>
          </div>
            <a  id="next2" class="btn btn-primary btn-block btn-flat">Siguiente</a>
          </div>
          <!--FORMULARIO 2 -->
          <div id="formu2">
              <p class="login-box-msg">Contacto</p>
            <div id="input-first_name" class="form-group has-feedback">
              <label>Nombre</label>
              <input type="text" class="form-control" name="first_name" id="first_name" maxlength="25"  placeholder="Ejemplo:Juan" required />
            </div>
            <div id="input-last_name" class="form-group has-feedback">
              <label>Apellido</label>
              <input type="text" class="form-control" name="last_name" id="last_name" maxlength="25" placeholder="" required />
            </div>
            <div id="input-posicion" class="form-group has-feedback">
              <label>Cargo</label>
              <input type="text" class="form-control" name="posicion" id="posicion" maxlength="25" placeholder="Cargo" required />
            </div>
            <div id="input-phone" class="form-group has-feedback">
              <label>Telefono</label>
              <input type="text" class="form-control" name="phone" id="phone" maxlength="25" placeholder="Ejemplo: 021211111111" required />
            </div>
            <div id="input-email" class="form-group has-feedback">
              <label>Correo</label>
              <input type="email" class="form-control" name="email" id="email" maxlength="30" placeholder="Ejemplo: maikol.leon@hotmail.com" required />
            </div>
            <a id="next3"  class="btn btn-primary btn-block btn-flat">Siguiente</a>
            <a onclick="mostrar1();" class="btn btn-primary btn-block btn-flat">Regresar</a>
          </div>
           <!--FORMULARIO 3 -->
          <div id="formu3">
            <p class="login-box-msg">Dirección</p>
            <!--PAIS -->
           <div class="box-body">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Pais</label>
                    <select id="country" onchange="next4()" class="form-control select2">
                     
                    </select>
                  </div><!-- /.form-group -->
                </div><!-- /.col -->
                 <!--ESTADO -->
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Estado</label>
                    <select id="state" onchange="next4()"  class="form-control select2" disabled="disabled">
                      
                    </select>
                  </div><!-- /.form-group -->
                </div><!-- /.col -->
                
                 <!--CIUDAD -->
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Ciudad</label>
                    <select id="town" onchange="next4()" class="form-control select2" disabled="disabled">
                    </select>
                  </div><!-- /.form-group -->
                </div><!-- /.col -->
                
                <!--MUNICIPIO -->
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Municipio</label>
                    <select id="parish" onchange="next4()" class="form-control select2" disabled="disabled">"name
                    </select>
                  </div><!-- /.form-group -->
                </div><!-- /.col -->
            </div><!-- /.box-body -->
           
            <div class="form-group has-feedback">
              <label>Direccion</label>
             <textarea class="text-area" name="address" required >
               
             </textarea>
            </div>
            
            <!--BOTON ENVIAR -->
            <div class="row">
              <div class="col-xs-12 text-center">
                <!--<button type="submit" class="btn btn-primary btn-block btn-flat"  >Enviar</button>-->
                <input class="boton-envi btn btn-primary btn-block btn-flat"   type="submit" value="Enviar" name="Enviar"/>
              </div>
            </div>
            <a onclick="mostrar02();" class="btn btn-primary btn-block btn-flat">Regresar</a>
          </div>
         
        </form>
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->
  </div>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
     <?php if(isset($mscript)){
       echo $mscript;
     
     } ?>
    <script>
    $("#next2").click(function(){
    
    var name = $('#name').val();
    var di = $('#di').val();
     var currency = $('#currency').val();
    
    if(name !="")
    {
      console.log('name no es vacio');
      // mostrar2();
     var vname = true;
       $("#input-name").removeClass("has-error");
       $("#input-name").addClass("has-success");
    }
    else
      {
          console.log('name vacio');
       $("#input-name").addClass("has-error");
      }
      
        if(di !="")
    {
      console.log('di no es vacio');
    
      var vdi = true;
       $("#input-di").removeClass("has-error");
       $("#input-di").addClass("has-success");
    }
    else
      {
          console.log('di vacio');
       $("#input-di").addClass("has-error");
      }
      
   if(currency !="")
    {
      console.log('currency no es vacio');
    
      var vcurrency = true;
       $("#input-currency").removeClass("has-error");
       $("#input-currency").addClass("has-success");
    }
    else
      {
          console.log('currency vacio');
       $("#input-currency").addClass("has-error");
      } 
      
 
    if(vname == true && vdi == true  && vcurrency == true){
      mostrar2();
    }
    
    });
     $("#next3").click(function(){
    console.log('esta')
    var name2 = $('#first_name').val();
    var last_name = $('#last_name').val();
    var phone = $('#phone').val();
    var email = $('#email').val();
    var posicion = $('#posicion').val();
    
  // <!--NOMBRE -->
 if(name2 !="")
    {
      console.log('name2  esta lleno');
      // mostrar2();
     var vname2 = true;
       $("#input-first_name").removeClass("has-error");
       $("#input-first_name").addClass("has-success");
    }
    else
      {
          console.log('name2 vacio');
       $("#input-first_name").addClass("has-error");
      }
      
      
      
// <!--APELLIDO -->
      if(last_name !="")
    {
      console.log('last_name  esta lleno');
      // mostrar2();
     var vlast_name = true;
       $("#input-last_name").removeClass("has-error");
       $("#input-last_name").addClass("has-success");
    }
    else
      {
          console.log('name2 vacio');
       $("#input-last_name").addClass("has-error");
      }
// <!--POSICION -->
 if(posicion !="")
    {
      console.log('posicion  esta lleno');
      // mostrar2();
     var vposicion = true;
       $("#input-posicion").removeClass("has-error");
       $("#input-posicion").addClass("has-success");
    }
    else
      {
          console.log('posicion vacio');
       $("#input-posicion").addClass("has-error");
      }
// <!--TELEFONO --> 
 if(phone !="")
    {
      console.log('phone  esta lleno');
      // mostrar2();
     var vphone = true;
       $("#input-phone").removeClass("has-error");
       $("#input-phone").addClass("has-success");
    }
    else
      {
          console.log('name2 vacio');
       $("#input-phone").addClass("has-error");
      }
// <!--EMAIL -->
 if(email !="")
    {
      console.log('last_name  esta lleno');
      // mostrar2();
     var vemail = true;
       $("#input-email").removeClass("has-error");
       $("#input-email").addClass("has-success");
    }
    else
      {
          console.log('name2 vacio');
       $("#input-email").addClass("has-error");
      }

    if(vname2 == true && vlast_name == true  &&  vphone == true   &&   vemail  == true    &&   vposicion  == true ){
      mostrar3();
    }
    
    });
    
  function next4(){
    console.log('esta')
    var pais = $('#pais').val();
    var estado = $('#estado').val();
    var ciudad = $('#ciudad').val();
    var municipio = $('#municipio').val();
    
  // <!--PAIS -->
 if(pais!="")
    {
      console.log(' pais esta lleno');
      // mostrar2();
     var vestado = true;
    }
    else
      {
          console.log('name2 vacio');
      }
      
if(estado!="")
    {
      console.log(' ciudad esta lleno');
      // mostrar2();
     var vciudad = true;
    }
    else
      {
          console.log('ciudad vacio');
      }

if(ciudad!="")
    {
      console.log(' ciudad esta lleno');
      // mostrar2();
     var vmunicipio = true;
    }
    else
      {
          console.log('ciudad vacio');
      }
      

      
      
    if(vestado == true){
      
      $("#estado").removeAttr("disabled");
      
    }
     if(vciudad == true){
      
      $("#ciudad").removeAttr("disabled");
      
    }
    if(vmunicipio == true){
      
      $("#municipio").removeAttr("disabled");
      
    }
  }
     function mostrar1() {
            console.log('Mostrar siguiente');
            $('#formu2').slideUp();        
            $('#formu1').slideDown();        
        }
        function mostrar2() {
            console.log('Mostrar siguiente');
            $('#formu1').slideUp();        
            $('#formu2').slideDown();        
        }
        
        function mostrar3() {
            console.log('Mostrar siguiente');
            $('#formu2').slideUp();        
            $('#formu3').slideDown();        
        }
        function mostrar02() {
            console.log('Mostrar siguiente');
            $('#formu3').slideUp();        
            $('#formu2').slideDown();        
        }
      
        
        
    </script>
