<?php 
/** 
 * Description of Router
 * @author zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
 * @date 2021-08-04
 */

namespace Coderhelper\Components;


use Coderhelper\Controllers\NotfoundController;

class Router {

    /**
     * Пространство имен для контроллеров
     * @var string
     */
    private string $controllersNamespace = 'Coderhelper\Controllers\\';

    /**
     * Массив с маршрутами
     * @var array
     */
    private array $routes;

    /**
     * Массив с параметрами
     * @var array|null
     */
    private array|null $parameters;

    /**
     * Router constructor.
     */
    public function __construct(){
        // Перенаправляем на корректный адрес с https и без www, если текущий адрес не корректен
        $this->redirection('http');
        $routesPath = PUBLIC_HTML."/config/routes.php";
        $this->routes = include($routesPath);
    }

    /**
     * Возвращает URI запроса без GET параметров
     * пример: /catalog/phone/iphone-6s/
     * @return string
     */
    private function getUri(): string
    {
        if(!empty($_SERVER['REQUEST_URI'])){
            return trim($_SERVER['REQUEST_URI'], '/');
        }
        return '';
    }

    /**
     * Возвращает базовый URL для ресурса
     * пример: http://example.com/
     * @return string - базовый URL для ресурса (протокол + хост) вида http://example.com/
     */
    private function getBaseUrl(): string
    {
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
        return $scheme . '://' . $_SERVER['HTTP_HOST'] . '/';
    }

    /**
     * Готовим поисковый запрос для передачи контроллеру
     * пример: /search/?query=iphone
     * @return void
     */
    private function prepareSearch(): void
    {
       if(isset($_GET['query']) && strlen($_GET['query']) >= 3){
           if(count($this->parameters) < 1) {
               $this->parameters[] = 1;
           }
           $spec = ['"', "'", '?', '/', '\\'];
           $this->parameters[] = str_replace($spec, '', trim($_GET['query']));
       }
    }

    /**
     * Запускает маршрутизацию
     * @return void
     */
    public function run(): void
    {
        // получить строку запроса
        $uri = $this->getUri();
        // проверить наличие такого запроса в routes.php
        foreach($this->routes as $uriPattern => $path) {
            // сравниваем $uriPattern и $uri
            // если есть совпадение, 
            if(preg_match("~^$uriPattern~ui", $uri)){
                // Получаем внутренний путь из внешнего согласно правилу
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                # Очищаем от GET параметров
                $internalRoute = preg_replace('~\?(.*)~', '', $internalRoute);

                // Опеределить action, controller, параметр
                $segments = explode('/', $internalRoute);
                $controllerName = ucfirst(array_shift($segments)."Controller");
                $actionName = "action".ucfirst(array_shift($segments));
                $this->parameters = $segments;
                # Если контроллер Search, добавляем в параметр поисковый запрос
                if($controllerName == "SearchController"){
                    $this->prepareSearch();
                }
                $controllerName = $this->controllersNamespace.$controllerName;
                # создать объект, вызвать метод (т.е. action)                
                $controllerObject = new $controllerName;
                // Если существует контроллер соответствущий роуту, вызывает контроллер, в противном случае вызов Notfound
                $result = (method_exists($controllerName, $actionName)) ?
                    call_user_func_array(array($controllerObject, $actionName), $this->parameters) : 
                    NotfoundController::actionNotfound();
                /**
                 * call_user_func_array вызывает action $actionName у объекта $controllerObject
                 * в случае отсутствия контроллера, вызываем NotFound
                 * с параметрами $parameters
                 */
                if($result != null){
                    break;
                }
            }
        }        
    }

    /**
     * Редирект на корректный адрес с https и без www, если текущий адрес не корректен
     * пример: http://www.example.com/ -> https://example.com/
     * @param string $def_scheme
     * @return void
     */
    private function redirection(string $def_scheme = 'https'): void
    {
        // Устанавливаем куку с временем последнего посещения
        setcookie("last_visit", time(), time() + 60 * 60 * 24 * 30, '/');
        // Редирект с www на без www
        $redirect = (str_starts_with($_SERVER['HTTP_HOST'], 'www.'))
            ? $def_scheme . '://' . substr($_SERVER['HTTP_HOST'], 4) . $_SERVER['REQUEST_URI']
            : (($_SERVER['REQUEST_SCHEME'] !== $def_scheme)
                ? $def_scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
                : null);
        // Если есть необходимость в редиректе, выполним его
        if ($redirect !== null) {
            header("Location: ".$redirect, true, 301);
            exit;
        }
    }






} 
