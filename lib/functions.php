<?php
// Protection from a directly call
if (!defined('FRAMEWORK_NS')) {
    exit;
}



/** MAIN LOGIC FUNCTIONS */

/**
 * Application pre-initialization
 */
function preInit() {
    // before_pre_init:           x
    
    /** Possible events */
    $GLOBALS['event'] = [
        'after_pre_init' => [],
        'before_init' => [],
        'after_init' => [],
        'before_start' => [],
        'before_routing' => [],
        'after_routing' => [],
        'after_finish' => [],
        'stop' => [],                   // условное событие: если в каком-то обработчике $e->stop=true прил остановлено, тогда это и все последующие
        'response_send' => [],
        'after_response_send' => []
   ];
    
    /** Event object */
    $e = new stdClass();
    $e->_stop = false;           // Do stop the script execution?
    $e->_message = 'All right';  // A reason of the stop of script execution.
    $e->_propagate = true;     // Do stop the events cycle (after a current handler of a current event)?
    $e->_reason = '';            // A reason of the stop of the events cycle.
    $GLOBALS['e'] = $e;
    
    // Load application configs
    $config = json_decode(
        file_get_contents(PROJECT_ROOT . '/config/application.config'),
        TRUE
    );
    $GLOBALS['config'] = &$config;
    
    // Route object
    $route = new stdClass;
    $route->main = new stdClass;
    //$route->{pluginName} = new stdClass;
    routeConstructor($route->main);
    $GLOBALS['route'] = $route;
    
    // Services
    // - main app service
    $main_service = array_shift($config['service']);
    require PROJECT_ROOT . $main_service;
    // - plugin services
    foreach ($config['service'] as $plugin => $relative_path) {
        require PROJECT_ROOT . '/plugin/' . $plugin . $relative_path;
    }
    
    // 'after_pre_init' event (will be run all handlers for this event )
    event('after_pre_init'); // check blocked IPs: stop execution
}

/**
 * Application initialize, before starting
 */
function init(){
    // Rise event 'before_init'
    event('before_init'); // 
    
    // Constants
    require_once PROJECT_ROOT.'/lib/consts.php';
    
    // Globals
    //require_once PROJECT_ROOT.'/lib/globals.php';
    
    // Rise event 'after_init'
    event('after_init'); //
}

/**
 * Application starting
 */
function run(){
    // Get route object
    $route = $GLOBALS['route'];
    
    // Rise event 'before_start'
    event('before_start'); // 
        
    // Get route info
    $url = &$_SERVER['REQUEST_URI']; //'/page/about/~/format/json';//
    routeInit($url, $route->main);
    
    // Run appropriate handler
    requestHandler($route->main, PROJECT_ROOT);
    
    // Rise event 'after_finish'
    event('after_finish');
}



/** HELPER FUNCTIONS */

/**
 * Adds an event handler.
 * 
 * @param string $option Possible values is 'On' or 'Off'.
 */
function registerEventHandler(string $event, \Closure $handler)
{
    // Verify handler
    verifyEventHandler($handler);  // if handler unexists, here arise error
    
    // Register Event Handler
    $GLOBALS['event'][$event][] = $handler;
}

/**
 * Executes an event (run all event handlers).
 * 
 * @param string $event 
 */
function event(string $name)
{
    $e = $GLOBALS['e'];
    $event = &$GLOBALS['event'];
    $handlers = &$event[$name];
    
    try {
        // event cycle
        foreach ($handlers as $h) {
            // Run current event handler
            $h($e);
            
            // Do the event cycle continue?
            if (!$e->_propagate) { break; }
        }
        
        // Was the application stopped?
        if ($e->_stop) {
            stop();
        }
    } catch(Exception $ex ) { // make own custom exception
        
    }
}

/**
 * Terminates the script executing.
 * 
 * @return void 
 */
function stop()
{
    // Protection from twice call
    static $called = false; if ($called) { return; } $called = true;
    
    // Global event object
    $e = $GLOBALS['e'];
    
    // Global event array
    $event = &$GLOBALS['event'];
    
    // Event handlers array
    $terminated_event = [
        'stop',
        'response_send',
        'after_response_send'
   ];
    
    // Run handlers
    foreach ($terminated_event as $name) {
        foreach ($event[$name] as $h) {
            $h($e);
        }
    }
}



/** R O U T I N G */

/**
 * Initializes route object with default values
 */
function routeConstructor($route)
{
    $route->uri = '';           // 'ru/api/page/add/~/name/sophiya'
    $route->params = '';        // 'name/sophiya'
    $route->uri_arr = [];       // [0=>'ru',1=>'api',2=>'page',3=>'add'], then [0=>'api',1=>'page',2=>'add'], then [0=>'page',1=>'add']
    $route->params_arr = [];    // ['name'=>'sophiya']
    $route->lang = '';          // 'uk','ru','en','uk_UA',...
    $route->type = '';          // 'api','page','layout','index'
}

/**
 * отрезать после "?"
 * разделить по "~": на uri и params
 * uri -> uri_arr
 * from uri_arr extract lang, write it to $route->lang, delete 'lang' uri_arr element, so uri_arr contains clean uri (clean for detect type and for directly router )
 * uri_arr: detect type, write type to $route->type, delete 'type' uri_arr element
 * ...run appropr handler
 */
function routeInit(string &$request_uri, $route)
{
    // Extracts uri and uri parameters (and writes it to $route array )
    extractUri($request_uri, $route);
    // Extract language
    extractLang($route);
    // Extract type
    extractType($route);
}

/**
 * Выделяет из строки запроса ($_SERVER['REQUEST_URI'] ), н-р:
 * '/api/ru/comment/add/~/count/2?t=1576586464' или
 * '/ru/api/comment/add/~/count/2?t=1576586464'
 * общий компонент пути (note result string has no first and last slash ):
 * 'api/ru/comment/add/~/count/2' или
 * 'ru/api/comment/add/~/count/2',
 * и далее из общего компонента, компонент пути с языком (note result string has no first and last slash ):
 * 'api/ru/comment/add' или
 * 'ru/api/comment/add'
 * и компонент ури-параметров (т.е. параметров переданных, как часть URI-PATH, а не как GET-параметры ):
 * 'count/2' (note result string has no first and last slash ).
 * 
 * @param string $request_uri In most cases it will value of $_SERVER['REQUEST_URI'].
 * @param string $uri Empty (zero length ) string, that is passed to be initialized.
 * @param string $uriprms Empty (zero length ) string, that is passed to be initialized.
 * 
 * @return void
 */
function extractUri(string &$request_uri, $route ) : void
{
    global $config;

    // Get path part (all after domain till '&' of GET parameters )
    $raw_uri = explode('?', $request_uri )[0];    // '/admin/user/add', but not '/admin/user/add?name=john'

    // Remove double '//' and more multi slashes, if exists
    $raw_uri = preg_replace($config['routing']['pattern']['exclude_multi_slash'], '/', $raw_uri );

    // Remove slashes around uri
    $raw_uri = trim($raw_uri, '/' );

    // Site index uri - '/'
    if(empty($raw_uri ) ) {
        $route->uri = '/';
        return;
    }

    // Explode uri and uri parameters
    $prms_pref = '/' . $config['routing']['params_prefix'] . '/'; // must be something like: '/~/', '/-/', etc
    $prms_pos = strpos($raw_uri, $prms_pref ); // seek '/~/' in $raw_uri
    if($prms_pos !== FALSE ) {
        // $raw_uri contains parameters ('/~/' was found )
        $route->uri = substr($raw_uri, 0, $prms_pos ); // uri without last slash
        $route->params = substr($raw_uri, $prms_pos + strlen($prms_pref ) ); // uri parameters without first slash
    } else {
        // $raw_uri has no any parameters
        $route->uri = $raw_uri;
    }
    
    // Drop uri to parts
    $route->uri_arr = explode('/', $route->uri );
    
    // Extract uri params to array
    $route->params_arr = getUriParamsAssoc($route->params);
}

/**
 * 
 */
function getUriParamsAssoc(string &$uriprm) : array
{
    if (strpos($uriprm, '/')) {
        $assoc = [];
        $tmp_array = explode('/', $uriprm );
        $loops = count($tmp_array ); 
        for($i = 0; $i < $loops; $i += 2 ) {
            $assoc[$tmp_array[$i]] = $tmp_array[$i + 1];
        }
        return $assoc;
    }
    return [];
}

/**
 * 
 */
function getUriParamsIndex(string &$uriprm) : array
{
    return explode('/', $uriprm);
}

/**
 * Извлекает компоненту языка из массива $uripart и записывает ее в 
 * переменную $lang. После чего удаляет из массива $uripart элемент с компонентой
 * языка.
 * 
 * В этой конкретной реализации предполагается, что элемент, с компонентой языка,
 * это первый или второй элемент массива $uripart. 
 * 
 * Значения компонента языка:
 * 1) короткое 2 символьное значение 'language', н-р, 'ru', 'en' (http://php.net/manual/ru/function.preg-match.php )
 * 2) полное 5 символьное значение 'language_COUNTRY', н-р, 'ru_RU', 'en_US' (https://developer.mozilla.org/en-US/docs/Web/API )
 * 
 * Массив $uripart - это результат разбиения компонента $uri по слешу, н-р:
 * $uri = 'ru/api/comment/add', тогда $uripart:
 * $uripart[0] = 'ru'
 * $uripart[1] = 'api'
 * $uripart[2] = 'comment'
 * $uripart[3] = 'add'
 * 
 * 
 * 
 */
function extractLang($route ) : void {
    global $config;
        
    // Get Pattern to detect Language Component
    $lang_pattern = &$config['routing']['pattern']['lang'];
    
    // Search language component only among two first elements of 'uri_arr'
    $c = count($route->uri_arr );
    if($c > 2 ) $c = 2;
        
    // Seek language part
    for($i = 0; $i < $c; $i++ ) {
        if(preg_match($lang_pattern, $route->uri_arr[$i] ) === 1 ) {
            $flang_found = TRUE;
            $route->lang = $route->uri_arr[$i];
            array_splice($route->uri_arr, $i, 1 );
        }
    }
    
    // Check 'uri_arr' array, if it is empty, set default language
    if(! $flang_found ) {
        $route->lang = &$config['lang'];
    }
}

/**
 * Extracts request type
 * 
 */
function extractType($route) : void {
    // Detect type of request
    $type = strtolower($route->uri_arr[0]); // it maybe not a type, if it is 'index' request
    if(in_array($type, REQUEST_TYPE ) ) {
        $route->type = $type;
        array_splice($route->uri_arr, 0, 1 );
    } else {
        $route->type = REQUEST_TYPE['INDEX'];
    }
}

/**
 * 
 */
function requestHandler(stdClass $route, string $location ) : void
{
    // Runs an appropriate handler for a request
    include $location . '/_' . $route->type . FEXT;
}



/** C O M M O N */

/**
 * 
 */
function getContentTo(string $path, &$content)
{
    // Get content
    ob_start();
    include $path;
    $content = ob_get_contents();
    ob_end_clean();
}

/**
 * 
 */
function getPagePath(string $type, $page, $layout = null)
{
    // Prefix
    $path = PROJECT_ROOT;
    
    // Name
    switch ($type) {
        case REQUEST_TYPE['API']:
            $path .= ('/' . REQUEST_TYPE['API'] . FG . $page . '.php');
            break;
        case REQUEST_TYPE['LAYOUT']:
            $path .= ('/' . REQUEST_TYPE['LAYOUT'] . FG . $layout . '.php');
            break;
        case REQUEST_TYPE['PART']:
            if (empty($layout)) {
                $layout = getLayoutName();
            }
            $path .= ('/' . REQUEST_TYPE['PART'] . FG . $layout . FG . $page . '.php');
            break;
        case REQUEST_TYPE['PAGE']:
            $path .= ('/' . REQUEST_TYPE['PAGE'] . FG . $page . '.php');
            break;
        case REQUEST_TYPE['STYLE']:
            if (empty($layout)) {
                $type_name = REQUEST_TYPE['PAGE'];
                $layout_or_page = $page;
            } else {
                $type_name = REQUEST_TYPE['LAYOUT'];
                $layout_or_page = $layout;
            }
            $path .= ('/css/' . $type_name . FG . $layout_or_page . '.css');
            break;
        case REQUEST_TYPE['SCRIPT']:
            if (empty($layout)) {
                $type_name = REQUEST_TYPE['PAGE'];
                $layout_or_page = $page;
            } else {
                $type_name = REQUEST_TYPE['LAYOUT'];
                $layout_or_page = $layout;
            }
            $path .= ('/js/' . $type_name . FG . $layout_or_page . '.js');
            break;
    }
    
    // Get result
    return $path;
}


/**
 * Makes response as string in JSON format.
 * 
 * @param bool $result 
 * @return string Response JSON string.
 */
function getResponseJson(bool $result, string $message, &$data) : string
{
    return json_encode([
        'result' => $result,
        'message' => $message,
        'data' => $data
   ]);
}
