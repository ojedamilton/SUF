<?php

namespace App\Observers;

use App\Models\NotaCredito;
use App\Models\InterfazVenta;
use App\Models\TipoFactura;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NotaCreditoObserver
{
    /**
     * Handle the NotaCredito "created" event.
     *
     * @param  \App\Models\NotaCredito  $notacredito
     * @return void
     */
    public function created(NotaCredito $notascredito)
    {
        try {
             // Cargar relaciones necesarias
             $notascredito->load(['puntoventa', 'cliente', 'tipofactura']);

            // Obtener la interfaz de ventas asociada a la empresa de la factura
            $interfazVenta = InterfazVenta::where('id_empresa', $notascredito->idEmpresa)->first();

            if (!$interfazVenta) {
                Log::warning("No se encontró una interfaz de venta para la empresa ID: {$notascredito->idEmpresa}");
                return;
            }

            // Formatear los valores según el formato del archivo
            $nLocal = str_pad($interfazVenta->nro_local, 10, ' ', STR_PAD_RIGHT);
            $nContrato = str_pad($interfazVenta->nro_contrato, 10, '0', STR_PAD_LEFT);
            $pos = str_pad($interfazVenta->pos_local, 2, '0', STR_PAD_LEFT);
            $fecha = now()->format('Ymd'); // Fecha en formato AAAAMMDD
            $hora = now()->format('His'); // Hora en formato HHMMSS
            $tipoCompra = substr($notascredito->tipofactura->tipoFactura ?? 'B', 0, 1) . 'C'; // -> 'C' Para NC, segun requerimientos
            $puntoVentaFiscal = str_pad($notascredito->puntoventa->numPuntoVenta ?? '0000', 4, '0', STR_PAD_LEFT);
            $numeroNotaCredito = str_pad($notascredito->numeroNotaCredito, 8, '0', STR_PAD_LEFT);
            $operacion = 'N'; // Operación normal
            $codVendedor = str_pad($notascredito->idUsuario, 2, '0', STR_PAD_LEFT); // Ejemplo de código de vendedor
            $dniCliente = 'DNI' . str_pad($notascredito->cliente->dniCliente ?? '0', 9, '0', STR_PAD_LEFT);
            $medioPago = 'PE  '; // Medio de pago
            $rubroLocal = str_pad('0000', 4, '0', STR_PAD_LEFT);
            $reporteSinIVA = str_pad('000000000', 9, '0', STR_PAD_LEFT);
            $importeIVA = str_pad('000000000', 9, '0', STR_PAD_LEFT);
            $ingresoTotal = str_pad(number_format($notascredito->totalNotaCredito, 2, '.', ''), 9, '0', STR_PAD_LEFT);


            // Generar la línea de salida
            $linea = "{$nLocal}{$nContrato}{$pos}{$fecha}{$hora}{$tipoCompra}{$puntoVentaFiscal}{$numeroNotaCredito}{$operacion}{$codVendedor}{$dniCliente}{$medioPago}{$rubroLocal}{$reporteSinIVA}{$importeIVA}{$ingresoTotal}\r\n";

            // Determinar la ruta donde se guardará el archivo
            $filePath = $interfazVenta->ruta;

            // Escribir en el archivo
            //Storage::put($filePath, $linea);

            // Crear el directorio si no existe
            if (!is_dir(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }

            // Escribir en el archivo
            file_put_contents($filePath, $linea, FILE_APPEND);

            Log::info("Archivo generado correctamente en: {$filePath}");
        } catch (\Exception $e) {
            Log::error("Error al generar el archivo para la factura ID: {$notascredito->id}. Error: {$e->getMessage()}");
        }
    }

    /**
     * Handle the Factura "updated" event.
     *
     * @param  \App\Models\Factura  $factura
     * @return void
     */
    public function updated(Factura $factura)
    {
        //
    }

    /**
     * Handle the Factura "deleted" event.
     *
     * @param  \App\Models\Factura  $factura
     * @return void
     */
    public function deleted(Factura $factura)
    {
        //
    }

    /**
     * Handle the Factura "restored" event.
     *
     * @param  \App\Models\Factura  $factura
     * @return void
     */
    public function restored(Factura $factura)
    {
        //
    }

    /**
     * Handle the Factura "force deleted" event.
     *
     * @param  \App\Models\Factura  $factura
     * @return void
     */
    public function forceDeleted(Factura $factura)
    {
        //
    }
}
