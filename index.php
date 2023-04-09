<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/core.php';
$today = date("Y-m-d");
$dateFrom = '';
$dateTo = '';

if (isset($_POST)) {
    if (isset($_POST['dateFrom'])) {
        $dateFrom = $_POST['dateFrom'];
    }
    if (isset($_POST['dateTo'])) {
        $dateTo = $_POST['dateTo'];
    }
}

$allData = getAllData($dateFrom, $dateTo);

?>

<?php require 'templates/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div>
                    <form style="margin: 10px" method="POST">
                        <p style="margin: 10px">Выберите дату начала периода: <input type="date"  class='center' name="dateFrom" value="" max="<?php echo $today ?>">
                        <p style="margin: 10px">Выберите дату окончания периода: <input type="date" class='center'  name="dateTo" value="" max="<?php echo $today ?>">
                            <input type="submit" value="Отправить">
                        </p>
</div>

<body>
    <div>
        <table class="flex-box">
            <tbody>
                <tr>
                    <td>
                        <?php require 'src/charts/chart1.php'; ?>
                    </td>
                    <td>
                        <?php require 'src/charts/chart2.php'; ?>
                    </td>
                    <td>
                        <?php require 'src/charts/chart3.php'; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <a class="weatherwidget-io" href="https://forecast7.com/ru/55d7637d62/moscow/" data-label_1="Погода" data-label_2="Москва" data-theme="original">Погода Москва</a>
    <script>
        ! function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (!d.getElementById(id)) {
                js = d.createElement(s);
                js.id = id;
                js.src = 'https://weatherwidget.io/js/widget.min.js';
                fjs.parentNode.insertBefore(js, fjs);
            }
        }(document, 'script', 'weatherwidget-io-js');
    </script>

</body>
<?php require 'templates/footer.php'; ?>