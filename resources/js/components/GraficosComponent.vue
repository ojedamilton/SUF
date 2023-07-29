<template >
    <section class="content col-lg-10 mt-2 ml-5">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row" style="display: flex; justify-content: center">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-orange">
                        <div class="inner">
                            <h3><sup style="font-size: 18px">$</sup> {{ totalVentaMensual }}</h3>

                            <p>Total Ventas del Mes</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ cantVentaMensual }}</h3>

                            <p>Numero de Ventas del Mes</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-violet">
                        <div class="inner">
                            <h3>{{ cantidadArticulosMensual }}</h3>

                            <p>Articulos Vendidos del Mes</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ ArtMasVendido }}</h3>

                            <p>Articulo Mas Vendido</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <div>
                <strong>
                    <p class="text-center">Estadísticas Anuales</p>
                </strong>
            </div>
            <!-- Tablero -->
            <div class="row chart-container mb-5 w-75 h-100"> 
                <!-- <div class="col-12"> -->
                    <Bar :options="chartOptions" :data="chartData"></Bar>
                <!-- </div> -->
            </div>
            <div class="row chart-container w-100 h-100">
                <Pie :options="chartOptionsPie" :data="dataPie"></Pie>
            </div>
        </div>
    </section>
</template>
<script>

import { Bar, Pie } from 'vue-chartjs'
import { Chart as ChartJS, Title, ArcElement, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import axios from 'axios';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement)

export default {

    components: { Bar, Pie },
    props: {
        chartId: {
            type: String,
            default: 'bar-chart',
        }
    },
    data() {
        return {
            etiquetas: [],
            valores: [],
            cantVentaMensual: 0,
            totalVentaMensual: 0,
            cantidadArticulosMensual:0,
            ArtMasVendido: '',
            chartData: {
                labels: []
                , datasets: [
                    {
                        label: '',
                        data: []
                    }
                ]
            },
            dataPie : {
                labels: [],
                datasets: [
                    {
                    data: []
                    }
                ]
            },
            chartOptions: {
                responsive: true,
                //maintainAspectRatio: false,
            },
            chartOptionsPie: {
                responsive: true,
                maintainAspectRatio: false,
            },
        }
    },
    methods: {
    
        async reporteVentas() {
            let url = 'api/reporteventas';
            let me = this;
            try {
                const totales = await axios.get(url);
                this.cantVentaMensual = totales.data.cantVentaMensual;
                this.totalVentaMensual = totales.data.totalVentaMensual;
                this.cantidadArticulosMensual = totales.data.cantidadArticulosMensual;
                this.ArtMasVendido = Object.keys(totales.data.cantidadArticulos)[0];
                this.chartData = {
                    labels: totales.data.toCurrentMonths,
                    datasets: [
                        {
                            label: 'Ventas',
                            backgroundColor: '#F8C471',
                            data: totales.data.amount,
                            grouped: false
                        },
                        {
                            label: 'Cantidad Facturas',
                            backgroundColor: '#17a2b8',
                            data: totales.data.countFacturas,
                            grouped: false
                        },
                        {
                            label: 'Articulos Vendidos',
                            backgroundColor: '#9B59B6',
                            data: totales.data.countArticulos,
                            grouped: false
                        },
                    ]
                };
                this.dataPie = {
                    labels: Object.keys(totales.data.cantidadArticulos),
                    datasets: [
                        {
                        backgroundColor: ['#41B883', '#E46651', '#00D8FF', '#DD1B16','#F4D03F','#F39C12','#BB8FCE'],
                        data: Object.values(totales.data.cantidadArticulos)
                        }
                    ]
                };
            } catch (error) {

            }
        }
    },
    mounted() {
        //this.grafico();
        this.reporteVentas();
    }
}
</script>
<style>
.chart-container {
    position: relative;
    margin: auto;
    height: 80vh;
    width: 80vw;
}
.bg-violet {
    background-color: #BB8FCE !important;
    color: #fff;
}
.bg-orange {
    background-color: #F39C12 !important;
    color: #fff !important;
}
.bg-orange > a {
    color: #fff !important;
}
</style>
