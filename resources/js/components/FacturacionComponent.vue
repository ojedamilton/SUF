<template>
  <main class="container">
    <div class="card my-3">
      <div class="d-flex card-header">
        <div class="p-0 flex-grow-1">
          <h5><i class="bi bi-plus-circle"></i> Nueva Factura</h5>
        </div>
      </div>

      <div class="card-body">
        <form class="form-horizontal" role="form" id="datos_factura">
          <div class="row">
            <label for="nombre_cliente" class="col-lg-1 control-label"
              >Cliente</label
            >
            <div class="col-lg-3 dropdown">
              <input type="text" class="form-control input-sm dropdown-toggle" v-model="buscar" @keyup="listarClientes(buscar)"  id="nombre_cliente" placeholder="Selecciona un cliente" value="" data-toggle="dropdown"
                aria-expanded="true"/>
              <div class="dropdown-menu" style="">
                <button v-for="cliente in arrayClientes" :key="cliente.id" :value="cliente.id" type="button" @click="rellenarCampos(cliente.nombreCliente,cliente.apellidoCliente,cliente.telefonoCliente,cliente.direccionCliente)" class="dropdown-item" data-value="1" data-email="asd@asd.com">
                  {{cliente.nombreCliente + ' ' + cliente.apellidoCliente}}</button>
                </div>
              <input id="id_cliente" name="id_cliente" type="hidden" value="" />
            </div>
            <label for="tel1" class="col-lg-1 control-label">Teléfono</label>
            <div class="col-lg-2">
              <input
                type="text"
                class="form-control input-sm"
                id="telefono"
                v-model="telefono"
                value=""
                readonly=""
              />
            </div>
            <label for="mail" class="col-lg-1 control-label">Email</label>
            <div class="col-lg-4">
              <input
                type="text"
                class="form-control input-sm"
                id="email"
                v-model="email"
                readonly=""
                value=""
              />
            </div>
          </div>

          <div class="row my-2">
            <label for="empresa" class="col-lg-1 control-label">Vendedor</label>
            <div class="col-lg-3">
              <input type="text" class="form-control input-sm" id="vendedor" value="administrador" readonly="" />
            </div>
            <label for="tel2" class="col-lg-1 control-label">Fecha</label>
            <div class="col-lg-2">
              <input type="text" class="form-control input-sm" id="fecha" value="16/06/2022" readonly="" />
            </div>
            <label for="email" class="col-lg-1 control-label">Pago</label>
            <div class="col-lg-2">
              <select class="form-control input-sm" id="valor" name="valor">
                <option  v-for="valor in arrayValores" :key="valor.id" :value="valor.id">{{valor.nombreValor}}</option>
              </select>
            </div>
          </div>

          <div class="col-md-12">
            <div class="d-flex justify-content-md-end">
              <div class="btn-group" role="group" aria-label="Basic example">
                <button
                  type="button"
                  class="btn btn-outline-secondary"
                  data-bs-toggle="modal"
                  data-bs-target="#addModalProducto"
                >
                  <i class="bi bi-plus-circle-fill"></i> Nuevo producto
                </button>
                <button type="submit" class="btn btn-outline-secondary">
                  <i class="bi bi-printer-fill"></i> Imprimir
                </button>
              </div>
            </div>
          </div>
        </form>

        <div class="clearfix"></div>
        <div
          id="editar_factura"
          class="col-md-12"
          style="margin-top: 10px"
        ></div>
        <!-- Carga los datos ajax -->

        <div id="resultados" class="col-md-12" style="margin-top: 10px">
          <table class="table">
            <tbody>
              <tr>
                <th class="text-center">CODIGO</th>
                <th class="text-center">CANT.</th>
                <th>DESCRIPCION</th>
                <th class="text-end">PRECIO UNIT.</th>
                <th class="text-end">PRECIO TOTAL</th>
                <th></th>
              </tr>
              <tr>
                <td class="text-end" colspan="4">SUBTOTAL $</td>
                <td class="text-end">0.00</td>
                <td></td>
              </tr>
              <tr>
                <td class="text-end" colspan="4">IVA (13)% $</td>
                <td class="text-end">0.00</td>
                <td></td>
              </tr>
              <tr>
                <td class="text-end" colspan="4">TOTAL $</td>
                <td class="text-end">0.00</td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Carga los datos ajax -->
      </div>
    </div>
  </main>
</template>
<script>
import axios from "axios"; // Importo libreria Axios
export default {  // todo lo que voy a exportar
  props: ["path"], // obtengo constante definida en app.js
  data() {  // variables con las que me manejo en el template
    return {
      arrayValores: [],
      arrayClientes: [],
      buscar:'',
      telefono:'',
      email:'',
      cliente:'',
      tituloModal: "",
      nombreVendedor: "",
      description: "",
      modal:0,
    }
  },
  computed: {  
    // se usa para hacer logica extensa en el template 
  },
  methods:{  // metodos comunes impulsados por eventos
    listarValores() {
      let me = this;
      var url = this.path + "/valores";
      axios
        .get(url) // ,{ params: {},} 
        .then(function (response) {
          var respuesta = response.data;
          me.arrayValores = respuesta.valores;
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 401) {
            location.reload(true);
          }
        });
    },

    listarClientes(buscar) {
      let me = this;
      var url = this.path + "/clientes?buscar="+buscar;
      axios
        .get(url) // ,{ params: {},} 
        .then(function (response) {
          var respuesta = response.data;
          me.arrayClientes = respuesta.clientes;
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 401) {
            location.reload(true);
          }
        });
    },
    rellenarCampos(n,a,t,e){
        this.telefono=t;
        this.email=e;
        this.buscar=n+' '+a;
    }
  },
  mounted() {  // se auto-ejecuta apenas termina de cargar el DOM
   this.listarValores();
   this.listarClientes();
  }
};
</script>
