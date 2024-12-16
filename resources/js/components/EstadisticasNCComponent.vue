<template>
    <main class="container pt-2">
        <div class="card">
            <div class="card-header border-0 text-center">
               <!--  <div class="card-tools d-flex flex-row">
                    <input type="text" id="min" name="min" value="" class="form-control">
                    <input type="text" id="max" name="min" value="" class="form-control">
                </div> -->
            </div>
            <div class="card-body table-responsive p-1">
                <table class="table table-striped table-valign-middle" id="notacreditoTable">
                    <thead>
                        <tr>
                            <th>N° NC</th>
                            <th>Tipo NC</th>
                            <th>Vendedor</th>
                            <th>Cliente</th>
                            <th>Total NC</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                    <!-- La armo con DataTable -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Spinner -->
        <div class="loader" v-if="isLoading"></div>  
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
        arrayNotaCredito: [],
        arrayDetalles:[],
        userEmpresa:{},
        notacredito:{
          puntoventa:{},
          tipofactura:{},
        },
        modal:0,
        isLoading: true,
        tituloModal: "Detalles Notas de Credito",
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
        listarNotasCredito() {
            let me = this;
            var url = "api/allnotacredito";
            axios
            .get(url)
            .then(function (response) {
                var respuesta = response.data;
                me.arrayNotaCredito = respuesta.listadonotacredito;
                me.isLoading = false;
                const dataTable = $('#notacreditoTable').DataTable({
                    searchPanes: {
                        cascadePanes: true,
                        viewTotal: true,
                        dtOpts:{
                            dom:'tp',
                            paging: 'true',
                            pagingType: 'numbers',
                            searching: 'true',
                        },
                    },
                    columnDefs:[
                        {
                            searchPanes:{
                                show: true,
                            },
                            targets:[1,2,3,4]
                        }
                    ],
                    dom: 'BPflrtip',
                    buttons: [
                        {extend:'csvHtml5', text:'Csv', className:'btn btn-info btn-sm'},
                        {extend:'excelHtml5', text:'Excel', className:'btn btn-success btn-sm'},
                        {extend:'pdfHtml5', text:'PDF',className:'btn btn-warning btn-sm'},
                        {extend: 'print',text:'Imprimir', className:'btn btn-primary btn-sm'}   
                    ],
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": true,
                    "responsive": true,
                    "language": {
                        "sProcessing":     "Procesando...",
                        "sLengthMenu":     "Mostrar _MENU_ registros",
                        "sZeroRecords":    "No se encontraron resultados",
                        "sEmptyTable":     "Ningún dato disponible en esta tabla",
                        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix":    "",
                        "sSearch":         "Buscar:",
                        "sUrl":            "",
                        "sInfoThousands":  ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst":    "Primero",
                            "sLast":     "Último",
                            "sNext":     "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        },
                        "buttons": {
                            "copy": "Copiar",
                            "colvis": "Visibilidad"
                        }
                    },
                    "order": [[ 0, "desc" ]],
                    // Data para la tabla api/allnotacredito
                    "data": me.arrayNotaCredito,
                    // Columnas para la tabla
                    columns: [
                        { data: "numeroNotaCredito" },
                        { data: "tipoFactura" },
                        { data: null,render(data)
                            {   return data.nameUser+' '+data.apellidoUser} },
                        { data: null,render(data)
                            {   return data.nombreCliente+' '+data.apellidoCliente} },
                        { data: "totalNotaCredito" },
                        { data: "fechaNotaCredito" },
                    ],

                });
            })
            .catch(function (error) {
                console.log(error);
                me.isLoading = false;
                if (error.response.status === 401) {
                location.reload(true);
                }
            });
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
            return 'error Tipo Nota de Credito'
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
        async notacreditoById(id) {
            let me = this;
            var url = "/getnotacreditobyid";
            try {
            const response = await axios.post(url,{id})
            const respuesta = response.data;
            me.notacredito = respuesta.notacredito;
            } catch (error) {
            console.log(error);
            if (error.response.status === 401) {
                location.reload(true);
            } 
            }   
        },
        descargarPDF() {
            let url='/descargarNotaCredito';
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
                link.setAttribute('download', 'NotaCredito.pdf');
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
        tipoNotaCredito(idTipoFactura) {
            if (idTipoFactura === 1) {
            return 'A';
            } else if (idTipoFactura === 2) {
            return 'B';
            } else if (idTipoFactura === 3) {
            return 'C';
            }
            return '';
        },
    },
    computed: {  
        // se usa para hacer logica extensa en el template 
    },
    mounted() {
        // se auto-ejecuta apenas termina de cargar el DOM
       
        this.listarNotasCredito();
        
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
  .notacredito {
    table-layout: fixed;
  }
  
  .fact-info > div > h5 {
    font-weight: bold;
  }
  
  .notacredito > thead {
    border-top: solid 3px #000;
    border-bottom: 3px solid #000;
  }
  
  .notacredito > thead > tr > th:nth-child(2), .notacredito > tbod > tr > td:nth-child(2) {
    width: 300px;
  }
  
  .notacredito > thead > tr > th:nth-child(n+3) {
    text-align: right;
  }
  
  .notacredito > tbody > tr > td:nth-child(n+3) {
    text-align: right;
  }
  
  .notacredito > tfoot > tr > th, .notacredito > tfoot > tr > th:nth-child(n+3) {
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
  