<?php
/**
 * Created by PhpStorm.
 * User: JimmDiGriz
 */

class Psr4Autoloader
{
    /**
     * @var array
     */
    protected $prefixes = array();

    /**
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * @param string $prefix The namespace prefix.
     * @param string $base_dir A base directory for class files in the
     * namespace.
     * @param bool $prepend If true, prepend the base directory to the stack
     * instead of appending it; this causes it to be searched first rather 
     * than last.
     * @return void
     */
    public function addNamespace($prefix, $base_dir, $prepend = false)
    {
        $prefix = trim($prefix, '\\') . '\\';
        
        $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        
        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = array();
        }
        
        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $base_dir);
        } else {
            array_push($this->prefixes[$prefix], $base_dir);
        }
    }

    /**
     * @param string $class The fully-qualified class name.
     * @return mixed The mapped file name on success, or boolean false on
     * failure.
     */
    public function loadClass($class)
    {
        $prefix = $class;
        
        while (false !== $pos = strrpos($prefix, '\\')) {
            
            $prefix = substr($class, 0, $pos + 1);
            
            $relative_class = substr($class, $pos + 1);
            
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }
            
            $prefix = rtrim($prefix, '\\');
        }
        
        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @param string $prefix The namespace prefix.
     * @param string $relative_class The relative class name.
     * @return mixed Boolean false if no mapped file can be loaded, or the
     * name of the mapped file that was loaded.
     */
    protected function loadMappedFile($prefix, $relative_class)
    {
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }
        
        foreach ($this->prefixes[$prefix] as $base_dir) {
            $file = $base_dir
                . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class)
                . '.php';
            
            if ($this->requireFile($file)) {
                return $file;
            }
        }
        
        return false;
    }

    /**
     * @param string $file The file to require.
     * @return bool True if the file exists, false if not.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}