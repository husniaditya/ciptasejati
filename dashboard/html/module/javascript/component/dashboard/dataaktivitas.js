$(document).ready(function() {
    // Function to generate the past 5 years
    function getPast5Years() {
        const result = [];
        const currentYear = new Date().getFullYear();

        for (let i = 0; i < 7; i++) {
            result.unshift(currentYear - i);
        }

        return result;
    }

    // Fetch data for each series via AJAX
    $.ajax({
        url: './module/ajax/dashboard/aktivitas/aj_getaktivitas.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // Initialize the chart
            Highcharts.chart('aktivitasChart', {
                title: {
                    text: 'Aktivitas CIPTA SEJATI',
                    align: 'left',
                    style: {
                        fontSize: '18px'
                    }
                },
                subtitle: {
                    text: 'Data aktivitas yang diselenggarakan oleh CIPTA SEJATI',
                    align: 'left',
                    style: {
                        fontSize: '12px'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Aktivitas',
                        style: {
                            fontSize: '11px'
                        }
                    },
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                xAxis: {
                    categories: getPast5Years(),
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                legend: {
                    title: {
                        text: 'Aktivitas Deskripsi:',
                        style: {
                            fontSize: '14px'
                        }
                    },
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    itemStyle: {
                        fontSize: '14px'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size: 14px">{point.key}</span><br/>',
                    style: {
                        fontSize: '12px'
                    }
                },
                plotOptions: {
                    series: {
                        label: {
                            connectorAllowed: false
                        }
                    }
                },
                series: data.series,
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
        },
        error: function(error) {
            console.error('Error fetching data:', error);
        }
    });
});
