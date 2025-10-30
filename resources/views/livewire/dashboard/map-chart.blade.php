<div>
    <div x-init="loadMapChart" id="map"></div>
</div>

@assets
<script src="https://code.highcharts.com/maps/modules/map.js"></script>
@endassets

@script
<script>
    Highcharts.setOptions({
        chart: {
            style: {
                fontFamily: 'Roboto Th'
            }
        }
    });
    window.loadMapChart = async () => {
        await fetch('https://code.highcharts.com/mapdata/custom/world.geo.json')
            .then(response => response.json())
            .then(mapData => {
                const data = @js($this->data);
                Highcharts.mapChart('map', {
                    chart: {
                        map: mapData,
                        backgroundColor: 'transparent'
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
                        enabled: true
                    },

                    responsive: {
                        rules: [{
                            condition: {
                                callback() {
                                    return document.body.offsetWidth < 753;
                                }
                            },
                            chartOptions: {
                                colorAxis: {
                                    layout: 'horizontal'
                                },
                                legend: {
                                    align: 'center'
                                },
                                mapNavigation: {
                                    buttonOptions: {
                                        verticalAlign: 'bottom'
                                    }
                                }
                            }
                        }]
                    },

                    series: [{
                        name: 'Newest Players',
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
    }
</script>
@endscript
