KALKULATOR KREDYTOWY - XAMPP - INCLUDE I OSOBNY SZABLON

INSTALACJA
1. Skopiuj folder php_01_widok_kontroler do C:\xampp\htdocs\
2. Uruchom Apache w XAMPP.
3. Otwórz http://localhost/php_01_widok_kontroler/
4. Login testowy: student; hasło: kredyt123.
5. Po zalogowaniu sprawdź obliczenia, wylogowanie i blokadę dostępu.

NOWE ELEMENTY
- includes/header.php: wspólny początek HTML dla obu widoków (include)
- includes/footer.php: wspólny koniec HTML dla obu widoków (include)
- includes/user_bar.php: wspólny pasek użytkownika i wylogowania
- css/kredyt.css: osobny, oszczędny szablon kalkulatora kredytowego
- app/login_view.php: prosty widok logowania z include header/footer
- app/calc_view.php: nowy szablon i include header/footer/user_bar

ZACHOWANE
- index.php: forward do kontrolera kalkulatora
- app/login.php: kontroler logowania
- app/calc.php: kontroler obliczania raty równej
- auth.php: sesja oraz ochrona kalkulatora
- app/logout.php: wylogowanie metodą POST

Kontroler odpowiada za logikę i obliczenia, widok za HTML.
Bez logowania wejście w index.php i app/calc.php przekierowuje do logowania.
Bezpośrednie wejście w app/calc_view.php też wymaga sesji.
Pliki includes/ nie są samodzielnymi stronami.

Przykład: 120000 zł, 10 lat, 6% -> rata około 1332,25 zł.

Uwaga: dane logowania są demonstracyjne; projekt służy do nauki.
Jeśli Apache działa na innym porcie, otwórz ten port w przeglądarce.
Formularze używają ścieżek względnych względem hosta (bez wymuszania portu 8080).
