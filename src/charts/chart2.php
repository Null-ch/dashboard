<?php
$allProfitData = getNomenclature($allData);
arsort($allProfitData);
$contractor = getName($allProfitData);
$count = count(explode(',', $contractor));
$contractorProfit = getValue($allProfitData);
$total = number_format(array_sum(explode(',', $contractorProfit)), 2, ',', ' ');
$contractor1 = explode(',', $contractor);

?>

<canvas id="myChart4" class="chart2"></canvas>
<input oninput="updateChart(this)" type="range" id="points" min="10" max="<?php echo $total ?>">
<script>
    Chart.defaults.font.size = 14;
    const labels = [<?php echo $contractor ?>];
    const data1 = [<?php echo $contractorProfit ?>];

    const data = {
        labels: labels,
        datasets: [{
            label: 'Прибыль в разрезе контрагентов',
            data: data1,
            borderWidth: 1,
            backgroundColor: [
                'rgba(80, 191, 78, 0.2)',
            ],
        }],

    };
    const config = {
        type: 'bar',
        data,
        options: {
            indexAxis: 'y',
            scales: {
                y: {
                    min: 0,
                },
            },
            plugins: {
                datalabels: {
                    align: 'right',
                    anchor: 'center',
                    formatter: (value, dnct1) => {
                        let sum = 0;
                        let dataArr = dnct1.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += Number(data);
                        });
                        let percentage = String(value).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1 ") + ' (' + (value * 100 / sum).toFixed(2) + '%)';
                        return percentage;
                    },
                },
                legend: {
                    display: true,
                    labels: {
                        font: {
                            size: 13
                        }
                    }
                }
            }
        },

        plugins: [ChartDataLabels],
    };

    const myChart4 = new Chart(
        document.getElementById('myChart4'),
        config
    );


    function updateChart(range) {
        const rangeValue = labels.slice(0, range.value);
        myChart4.config.data.labels = rangeValue;
        myChart4.update()
    };
</script>