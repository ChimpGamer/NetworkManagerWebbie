<div>
    <div x-init="loadOnlinePlayersChart" id="container"></div>
</div>

@script
<script>
    const data = @js($this->data);
    Highcharts.setOptions({
        chart: {
            style: {
                fontFamily: 'Roboto'
            },
            zoomType: false
        }
    });
    window.loadOnlinePlayersChart = () => {
        Highcharts.chart('container', {
            chart: {
                type: 'areaspline',
                backgroundColor: 'transparent',
                height: 200,
                zoomType: 'x'
            },
            title: {
                text: ''
            },
            series: [{
                name: 'Online Players',
                color: '#2196F3',
                data: data
            }],
            scrollbar: {
                enabled: false
            },
            navigator: {
                enabled: false
            },
            yAxis: {
                title: {
                    text: ''
                },
                gridLineWidth: 0,
                minorGridLineWidth: 0,
                labels: {
                    style: {
                        color: '#B6B6B6',
                        fontSize: '12px'
                    }
                }
            },
            xAxis: {
                gridLineWidth: 0,
                minorGridLineWidth: 0,
                labels: {
                    style: {
                        color: '#B6B6B6',
                        fontSize: '12px'
                    }
                },
                type: 'datetime',
                events: {
                    afterSetExtremes: function (event) {
                        //console.log(event);
                        var date = new Date(event.min);
                        var datevalues = date.getFullYear()
                            + '-' + date.getMonth() + 1
                            + '-' + date.getDate()
                            + ' ' + date.getUTCHours()
                            + ':' + date.getMinutes()
                            + ':' + date.getSeconds();
                        $("#timestamp").text(datevalues);
                    }
                }
            },
            time: {
                useUTC: false
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            tooltip: {
                backgroundColor: '#FFFFFF',
                borderColor: '#F7F7F7',
                borderRadius: 2,
                borderWidth: 1,
                xDateFormat: '%A, %e %b, %H:%M',
                style: {
                    fontSize: '12px'
                }
            },
            plotOptions: {
                series: {
                    pointStart: 2010,
                    fillOpacity: 0.1,
                    marker: {
                        enabled: false
                    }
                }
            },
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                }]
            }
        });
    };
</script>
@endscript
