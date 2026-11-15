<?php

declare(strict_types=1);

namespace Core;

/**
 * Router z białą listą akcji, metod HTTP oraz ról.
 * Jest wywoływany WYŁĄCZNIE przez public/index.php.
 */
final class Router
{
    /** @var array<string,array{class:class-string,method:string,verbs:list<string>,roles:list<string>}> */
    private array $routes = [];

    public function __construct(private AppContext $app)
    {
    }

    /**
     * @param class-string $controller
     * @param list<string> $verbs
     * @param list<string> $roles Pusta lista oznacza zasób publiczny.
     */
    public function add(
        string $action,
        string $controller,
        string $method,
        array $verbs = ['GET'],
        array $roles = []
    ): self {
        if (preg_match('/^[a-z][a-zA-Z0-9]*$/D', $action) !== 1 || isset($this->routes[$action])) {
            throw new \InvalidArgumentException('Niepoprawna lub zduplikowana nazwa akcji.');
        }
        if ($verbs === [] || array_diff($verbs, ['GET', 'POST']) !== []) {
            throw new \InvalidArgumentException('Niedozwolona metoda HTTP.');
        }
        if (array_diff($roles, ['user', 'admin']) !== []) {
            throw new \InvalidArgumentException('Nieznana rola w definicji trasy.');
        }
        if (!class_exists($controller) || !method_exists($controller, $method)) {
            throw new \InvalidArgumentException('Nie istnieje kontroler lub metoda: ' . $controller . '::' . $method);
        }
        $this->routes[$action] = [
            'class' => $controller,
            'method' => $method,
            'verbs' => array_values(array_unique($verbs)),
            'roles' => array_values(array_unique($roles)),
        ];
        return $this;
    }

    public function dispatch(string $action): void
    {
        $route = $this->routes[$action] ?? null;
        if ($route === null) {
            $this->error(404, 'Nie znaleziono żądanej strony.', 'Strona nie istnieje');
            return;
        }

        // Najpierw zgodność metody, następnie decyzja o dostępie – w jednym miejscu.
        $verb = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (!in_array($verb, $route['verbs'], true)) {
            header('Allow: ' . implode(', ', $route['verbs']));
            $this->error(405, 'Ta akcja nie obsługuje metody ' . $verb . '.', 'Niedozwolona metoda');
            return;
        }

        if (!Auth::hasAnyRole($route['roles'])) {
            if (!Auth::loggedIn()) {
                // Zachowujemy tylko nazwę akcji; nigdy pełny adres z żądania.
                $next = $verb === 'GET' ? ['next' => $action] : [];
                redirect_to('login', $next, 302);
            }
            $this->error(403, 'Nie masz uprawnień do wybranej strony.', 'Brak uprawnień');
            return;
        }

        $class = $route['class'];
        $controller = new $class($this->app);
        $controller->{$route['method']}();
    }

    private function error(int $status, string $message, string $title): void
    {
        http_response_code($status);
        $this->app->messages->addError($message);
        $this->app->render('error.tpl', ['page_title' => $title]);
    }
}
