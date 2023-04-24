<?php
$profit = getProfit($allData);
$plan = getPlanProfit($allData);

arsort($plan);

$labels = getName($plan);

$profit = getSortBy($plan, $profit);
$profitVal = getValue($profit);

$planProfit = getSortBy($plan, $plan);
$planProfitVal = getValue($planProfit);

?>

<div class="chart3">
    <canvas id="plan"></canvas>
</div>
<script>
    Chart.defaults.font.size = 13;
    ctx = document.getElementById('plan');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php echo $labels ?>],
            datasets: [

                {
                    label: 'План',
                    data: [<?php echo $planProfitVal ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.3)',
                    ],
                    borderColor: [
                        'rgba(94, 209, 201, 1)',
                    ],
                    borderWidth: 1,
                    datalabels: {
                        // align: 'center',
                        // anchor: 'end',
                        formatter: (value, ctx) => {

                            return 'План на период: ' + "\n" + String(value).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1 ") + ' руб.';
                        },
                        textAlign: 'center'
                    }
                },
                {
                    label: 'Выполнение плана',
                    data: [<?php echo $profitVal ?>],
                    backgroundColor: [
                        'rgba(252, 136, 101, 0.4)',
                    ],
                    borderColor: [
                        'rgba(252, 136, 101, 1)',
                    ],
                    borderWidth: 1,
                    datalabels: {
                        // align: 'center',
                        // anchor: 'end',
                        formatter: (value, ctx) => {
                            let plan1 = ctx.chart.data.datasets[0].data[ctx.dataIndex];
                            console.log(plan1);
                            let planProfit1 = ctx.chart.data.datasets[1].data[ctx.dataIndex];
                            value = value.toFixed(2);
                            percent = 'Выполнение плана: ' + "\n" + (planProfit1 * 100 / plan1).toFixed(2) + '%' + "\n" + ' (' + String(value).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1 ") + ' руб.)';
                            return percent;
                        },
                        font: {
                            weight: 'bold',
                        },
                        textAlign: 'center'
                    },
                },
            ],
        },
        options: {

            scales: {
                x: {
                    //stacked: true,
                },
                y: {
                    //stacked: true,
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },

            }
        },
        plugins: [ChartDataLabels]
    });
</script>
</canvas>