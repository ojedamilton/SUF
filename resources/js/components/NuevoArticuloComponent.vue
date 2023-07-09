<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <p class="text-center"><strong> ARTICULOS</strong></p>
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Nuevo Articulo</h3>
          </div>
          <!-- form start -->
          <form id="form">
            <div class="card-body">
              <div class="form-group">
                <label for="exampleInputName">Nombre Articulo</label>
                <input type="text" class="form-control" v-model="nombre" name="nombre" maxlength="50" id="exampleInputName" placeholder="Ingrese Nombre Articulo"/>
              </div>
              <div class="form-group">
                <label for="exampleInputPrecioV">Precio Venta</label>
                <input type="number" class="form-control" v-model="precioV" name="precioV"  min="1" id="exampleInputPrecioV" placeholder="Ingrese Precio Venta"/>
              </div>
              <div class="form-group">
                <label for="exampleInputPrecioC">Precio Compra</label>
                <input type="number" class="form-control"  v-model="precioC" name="precioC"  min="1" max="100" id="exampleInputPrecioC" placeholder="Ingrese precio Compra"/>
              </div>
              <div class="form-group">
                <label for="exampleInputCategoria">Categorias</label>
                <select class="form-control" v-model="categoriaId" id="exampleInputCategoria" name="categoria">
                  <option selected value="0">Seleccione Categoria</option>
                  <option  v-for="categoria in arrayCategorias" :key="categoria.id" :value="categoria.id">{{categoria.nombreCategoria}}</option>
                </select>
              </div>
                <div class="form-group">
                <label for="exampleInputProveedor">Proveedor</label>
                <select class="form-control" v-model="proveedorId" id="exampleInputProveedor" name="proveedor">
                  <option selected value="0">Seleccione Proveedor</option>
                  <option  v-for="proveedor in arrayProveedor" :key="proveedor.id" :value="proveedor.id">{{proveedor.nombreProveedor}}</option>
                </select>
              </div>
              <div class="form-group">
                <label for="exampleInputStock">Cantidad Inicial</label>
                <input type="number" class="form-control" v-model="stock" name="stock" min="1" id="exampleInputstock" placeholder="Ingrese Stock"/>
              </div>
              <div class="form-group">
                <label for="exampleInputStockMinimo">Alerta Minima</label>
                <input type="number" class="form-control" v-model="stockMinimo" name="stockMinimo" min="1" id="exampleInputstockMinimo" placeholder="Ingrese Stock Minimo"/>
              </div>
            </div>
            <div class="loader" v-if="loading"></div>
              <div v-show="errorArticulo" class="form-group div-error">
                <div class="text-left">
                    <div v-for="error in errorMostrarMsj" :key="error" v-text="error">
                    </div>
                </div>
            </div>
            <div class="card-footer">
              <button type="button" @click="enviarForm()" class="btn btn-primary">Crear</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import axios from "axios";
  export default {
    props: ["path"],
    data() {
      return {
        idArticulo: 0,
        categoriaId:0,
        proveedorId:0,
        nombre:"",
        precioV:'',
        precioC:'',
        stock:'',
        stockMinimo:'',
        arrayCategorias:[],
        arrayProveedor:[],
        loading: false,
        modal: 0,
        idCan: "",
        tituloModal: "",
        tipoAccion: 0,
        buscar: "",
        pagination: {
          total: 0,
          current_page: 0,
          per_page: 0,
          last_page: 0,
          from: 0,
          to: 0,
        },
        offset: 3,
        errorArticulo: 0,
        errorMostrarMsj: [],
      };
    },
    computed: {
      isActived: function () {
        return this.pagination.current_page;
      }
    },
    methods: {
      listarCategorias() {
        let me = this;
        var url = "api/categoria";
        axios
          .get(url) // ,{ params: {},} 
          .then(function (response) {
            var respuesta = response.data;
            me.arrayCategorias = respuesta.categorias.data;
          })
          .catch(function (error) {
            console.log(error);
            if (error.response.status === 401) {
              location.reload(true);
            }
        });
      },

      listarProveedores() {
        let me = this;
        var url = "api/proveedores";
        axios
          .get(url) // ,{ params: {},} 
          .then(function (response) {
            var respuesta = response.data;
            me.arrayProveedor = respuesta.proveedores.data;
          })
          .catch(function (error) {
            console.log(error);
            if (error.response.status === 401) {
              location.reload(true);
            }
        });
      },

      validarArticulos() {
        this.errorArticulo = 0;
        this.errorMostrarMsj = [];
        if(!this.nombre) this.errorMostrarMsj.push('* El nombre no puede estar vacío');
        if(!this.precioV) this.errorMostrarMsj.push('* El precio ventra');
        if(!this.precioC) this.errorMostrarMsj.push('* El precio compra');
        if(!this.stock) this.errorMostrarMsj.push('* El stock no puede estar vacío');
        if (this.errorMostrarMsj.length) this.errorArticulo = 1;
      },
      //Implemento Async-Await//
      async enviarForm(){
        this.validarArticulos();
        if(this.errorArticulo==1)return;
        let me=this;
        let url = "api/crearArticulo";
        this.loading = true;
        try {
          const response= await axios.post(url,{nombre:this.nombre,precioC:this.precioC,precioV:this.precioV,proveedorId:this.proveedorId,categoriaId:this.categoriaId,stock:this.stock,stockMinimo:this.stockMinimo})
          let respuesta = response.data;
          if (respuesta.status == 201) {
            let success=respuesta.success;
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: success,
              showConfirmButton: false,
              timer: 4000
            });
            //Limpio variables
            me.nombre='';
            me.precioC='';
            me.precioV='';
            me.proveedorId='0';
            me.categoriaId='0';
            me.stock='';
            me.stockMinimo='';
            this.loading=false
          }else{
            let errors=respuesta.errors;
            let strError=JSON.stringify(errors);
            // Ver array 2 indices con array adentro...
            Swal.fire({
              position: 'center',
              icon: 'error',
              html: strError,
              showConfirmButton: false,
              timer: 4000
            });
          }
          this.loading=false
        } catch (error) {
            console.log('Errores de Conexion'+error);
            this.loading=false
        }
      },
     
    },
    mounted() {
      this.listarCategorias();
      this.listarProveedores();
    },
  };
</script>
<style>
  .modal-content {
    width: 100%;
    position: absolute !important;
  }
  .mostrar {
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color: #3c29297a !important;
  }
  .div-error {
    display: flex;
    justify-content: left;
  }
  .div-error {
    color: red;
    font-weight: bold;
  }
  .loader{  /* Loader Div Class */
      position: absolute;
      top:0px;
      right:0px;
      width:100%;
      height:100%;
      background-color:#eceaea;
      background-image: url('/webfonts/ajax-loader.gif');
      background-size: 50px;
      background-repeat:no-repeat;
      background-position:center;
      z-index:10000000;
      opacity: 0.4;
      filter: alpha(opacity=40);
  }
</style>
