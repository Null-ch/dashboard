<?php
$manageArrChart2 = getNomenclature($allData);
arsort($manageArrChart2);
$manageChart2 = getName($manageArrChart2);
$count = count(explode(',', $manageChart2));
$manageStatsChart2 = getValue($manageArrChart2);
$res = getNomenclature($allData);
?>
    <div class="chartBox1">
    <canvas id="myChart4" style="margin-left: 10px;"></canvas>
<button class="selectChart" style="margin-left: 180px">Прошлая страница</button>
<button class="selectChart"> Следующая страница</button>
    </div>

</style>


<script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    const profit = [];
    const profitpoints = [];
    for (i = 1; i <= <?php echo $count ?>; i++) {
        profit.push('');
        profitpoints.push(i);
    }
    const labels = [<?php echo $manageChart2 ?>];
    const data = {
        labels: labels,
        datasets: [{
            label: 'Прибыль от контрагентов',
            data: [<?php echo $manageStatsChart2 ?>],
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
                   
                    max: 10,
                }
            },
            plugins: {
                datalabels: {
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
                            size: 14
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

</script>