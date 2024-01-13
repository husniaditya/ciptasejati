$(function () {
    // Create the chart
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            align: 'center',
            text: 'Data Keanggotaan'
        },
        subtitle: {
            align: 'center',
            text: 'Klik untuk melihat detail data'
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
                text: 'Jumlah Keanggotaan'
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
                    format: '{point.y:.1f}%'
                }
            }
        },
    
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        },
        series: [{
            name: 'Browsers',
            colorByPoint: true,
            data: [{
                name: 'Microsoft Internet Explorer',
                y: 56.33,
                drilldown: 'Microsoft Internet Explorer'
            }, {
                name: 'Chrome',
                y: 24.03,
                drilldown: 'Chrome'
            }]
        }],
        drilldown: {
            series: [{
                name: 'Microsoft Internet Explorer',
                id: 'Microsoft Internet Explorer',
                colorByPoint: true,
                data: [
                    {
                    name: 'M1',
                    y: 22,
                    drilldown: 'M1'
                    },
                    {
                    name: 'M2',
                    y: 30,
                    drilldown: 'M2'
                    },
                ]
            }, {
                name: 'Chrome',
                id: 'Chrome',
                colorByPoint: true,
                data: [
                    [
                        'v40.0',5
                    ]
                ]
            }, {
                id: 'M1',
                data: [
                    [
                        'v8.0',17.2
                    ],
                    [
                        '1.0',25.2
                    ]
                ]
            }]
        }
    });
});