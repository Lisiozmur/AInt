<?php require_once dirname(__FILE__).'/../config.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <title>Kalkulator kredytowy</title>
</head>
<body>
<h2>Kalkulator kredytowy</h2>

<form action="<?php echo _APP_URL; ?>/app/calc.php" method="post">
    <label for="kwota">Kwota kredytu (zł): </label>
    <input id="kwota" type="number" name="kwota" min="0.01" step="0.01" required value="<?php echo htmlspecialchars($kwota, ENT_QUOTES, 'UTF-8'); ?>" /><br />

    <label for="lata">Liczba lat: </label>
    <input id="lata" type="number" name="lata" min="1" step="1" required value="<?php echo htmlspecialchars($lata, ENT_QUOTES, 'UTF-8'); ?>" /><br />

    <label for="oprocentowanie">Oprocentowanie roczne (%): </label>
    <input id="oprocentowanie" type="number" name="oprocentowanie" min="0" step="0.01" required value="<?php echo htmlspecialchars($oprocentowanie, ENT_QUOTES, 'UTF-8'); ?>" /><br />

    <input type="submit" value="Oblicz ratę" />
</form>

<?php if (!empty($messages)) { ?>
    <ol style="margin: 20px; padding: 10px 10px 10px 30px; background-color: #f88; width: 300px;">
        <?php foreach ($messages as $msg) { ?>
            <li><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php } ?>
    </ol>
<?php } ?>

<?php if (isset($rata)) { ?>
    <div style="margin: 20px; padding: 10px; background-color: #ff0; width: 300px;">
        Miesięczna rata: <?php echo number_format($rata, 2, ',', ' '); ?> zł
    </div>
<?php } ?>
</body>
</html>
