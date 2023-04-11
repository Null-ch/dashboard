<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/core.php';
if(empty($_SESSION['user_id'])) {
    header('Location: /login');
}

$today = date("Y-m-d");
$dateFrom = '';
$dateTo = $today;
$message = '';
$status = 0;
if (isset($_POST)) {
    if (isset($_POST['dateFrom'])) {
        $dateFrom = $_POST['dateFrom'];
    }
    if (isset($_POST['dateTo'])) {
        $dateTo = $_POST['dateTo'];
    }
    if ($dateFrom > $dateTo){
        $status = 1;
        $message = 'Начальная дата не может быть больше конечной!';
    }
}

$allData = getAllData($dateFrom, $dateTo);

?>

<?php require 'templates/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div style="background-color:#e3e1e1; margin:10px">
                    <form style="margin-left: 350px" method="POST">
                        <p style="margin: 10px">Выберите дату начала периода: <input type="date"  class='center' name="dateFrom" value="<?php echo $dateFrom ?>" max="<?php echo $today ?>">
                        <p style="margin: 10px">Выберите дату окончания периода: <input type="date" class='center'  name="dateTo" value="<?php echo $dateTo ?>" max="<?php echo $today ?>">
                            <input type="submit" value="Отправить">
                        </p>
</div>
<?php
if ($status > 0) {
    template('messages/error_message.php', [ "message" => "$message"]);
}
?>
<body>
    <div class="main">
<div style="background-color:#e3e1e1; margin:10px">
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
</div>
</div>
</body>
<?php require 'templates/footer.php'; ?>