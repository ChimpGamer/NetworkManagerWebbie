<div>
    <div x-init="loadServerStatsChart" id="container"></div>
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
    window.loadServerStatsChart = () => {
        Highcharts.stockChart('container', {
            chart: {
                type: 'areaspline',
                backgroundColor: 'transparent',
                zoomType: 'x',
            },

            rangeSelector: {
                buttons: [{
                    type: 'hour',
                    count: 2,
                    text: '2h'
                }, {
                    type: 'hour',
                    count: 12,
                    text: '12h'
                }, {
                    type: 'day',
                    count: 1,
                    text: '1d'
                }, {
                    type: 'day',
                    count: 3,
                    text: '3d'
                }, {
                    type: 'week',
                    count: 1,
                    text: '1w'
                }, {
                    type: 'all',
                    text: 'All'
                }],
                selected: 0
            },

            title: {
                text: '',
            },

            credits: {
                enabled: false
            },

            boost: {
                useGPUTranslations: true,
                usePreAllocated: true
            },

            yAxis: {
                title: {
                    text: ''
                },
                gridLineDashStyle: 'dot',
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
                }
            },
            time: {
                useUTC: false
            },
            plotOptions: {
                series: {
                    marker: {
                        enabled: false
                    }
                },
            },
            legend: {
                enabled: true,
                itemStyle: {
                    color: '#FFFFFF'
                }
            },
            tooltip: {
                backgroundColor: '#FFFFFF',
                borderColor: '#F7F7F7',
                borderRadius: 2,
                borderWidth: 1,
                valueDecimals: 0,
                split: false,
                shared: true
            },
            series: data
        });
    }
</script>
@endscript
