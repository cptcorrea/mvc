<?php


namespace App\Controller;

use App\Utils\View;

Class Page {

     /**
     * Metodo responsavel por retornar a renderização da pagina de login
     * @param Request $request
     * @return string 
     */
    private static $modules = [
        'cliente' => [
            'label' => 'cliente',
            'link' => URL.'/client'
        ],
        'usuario' => [
            'label' => 'usuario',
            'link'  => URL.'/users'
        ]
    ];

       
   
    /**
     * Metodo responsavel por retornar o conteudo (view) da estrutura generica de pagina do paineç
     * @param string $title
     * @param string $content
     * @return string 
     */
    public static function getPage($title,$content) {
        return View::render('page',[
            'title' => $title,
            'content' => $content
            ]);
    }
    
    /**
     * Metodo responsavel por retornar a renderização da pagina de login
     * @param Request $request
     * @return string 
     */
    private static function getMenu($currentModule) {
        
        $links = '';

                        
        foreach(self::$modules as $key=>$module) {
            $links .= View::render('menu/link',[
                'label' => $module['label'],
                'link' => $module['link'],
                'current' => $key == $currentModule ? 'text-danger' : ''
            ]);
            
        }

        

        //RETORNA A RENDERIZACAO DO MENU  
        return View::render('menu/box',[
            'links' => $links
            ]);
    }
    
    /**
     * Metodo responsavel por renderizar a view do painel com conteudo dinamicos
     * @param string $title
     * @param string $content
     * @param string $currentModule
     * @return string 
     */
    public static function getPanel($title,$content,$currentModule) {
        $contentPanel = View::render('panel',[
            'menu' => self::getMenu($currentModule),
            'content' => $content
            ]);
        
        //RETORNA A PAGINA RENDERIZADA
        return self::getPage($title,$contentPanel);
    }
    
    /**
     * Metodo responsavel por retornar a renderização da pagina de login
     * @param Request $request
     * @return string 
     */
    public static function getPagination($request,$objPagination) {
        $pages = $objPagination->getPages();
        
        if(count($pages) <= 1) return "";
        
        $links = "";
        
        $url = $request->getRouter()->getCurrentUrl();
        
        $queryParams = $request->getQueryParams();
        
        foreach($pages as $page) {
            $queryParams['page'] = $page['page'];
            
            $link = $url."?".http_build_query($queryParams);
            
            $links .= View::render('pagination/link', [
            'page' => $page['page'],
            'link' => $link,
            'active' => $page['current'] ? 'active' : ''
            ]);
        
            
        }
        
        return View::render('pagination/box', [
            'links' => $links
            ]);
        
    }
    
}