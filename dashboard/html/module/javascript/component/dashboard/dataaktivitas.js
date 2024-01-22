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
            text: 'Jumlah Aktivitas',
            style: {
                fontSize:'11px'
            }
        },
        labels: {
            style: {
                fontSize:'11px'
            }
        }
    },

    xAxis: {
        accessibility: {
            rangeDescription: 'Range: 2010 to 2020'
        },
        labels: {
            style: {
                fontSize:'12px'
            }
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

    tooltip: {
		headerFormat: '<span style="font-size: 14px">{point.key}</span><br/>',
        style: {
            fontSize:'12px'
        }
    },
    plotOptions: {
        series: {
            label: {
                connectorAllowed: false,
            },
            pointStart: 2014,
        }
    },

    series: [
        {
        name: 'Pembukaan Pusat Daya',
        data: [Math.floor(Math.random() * (200 - 100 + 1)) + 100, Math.floor(Math.random() * (200 - 100 + 1)) + 100, Math.floor(Math.random() * (200 - 100 + 1)) + 100, Math.floor(Math.random() * (200 - 100 + 1)) + 100, Math.floor(Math.random() * (200 - 100 + 1)) + 100, Math.floor(Math.random() * (200 - 100 + 1)) + 100, Math.floor(Math.random() * (200 - 100 + 1)) + 100, Math.floor(Math.random() * (200 - 100 + 1)) + 100, Math.floor(Math.random() * (200 - 100 + 1)) + 100, Math.floor(Math.random() * (200 - 100 + 1)) + 100, 2]
    }, {
        name: 'Ujian Kenaikan Tingkat',
        data: [Math.floor(Math.random() * (100 - 50 + 1)) + 50, Math.floor(Math.random() * (100 - 50 + 1)) + 50, Math.floor(Math.random() * (100 - 50 + 1)) + 50, Math.floor(Math.random() * (100 - 50 + 1)) + 50, Math.floor(Math.random() * (100 - 50 + 1)) + 50, Math.floor(Math.random() * (100 - 50 + 1)) + 50, Math.floor(Math.random() * (100 - 50 + 1)) + 50, Math.floor(Math.random() * (100 - 50 + 1)) + 50, Math.floor(Math.random() * (100 - 50 + 1)) + 50, Math.floor(Math.random() * (100 - 50 + 1)) + 50, 5]
    }, {
        name: 'Latihan Gabungan',
        data: [Math.floor(Math.random() * (75 - 25 + 1)) + 25, Math.floor(Math.random() * (75 - 25 + 1)) + 25, Math.floor(Math.random() * (75 - 25 + 1)) + 25, Math.floor(Math.random() * (75 - 25 + 1)) + 25, Math.floor(Math.random() * (75 - 25 + 1)) + 25, Math.floor(Math.random() * (75 - 25 + 1)) + 25, Math.floor(Math.random() * (75 - 25 + 1)) + 25, Math.floor(Math.random() * (75 - 25 + 1)) + 25, Math.floor(Math.random() * (75 - 25 + 1)) + 25, Math.floor(Math.random() * (75 - 25 + 1)) + 25, 2]
    }, {
        name: 'Pendidikan dan Latihan',
        data: [Math.floor(Math.random() * (200 - 150 + 1)) + 150, Math.floor(Math.random() * (200 - 150 + 1)) + 150, Math.floor(Math.random() * (200 - 150 + 1)) + 150, Math.floor(Math.random() * (200 - 150 + 1)) + 150, Math.floor(Math.random() * (200 - 150 + 1)) + 150, Math.floor(Math.random() * (200 - 150 + 1)) + 150, Math.floor(Math.random() * (200 - 150 + 1)) + 150, Math.floor(Math.random() * (200 - 150 + 1)) + 150, Math.floor(Math.random() * (200 - 150 + 1)) + 150, Math.floor(Math.random() * (200 - 150 + 1)) + 150, 7]
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
