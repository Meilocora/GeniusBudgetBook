import './../Chart.js/chart.js';
// const { Chart } = await import('./../Chart.js/chart.js');
import './../Chart.js/chartjs-plugin-datalabels.js';
// Chart.register(ChartDataLabels);



export default class ChartGenerator {
    generatePieChart(selector, labelsArray, dataArray, symbol, legendDisplay, backgoundColors) {
        var ctx = document.getElementById(selector);
        let myChart = new Chart(ctx, {
        type: 'pie',
        plugins: [ChartDataLabels],
        data: {
            labels: labelsArray,
            datasets: [{
                data: dataArray,
                backgroundColor: backgoundColors,
                borderColor: 'rgb(0,0,0)',
                borderWidth: 1,
            }],
            
        },
        options: {
            layout: {
                padding: 30
            },
            responsive: false,
            plugins: {
                datalabels: {
                    display: 'auto',
                    color: 'white',
                    anchor: 'end',
                    align: 'end',
                    offset: -30,
                    backgroundColor: '#495057',
                    borderWidth: '1',
                    borderColor: 'black',
                    borderRadius: 20,
                    padding: 6,
                    font: {
                        size: 16
                    },
                    formatter: function(value) {
                        return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".") + ` ${symbol}`;
                        }
                },
                legend: {
                    display: legendDisplay,
                    position: `right`,
                    labels: {
                        color: 'black',
                        font: {
                            size: 16
                        }
                    }
                    
                },
            }
            }
    });
    }

    generateLineChart(selector, titleText, lineColors, wdGoalData, xAxisLabels, lineLabels, data1, data2, data3, data4, data5, data6, data7, data8, data9, data10) {
        var ctx = document.getElementById(selector);
        let myChart = new Chart(ctx, {
            type: 'line',
            plugins: [ChartDataLabels],
            data: {
                labels: xAxisLabels,
                datasets: [
                    {
                        data: data1,
                        label: lineLabels[0],
                        fill: (lineColors.length === 2) ? false : 'origin',
                    },
                    {
                        data: data2,
                        label: lineLabels[1],
                        fill: (lineColors.length === 2) ? false : '-1',
                    },
                    {
                        data: data3,
                        label: lineLabels[2],
                        fill: '-1',
                    },
                    {
                        data: data4,
                        label: lineLabels[3],
                        fill: '-1',
                    },
                    {
                        data: data5,
                        label: lineLabels[4],
                        fill: '-1',
                    },
                    {
                        data: data6,
                        label: lineLabels[5],
                        fill: '-1',
                    },
                    {
                        data: data7,
                        label: lineLabels[6],
                        fill: '-1',
                    },
                    {
                        data: data8,
                        label: lineLabels[7],
                        fill: '-1',
                    },
                    {
                        data: data9,
                        label: lineLabels[8],
                        fill: '-1',
                    },
                    {
                        data: data10,
                        label: lineLabels[9],
                        fill: '-1',
                    },
                    {
                        data: wdGoalData,
                        label: 'Total wealth goal',
                        fill: '-1',
                        stack: "goal",
                        borderColor: 'rgb(255,215,0)',
                        backgroundColor: 'rgba(190,162,10)',
                    }
                    ],
            },
            options: {
                backgroundColor: lineColors,
                elements: {
                    line: {
                        borderWidth: (lineColors.length === 2) ? 4 : 2,
                        borderColor: (lineColors.length === 2) ? lineColors : 'black',
                        tension: 0.5,
                    },
                    point: {
                        radius: 2,
                        hitRadius: 5,
                        borderWidth: 1,
                        borderColor: 'black',
                        backgroundColor: '#4efd54',
                        hoverRadius: 10,
                        hoverBorderWidth: 4,
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        font: {
                            size: 24,
                            color: 'black',
                        },
                        text: titleText,
                    },
                    datalabels: {
                        display: false,
                    },
                    legend: {
                        labels: {
                            filter: (legendItem, data) => (typeof legendItem.text !== 'undefined'),
                            lineWidth: 1,
                            borderColor: 'black',
                            color: 'black',
                            font: {
                                size: 16
                            },
                        },
                        position: 'bottom',
                        reverse: false,
                    }
                },
                responsive: false,
                scales: {
                    y: {    
                            max: wdGoalData[0]*1.2,
                            grid: {
                                lineWidth: 1.5,
                            },
                            ticks: {
                                font: {
                                    size: 16,
                                },
                                color: 'black',
                                callback: function(value) {
                                    return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".") + ` €`;
                                    },
                            },
                            stacked: (lineColors.length === 2) ? false : true,
                        },
                    x: {
                        grid: {
                            clineWidth: 1.5,
                        },
                        ticks: {
                            font: {
                                size: 16,
                            },
                            color: 'black',
                        },
                    }
                }
            }
        });
    }
}