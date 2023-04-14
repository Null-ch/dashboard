<?php

$manageArrChart3 = getAvgTnPerKm($allData);
arsort($manageArrChart3);
$manageChart3 = getName($manageArrChart3);
$manageStatsChart3 = getValue($manageArrChart3);
?>
<canvas id="myChart2" class="chart3">
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