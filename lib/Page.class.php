<?php
/**
 * 
 * 
 */

namespace Niggee;



class Page {
    
    /**
     * Определяет является ли текущий пункт меню активным.
     */
    public static function isActiveMenuItem(string $item_uri ){
        // Assume main menu items can't have URI params, like /user/list/~/page/2, but only clean URI: /user/list
        return ($item_uri === RAW_URI );
    
    }
    
    /***/
    public static function verifyCookieEnabled() {
        ;


        /* Name indicating verified cookie */
        $vcname = Config::$prm['VERIFICATION_COOKIE_NAME'];
        
        if(array_key_exists($vcname, $_COOKIE ) ){
            
            /* After user enable cookie and press button 'Cookie is enabled, continue', in brauser they was not installed, 
             * and testing process will start again, and until testing will set $_SERVER['QUERY_STRING']=='/path/wherefrom/user/came', to delete it: */
            if(isset($_GET['continue'] ) ){
                header('Location: '.REQUEST_PROTOCOL.'://'.$_SERVER['HTTP_HOST'].$_GET['continue']);
                exit;
            }
            
            return;
            
        } elseif(RAW_URI === Config::$prm['VERIFICATION_COOKIE_URI'] |
                RAW_URI === '/'.LANG.Config::$prm['VERIFICATION_COOKIE_URI'] ){
            // Проверочные куки уже отправлялись, но в запросе тестовые куки отсутствуют:
            // перенаправляем пользователя на тупиковую страницу с сообщением включить куки.
            
            header('Location: '.REQUEST_PROTOCOL.'://'.$_SERVER['HTTP_HOST'].'/'.LANG.Config::$prm['VERIFICATION_COOKIE_FAIL_URI'].'?'.$_SERVER['QUERY_STRING'] );
            /* Return from script */
            exit;
            
        } elseif(RAW_URI === Config::$prm['VERIFICATION_COOKIE_FAIL_URI'] |
                RAW_URI === '/'.LANG.Config::$prm['VERIFICATION_COOKIE_FAIL_URI'] ){
            // тупиковая страница: пользователь будет оставаться на этой странице пока не включит поддержку куки
            
            // страница с сообщением включить куки
            $page = PROJECT_ROOT . RELATIVE_PAGE_DIR . '/verification_cookie_fail.php';
            if(!is_file($page ) ) $page = PROJECT_ROOT . RELATIVE_PAGE_DIR . '/verification_cookie_fail.php';
            
            $layout = PROJECT_ROOT . RELATIVE_LAYOUT_DIR . '/verification/_.html';
            // Render page
            include $layout;
            /* Return from script */
            exit;
            
        } else {
            // Проверочные куки еще НЕ УСТАНАВЛИВАЛИСЬ
            
            setcookie($vcname, '1', time() + 60*60*24*365, '/');
            header('Location: '.REQUEST_PROTOCOL.'://'.$_SERVER['HTTP_HOST'].'/'.LANG.Config::$prm['VERIFICATION_COOKIE_URI'].'?continue='.REQUEST_URI );
            exit;
                
        }
        
    }
    /***/
    public static function verifyJavaScriptEnabled() {
        ;
        
        /* Name indicating verified JavaScript */
        $vjsname = Config::$prm['VERIFICATION_JAVASCRIPT_COOKIE_NAME'];
        if(array_key_exists($vjsname, $_COOKIE ) ){
            /* Testing cookie is present, JS is enabled */
            return;
        } else {
            // On page top is present js script, which set cookie and redirect page to page where from user came,
            // if JS is not enabled, user will see message for JS enabled.
            $page = PROJECT_ROOT . RELATIVE_PAGE_DIR . '/verification_js_fail.php';
            if(!is_file($page ) ) $page = PROJECT_ROOT . RELATIVE_PAGE_DIR . '/verification_js_fail.php';
            $layout = PROJECT_ROOT . RELATIVE_LAYOUT_DIR . '/verification/_.html';
            /* Render page */
            include $layout;
            /* Return from script */
            exit;
        }
    }
    
    
    /**
     * 
     */
    public static function printIcon(string $path, string $img_type ){
        $icon_file = PROJECT_ROOT . '/' . $path;
        if(is_file($icon_file ) ){
            $icon_binary = fread(fopen($icon_file, "r" ), filesize($icon_file ) );
            echo '<link rel="shortcut icon" href="data:image/'.$img_type.';base64,' . base64_encode($icon_binary ) . '" />';
        }
    }
    
    /**
     * 
     */
    public static function printCss(string $path ){
        $css_file = PROJECT_ROOT . '/' . $path;
        if(is_file($css_file ) ){
            /* Make css script enable for PHP code */
            ob_start(); include $css_file; $css = ob_get_clean();
            echo '<style>' . $css .'</style>';
        }
    }
    
    /**
     * 
     */
    public static function printJs(string $path ){
        $js_file = PROJECT_ROOT . '/' . $path;
        if(is_file($js_file ) ){
            /* Make css script enable for PHP code */
            ob_start(); include $js_file; $js = ob_get_clean();
            echo '<script>' . $js . '</script>';
        }
    }
    /**
     * 
     */
    public static function printLayoutJs(){
        global $route;
        $js_file = PROJECT_ROOT . RELATIVE_JS_DIR . '/layout/' . $route['layout'] . '.js';
        if(is_file($js_file ) ){
            /* Make css script enable for PHP code */
            ob_start(); include $js_file; $js = ob_get_clean();
            echo '<script>' . $js . '</script>';
        }
    }
    /**
     * 
     */
    public static function printPageJs(){
        global $route;
        $js_file = PROJECT_ROOT . RELATIVE_JS_DIR . '/page/' . basename($route['page'], '.php' ) . '.js';
        if(is_file($js_file ) ){
            /* Make css script enable for PHP code */
            ob_start(); include $js_file; $js = ob_get_clean();
            echo '<script>' . $js . '</script>';
        }
    }
}