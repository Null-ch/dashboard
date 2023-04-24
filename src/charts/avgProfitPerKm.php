<?php
$manageArrChart3 = getAvgTnPerKm($groupData);

arsort($manageArrChart3);

$manageChart3 = getName($manageArrChart3);
$manageStatsChart3 = getValue($manageArrChart3);
?>
<div class="chart2">
    <canvas id="myChart2">
</div>
<script>
    Chart.defaults.font.size = 12;
    ctx = document.getElementById('myChart2');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php echo $manageChart3 ?>],
            datasets: [{
                label: 'Средняя стоимость тн/км (руб)',
                data: [<?php echo $manageStatsChart3 ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                ],
                borderWidth: 2
            }]
        },
        //fill: false,
        options: {
            maintainAspectRatio: false,
            plugins: {
                datalabels: {
                    anchor: 'center',
                    formatter: (value, dnct1) => {
                        let sum = value.toFixed(2);
                        let percentage = String(sum) + ' руб.';
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
            },

        },
        plugins: [ChartDataLabels]
    });
</script>
</canvas>