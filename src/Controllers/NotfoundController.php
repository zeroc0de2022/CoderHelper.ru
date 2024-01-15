<?php
/**
 * Description of NotfoundController
 * @author zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
 * @date 2021-04-08
 */

namespace Coderhelper\Controllers;
class NotfoundController {
    
    /**
     * NotFound action page
     */
    public static function actionNotfound(): bool
    {

        $metadata = [
            'title' => 'Страница не существует!',
            'description' => 'Страница не существует.',
            'keywords' => 'Not found, программирование, языки программирования'
        ];
        header( 'refresh: 10; url=/' ); 
        require_once(PUBLIC_HTML.'/views/notfound/index.php');
        return true;
    }
    
    

    
    
}
