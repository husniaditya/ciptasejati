(function (H) {
    H.seriesTypes.pie.prototype.animate = function (init) {
        const series = this,
            chart = series.chart,
            points = series.points,
            {
                animation
            } = series.options,
            {
                startAngleRad
            } = series;

        function fanAnimate(point, startAngleRad) {
            const graphic = point.graphic,
                args = point.shapeArgs;

            if (graphic && args) {

                graphic
                    // Set inital animation values
                    .attr({
                        start: startAngleRad,
                        end: startAngleRad,
                        opacity: 1
                    })
                    // Animate to the final position
                    .animate({
                        start: args.start,
                        end: args.end
                    }, {
                        duration: animation.duration / points.length
                    }, function () {
                        // On complete, start animating the next point
                        if (points[point.index + 1]) {
                            fanAnimate(points[point.index + 1], args.end);
                        }
                        // On the last point, fade in the data labels, then
                        // apply the inner size
                        if (point.index === series.points.length - 1) {
                            series.dataLabelsGroup.animate({
                                opacity: 1
                            },
                            void 0,
                            function () {
                                points.forEach(point => {
                                    point.opacity = 1;
                                });
                                series.update({
                                    enableMouseTracking: true
                                }, false);
                                chart.update({
                                    plotOptions: {
                                        pie: {
                                            innerSize: '40%',
                                            borderRadius: 8
                                        }
                                    }
                                });
                            });
                        }
                    });
            }
        }

        if (init) {
            // Hide points on init
            points.forEach(point => {
                point.opacity = 0;
            });
        } else {
            fanAnimate(points[0], startAngleRad);
        }
    };
}(Highcharts));

Highcharts.chart('pieAktivitasChart', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Komposisi Pendidikan dan Latihan CIPTA SEJATI',
        align: 'left',
        style: {
            fontSize:'18px'
        }
    },
    subtitle: {
        text: 'Total persentase aktivitas',
        align: 'left',
        style: {
            fontSize:'14px'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:12px">{series.name}</span><br>',
        pointFormat: '<span style="font-size:12px;color:{point.color};">{point.name}: <b>{point.percentage:.2f}%</b></span>'
    },
    accessibility: {
        point: {
            valueSuffix: '%'
        }
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            borderWidth: 2,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<span style="font-size:12px"><b>{point.name}</b><br>{point.percentage:.2f}%</span>',
                distance: 20,
            }
        }
    },
    series: [{
        // Disable mouse tracking on load, enable after custom animation
        name: 'Pendidikan dan Latihan',
        enableMouseTracking: false,
        animation: {
            duration: 2000
        },
        colorByPoint: true,
        data: [{
            name: 'Materi',
            y: 21.3
        }, {
            name: 'Latihan Fisik',
            y: 18.7
        }, {
            name: 'Latihan Spiritual',
            y: 20.2
        }, {
            name: 'Latihan Teknik',
            y: 14.2
        }]
    }]
});
