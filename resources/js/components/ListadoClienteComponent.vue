<template>
    <div class="container-fluid">
        <div class=" row justify-content-center">
            <div class="col-md-8">
                <p class="text-center"><strong>ABM CLIENTE</strong></p>
                <div class="card">
                    <div class="card-header">
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
                                    <th>DIRECCIÓN</th>
                                    <th>TELEFONO</th>
                                    <th>ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="clientes in arrayCliente" :key="clientes.id">
                                    <td>{{clientes.nombreCliente}}</td>
                                    <td>{{clientes.apellidoCliente}}</td>
                                    <td>{{clientes.dniCliente}}</td>
                                    <td>{{clientes.emailCliente}}</td>
                                    <td>{{clientes.direccionCliente}}</td>
                                    <td>{{clientes.telefonoCliente}}</td>
                                    <td>
                                        <a class="pr-2" @click="editarModal(clientes);" href="#"><i class="fas fa-edit text-warning"></i></a>
                                        <a class="pr-2" @click="eliminarCliente(clientes.id);" href="#"><i class="fas fa-trash-alt text-danger"></i></a>
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
                                    <label class='col-md-2 form-control-label mb-0'>Nombre</label>
    
                                    <div class='col-md-10'>
                                        <input id='Nombre' class='form-control' type='text' name='' placeholder='Ingrese una nombrecliente..'  v-model='nombreCliente' >
                                    </div>
                                </div>
    
                                <!-- Apellido -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Apellido</label>
    
                                    <div class='col-md-10'>
                                        <input id='apellidoCliente' class='form-control' type='text' name='' placeholder='Ingrese una apellidoCliente..'  v-model='apellidoCliente' >
                                    </div>
                                </div>
    
                                <!-- Cuit -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Cuit</label>
    
                                    <div class='col-md-10'>
                                        <input id='cuitCliente' class='form-control' type='text' name='' placeholder='Ingrese una cuitCliente..'  v-model='cuitCliente' >
                                    </div>
                                </div>
    
                                <!-- Email -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Email</label>
    
                                    <div class='col-md-10'>
                                        <input id='emailCliente' class='form-control' type='text' name='' placeholder='Ingrese una emailCliente..' v-model='emailCliente' >
                                    </div>
                                </div>

                                <!-- Dirección -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Dirección</label>
    
                                    <div class='col-md-10'>
                                        <input id='emailCliente' class='form-control' type='text' name='' placeholder='Ingrese una direccionCliente..' v-model='direccionCliente' >
                                    </div>
                                </div>

                                <!-- Telefono -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Telefono</label>
    
                                    <div class='col-md-10'>
                                        <input id='Telefono' class='form-control' type='text' name='' placeholder='Ingrese una telefonoCliente..'  v-model='telefonoCliente' >
                                    </div>
                                </div>

                                <!-- Errores Validación -->
                                <div v-show='errorcliente' class='form-group row align-items-center div-error'>
                                    <div class='text-center text-error'>
                                        <div v-for='error in errorMostrarMsjuser' :key='error' v-text='error'></div>
                                    </div>
                                </div>
                                <!-- Guardo los Cambios -->
                                <div class='d-flex flex-column justify-content-center align-items-center'>
    
                                    <button class='btn btn-success text-center w-33' type='button' v-if='tipoAccion == 2' @click="ActualizarCliente()">Editar Cambios</button>
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
import ModalReutilizable from './partials/ModalReutilizable.vue';
export default {
  components: { ModalReutilizable },
    props:['path'],
    data(){
        return{
            idCliente:0,
            modal:0,
            idCan:'',
            tituloModal:'',
            nombreCliente:'',
            apellidoCliente:'',
            cuitCliente:'',
            emailCliente:'',
            direccionCliente:'',
            telefonoCliente:'',
            description:'',
            tipoAccion:0,
            buscar:'',
            arrayCliente:[],
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
         * Listo todos los Clientes , si hay una busqueda la agrego
         * Traigo los activos en 1 ya que la baja es logica.
         * Deberiamos crear una Columna Status para ver sus estados.
         * 
         * @param integer $page
         * @param string $buscar
         * @return void
         */
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
                    me.pagination= response.data.pagination;

                })
                .catch(function (error) {
                    console.log(error);
                    if(error.status === 401){
                        location.reload(true)
                    }
                })
                .then(function () {
                    // always executed
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
            me.listarcliente(page,buscar);
        },

        /**
         * Cierro el modal , Le cambio el Flag a 0
         * 
         * @return void
         */
        cerrarModal(){
            this.modal=0;
        },

        /**
         * Sobreescribe en las variables que defini anteriormente en el data()return{}
         * Al abrir el modal ya tienen en su v-model estas variables para tomar ese valor
         * 
         * @param array  $cliente 
         * @return void
         */
        editarModal(cliente){
            this.modal=1;
            this.tituloModal='Editar Cliente';
            this.idCliente=cliente['id'];
            this.nombreCliente=cliente['nombreCliente'];
            this.apellidoCliente=cliente['apellidoCliente'];
            this.cuitCliente=cliente['dniCliente'];
            this.emailCliente=cliente['emailCliente'];
            this.telefonoCliente=cliente['telefonoCliente'];
            this.direccionCliente=cliente['direccionCliente'];
            this.tipoAccion=2;     
        },

        /**
         * Valido datos de cliente
         * Envio por metodo put los datos
         * verificar /updateCliente en web.php
         * Controlador ClienteController.php metodo update()
         * 
         * @return SwalFire modal de confirmación
         */
        ActualizarCliente(){
            this.validarCliente();
            if(this.errorRole==1 ){
                return;
            } 
            let me=this;
            var url = '/updateCliente';
            axios.put(url,{
                'nombreCliente':this.nombreCliente,
                'apellidoCliente':this.apellidoCliente,
                'cuitCliente':this.cuitCliente,
                'emailCliente':this.emailCliente,
                'telefonoCliente':this.telefonoCliente,
                'direccionCliente':this.direccionCliente,
                'idCliente':this.idCliente,
            }).then(function (response){
                // Ciero el modal
                me.cerrarModal();
                // Listo Cliente asi se Actuaiza la tabla
                me.listarcliente( me.pagination.current_page,me.buscar);
                // Creo el mensaje de exito
                swal.fire({
                    title:'Editado!',
                    text:'El Cliente fue Editado.',
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
        },

        /**
         * Pop-up de confirmación para eliminar
         * Aceptar: envio por metodo post el idcliente
         * Cancelar : mensaje de cancelación y no se hace nada
         * Verificar /deleteCliente en web.php
         * Controlador ClienteController.php metodo destroy()
         * 
         * @param integer idcliente
         * @return SwalFire modal de confirmación
         */
        eliminarCliente(idcliente){
            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Estas seguro de eliminarlo?',
                text: "You won't be able to revert this!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
            }).then((result) => {
                if (result.value) {  
                    let me=this;
                    var url = '/deleteCliente';
                    axios.post(url,{
                        'idCliente':idcliente,
                    }).then(function (response){
                        me.cerrarModal();
                        me.listarcliente(me.pagination.current_page,me.buscar);
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
                        text:'Your imaginary file is safe ',
                        icon:'error',
                        timer: 1500,
                        timerProgressBar: true,
                    })
                }  
            })          
        },
        /**
         * Valido datos de cliente que no esten vacios
         * 
         * @return void
         */
        validarCliente(){
            this.errorcliente=0;
            this.errorMostrarMsjuser=[];
            if(!this.nombreCliente) this.errorMostrarMsjuser.push('* El nombre de Cliente no puede estar vacío');
            if(!this.apellidoCliente) this.errorMostrarMsjuser.push('* El apellido de Cliente no puede estar vacío');
            if(this.errorMostrarMsjuser.length) this.errorcliente=1;
        },
    },
    mounted() {
          this.listarcliente(1,this.buscar);
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