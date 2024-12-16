<template>
  <main class="container">
    <div class="card my-3">
      <div class="d-flex card-header">
        <div class="p-0 flex-grow-1">
          <h5><i class="bi bi-plus-circle"></i> Nueva Nota de Crédito</h5>
        </div>
      </div>

      <div class="card-body">

        <form class="form-horizontal" role="form" id="datos_notacredito">
          <div class="row">
            <label for="nombre_cliente" class="col-lg-1 control-label">Cliente</label>
            <div class="col-lg-3 dropdown">
              <input type="text" autocomplete="off" class="form-control input-sm dropdown-toggle" v-model="buscarCliente" @keyup="listarClientes(buscarCliente)"  id="nombre_cliente" placeholder="Selecciona un cliente" value="" data-toggle="dropdown"
                aria-expanded="true"/>
              <div class="dropdown-menu" style="">
                <button v-for="cliente in arrayClientes" :key="cliente.id" :value="cliente.id" type="button" @click="clienteTipoFactura(cliente.idSituacion),rellenarCampos(cliente.nombreCliente,cliente.apellidoCliente,cliente.telefonoCliente,cliente.emailCliente,cliente.id)" class="dropdown-item" data-value="1" data-email="asd@asd.com">
                  {{cliente.nombreCliente + ' ' + cliente.apellidoCliente}}</button>
                </div>
              <input v-model="idCliente" name="id_cliente" type="hidden" value="" />
            </div>
            <label for="telefono" class="col-lg-1 control-label">Teléfono</label>
            <div class="col-lg-2">
              <input type="text" class="form-control input-sm" id="telefono" v-model="telefono" value="" readonly="">
            </div>
            <label for="email" class="col-lg-1 control-label">Email</label>
            <div class="col-lg-4">
              <input type="text" class="form-control input-sm" id="email" v-model="email" readonly=""  value="" />
            </div>
          </div>

          <div class="row my-2">
            <label for="vendedor" class="col-lg-1 control-label">Vendedor</label>
            <div class="col-lg-3">
              <input type="text" class="form-control input-sm" id="vendedor" v-model="nombreVendedor" readonly="" />
            </div>
            <label for="fechaNotaCredito" class="col-lg-1 control-label">Fecha</label>
            <div class="col-lg-2">
              <input type="text" class="form-control input-sm" id="fechaNotaCredito" v-model="fechaNotaCredito" readonly="" />
            </div>
            <label for="valor" class="col-lg-1 control-label">Pago</label>
            <div class="col-lg-2">
              <select class="form-control input-sm" v-model="pagoId" id="valor" name="valor">
                <option value="0">Seleccionar...</option>
                <option  v-for="valor in arrayValores" :key="valor.id" :value="valor.id">{{valor.nombreValor}}</option>
              </select>
            </div>
            <label for="tipoFactura" class="col-lg-1 control-label">T.NC</label>
            <div class="col-lg-1">
              <select class="form-control input-sm" v-model="tipoFacturaId"  id="tipoFactura" name="tipoFactura">
                <option value=0>...</option>
                <option  v-for="tipoFactura in arrayTipoFactura" :key="tipoFactura.idTipoFactura" :value="tipoFactura.idTipoFactura">{{tipoFactura.tipoFactura}}</option>
              </select>
            </div>
          </div>

          <div class="row my-3 align-items-center">
            <label for="factura_asociada" class="col-lg-1 control-label">Fact. Asoc.</label>
            <div class="col-lg-3">
              <input type="text" autocomplete="off"  v-model="FacturaAsociada" class="form-control input-sm" id="factura_asociada" placeholder="N° Factura Asociada" />
            </div>
            <div class="col-lg-8 d-flex justify-content-end">
              <button type="button" class="btn btn-success px-4" @click="abrirModal(),listarArticulos(1,buscarArticulo)">
                <i class="bi bi-plus-circle-fill"></i> Agregar Artículo
              </button>
            </div>

          </div>
        </form>

        <div class="clearfix"></div>
        <div
          id="editar_factura"
          class="col-md-12"
          style="margin-top: 10px"
        ></div>
      
        <div id="resultados" class="col-md-12" style="margin-top: 10px">
          <table class="table">
            <tbody>
              <tr>
                <th class="text-center">CODIGO</th>
                <th>DESCRIPCION</th>
                <th class="">CANT.</th>
                <th class="text-end">PRECIO UNIT.</th>
                <th class="text-end">PRECIO TOTAL</th>
                <th class="text-end">ACCION</th>
                <th></th>
              </tr>
                <!-- Iterar datos -->
              <tr v-for="(detalle,index) in arrayDetalles" :key="detalle.idArticulo"> 
                <td class="text-center">{{detalle.idArticulo}}</td>
                <td >{{detalle.nombre}}</td>
                <td class="">{{detalle.cantidadArticulo}}</td>
                <td>{{detalle.precioVenta}}</td>
                <td>{{detalle.totalDetalle}}</td>
                <td>
                  <button @click="eliminarItem(index), sumarSubtotal(), obtenerDescuento()" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                </td>
                <td></td>
              </tr>
              <tr>
                <td>
                    <label class="text-end font-weight-normal" colspan="4" for="descuento">Descuento</label>
                </td>
                <td class="text-end" colspan="6" >
                   <select @change="obtenerDescuento()" v-model="descuento" name="descuento" id="descuento">
                      <option value="0">Sin descuento</option>
                      <option value="5">5%</option>
                      <option value="10">10%</option>
                      <option value="15">15%</option>
                      <option value="20">20%</option>
                      <option value="25">25%</option>
                      <option value="30">30%</option>
                      <option value="40">40%</option>
                      <option value="50">50%</option>
                      <option value="60">60%</option>
                   </select>
                </td>
              </tr>
              <tr>
                <td class="text-end" colspan="4">SUBTOTAL $</td>
                <td id="subTotalNotaCredito" colspan="2" class="text-end">0</td>
                <td></td>
              </tr>
              <tr>
                <td class="text-end font-weight-bold" colspan="4">TOTAL $</td>
                <td id="totalNotaCredito" colspan="3" class="text-end font-weight-bold">0</td>
              </tr>
            </tbody>
          </table>
           <div v-show="errorNotaCredito" class="form-group div-error">
                <div class="text-left">
                    <div v-for="error in errorMostrarMsjNotaCredito" :key="error" v-text="error">
                    </div>
                </div>
            </div>
          <div class="d-flex justify-content-md-end">
            <button class="btn btn-primary" @click="realizarNotaCredito()" >Realizar NC</button>
          </div>
        </div>
        <!-- Carga los datos ajax -->
      </div>
    </div>
     <!-- Spinner -->
     <div class="loader" v-if="isLoading"></div> 
      <!-- Modal -->
      <div class="modal fade" :class="{'mostrar': modal}" style="display: none;" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">{{tituloModal}}</h5>
            <button type="button"  @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="text-center">
              <h3>Agregar Articulos</h3>
            </div> 
            <div class="modal-body">
              <div class="c">
                    <div class="c-header">
                        <!-- Find a result -->
                        <input type="text" v-model="buscarArticulo"  @keyup="listarArticulos(1,buscarArticulo)" class="form-control" placeholder="Texto a buscar">     
                    </div> 
                    <!-- List Table Details --> 
                    <div class="cbody">  
                      <table id="table_articulo" class="table table-striped" width="100%">
                          <thead>
                            <tr>  
                                <th>Codigo</th>
                                <th>Articulo</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Agregar</th>
                            </tr>  
                          </thead>  
                          <tbody>
                            <tr v-for="articulo in arrayArticulos" :key="articulo.id" >    
                                <td>{{articulo.id}}</td>    
                                <td>{{articulo.nombreArticulo}}</td> 
                                <td class="col-2" >
                                  <input class="form-control form-control-sm" :value="articulo.precio" min="1" type="number" name="precio" :id="'precio_'+articulo.id">
                                </td>
                                <td class="col-2" >
                                  <input class="form-control form-control-sm lineacantidad" value="1" type="number" name="cantidad" :id="articulo.id">
                                  </td>
                                <td>
                                  <button @click="rellenarDetalleNotaCredito(articulo.id,articulo.nombreArticulo,articulo.precio),sumarSubtotal(),obtenerDescuento()" class="btn btn-primary">Agregar</button> <!--  -->
                                </td>
                            </tr>  
                          </tbody>  
                      </table>
                      <div v-show="errorArticulos" class="form-group div-error">
                        <div class="text-right">
                            <div v-text="errorMostrarMsjArticulos">
                            </div>
                        </div>
                      </div>
                      <nav>
                          <ul class="pagination">
                              <li class="page-item"  v-if="pagination.current_page > 1 ">
                                  <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscarArticulo)">Ant</a>
                              </li>
                              <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                  <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscarArticulo)" >{{page}}</a>
                              </li>
                              <li class="page-item" v-if="pagination.current_page < pagination.last_page "  >
                                  <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page+1,buscarArticulo)">Sig</a>
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
import axios from "axios"; // Importo libreria Axios
import { set } from "lodash";
export default {  // todo lo que voy a exportar
  props: ["path"], // obtengo constante definida en app.js
  data() {  // variables con las que me manejo en el template
    return {
      arrayValores: [],
      arrayClientes: [],
      arrayArticulos: [],
      arrayDetalles: [],
      arrayTipoFactura: [],
      buscarCliente: '',
      descuento: 0,
      isLoading: false,
      buscarArticulo: '',
      pagination: {
        'total': 0,
        'current_page': 0,
        'per_page': 0,
        'last_page': 0,
        'from': 0,
        'to': 0,
      },
      offset:3,
      telefono:'',
      email:'',
      fechaNotaCredito:'',
      cliente:'',
      idCliente:0,
      precio:0,
      pagoId:0,
      FacturaAsociada:0,
      tipoFacturaId:0,
      cantidadArtModal:1,
      tituloModal: "",
      nombreVendedor: "",
      idVendedor: 0,
      description: "",
      modal:0,
      errorNotaCredito: 0,
      errorArticulos:0,
      errorMostrarMsjNotaCredito: [],
      errorMostrarMsjArticulos:""
    }
  },

  // se usa para hacer logica extensa en el template 
  computed: {
    isActived: function () {
      return this.pagination.current_page;
    },
    pagesNumber: function () {
      if (!this.pagination.to) {
        return []
      }
      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + (this.offset * 2);
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },
  // metodos comunes impulsados por eventos
  methods:{  
    listarValores() {
      let me = this;
      var url = "api/valores";
      axios
        .get(url)  
        .then((response)=>{
          var respuesta = response.data;
          me.arrayValores = respuesta.valores.data;
        })
        .catch((error)=>{
          console.log(error);
          me.arrayValores = [];

        });
    },
    abrirModal(){
     this.modal=1;
    },
    cerrarModal(){
     this.modal=0;
    },
    validarNotaCredito() {
      this.errorNotaCredito = 0;
      this.errorMostrarMsjNotaCredito = [];
      let subtotal = document.querySelector('#subTotalNotaCredito').textContent;
      let total = document.querySelector('#totalNotaCredito').textContent;
      if(!this.buscarCliente) this.errorMostrarMsjNotaCredito.push('* El nombre de cliente no puede estar vacío');
      if(!this.telefono) this.errorMostrarMsjNotaCredito.push('* El telefono no puede estar vacío');
      if(!this.fechaNotaCredito) this.errorMostrarMsjNotaCredito.push('* La fecha no puede estar vacío');
      if(!this.pagoId) this.errorMostrarMsjNotaCredito.push('* El metodo de pago no puede estar vacío');
      if(!this.email) this.errorMostrarMsjNotaCredito.push('* El email no puede estar vacío');
      if(!this.tipoFacturaId || this.tipoFactura == 0) this.errorMostrarMsjNotaCredito.push('* El tipo de comprobante no puede estar vacío');
      if(!this.arrayDetalles[0]) this.errorMostrarMsjNotaCredito.push('* No hay Articulos agregados');
      if(this.FacturaAsociada == 0) this.errorMostrarMsjNotaCredito.push('* La Factura Asociada no puede estar vacía');
      if(subtotal < 0) this.errorMostrarMsjNotaCredito.push('* El subtotal no puede ser negativo');
      if(total < 0) this.errorMostrarMsjNotaCredito.push('* El total no puede ser negativo');
      if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)) this.errorMostrarMsjNotaCredito.push('* El email no es valido');
      if (this.errorMostrarMsjNotaCredito.length) this.errorNotaCredito = 1;
    },
    validarFacturaAsociada(){
      //this.errorNotaCredito = 0;
      //this.errorMostrarMsjNotaCredito = [];
      let me = this;
      let url = 'api/validarFacturaAsociada';
      let totalNotaCredito = document.querySelector('#totalNotaCredito').textContent;
      //debugger;
      axios.
        post(url,
          {FacturaAsociada:this.FacturaAsociada,
          totalNotaCredito:Number.parseFloat(totalNotaCredito).toFixed(2),
          tipoFacturaId:this.tipoFacturaId}
        )
        .then((response)=>{
          var respuesta = response.data;
          if (respuesta.success) {
            //me.errorMostrarMsjNotaCredito.push(respuesta.message);
            //me.errorNotaCredito = 1;
          }
        })
        .catch((error)=>{
          me.errorMostrarMsjNotaCredito.push(error.response.data.message);
          me.errorNotaCredito = 1;
        });
    },
    async usuarioAuth(){
       let me  = this;
       let url = '/showUserAuth'
       try {
            const response      = await axios.get(url);
            const status        = response.status;
            const data          = response.data
            this.idVendedor     = data.id;
            this.nombreVendedor = data.name; 
       } catch (error) {
         return 'error User Auth';
       }  
    },
    async tipoFacturaEmpresa(){
      let me  = this;
      let url = 'api/tipoFacturaEmpresa';
      try {
           const response         = await axios.get(url);
           const data             = response.data;
           this.arrayTipoFactura  = data.tipoFactura;
        
      } catch (error) {
        
          Toastify({
            text: error.response.data.message,
            duration: 6000,
            className: "danger",
            newWindow: true,
            close: true,
            stopOnFocus: true, // Prevents dismissing of toast on hover
            style: {
              background: "linear-gradient(to right, rgb(255,195,113), rgb(255,95,109))",
            }
          }).showToast();
      }
    },
    async clienteTipoFactura(idTipo){
      let me  = this;
      let url = '/clienteTipoFactura';
      try {
           const response         = await axios.post(url,{idTipo});
           const data             = response.data;
           if (data.tipoFactura) {
              this.arrayTipoFactura.splice(0,this.arrayTipoFactura.length, ...[]); 
              this.$set(this.arrayTipoFactura, 0, ...[]);
              this.$set(this.arrayTipoFactura,0, data.tipoFactura);
           }else{
             this.tipoFacturaEmpresa();
           }
        
      } catch (error) {
        return 'error Tipo Factura'
      }
    },
    listarClientes(buscarCliente) {
      let me = this;
      var url = "/clientes?buscar="+buscarCliente;
      axios
        .get(url) // ,{ params: {},} 
        .then(function (response) {
          var respuesta = response.data;
          me.arrayClientes = respuesta.clientes.data;
        })
        .catch(function (error) {
          console.log(error);
         /*  if (error.response.status === 401) {
            location.reload(true);
          } */
        });
    },
    listarArticulos(page,buscarArticulo) {
      let me = this;
      var url = "api/articulos?page="+page+"&buscar="+buscarArticulo;
      axios
        .get(url)
        .then((response)=>{
          var respuesta = response.data;
          me.arrayArticulos = respuesta.articulos.data;
          me.pagination = respuesta.pagination;
          me.errorArticulos=0;
        })
        .catch((error)=>{
          console.log(error);
          me.errorArticulos=1;
          me.errorMostrarMsjArticulos=error.response.data.message
        });
    },
    rellenarCampos(nombre,apellido,tel,email,cliente){
        this.telefono=tel;
        this.email=email;
        this.buscarCliente=nombre+' '+apellido;
        this.idCliente=cliente;
    },
    /**
     * Se encarga de rellenar el detalle de la nota de credito
     *
     * @param {integer} id - id del articulo
     * @param {string} nombre - nombre del articulo
     * @param {integer} p - precio del articulo
     * @param {integer} cantidad - cantidad del articulo
     * 
     * @returns {void}
     */
    rellenarDetalleNotaCredito(id, nombre) {

      let precio = parseInt(document.getElementById('precio_'+id).value.replace("-", ""));
      let valorCantidad = parseInt(document.getElementById(id).value);
      let existe = false;
      this.arrayDetalles.forEach((detalle, index) => {
        if (detalle.idArticulo === id && detalle.precioVenta === precio) {
          // Si el artículo y el precio coinciden en los detalles, se modifica la cantidad
          this.arrayDetalles[index].cantidadArticulo += valorCantidad;
          this.arrayDetalles[index].totalDetalle =
          this.arrayDetalles[index].cantidadArticulo * precio;
          existe = true;
        }
      });

      if (!existe) {
        // Si el artículo no existe en los detalles o el precio no coincide, se agrega un nuevo detalle
        const nuevoDetalle = {
          idArticulo: id,
          nombre: nombre,
          cantidadArticulo: valorCantidad,
          precioVenta: precio,
          totalDetalle: valorCantidad * precio,
        };
        this.arrayDetalles.push(nuevoDetalle);
      }
    },
    validateNegativePrice(id) {
      let price = document.getElementById("precio_"+id).value;
      if (price < 0) {
        document.getElementById("precio").value = 0;
      }
    },
    /**
     * Se encarga de validar el stock del articulo
     *
     * @param {integer} idArticulo - id del articulo
     * @param {integer} cantidad - cantidad del articulo
     * 
     * @returns {boolean} IsValid - true si hay stock, false si no hay stock
     */
  
    /**
     * Se encarga de eliminar cada Item del detalle de la nota de credito
     *
     * @param {integer} id
     * 
     * @returns {void}
     */
    eliminarItem(id) {
      var parsedobj = JSON.parse(JSON.stringify(this.arrayDetalles))
      console.log(parsedobj)
      this.arrayDetalles.splice(id, 1)
      const restaTotal = parsedobj.reduce((acum, elem, i) => {
        console.log(id, i);
        if (id != i) {
          acum = acum + elem.totalDetalle
          return acum
        } else {
          return acum
        }
      }, 0)
      console.log(restaTotal)
      const totalNotaCredito = document.querySelector('#subTotalNotaCredito')
      totalNotaCredito.textContent = restaTotal
    },
    sumarSubtotal(){
      var parsedobj = JSON.parse(JSON.stringify(this.arrayDetalles))
      const subTotal =parsedobj.reduce((acum,elem,i)=>{
          acum=acum+elem.totalDetalle
          return acum
      },0)
      const subTotalNotaCredito = document.querySelector('#subTotalNotaCredito')
      subTotalNotaCredito.textContent = subTotal
      const totalNotaCredito = document.querySelector('#totalNotaCredito')
      totalNotaCredito.textContent = subTotal
      console.log(subTotal)
    },
    sumarTotal(){
      
      const totalNotaCredito = document.querySelector('#totalNotaCredito')
      totalNotaCredito.textContent = total
   
    },
    obtenerDescuento(){
      const subTotalNotaCredito = document.querySelector('#subTotalNotaCredito');
      const totalNotaCredito = document.querySelector('#totalNotaCredito');
      let dcto  = 0;
      switch (this.descuento) {
        case '5':
          dcto = parseFloat(subTotalNotaCredito.textContent) - parseFloat(subTotalNotaCredito.textContent)*5/100;
          totalNotaCredito.textContent = dcto;
          break;
        case '10':
          dcto = parseFloat(subTotalNotaCredito.textContent) - parseFloat(subTotalNotaCredito.textContent)*10/100;
          totalNotaCredito.textContent = dcto;
          break;
        case '15':
          dcto = parseFloat(subTotalNotaCredito.textContent) - parseFloat(subTotalNotaCredito.textContent)*15/100;
          totalNotaCredito.textContent = dcto;
          break;
        case '20':
          dcto = parseFloat(subTotalNotaCredito.textContent) - parseFloat(subTotalNotaCredito.textContent)*20/100;
          totalNotaCredito.textContent = dcto;
          break;
        case '25':
          dcto = parseFloat(subTotalNotaCredito.textContent) - parseFloat(subTotalNotaCredito.textContent)*25/100;
          totalNotaCredito.textContent = dcto;
          break;
        case '30':
          dcto = parseFloat(subTotalNotaCredito.textContent) - parseFloat(subTotalNotaCredito.textContent)*30/100;
          totalNotaCredito.textContent = dcto;
          break;
        case '40':
          dcto = parseFloat(subTotalNotaCredito.textContent) - parseFloat(subTotalNotaCredito.textContent)*40/100;
          totalNotaCredito.textContent = dcto;
          break;
        case '50':
          dcto = parseFloat(subTotalNotaCredito.textContent) - parseFloat(subTotalNotaCredito.textContent)*50/100;
          totalNotaCredito.textContent = dcto;
          break;
        case '60':
          dcto = parseFloat(subTotalNotaCredito.textContent) - parseFloat(subTotalNotaCredito.textContent)*60/100;
          totalNotaCredito.textContent = dcto;
          break;
        default:
           var parsedobj = JSON.parse(JSON.stringify(this.arrayDetalles))
           const total =parsedobj.reduce((acum,elem,i)=>{
                 acum=acum+elem.totalDetalle
                return acum
              },0)
          totalNotaCredito.textContent = total;
          break;
      }
      /*  if (this.descuento) {
         const totalNotaCredito = document.querySelector('#totalNotaCredito');
         let dcto = parseFloat(totalNotaCredito.textContent)*parseInt(this.descuento)/100;  
         let total = parseFloat(totalNotaCredito.textContent) - dcto;
         totalNotaCredito.textContent = total;
       } */
    },
  
    /**
     * Se encarga de Realizar NC todo el detalle de la nc
     *
     * @returns {void}
     */
    realizarNotaCredito(){
      this.isLoading=true;
      this.validarNotaCredito();
      this.validarFacturaAsociada();
      let totalNotaCredito = document.querySelector('#totalNotaCredito').textContent;
      let pago = parseInt(document.querySelector('#valor').value);
      let me = this;

      var url = "/api/notacredito";
      setTimeout(() => {
        if (this.errorNotaCredito == 1) {
          this.isLoading = false;
          return;
        }
        axios
          .post(url ,{ 
                notacredito:{
                  'pago':pago,
                  'id_cliente': this.idCliente,
                  'fechaNotaCredito':this.fechaNotaCredito,
                  'totalNotaCredito':parseInt(totalNotaCredito),
                  'descuento':parseInt(this.descuento),
                  'tipoFacturaId':this.tipoFacturaId,
                  'idFacturaAsociada':this.FacturaAsociada,
                }, 
                detalles:this.arrayDetalles
          }) 
          .then(function (response) {
            var respuesta = response.data;
            me.isLoading=false;
            document.querySelector('#subTotalNotaCredito').textContent='0';
            document.querySelector('#totalNotaCredito').textContent='0';
            me.arrayDetalles=[];
            me.buscar='';
            me.telefono='';
            me.email='';
            me.descuento=0;
            me.idCliente='';
            me.pagoId=0;
            me.FacturaAsociada=0;
            me.tipoFacturaId=0;
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Tu Nota de Credito Ha sido Creada',
              showConfirmButton: false,
              timer: 3000
            })
            /* this.arrayDetalles.splice(this.arrayDetalles.lenght);
            totalNotaCredito='0'; */
          })
          .catch(function (error) {
            me.isLoading=false;
            let errorMessage=error.response.data.message;
              Swal.fire({
                position: 'center',
                icon: 'error',
                title: errorMessage,
                showConfirmButton: false,
                timer: 3000
              });
          });
      }, 3000);
    },
     cambiarPagina(page,buscar){
        let me = this;
        //Actualizar pagina actual
        me.pagination.current_page=page;
        //Enviar la petición para visualizar la data de esa página
        me.listarArticulos(page,buscar);
    },
  },
  // se auto-ejecuta apenas termina de cargar el DOM
  mounted() {  
   this.listarValores();
   this.listarClientes();
   let date = new Date();
   this.fechaNotaCredito=date.toISOString().split('T')[0];
   this.usuarioAuth();
   this.tipoFacturaEmpresa();
  
  }
};
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