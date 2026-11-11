<?php
// Wspólny początek dokumentu HTML.
if (!defined('FROM_VIEW')) { http_response_code(403); exit('Brak dostępu.'); }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <?php if (!empty($page_css)) { ?>
        <link rel="stylesheet" href="<?php echo _APP_ROOT.'/css/'.rawurlencode($page_css); ?>" />
    <?php } ?>
</head>
<body>
