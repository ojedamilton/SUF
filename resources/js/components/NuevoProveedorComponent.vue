<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Nuevo Proveedor</h3>
          </div>
          <!-- form start -->
          <form id="form">
            <div class="card-body">
              <div class="form-group">
                <label for="exampleInputName">Nombre</label>
                <input type="text" class="form-control" v-model="nombre" name="nombre" id="exampleInputName" placeholder="Ingrese Nombre"/>
              </div>
              <div class="form-group">
                <label for="exampleInputApellido">Apellido</label>
                <input type="text" class="form-control" v-model="apellido" name="apellido" id="exampleInputApellido" placeholder="Ingrese Apellido"/>
              </div>
              <div class="form-group">
                <label for="exampleInputCuit">Cuit</label>
                <input type="text" class="form-control"  v-model="cuit" name="cuit" id="exampleInputCuit" placeholder="Ingrese Cuit"/>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input type="email" class="form-control"  v-model="email" name="email" id="exampleInputEmail1" placeholder="Ingrese Email"/>
              </div>
                <div class="form-group">
                <label for="exampleInputDireccion">Dirección</label>
                <input type="text" class="form-control" v-model="direccion" name="direccion" id="exampleInputDireccion" placeholder="Ingrese Direccion"/>
              </div>
              <div class="form-group">
                <label for="exampleInputTelefono">Teléfono</label>
                <input type="text" class="form-control" v-model="telefono" name="telefono" id="exampleInputTelefono" placeholder="Ingrese Teléfono"/>
              </div>
            </div>
            <div class="loader" v-if="loading"></div>
              <div v-show="errorproveedor" class="form-group div-error">
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
        idProveedor: 0,
        nombre:"",
        apellido:"",
        cuit:"",
        email:"",
        direccion:"",
        telefono:"",
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
        errorproveedor: 0,
        errorMostrarMsj: [],
      };
    },
    computed: {
      isActived: function () {
        return this.pagination.current_page;
      }
    },
    methods: {
      allLetter() {
        let a = this._data.nombreproveedor;
        if (!a.match(/^[A-Za-z]+$/)) {
          this._data.nombreRol = "";
        }
        this._data.nombreproveedor = this._data.nombreproveedor.toUpperCase();
        return;
      },
      validarProveedor() {
        this.errorproveedor = 0;
        this.errorMostrarMsj = [];
        if(!this.nombre) this.errorMostrarMsj.push('* El nombre no puede estar vacío');
        if(!this.apellido) this.errorMostrarMsj.push('* El apellido no puede estar vacío');
        if(!this.cuit) this.errorMostrarMsj.push('* El cuit no puede estar vacío');
        if(!this.email) this.errorMostrarMsj.push('* El email no puede estar vacío');
        if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)) this.errorMostrarMsj.push('* El email no es valido');
        if(!this.direccion) this.errorMostrarMsj.push('* La direccion no puede estar vacía');
        if(!this.telefono) this.errorMostrarMsj.push('* El teléfono no puede estar vacío');
        if (this.errorMostrarMsj.length) this.errorproveedor = 1;
      },
      //Implemento Async-Await//
      async enviarForm(){
        this.validarProveedor();
        if(this.errorproveedor==1)return;
        let me=this;
        let url = "api/createProveedor";
        this.loading = true;
        try {
          const response= await axios.post(url,{nombre:this.nombre,apellido:this.apellido,cuit:this.cuit,email:this.email,direccion:this.direccion,telefono:this.telefono})
          let respuesta = response.data;
          if (response.status == 201) {
            let success=respuesta.message;
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: success,
              showConfirmButton: false,
              timer: 4000
            });
            //Limpio variables
            me.nombre='';
            me.apellido='';
            me.cuit='';
            me.email='';
            me.direccion='';
            me.telefono='';
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
      // this.listarGrupos();
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
