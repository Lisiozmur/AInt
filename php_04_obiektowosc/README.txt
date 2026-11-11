KALKULATOR KREDYTOWY - MVC, SMARTY, XAMPP

STRUKTURA:
index.php - punkt wejscia: logowanie i wylogowanie
calc.php - punkt wejscia: wyswietlenie formularza i obliczenie raty
app/controllers/CalcCtrl.class.php - kontroler kalkulatora
app/controllers/LoginCtrl.class.php - kontroler logowania
app/model/CalcForm.class.php - model danych formularza
app/model/CalcResult.class.php - model wyniku
app/model/Messages.class.php - klasa komunikatow
app/lib/Auth.class.php - sesje i kontrola dostepu
templates/CalcView.tpl - widok Smarty
    Header.tpl, Footer.tpl, UserBar.tpl - wspolne czesci ({include})
templates/LoginView.tpl - widok logowania
css/kredyt.css - prosty arkusz stylow

INSTALACJA (XAMPP):
1. Rozpakuj folder php_01_widok_kontroler do C:\xampp\htdocs\.
2. Zainstaluj Composer z https://getcomposer.org/ (jesli jeszcze go nie masz).
3. Otworz terminal w folderze C:\xampp\htdocs\php_01_widok_kontroler.
4. Uruchom: composer install
   To pobiera prawdziwa biblioteke Smarty do katalogu vendor/.
   WYMAGANE: bez tej czynnosci szablony .tpl nie moga dzialac.
5. Uruchom Apache w XAMPP.
6. Wejdz: http://localhost/php_01_widok_kontroler/
7. Dane testowe: student / kredyt123

WAZNE: port 8080 NIE JEST NA SZTYWNO USTAWIONY.
Jesli Apache dziala na porcie 8080, otworz adres z :8080;
formularz korzysta ze sciezki wzglednej do domeny i zachowuje aktualny port.

PRZYKLAD: kwota 120000, lata 10, oprocentowanie 6 -> rata 1332,25 zl.

OCHRONA:
Sesja jest sprawdzana w CalcCtrl::process() przed utworzeniem widoku.
Klasy i szablony nie sa punktami wejscia; dodatkowo .htaccess ogranicza
bezposredni dostep HTTP do katalogow app/, templates/, var/, vendor/.
Logowanie i wylogowanie obsluguje index.php; formularz kredytowy calc.php.
CSRF jest weryfikowane przy wysylaniu formularzy.

UWAGA: .htaccess wymaga mod_rewrite oraz AllowOverride w Apache.
Haslo jest demonstracyjne; do prawdziwej aplikacji potrzebna jest baza danych
uzytkownikow i zarzadzanie kontami.

Do archiwum NIE dolaczono zaleznosci vendor/ - Smarty instaluje Composer.
