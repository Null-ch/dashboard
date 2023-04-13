<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/core.php';
if (empty($_SESSION['user_id'])) {
    header('Location: /login');
}

$inn = '';
$dateTo = date("Y-m-d");
$dateFrom = date("Y-m-d", strtotime($dateTo . '- 7 days'));
$message = '';
$status = 0;

if (isset($_POST)) {
    if (isset($_POST['dateFrom'])) {
        $dateFrom = $_POST['dateFrom'];
    }
    if (isset($_POST['dateTo'])) {
        $dateTo = $_POST['dateTo'];
    }
    if ($dateFrom > $dateTo) {
        $status = 1;
        $message = 'Начальная дата не может быть больше конечной!';
        $dateFrom = $dateTo;
    }
    if (isset($_POST['organization'])) {
        $inn = $_POST['organization'];
    }
}
if (isset($_POST)) {
    if (isset($_POST['organization'])) {
        $inn = $_POST['organization'];
    }
}

$allData = getAllData($dateFrom, $dateTo, $inn);

?>

<?php require 'templates/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div style="background-color:#e3e1e1; margin:10px">
    <form style="margin-left: 80px" method="POST">
        <p style="margin: 10px">Выберите дату начала периода: <input type="date" class='center' name="dateFrom"
                value="<?php echo $dateFrom ?>" max="<?php echo $dateTo ?>">
            Выберите дату окончания периода: <input type="date" class='center' name="dateTo"
                value="<?php echo $dateTo ?>" max="<?php echo $dateTo ?>">
            Выберите организацию <select name="organization" id="org-select" class="select">
                <? switch ($inn):
                    case '5024180062': ?>
                        <option value="5024180062">Вектор</option>
                        <option value="5024163243">Спектр</option>
                        <? break; ?>
                    <? case '5024163243': ?>
                        <option value="5024163243">Спектр</option>
                        <option value="5024180062">Вектор</option>
                        <? break; ?>
                    <? default: ?>
                        <option value="5024163243">Спектр</option>
                        <option value="5024180062">Вектор</option>
                <? endswitch; ?>
                <input style="margin-left: 10px" type="submit" value="Отправить">
        </p>
</div>
<?php
if ($status > 0) {
    template('messages/error_message.php', ["message" => "$message"]);
}
?>

<body>
    <div style="background-color:#e3e1e1; margin:10px">
        <div style="margin:10px">
            <div class="flex-box">
                <div>
                    <table>
                        <tbody>
                            <tr>
                                <td class="chart1">
                                    <?php require 'src/charts/chart1.php'; ?>
                                </td>
                                <td class="chart3">
                                    <?php require 'src/charts/chart3.php'; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex-box">
                <?php require 'src/charts/chart2.php'; ?>
            </div>
            <div class="flex-box">
                <?php require 'src/charts/chart4.php'; ?>
            </div>
        </div>
    </div>

</body>
<?php require 'templates/footer.php'; ?>