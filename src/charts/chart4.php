
<?php
$profit = getProfit($allData);
$plan = getPlanProfit($allData);
$percent = getPercent($plan, $profit);
arsort($percent);

$profit = getSortBy($percent, $profit);
$total = getPercentData($percent, $profit);

$labels = getName($percent);
$data = getValue($percent);

$planProfit = getValue($plan);
$labelsProfit = getName($plan);
?>

<canvas id="myChart12" style="margin-left: 10px">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script>

        Chart.defaults.font.size = 16;
        ctx = document.getElementById('myChart12');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php echo $labels ?>],
                datasets: [{
                    
                    label: 'Выполнение плана продаж',
                    data: [<?php echo $data ?>],
                    backgroundColor: [
                        'rgba(60, 168, 52, 0.6)',
                        'rgba(214, 247, 94, 0.5)',
                        'rgba(247, 245, 94, 0.4)',
                        'rgba(247, 145, 94, 0.3)',
                    ],
                }],
            },
            options: {
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    datalabels: {
                        formatter: (value, dnct1) => {
                            let sum = 0;
                            let dataArr = dnct1.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += Number(data);
                            });
                        let percentage = String(value).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1 ") + '%';
                        return percentage;
                        },
                    },
                }
            },
            plugins: [ChartDataLabels],
        });
    </script>
</canvas>