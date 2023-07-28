<template>
  <main class="container">
    <div class="card my-3">
      <div class="d-flex card-header">
        <div class="p-0 flex-grow-1">
          <h5><i class="bi bi-plus-circle"></i> Nueva Compra</h5>
        </div>
      </div>

      <div class="card-body">

        <form class="form-horizontal" role="form" id="datos_compra">
          <div class="row">
            <label for="nombre_proveedor" class="col-lg-1 control-label"
              >Proveedor</label
            >
            <div class="col-lg-3 dropdown">
              <input type="text" autocomplete="off" class="form-control input-sm dropdown-toggle" v-model="buscarProveedor" @keyup="listarProveedores(buscarProveedor)"  id="nombre_proveedor" placeholder="Selecciona un proveedor" value="" data-toggle="dropdown"
                aria-expanded="true"/>
              <div class="dropdown-menu" style="">
                <button v-for="proveedor in arrayProveedores" :key="proveedor.id" :value="proveedor.id" type="button" @click="rellenarCampos(proveedor.nombreProveedor,proveedor.apellidoProveedor,proveedor.telefonoProveedor,proveedor.emailProveedor,proveedor.id)" class="dropdown-item" data-value="1" data-email="asd@asd.com">
                  {{proveedor.nombreProveedor + ' ' + proveedor.apellidoProveedor}}</button>
                </div>
              <input v-model="idProveedor" name="id_proveedor" type="hidden" value="" />
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
            <label for="empresa" class="col-lg-1 control-label">Comprador</label>
            <div class="col-lg-3">
              <input type="text" class="form-control input-sm" id="comprador" v-model="nombreComprador" readonly="" />
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
          id="editar_compra"
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
                <td id="subTotalCompra" colspan="2" class="text-end">0</td>
                <td></td>
              </tr>
              <tr>
                <td class="text-end font-weight-bold" colspan="4">TOTAL $</td>
                <td id="totalCompra" colspan="3" class="text-end font-weight-bold">0</td>
              </tr>
            </tbody>
          </table>
           <div v-show="errorCompra" class="form-group div-error">
                <div class="text-left">
                    <div v-for="error in errorMostrarMsjCompra" :key="error" v-text="error">
                    </div>
                </div>
            </div>
          <div class="d-flex justify-content-md-end">
            <button class="btn btn-primary" @click="comprarTodo()" >Comprar</button>
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
                                  <button @click="rellenarDetalleCompra(articulo.id,articulo.nombreArticulo,articulo.precio),sumarSubtotal(),obtenerDescuento()" class="btn btn-primary">Agregar</button> <!--  -->
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
      arrayProveedores: [],
      arrayArticulos: [],
      arrayDetalles: [],
      buscarProveedor: '',
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
      proveedor:'',
      idProveedor:0,
      precio:0,
      pagoId:0,
      cantidadArtModal:1,
      tituloModal: "",
      nombreComprador: "",
      idComprador: 0,
      description: "",
      modal:0,
      errorCompra: 0,
      errorArticulos:0,
      errorMostrarMsjCompra: [],
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
    validarCompra() {
      this.errorCompra = 0;
      this.errorMostrarMsjCompra = [];
      if(!this.buscarProveedor) this.errorMostrarMsjCompra.push('* El nombre de proveedor no puede estar vacío');
      if(!this.telefono) this.errorMostrarMsjCompra.push('* El telefono no puede estar vacío');
      if(!this.fecha) this.errorMostrarMsjCompra.push('* La fecha no puede estar vacía');
      if(!this.pagoId) this.errorMostrarMsjCompra.push('* El metodo de pago no puede estar vacío');
      if(!this.email) this.errorMostrarMsjCompra.push('* El email no puede estar vacío');
      if(!this.arrayDetalles[0]) this.errorMostrarMsjCompra.push('* No hay Articulos para Comprar');
      if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)) this.errorMostrarMsjCompra.push('* El email no es valido');
      if (this.errorMostrarMsjCompra.length) this.errorCompra = 1;
    },
    async usuarioAuth(){
       let me  = this;
       let url = '/showUserAuth'
       try {
            const response      = await axios.get(url);
            const status        = response.status;
            const data          = response.data
            this.idComprador     = data.id;
            this.nombreComprador = data.name; 
       } catch (error) {
         return 'error User Auth';
       }  
    },
    listarProveedores(buscarProveedor) {
      let me = this;
      var url = "/api/proveedores?buscar="+buscarProveedor;
      axios
        .get(url) // ,{ params: {},} 
        .then(function (response) {
          var {proveedores} = response.data;
          me.arrayProveedores=proveedores.data;
          me.pagination= response.data.pagination;
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
    rellenarCampos(nombre,apellido,tel,email,proveedor){
        this.telefono=tel;
        this.email=email;
        this.buscarProveedor=nombre+' '+apellido;
        this.idProveedor=proveedor;
    },
    rellenarDetalleCompra(id, nombre) {
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
     * Se encarga de eliminar cada Item del detalle de la compra
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
      const totalCompra = document.querySelector('#subTotalCompra')
      totalCompra.textContent = restaTotal
    },
    sumarSubtotal(){
      var parsedobj = JSON.parse(JSON.stringify(this.arrayDetalles))
      const subTotal =parsedobj.reduce((acum,elem,i)=>{
          acum=acum+elem.totalDetalle
          return acum
      },0)
      const subTotalCompra = document.querySelector('#subTotalCompra')
      subTotalCompra.textContent = subTotal
      const totalCompra = document.querySelector('#totalCompra')
      totalCompra.textContent = subTotal
      console.log(subTotal)
    },
    sumarTotal(){
      
      const totalCompra = document.querySelector('#totalCompra')
      totalCompra.textContent = total
   
    },
    obtenerDescuento(){
      const subTotalCompra = document.querySelector('#subTotalCompra');
      const totalCompra = document.querySelector('#totalCompra');
      let dcto  = 0;
      switch (this.descuento) {
        case '5':
          dcto = parseFloat(subTotalCompra.textContent) - parseFloat(subTotalCompra.textContent)*5/100;
          totalCompra.textContent = dcto;
          break;
        case '10':
          dcto = parseFloat(subTotalCompra.textContent) - parseFloat(subTotalCompra.textContent)*10/100;
          totalCompra.textContent = dcto;
          break;
        case '15':
          dcto = parseFloat(subTotalCompra.textContent) - parseFloat(subTotalCompra.textContent)*15/100;
          totalCompra.textContent = dcto;
          break;
        case '20':
          dcto = parseFloat(subTotalCompra.textContent) - parseFloat(subTotalCompra.textContent)*20/100;
          totalCompra.textContent = dcto;
          break;
        case '25':
          dcto = parseFloat(subTotalCompra.textContent) - parseFloat(subTotalCompra.textContent)*25/100;
          totalCompra.textContent = dcto;
          break;
        case '30':
          dcto = parseFloat(subTotalCompra.textContent) - parseFloat(subTotalCompra.textContent)*30/100;
          totalCompra.textContent = dcto;
          break;
        case '40':
          dcto = parseFloat(subTotalCompra.textContent) - parseFloat(subTotalCompra.textContent)*40/100;
          totalCompra.textContent = dcto;
          break;
        case '50':
          dcto = parseFloat(subTotalCompra.textContent) - parseFloat(subTotalCompra.textContent)*50/100;
          totalCompra.textContent = dcto;
          break;
        case '60':
          dcto = parseFloat(subTotalCompra.textContent) - parseFloat(subTotalCompra.textContent)*60/100;
          totalCompra.textContent = dcto;
          break;
        default:
           var parsedobj = JSON.parse(JSON.stringify(this.arrayDetalles))
           const total =parsedobj.reduce((acum,elem,i)=>{
                 acum=acum+elem.totalDetalle
                return acum
              },0)
          totalCompra.textContent = total;
          break;
      }
    },
  
    /**
     * Se encarga de Comprar todo el detalle de la compra
     *
     * @returns {void}
     */
    comprarTodo(){
      this.validarCompra();
      if(this.errorCompra==1)return; 
      let totalCompra = document.querySelector('#totalCompra').textContent;
      let pago = parseInt(document.querySelector('#valor').value);
      let me = this;
      
      //const detalleParse = {...detalleObjeto} // Sacar Observer
     
      //me.arrayDetalles=[];
      //debugger;
      console.log('me Array: '+this.arrayDetalles);
      var url = "/comprar";
      axios
        .post(url ,{ 
              compra:{
                'pago':pago,
                'id_proveedor': this.idProveedor,
                'fecha':this.fecha,
                'totalCompra':parseInt(totalCompra),
                'descuento':parseInt(this.descuento),
              }, 
              detalles:this.arrayDetalles
        }) 
        .then(function (response) {
          var respuesta = response.data;
          document.querySelector('#subTotalCompra').textContent='0';
          document.querySelector('#totalCompra').textContent='0';
          me.arrayDetalles=[];
          me.buscar='';
          me.telefono='';
          me.email='';
          me.descuento=0;
          console.log(respuesta);
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Tu Compra Ha sido Creada',
            showConfirmButton: false,
            timer: 3000
          })
          /* this.arrayDetalles.splice(this.arrayDetalles.lenght);
          totalCompra='0'; */
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
   this.listarProveedores();
   let date = new Date();
   this.fecha=date.toISOString().split('T')[0];
   this.usuarioAuth();  
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