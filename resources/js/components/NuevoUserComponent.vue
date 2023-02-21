<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Nuevo Usuario</h3>
          </div>
          <!-- form start -->
          <form id="form">
            <div class="card-body">
              <div class="form-group">
                <label for="exampleInputName">Nombre</label>
                <input type="text" class="form-control" v-model="nombre" name="nombre" id="exampleInputName" placeholder="Enter Name"/>
              </div>
              <div class="form-group">
                <label for="exampleInputName">Apellido</label>
                <input type="text" class="form-control" v-model="apellido" name="apellido" id="exampleInputApellido" placeholder="Enter Name"/>
              </div>
              <div class="form-group">
                <label>Grupo/s</label>
                <div v-for=" grupo in arrayGrupos" :key="grupo.id" class="custom-control custom-checkbox">
                  <input class="custom-control-input" v-model="idGrupos" type="checkbox" :value="grupo.id"  ref="input_nombre" :id="grupo.nombreGrupo"> 
                  <label :for="grupo.nombreGrupo" class="custom-control-label">{{grupo.nombreGrupo}}</label>  
                </div>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input type="email" class="form-control"  v-model="email" name="email" id="exampleInputEmail1" placeholder="Enter email"/>
              </div>
               <div class="form-group">
                <label for="exampleInputEmail1">Contraseña</label>
                <input type="password" class="form-control" v-model="password" name="password" id="password" placeholder="Enter Password"/>
              </div>
            </div>
            <div class="loader" v-if="loading"></div>
             <div v-show="erroruser" class="form-group div-error">
                <div class="text-left">
                    <div v-for="error in errorMostrarMsjuser" :key="error" v-text="error">
                    </div>
                </div>
            </div>
            <div class="card-footer">
              <button type="button" @click="enviarForm()" class="btn btn-primary">Enviar Correo</button>
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
      idUser: 0,
      idGrupos: [],
      nombre:"",
      apellido:"",
      email:"",
      password:"",
      loading: false,
      modal: 0,
      idCan: "",
      tituloModal: "",
      tipoAccion: 0,
      buscar: "",
      arrayGrupos: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
      },
      offset: 3,
      erroruser: 0,
      errorMostrarMsjuser: [],
    };
  },
  computed: {
    isActived: function () {
      return this.pagination.current_page;
    }
  },
  methods: {
    allLetter() {
      let a = this._data.nombreuser;
      if (!a.match(/^[A-Za-z]+$/)) {
        this._data.nombreRol = "";
      }
      this._data.nombreuser = this._data.nombreuser.toUpperCase();
      return;
    },
    listarGrupos() {
      let me = this;
      var url = "/grupos";
      axios
        .get(url, {
          params: {},
        })
        .then(function (response) {
          var respuesta = response.data;
          me.arrayGrupos = respuesta.grupos;
          //me.pagination = respuesta.pagination;
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 401) {
            location.reload(true);
          }
        })
        .then(function () {
          // always executed
        });
    },
    validaruser() {
      this.erroruser = 0;
      this.errorMostrarMsjuser = [];
      if(!this.nombre) this.errorMostrarMsjuser.push('* El nombre no puede estar vacío');
      if(!this.apellido) this.errorMostrarMsjuser.push('* El apellido no puede estar vacío');
      if(!this.email) this.errorMostrarMsjuser.push('* El email no puede estar vacío');
      if(!this.password) this.errorMostrarMsjuser.push('* La contraseña no puede estar vacía');
      if(!/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)) this.errorMostrarMsjuser.push('* El email no es valido');
      if(this.idGrupos.length==0) this.errorMostrarMsjuser.push('* El Grupo no puede estar vacío');
      if (this.errorMostrarMsjuser.length) this.erroruser = 1;
    },
    //Implemento Async-Await//
    async enviarForm(){
      this.validaruser();
      if(this.erroruser==1)return; 
      let me=this;
      let url = "/crearusuario";
      this.loading = true;
      try {
        const response= await axios.post(url,{nombre:this.nombre,apellido:this.apellido,grupo:[...this.idGrupos],email:this.email,password:this.password})
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
          me.apellido='';
          me.email='';
          me.idGrupos=[];
          me.password='';  
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
    methodCan() {
      let me = this;
      var url = "/roleuser";
      axios
        .get(url, {
          params: {},
        })
        .then(function (response) {
          me.idCan = response.data;
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 401) {
            location.reload(true);
          }
        });
    },
    eliminaruser(idUser) {
      const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: "btn btn-primary",
          cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
      });
      swalWithBootstrapButtons
        .fire({
          title: "Estas seguro de eliminarlo?",
          text: "You won't be able to revert this!",
          icon: "question",
          showCancelButton: true,
          confirmButtonText: "Aceptar",
          cancelButtonText: "Cancelar",
          reverseButtons: true,
        })
        .then((result) => {
          if (result.value) {
            let me = this;
            var url = "/deleterolusuario";
            axios
              .post(url, {
                id: idUser,
              })
              .then(function (response) {
                me.cerrarModal();
                me.listaruser(me.pagination.current_page, me.buscar);
                me.methodCan();
                swal.fire({
                  title: "Eliminado!",
                  text: "El registro fue Eliminado.",
                  icon: "success",
                  timer: 1500,
                  timerProgressBar: true,
                });
              })
              .catch(function (error) {
                console.log(error);
                if (error.response.status === 401) {
                  location.reload(true);
                }
              });
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            swal.fire({
              title: "Cancelled",
              text: "Your imaginary file is safe ",
              icon: "error",
              timer: 1500,
              timerProgressBar: true,
            });
          }
        });
    },
  },
  mounted() {
    this.listarGrupos();
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
