<?php

?>
<div class="chart4">
    <div class="subbox">
        <p>Выбытия разрезе статей</p>
        <canvas id="chartExpense">
    </div>
</div>
<script>
    const lab = [<?php echo $expenseNames ?>];
    Chart.defaults.font.size = 13;
    ctx = document.getElementById('chartExpense');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php echo $expenseNames ?>],
            datasets: [{
                // label: 'Расход',
                data: [<?php echo $expenseStats ?>],
                backgroundColor: [
                    'rgb(255, 99, 132, 0.2)',
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
    const subbox = document.querySelector('.subbox');
    if (lab.length < 4) {
        subbox.style.height = '300px';
    } else if (lab.length < 10) {
        subbox.style.height = '400px';
    } else if (lab.length < 15) {
        subbox.style.height = '600px';
    } else {
        subbox.style.height = '1200px';
    }
</script>

</canvas>