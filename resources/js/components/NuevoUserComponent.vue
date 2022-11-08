<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Nuevo Usuario</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form>
            <div class="card-body">
              <div class="form-group">
                <label for="exampleInputName">Nombre</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleInputName"
                  placeholder="Enter Name"
                />
              </div>
              <div class="form-group">
                <label for="exampleInputName">Apellido</label>
                <input
                  type="text"
                  class="form-control"
                  id="exampleInputName"
                  placeholder="Enter Name"
                />
              </div>
              <div class="form-group">
                <label>Grupo/s</label>
                <select multiple="" class="form-control">
                    <option>Administrador</option>
                    <option>Vendedor</option>
                    <option>Comprador</option>
                </select>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="exampleInputEmail1"
                  placeholder="Enter email"
                />
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Enviar Correo</button>
              <a href="http://127.0.0.1:8000/notificacioncorreo">Enviar</a>
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
      idRol: [],
      idRoleUser: [],
      idRolVmodle: [],
      idRolBack: 0,
      modal: 0,
      idCan: "",
      tituloModal: "",
      nombreuser: "",
      description: "",
      tipoAccion: 0,
      buscar: "",
      arrayUser: [],
      arrayRoles: [],
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
    },
    pagesNumber: function () {
      if (!this.pagination.to) {
        return [];
      }
      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }
      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    },
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
    listaruser(page, buscar) {
      let me = this;
      var url = "/usuarios?page=" + page + "&buscar=" + buscar;
      axios
        .get(url, {
          params: {},
        })
        .then(function (response) {
          var respuesta = response.data;
          me.arrayUser = respuesta.users.data;
          //respuesta.array_namesector.map(value=>{ me.arraynameSector.push(value.sector);});
          me.pagination = respuesta.pagination;
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
    listarRoles() {
      let me = this;
      var url = "/role?destino=rolpermiso";
      axios
        .get(url, {
          params: {},
        })
        .then(function (response) {
          var respuesta = response.data;
          me.arrayRoles = respuesta.rolepermiso;
          console.log(respuesta.role.data[1].name);
          //me.idRolUser=respuesta.role.data[1].name;
          me.pagination = respuesta.pagination;
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
    cambiarPagina(page, buscar) {
      let me = this;
      //Actualizar pagina actual
      me.pagination.current_page = page;
      //Enviar la petición para visualizar la data de esa página
      me.listaruser(page, buscar);
    },
    cerrarModal() {
      this.modal = 0;
      this.nombreuser = "";
      this.description = "";
    },
    abrirModal(modelo, accion, data = []) {
      let self = this;
      /*   Vue.nextTick()
                    .then(function () {
                        self.$refs.input_nombre.focus();
                }); */
      switch (modelo) {
        case "user": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Usuario";
              this.nombreuser = "";
              this.description = "";
              this.tipoAccion = 1;
              break;
            }
            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Asignar Rol Usuario";
              this.idUser = data["id"];
              this.idRol = data["idrol"];
              this.idRoleUser = [];
              data["roles"].forEach((element) =>
                this.idRoleUser.push(element["id"])
              );
              this.idRolBack = data["idrol"];
              this.tipoAccion = 2;
              break;
            }
          }
        }
      }
    },
    validaruser(ubicacionmodal) {
      this.erroruser = 0;
      this.errorMostrarMsjuser = [];
      //if(!this.idRol) this.errorMostrarMsjuser.push('* El rol no puede estar vacío');
      if (this.errorMostrarMsjuser.length) this.erroruser = 1;
    },
    registraroluser() {
      this.validaruser("registrar");
      if (this.erroruser == 1) {
        return;
      }
      let me = this;
      var url = "/nuevorolusuario";
      axios
        .post(url, {
          model_id: this.idUser,
          role_id: this.idRol,
          idRoleUser: this.idRoleUser,
        })
        .then(function (response) {
          if (response.data === "duplicado") {
            swal.fire({
              title: "Duplicado!",
              text: "El registro esta Duplicado.",
              icon: "error",
              timer: 1500,
              timerProgressBar: true,
            });
          } else {
            me.cerrarModal();
            me.listaruser(1, me.buscar);
            swal.fire({
              title: "Creado!",
              text: "El Rol fue Creado.",
              icon: "success",
              timer: 1500,
              timerProgressBar: true,
            });
          }
        })
        .catch(function (error) {
          console.log(error);
          if (error.response.status === 401) {
            location.reload(true);
          }
        });
    },
    Actualizaroluser() {
      this.validaruser("actualizar");
      if (this.erroruser == 1) {
        return;
      }
      let me = this;
      var url = "/updaterolusuario";
      axios
        .put(url, {
          model_id: this.idUser,
          role_id: this.idRoleUser,
          //'role_id_back':this.idRolBack,
          idRoleUser: this.idRoleUser,
        })
        .then(function (response) {
          if (response.data === "duplicado") {
            swal.fire({
              title: "Duplicado!",
              text: "El registro esta Duplicado.",
              icon: "error",
              timer: 1500,
              timerProgressBar: true,
            });
          } else {
            me.cerrarModal();
            me.listaruser(me.pagination.current_page, me.buscar);
            me.methodCan();
            swal.fire({
              title: "Editado!",
              text: "El Rol fue Editado.",
              icon: "success",
              timer: 1500,
              timerProgressBar: true,
            });
          }
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
    this.listaruser(1, this.buscar);
    this.listarRoles();
    this.methodCan();
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
</style>
