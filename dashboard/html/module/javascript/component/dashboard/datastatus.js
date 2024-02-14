// Define statusChart in the global scope
var statusChart;

// Define a function to fetch the data via AJAX
function fetchDataAndUpdateChart() {
    $.ajax({
        url: './module/ajax/dashboard/anggota/aj_statusanggota.php',
        method: 'POST',
        success: function(data) {
            console.log('Got data:', data);
            // Update xAxis categories
            statusChart.xAxis[0].setCategories(data.xAxisCategories);

            // Update the chart series data
            statusChart.series[0].setData(data.seriesDataAktif.data);
            statusChart.series[1].setData(data.seriesDataTidakAktif.data);
        },
        error: function(xhr, status, error) {
            // Handle errors if needed
            console.error("Error fetching data:", error);
        }
    });
}

$(document).ready(function() {
    // Create the initial chart
    statusChart = Highcharts.chart('statusChart', {
        chart: {
            type: 'column'
        },
        title: {
            text: '<span style="font-size:16px">Data Status Keanggotaan</span>',
            align: 'left'
        },
        subtitle: {
            text: '<span style="font-size:12px">Total Data Keanggotaan Aktif dan Tidak Aktif</span>',
            align: 'left'
        },
        xAxis: {
            categories: [], // Empty initially, will be updated via AJAX
            crosshair: true,
            accessibility: {
                description: 'Countries'
            },
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: '<span style="font-size:10px">Total Keanggotaan</span>'
            },
            labels: {
                style: {
                    fontSize: '11px'
                }
            }
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
            },
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '<span style="font-size:10px">{point.y}</span>'
                },
            }
        },
        tooltip: {
            formatter: function() {
                return '<span style="font-size:12px">' + this.x + '</span><br/>' +
                    '<span style="font-size:12px;color:' + this.series.color + '">' + this.series.name + ' : <b>' + this.y + '</b> Anggota</span><br/>';
            }
        },
        series: [{
                name: 'Aktif',
                data: [] // Placeholder for initial data, will be updated via AJAX
            },
            {
                name: 'Tidak Aktif',
                data: [] // Placeholder for initial data, will be updated via AJAX
            }
        ]
    });

    // Fetch data and update chart initially
    fetchDataAndUpdateChart();
});
