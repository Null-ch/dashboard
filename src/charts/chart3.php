<?php

$manageArrChart3 = getAvgTnPerKm($allData);
arsort($manageArrChart3);
$manageChart3 = getName($manageArrChart3);
$manageStatsChart3 = getValue($manageArrChart3);
?>
<canvas id="myChart2">
    <script>
        ctx = document.getElementById('myChart2');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php echo $manageChart3 ?>],
                datasets: [{
                    label: 'Средняя стоимость тн/км (руб)',
                    data: [<?php echo $manageStatsChart3 ?>],
                    backgroundColor: [
                        'rgba(60, 168, 52, 0.6)',
                        'rgba(214, 247, 94, 0.5)',
                        'rgba(247, 245, 94, 0.4)',
                        'rgba(247, 145, 94, 0.3)',
                    ],
                    borderWidth: 2
                }]
            },
            fill: false,
            options: {
            },
        });
    </script>
</canvas>