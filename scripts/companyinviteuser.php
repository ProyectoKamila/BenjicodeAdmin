<script type="text/javascript" src="">
    $(document).on("ready", inviteuser);
    console.log('busqueda');
    function ininviteuser(valor){
        $.ajax({
            url: "<?php echo base_url(); ?>",
            type:"POST",
            data:{search:valor},
            success:function(respuesta){
                alert(respuesta);
            }
        })
    }
</script>