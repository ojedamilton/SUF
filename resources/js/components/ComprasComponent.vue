<template>
  <main class="container">
    <div class="card my-3">
      <div class="d-flex card-header">
        <div class="p-0 flex-grow-1">
          <h5><i class="bi bi-plus-circle"></i> Nueva Compra</h5>
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
                <button v-for="cliente in arrayClientes" :key="cliente.id" :value="cliente.id" type="button" @click="rellenarCampos(cliente.nombreCliente,cliente.apellidoCliente,cliente.telefonoCliente,cliente.emailCliente,cliente.id)" class="dropdown-item" data-value="1" data-email="asd@asd.com">
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
              <input type="text" class="form-control input-sm" id="fecha" v-model="fecha" readonly="" />
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
                  class="btn btn-success"
                  @click="abrirModal(),listarArticulos(buscarArticulo)"
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
                     <button @click="eliminarItem(index)" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                   </td>
                </tr>      
              <tr>
                <td class="text-end" colspan="4">TOTAL $</td>
                <td id="totalFactura" class="text-end">0.00</td>
                <td></td>
              </tr>
            </tbody>
          </table>
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
              <div class="modal-body">
                  <div class="c">
                        <div class="c-header">
                            <!-- Find a result -->
                            <input type="text" v-model="buscarArticulo"  @keyup="listarArticulos(buscarArticulo)" class="form-control" placeholder="Texto a buscar">     
                        </div> 
                        <!-- List Table Details --> 
                        <div class="cbody">   
                            <table id="table_articulo" class="table table-striped" width="100%">
                                <thead>
                                <tr>  
                                    <th>Codigo</th>
                                    <th>articulo</th>
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
                                          <input class="form-control form-control-sm" :value="articulo.precio" type="number" name="precio" id="precio">
                                        </td>
                                        <td class="col-2">
                                          <input class="form-control form-control-sm lineacantidad" value="1" type="number" name="cantidad" :id="articulo.id">
                                          </td>
                                        <td>
                                          <button @click="rellenarDetalleFactura(articulo.id,articulo.nombreArticulo,articulo.precio),sumarTotal()" class="btn btn-primary">Add</button> <!--  -->
                                        </td>
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
import axios from "axios"; // Importo libreria Axios
export default {  // todo lo que voy a exportar
  props: ["path"], // obtengo constante definida en app.js
  data() {  // variables con las que me manejo en el template
    return {
      arrayValores: [],
      arrayClientes: [],
      arrayArticulos:[],
      arrayDetalles:[],
      buscar:'',
      buscarArticulo:'',
      pagination:{
                  'total':0,
                  'current_page':0,
                  'per_page':0,
                  'last_page':0,
                  'from':0,
                  'to':0,
      },
      telefono:'',
      email:'',
      fecha:'',
      cliente:'',
      idCliente:0,
      precio:0,
      cantidadArtModal:1,
      tituloModal: "",
      nombreVendedor: "",
      description: "",
      modal:0,
    }
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
  methods:{  // metodos comunes impulsados por eventos
    listarValores() {
      let me = this;
      var url = "/valores";
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
    abrirModal(){
     this.modal=1;
    },
    cerrarModal(){
     this.modal=0;
    },
    listarClientes(buscar) {
      let me = this;
      var url = "/clientes?buscar="+buscar;
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
    listarArticulos(buscarArticulo) {
      let me = this;
      var url = "/articulos?buscar="+buscarArticulo;
      axios
        .get(url) // ,{ params: {},} 
        .then(function (response) {
          var respuesta = response.data;
          me.arrayArticulos = respuesta.articulos;
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 401) {
            location.reload(true);
          }
        });
    },
    rellenarCampos(n,a,t,e,c){
        this.telefono=t;
        this.email=e;
        this.buscar=n+' '+a;
        this.idCliente=c;
    },
    rellenarDetalleFactura(id,nombre,precio,cantidad,t){
      const valorCantidad =parseInt(document.getElementById(id).value)     
       const detalleObjeto ={}
       detalleObjeto.idArticulo=id
       detalleObjeto.nombre=nombre
       detalleObjeto.precioVenta=parseInt(precio)
       detalleObjeto.cantidadArticulo=valorCantidad
       detalleObjeto.totalDetalle=valorCantidad*precio
       // const detalleParse= JSON.parse(JSON.stringify(detalleObjeto))
       const detalleParse = {...detalleObjeto} // Sacar Observer
       this.arrayDetalles.push(detalleParse);
       //console.log(this.arrayDetalles.splice(this.arrayDetalles.lenght));
       //debugger
      //this.arrayDetalles.splice(0,1);
    },
    eliminarItem(id){
      var parsedobj = JSON.parse(JSON.stringify(this.arrayDetalles))
      console.log(parsedobj)
      this.arrayDetalles.splice(id,1)
      const restaTotal =  parsedobj.reduce((acum,elem,i)=>{
        console.log(id,i);
          if (id != i) {
           acum=acum+elem.totalDetalle
           return acum 
          }else{
            return acum
          }
      },0)
      console.log(restaTotal)
      const totalFactura = document.querySelector('#totalFactura')
      totalFactura.textContent = restaTotal
    },
    sumarTotal(){
      var parsedobj = JSON.parse(JSON.stringify(this.arrayDetalles))
      const total =parsedobj.reduce((acum,elem,i)=>{
          acum=acum+elem.totalDetalle
          return acum
      },0)
      const totalFactura = document.querySelector('#totalFactura')
      totalFactura.textContent = total
      console.log(total)
    },
    facturarTodo(){
      let totalFactura = document.querySelector('#totalFactura').textContent
      let pago = parseInt(document.querySelector('#valor').value);
      let me = this;
      //const detalleParse = {...detalleObjeto} // Sacar Observer
      me.arrayDetalles=[];
      //debugger;
      console.log('me Array: '+this.arrayDetalles);
      var url =+ "/facturar";
      axios
        .post(url ,{ 
              factura:{
                'pago':pago,
                'id_cliente': this.idCliente,
                'fecha':this.fecha,
                'totalFactura':parseInt(totalFactura)
              }, 
              detalles:this.arrayDetalles
        }) 
        .then(function (response) {
          var respuesta = response.data;
          console.log(respuesta);
          /* this.arrayDetalles.splice(this.arrayDetalles.lenght);
          totalFactura='0'; */
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 401) {
            location.reload(true);
          }
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
  mounted() {  // se auto-ejecuta apenas termina de cargar el DOM
   this.listarValores();
   this.listarClientes();
   let date = new Date();
   this.fecha=date.toISOString().split('T')[0];
  
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