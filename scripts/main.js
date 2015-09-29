
    function custom_search(value){
        console.log(value);
        /*if(value == "jose.vivas@proyectokamila.com"){
            $("#while-search").addClass("hidden");
            $("#box-result-search").removeClass("hidden");
        }else{
            $("#box-result-search").addClass("hidden");
            $("#while-search").removeClass("hidden");
        }*/
        
        
        
        if(value == ""){
            $("#box-result-search").addClass("hidden");
            $("#while-search").addClass("hidden");
        }else{
            $("#while-search").addClass("hidden");
            $("#box-result-search").removeClass("hidden");
            $.ajax({
                url:"./panel/companyInviteUser_ajax",
                type: "POST",
                data: {search:value},
                success:function($consult){
                   // console.log($consult);
                    var registros = eval($consult);
                    html ='';
                    
                    for(var i = 0; i < registros.length; i++){
                        //html = registros[i]['name'];
                        html+='<li class="'+i+'" onclick="addLista('+registros[i]['user']+');">';
                        html+='<img src="http://pknetmarketing.com/images/'+registros[i]['picture']+'" alt="User Image" class="min-img-profile"  title="'+ registros[i]['name']+' '+registros[i]['last_name']+'"/>';
                        html+='<a class="users-list-name"  title="'+ registros[i]['name']+' '+registros[i]['last_name']+'" >'+registros[i]['name']+' '+registros[i]['last_name']+'</a>';
                        html+='</li>';
                    }
                    $("#respuesta").html(html);
                    $("cont").html(i);
                }
            });
        }
    }
    /*function addLista(id){
        console.log("addLista");
        console.log(id);
    }*/
    
    /*
          $(document).on("ready", inviteuser);
          console.log('busqueda');
          function inviteuser(valor){
              $.ajax({
                  url: "http://benjicode-pkadmin.c9.io/panel/companyInviteUser_ajax",
                  type:"POST",
                  data:{search:valor},
                  success:function(respuesta){
                      alert(respuesta);
                  }
              })
          }
      
*/