<template>
    <div class="container-fluid">
        <div class=" row justify-content-center">
            <div class="col-md-8">
                <p class="text-center"><strong>LISTADO CATEGORIAS</strong></p>
                <div class="card">
                    <div class="card-header">
                        <!-- Add users -->
                        <div >
                           <!--  <button v-if="idCan.includes('new')" type="button" class="btn btn-success" @click="abrirModal('user','registrar')" >Nuevo</button><br><br> -->
                        </div>
                        <!-- Find a result -->
                        <input type="text" v-model="buscar"  @keyup="listarCategoria(1,buscar)" class="form-control" placeholder="Texto a buscar">
                    </div>
                    <!-- List Table users -->
                    <div class="card-body">
                        <table id="table_categoria" class="table table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>NOMBRE</th>
                                    <th>ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categorias in arrayCategoria" :key="categorias.id">
                                    <td>{{categorias.nombreCategoria}}</td>
                                    <td>
                                        <a class="pr-2" @click="editarModal(categorias);" href="#"><i class="fas fa-edit text-warning"></i></a>
                                        <a class="pr-2" @click="eliminarCategoria(categorias.id);" href="#"><i class="fas fa-trash-alt text-danger"></i></a>
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
                                        <input id='Nombre' class='form-control' type='text' name='' placeholder='Ingrese un nombre..'  v-model='nombreCategoria' >
                                    </div>
                                </div>

                                <!-- Errores Validación -->
                                <div v-show='errorcategoria' class='form-group row align-items-center div-error'>
                                    <div class='text-center text-error'>
                                        <div v-for='error in errorMostrarMsjuser' :key='error' v-text='error'></div>
                                    </div>
                                </div>
                                <!-- Guardo los Cambios -->
                                <div class='d-flex flex-column justify-content-center align-items-center'>
    
                                    <button class='btn btn-success text-center w-33' type='button' v-if='tipoAccion == 2' @click="ActualizarCategoria()">Editar Cambios</button>
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
            idCategoria:0,
            modal:0,
            idCan:'',
            tituloModal:'',
            nombreCategoria:'',
            description:'',
            tipoAccion:0,
            buscar:'',
            arrayCategoria:[],
            pagination:{
                'total':0,
                'current_page':0,
                'per_page':0,
                'last_page':0,
                'from':0,
                'to':0,
            },
            offset:3,
            errorcategoria:0,
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
         * Listo todas las categorias , si hay una busqueda la agrego
         * Traigo los activos en 1 ya que la baja es logica.
         * Deberiamos crear una Columna Status para ver sus estados.
         * 
         * @param integer $page
         * @param string $buscar
         * @return void
         */
         listarCategoria(page,buscar){
            let me = this;
            var url= '/api/categoria?page='+page+'&buscar='+buscar;
            axios.get( url , {
                params: {
                }
            })
                .then(function (response) {
                    // destructuro la respuesta para obtener las categorias
                    var {categorias} = response.data;
                    me.arrayCategoria=categorias.data;
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
            me.listarCategoria(page,buscar);
        },

        /**
         * Cierro el modal , Le cambio el Flag a 0
         * 
         * @return void
         */
        cerrarModal(){
            this.modal=0;
            this.errorcategoria=0;
        },

        /**
         * Sobreescribe en las variables que defini anteriormente en el data()return{}
         * Al abrir el modal ya tienen en su v-model estas variables para tomar esa categoria
         * 
         * @param array  $categorias 
         * @return void
         */
        editarModal(categorias){
            this.modal=1;
            this.tituloModal='Editar Categoria';
            this.idCategoria=categorias['id'];
            this.nombreCategoria=categorias['nombreCategoria'];
            this.tipoAccion=2;
        },

        /**
         * Valido datos de Categoria
         * Envio por metodo put los datos
         * verificar /updateCategoria en web.php
         * Controlador CategoriaController.php metodo update()
         *
         * @return SwalFire modal de confirmación
         */
        ActualizarCategoria(){
            this.validarCategoria();
            if(this.errorcategoria==1 ){
                return;
            }
            let me=this;
            var url = '/api/updateCategoria';
            axios.put(url,{
                'nombreCategoria':this.nombreCategoria,
                'idCategoria':this.idCategoria,
            }).then(function (response){
                // Ciero el modal
                me.cerrarModal();
                // Listo Categoria asi se Actuaiza la tabla
                me.listarCategoria( me.pagination.current_page,me.buscar);
                // Creo el mensaje de exito
                swal.fire({
                    title:'Editado!',
                    text:'La categoria fue Editada.',
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
         * Aceptar: envio por metodo post el idCategoria
         * Cancelar : mensaje de cancelación y no se hace nada
         * Verificar /deleteCategoria en web.php
         * Controlador CategoriaController.php metodo destroy()
         *
         * @param integer idCategoria
         * @return SwalFire modal de confirmación
         */
        eliminarCategoria(idCategoria){
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
                    var url = '/api/deleteCategoria';
                    axios.post(url,{
                        'idCategoria':idCategoria,
                    }).then(function (response){
                        me.cerrarModal();
                        me.listarCategoria(me.pagination.current_page,me.buscar);
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
         * Valido datos de categorias que no esten vacios
         *
         * @return void
         */
         validarCategoria(){
            this.errorcategoria=0;
            this.errorMostrarMsjuser=[];
            if(!this.nombreCategoria) this.errorMostrarMsjuser.push('* El nombre no puede estar vacío');
            if(this.errorMostrarMsjuser.length) this.errorcategoria=1;
        },
    },
    mounted() {
          this.listarCategoria(1,this.buscar);
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