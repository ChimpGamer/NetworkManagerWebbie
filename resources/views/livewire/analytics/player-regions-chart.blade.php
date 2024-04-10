<div>
    <div class="row">
        <div class="col-4">
            <div x-init="loadCountriesChart" id="countries" style="width: 100%;" class="highcharts-dark"></div>
        </div>
        <div class="col-8">
            <div x-init="loadPlayerRegionsChart" id="map" style="width: 100%;"></div>
        </div>
    </div>
</div>

@assets
<script src="https://code.highcharts.com/maps/modules/map.js"></script>
<script src="https://code.highcharts.com/mapdata/custom/world.js"></script>
@endassets

@script
<script>
    const data = @js($this->mapData);
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
                    return '<b>' + this.y + '</b> Players from <b>' + this.x + '</b>';
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

    window.loadPlayerRegionsChart = () => {
        Highcharts.mapChart('map', {
            chart: {
                map: 'custom/world',
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
    };
</script>
@endscript
