<template>
    <div class="container-fluid">
        <div class=" row justify-content-center">
            <div class="col-md-8">
                <p class="text-center"><strong>INVENTARIO STOCKS</strong></p>
                <div class="card">
                    <div class="card-header">
                        <!-- Find a result -->
                        <input type="text" v-model="buscar"  @keyup="listarStock(1,buscar)" class="form-control" placeholder="Texto a buscar">
                    </div>
                    <!-- List Table users -->
                    <div class="card-body">
                        <table id="table_clientes" class="table table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ARTICULO</th>
                                    <th>CANTIDAD</th>
                                    <th>STOCK MINIMO</th>
                                    <th>ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="stock in arrayStock" :key="stock.id">
                                    <td>{{stock.id}}</td>
                                    <td>{{stock.articulo.nombreArticulo}}</td>
                                    <td>{{stock.cantidad}}</td>
                                    <td>{{stock.cantidadMinima}}</td>
                                    <td>
                                        <a class="ps-2" @click="editarModal(stock);" href="#"><i class="fas fa-edit text-warning"></i></a>
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

                              
                                <!-- Cantidad -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Cantidad</label>
    
                                    <div class='col-md-10'>
                                        <input id='cantidad' class='form-control' type='number' name=''   v-model='cantidad' >
                                    </div>
                                </div>
    
                                <!-- Cantidad Minima -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Cantidad Minima</label>
    
                                    <div class='col-md-10'>
                                        <input id='cantidadMinima' class='form-control' type='number' name=''  v-model='cantidadMinima' >
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
    
                                    <button class='btn btn-success text-center w-33' type='button' v-if='tipoAccion == 2' @click="actualizarInventario()">Editar Cambios</button>
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
            idStock:0,
            modal:0,
            idCan:'',
            cantidad:0,
            cantidadMinima:0,
            cantidadBackUp:0,
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
            arrayStock:[],
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
         * Listo Inventario , si hay una busqueda la agrego
         * Traigo los activos en 1 ya que la baja es logica.
         * Deberiamos crear una Columna Status para ver sus estados.
         * 
         * @param integer $page
         * @param string $buscar
         * @return void
         */
        listarStock(page,buscar){
            if (buscar.length==0) {
                buscar='*';
            }
            let me = this;
            var url= 'api/stocks/'+page+'/'+buscar;
            axios.get( url , {
                params: {
                }
            })
            .then(function (response) {
                // destructuro la respuesta para obtener los stocks
                var {stocks} = response.data;
                me.arrayStock=stocks.data;
                me.pagination= response.data.pagination;

            })
            .catch(function (error) {
                console.log(error);
                if(error.status === 401){
                    location.reload(true)
                }
            })
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
            me.listarStock(page,buscar);
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
         * @param array  $stock
         * @return void
         */
        editarModal(stock){
            this.modal=1;
            this.tituloModal='Editar Inventario';
            this.idStock=stock['id'];
            this.cantidad=stock['cantidad'];
            this.cantidadMinima=stock['cantidadMinima'];
            this.cantidadBackUp=stock['cantidadBackup'];
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
        actualizarInventario(){
            this.validarInventario();
            if(this.errorRole==1 ){
                return;
            } 
            let me=this;
            var url = 'api/updateInventario';
            axios.put(url,{
                'cantidad':this.cantidad,
                'cantidadMinima':this.cantidadMinima,
                'cantidadBackup':this.cantidadBackUp,
                'idStock':this.idStock,
            }).then(function (response){
                // Ciero el modal
                me.cerrarModal();
                // Listo Cliente asi se Actuaiza la tabla
                me.listarStock( me.pagination.current_page,me.buscar);
                // Creo el mensaje de exito
                swal.fire({
                    title:'Editado!',
                    text:'El Stcok fue Editado.',
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
         * Valido datos de cliente que no esten vacios
         * 
         * @return void
         */
        validarInventario(){
            this.errorcliente=0;
            this.errorMostrarMsjuser=[];
            if(!this.cantidad) this.errorMostrarMsjuser.push('* La cantidad no puede estar vacío');
            if(!this.cantidadMinima) this.errorMostrarMsjuser.push('* La cantidad minima no puede estar vacío');
            if(!this.cantidadBackUp) this.errorMostrarMsjuser.push('* La cantidad bak-up no puede estar vacío');
            if(this.errorMostrarMsjuser.length) this.errorcliente=1;
        },
    },
    mounted() {
          this.listarStock(1,this.buscar);
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