# Kalkulator kredytowy – zadania 5, 5a, 5b, 6, 6a, 6b

Zaktualizowany projekt PHP z **jednym publicznym punktem wejścia**, obiektowym routerem, ochroną akcji według ról, sesjami, kontrolerem logowania, widokami **Smarty 5**, autoloaderem i kalkulatorem rat równych.

## Szybkie uruchomienie

Wymagania: **PHP 8.1 lub nowszy**, **Composer** i dostęp do instalacji pakietu `smarty/smarty`.

```bash
composer install
php -S localhost:8000 -t public
```

Otwórz w przeglądarce <http://localhost:8000/index.php?action=home>.

**Konta demonstracyjne** (wyłącznie do nauki; przed publikacją usuń je z projektu):

| Rola | Login | Hasło |
|---|---|---|
| `user` | `user@example.test` | `Kredyt123!` |
| `admin` | `admin@example.test` | `Admin123!` |

Konta zapisano w `app/Models/DemoUserRepository.php` jako hashe z `password_hash()`; logowanie korzysta z `password_verify()`. Nie używaj tych demonstracyjnych danych w systemie produkcyjnym. System nie zawiera rejestracji ani bazy użytkowników.

### Testy

```bash
php tests/run.php
```

Testy uruchamiają się **bez Composera** dzięki niewielkiej zaślepce Smarty w `tests/stubs/Smarty.php`; sprawdzają logikę, ale nie pełne renderowanie HTML. Działanie interfejsu obejrzysz po `composer install`.

## Organizacja plików

```text
kalkulator-kredytowy-5-6b/
├── public/
│   ├── index.php                   # JEDYNY publiczny skrypt PHP; definicje tras/ochrony
│   ├── .htaccess
│   └── assets/css/main.css         # publiczny zasób statyczny
├── app/
│   ├── Controllers/
│   │   ├── HomeCtrl.php
│   │   ├── LoanCtrl.php
│   │   ├── LoginCtrl.php           # obiektowy kontroler logowania
│   │   └── AdminCtrl.php
│   ├── Models/
│   │   ├── LoanCalculator.php
│   │   └── DemoUserRepository.php  # demonstracyjne konta z hashami haseł
│   └── Views/
│       ├── layout/header.tpl, footer.tpl
│       ├── home.tpl, error.tpl
│       ├── loan/form.tpl, result.tpl
│       ├── auth/LoginView.tpl     # widok logowania w Smarty
│       └── admin/index.tpl
├── core/
│   ├── ClassLoader.php            # spl_autoload_register()
│   ├── Router.php                 # obiektowy routing + ochrona
│   ├── Auth.php                   # przechowywanie tożsamości/roli w sesji
│   ├── AppContext.php, Config.php, Messages.php
│   └── functions.php              # helpery: role, parametry, przekierowania, CSRF
├── config.php
├── init.php                       # sesja, Smarty, konfiguracja, wiadomości, autoload
├── composer.json
├── tests/run.php                  # testy jednostkowe i logiki routingu
└── var/smarty/{compile,cache}/    # niepubliczne pliki wygenerowane przez Smarty
```

### Zadania 5–5b (zachowane)

- **5 – Kontroler główny:** `public/index.php` przyjmuje parametr `action`; strona główna zawiera menu, a `LoanCtrl` umożliwia obliczenie raty, sumy spłat i odsetek. Nie ma innych publicznych skryptów PHP.
- **5a – Nowa struktura:** `init.php` tworzy konfigurację, komunikaty, silnik Smarty i sesję; `core/functions.php` zawiera funkcje pomocnicze. Kontrolery, modele i szablony znajdują się w osobnych folderach.
- **5b – Przestrzenie nazw:** `Core\ClassLoader` mapuje `Core\*` na `core/` i `App\*` na `app/`, korzystając z `spl_autoload_register()` (poprawna nazwa funkcji PHP).

### Zadanie 6 – Ochrona zasobów w kontrolerze głównym

- Każde żądanie akcji PHP przechodzi przez **`public/index.php`**, gdzie zdefiniowana jest biała lista dostępnych akcji.
- Dostęp do akcji chronionych jest sprawdzany w jednym miejscu: metodzie **`Core\Router::dispatch()`**. Samych `LoanCtrl` i `AdminCtrl` nie trzeba zabezpieczać oddzielnie.
- Logowanie obsługuje **`App\Controllers\LoginCtrl`**, a formularz – szablon **`app/Views/auth/LoginView.tpl`**. Jest to odpowiednik wymaganych w poleceniu `LoginCtrl.class` i `LoginView`, z nazwami plików dostosowanymi do autoloadingu PSR-4 i Smarty.

### Zadanie 6a – Funkcje pomocnicze i role

- `set_user_role($login, $role)` zapisuje użytkownika i rolę w sesji **po sprawdzeniu danych**; `clear_user_role()` czyści tożsamość; `current_user()`, `current_role()`, `is_logged_in()` i `has_role('admin')` odczytują uprawnienia.
- `get_string()`, `post_string()` i `param_string()` pobierają parametry bez przyjmowania wartości-tablic.
- `app_url($action, $params)` tworzy bezpieczne linki do `index.php`, `redirect_to($action, $params)` wykonuje wyłącznie przekierowanie wewnętrzne.
- `csrf_token()` i `csrf_valid()` obsługują tokeny formularzy POST.
- W pośredniej wersji 6a wywołanie kontrolera mogło realizować `dispatch_action()`; w **wersji końcowej 6b funkcję tę zastępuje obiekt Router**, więc nie utrzymujemy obu konkurencyjnych mechanizmów.

### Zadanie 6b – Obiektowy router

Definicje akcji w `public/index.php`:

```php
$router = new Router($app);
$router->add('home', HomeCtrl::class, 'index', ['GET']);
$router->add('login', LoginCtrl::class, 'form', ['GET']);
$router->add('loginPost', LoginCtrl::class, 'authenticate', ['POST']);
$router->add('loanForm', LoanCtrl::class, 'form', ['GET'], ['user', 'admin']);
$router->add('loanCalc', LoanCtrl::class, 'calculate', ['POST'], ['user', 'admin']);
$router->add('admin', AdminCtrl::class, 'index', ['GET'], ['admin']);
$router->add('logout', LoginCtrl::class, 'logout', ['POST'], ['user', 'admin']);
$router->dispatch(get_string('action', 'home'));
```

Pusta lista ról oznacza akcję **publiczną**. Router weryfikuje istnienie trasy, metodę HTTP i listę uprawnionych ról, a następnie sam tworzy kontroler i wywołuje metodę. Nie wywołuje kontrolerów po nazwach przesłanych bezpośrednio od użytkownika.

## Macierz dostępu

| Akcja (`index.php?action=...`) | Metoda HTTP | Gość | `user` | `admin` |
|---|---|---|---|---|
| `home` | GET | ✔ | ✔ | ✔ |
| `login` | GET | ✔ | przekierowanie | przekierowanie |
| `loginPost` | POST | ✔ | przekierowanie | przekierowanie |
| `loanForm` | GET | → logowanie | ✔ | ✔ |
| `loanCalc` | POST | → logowanie | ✔ | ✔ |
| `admin` | GET | → logowanie | **403** | ✔ |
| `logout` | POST | → logowanie | ✔ | ✔ |

Niepoprawna akcja daje HTTP **404**, nieprawidłowa metoda HTTP – **405**. Użytkownik zalogowany, ale bez wymaganej roli, dostaje **403**. Gość przy wejściu na zasób chroniony trafia na formularz logowania. Parametr `next` może wskazywać tylko znane akcje GET: `loanForm` lub `admin`; nie jest otwartym adresem URL.

## Sposób sprawdzenia w przeglądarce

1. Jako gość otwórz `index.php?action=loanForm` – nastąpi przekierowanie na logowanie.
2. Zaloguj się jako `user@example.test` / `Kredyt123!` i oblicz ratę kredytu.
3. Otwórz `index.php?action=admin` – zobaczysz **403 Brak uprawnień**.
4. Wyloguj się przyciskiem w nagłówku i zaloguj jako `admin@example.test` / `Admin123!`.
5. Otwórz `index.php?action=admin` – strona administracyjna zostanie wyświetlona.
6. Otwórz `index.php?action=nieMaTakiejAkcji` – **404**. Spróbuj wykonać GET pod `index.php?action=loanCalc` – **405**.

### Wzór na ratę równą

`R = K × r / (1 - (1 + r)^(-n))`, gdzie `r` to roczne oprocentowanie / 12 / 100, a `n` oznacza liczbę miesięcy. Dla 0%: `R = K / n`. Obliczenia są edukacyjne i nie uwzględniają RRSO, prowizji, ubezpieczeń ani zmiany stóp.

## Ważne ograniczenia i bezpieczeństwo

- **Nie wystawiaj katalogu projektu jako root strony.** W serwerze Apache/XAMPP ustaw `DocumentRoot` na **`public/`**; w serwerze developerskim użyj `-t public`. Wtedy kontrolery, szablony, sesje i model pozostają poza publicznym katalogiem.
- W ćwiczeniu dostępne są jawnie opisane konta demo i brak jest bazy użytkowników. **Nie publikuj tej wersji w internecie** bez usunięcia kont demo, wdrożenia prawdziwego repozytorium kont i zabezpieczenia konfiguracji.
- Logowanie weryfikuje hashe przez `password_verify()`, regeneruje identyfikator sesji; wylogowanie usuwa rolę i regeneruje identyfikator; logowanie i wylogowanie mają ochronę CSRF. Router sprawdza role po stronie serwera.
- Jest prosty limit 5 nieudanych logowań / 5 minut **na sesję**. To nie zastępuje produkcyjnego rate limitu na poziomie adresu IP/użytkownika.
- Na serwerze produkcyjnym wymuś HTTPS, poprawne ustawienia cookie Secure / nagłówków i zabezpiecz logowanie oraz repozytorium kont. Ustaw uprawnienia zapisu dla `var/smarty/compile` i `var/smarty/cache`.
