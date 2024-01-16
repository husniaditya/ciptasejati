
var TingkatanChart; // Declare chart as a global variable

// Use AJAX to fetch initial data from the server
$.ajax({
    url: './module/ajax/dashboard/tingkatan/aj_dashtingkatan.php',
    method: 'POST',
    dataType: 'json',
    success: function (initialData) {
        // console.log('Got initial data:', initialData);
        initializeTingkatanChart(initialData);
    },
    error: function (error) {
        console.log('Error fetching data:', error);
    }
});

function initializeTingkatanChart(initialData) {
    TingkatanChart = Highcharts.chart('tingkatanChart', {
        chart: {
            type: 'column',
            events: {
                drilldown: function (e) {
                    if (!e.seriesOptions) {
                        var chart = this;
                        $.ajax({
                            url: './module/ajax/dashboard/tingkatan/aj_drilldowntingkatan.php',
                            method: 'POST',
                            data: { drilldownId: e.point.drilldown },
                            dataType: 'json',
                            success: function (drilldownData) {
                                // console.log('Got drilldown data:', drilldownData);
                                chart.addSeriesAsDrilldown(e.point, {
                                    colorByPoint: true,
                                    name: e.point.name,
                                    id: e.point.drilldown,
                                    data: drilldownData.map(function (drilldownEntry) {
                                        // console.log('drilldownEntry:', drilldownEntry);
                                        return {
                                            colorByPoint: true,
                                            name: drilldownEntry.name,
                                            y: drilldownEntry.y,
                                            drilldown: drilldownEntry.drilldown,
                                        };
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
            text: '<span style="font-size:16px">Data Tingkatan</span>'
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
            type: 'category',
            labels: {
                style: {
                    fontSize:'12px'
                }
            }
        },
        yAxis: {
            title: {
                text: '<span style="font-size:10px">Total Keanggotaan</span>'
            },
            labels: {
                style: {
                    fontSize:'10px'
                }
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
            headerFormat: '<span style="font-size:12px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color};font-size:12px">{point.name} : <b>{point.y}</b> Anggota</span><br/>'
        },
        series: [{
            name: 'Tingkatan',
            colorByPoint: true,
            data: initialData.map(function (entry) {
                return {
                    name: entry.tingkatan,
                    y: entry.tingkatan_anggota,
                    drilldown: entry.drilldown
                };
            })
        }],
        drilldown: {
            breadcrumbs: {
                position: {
                    align: 'left'
                },
                buttonTheme: {
                style: {
                    fontSize: "12px"
                    }
                }
            },
            series: []
        }
        // Add any other options specific to your implementation here
    });
}