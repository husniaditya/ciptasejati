var chart; // Declare chart as a global variable

// Use AJAX to fetch initial data from the server
$.ajax({
    url: './module/ajax/dashboard/anggota/aj_dashanggota.php',
    method: 'POST',
    dataType: 'json',
    success: function (initialData) {
        initializeChart(initialData);
    },
    error: function (error) {
        console.log('Error fetching data:', error);
    }
});

function initializeChart(initialData) {
    chart = Highcharts.chart('container', {
        chart: {
            type: 'column',
            events: {
                drilldown: function (e) {
                    if (!e.seriesOptions) {
                        var chart = this;
                        $.ajax({
                            url: './module/ajax/dashboard/anggota/aj_drilldownanggota.php',
                            method: 'POST',
                            data: { drilldownId: e.point.drilldown },
                            dataType: 'json',
                            success: function (drilldownData) {
                                chart.addSeriesAsDrilldown(e.point, {
                                    colorByPoint: true,
                                    name: 'Cabang',
                                    id: e.point.drilldown,
                                    data: drilldownData.map(function (drilldownEntry) {
                                        return [drilldownEntry.cabang, drilldownEntry.cabang_anggota];
                                    })
                                });
                            },
                            error: function (error) {
                                console.log('Error fetching drilldown data:', error);
                            }
                        });
                    }
                },
                drillup: function () {
                    // No need to remove the series manually
                }
            }
        },
        title: {
            align: 'center',
            text: '<span style="font-size:16px">Data Keanggotaan</span>'
        },
        subtitle: {
            align: 'center',
            text: '<span style="font-size:12px">Klik kolom grafik untuk melihat detail data</span>'
        },
        accessibility: {
            announceNewData: {
                enabled: true
            }
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: '<span style="font-size:10px">Total Keanggotaan</span>'
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '<span style="font-size:10px">{point.y}</span>'
                },
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> Anggota<br/>'
        },
        series: [{
            name: 'Daerah',
            colorByPoint: true,
            data: initialData.map(function (entry) {
                return {
                    name: entry.daerah,
                    y: entry.daerah_anggota,
                    drilldown: entry.drilldown
                };
            })
        }],
        drilldown: {
            breadcrumbs: {
                position: {
                    align: 'left'
                }
            },
            activeDataLabelStyle: {
                color: '#000000'
            },
            series: []
        }
        // Add any other options specific to your implementation here
    });
}