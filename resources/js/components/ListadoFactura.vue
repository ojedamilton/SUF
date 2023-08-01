<template>
  <main class="container">
      <div class="card">
        <div class="card-header border-0">
          <p class="text-center"><strong>LISTADO FACTURAS</strong></p>
          <input type="text" v-model="buscar"  @keyup="listarFacturas(buscar)" class="form-control" placeholder="Busqueda por numero de factura o fecha">

          <div class="card-header">
          </div>
        </div>
        <div class="card-body table-responsive p-1">
          <table class="table table-striped table-valign-middle">
            <thead>
              <tr>
                <th>N° Factura</th>
                <th>Tipo Factura</th>
                <th>Total Factura</th>
                <th>Fecha</th>
                <th>Ver</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="factura in facturasPaginadas" :key="factura.id">
                <td>
                  {{ factura.numeroFactura }}
                </td>
                <td>
                  {{ factura.tipoFactura }}
                </td>
                <td>$ {{ factura.totalFactura }}</td>
                <td>
                  {{ factura.fechaModificacion }}
                </td>
                <td>
                  <!-- Permiso visualizador de Ventas -->
                  <a v-if="idAccionesUser.includes('viewVendedor')" @click="abrirModal(); listarDetallesById(factura.id), listarEmpresa(), facturaById(factura.id), listarClientes(factura.id)" href="home#/listadofacturacion" class="text-muted">
                    <i class="fas fa-search"></i>
                  </a>
                  <span title="Solicitar Permiso" v-else>
                    <i class="fas fa-times"></i>
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
          <nav class=" d-flex flex-row justify-content-start card-tools">
            <div class="ml-2">
              <button class="btn btn-primary" @click="paginaAnterior">Anterior</button>
            </div>
            <div class="ml-1">
              <button class="btn btn-primary" @click="paginaSiguiente">Siguiente</button>
            </div>
          </nav> 
        </div>
      </div>
    <!-- Spinner -->
    <div class="loader" v-if="isLoading"></div>
    <!-- Modal -->
    <ModalFactura :modalFlag="modal" @cambiar-modal="cerrarModal" :userEmpresa="userEmpresa" :factura="factura" :arrayDetalles="arrayDetalles" :arrayClientes="arrayClientes"/>

  </main>
</template>
<script>
import axios from 'axios';
// importamos el componente
import ModalFactura from './partials/ModalFactura.vue';
export default {
  components: {
    // registramos el componente para poder usarlo
    ModalFactura,
  },
  data() {
    return {
      arrayFacturas: [],
      arrayDetalles:[],
      idAccionesUser:[],
      userEmpresa:{},
      arrayClientes:{},
      factura:{
        puntoventa:{},
        tipofactura:{},
      },
      modal:0,
      buscar:'',
      isLoading:true,
      tituloModal: "Detalles Facturas",
      facturasPaginadas: [], // Arreglo para almacenar las facturas de la página actual
      elementosPorPagina: 10, // Número de elementos que quieres mostrar por página
      paginaActual: 1, // Página actual (inicialmente establecida en 1)
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
    // listarFacturas() {
    //   let me = this;
    //   var url = "api/allfacturas";
    //   axios
    //     .get(url)
    //     .then(function (response) {
    //       var respuesta = response.data;
    //       me.arrayFacturas = respuesta.listadofacturas;
    //     })
    //     .catch(function (error) {
    //       console.log(error);
    //       if (error.response.status === 401) {
    //         location.reload(true);
    //       }
    //     });
    // },
    listarFacturas(buscar) {
    let me = this;
    var url = "api/allfacturas" + '?buscar=' + buscar;
    axios
      .get(url)
      .then(function (response) {
        var respuesta = response.data;
        me.arrayFacturas = respuesta.listadofacturas;
        me.isLoading=false;

        // Actualizar el arreglo de facturas para mostrar solo los elementos de la página actual
        me.actualizarFacturas();
      })
      .catch(function (error) {
        console.log(error);
        me.isLoading=false;
        if (error.response.status === 401) {
          location.reload(true);
        }
      });
    },
    actualizarFacturas() {
      const inicio = (this.paginaActual - 1) * this.elementosPorPagina;
      const fin = inicio + this.elementosPorPagina;

      // Filtrar el arreglo para mostrar solo las facturas de la página actual
      this.facturasPaginadas = this.arrayFacturas.slice(inicio, fin);
    },
    paginaAnterior() {
      if (this.paginaActual > 1) {
        this.paginaActual--;
        this.actualizarFacturas();
      }
    },
    paginaSiguiente() {
      const ultimaPagina = Math.ceil(this.arrayFacturas.length / this.elementosPorPagina);
      if (this.paginaActual < ultimaPagina) {
        this.paginaActual++;
        this.actualizarFacturas();
      }
    },
    abrirModal() {
      this.modal = 1;
    },
    /**
     * Este metodo se ejecuta desde el componente hijo @cambiar-modal
     * @param {boolean} val
     */
    cerrarModal(val) {
      this.modal = val;
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
    listarClientes(idFactura) {
      let me = this;
      var url = "/clienteFactura?id=" + idFactura;
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          console.log(respuesta)

          me.arrayClientes = respuesta.cliente;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    // listarSituacion() {
    //   let me = this;
    //   var url = "/situacionfiscal";
    //   axios
    //     .get(url) // ,{ params: {},} 
    //     .then(function (response) {
    //       var respuesta = response.data;
    //       me.arraySituacion = respuesta.situacionfiscal;
    //     })
    //     .catch(function (error) {
    //       console.log(error);
    //       if (error.response.status === 401) {
    //         location.reload(true);
    //       }
    //     });
    // },

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
    cambiarPagina(page,buscar){
        let me = this;
        //Actualizar pagina actual
        me.pagination.current_page=page;
        //Enviar la petición para visualizar la data de esa página
        //me.listarArticulos(page,buscar);
    },
    tipoFactura(idTipoFactura) {
      if (idTipoFactura === 1) {
        return 'A';
      } else if (idTipoFactura === 2) {
        return 'B';
      } else if (idTipoFactura === 3) {
        return 'C';
      }
      return '';
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
    this.listarFacturas(this.buscar);
    this.methodCan();
  },
  watch: {
    // se usa para escuchar cambios en las variables
    cerrarModal: function (val) {
      console.log("cerre el modal desde el padre");
    },
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
  font-size: 19px;
  text-align: right;
}

.cond {
  border-top: solid 2px #000;
}
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
