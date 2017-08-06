<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Base\DB;

use Core\Application;
use Core\Base\DB\Validation\Validator;
use Core\Helpers\ArrayHelper;
use Core\Interfaces\ToArrayInterface;

/**
 * @todo: add validations, events, iterators, cache, etc.
 * @todo: make query builder
 * @todo: remove mysql dependency, add database providers
 * @todo: add join
 * 
 * @property bool $isNewRecord
 */
abstract class BaseModel implements ToArrayInterface
{
    const ASCENDANT = 'asc';
    const DESCENDANT = 'desc';
    
    const ONE = 0;
    
    /**@var string $query*/
    private $query = '';
    /**@var array $params*/
    private $params;
    /**@var \Core\Components\DB\DBConnection $db*/
    private $db;
    /**@var array $select*/
    private $select = [];
    /**@var array $order*/
    private $order = [];
    /**@var array $relations*/
    private $relations = [];
    /**@var array $with*/
    private $with = [];
    /**@var int $limit*/
    private $limit;
    /**@var int offset*/
    private $offset;
    
    public $isNewRecord = false;
    
    private function __construct()
    {
        $this->db = Application::$app->get('db');
        
        $this->isNewRecord = true;
    }
    
    public static function model()
    {
        return new static;
    }
    
    /**
     * @return string
     */
    public abstract function getTableName();
    public abstract function getColumnMap(): array;
    
    public function relations(): array
    {
        return [
            
        ];
    }
    
    public function rules(): array
    {
        return [
            
        ];
    }
    
    public function beforeSave()
    {
        $this->validate();
    }
    
    public function validate()
    {
        foreach ($this->rules() as $rule) {
            if (!isset($rule['class'])) {
                continue;
            }
            
            $validator = new $rule['class'];
            
            if (!($validator instanceof Validator)) {
                continue;
            }
            
            /**@var \Core\Base\DB\Validation\Validator $validator*/
            
            if (isset($rule['options'])) {
                foreach ($rule['options'] as $optionName => $optionValue) {
                    $validator->{$optionName} = $optionValue;
                }
            }
            
            if (!$validator->run($this)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * @return string
     */
    public abstract function getPrimaryKey();
    
    public function select($value) {
        if (!$this->select) {
            $this->select = array();
        }
        
        if (!is_array($value)) {
            if (strpos($value, ',') !== false) {
                $value = array_map(function($item) {
                    return trim($item);
            }, explode(',', $value));
            }
            
            $value = array($value);
        }

        $this->select = ArrayHelper::merge($this->select, $value);
    }

    /**
     * @param array $fields = []
     */
    public function save($fields = [])
    {
        $this->beforeSave();
        
        if ($this->isNewRecord) {
            $this->insert($fields);
        } else {
            $this->update($fields);
        }
    }
    
    /**
     * @param array $fields = []
     */
    public function insert($fields = [])
    {
        $setter = $this->buildSetterStatement($fields);
        
        $sql = "insert into `{$this->getTableName()}` {$setter['query']}";

        $this->db->execute($sql, $setter['params']);
    }

    /**
     * @param array $fields = []
     */
    public function update($fields = [])
    {
        $setter = $this->buildSetterStatement($fields);
        
        $sql = "update `{$this->getTableName()}` {$setter['query']}";
        
        if ($this->getPrimaryKey()) {
            $condition = $this->buildPrimaryKeyCondition();
            
            $sql .= " where {$condition['query']}";
            
            $setter['params'] = ArrayHelper::merge($setter['params'], $condition['params']);
        }

        $this->db->execute($sql, $setter['params']);
    }

    /**
     * @param array $fields = []
     *
     * @return string
     */
    private function buildSetterStatement($fields = [])
    {
        if (count($fields) == 0) {
            $fields = $this->getColumnMap();
        }

        $params = [];
        $query = 'set ';
        
        $fieldsCount = count($fields);
        
        for ($i = 0; $i < $fieldsCount; $i++) {
            $field = $fields[$i];
            
            $query .= "`{$field}` = :{$field}";
            $params[":{$field}"] = $this->{$field};

            if (($i + 1) < $fieldsCount) {
                $query .= ", ";
            }
        }
        
        return [
            'params' => $params,
            'query' => $query,
        ];
    }
    
    public function delete()
    {
        throw new \Exception('NotImplemented');
    }
    
    /**
     * @return int
     */
    public function count()
    {
        $sql = 'select count(*) from `' . $this->getTableName() . '`';

        return $this->db->execute($sql, [])[0][0];
    }
    
    /**
     * @return static
     */
    public function find()
    {
        $this->query = $this->buildSelectStatement() 
             . ' limit ' . $this->limit . ($this->offset ? ' offset ' . $this->offset : '');
        
//        echo $this->query; die();

        return $this->populate($this->db->execute($this->query, $this->params)) ?? [];
    }
    
    /**
     * return static[]
     */
    public function findAll()
    {
        return $this->populate($this->db->execute($this->buildSelectStatement(), $this->params));
    }
    
    /**
     * @return string
     */
    private function buildSelectStatement()
    {
        return 'select '
        . (count($this->select) == 0 ? '*' : implode(',', $this->select))
        . ' from `' . $this->getTableName() . '`'
        . ($this->query ? $this->query : '')
        . (count($this->order) > 0 ?' order by ' . $this->buildOrderStatement() : '');
    }
    
    /**
     * @return string
     */
    private function buildPrimaryKeyCondition()
    {
        $primaryKey = $this->getPrimaryKey();
        
        $fields = explode(',', $primaryKey);
        
        $condition = '';
        $params = [];
        
        $fieldsCount = count($fields);
        
        if ($fieldsCount > 1) {
            for ($i = 0; $i < $fieldsCount; $i++) {
                $field = $fields[$i];
                
                $condition .= "`{$field}` = :{$field}";
                $params[":{$field}"] = $this->{$field};
                
                if (($i + 1) < $fieldsCount) {
                    $condition .= ' and ';
                }
            }
        } else {
            $condition = "`{$primaryKey}` = :{$primaryKey}";
            $params[":{$primaryKey}"] = $this->{$primaryKey};
        }
        
        return [
            'query' => $condition,
            'params' => $params,
        ];
    }
    
    /**
     * @param string $value
     * @param string $order
     * 
     * @throws \Exception
     * 
     * @return static
     */
    public function orderBy($value, $order = self::DESCENDANT)
    {
        if (!in_array($order, array(self::DESCENDANT, self::ASCENDANT))) {
            throw new \Exception('NotSupportedOrder[' . $order . ']');
        }
        
        if (!$this->order) {
            $this->order = array();
        }
        
        $this->order[] = $value . ' ' . $order;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function buildOrderStatement()
    {
        return count($this->order) > 0 ? implode(',', $this->order) : '';
    }
    
    private function populate($models)
    {
        $result = array();
        
        foreach ($models as $model) {
            $instance = new static;
            
            foreach ($this->getColumnMap() as $name) {
                $instance->{$name} = $model[$name];
                $instance->isNewRecord = false;
            }
            
            $result[] = $instance;
        }
        
        return $result;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $operator
     * @param string $glue
     *
     * @return static
     */
    public function where($name, $value, $operator = '=', $glue = 'and')
    {
        if (!$this->query) {
            $this->query = ' where ';
        } else {
            $this->query .= ' ' . $glue . ' ';
        }
        
        $bindingParam = ':' . $name . count($this->params);
        
        $this->query .= '(`' . $this->getTableName() . '`.`' . $name . '` ' . $operator . ' ' . $bindingParam . ')';
        
        $this->params[$bindingParam] = $value;
        
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $operator
     *
     * @return static
     */
    public function andWhere($name, $value, $operator = '=')
    {
        return $this->where($name, $value, $operator);
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $operator
     *
     * @return static
     */
    public function orWhere($name, $value, $operator = '=')
    {
        return $this->where($name, $value, $operator, 'or');
    }
    
    /**
     * @param int $limit
     * 
     * @return static
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        
        return $this;
    }

    /**
     * @param int $offset
     *
     * @return static
     */
    public function offset($offset)
    {
        $this->offset = $offset;

        return $this;
    }
    
    /**
     * @param int $page
     * 
     * @return static
     */
    public function page($page)
    {
        if ($page > 0) {
            $this->offset = $this->limit * ($page - 1);
        }
        
        return $this;
    }
    
    /**
     * @param array $data
     */
    public function load($data)
    {
        foreach ($this->getColumnMap() as $field) {
            if (!isset($data[$field])) {
                continue;
            }
            
            $this->{$field} = $data[$field];
        }
    }
    
    public function __get($name) 
    {
        if (!in_array($name, $this->getColumnMap())) {
            throw new \Exception("UndefinedProperty[{$name}]");
        }
        
        if (!isset($this->{$name})) {
            return null;
        }
        
        return $this->{$name};
    }
    
    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }
    
    public function __toString()
    {
        $result = '| ';
        
        foreach ($this->getColumnMap() as $field) {
            $result .= $field . ' = ' . $this->{$field} . ' | ';
        }
        
        return $result;
    }

    public function toArray(): array
    {
        $result = [];

        foreach ($this->getColumnMap() as $field) {
            $result[$field] = $this->{$field};
        }

        return $result;
    }
}