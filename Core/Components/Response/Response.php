<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

namespace Core\Components\Response;

use Core\Values\HttpContentType;
use Core\Values\HttpStatusCode;

class Response
{
    private $statusCode = HttpStatusCode::OK; 
    private $contentType = HttpContentType::TEXT;
    private $data = '';
    
    public function __construct()
    {
        
    }
    
    /**
     * @param string $name
     * @param string $value
     * 
     * @throws \Exception
     */
    public function addData($name, $value) {
        try {
            if (is_array($this->data)) {
                $this->data[$name] = $value;
            } else if (is_object($this->data)) {
                $this->data->{$name} = $value;
            } else {
                $this->data = array($name => $value);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }
    
    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    public function getContentType(): string
    {
        return $this->contentType;
    }
    
    /**
     * @param int $value
     * 
     * @throws \Exception
     */
    public function setStatusCode($value)
    {
        if (!is_int($value)) {
            throw new \Exception('Invalid Status Code', HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
        
        $this->statusCode = $value;
    }
    
    /**
     * @param string $value
     */
    public function setContentType($value)
    {
        $this->contentType = $value;
    }
    
    public function setHeaders()
    {
        http_response_code($this->statusCode);
        header('Content-Type: ' . $this->contentType);
    }
    
    /**
     * @param string $header
     */
    public function addHeader($header)
    {
        header($header);
    }
    
    public function __toString()
    {
        if (is_string($this->data)) {
            return $this->data;
        }
        
        try {
            if ($this->contentType == HttpContentType::JSON) {
                return json_encode($this->data);
            }

            return (string)$this->data;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }
}