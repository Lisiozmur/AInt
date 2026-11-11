<?php
// WIDOK kalkulatora - chroniony także przy bezpośrednim wejściu na plik.
require_once dirname(__FILE__).'/../config.php';
require_once _ROOT_PATH.'/auth.php';
require_login();
if (basename($_SERVER['SCRIPT_FILENAME']) === 'calc_view.php') {
    header('Location: '._APP_ROOT.'/app/calc.php');
    exit();
}
$kwota = isset($kwota) ? $kwota : '';
$lata = isset($lata) ? $lata : '';
$oprocentowanie = isset($oprocentowanie) ? $oprocentowanie : '';
$page_title = 'Kalkulator kredytowy';
$page_css = 'kredyt.css'; // Inny szablon niż dla strony logowania.
define('FROM_VIEW', true);
include _ROOT_PATH.'/includes/header.php';
?>
<main>
    <h1>Kalkulator kredytowy</h1>
    <?php include _ROOT_PATH.'/includes/user_bar.php'; ?>

    <form action="<?php echo _APP_ROOT; ?>/app/calc.php" method="post">
        <div class="field">
            <label for="kwota">Kwota kredytu (zł):</label>
            <input id="kwota" type="number" name="kwota" min="0.01" step="0.01" required value="<?php echo htmlspecialchars((string)$kwota, ENT_QUOTES, 'UTF-8'); ?>" />
        </div>
        <div class="field">
            <label for="lata">Liczba lat:</label>
            <input id="lata" type="number" name="lata" min="1" step="1" required value="<?php echo htmlspecialchars((string)$lata, ENT_QUOTES, 'UTF-8'); ?>" />
        </div>
        <div class="field">
            <label for="oprocentowanie">Oprocentowanie roczne (%):</label>
            <input id="oprocentowanie" type="number" name="oprocentowanie" min="0" step="0.01" required value="<?php echo htmlspecialchars((string)$oprocentowanie, ENT_QUOTES, 'UTF-8'); ?>" />
        </div>
        <button type="submit">Oblicz ratę</button>
    </form>

    <?php if (!empty($messages)) { ?>
        <ol class="error">
            <?php foreach ($messages as $msg) { ?>
                <li><?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php } ?>
        </ol>
    <?php } ?>

    <?php if (isset($rata)) { ?>
        <div class="result">
            Miesięczna rata:<br />
            <strong><?php echo number_format($rata, 2, ',', ' '); ?> zł</strong>
        </div>
    <?php } ?>
</main>
<?php include _ROOT_PATH.'/includes/footer.php'; ?>
