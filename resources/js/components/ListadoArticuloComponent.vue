<template>
    <div class="container-fluid">
        <div class=" row justify-content-center">
            <div class="col-md-8">
                <p class="text-center"><strong>LISTADO ARTICULOS</strong></p>
                <div class="card">
                    <div class="card-header">
                        <!-- Add users -->
                        <div >
                           <!--  <button v-if="idCan.includes('new')" type="button" class="btn btn-success" @click="abrirModal('user','registrar')" >Nuevo</button><br><br> -->
                        </div>
                        <!-- Find a result -->
                        <input type="text" v-model="buscar"  @keyup="listarArticulos(1,buscar)" class="form-control" placeholder="Texto a buscar">
                    </div>
                    <!-- List Table users -->
                    <div class="card-body">
                        <table id="table_articulo" class="table table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>NOMBRE ARTICULO</th>
                                    <th>PRECIO VENTA</th>
                                    <th>PRECIO COMPRA</th>
                                    <th>CATEGORIA</th>
                                    <th>PROVEEDOR/ES</th>
                                    <th>ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="articulos in arrayArticulos" :key="articulos.id">
                                    <td>{{articulos.nombreArticulo}}</td>
                                    <td>{{articulos.precio}}</td>
                                    <td>{{articulos.precioCompra}}</td>
                                    <td>
                                        <span class="badge badge-secondary">{{ articulos.categoria.nombreCategoria }}</span>
                                    </td>
                                    <td>
                                        <span  v-for="proveedor in articulos.proveedores" :key="proveedor.id" class="badge badge-secondary">{{ proveedor.nombreProveedor }}</span>
                                    </td>
                                    <td>
                                        <a class="pr-2" @click="editarModal(articulos);" href="#"><i class="fas fa-edit text-warning"></i></a>
                                        <a class="pr-2" @click="eliminarArticulo(articulos.id);" href="#"><i class="fas fa-trash-alt text-danger"></i></a>
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
            <!-- Spinner -->
            <div class="loader" v-if="isLoading"></div>
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
                                        <input id='Nombre' class='form-control' type='text' name='' placeholder='Ingrese un nombre..'  v-model='nombreArticulo' >
                                    </div>
                                </div>

                                <!-- Precio Venta -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Precio Venta</label>
    
                                    <div class='col-md-10'>
                                        <input id='Precio' class='form-control' type='text' name='' placeholder='Ingrese un precio..'  v-model='precio' >
                                    </div>
                                </div>

                                <!-- Precio Compra -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Precio Compra</label>
    
                                    <div class='col-md-10'>
                                        <input id='PrecioCompra' class='form-control' type='text' name='' placeholder='Ingrese un precio..'  v-model='precioCompra' >
                                    </div>
                                </div>

                                <!-- Categoria -->
                                <div class='form-group row align-items-center'>
                                    <label class='col-md-2 form-control-label mb-0'>Categoria</label>
    
                                    <div class='col-md-10'>
                                        <select class="form-control" v-model="idCategoria" id="categoria" name="">
                                        <option  v-for="categorias in arrayCategoria" :key="categorias.id" :value="categorias.id">{{categorias.nombreCategoria}}</option>
                                    </select>
                                    </div>
                                </div>

                                <!-- -- Proveedores -- -->

                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 form-control-label mb-0">Proveedor/es</label>
                                        <multiselect
                                            v-model="selectedProveedor"
                                            :options="arrayProveedor"
                                            label="nombreProveedor"
                                            track-by="id"
                                            :multiple="true"
                                            :taggable="true"
                                            placeholder="Seleccione una o varios proveedores"
                                        >
                                        </multiselect>
                                </div>

                                <!-- Errores Validación -->
                                <div v-show='errorarticulo' class='form-group row align-items-center div-error'>
                                    <div class='text-center text-error'>
                                        <div v-for='error in errorMostrarMsjPrecio' :key='error' v-text='error'></div>
                                    </div>
                                </div>
                                <!-- Guardo los Cambios -->
                                <div class='d-flex flex-column justify-content-center align-items-center'>
    
                                    <button class='btn btn-success text-center w-33' type='button' v-if='tipoAccion == 2' @click="ActualizarArticulo()">Editar Cambios</button>
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
            idArticulo:0,
            modal:0,
            idCan:'',
            isLoading:true,
            tituloModal:'',
            nombreArticulo:'',
            precio:'',
            precioCompra:'',
            arrayCategoria:'',
            description:'',
            tipoAccion:0,
            buscar:'',
            arrayArticulos:[],
            arrayProveedor:[],
            pagination:{
                'total':0,
                'current_page':0,
                'per_page':0,
                'last_page':0,
                'from':0,
                'to':0,
            },
            offset:3,
            idCategoria: 0,
            errorarticulo:0,
            errorMostrarMsjPrecio:[],
            selectedProveedor:[],
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
         * Listo todas las articulos , si hay una busqueda la agrego
         * Traigo los activos en 1 ya que la baja es logica.
         * Deberiamos crear una Columna Status para ver sus estados.
         * 
         * @param integer $page
         * @param string $buscar
         * @return void
         */
         listarArticulos(page,buscar){
            let me = this;
            var url= '/api/articulos?page='+page+'&buscar='+buscar;
            axios.get( url , {
                params: {
                }
            })
                .then(function (response) {
                    // destructuro la respuesta para obtener las articulos
                    var {articulos} = response.data;
                    me.arrayArticulos=articulos.data;
                    me.pagination= response.data.pagination;
                    me.isLoading=false;

                    //me.obtenerProveedoresPorArticulo() 

                })
                .catch(function (error) {
                    console.log(error);
                    isLoading=false;
                    if(error.status === 401){
                        location.reload(true)
                    }
                })
                .then(function () {
                    // always executed
                });
        },
        listarCategoria() {
            let me = this;
            var url = "/api/categoria";
            axios
                .get(url) // ,{ params: {},} 
                .then(function (response) {
                var respuesta = response.data;
                me.arrayCategoria = respuesta.categorias.data;
                console.log(me.arrayCategoria)
                })
                .catch(function (error) {
                console.log(error);
                if (error.response.status === 401) {
                    location.reload(true);
                }
                });
            },
            listarProveedores() {
                let me = this;
                var url = "api/proveedores";
                axios
                .get(url) // ,{ params: {},} 
                .then(function (response) {
                    
                    let proveedores = response.data.proveedores.data;
                    console.log(proveedores)
                    me.arrayProveedor = proveedores;
                    me.options = proveedores.map((proveedor) => proveedor.nombreProveedor);
                    // console.log(response)
                    // var respuesta = response.data;
                    // me.arrayProveedor = respuesta.proveedores.data;
                })
                .catch(function (error) {
                    console.log(error);
                    if (error.response.status === 401) {
                    location.reload(true);
                    }
                });
            },

            obtenerProveedoresPorArticulo() {
            let me = this;
            me.arrayArticulos.forEach((user, index) => { 
                axios.get('/api/getProveedoresByArticulo/' + user.id)
                    .then(function(response) {
                        var respuesta = response.data;
                        me.$set(me.arrayArticulos[index], 'proveedores', respuesta.proveedores);
                    })
                    .catch(function(error) {
                        console.log(error);
                        if (error.response.status === 401) {
                            location.reload(true);
                        } else if (error.response.status === 404) {
                            console.log("Los proveedores no se encontraron");
                        }
                    });
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
            me.listarArticulos(page,buscar);
        },

        /**
         * Cierro el modal , Le cambio el Flag a 0
         * 
         * @return void
         */
        cerrarModal(){
            this.modal=0;
            this.errorarticulo=0;
        },

        /**
         * Sobreescribe en las variables que defini anteriormente en el data()return{}
         * Al abrir el modal ya tienen en su v-model estas variables para tomar ese articulo
         * 
         * @param array  $articulos 
         * @return void
         */
        editarModal(articulos){
            this.modal=1;
            this.tituloModal='Editar Articulo';
            this.idArticulo=articulos['id'];
            this.nombreArticulo=articulos['nombreArticulo'];
            this.precio = articulos['precio'];
            this.precioCompra = articulos['precioCompra'];
            this.idCategoria = articulos['idCategoria'];
            this.selectedProveedor = articulos.proveedores.map(proveedor => ({
                id: proveedor.id,
                nombreProveedor: proveedor.nombreProveedor
            }));
            this.tipoAccion=2;
        },

        /**
         * Valido datos de Articulo
         * Envio por metodo put los datos
         * verificar /updateArticulo en web.php
         * Controlador ArticuloController.php metodo update()
         *
         * @return SwalFire modal de confirmación
         */
        ActualizarArticulo(){
            this.validarArticulos();
            if(this.errorarticulo==1 ){
                return;
            }
            let me=this;
            var url = '/api/updateArticulo';
            axios.put(url,{
                'nombreArticulo':this.nombreArticulo,
                'precio':this.precio,
                'precioCompra':this.precioCompra,
                'idArticulo':this.idArticulo,
                'idCategoria':this.idCategoria,
                'selectedProveedor':this.selectedProveedor,
            }).then(function (response){
                // Ciero el modal
                me.cerrarModal();
                // Listo Articulo asi se Actuaiza la tabla
                me.listarArticulos( me.pagination.current_page,me.buscar);
                // Creo el mensaje de exito
                swal.fire({
                    title:'Editado!',
                    text:'El articulo fue Editada.',
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
         * Aceptar: envio por metodo post el idArticulo
         * Cancelar : mensaje de cancelación y no se hace nada
         * Verificar /deleteArticulo en web.php
         * Controlador ArticuloController.php metodo destroy()
         *
         * @param integer idArticulo
         * @return SwalFire modal de confirmación
         */
        eliminarArticulo(idArticulo){
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
                    var url = '/api/deleteArticulo';
                    axios.post(url,{
                        'idArticulo':idArticulo,
                    }).then(function (response){
                        me.cerrarModal();
                        me.listarArticulos(me.pagination.current_page,me.buscar);
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
                        text:'Tu registro está a salvo',
                        icon:'error',
                        timer: 1500,
                        timerProgressBar: true,
                    })
                }
            })
        },
        /**
         * Valido datos de articulos que no esten vacios
         *
         * @return void
         */
         validarArticulos(){
            this.errorarticulo=0;
            this.errorMostrarMsjPrecio=[];
            if(!this.nombreArticulo) this.errorMostrarMsjPrecio.push('* El nombre no puede estar vacío');
            if(!this.precio) this.errorMostrarMsjPrecio.push('* El precio no puede estar vacío');
            if(!this.precioCompra) this.errorMostrarMsjPrecio.push('* El precio no puede estar vacío');
            if(!this.idCategoria) this.errorMostrarMsjPrecio.push('* La categoria no puede estar vacía');
            if (this.selectedProveedorlength == 0) this.errorMostrarMsjPrecio.push("* El select proveedor/es no puede estar vacío");
            if(this.errorMostrarMsjPrecio.length) this.errorarticulo=1;
        },
    },
    mounted() {
          this.listarArticulos(1,this.buscar);
          this.listarCategoria();
          this.listarProveedores();
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
.loader{  /* Loader Div Class */
    position: absolute;
    top:0px;
    right:0px;
    width:100%;
    height:100%;
    background-color:#eceaea;
    background-image: url('/img/loading-gif.gif');
    background-size: 50px;
    background-repeat:no-repeat;
    background-position:center;
    z-index:10000000;
    opacity: 0.4;
    filter: alpha(opacity=40);
}
</style>