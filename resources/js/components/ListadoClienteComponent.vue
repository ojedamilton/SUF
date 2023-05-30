<template>
    <div class="container-fluid">
        <div class=" row justify-content-center">
            <div class="col-md-8">
                <p class="text-center"><strong>ABM CLIENTE</strong></p>
                <div class="card">
                    <div class="card-header">
                        <!-- Add users -->
                        <div >
                           <!--  <button v-if="idCan.includes('new')" type="button" class="btn btn-success" @click="abrirModal('user','registrar')" >Nuevo</button><br><br> -->
                        </div>
                        <!-- Find a result -->
                        <input type="text" v-model="buscar"  @keyup="listarcliente(1,buscar)" class="form-control" placeholder="Texto a buscar">
                    </div>
                    <!-- List Table users -->
                    <div class="card-body">
                        <table id="table_clientes" class="table table-striped" width="100%">
                            <thead>
                            <tr>
                                <th>NOMBRE</th>
                                <th>APELLIDO</th>
                                <th>CUIT</th>
                                <th>EMAIL</th>
                                <th>ACCIÓN</th>
                                <!-- <th>PERMISOS</th> -->

                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="clientes in arrayCliente" :key="clientes.id">
                                    <td>{{clientes.nombreCliente}}</td>
                                    <td>{{clientes.apellidoCliente}}</td>
                                    <td>{{clientes.dniCliente}}</td>
                                    <td>{{clientes.emailCliente}}</td>
                                    <td>
                                        <a><i class="fas fa-edit"></i></a>
                                        <a><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item"  v-if="pagination.current_page > 1 ">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" v-text="page">1</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page "  >
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page+1,buscar)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" :class="{'mostrar': modal}" style="display: none;" id="exampleModalCenter" tabindex="-1" user="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" user="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">{{tituloModal}}</h5>
                            <button type="button"  @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-2 form-control-label">Rol</label>
                                    <div class="col-md-10">
                                        <div v-for=" roles in arrayRoles" :key="roles.id" class="custom-control custom-checkbox">
                                            <div v-if="idRoleUser.includes(roles.id)">
                                                <input v-model="idRoleUser" class="custom-control-input" type="checkbox"  ref="input_nombre" :value="roles.id" :id="roles.name" checked="checked" > <!-- cheked="" -->
                                                <label :for="roles.name" class="custom-control-label">{{roles.name}}</label>
                                            </div>
                                           <div v-else>
                                              <input class="custom-control-input" v-model="idRoleUser" type="checkbox" :value="roles.id"   ref="input_nombre" :id="roles.name"> 
                                              <label :for="roles.name" class="custom-control-label">{{roles.name}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-show="errorcliente" class="text-leftform-group row div-error">
                                    <div class="text-left text-error">
                                        <div v-for="error in errorMostrarMsjuser" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" v-if="tipoAccion==1"  @click="registraroluser()" class="btn btn-success">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" @click="Actualizaroluser()" class="btn btn-success">Actualizar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    props:['path'],
    data(){
        return{
            idCliente:0,
            idRol:[],
            idRoleUser:[],
            idRolVmodle:[],
            idRolBack:0,
            modal:0,
            idCan:'',
            tituloModal:'',
            nombrecliente:'',
            description:'',
            tipoAccion:0,
            buscar:'',
            arrayCliente:[],
            arrayRoles:[],
            pagination:{
                'total':0,
                'current_page':0,
                'per_page':0,
                'last_page':0,
                'from':0,
                'to':0,
            },
            offset:3,
            errorcliente:0,
            errorMostrarMsjuser:[],
        }
    },
    computed:{
        isActived: function(){
            return this.pagination.current_page;
        },
        pagesNumber: function (){
            if(!this.pagination.to){
                return[]
            }
            var from= this.pagination.current_page - this.offset;
            if(from < 1){
                from = 1;
            }
            var to = from + (this.offset * 2);
            if(to >= this.pagination.last_page){
                to = this.pagination.last_page;
            }

            var pagesArray=[];
            while(from <= to){
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },
     methods:{
        listarcliente(page,buscar){
            let me = this;
            var url= '/clientes?page='+page+'&buscar='+buscar;
            axios.get( url , {
                params: {
                }
            })
                .then(function (response) {
                    // destructuro la respuesta para obtener los clientes
                    var {clientes} = response.data;
                    me.arrayCliente=clientes.data;
                    //respuesta.array_namesector.map(value=>{ me.arraynameSector.push(value.sector);});
                    me.pagination= respuesta.pagination;

                })
                .catch(function (error) {
                    console.log(error);
                    if(error.response.status === 401){
                        location.reload(true)
                    }
                })
                .then(function () {
                    // always executed
                });
        },
         cambiarPagina(page,buscar){
            let me = this;
            //Actualizar pagina actual
            me.pagination.current_page=page;
            //Enviar la petición para visualizar la data de esa página
            me.listarcliente(page,buscar);
        },
         cerrarModal(){
            this.modal=0;
            this.nombreuser="";
            this.description="";
        },
         abrirModal(modelo, accion, data=[]){
            let self = this
          /*   Vue.nextTick()
                .then(function () {
                    self.$refs.input_nombre.focus();
            }); */
            switch(modelo){
                case "user":
                {
                    switch(accion){
                        case "registrar":{
                            this.modal=1;
                            this.tituloModal='Registrar Usuario';
                            this.nombreuser='';
                            this.description='';
                            this.tipoAccion=1;
                            break;
                        }
                        case "actualizar":{
                            this.modal=1;
                            this.tituloModal='Asignar Rol Usuario';
                            this.idUser=data['id'];
                            this.idRol=data['idrol'];
                            this.idRoleUser=[];
                            data['roles'].forEach(element => this.idRoleUser.push(element['id']));
                            this.idRolBack=data['idrol'];
                            this.tipoAccion=2;
                            break;
                        }
                    }
                }
            }
        },
        validaruser(ubicacionmodal){
            this.errorcliente=0;
            this.errorMostrarMsjuser=[];
            //if(!this.idRol) this.errorMostrarMsjuser.push('* El rol no puede estar vacío');
            if(this.errorMostrarMsjuser.length) this.errorcliente=1;
        },
    },
    mounted() {
          this.listarcliente(1,this.buscar);
        //   this.listarRoles();
        //   this.methodCan();
    }
}
</script>
<style>
.modal-content{
    width: 100%;
    position: absolute !important;
}
.mostrar{
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color: #3c29297a !important;
}
.div-error{
    display: flex;
    justify-content: left;
}
.div-error{
    color: red;
    font-weight: bold;
}
</style>