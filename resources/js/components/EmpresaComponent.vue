<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Registrar Empresa</h3>
          </div>
          <!-- form start -->
          <form id="form">
            <div class="card-body">
              <div class="form-group">
                <label for="exampleInputName">Nombre</label>
                <input type="text" class="form-control" v-model="nombre" name="nombre" id="exampleInputName" placeholder="Ingrese Nombre"/>
              </div>
              <div class="form-group">
                <label for="exampleInputRazonSocial">Razón Social</label>
                <input type="text" class="form-control" v-model="razonsocial" name="razonsocial" id="exampleInputRazonSocial" placeholder="Ingrese Razón Social"/>
              </div>
              <div class="form-group">
                <label for="exampleInputCuit">Cuit</label>
                <input type="text" class="form-control"  v-model="cuit" name="cuit" id="exampleInputCuit" placeholder="Ingrese Cuit"/>
              </div>
              <div class="form-group">
                <label for="exampleInputIngresosBrutos">Ingresos Brutos</label>
                <input type="text" class="form-control" v-model="ingresosbrutos" name="ingresosbrutos" id="exampleInputIngresosBrutos" placeholder="Ingrese Ingresos Brutos"/>
              </div>
                <div class="form-group">
                <label for="exampleInputDireccion">Dirección</label>
                <input type="text" class="form-control" v-model="direccion" name="direccion" id="exampleInputDireccion" placeholder="Ingrese Direccion"/>
              </div>
              <div class="form-group">
                <label for="exampleInputTelefono">Teléfono</label>
                <input type="tel" class="form-control" v-model="telefono" name="telefono" id="exampleInputTelefono" placeholder="Ingrese Teléfono"/>
              </div>
              <div class="form-group">
                <label for="exampleInputInicioActividades">Inicio de Actividades</label>
                <input type="date" class="form-control" v-model="inicioActividades" name="inicioActividades" id="exampleInputInicioActividades"/>
              </div>
              <div class="form-group">
                <label for="exampleInputSituacion">Tipo de Empresa</label>
                <select class="form-control" v-model="tipoId" id="tiposempresas" name="tiposempresas">
                <option  v-for="tiposempresas in arrayTiposEmpresas" :key="tiposempresas.id" :value="tiposempresas.id">{{tiposempresas.nombreTipoEmpresa}}</option>
              </select>
              </div>
            </div>
            <div class="loader" v-if="loading"></div>
              <div v-show="errorempresa" class="form-group div-error">
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
        idEmpresa: 0,
        nombre:"",
        razonsocial:"",
        cuit:"",
        ingresosbrutos:"",
        direccion:"",
        telefono:"",
        inicioActividades:"",
        arrayTiposEmpresas:[],
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
        tipoId: 0,
        errorempresa: 0,
        errorMostrarMsj: [],
      };
    },
    computed: {
      isActived: function () {
        return this.pagination.current_page;
      }
    },
    methods: {
      listarTipoEmpresa() {
      let me = this;
      var url = "api/tiposempresas";
      axios
        .get(url)
        .then(function (response) {
          var respuesta = response.data;
          me.arrayTiposEmpresas = respuesta.tiposempresas;
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 401) {
            location.reload(true);
          }
        });
    },
      validarTipoEmpresa() {
        this.errorempresa = 0;
        this.errorMostrarMsj = [];
        if(!this.nombre) this.errorMostrarMsj.push('* El nombre no puede estar vacío');
        if(!this.razonsocial) this.errorMostrarMsj.push('* La razón social no puede estar vacía');
        if(!this.cuit) this.errorMostrarMsj.push('* El cuit no puede estar vacío');
        if(!this.ingresosbrutos) this.errorMostrarMsj.push('* El ingreso bruto no puede estar vacío');
        if(!this.direccion) this.errorMostrarMsj.push('* La direccion no puede estar vacía');
        if(!this.telefono) this.errorMostrarMsj.push('* El teléfono no puede estar vacío');
        if(!this.inicioActividades) this.errorMostrarMsj.push('* La fecha no puede estar vacía');
        if(!this.tipoId) this.errorMostrarMsj.push('* El tipo de empresa no puede estar vacío');
        if (this.errorMostrarMsj.length) this.errorempresa = 1;
      },
      //Implemento Async-Await//
      async enviarForm(){
        this.validarTipoEmpresa();
        if(this.errorempresa==1)return;
        let me=this;
        let url = "/createEmpresa";
        this.loading = true;
        try {
          const response= await axios.post(url,{nombre:this.nombre,razonsocial:this.razonsocial,cuitEmpresa:this.cuit,ingresosbrutos:this.ingresosbrutos,direccion:this.direccion,telefono:this.telefono,inicioActividades:this.inicioActividades,tipoId:this.tipoId})
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
            me.razonsocial='';
            me.cuit='';
            me.inicioActividades='';
            me.direccion='';
            me.telefono='';
            me.ingresosbrutos='';
            me.tipoId=0;
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
      this.listarTipoEmpresa();
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
