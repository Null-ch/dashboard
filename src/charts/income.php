<?php


?>
<div class="chart4">
    <p>Доход разрезе статей</p>
    <canvas id="chartIncome">
</div>
<script>
    Chart.defaults.font.size = 13;
    ctx = document.getElementById('chartIncome');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php echo $incomeNames ?>],
            datasets: [{
                // label: 'Доход',
                data: [<?php echo $incomeStats ?>],
                backgroundColor: [
                    'rgb(75, 192, 192, 0.2)',
                ],
                borderWidth: 2,
                // cutout: '50%',
            }]
        },

        options: {
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                },
                datalabels: {
                    align: 'right',
                    anchor: 'center',
                    formatter: (value, dnct1) => {
                        let sum = value.toFixed(2);
                        let percentage = String(value).replace(/(\d)(?=(\d{3})+([^\d]|$))/g, "$1 ") + ' руб.';
                        return percentage;
                    },
                    font: {
                        weight: '800',
                    },
                },

            }
        },
        plugins: [ChartDataLabels],
    });
</script>
</canvas>