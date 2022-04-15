<?php

  class DBConnection {
    public static function getDB() {
        try {
            $options = [
                PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
            ];
    
            $dbConfig = array(
                'development' => array(
                    'user' => 'root',
                    'password' => 'root',
                    'host' => 'localhost',
                    'database' => 'zoom',
                    'port' => 8889
                ),
        
                'production' => array(
                    'user' => 'corretio_cargo',
                    'password' => 'Iloveodunayo123',
                    'host' => 'localhost',
                    'database' => 'corretio_cargo',
                    'port' => 3306
                )
            );
            
            $enviroment = $_SERVER['HTTP_HOST'] == 'localhost:8888' ? 'development' : 'production';
            $dbConfig = $dbConfig[$enviroment];
            $pdo = new PDO('mysql:host=' . $dbConfig['host'] . ";port=" . $dbConfig['port'] . ';dbname=' . $dbConfig['database'], 
            $dbConfig['user'], $dbConfig['password'], $options);
            return $pdo;
    
    } catch(Exception $e) {
        var_dump($e); exit;
        ErrorMail::LogError($e);
    }
    }
  }
?>
