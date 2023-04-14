<?php
$manageArrChart1 = getManageCount($allData);
arsort($manageArrChart1);
$manageChart1 = getName($manageArrChart1);
$manageStatsChart1 = getValue($manageArrChart1);
$total = array_sum($manageArrChart1);

?>
<canvas id="myChart" style="margin-left: 60px">

    <script>
        const centertextDoughnut = {
            id: 'centertextDoughnut',
            afterDatasetsDraw(chart, args, pluginOptions) {
                const { ctx } = chart;
                ctx.textAlign = 'center';
                ctx.font = '16px sans-serif';
                const text = 'Всего рейсов: <?php echo $total ?>';
                const textWidth = ctx.measureText(text).width;
                const x = chart.getDatasetMeta(0).data[0].x;
                const y = chart.getDatasetMeta(0).data[0].y;

                ctx.fillText(text, x, y);
            }
        };

        Chart.defaults.font.size = 16;
        ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [<?php echo $manageChart1 ?>],
                datasets: [{
                    label: 'Всего рейсов',
                    data: [<?php echo $manageStatsChart1 ?>],
                    cutout: '50%',
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
            plugins: [ChartDataLabels, centertextDoughnut],
        });
    </script>
</canvas>