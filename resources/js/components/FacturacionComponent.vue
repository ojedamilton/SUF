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
              <input type="text" autocomplete="off" class="form-control input-sm dropdown-toggle" v-model="buscarCliente" @keyup="listarClientes(buscarCliente)"  id="nombre_cliente" placeholder="Selecciona un cliente" value="" data-toggle="dropdown"
                aria-expanded="true"/>
              <div class="dropdown-menu" style="">
                <button v-for="cliente in arrayClientes" :key="cliente.id" :value="cliente.id" type="button" @click="clienteTipoFactura(cliente.idSituacion),rellenarCampos(cliente.nombreCliente,cliente.apellidoCliente,cliente.telefonoCliente,cliente.emailCliente,cliente.id)" class="dropdown-item" data-value="1" data-email="asd@asd.com">
                  {{cliente.nombreCliente + ' ' + cliente.apellidoCliente}}</button>
                </div>
              <input v-model="idCliente" name="id_cliente" type="hidden" value="" />
            </div>
            <label for="tel1" class="col-lg-1 control-label">Teléfono</label>
            <div class="col-lg-2">
              <input type="text" class="form-control input-sm" id="telefono" v-model="telefono" value="" readonly="">
            </div>
            <label for="mail" class="col-lg-1 control-label">Email</label>
            <div class="col-lg-4">
              <input type="text" class="form-control input-sm" id="email" v-model="email" readonly=""  value="" />
            </div>
          </div>

          <div class="row my-2">
            <label for="empresa" class="col-lg-1 control-label">Vendedor</label>
            <div class="col-lg-3">
              <input type="text" class="form-control input-sm" id="vendedor" v-model="nombreVendedor" readonly="" />
            </div>
            <label for="tel2" class="col-lg-1 control-label">Fecha</label>
            <div class="col-lg-2">
              <input type="text" class="form-control input-sm" id="fecha" v-model="fecha" readonly="" />
            </div>
            <label for="email" class="col-lg-1 control-label">Pago</label>
            <div class="col-lg-2">
              <select class="form-control input-sm" v-model="pagoId" id="valor" name="valor">
                <option value="0">Seleccionar...</option>
                <option  v-for="valor in arrayValores" :key="valor.id" :value="valor.id">{{valor.nombreValor}}</option>
              </select>
            </div>
            <label for="email" class="col-lg-1 control-label">T.Factura</label>
            <div class="col-lg-1">
              <select class="form-control input-sm" v-model="tipoFacturaId"  id="tipoFactura" name="tipoFactura">
                <option value=0>...</option>
                <option  v-for="tipoFactura in arrayTipoFactura" :key="tipoFactura.idTipoFactura" :value="tipoFactura.idTipoFactura">{{tipoFactura.tipoFactura}}</option>
              </select>
            </div>
          </div>

          <div class="col-md-12">
            <div class="d-flex justify-content-md-end">
              <div class="btn-group" role="group" aria-label="Basic example">
                <button
                  type="button"
                  class="btn btn-success"
                  @click="abrirModal(),listarArticulos(1,buscarArticulo)"
                >
                  <i class="bi bi-plus-circle-fill"></i> Agregar Articulo
                </button>
             <!--    <button type="submit" class="btn btn-primary">
                  <i class="bi bi-printer-fill"></i> Imprimir
                </button> -->
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
                <td id="subTotalFactura" colspan="2" class="text-end">0</td>
                <td></td>
              </tr>
              <tr>
                <td class="text-end font-weight-bold" colspan="4">TOTAL $</td>
                <td id="totalFactura" colspan="3" class="text-end font-weight-bold">0</td>
              </tr>
            </tbody>
          </table>
           <div v-show="errorFactura" class="form-group div-error">
                <div class="text-left">
                    <div v-for="error in errorMostrarMsjFactura" :key="error" v-text="error">
                    </div>
                </div>
            </div>
          <div class="d-flex justify-content-md-end">
            <button class="btn btn-primary" @click="facturarTodo()" >Facturar</button>
          </div>
        </div>
        <!-- Carga los datos ajax -->
      </div>
    </div>
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
                                <td class="col-2">
                                  <input class="form-control form-control-sm" :value="articulo.precio" type="number" name="precio" :id="'precio_'+articulo.id">
                                </td>
                                <td class="col-2">
                                  <input class="form-control form-control-sm lineacantidad" value="1" type="number" name="cantidad" :id="articulo.id">
                                  </td>
                                <td>
                                  <button @click="rellenarDetalleFactura(articulo.id,articulo.nombreArticulo,articulo.precio),sumarSubtotal(),obtenerDescuento()" class="btn btn-primary">Agregar</button> <!--  -->
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
      fecha:'',
      cliente:'',
      idCliente:0,
      precio:0,
      pagoId:0,
      tipoFacturaId:0,
      cantidadArtModal:1,
      tituloModal: "",
      nombreVendedor: "",
      idVendedor: 0,
      description: "",
      modal:0,
      errorFactura: 0,
      errorArticulos:0,
      errorMostrarMsjFactura: [],
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
    validarFactura() {
      this.errorFactura = 0;
      this.errorMostrarMsjFactura = [];
      if(!this.buscarCliente) this.errorMostrarMsjFactura.push('* El nombre de cliente no puede estar vacío');
      if(!this.telefono) this.errorMostrarMsjFactura.push('* El telefono no puede estar vacío');
      if(!this.fecha) this.errorMostrarMsjFactura.push('* La fecha no puede estar vacío');
      if(!this.pagoId) this.errorMostrarMsjFactura.push('* El metodo de pago no puede estar vacío');
      if(!this.email) this.errorMostrarMsjFactura.push('* El email no puede estar vacío');
      if(!this.tipoFacturaId || this.tipoFactura == 0) this.errorMostrarMsjFactura.push('* El tipoFactura no puede estar vacío');
      if(!this.arrayDetalles[0]) this.errorMostrarMsjFactura.push('* No hay Articulos para Facturar');
      if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)) this.errorMostrarMsjFactura.push('* El email no es valido');
      if (this.errorMostrarMsjFactura.length) this.errorFactura = 1;
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
           //console.log(this.arrayTipoFactura);
        
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
    // rellenarDetalleFactura(id,nombre){
    //    let precio = parseInt(document.getElementById('precio_'+id).value);
    //    const valorCantidad =parseInt(document.getElementById(id).value)     
    //    const detalleObjeto ={}
    //    detalleObjeto.idArticulo=id
    //    detalleObjeto.nombre=nombre
    //    detalleObjeto.precioVenta=parseInt(precio)
    //    detalleObjeto.cantidadArticulo=valorCantidad
    //    detalleObjeto.totalDetalle=valorCantidad*precio
    //    // const detalleParse= JSON.parse(JSON.stringify(detalleObjeto))
    //    const detalleParse = {...detalleObjeto} // Sacar Observer
    //    this.arrayDetalles.push(detalleParse);
    //    //console.log(this.arrayDetalles.splice(this.arrayDetalles.lenght));
    //    //debugger
    //   //this.arrayDetalles.splice(0,1);
    // },
    rellenarDetalleFactura(id, nombre) {
      let precio = parseInt(document.getElementById('precio_'+id).value);
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



    /**
     * Se encarga de eliminar cada Item del detalle de la factura
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
      const totalFactura = document.querySelector('#subTotalFactura')
      totalFactura.textContent = restaTotal
    },
    sumarSubtotal(){
      var parsedobj = JSON.parse(JSON.stringify(this.arrayDetalles))
      const subTotal =parsedobj.reduce((acum,elem,i)=>{
          acum=acum+elem.totalDetalle
          return acum
      },0)
      const subTotalFactura = document.querySelector('#subTotalFactura')
      subTotalFactura.textContent = subTotal
      const totalFactura = document.querySelector('#totalFactura')
      totalFactura.textContent = subTotal
      console.log(subTotal)
    },
    sumarTotal(){
      
      const totalFactura = document.querySelector('#totalFactura')
      totalFactura.textContent = total
   
    },
    obtenerDescuento(){
      const subTotalFactura = document.querySelector('#subTotalFactura');
      const totalFactura = document.querySelector('#totalFactura');
      let dcto  = 0;
      switch (this.descuento) {
        case '5':
          dcto = parseFloat(subTotalFactura.textContent) - parseFloat(subTotalFactura.textContent)*5/100;
          totalFactura.textContent = dcto;
          break;
        case '10':
          dcto = parseFloat(subTotalFactura.textContent) - parseFloat(subTotalFactura.textContent)*10/100;
          totalFactura.textContent = dcto;
          break;
        case '15':
          dcto = parseFloat(subTotalFactura.textContent) - parseFloat(subTotalFactura.textContent)*15/100;
          totalFactura.textContent = dcto;
          break;
        case '20':
          dcto = parseFloat(subTotalFactura.textContent) - parseFloat(subTotalFactura.textContent)*20/100;
          totalFactura.textContent = dcto;
          break;
        case '25':
          dcto = parseFloat(subTotalFactura.textContent) - parseFloat(subTotalFactura.textContent)*25/100;
          totalFactura.textContent = dcto;
          break;
        case '30':
          dcto = parseFloat(subTotalFactura.textContent) - parseFloat(subTotalFactura.textContent)*30/100;
          totalFactura.textContent = dcto;
          break;
        case '40':
          dcto = parseFloat(subTotalFactura.textContent) - parseFloat(subTotalFactura.textContent)*40/100;
          totalFactura.textContent = dcto;
          break;
        case '50':
          dcto = parseFloat(subTotalFactura.textContent) - parseFloat(subTotalFactura.textContent)*50/100;
          totalFactura.textContent = dcto;
          break;
        case '60':
          dcto = parseFloat(subTotalFactura.textContent) - parseFloat(subTotalFactura.textContent)*60/100;
          totalFactura.textContent = dcto;
          break;
        default:
           var parsedobj = JSON.parse(JSON.stringify(this.arrayDetalles))
           const total =parsedobj.reduce((acum,elem,i)=>{
                 acum=acum+elem.totalDetalle
                return acum
              },0)
          totalFactura.textContent = total;
          break;
      }
      /*  if (this.descuento) {
         const totalFactura = document.querySelector('#totalFactura');
         let dcto = parseFloat(totalFactura.textContent)*parseInt(this.descuento)/100;  
         let total = parseFloat(totalFactura.textContent) - dcto;
         totalFactura.textContent = total;
       } */
    },
  
    /**
     * Se encarga de Facturar todo el detalle de la factura
     *
     * @returns {void}
     */
    facturarTodo(){
      this.validarFactura();
      if(this.errorFactura==1)return; 
      let totalFactura = document.querySelector('#totalFactura').textContent;
      let pago = parseInt(document.querySelector('#valor').value);
      let me = this;
      
      //const detalleParse = {...detalleObjeto} // Sacar Observer
     
      //me.arrayDetalles=[];
      //debugger;
      console.log('me Array: '+this.arrayDetalles);
      var url = "/facturar";
      axios
        .post(url ,{ 
              factura:{
                'pago':pago,
                'id_cliente': this.idCliente,
                'fecha':this.fecha,
                'totalFactura':parseInt(totalFactura),
                'descuento':parseInt(this.descuento),
                'tipoFacturaId':this.tipoFacturaId
              }, 
              detalles:this.arrayDetalles
        }) 
        .then(function (response) {
          var respuesta = response.data;
          document.querySelector('#subTotalFactura').textContent='0';
          document.querySelector('#totalFactura').textContent='0';
          me.arrayDetalles=[];
          me.buscar='';
          me.telefono='';
          me.email='';
          me.descuento=0;
          console.log(respuesta);
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Tu Factura Ha sido Creada',
            showConfirmButton: false,
            timer: 3000
          })
          /* this.arrayDetalles.splice(this.arrayDetalles.lenght);
          totalFactura='0'; */
        })
        .catch(function (error) {
          console.log(error);
          /* if (error.response.status === 401) {
            location.reload(true);
          } */
        });
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
   this.fecha=date.toISOString().split('T')[0];
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
</style>    