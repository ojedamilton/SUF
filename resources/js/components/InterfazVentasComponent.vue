<template>
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <p class="text-center"><strong>INTERFAZ DE VENTAS</strong></p>
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Configuraciones</h3>
            </div>
            <!-- form start -->
            <form id="form">
              <div class="card-body">
                <div class="form-group">
                  <label for="exampleInputName">Nro Local</label>
                  <input type="text" class="form-control" v-model="nroLocal" name="nroLocal" id="nroLocal" placeholder="Ingrese Nro Local"/>
                </div>
                <div class="form-group">
                  <label for="exampleInputName">Nro Contrato</label>
                  <input type="text" class="form-control" v-model="nroContrato" name="nroContrato" id="nroContrato" placeholder="Ingrese Nro Contrato"/>
                </div>
                <div class="form-group">
                  <label for="exampleInputName">Pos Local</label>
                  <input type="text" class="form-control" v-model="posLocal" name="posLocal" id="posLocal" placeholder="Ingrese pPos Local"/>
                </div>
                <div class="form-group">
                    <label for="exampleInputCategoria">Pto Venta</label>
                    <select class="form-control" v-model="idPtoVta" id="idPtoVta" name="ptoVta">
                      <option selected value="0">Seleccione Punto de Venta</option>
                      <option  v-for="ptoVenta in arrayPtoVentas" :key="ptoVenta.id" :value="ptoVenta.id">{{ptoVenta.numPuntoVenta}}</option>
                    </select>
                </div>
                <div class="form-group">
                  <label for="exampleInputName">Ruta Trancomp</label>
                  <input type="text" class="form-control" v-model="ruta" name="ruta" id="ruta" placeholder="Ingrese Ruta Trancomp"/>
                </div>
              </div>
              <div class="loader" v-if="loading"></div>
                <div v-show="errorInterfazVentas" class="form-group div-error">
                  <div class="ml-3 text-left">
                      <div v-for="error in errorMostrarMsj" :key="error" v-text="error">
                      </div>
                  </div>
              </div>
              <div class="card-footer">
                <button type="button" @click="enviarForm()" class="btn btn-primary">Guardar</button>
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
          idInterfazVenta: 0,
          nroLocal:"",
          nroContrato:"",
          posLocal:"",
          idPtoVta:"",
          ruta:"",
          arrayPtoVentas:[],
          InterfazVenta: {},
          loading: false,
          modal: 0,
          idCan: "",
          tipoAccion: 0,
          pagination: {
            total: 0,
            current_page: 0,
            per_page: 0,
            last_page: 0,
            from: 0,
            to: 0,
          },
          offset: 3,
          errorInterfazVentas: 0,
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
        validarInterfazVentas() {
          this.errorInterfazVentas = 0;
          this.errorMostrarMsj = [];
          if(!this.nroLocal) this.errorMostrarMsj.push('* El Nro Local no puede estar vacío');
          if(!this.nroContrato) this.errorMostrarMsj.push('* El Nro Contrato no puede estar vacío');
          if(!this.posLocal) this.errorMostrarMsj.push('* El Pos Local no puede estar vacío');
          if(!this.idPtoVta) this.errorMostrarMsj.push('* El Pto Vta no puede estar vacío');
          if(!this.ruta) this.errorMostrarMsj.push('* La Ruta no puede estar vacía');
          if (this.errorMostrarMsj.length) this.errorInterfazVentas = 1;
        },
        listarPtoVenta() {
          let me = this;
          var url = "api/ptoventa";
          axios
          .get(url) // ,{ params: {},} 
          .then(function (response) {
              var respuesta = response.data;
              me.arrayPtoVentas = respuesta.ptoVenta;
          })
          .catch(function (error) {
              console.log(error);
              if (error.response.status === 401) {
              location.reload(true);
              }
          });
        },
        listarInterfazVentas() {
          let me = this;
          var url = "api/interfazVentaByEmpresa";
          axios
          .get(url) // ,{ params: {},} 
          .then(function (response) {
              var respuesta = response.data;
              me.InterfazVenta = respuesta.interfazVenta;
              me.idInterfazVenta = me.InterfazVenta.id;
              me.nroContrato = me.InterfazVenta.nro_contrato;
              me.nroLocal = me.InterfazVenta.nro_local;
              me.posLocal = me.InterfazVenta.pos_local;
              me.idPtoVta = me.InterfazVenta.id_pto_vta;
              me.ruta = me.InterfazVenta.ruta;
          })
          .catch(function (error) {
              console.log(error);
              if (error.response.status === 401) {
              location.reload(true);
              }
          });
        },
        //Implemento Async-Await//
        async enviarForm(){
          this.validarInterfazVentas();
          if(this.errorInterfazVentas==1)return;
          let me=this;
          let url = "api/crearinterfazventas";
          this.loading = true;
          try {
            const response= await axios.post(url,{
              idInterfazVenta:this.idInterfazVenta,
              nroLocal:this.nroLocal,
              nroContrato:this.nroContrato,
              posLocal:this.posLocal,
              idPtoVta:this.idPtoVta,
              ruta:this.ruta
            })
            let respuesta = response.data;
            let message=respuesta.message;
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: message,
              showConfirmButton: false,
              timer: 4000
            });
            this.loading=false
          } catch (error) {
              let errorMessage=error.response.data.message;
              Swal.fire({
                position: 'center',
                icon: 'error',
                title: errorMessage,
                showConfirmButton: false,
                timer: 4000
              });
              this.loading=false
          }
        },
      },
      mounted() {
        this.listarPtoVenta();
        this.listarInterfazVentas();
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
    .loader{
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