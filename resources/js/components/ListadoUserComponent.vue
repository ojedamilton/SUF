<template>
        <div class="container-fluid">
            <div class=" row justify-content-center">
                <div class="col-md-8">
                    <p class="text-center"><strong>LISTADO USUARIO</strong></p>
                    <div class="card">
                        <div class="card-header">
                            <!-- Add users -->
                            <div >
                               <!--  <button v-if="idCan.includes('new')" type="button" class="btn btn-success" @click="abrirModal('user','registrar')" >Nuevo</button><br><br> -->
                            </div>
                            <!-- Find a result -->
                            <input type="text" v-model="buscar"  @keyup="listarUsuarios(1,buscar)" class="form-control" placeholder="Texto a buscar">
                        </div>
                        <!-- List Table users -->
                        <div class="card-body">
                            <table id="table_user" class="table table-striped" width="100%">
                                <thead>
                                <tr>
                                    <th>NOMBRE</th>
                                    <th>APELLIDO</th>
                                    <th>EMAIL</th>
                                    <th>GRUPO/S</th>
                                    <th>EMPRESA/S</th>
                                    <th>ACCIÓN</th>
                                    <!-- <th>PERMISOS</th> -->

                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in arrayUser" :key="user.id">
                                        <td>{{user.name}}</td>
                                        <td>{{user.apellido}}</td>
                                        <td>{{user.email}}</td>
                                        <td>
                                            <span v-for="grupo in user.grupos" :key="grupo.id" class="badge badge-primary">{{ grupo.nombreGrupo }}</span>
                                        </td>
                                        <td>
                                            <span v-for="empresa in user.empresas" :key="empresa.id" class="badge badge-secondary">{{ empresa.nombreEmpresa }}</span>
                                        </td>
                                        <td class="d-flex flex-row ">
                                            <!-- Permiso a Edición Admin -->
                                            <a v-if="idAccionesUser.includes('editAdmin')" class="btn btn-warning btn-sm" @click="editarModal(user);" href="#"><i class="fas fa-edit text-light"></i></a>
                                            <!-- Permiso para Eliminar Admin -->
                                            <a v-if="idAccionesUser.includes('deleteAdmin')" class="btn btn-danger btn-sm ml-1" @click="eliminarUsuario(user.id);" href="#"><i class="fas fa-trash-alt text-light"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-show="erroruser" class="form-group div-error">
                                <div class="text-right">
                                    <div v-text="errorMostrarMsjuser"></div>
                                </div>
                            </div>
                            <nav class="pt-1">
                                <ul class="pagination">
                                    <li class="page-item"  v-if="pagination.current_page > 1 ">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" v-text="page"></a>
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
                            <!-- Le defino el titulo al modal -->
                            <h5 class="modal-title" id="exampleModalLongTitle">{{tituloModal}}</h5>
                            <!-- [x] para cerrar modal -->
                            <button type="button"  @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class='modal-body'>
                            <form action='' method='' enctype='multipart/form-data' class='form-horizontal'>

                                <!-- Nombre -->
                                <div class='form-group row align-items-center'>
                                    <label for="nombreUsuario" class='col-md-2 form-control-label mb-0'>Nombre</label>

                                    <div class='col-md-10'>
                                        <input id='nombreUsuario' class='form-control' type='text' name='nombreUsuario' placeholder='Ingrese un nombre..'  v-model='nombreUsuario' >
                                    </div>
                                </div>

                                <!-- Apellido -->
                                <div class='form-group row align-items-center'>
                                    <label for="apellidoUsuario" class='col-md-2 form-control-label mb-0'>Apellido</label>

                                    <div class='col-md-10'>
                                        <input id='apellidoUsuario' class='form-control' type='text' name='apellidoUsuario' placeholder='Ingrese un apellido..'  v-model='apellidoUsuario' >
                                    </div>
                                </div>

                                <!-- -- Grupos -- -->

                                <div class="form-group">
                                    <label>Grupo/s</label>
                                    <div v-for="grupo in arrayGrupos" :key="grupo.id" class="custom-control custom-checkbox form-group row align-items-center">
                                        <input class="custom-control-input" v-model="idGrupos" type="checkbox" :value="grupo.id" :id="grupo.nombreGrupo"/>
                                        <label :for="grupo.nombreGrupo" class="custom-control-label">{{ grupo.nombreGrupo }}</label>
                                    </div>
                                </div>

                                <!-- -- Empresas -- -->

                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 form-control-label mb-0">Empresa/s</label>
                                        <multiselect
                                            v-model="selectedEmpresa"
                                            :options="arrayEmpresa"
                                            label="nombreEmpresa"
                                            track-by="id"
                                            :multiple="true"
                                            :taggable="true"
                                            placeholder="Seleccione una o varias empresas"
                                        >
                                        </multiselect>
                                    </div>

                                <!-- Errores Validación -->
                                <div v-show='erroruser' class='form-group row align-items-center div-error'>
                                    <div class='text-center text-error'>
                                        <div v-for='error in errorMostrarMsjuser' :key='error' v-text='error'></div>
                                    </div>
                                </div>
                                <!-- Guardo los Cambios -->
                                <div class='d-flex flex-column justify-content-center align-items-center'>

                                    <button class='btn btn-success text-center w-33' id="buttonsend" type='button' v-if='tipoAccion == 2' @click="ActualizarUsuario()">Editar Cambios</button>
                                </div>
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
import Multiselect from "vue-multiselect";
export default {
    components: { Multiselect },
    props:['path'],
    data(){
        return{
            idUser: 0,
            idGrupos: [],
            idAccionesUser:[],
            nombreUsuario: "",
            apellidoUsuario: "",
            loading: false,
            modal: 0,
            idCan: "",
            tituloModal: "",
            tipoAccion: 0,
            buscar: "",
            arrayGrupos: [],
            arrayEmpresa: [],
            arrayUser:[],
            pagination:{
                'total':0,
                'current_page':0,
                'per_page':0,
                'last_page':0,
                'from':0,
                'to':0,
            },
            offset: 3,
            erroruser: 0,
            empresaId: 0,
            errorMostrarMsjuser: [],
            selectedEmpresa: [],
            options: ["list", "of", "options"],
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
         * Listo todos los Usuarios , si hay una busqueda la agrego
         * Traigo los activos en 1 ya que la baja es logica.
         * Deberiamos crear una Columna Status para ver sus estados.
         *
         * @param integer $page
         * @param string $buscar
         * @return void
         */
        listarUsuarios(page,buscar){
            let me = this;
            var url= 'api/usuarios?page='+page+'&buscar='+buscar;
            axios.get( url , {
                params: {
                }
            })
            .then(function (response) {
                var respuesta = response.data;
                me.arrayUser=respuesta.users.data;
                me.pagination= respuesta.pagination;
                me.erroruser=0

                // console.log("Usuarios:", me.arrayUser); 

               /*  me.obtenerGruposPorUsuario();
                me.obtenerEmpresasPorUsuario(); */
            })
            .catch(function (error) {
                console.log(error);
                me.erroruser=1,
                me.errorMostrarMsjuser=error.response.data.message
            });
        },
        /* obtenerGruposPorUsuario() {
            let me = this;
            me.arrayUser.forEach((user, index) => { // Utilizamos el índice para asegurar que los grupos se asignen correctamente
                axios.get('/api/getGruposByUser/' + user.id)
                    .then(function(response) {
                        var respuesta = response.data;
                        me.$set(me.arrayUser[index], 'grupos', respuesta.grupos); // Usamos $set para asignar la propiedad 'grupos'
                    })
                    .catch(function(error) {
                        console.log(error);
                        if (error.response.status === 401) {
                            location.reload(true);
                        } else if (error.response.status === 404) {
                            console.log("Los grupos no se encontraron");
                        }
                    });
            });
        }, */
       /*  obtenerEmpresasPorUsuario() {
            let me = this;
            me.arrayUser.forEach((user, index) => { 
                axios.get('/api/getEmpresasByUser/' + user.id)
                    .then(function(response) {
                        var respuesta = response.data;
                        me.$set(me.arrayUser[index], 'empresas', respuesta.empresas);
                    })
                    .catch(function(error) {
                        console.log(error);
                        if (error.response.status === 401) {
                            location.reload(true);
                        } else if (error.response.status === 404) {
                            console.log("Las empresas no se encontraron");
                        }
                    });
            });
        }, */

        listarEmpresas() {
            let me = this;
            var url = "api/empresas?page=1&buscar=userListado";
            axios
                .get(url) // ,{ params: {},}
                .then(function (response) {
                let empresas = response.data.empresas.data;
                me.arrayEmpresa = empresas;
                me.options = empresas.map((empresa) => empresa.nombreEmpresa);
                // console.log(me.arrayEmpresa);
                })
                .catch(function (error) {
                console.log(error);
                if (error.response.status === 401) {
                    location.reload(true);
                }
                });
        },

        checkEmpresas() {
            console.log(this.selectedEmpresa);
        },
        listarGrupos() {
            let me = this;
            let url = '/api/grupos';
            axios
                .get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayGrupos = respuesta.grupos;
                    //me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                    if (error.response.status === 401) {
                        location.reload(true);
                    } else if (error.response.status === 404) {
                        console.log("Los grupos no se encontraron");
                    }
                });
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
            me.listarUsuarios(page,buscar);
        },

        /**
         * Cierro el modal , Le cambio el Flag a 0
         *
         * @return void
         */
        cerrarModal(){
            this.modal=0;
            this.erroruser=0;
        },

        /**
         * Sobreescribe en las variables que defini anteriormente en el data()return{}
         * Al abrir el modal ya tienen en su v-model estas variables para tomar ese valor
         *
         * @param array  $user
         * @return void
         */
         editarModal(usuario) {
            this.modal = 1;
            this.tituloModal = 'Editar Usuario';
            this.idUser = usuario['id'];
            this.nombreUsuario = usuario['name'];
            this.apellidoUsuario = usuario['apellido'];
            this.selectedEmpresa = usuario.empresas.map(empresa => ({
                id: empresa.id,
                nombreEmpresa: empresa.nombreEmpresa
            }));
            this.idGrupos = usuario.grupos.map(grupo => grupo.id);
            this.tipoAccion = 2;
            // console.log(usuario )
        },

        /**
         * Valido datos de usuario
         * Envio por metodo put los datos
         * verificar /updateUsuario en web.php
         * Controlador UserController.php metodo update()
         *
         * @return SwalFire modal de confirmación
        //  */
        ActualizarUsuario(){
            this.validarUsuario();
            if(this.erroruser==1 ){
                return;
            }
            let me=this;
            var url = '/api/updateUsuario';
            let btn_editar = document.getElementById('buttonsend');
            btn_editar.disabled = true;
            axios.put(url,{
                'idUser':this.idUser,
                'nombreUsuario':this.nombreUsuario,
                'apellidoUsuario':this.apellidoUsuario,
                'idGrupos':this.idGrupos,
                'selectedEmpresa':this.selectedEmpresa,
            }).then(function (response){
                btn_editar.disabled = false;
                // Ciero el modal
                me.cerrarModal();
                // Listo Usuarios asi se Actuaiza la tabla
                me.listarUsuarios( me.pagination.current_page,me.buscar);
                // Creo el mensaje de exito
                swal.fire({
                    title:'Editado!',
                    text:'El Usuario fue Editado.',
                    icon:'success',
                    timer: 1500,
                    timerProgressBar: true,
                })
            }).catch(function(error){
                btn_editar.disabled = false;
                console.log(error);
                if(error.status === 401){
                    location.reload(true)
                }
            });console.log(this.idGrupos,this.selectedEmpresa)
        },

        /**
         * Pop-up de confirmación para eliminar
         * Aceptar: envio por metodo post el idusuario
         * Cancelar : mensaje de cancelación y no se hace nada
         * Verificar /deleteUsuario en web.php
         * Controlador UserController.php metodo destroy()
         *
         * @param integer idusuario
         * @return SwalFire modal de confirmación
         */
        eliminarUsuario(idUser){
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Estas seguro de eliminarlo?',
                // text: "You won't be able to revert this!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
            }).then((result) => {
                if (result.value) {
                    let me=this;
                    var url = '/api/deleteUsuario';
                    axios.post(url,{
                        'id':idUser,
                    }).then(function (response){
                        me.cerrarModal();
                        me.listarUsuarios(me.pagination.current_page,me.buscar);
                        swal.fire({
                            title:'Eliminado!',
                            text:'El registro fue Eliminado.',
                            icon:'success',
                            timer: 1500,
                            timerProgressBar: true,
                        })
                    }).catch(function(error){
                        console.log(error);
                        if(error.status === 401){
                        location.reload(true)
                    }
                    });
                }else if(result.dismiss === Swal.DismissReason.cancel){
                    swal.fire({
                        title: 'Cancelled',
                        text:'Tu registro está a salvo ',
                        icon:'error',
                        timer: 1500,
                        timerProgressBar: true,
                    })
                }
            })
        },
        /**
         * Valido datos de usuario que no esten vacios
         *
         * @return void
         */
        validarUsuario() {
        this.erroruser = 0;
        this.errorMostrarMsjuser = [];
        if (!this.nombreUsuario)
            this.errorMostrarMsjuser.push("* El nombre no puede estar vacío");
        if (!this.apellidoUsuario)
            this.errorMostrarMsjuser.push("* El apellido no puede estar vacío");
        if (this.idGrupos.length == 0)
            this.errorMostrarMsjuser.push("* El Grupo no puede estar vacío");
        if (this.selectedEmpresa.length == 0)
            this.errorMostrarMsjuser.push(
            "* La empresa select no puede estar vacía"
            );
        if (this.errorMostrarMsjuser.length) this.erroruser = 1;
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
    },
    mounted() {
        this.listarUsuarios(1,this.buscar);
        this.listarEmpresas();
        this.listarGrupos();
        this.methodCan();
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
.loader {
  /* Loader Div Class */
  position: absolute;
  top: 0px;
  right: 0px;
  width: 100%;
  height: 100%;
  background-color: #eceaea;
  background-image: url("/webfonts/ajax-loader.gif");
  background-size: 50px;
  background-repeat: no-repeat;
  background-position: center;
  z-index: 10000000;
  opacity: 0.4;
  filter: alpha(opacity=40);
}

/* .custom-select {
    background-color: #f8f8f8;
    color: #333;
} */
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

