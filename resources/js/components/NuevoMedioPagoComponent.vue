<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <p class="text-center"><strong>ABM MEDIO DE PAGO</strong></p>
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Nuevo Medio De Pago</h3>
          </div>
          <!-- form start -->
          <form id="form">
            <div class="card-body">
              <div class="form-group">
                <label for="exampleInputName">Nombre</label>
                <input type="text" class="form-control" v-model="nombre" name="nombre" id="exampleInputName" placeholder="Ingrese Nombre"/>
              </div>
            </div>
            <div class="loader" v-if="loading"></div>
              <div v-show="errormediopago" class="form-group div-error">
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
        idMedioPago: 0,
        nombre:"",
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
        errormediopago: 0,
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
        let a = this._data.nombrevalor;
        if (!a.match(/^[A-Za-z]+$/)) {
          this._data.nombreRol = "";
        }
        this._data.nombrevalor = this._data.nombrevalor.toUpperCase();
        return;
      },
      validarMedioPago() {
        this.errormediopago = 0;
        this.errorMostrarMsj = [];
        if(!this.nombre) this.errorMostrarMsj.push('* El nombre no puede estar vacío');
        if (this.errorMostrarMsj.length) this.errormediopago = 1;
      },
      //Implemento Async-Await//
      async enviarForm(){
        this.validarMedioPago();
        if(this.errormediopago==1)return;
        let me=this;
        let url = "/crearvalores";
        this.loading = true;
        try {
          const response= await axios.post(url,{nombre:this.nombre})
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
      // methodCan() {
      //   let me = this;
      //   var url = "/roleuser";
      //   axios
      //     .get(url, {
      //       params: {},
      //     })
      //     .then(function (response) {
      //       me.idCan = response.data;
      //     })
      //     .catch(function (error) {
      //       console.log(error);
      //       if (error.response.status === 401) {
      //         location.reload(true);
      //       }
      //     });
      // },
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
