<?php

namespace App\Observers;

use App\Models\Factura;
use App\Models\InterfazVenta;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FacturaObserver
{
    /**
     * Handle the Factura "created" event.
     *
     * @param  \App\Models\Factura  $factura
     * @return void
     */
    public function created(Factura $factura)
    {
        try {
             // Cargar relaciones necesarias
             $factura->load(['puntoventa', 'cliente', 'valor']);

            // Obtener la interfaz de ventas asociada a la empresa de la factura
            $interfazVenta = InterfazVenta::where('id_empresa', $factura->idEmpresa)->first();

            if (!$interfazVenta) {
                Log::warning("No se encontró una interfaz de venta para la empresa ID: {$factura->idEmpresa}");
                return;
            }

            // Formatear los valores según el formato del archivo
            $nLocal = str_pad($interfazVenta->nro_local, 10, ' ', STR_PAD_RIGHT);
            $nContrato = str_pad($interfazVenta->nro_contrato, 10, '0', STR_PAD_LEFT);
            $pos = str_pad($interfazVenta->pos_local, 2, '0', STR_PAD_LEFT);
            $fecha = now()->format('Ymd'); // Fecha en formato AAAAMMDD
            $hora = now()->format('His'); // Hora en formato HHMMSS
            $tipoCompra = 'BD';
            $puntoVentaFiscal = str_pad($factura->puntoventa->numPuntoVenta ?? '060', 3, '0', STR_PAD_LEFT);
            $numeroFactura = str_pad($factura->numeroFactura, 10, '0', STR_PAD_LEFT);
            $operacion = 'N'; // Operación normal
            $codVendedor = str_pad($factura->idUsuario, 2, '0', STR_PAD_LEFT); // Ejemplo de código de vendedor
            $dniCliente = str_pad($factura->cliente->dniCliente ?? '0000000000', 10, '0', STR_PAD_LEFT);
            $medioPago = $factura->valor->nombreValor ?? 'PTV'; // Medio de pago
            $rubroLocal = str_pad('0000', 4, '0', STR_PAD_LEFT);
            $reporteSinIVA = str_pad('0000000000', 10, '0', STR_PAD_LEFT);
            $importeIVA = str_pad('0000000000', 10, '0', STR_PAD_LEFT);
            $ingresoTotal = str_pad(number_format($factura->totalFactura, 2, '', ''), 12, '0', STR_PAD_LEFT);

            // Generar la línea de salida
            $linea = "L{$nLocal}{$nContrato}{$pos}{$fecha}{$hora}{$tipoCompra}{$puntoVentaFiscal}{$numeroFactura}{$operacion}{$codVendedor}{$dniCliente}{$medioPago}{$rubroLocal}{$reporteSinIVA}{$importeIVA}{$ingresoTotal}\r\n";

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
            Log::error("Error al generar el archivo para la factura ID: {$factura->id}. Error: {$e->getMessage()}");
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
