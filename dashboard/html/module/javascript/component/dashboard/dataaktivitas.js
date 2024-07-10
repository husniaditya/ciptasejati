$(document).ready(function() {
    // Function to generate month names for the past 12 months up to the current month
    function getPast12Months() {
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const result = [];
        const currentMonth = new Date().getMonth();
        const currentYear = new Date().getFullYear();

        for (let i = 0; i < 12; i++) {
            const monthIndex = (currentMonth - i + 12) % 12;
            const year = currentMonth - i < 0 ? currentYear - 1 : currentYear;
            result.unshift(`${monthNames[monthIndex]} ${year}`);
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
                    categories: getPast12Months(),
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