<?php

namespace netvod\db;
use PDO;
ConnexionFactory::setConfig( 'db.config.ini' );

class ConnexionFactory{
    public static $db = null;
    public static $config =[];

    public static function setConfig($iniFile): void{
        self::$config = parse_ini_file($iniFile);
    }

    public static function makeConnection(): ?PDO
    {
        if(self::$db === null){
            $dsn = self::$config['driver'].
                ':host='.self::$config['host'].
                ';dbname='.self::$config['dataBase'];
            self::$db = new PDO($dsn, self::$config['username'], self::$config['password'],[
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ]);
            self::$db->prepare("SET NAMES 'utf8'")->execute();
        }
        return self::$db;
    }
}