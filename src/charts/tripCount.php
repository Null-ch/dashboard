<?php
$manageData = getTripCount($groupData);
arsort($manageData);

$manageNames = getName($manageData);
$manageStats = getValue($manageData);
$total = array_sum($manageData);

?>
<div class="chart1">
<p>Количество совершенных рейсов</p>
<canvas id="myChart" >
</div>
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
                labels: [<?php echo $manageNames ?>],
                datasets: [{
                    label: 'Всего рейсов',
                    data: [<?php echo $manageStats ?>],
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
                        font: {
                                weight: 'bold',
                            },
                    },
                    
                }
            },
            plugins: [ChartDataLabels, centertextDoughnut],
        });
    </script>
</canvas>