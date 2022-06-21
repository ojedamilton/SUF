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
              <input
                type="text"
                class="form-control input-sm dropdown-toggle"
                id="nombre_cliente"
                placeholder="Selecciona un cliente"
                required=""
                value=""
                data-toggle="dropdown"
                aria-expanded="true"
              />
              <div class="dropdown-menu" style="">
                <button
                  type="button"
                  class="dropdown-item"
                  data-value="1"
                  data-email="pablo.9000ppp@hotmail.com"
                  data-telefono="018341453794"
                >
                  P<span class="text-primary">a</span>blo Picasso</button
                ><button
                  type="button"
                  class="dropdown-item"
                  data-value="3889"
                  data-email="juanhernandezm2208@gmail.com"
                  data-telefono="3023921157"
                >
                  Ju<span class="text-primary">a</span>n David</button
                ><button
                  type="button"
                  class="dropdown-item"
                  data-value="3887"
                  data-email="alanlop@gmail.com"
                  data-telefono="4221255"
                >
                  W<span class="text-primary">a</span>lter</button
                ><button
                  type="button"
                  class="dropdown-item"
                  data-value="3890"
                  data-email="alanlopezeta@gmail.com"
                  data-telefono="2345"
                >
                  LOL<span class="text-primary">A</span></button
                ><button
                  type="button"
                  class="dropdown-item"
                  data-value="3882"
                  data-email="fbn@gmail.com"
                  data-telefono="urrego beltran"
                >
                  k<span class="text-primary">a</span>therine marcela
                </button>
              </div>
              <input id="id_cliente" name="id_cliente" type="hidden" value="" />
            </div>
            <label for="tel1" class="col-lg-1 control-label">Teléfono</label>
            <div class="col-lg-2">
              <input
                type="text"
                class="form-control input-sm"
                id="tel1"
                placeholder="Teléfono"
                value=""
                readonly=""
              />
            </div>
            <label for="mail" class="col-lg-1 control-label">Email</label>
            <div class="col-lg-4">
              <input
                type="text"
                class="form-control input-sm"
                id="mail"
                placeholder="Email"
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
              <select
                class="form-control input-sm"
                id="condiciones"
                name="condiciones"
              >
                <option value="1">Efectivo</option>
                <option value="2">Cheque</option>
                <option value="3">Transferencia bancaria</option>
                <option value="4">Crédito</option>
              </select>
            </div>
            <!-- <div class="col-lg-2">
              <select
                class="form-control input-sm"
                id="estado_factura"
                name="estado_factura"
              >
                <option value="1">Pagado</option>
                <option value="2">Pendiente</option>
              </select>
            </div> -->
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
             <!--    <button
                  type="button"
                  class="btn btn-outline-secondary"
                  data-bs-toggle="modal"
                  data-bs-target="#addModal"
                >
                  <i class="bi bi-person-fill"></i> Nuevo cliente
                </button> -->
                <!-- <button
                  type="button"
                  data-bs-toggle="modal"
                  data-bs-target="#myModal"
                  class="btn btn-outline-secondary"
                >
                  <i class="bi bi-search"></i> Agregar productos
                </button> -->
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
     arraaay:'array'
    }
  },
  computed: {  // se usa para hacer logica extensa en el template 

  },
  methods:{  // metodos comunes impulsados por eventos

  },
  mounted() {  // se auto ejecuta apenas termina de cargar el DOM

  }
};
</script>
