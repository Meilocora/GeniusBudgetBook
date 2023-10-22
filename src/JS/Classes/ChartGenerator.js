import './../Chart.js/chart.js';
// const { Chart } = await import('./../Chart.js/chart.js');
import './../Chart.js/chartjs-plugin-datalabels.js';
// Chart.register(ChartDataLabels);



export default class ChartGenerator {
    generateDoughnutChart(selector, labelsArray, dataArray1, dataArray2, backgoundColors, legendPosition, titleText, titleDisplay) {
        var ctx = document.getElementById(selector);
        let myChart = new Chart(ctx, {
        type: 'doughnut',
        plugins: [ChartDataLabels],
        data: {
            labels: labelsArray,
            datasets: [
                {
                    data: dataArray1,
                    backgroundColor: backgoundColors,
                    borderColor: 'rgb(0,0,0)',
                    borderWidth: 1,
                    hoverOffset: 20,
                    datalabels: {
                        anchor: 'end',
                        align: 'center',
                        formatter: function(value) {
                                return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".") + ` €`;
                        }
                    }
                },
                {
                    data: dataArray2,
                    backgroundColor: backgoundColors,
                    borderColor: 'rgb(0,0,0)',
                    borderWidth: 1,
                    hoverOffset: -20,
                    datalabels: {
                        anchor: 'start',
                        align: 'end',
                        formatter: function(value) {
                                return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".") + ` %`;
                        }
                    }
                }
            ],
            
        },
        options: {
            hoverBackgroundColor: 'gold',
            hoverBorderWidth: 2,
            cutout: 40,
            layout: {
                padding: 20
            },
            responsive: false,
            plugins: {
                datalabels: {
                    display: 'auto',
                    color: 'white',
                    backgroundColor: '#495057',
                    borderWidth: '1',
                    borderColor: 'black',
                    borderRadius: 20,
                    padding: 8,
                    font: {
                        size: 14
                    },
                },
                legend: {
                    position: legendPosition,
                    labels: {
                        color: 'black',
                        font: {
                            size: 16
                        },
                    }
                    
                },
                title: {
                    text: titleText,
                    display: titleDisplay,
                    font: {
                        size: 24,
                    }
                },
            }
            }
    });
    }

    generateLineChart(selector, titleText, lineColors, GoalData, xAxisLabels, lineLabels, data1, data2, data3, data4, data5, data6, data7, data8, data9, data10) {
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
                        data: GoalData,
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
                            suggestedMax: GoalData[0]*1.05,
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
                            display: false,
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