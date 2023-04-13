<?php
$manageArrChart1 = getManageCount($allData);
arsort($manageArrChart1);
$manageChart1 = getName($manageArrChart1);
$manageStatsChart1 = getValue($manageArrChart1);
?>
<canvas id="myChart" style="margin-left: 90px">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script>
        Chart.defaults.font.size = 16;
        ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {

                labels: [<?php echo $manageChart1 ?>],
                datasets: [{
                    label: 'Всего рейсов',
                    data: [<?php echo $manageStatsChart1 ?>],
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    datalabels: {
                        formatter: (value, dnct1) => {
                            let sum = 0;
                            let dataArr = dnct1.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += Number(data);
                            });
                            let percentage = value;
                            return percentage;
                        },
                    },
                }
            },
            plugins: [ChartDataLabels],
        });
    </script>
</canvas>