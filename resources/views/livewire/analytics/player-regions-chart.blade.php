<div>
    <div class="row">
        <div class="col-4">
            <div x-init="loadCountriesChart" id="countries"></div>
        </div>
        <div class="col-8">
            <div x-init="await loadPlayerRegionsChart" id="map"></div>
        </div>
    </div>
</div>

@assets
<script src="https://code.highcharts.com/maps/modules/map.js"></script>
@endassets

@script
<script>
    const countryNames = @js($this->countryNames);
    const countryPlayers = @js($this->countryPlayers);
    Highcharts.setOptions({
        chart: {
            style: {
                fontFamily: 'Roboto',
            },
            zoomType: false
        }
    });
    window.loadCountriesChart = () => {
        Highcharts.chart('countries', {
            chart: {
                type: 'bar',
                backgroundColor: 'transparent',
                animation: false
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: countryNames,
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: '',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                backgroundColor: '#FFFFFF',
                borderColor: '#F7F7F7',
                borderRadius: 2,
                borderWidth: 1,
                padding: 15,
                animation: true,
                valueDecimals: false,
                formatter: function () {
                    return '<b>' + this.y + '</b> Players from <b>' + this.key + '</b>';
                }
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true,
                        color: '#FFFFFF'
                    }
                },
                column: {
                    stacking: 'percent'
                }
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            series: [{
                name: this.point,
                data: countryPlayers,
                color: '#2196F3'
            }]
        });
    }

    window.loadPlayerRegionsChart = async () => {
        await fetch('https://code.highcharts.com/mapdata/custom/world.geo.json')
            .then(response => response.json())
            .then(mapData => {
                const data = @js($this->mapData);
                Highcharts.mapChart('map', {
                    chart: {
                        map: mapData,
                        backgroundColor: 'transparent',
                        animation: false
                    },
                    title: {
                        text: '',
                        align: 'left'
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
                        borderWidth: 1
                    },
                    mapNavigation: {
                        enabled: false,
                        buttonOptions: {
                            verticalAlign: 'bottom'
                        }
                    },
                    series: [{
                        name: 'Players',
                        joinBy: ['iso-a2', 'code'],
                        data: data,
                        color: '#2196F3',
                        tooltip: {
                            pointFormat: '<b>{point.name}</b>: {point.z} players',
                            headerFormat: ''
                        }
                    }]
                });
            })
    };
</script>
@endscript
