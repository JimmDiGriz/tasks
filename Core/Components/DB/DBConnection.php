<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Components\DB;

use PDO;

/**
 * @todo: remove mysql dependency
 */
class DBConnection
{
    /**@var PDO $connection*/
    private $connection;
    private $config;
    
    public function __construct($config) 
    {
        $this->config = $config;
    }
    
    /**
     * @return \PDO
     */
    public function getConnection()
    {
        if (!$this->connection) {
            $this->connection = new PDO('mysql:host=' . $this->config['host']. ';dbname=' . $this->config['db_name'] , $this->config['user'], $this->config['password']);
        }
        
        return $this->connection;
    }
    
    /**
     * @param string $sql
     * @param array $params
     * 
     * @return array
     */
    public function execute($sql, $params): array
    {
        $statement = $this->getConnection()->prepare($sql);
        
        $result = $statement->execute($params);
        
        if (!$result) {
            return [];
        }
        
        $result = $statement->fetchAll();
        
        return $result;
    }
}