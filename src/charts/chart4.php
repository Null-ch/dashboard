<?php
$profit = getProfit($allData);
$plan = getPlanProfit($allData);
$percent = getPercent($plan, $profit);
arsort($percent);

$labels = getName($percent);

$profit = getSortBy($percent, $profit);
$profitVal = getValue($profit);

$planProfit = getSortBy($percent, $plan);
$planProfitVal = getValue($planProfit);

?>

<canvas id="plan" style="margin-left: 10px" class="chart4">
    <script>
        Chart.defaults.font.size = 14;
        ctx = document.getElementById('plan');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php echo $labels ?>],
                datasets: [
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
                            formatter: (value, ctx) => {
                                let plan1 = ctx.chart.data.datasets[1].data[ctx.dataIndex];
                                console.log(plan1);
                                let planProfit1 = ctx.chart.data.datasets[0].data[ctx.dataIndex];
                                percent = 'Выполнение плана: ' + (planProfit1 * 100 / plan1).toFixed(2) + '%' + "\n" + ' (' + String(value).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1 ") + ' руб.)';
                                return percent;
                            },
                            font: {
                                weight: 'bold',
                            },
                            textAlign: 'center'
                        },
                    },
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
                            formatter: (value, ctx) => {
                                return 'План на период: ' + String(value).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1 ") + 'руб.';
                            },
                        }
                    },
                ],
            },
            options: {

                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
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