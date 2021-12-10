<?php
    try {
        if (!defined('HOST')) {
            // define('HOST','localhost');
            //define('PORT','port=41890');
            // define('USER','root');
            // define('PASS','Cupom@System123');
    
            define('HOST','200.143.59.36');
            define('PORT','port=41890');
            define('USER','ecommercecupom');
            define('PASS','Cupom@System123');
        }

        if (!defined('IMAGE')) {
            define('IMAGE','http://cupomautomacao.com/Ecommerce/IMG/Product/1/');
            // define('IMAGE','../Ecommerce/IMG/Product/1/');
        }
    
        date_default_timezone_set('Brazil/east');
    
        $conn = new PDO('mysql:host='.HOST.';'.PORT.';dbname=ecommerce', USER, PASS);
    
        if (!defined('TOKEN'))
            define('TOKEN', 'APP_USR-6689606040344275-021716-92f66882f7340d789e60e5bcf5427dce-152498470');
    } catch (PDOException $e) {
        echo $e;
        die();
    }
?>