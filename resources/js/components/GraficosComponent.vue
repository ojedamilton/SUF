<template>
    <section class="content col-lg-12">
        <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                <h3>{{cantVentaSemanal}}</h3>

                <p>Cantidad Facturas Diarias</p>
                </div>
                <div class="icon">
                <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                <h3><sup style="font-size: 18px">$</sup> {{totalVentaSemanal}}</h3>

                <p>Total Facturacion Diaria</p>
                </div>
                <div class="icon">
                <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                <h3>{{cantClientes}}</h3>

                <p>Clientes</p>
                </div>
                <div class="icon">
                <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-secondary">
                <div class="inner">
                <h3>{{cantUsuarios}}</h3>

                <p>Usuarios</p>
                </div>
                <div class="icon">
                <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
            </div>
            <!-- ./col -->
        </div>
         <Bar :options="chartOptions" :data="chartData"></Bar>
        </div>
        
    </section>
        
</template>
<script>

import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import axios from 'axios';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {

    components:{Bar},
    props:{
        chartId: {
            type:String,
            default:'bar-chart',
        }
    },
    data() {
        return {
            etiquetas:[],
            valores:[],
            cantVentaSemanal:0,
            totalVentaSemanal:0,
            cantClientes:0,
            cantUsuarios:0,
            chartData:{
                    labels: []
                    ,datasets:[
                     {label:'',
                        data: []
                    }
                ]},
            chartOptions: {
                responsive: true,
            },
        }
    },
    methods: {
        grafico(){
            /* let url='/articulos';
            const totales = await axios.get(url);
            console.log(totales.data.articulos.data[0]); */
            this.chartData = {
                labels: ['January','February','March','April','May'],
                datasets:[
                    {label:'Articulos', backgroundColor: '#f87979',
                        data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]}
                ]
            }; 
        },
        async reporteVentas(){
            let url='/reporteventas';
            let me=this;
            try {
                const totales = await axios.get(url);
                this.cantVentaSemanal=totales.data.cantVentaSemanal;
                this.totalVentaSemanal=totales.data.totalVentaSemanal;
            }catch(error){

            }   
        }
    },
    mounted(){
      this.grafico();
      this.reporteVentas();
    }
}
</script>