<template>
    <main class="container">
        <div class="card">
          <div class="card-header border-0">
            <p class="text-center"><strong>LISTADO COMPRAS</strong></p>
            <input type="text" v-model="buscar"  @keyup="listarCompras(buscar)" class="form-control" placeholder="Busqueda por numero de compra o fecha">
  
            <div class="card-header">
            </div>
          </div>
          <div class="card-body table-responsive p-1">
            <table class="table table-striped table-valign-middle">
              <thead>
                <tr>
                  <th>N° Compra</th>
                  <th>Total Compra</th>
                  <th>Fecha</th>
                  <th>Ver</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="compra in comprasPaginadas" :key="compra.id">
                  <td>
                    {{ compra.numeroCompra }}
                  </td>
                  <td>$ {{ compra.totalCompra }}</td>
                  <td>
                    {{ compra.fechaCompra }}
                  </td>
                  <td>
                    <!-- Permiso visualizador de Compras -->
                    <a v-if="idAccionesUser.includes('viewComprador')" @click="abrirModal(); listarDetallesComprasById(compra.id), listarEmpresa(), compraById(compra.id), listarProveedores(compra.id)" href="home#/listadocompras" class="text-muted">
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
  
      <!-- Modal -->
      <ModalCompra :modalFlag="modal" @cambiar-modal="cerrarModal" :userEmpresa="userEmpresa" :compra="compra" :arrayDetalles="arrayDetalles" :arrayProveedores="arrayProveedores"/>
  
    </main>
  </template>
  <script>
  import axios from 'axios';
  // importamos el componente
  import ModalCompra from './partials/ModalCompra.vue';
  export default {
    components: {
      // registramos el componente para poder usarlo
      ModalCompra,
    },
    data() {
      return {
        arrayCompras: [],
        arrayDetalles:[],
        idAccionesUser:[],
        userEmpresa:{},
        arrayProveedores:{},
        compra:{},
        modal:0,
        buscar:'',
        tituloModal: "Detalles Compras",
        comprasPaginadas: [], // Arreglo para almacenar las compras de la página actual
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
      listarCompras(buscar) {
      let me = this;
      var url = "api/allcompras" + '?buscar=' + buscar;
      axios
        .get(url)
        .then(function (response) {
          console.log(response)

          var respuesta = response.data;
          me.arrayCompras = respuesta.listadocompras;
  
          // Actualizar el arreglo de compras para mostrar solo los elementos de la página actual
          me.actualizarCompras();
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 401) {
            location.reload(true);
          }
        });
      },
      actualizarCompras() {
        const inicio = (this.paginaActual - 1) * this.elementosPorPagina;
        const fin = inicio + this.elementosPorPagina;
  
        // Filtrar el arreglo para mostrar solo las compras de la página actual
        this.comprasPaginadas = this.arrayCompras.slice(inicio, fin);
      },
      paginaAnterior() {
        if (this.paginaActual > 1) {
          this.paginaActual--;
          this.actualizarCompras();
        }
      },
      paginaSiguiente() {
        const ultimaPagina = Math.ceil(this.arrayCompras.length / this.elementosPorPagina);
        if (this.paginaActual < ultimaPagina) {
          this.paginaActual++;
          this.actualizarCompras();
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
      listarProveedores(idCompra) {
        let me = this;
        var url = "/api/proveedorCompra?id=" + idCompra;
        axios
          .get(url)
          .then(function (response) {
            var respuesta = response.data;
            console.log(respuesta)
  
            me.arrayProveedores = respuesta.proveedor;
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
  
      listarDetallesComprasById(id) {
        let me = this;
        var url = "/api/detallescomprasbyid";
        axios
          .post(url,{id:id})
          .then(function (response) {
            var respuesta = response.data;
            me.arrayDetalles = respuesta.detallescomprasbyid;
          })
          .catch(function (error) {
            console.log(error);
            if (error.response.status === 401) {
              location.reload(true);
            }
          });
      },
      async compraById(id) {
        let me = this;
        var url = "/api/getcomprasbyid";
        try {
          const response = await axios.post(url,{id})
          const respuesta = response.data;
          me.compra = respuesta.compra;
        } catch (error) {
          console.log(error);
          if (error.response.status === 401) {
              location.reload(true);
          } 
        }   
      },
      descargarPDF() {
         let url='/api/descargarCompra';
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
              link.setAttribute('download', 'Compra.pdf');
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
      this.listarCompras(this.buscar);
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
  </style>
  