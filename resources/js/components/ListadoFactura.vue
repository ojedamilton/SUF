<template>
  <main class="container">
    <div class="card">
      <div class="card-header border-0">
        <h3 class="card-title">Listado de Facturas</h3>
        <div class="card-tools">
          <a href="#" class="btn btn-tool btn-sm">
            <i class="fas fa-download"></i>
          </a>
          <a href="#" class="btn btn-tool btn-sm">
            <i class="fas fa-bars"></i>
          </a>
        </div>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-striped table-valign-middle">
          <thead>
            <tr>
              <th>Nro Factura</th>
              <th>Precio</th>
              <th>Fecha</th>
              <th>Ver</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="factura in arrayValores" :key="factura.id">
              <td>
                {{ factura.id }}
              </td>
              <td>$ {{ factura.totalFactura }}</td>
              <td>
                {{ factura.fechaModificacion }}
              </td>
              <td>
                <a @click="abrirModal();listarDetallesById(factura.id)" href="#" class="text-muted">
                  <i class="fas fa-search"></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
     <!-- Modal -->
      <div class="modal fade" :class="{'mostrar': modal}" style="display: none;" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-top" role="document">
          <div class="modal-content">
              <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">{{tituloModal}}</h5>
              <button type="button"  @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
              </div>
              <div class="modal-body">
                  <div class="c">
                        <!-- List Table Details --> 
                        <div class="cbody">   
                            <table id="table_articulo" class="table table-striped" width="100%">
                                <thead>
                                <tr>  
                                    <th>Codigo</th>
                                    <th>articulo</th>
                                    <th>cantidad</th>
                                    <th>Precio</th>
                                </tr>  
                                </thead>  
                                <tbody>
                                    <tr v-for="detalle in arrayDetalles" :key="detalle.id" >    
                                        <td>{{detalle.id}}</td>    
                                        <td>{{detalle.idArticulo}}</td> 
                                        <td>{{detalle.cantidadArticulo}}</td>
                                        <td>{{detalle.totalDetalle}}</td>
                                    </tr>  
                                </tbody>  
                            </table>
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item"  v-if="pagination.current_page > 1 ">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar)" >1</a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page "  >
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page+1,buscar)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div> 
                    </div>
              </div>
          </div>
          </div>
      </div>
  </main>
</template>
<script>
export default {
  data() {
    return {
      arrayValores: [],
      arrayDetalles:[],
       modal:0,
       tituloModal: "Detalles Facturas",
        pagination:{
                  'total':0,
                  'current_page':0,
                  'per_page':0,
                  'last_page':0,
                  'from':0,
                  'to':0,
      },
    };
  },
  methods: {
    listarValores() {
      let me = this;
      var url = "/allfacturas";
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayValores = respuesta.listadofacturas;
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 401) {
            location.reload(true);
          }
        });
    },
    listarDetallesById(id) {
      let me = this;
      var url = "/detallesbyid";
      axios
        .post(url,{id:id})
        .then(function (response) {
          var respuesta = response.data;
          me.arrayDetalles = respuesta.detallesbyid;
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 401) {
            location.reload(true);
          }
        });
    },
    abrirModal() {
      this.modal = 1;
    },
    cerrarModal() {
      this.modal = 0;
    },
    cambiarPagina(page,buscar){
        let me = this;
        //Actualizar pagina actual
        me.pagination.current_page=page;
        //Enviar la petición para visualizar la data de esa página
        //me.listarArticulos(page,buscar);
    },
  },
   computed: {  
    // se usa para hacer logica extensa en el template 
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
  mounted() {
    // se auto-ejecuta apenas termina de cargar el DOM
    this.listarValores();
  },
};
</script>
