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
                    padding: 20,
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
                        data: (GoalData !== 0) ? GoalData : '',
                        label: (GoalData !== 0) ? 'Total wealth goal' : undefined,
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
                        tension: 0.15,
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
                            // min: (lineColors.length === 2 | data1[0] < 0) ? null : 0,
                            min: (lineColors.length === 2 | data1[1] <= 0) ? null : 0,
                            max: (data1[0] <= 0) ? 0 : null,
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

    generateLineChartCashflow(selector, titleText, xAxisLabels, lineLabels, data1, data2, data3) {
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
                        fill: 'origin',
                    },
                    {
                        data: data2,
                        label: lineLabels[1],
                        fill: '-1',
                    },
                    {
                        data: data3,
                        label: lineLabels[2],
                        fill: 'origin',
                    }
                        ],
            },
            options: {
                backgroundColor: ['rgb(0,0,255,0.5)', 'rgb(0,255,0,0.5)', 'rgb(255,0,0,0.5)'],
                elements: {
                    line: {
                        borderWidth: 2,
                        borderColor: 'black',
                        tension: 0.15,
                    },
                    point: {
                        radius: 2,
                        hitRadius: 5,
                        borderWidth: 1,
                        borderColor: 'black',
                        backgroundColor: 'white',
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
                        stacked: false,
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

    generateBarChart(selector, yScale, xLabels, titleText, revColors, expColors, categories, revdataregular, revdatafixed, expdataregular, expdatafixed) {
        var ctx = document.getElementById(selector);
        let noDataLabales = ['compoundInterestBarchart'];
        let myChart = new Chart(ctx, {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: {
                labels: categories,
                datasets: [
                    {
                        data: revdatafixed,
                        label: xLabels[0],
                        fill: '-1',
                        backgroundColor: revColors[0], 
                        datalabels: {
                            display: (revdatafixed > revdataregular) ? true : 'auto',
                            color: (yScale === 'logarithmic') ? 'white' : 'black',
                        }
                    },
                    {
                        data: revdataregular,
                        label: xLabels[1],
                        fill: '-1',
                        backgroundColor: revColors[9],
                        datalabels: {
                            display: (revdataregular > revdatafixed) ? true : 'auto',
                            color: 'black',
                        }
                    },
                    {
                        data: expdatafixed,
                        label: xLabels[2],
                        fill: '-1',
                        backgroundColor: expColors[0], 
                        datalabels: {
                            display: (expdatafixed > expdataregular) ? true : 'auto',
                            color: (yScale === 'logarithmic') ? 'white' : 'black',
                        }
                    },
                    {
                        data: expdataregular,
                        label: xLabels[3],
                        fill: '-1',
                        backgroundColor: expColors[9],
                        datalabels: {
                            display: (expdataregular > expdatafixed) ? true : 'auto',
                            color: 'black',
                        }
                    },
                    ],
            },
            options: {
                layout: {
                    padding: 20
                },
                borderColor: 'rgb(0,0,0)',
                borderWidth: 2,
                hoverBackgroundColor: 'gold',
                hoverBorderWidth: 2,
                plugins: {
                    title: {
                        display: true,
                        font: {
                            size: 24,
                        },
                        text: titleText,
                    },
                    datalabels: {
                        anchor: (yScale === 'logarithmic') ? 'end' : 'start',
                        align: (yScale === 'logarithmic') ? 'start' : 'start',
                        offset: (yScale === 'logarithmic') ? 0 : -30,
                        padding: 10,
                        margin: 10,
                        font: {
                            size: noDataLabales.includes(selector) ? 0 : 14,
                            weight: 'bold',
                        },
                        formatter: function(value) {
                            if(value !== 0) {
                                return value.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ".") + ` €`;
                            } else {
                                return '';
                            }
                         },
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
                interaction: {
                intersect: false,
                },
                scales: {
                y: {
                    type: yScale,
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
                    stacked: true,
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
                    stacked: true,
                },
                }
            }
        });
    }
    


    

}