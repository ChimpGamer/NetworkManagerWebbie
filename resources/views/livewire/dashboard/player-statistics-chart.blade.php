<div>
    <div x-init="loadPlayerStatisticsChart" id="container"></div>
</div>

@script
<script>
    const newPlayersData = @js($this->newPlayers);
    const sessionData = @js($this->sessions);
    const playerPeakData = @js($this->playerPeak);
    Highcharts.setOptions({
        chart: {
            style: {
                fontFamily: 'Roboto Th'
            }
        }
    });
    window.loadPlayerStatisticsChart = () => {
        Highcharts.chart('container', {
            chart: {
                type: 'areaspline',
                backgroundColor: 'transparent',
                zoomType: 'x'
            },

            title: {
                text: '',
                style: {
                    fontSize: '18px',
                    color: '#212121'
                },
                align: 'left'
            },
            series: [{
                name: 'New Players',
                color: '#2196F3',
                data: newPlayersData
            }, {
                name: 'Total Sessions',
                dashStyle: 'ShortDash',
                color: '#2196F3',
                data: sessionData
            }, {
                name: 'Player Peak',
                dashStyle: 'Dot',
                color: '#2196F3',
                data: playerPeakData
            }],

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
                        const date = new Date(event.min);
                        const datevalues = date.getFullYear()
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
                borderColor: '#FFFFFF',
                borderRadius: 2,
                borderWidth: 1,
                shared: true
            },
            plotOptions: {
                series: {
                    pointStart: 2010,
                    fillOpacity: 0.2,
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
    }
</script>
@endscript
