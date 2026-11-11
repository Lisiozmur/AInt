KALKULATOR KREDYTOWY - FRONT CONTROLLER + MVC + SMARTY

1. Skopiuj folder php_01_widok_kontroler do C:\xampp\htdocs\
2. W terminalu otworz katalog C:\xampp\htdocs\php_01_widok_kontroler
3. Uruchom: composer install  (wymagany Composer, PHP >= 8.1)
4. W XAMPP uruchom Apache (standardowo port 80).
5. Otworz: http://localhost/php_01_widok_kontroler/
   Jesli Apache dziala na innym porcie, uzyj swojego portu w URL.

Dane demonstracyjne: student / kredyt123

JEDEN PUBLICZNY PUNKT WEJSCIA: index.php
  index.php?action=login   GET  - formularz logowania
  index.php?action=doLogin POST - wyslanie loginu
  index.php?action=home    GET  - strona glowna
  index.php?action=calc    GET  - formularz kredytowy
  index.php?action=doCalc  POST - obliczenie raty
  index.php?action=logout  POST - wylogowanie

init.php - konfiguracja, autoload, sesja, wspolne obiekty (App)
functions.php - funkcje pomocnicze
app/Core/ClassLoader.class.php - rejestracja spl_autoload_register
app/Controllers - kontrolery; app/Model - modele
app/Views - szablony Smarty; public - publiczny CSS

Wazne:
- Serwer Apache musi uwzgledniac pliki .htaccess (AllowOverride All).
  Katalogi app, var, vendor i pliki konfiguracyjne sa w ten sposob
  zablokowane dla bezposredniego dostepu przez przegladarke.
- Skrypty kontrolerow sa jedynie definicjami klas; wywoluje je index.php.
- Dane logowania sa na stale w kodzie tylko do cwiczen. Do wdrozenia
  produkcyjnego potrzebne sa konta z bazy, ograniczenia prob logowania,
  HTTPS oraz konfiguracja sesji i serwera.
- Jesli zmienisz nazwe katalogu aplikacji, popraw APP_ROOT w config.php.
