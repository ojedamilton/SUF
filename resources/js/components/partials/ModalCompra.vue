<script>
import PrintJS from 'print-js';
export default {
  props: ["path","modalFlag","userEmpresa","compra","arrayDetalles", "arrayProveedores"],
  data() {
    return {
      // Obtengo el valor del modalFlag del padre
      modalLocal: this.modalFlag,
    };
  },
  methods: {
    /**
     * Emito el evento para que el padre cambie el valor del modalFlag
     * @return event 'cambiar-modal'
     */
    cerrarModal() {
      this.modalLocal = 0;
      this.$emit('cambiar-modal', 0);
    },
    downloadPage() {
      const modalBody = document.getElementById('modal-body');
      const originalOverflow = modalBody.style.overflow;

      try {
        // Mostrar todo el contenido del modal
        modalBody.style.overflow = 'visible';

        PrintJS({
          printable: 'modal-body',
          type: 'html',
          targetStyles: ['*'],
          ignoreElements: ['modal-header', 'modal-footer'], // Opcional: Ignorar los elementos de encabezado y pie de página
        });
      } catch (error) {
        // Manejar el error, si lo deseas
      } finally {
        // Restaurar el estado original del desplazamiento después de la impresión o la cancelación
        modalBody.style.overflow = originalOverflow;
      }
    },
  },
  // Escucho cambios que se produzcan en el padre
  watch: {
    modalFlag: function (val) {
      console.log("modalFlag changed");
      this.modalLocal = this.modalFlag
    },
  },  
}
</script>
<template>
    <div class="modal fade" :class="{'mostrar': modalLocal}" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-top modal-dialog-scrollable pt-5" >
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button"  @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body" id="modal-body">
                <div class="col-12">
                  <h4>Empresa</h4>
                  <div class="row my-3">
                    <div class="col-10">
                      <p><strong>Nombre:</strong> {{userEmpresa.nombreEmpresa}}</p>
                      <p><strong>Cuit: </strong>{{userEmpresa.cuitEmpresa}}</p>
                      <p><strong>Direccion: </strong>{{userEmpresa.direccionEmpresa}}</p>
                      <p><strong>Inicio Actividad: </strong>{{userEmpresa.inicioActividades}}</p>
                    </div>
                    <div class="col-2">
                      <img src="webfonts/suf.png" />
                    </div>
                  </div>
                  
                  <hr />
                  
                  <div class="row fact-info mt-3 col-12">
                    <div class="col-4">
                      <h5>N° de compra</h5>
                      <h5>Fecha</h5>
                    </div>
                    <div class="col-4">
                      <p>{{ compra.numeroCompra }}</p>
                      <p>{{compra.fechaCompra}}</p>
                    </div>
                  </div>
                  <div class="row my-4">
                    <table class="table table-borderless compra">
                      <thead>
                        <tr>
                          <th>Cant.</th>
                          <th>Descripcion</th>
                          <th>Precio Unitario</th>
                          <th>Importe</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="detalle in arrayDetalles" :key="detalle.id" >    
                          <td>{{detalle.cantidadArticulo}}</td>
                          <td>{{detalle.articulo.nombreArticulo}}</td> 
                          <td>{{detalle.precioVenta}}</td>    
                          <td>{{detalle.totalDetalle}}</td>
                        </tr>  
                      </tbody>
                      <tfoot>
                        <tr>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                        </tr>
                        <tr>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                        </tr>
                        <tr>
                          <th></th>
                          <th></th>
                          <th class="font-weight-normal">Subtotal</th>
                          <th class="font-weight-normal">{{ (compra.totalCompra / (1 - this.compra.descuento / 100)).toFixed(2) }}</th>
                        </tr>
                        <tr>
                          <th></th>
                          <th></th>
                          <th class="font-weight-normal">Descuento</th>
                          <th class="font-weight-normal">{{ (compra.totalCompra - (compra.totalCompra / (1 - (compra.descuento / 100)))).toFixed(2) }}</th>
                        </tr>
                        <tr>
                          <th></th>
                          <th></th>
                          <th>Total Compra</th>
                          <th>{{compra.totalCompra}}</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  <div class="cond row">
                    <div class="col-12 mt-3">
                      <div class="col-10">
                      <p><strong>Proveedor: </strong> {{arrayProveedores.nombreProveedor}} {{arrayProveedores.apellidoProveedor}}</p>
                      <p><strong>Email: </strong>{{arrayProveedores.emailProveedor}}</p>
                    </div>
                    </div>
                  </div>
               </div>
            </div>
            <div class="col-12">
              <div class="d-flex justify-content-md-center py-3">
                <button class="btn btn-primary" @click="downloadPage()" >Descargar</button>
              </div>
            </div>  
          </div>
        </div>
      </div>
</template>
<style>
.modal-content{
  width: 100%;
  margin-top:30px;
  position: absolute !important;
}
.mostrar{
  display: list-item !important;
  opacity: 1 !important;
  position: absolute !important;
  background-color: #3c29297a !important;
}</style>