<template>
    <div class="container-fluid">
        <div class=" row justify-content-center">
            <div class="col-md-8">
                <p class="text-center"><strong>LISTADO ACCIONES</strong></p>
                <div class="card">
                    <div class="card-header">
                        <!-- Find a result -->
                        <input type="text" v-model="buscar"  @keyup="listarAccion(1,buscar)" class="form-control" placeholder="Texto a buscar">
                    </div>
                    <!-- List Table users -->
                    <div class="card-body">
                        <table id="table_clientes" class="table table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NOMBRE</th>
                                    <th>DESCRIPCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="accion in arrayAccion" :key="accion.id">
                                    <td>{{accion.id}}</td>
                                    <td><span class="badge badge-secondary">{{accion.nombreAccion}}</span></td>
                                    <td>{{accion.descripcionAccion}}</td>
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
            <!-- Spinner -->
            <div class="loader" v-if="isLoading"></div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    props:['path'],
    data(){
        return{
            idAccion:0,
            nombreAccion:'',
            descripcionAccion:'',
            tipoAccion:0,
            buscar:'',
            isLoading:true,
            arrayAccion:[],
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
        listarAccion(page,buscar){
            /* if (buscar.length==0) {
                buscar='*';
            } */
            let me = this;
            var url= 'api/acciones?page='+page+'&buscar='+buscar;
            axios.get( url , {
                params: {
                }
            })
            .then(function (response) {
                // destructuro la respuesta para obtener los stocks
                var {acciones} = response.data;
                me.arrayAccion=acciones.data;
                me.pagination= response.data.pagination;
                me.isLoading=false;

            })
            .catch(function (error) {
                console.log(error);
                me.isLoading=false;
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
            me.listarAccion(page,buscar);
        },

    },
    mounted() {
          this.listarAccion(1,this.buscar);
    }
}
</script>
<style>
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
</style>