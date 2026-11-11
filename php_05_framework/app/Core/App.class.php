<?php
namespace App\Core;

use App\Model\Messages;
use Smarty\Smarty;

class App {
    private static ?Messages $messages = null;
    private static ?Smarty $smarty = null;

    public static function init(): void {
        self::$messages = new Messages();
    }
    public static function messages(): Messages {
        return self::$messages ??= new Messages();
    }
    public static function smarty(): Smarty {
        if (self::$smarty !== null) return self::$smarty;
        $autoload = ROOT_PATH . '/vendor/autoload.php';
        if (!is_file($autoload)) {
            http_response_code(500);
            exit('Brak Smarty. Uruchom composer install w katalogu projektu.');
        }
        require_once $autoload;
        $smarty = new Smarty();
        $smarty->setTemplateDir(ROOT_PATH . '/app/Views');
        $smarty->setCompileDir(ROOT_PATH . '/var/templates_c');
        $smarty->assign('appRoot', APP_ROOT);
        self::$smarty = $smarty;
        return $smarty;
    }
    public static function render(string $template, array $vars = []): void {
        $smarty = self::smarty();
        $smarty->assign('loggedIn', Auth::loggedIn());
        $smarty->assign('userName', $_SESSION['login'] ?? '');
        $smarty->assign('csrfToken', Auth::token());
        foreach ($vars as $key => $value) $smarty->assign($key, $value);
        $smarty->display($template);
    }
}
