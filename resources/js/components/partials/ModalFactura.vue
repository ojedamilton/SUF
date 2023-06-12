<script>
import PrintJS from 'print-js';
export default {
  props: ["path","modalFlag","userEmpresa","factura","arrayDetalles"],
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
      PrintJS({
        printable: 'modal-body', // ID del elemento que deseas imprimir (el contenido del modal)
        type: 'html',
        targetStyles: ['*'],
        style: `
          .modal-header,
          .modal-footer {
            display: none;
          }
        `
      });
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
        <div class="modal-dialog modal-lg modal-dialog-top" role="document">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button"  @click="cerrarModal()" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
              <div class="modal-body" id="modal-body">
                <div class="col-12">
                  <h4>Datos Empresa</h4>
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
                      <h5>Tipo Factura</h5>
                      <p>
                        {{factura.tipofactura.tipoFactura}}
                      </p>
                    </div>
                    <div class="col-4">
                      <h5>N° de factura</h5>
                      <h5>Fecha</h5>
                    </div>
                    <div class="col-4">
                      <p>{{factura.puntoventa.numPuntoVenta}}-{{factura.numeroFactura}}</p>
                      <p>{{factura.fechaModificacion}}</p>
                    </div>
                  </div>
                  <div class="row my-4">
                    <table class="table table-borderless factura">
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
                          <th>Descuento</th>
                          <th>{{ (factura.totalFactura - (factura.totalFactura / (1 - (factura.descuento / 100)))).toFixed(2) }}</th>
                        </tr>
                        <tr>
                          <th></th>
                          <th></th>
                          <th>Total Factura</th>
                          <th>{{factura.totalFactura}}</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                  <div class="cond row">
                    <div class="col-12 mt-3">
                      <h4>Condiciones y formas de pago</h4>
                      <p>El pago se debe realizar en un plazo de 15 dias.</p>
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