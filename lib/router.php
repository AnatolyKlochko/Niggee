<?php
return [
    'Direct' => function($route) : bool {
        // Termin 'page' means any thing: page, api handler, page handler, ...
        $result['page'] = '/' . $route->type . '/' . implode('/', $route->uri_arr);
        
    },

    'Index' => function($route) : bool {
        // Termin 'page' means any thing: page, api handler, page handler, ...
        
        // Page name
        $page = implode('-', $route->uri_arr);
                
        // Get info about page
        $pri = (function($name) {
            // A source can be MySQL memory table, Reddis, etc.
            $route  = json_decode(
                file_get_contents(PROJECT_ROOT . '/config/index.route.config'),
                TRUE
            );
            
            // I'm any...
            return $route[$name];
        })($page); // ['layout'=>'layout name','is_secure'=>bool]
        // Verify: is array empty (page is't matched)?
        if (empty($pri)) {
            return false;
        }
        
        // Fill route object
        $route->page = $page;
        $route->layout = $pri['layout'];
        $route->is_secure = $pri['is_secure'];
        
        // Matching is successfull!
        return true;
    },

    'Page' => function($route) : bool {
        // Termin 'page' means any thing: page, api handler, page handler, ...
        
        // Page name
        $page = implode('-', $route->uri_arr);
                
        // Get info about page
        $pri = (function($name) {
            // A source can be MySQL memory table, Reddis, etc.
            $route  = json_decode(
                file_get_contents(PROJECT_ROOT . '/config/index.route.config'),
                TRUE
            );
            
            // I'm any...
            return $route[$name];
        })($page); // ['layout'=>'layout name','is_secure'=>bool]
        // Verify: is array empty (page is't matched)?
        if (empty($pri)) {
            return false;
        }
        
        // Fill route object
        $route->page = $page;
        $route->is_secure = $pri['is_secure'];
        
        
        // Matching is successfull!
        return true;
    },
    
    
    'Pattern' => function($route) : bool {
    
    },

            
    'Category' => function($route) : bool {
    
    },

            
    'Product' => function($route) : bool {
    
    },
];
