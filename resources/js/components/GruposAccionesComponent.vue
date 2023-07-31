<template>
    <div class="container-fluid">
        <div class=" row justify-content-center">
            <div class="col-md-8">
                <p class="text-center"><strong>GRUPOS - ACCIONESS</strong></p>
                <div class="card">
                    <div class="card-header">
                        <!-- Find a result -->
                        <input type="text" v-model="buscar"  @keyup="listarGrupo(1,buscar)" class="form-control" placeholder="Texto a buscar">
                    </div>
                    <!-- List Table users -->
                    <div class="card-body">
                        <table id="table_clientes" class="table table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>GRUPO</th>
                                    <th>ACCIONES</th>
                                    <th>ADMINISTRAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="grupo in arrayGrupo" :key="grupo.id">
                                    <td>{{grupo.id}}</td>
                                    <td class="badge badge-primary mt-2">
                                        <!-- <span class="badge badge-primary"> -->
                                            {{grupo.nombreGrupo}}
                                       <!--  </span> -->
                                    </td>
                                    <td>
                                        <span :title="accion.descripcionAccion" v-for="accion in grupo.acciones" :key="accion.id"  class="badge badge-success mr-1">
                                            {{ accion.nombreAccion }}
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Permiso con Edición Admin -->
                                        <a v-if="idAccionesUser.includes('editAdmin')" class="btn btn-secondary btn-sm ml-4" id="botoneditar"  @click="abrirModal(grupo.acciones,grupo.id)">
                                            <i class="fa fa-wrench text-light"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" :class="{'mostrar': modal}" style="display: none;" id="exampleModalCenter" tabindex="-1" Permisos="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog  modal-dialog-centered" Permisos="document">
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
                                    <label class="col-md-5 text-center bg-secondary form-control-label">Acciones</label>
                                    <div class="col-md-9">
                                            <div v-for=" permisos in arrayAcciones" :key="permisos.id" class="custom-control custom-checkbox">
                                                <div v-if="idPermissionUser.includes(permisos.id)"> 
                                                    <input v-model="idPermissionUser" class="custom-control-input" type="checkbox" :value="permisos.id" :id="permisos.nombreAccion" checked="checked" > <!-- cheked="" -->
                                                    <label :for="permisos.nombreAccion" class="custom-control-label">{{permisos.nombreAccion}}</label>
                                                </div>
                                            <div v-else>
                                                <input class="custom-control-input" v-model="idPermissionUser" type="checkbox" :value="permisos.id" :id="permisos.nombreAccion"> 
                                                <label :for="permisos.nombreAccion" class="custom-control-label">{{permisos.nombreAccion}}</label>  
                                            </div> 
                                            
                                        </div>
                                    </div>    
                                </div>  
                                <!-- <div v-show="errorRolPermisos" class="text-leftform-group row div-error">
                                    <div class="text-left text-error">
                                        <div v-for="error in errorMostrarMsjRolPermisos" :key="error" v-text="error">
                                        </div>
                                    </div>
                                </div> -->
                                <button type="button"  @click="ActualizarGrupoAcciones()" class="btn btn-success">Actualizar</button>
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
            idGrupo:0,
            nombreGrupo:'',
            descripcionGrupo:'',
            tipoGrupo:0,
            idAccionesUser:'',
            modal:0,
            tituloModal:'',
            idPermissionUser:[],
            idPermisos:0,
            idPermisosBack:0,
            idRolBack:0,
            tipoAccion:0,
            buscar:'',
            arrayGrupo:[],
            arrayAcciones:[],
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

        /**
         * Listo Grupos , si hay una busqueda la agrego
         * Traigo los activos en 1 ya que la baja es logica.
         * Deberiamos crear una Columna Status para ver sus estados.
         * 
         * @param integer $page
         * @param string $buscar
         * @return void
         */
        listarGrupo(page,buscar){
            /* if (buscar.length==0) {
                buscar='*';
            } */
            let me = this;
            var url= 'api/grupos';
            axios.get( url , {
                params: {
                }
            })
            .then(function (response) {
                // destructuro la respuesta para obtener los stocks
                var {grupos} = response.data;
                me.arrayGrupo=grupos;
                //me.pagination= response.data.pagination;

            })
            .catch(function (error) {
                console.log(error);
                if(error.status === 401){
                    location.reload(true)
                }
            })
        },
        listarAcciones(page,buscar){
            let me = this;
            var url= 'api/acciones?page='+page+'&buscar='+buscar+'';
            axios.get( url , {
                params: {
                }
            })
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayAcciones=respuesta.acciones.data;
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
        ActualizarGrupoAcciones(){
            let me=this;
            var url = 'api/updateGrupoAcciones';
            axios.put(url,{
                'idGrupo':this.idGrupo,
                'permisos':this.idPermissionUser, 
            }).then(function (response){
                me.cerrarModal();
                me.listarGrupo(1,'');
                swal.fire({
                    title:'Editado!',
                    text:'El registro fue Editado.',
                    icon:'success',
                    timer: 1500,
                    timerProgressBar: true,
                })
            
            }).catch(function(error){
                console.log(error);
                if(error.response.status === 401){
                    location.reload(true)
                }
            });
        },
        methodCan(){
            let me=this;
            var url= 'api/grupoAccionesByUser';
            axios.get(url,{
                    params: {
                }
            }).then(function (response){
                me.idAccionesUser=response.data.acciones; 
            }).catch(function(error){
                console.log(error);
                if(error.response.status === 401){
                    location.reload(true)
                }
            });

        },
        cerrarModal(){
                this.modal=0;
                this.idPermisos="";
                this.idRol=0;
        },
        abrirModal(acciones=[],idgrupo){
            let self = this;    
            this.modal=1;
            this.tituloModal='Actualizar Grupo-Accion';
            this.idGrupo=idgrupo;
            this.idPermissionUser=[];
            acciones.forEach(accion => this.idPermissionUser.push(accion['id']));
            this.tipoAccion=2; 
        },
        /**
         * Cambiar de pagina en la tabla
         * 
         * @param integer page
         * @param string buscar
         * @return void
         */
        cambiarPagina(page,buscar){
            let me = this;
            //Actualizar pagina actual
            me.pagination.current_page=page;
            //Enviar la petición para visualizar la data de esa página
            me.listarGrupo(page,buscar);
        },

    },
    mounted() {
        this.listarGrupo(1,this.buscar);
        this.listarAcciones(1,this.buscar);
        this.methodCan();
    }
}
</script>