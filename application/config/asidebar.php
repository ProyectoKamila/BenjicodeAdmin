</div>
</div>
<aside id="sidebar">
    <div class="logo">
        <a href="<?= base_url() . 'carrito/carrito' ?>">  <img src="./public/images/logo.png" alt="logo menu" /></a>
    </div>
    <ul class="opcion">
        <?
        $varia = $this->session->userdata('usuario');
        if($varia[0]['permisos'] >= 3){?>
        <li class="user" id="user"><img src="./images/admin/controles/usuario.png" alt="usuario" title="usuario"/></li>
        <li class="user-group">
            <a href="<?= base_url() . 'carrito/registro' ?>" ><img src="./images/admin/controles/add-usuario.png" alt=" Agregar usuario" title="Agregar usuario"/></a>
        </li>
        <li class="user-group">    
            <a href="<?= base_url() . 'carrito/ver_usua/'; ?>" ><img src="./images/admin/controles/ver-usuario.png" alt=" Agregar usuario" title="Agregar usuario"/></a>
        </li>
        <? }
        ?>  
        <?
        $varia = $this->session->userdata('usuario');
        if($varia[0]['permisos'] >= 2){?>
        <li class="user" id="prod"><img src="./images/admin/controles/productos.png" alt="" title="Producto"/></li>
        <li class="prod-group">
            <a href="<?= base_url() . 'carrito/reg_pro' ?>" ><img src="./images/admin/controles/add-producto.png" alt="Agregar producto" title="Agregar Producto"/></a>
        </li>
        <li class="prod-group">
            <a href="<?= base_url() . 'carrito/ver_pro' ?>" ><img src="./images/admin/controles/ver-producto.png" alt="Ver producto" title="Ver Producto"/></a>
        </li>
        <? }
        ?>  
        <?
        $varia = $this->session->userdata('usuario');
        if($varia[0]['permisos'] >= 3){?>
        <li class="user" id="account"><img src="./images/admin/controles/mi-empresa.png" alt="Perfil de La empresa" title="Mi Perfil"/></li>
        <li class="account-group">
            <a href="<?= base_url() . 'carrito/miempresa' ?>" ><img src="./images/admin/controles/editar-mi-empresa.png" alt="Perfil de La empresa" title="Editar Perfil"/></a>
        </li>
        <li class="account-group">
            <a href="<?= base_url() . 'carrito/view_saldo' ?>" ><img src="./images/admin/controles/agregar-saldo.png" alt="Perfil de La empresa" title="Agregar Saldo"/></a>
        </li>
        <? }
        ?> 
        <?
        $varia = $this->session->userdata('usuario');
        if($varia[0]['permisos'] >= 2){?>
        <li class="account-group">
            <a href="<?= base_url() . 'carrito/publicacion' ?>" ><img src="./images/admin/controles/generar-publicacion.png" alt="generar-publicaciones" title="generar-publicaciones"/></a>
        </li>
        <? }
        ?> 
        <?
        $varia = $this->session->userdata('usuario');
        if($varia[0]['permisos'] >= 3){?>
        <li class="account-group">
            <a href="<?= base_url() . 'carrito/view_pagos' ?>" ><img src="./images/admin/controles/ver-pagos.png" alt="Perfil de La empresa" title="Ver Pagos"/></a>
        </li>
        <? }
        ?> 
        <?
        $varia = $this->session->userdata('usuario');
        if($varia[0]['permisos'] >= 1){?>
        <li class="questions">
            <a href="<?= base_url() . 'carrito/ver_preguntas' ?>" ><img src="./images/admin/controles/questions.png" alt="Perfil de La empresa" title="Ver Preguntas"/></a>
        </li>
        <? }
        ?> 
        <?
        $varia = $this->session->userdata('usuario');
        if ($varia[0]['permisos'] >= 4) {
            ?>
            <li class="user" id="adm-bus"><img src="./images/admin/controles/gestion.png" alt="Perfil de La empresa" title="Gestionar Empresa"/></li>
            <li class="admbus-group">
                <a href="<?= base_url() . 'carrito/empresas' ?>" ><img src="./images/admin/controles/kamila-ver-empresas.png" alt="empresa" title="Ver empresas Registradas"/></a>

            </li>
            <li class="admbus-group">
                <a href="<?= base_url() . 'carrito/generar_cobros' ?>" ><img src="./images/admin/controles/kamila-generar-cobros.png" alt="Perfil de La empresa" title="generar-cobros"/></a>
            </li>

            <li class="admbus-group">
                <a href="<?= base_url() . 'carrito/view_cuenta' ?>" ><img src="./images/admin/controles/kamila-crear-cuentas.png" alt="Perfil de La empresa" title="Crear-Cuentass"/></a>
            </li>

            <li class="admbus-group">
                <a href="<?= base_url() . 'carrito/view_pagos_empresa' ?>" ><img src="./images/admin/controles/kamila-ver-pagos.png" alt="Perfil de La empresa" title="Ver-Pagos"/></a>
            </li>
            <li class="admbus-group">
                <a href="<?= base_url() . 'carrito/view_config' ?>" ><img src="./images/admin/controles/kamila-conf.png" alt="Configuraciones" title="Configuracion de Empresa"/></a>
            </li>
        <? }
        ?>
        <li class="back">
            <img src="./images/admin/controles/volver.png" alt="Ver Todas Las opciones" title="Ver Todas las Opciones"/>
        </li>
    </ul>
    <span class="shadow"></span>
</aside>
</div>