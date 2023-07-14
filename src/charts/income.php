<?php


?>
<div class="chart4">
    <div class="subbox2">
        <p>Поступления разрезе статей</p>
        <canvas id="chartIncome">
    </div>
</div>
<script>
    const lab1 = [<?php echo $incomeNames ?>];
    Chart.defaults.font.size = 13;
    ctx = document.getElementById('chartIncome');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php echo $incomeNames ?>],
            datasets: [{
                //  label: 'Доход',
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
                        weight: '700',
                    },
                },

            }
        },
        plugins: [ChartDataLabels],
    });
    const subbox2 = document.querySelector('.subbox2');
    if (lab1.length < 4) {
        subbox2.style.height = '300px';
    } else if (lab1.length < 10) {
        subbox2.style.height = '400px';
    } else if (lab1.length < 15) {
        subbox2.style.height = '600px';
    } else {
        subbox2.style.height = '1200px';
    }
</script>
</canvas>