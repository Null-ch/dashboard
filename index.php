<?php
require $_SERVER['DOCUMENT_ROOT'] . '/src/core.php';
if (empty($_SESSION['user_id'])) {
    header('Location: /login');
}

?>

<?php require 'templates/header.php'; ?>

<body>
    <div class="block">
        <form style="margin-left: 80px" method="POST">
            <p style="margin: 10px">Выберите дату начала периода: <input type="date" class='center' name="dateFrom"
                    value="<?php echo $dateFrom ?>" max="<?php echo $dateTo ?>">
                Выберите дату окончания периода: <input type="date" class='center' name="dateTo"
                    value="<?php echo $dateTo ?>" max="<?php echo $today ?>">
                Выберите организацию <select name="organization" id="org-select" class="select">
                    <?php switch ($inn):
                        case '5024180062': ?>
                    <option value="5024180062">Вектор</option>
                    <option value="5024163243">Спектр</option>
                    <? break; ?>
                    <?php
                        case '5024163243': ?>
                    <option value="5024163243">Спектр</option>
                    <option value="5024180062">Вектор</option>
                    <? break; ?>
                    <?php
                        case '': ?>
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
    <div class="base">
        <div class="contentMain">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <?php require $_SERVER['DOCUMENT_ROOT'] . '/src/charts/tripCount.php'; ?>
                        </td>
                        <td>
                            <?php require $_SERVER['DOCUMENT_ROOT'] . '/src/charts/avgProfitPerKm.php'; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="contentMain">
            <?php require $_SERVER['DOCUMENT_ROOT'] . '/src/charts/profitPerClient.php'; ?>
        </div>
        <div class="contentMain">
            <?php require $_SERVER['DOCUMENT_ROOT'] . '/src/charts/profitPlan.php'; ?>
        </div>
    </div>
    </div>
</body>
<?php require 'templates/footer.php'; ?>
