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
            <tr v-for="factura in arrayFacturas" :key="factura.id">
              <td>
                {{ factura.id }}
              </td>
              <td>$ {{ factura.totalFactura }}</td>
              <td>
                {{ factura.fechaModificacion }}
              </td>
              <td>
                <a @click="abrirModal();listarDetallesById(factura.id),listarEmpresa(),facturaById(factura.id)" href="#" class="text-muted">
                  <i class="fas fa-search"></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" :class="{'mostrar': modal}" style="display: none;" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-top" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle"></h5>
              <button type="button"  @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
            <div class="modal-body">
              <div class="col-12">
                <h4>Datos Empresa</h4>
                <div class="row my-3">
                  <div class="col-10">
                    <p><strong>Nombre:</strong> {{userEmpresa.nombreEmpresa}}<p>
                    <p><strong>Cuit: </strong>{{userEmpresa.cuitEmpresa}}</p>
                    <p><strong>Direccion: </strong>{{userEmpresa.direccionEmpresa}}</p>
                    <p><strong>Inicio Actividad: </strong>{{userEmpresa.inicioActividades}}</p>
                  </div>
                  <div class="col-2">
                    <img src="webfonts/suf.png" />
                  </div>
                </div>
                
                <hr />
                
                <div class="row fact-info mt-3 col-12">
                  <div class="col-4">
                    <h5>Tipo Factura</h5>
                    <p>
                      {{factura.tipofactura.tipoFactura}}
                    </p>
                  </div>
                  <div class="col-4">
                    <h5>N° de factura</h5>
                    <h5>Fecha</h5>
                  </div>
                  <div class="col-4">
                    <p>{{factura.puntoventa.numPuntoVenta}}-{{factura.numeroFactura}}<p>
                    <p>{{factura.fechaModificacion}}</p>
                  </div>
                </div>
                <div class="row my-5">
                  <table class="table table-borderless factura">
                    <thead>
                      <tr>
                        <th>Cant.</th>
                        <th>Descripcion</th>
                        <th>Precio Unitario</th>
                        <th>Importe</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="detalle in arrayDetalles" :key="detalle.id" >    
                        <td>{{detalle.cantidadArticulo}}</td>
                        <td>{{detalle.articulo.nombreArticulo}}</td> 
                        <td>{{detalle.articulo.precio}}</td>    
                        <td>{{detalle.totalDetalle}}</td>
                      </tr>  
                    </tbody>
                    <tfoot>
                      <tr>
                        <th></th>
                        <th></th>
                        <th>Total Factura</th>
                        <th>{{factura.totalFactura}}</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="cond row">
                  <div class="col-12 mt-3">
                    <h4>Condiciones y formas de pago</h4>
                    <p>El pago se debe realizar en un plazo de 15 dias.</p>
                  </div>
                </div>
             </div>
          </div>
          <div class="col-12">
            <div class="d-flex justify-content-md-center py-3">
              <button class="btn btn-primary" @click="downloadPage()" >Descargar</button>
            </div>
          </div>  
        </div>
      </div>
    </div>
  </main>
</template>
<script>
import axios from 'axios';
export default {
  data() {
    return {
      arrayFacturas: [],
      arrayDetalles:[],
      userEmpresa:{},
      factura:{
        puntoventa:{},
        tipofactura:{},
      },
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
    listarFacturas() {
      let me = this;
      var url = "api/allfacturas";
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayFacturas = respuesta.listadofacturas;
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 401) {
            location.reload(true);
          }
        });
    },
    async listarEmpresa(){
       let me  = this;
       let url = "/userEmpresa";
       try {
           const response    = await axios.get(url);
           const data        = response.data;
           this.userEmpresa  = data.userEmpresa;
        
      } catch (error) {
        return 'error Tipo Factura'
      }
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
    async facturaById(id) {
      let me = this;
      var url = "/getfacturasbyid";
      try {
        const response = await axios.post(url,{id})
        const respuesta = response.data;
        me.factura = respuesta.factura;
      } catch (error) {
        console.log(error);
        if (error.response.status === 401) {
            location.reload(true);
        } 
      }   
    },
    descargarPDF() {
       let url='/descargarFactura';
      // Obtener el contenido del modal
      const contenido = $('.modal-body').html();
      /* console.log(contenido); */
      // Enviar una petición GET a la ruta de descarga del PDF con el contenido del modal como parámetro
      //window.location.href = '{{ route("descargar-pdf") }}?contenido=' + encodeURIComponent(contenido);
      axios.post(url,{contenido},{responseType: 'blob'})
        .then(function (response) {
            console.log(response.data);
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'Factura.pdf');
            document.body.appendChild(link);
            link.click();
         })
          .catch(function (error) {
            console.log(error);
          });
    },
     downloadPage() {
      window.print();
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
    this.listarFacturas();
  },
};
</script>
<style>
.factura {
  table-layout: fixed;
}

.fact-info > div > h5 {
  font-weight: bold;
}

.factura > thead {
  border-top: solid 3px #000;
  border-bottom: 3px solid #000;
}

.factura > thead > tr > th:nth-child(2), .factura > tbod > tr > td:nth-child(2) {
  width: 300px;
}

.factura > thead > tr > th:nth-child(n+3) {
  text-align: right;
}

.factura > tbody > tr > td:nth-child(n+3) {
  text-align: right;
}

.factura > tfoot > tr > th, .factura > tfoot > tr > th:nth-child(n+3) {
  font-size: 24px;
  text-align: right;
}

.cond {
  border-top: solid 2px #000;
}
</style>
