Highcharts.chart('aktivitasChart', {

    title: {
        text: 'Aktivitas CIPTA SEJATI',
        align: 'left',
        style: {
            fontSize:'18px'
        }
    },

    subtitle: {
        text: 'Data aktivitas yang diselenggarakan oleh CIPTA SEJATI',
        align: 'left',
        style: {
            fontSize:'12px'
        }
    },

    yAxis: {
        title: {
            text: 'Jumlah Aktivitas'
        }
    },

    xAxis: {
        accessibility: {
            rangeDescription: 'Range: 2010 to 2020'
        }
    },

    legend: {
        title: {
          text: 'Aktivitas Deskripsi:',
          style: {
              fontSize:'14px'
          }
        },
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        itemStyle: {
            "fontSize": "14px",
        }
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 2010
        }
    },

    series: [{
        name: 'Pembukaan Pusat Daya',
        data: [43934, 48656, 65165, 81827, 112143, 142383,
            171533, 165174, 155157, 161454, 154610]
    }, {
        name: 'Ujian Kenaikan Tingkat',
        data: [24916, 37941, 29742, 29851, 32490, 30282,
            38121, 36885, 33726, 34243, 31050]
    }, {
        name: 'Latihan Gabungan',
        data: [11744, 30000, 16005, 19771, 20185, 24377,
            32147, 30912, 29243, 29213, 25663]
    }, {
        name: 'Pendidikan dan Latihan',
        data: [null, null, null, null, null, null, null,
            null, 11164, 11218, 10077]
    },],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});
