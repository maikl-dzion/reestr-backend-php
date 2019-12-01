<?php

class Router {

    public  $routes = array();
    public  $errorMessage = array();
    public  $params = array();
    private $controller;
    private $action;
    public  $pathInfo;
    private $db;

    public function __construct($routes, $db = false) {
         $this->routes = $routes;
         $this->db = $db;
         // $this->run();
    }

    private function getPathInfo() {
        $delimiter = '/'; 
        $err =  'NOT PATH INFO';   
        if(empty($_SERVER['PATH_INFO'])) $this->error($err);  
        $this->pathInfo = trim($_SERVER['PATH_INFO'], $delimiter);
        return explode($delimiter, $this->pathInfo);  
    }
    
    private function getRoute($pathInfo) {
        
        $controllerName = $actionName = $requestUri = '';
        
        foreach ($pathInfo as $key => $value) {
            switch ($key) {
                case 0  : $controllerName = $value; break;
                case 1  : $actionName     = $value; break;
                default : $this->params[] = $value; break;
            }
        }

        $requestUri = $controllerName . '/' . $actionName;

        if(!isset($this->routes[$requestUri])) {
            $r = $this->checkRoute($controllerName, $actionName);
            if(!$r) {
                $err = 'NOT ROUTE - "' .$requestUri. '" <br> (pathInfo-' . $this->pathInfo. ') ';
                $this->error($err);
            }
        }

        //--- получаем route 
        $route = explode('/', $this->routes[$requestUri]); 
 
        //--- обработка ошибок
        if(!isset($route[0])) $this->error('NOT CLASS NAME ');
        if(!isset($route[1])) $this->error('NOT ACTION NAME');

        $this->controller = $route[0];
        $this->action     = $route[1];

        return $route;
    }
    
    public function run() {

        $result = $pathInfo = $params = array();

        $pathInfo = $this->getPathInfo();    //---получаем строку запроса от клиента
        $route = $this->getRoute($pathInfo); //---получаем маршрут (route[0] = controller, route[0] = action, route[2] = method)

        $className  = $this->controller;
        $actionName = $this->action;  

        // -- проверяем наличие класса  
        if(!class_exists($className))  
           $this->error('NOT CLASS -' . $className);
          
        // -- создаем объект класса
        $controller = new $className($this->db, $this->params);
        
        // -- проверяем наличие метода
        if(!method_exists($controller, $actionName)) 
            $this->error('NOT ACTION  -' . $actionName);

        // -- запускаем метод класса
        $result = $controller->$actionName();
        
        return $result;
    }

    public function checkRoute($controllerName, $actionName) {
        
        if(class_exists($controllerName)) {
            $controller = new $controllerName($this->db, $this->params);
            if(method_exists($controller, $actionName)) {
                $requestUri = $controllerName .'/'. $actionName;
                $this->routes[$requestUri] = $requestUri;
                $routes = var_export($this->routes, true);
                $result = '<?php  $Routes = ' . $routes . ';';
                file_put_contents(CONF_DIR .'/routes.php', $result);
                return true;
                // lg($this->routes);
            };
        }
        
        return false;
    }

    public function error($values = '') {
        $debugTrace = '';
        if(DEBUG_TRACE)  $debugTrace = debug_backtrace();
        if(!empty($this->errorMessage))
           lg($values, $this->errorMessage, $debugTrace);
        else 
           lg($values, $debugTrace);
    }
    
}

