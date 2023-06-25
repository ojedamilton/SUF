<template>
    <div class="container-fluid">
        <div class=" row justify-content-center">
            <div class="col-md-8">
                <p class="text-center"><strong>LISTADO EMPRESAS</strong></p>
                <div class="card">
                    <div class="card-header">
                        <!-- Add users -->
                        <div >
                           <!--  <button v-if="idCan.includes('new')" type="button" class="btn btn-success" @click="abrirModal('user','registrar')" >Nuevo</button><br><br> -->
                        </div>
                        <!-- Find a result -->
                        <input type="text" v-model="buscar"  @keyup="listarEmpresas(1,buscar)" class="form-control" placeholder="Texto a buscar">
                    </div>
                    <!-- List Table users -->
                    <div class="card-body">
                        <table id="table_valores" class="table table-striped" width="100%">
                            <thead>
                            <tr>
                                <th>NOMBRE</th>
                                <th>RAZON SOCIAL</th>
                                <th>CUIT</th>
                                <th>TIPO EMPRESA</th>
                                <th>ACCIÓN</th>
                                <!-- <th>PERMISOS</th> -->

                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="empresa in arrayEmpresa" :key="empresa.id">
                                    <td>{{empresa.nombreEmpresa}}</td>
                                    <td>{{empresa.razonSocial}}</td>
                                    <td>{{empresa.cuitEmpresa}}</td>
                                    <td>{{ tipoEmpresa(empresa.idTipoEmpresa) }}</td>
                                    <td>
                                        <a class="pr-2" @click="editarModal(empresa);" href="#"><i class="fas fa-edit text-warning"></i></a>
                                        <a class="pr-2" @click="eliminarEmpresa(empresa.id);" href="#"><i class="fas fa-trash-alt text-danger"></i></a>
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
                                        <input id='Nombre' class='form-control' type='text' name='' placeholder='Ingrese un nombre..'  v-model='nombreEmpresa' >
                                    </div>
                                </div>
    
                                <!-- Razon Social -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Razon Social</label>
    
                                    <div class='col-md-10'>
                                        <input id='razonSocial' class='form-control' type='text' name='' placeholder='Ingrese una razon social..'  v-model='razonSocial' >
                                    </div>
                                </div>
    
                                <!-- Cuit -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Cuit</label>
    
                                    <div class='col-md-10'>
                                        <input id='cuitEmpresa' class='form-control' type='text' name='' placeholder='Ingrese un cuit..'  v-model='cuitEmpresa' >
                                    </div>
                                </div>
    
                                <!-- Ingresos Brutos -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Ingresos Brutos</label>
    
                                    <div class='col-md-10'>
                                        <input id='ingresosBrutosEmpresa' class='form-control' type='text' name='' placeholder='Ingrese un ingreso bruto..' v-model='ingresosBrutosEmpresa' >
                                    </div>
                                </div>

                                <!-- Dirección -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Dirección</label>
    
                                    <div class='col-md-10'>
                                        <input id='direccionEmpresa' class='form-control' type='text' name='' placeholder='Ingrese una direccion..' v-model='direccionEmpresa' >
                                    </div>
                                </div>

                                <!-- Telefono -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Telefono</label>
    
                                    <div class='col-md-10'>
                                        <input id='Telefono' class='form-control' type='text' name='' placeholder='Ingrese un telefono..'  v-model='telEmpresa' >
                                    </div>
                                </div>

                                <!-- Inicio Actividades -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Inicio Actividades</label>
    
                                    <div class='col-md-10'>
                                        <input id='Actividades' class='form-control' type='text' name='' placeholder='Ingrese una fecha..'  v-model='inicioActividades' >
                                    </div>
                                </div>

                                <!-- Telefono -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Tipo Empresa</label>
    
                                    <div class='col-md-10'>
                                        <input id='Telefono' class='form-control' type='text' name='' placeholder='Ingrese una telefono..'  v-model='idTipoEmpresa' >
                                    </div>
                                </div>

                                <!-- Errores Validación -->
                                <div v-show='errorempresa' class='form-group row align-items-center div-error'>
                                    <div class='text-center text-error'>
                                        <div v-for='error in errorMostrarMsj' :key='error' v-text='error'></div>
                                    </div>
                                </div>
                                <!-- Guardo los Cambios -->
                                <div class='d-flex flex-column justify-content-center align-items-center'>
    
                                    <button class='btn btn-success text-center w-33' type='button' v-if='tipoAccion == 2' @click="ActualizarEmpresa()">Editar Cambios</button>
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
// import ModalReutilizable from './partials/ModalReutilizable.vue';
export default {
//   components: { ModalReutilizable },
    props:['path'],
    data(){
        return{
            idEmpresa:0,
            modal:0,
            idCan:'',
            tituloModal:'',
            nombreEmpresa:'',
            razonSocial:'',
            cuitEmpresa:'',
            ingresosBrutosEmpresa:'',
            direccionEmpresa:'',
            telEmpresa:'',
            inicioActividades:'',
            idTipoEmpresa:'',
            description:'',
            tipoAccion:0,
            buscar:'',
            arrayEmpresa:[],
            pagination:{
                'total':0,
                'current_page':0,
                'per_page':0,
                'last_page':0,
                'from':0,
                'to':0,
            },
            offset:3,
            errorempresa:0,
            errorMostrarMsj:[],
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
         * Listo todas las Empresas , si hay una busqueda la agrego
         * Traigo los activos en 1 ya que la baja es logica.
         * Deberiamos crear una Columna Status para ver sus estados.
         * 
         * @param integer $page
         * @param string $buscar
         * @return void
         */
         tipoEmpresa(idTipoEmpresa) {
            if (idTipoEmpresa === 1) {
                return 'Responsable inscripto';
            } else if (idTipoEmpresa === 2) {
                return 'Excento Iva';
            } else if (idTipoEmpresa === 3) {
                return 'Monotributista';
            }
            return '';
        },
        listarEmpresa(page,buscar){
            let me = this;
            var url= '/api/empresas?page='+page+'&buscar='+buscar;
            axios.get( url , {
                params: {
                }
            })
                .then(function (response) {
                    // destructuro la respuesta para obtener los empresas
                    var {empresas} = response.data;
                    me.arrayEmpresa=empresas.data;
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
            me.listarEmpresa(page,buscar);
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
         * @param array  $empresa 
         * @return void
         */
        editarModal(empresa){
            this.modal=1;
            this.tituloModal='Editar Empresa';
            this.idEmpresa=empresa['id'];
            this.nombreEmpresa=empresa['nombreEmpresa'];
            this.razonSocial=empresa['razonSocial'];
            this.cuitEmpresa=empresa['cuitEmpresa'];
            this.ingresosBrutosEmpresa=empresa['ingresosBrutosEmpresa'];
            this.telEmpresa=empresa['telEmpresa'];
            this.inicioActividades=empresa['inicioActividades']
            this.direccionEmpresa=empresa['direccionEmpresa'];
            this.idTipoEmpresa=empresa['idTipoEmpresa']
            this.tipoAccion=2;     
        },

        /**
         * Valido datos de empresa
         * Envio por metodo put los datos
         * verificar /updateEmpresa en web.php
         * Controlador EmpresaController.php metodo update()
         * 
         * @return SwalFire modal de confirmación
         */
        ActualizarEmpresa(){
            this.validarEmpresa();
            if(this.errorRole==1 ){
                return;
            } 
            let me=this;
            var url = '/api/updateEmpresa';
            axios.put(url,{
                'nombreEmpresa':this.nombreEmpresa,
                'razonSocial':this.razonSocial,
                'cuitEmpresa':this.cuitEmpresa,
                'ingresosBrutosEmpresa':this.ingresosBrutosEmpresa,
                'telEmpresa':this.telEmpresa,
                'inicioActividades':this.inicioActividades,
                'direccionEmpresa':this.direccionEmpresa,
                'idTipoEmpresa':this.idTipoEmpresa,
                'idEmpresa':this.idEmpresa,
            }).then(function (response){
                // Ciero el modal
                me.cerrarModal();
                // Listo Empresa asi se Actuaiza la tabla
                me.listarEmpresa( me.pagination.current_page,me.buscar);
                // Creo el mensaje de exito
                swal.fire({
                    title:'Editado!',
                    text:'La empresa fue Editada.',
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
         * Aceptar: envio por metodo post el idEmpresa
         * Cancelar : mensaje de cancelación y no se hace nada
         * Verificar /deleteEmpresa en web.php
         * Controlador EmpresaController.php metodo destroy()
         * 
         * @param integer idEmpresa
         * @return SwalFire modal de confirmación
         */
        eliminarEmpresa(idEmpresa){
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
                    var url = '/api/deleteEmpresa';
                    axios.post(url,{
                        'idEmpresa':idEmpresa,
                    }).then(function (response){
                        me.cerrarModal();
                        me.listarEmpresa(me.pagination.current_page,me.buscar);
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
         * Valido datos de empresa que no esten vacios
         * 
         * @return void
         */
        validarEmpresa(){
            this.errorempresa = 0;
            this.errorMostrarMsj = [];
            if(!this.nombre) this.errorMostrarMsj.push('* El nombre no puede estar vacío');
            if(!this.razonsocial) this.errorMostrarMsj.push('* La razón social no puede estar vacía');
            if(!this.cuit) this.errorMostrarMsj.push('* El cuit no puede estar vacío');
            if(!this.ingresosbrutos) this.errorMostrarMsj.push('* El ingreso bruto no puede estar vacío');
            if(!this.direccion) this.errorMostrarMsj.push('* La direccion no puede estar vacía');
            if(!this.telefono) this.errorMostrarMsj.push('* El teléfono no puede estar vacío');
            if(!this.inicioactividades) this.errorMostrarMsj.push('* La fecha no puede estar vacía');
            if(!this.tipoId) this.errorMostrarMsj.push('* El tipo de empresa no puede estar vacío');
            if (this.errorMostrarMsj.length) this.errorempresa = 1;
        },
    },
    mounted() {
          this.listarEmpresa(1,this.buscar);
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