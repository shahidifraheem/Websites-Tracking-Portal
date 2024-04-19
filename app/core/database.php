<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden - Direct access not allowed.";
    exit;
}

class Database
{
    public static $con;

    /**
     * Initializing Database Connection
     * 
     */
    public function __construct()
    {
        try {
            $string = DB_TYPE . ":host=" . DB_HOST . ";dbname=" . DB_NAME;
            self::$con = new PDO($string, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Database Instance
     *
     * @return DB_Connection
     */
    public static function getInstance()
    {
        if (self::$con) {
            return self::$con;
        }

        /**
         * Initiating class from within the function
         * 
         * @return DB_object
         */
        return $instance = new self();
    }

    /**
     * Database Instance
     * Use this if getInstance() will not work
     * 
     * @return DB_Connection
     */
    public static function newInstance()
    {
        return $instance = new self();
    }

    /**
     * This function use to read data from database
     *
     * @return data
     */
    public function read($query, $data = array())
    {
        // Old Approach
        /* $stmt = self::$con->prepare($query);
        $result = $stmt->execute($data);

        if ($result) {
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            if (is_array($data) && count($data) > 0) {
                return $data;
            }
        }
        return false; */

        // New Approach
        try {
            $stmt = self::$con->prepare($query);
            $result = $stmt->execute($data);

            if ($result) {
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);
                if (is_array($data) && count($data) > 0) {
                    return $data;
                }
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * This function use to write in database
     *
     * @return true/false
     */
    public function write($query, $data = array())
    {
        // Old Approach
        /* $stmt = self::$con->prepare($query);
        $result = $stmt->execute($data);

        if ($result) {
            return true;
        }
        return false; */

        // New Approach
        try {
            $stmt = self::$con->prepare($query);
            $result = $stmt->execute($data);

            if ($result) {
                return true;
            } else {
                return false; // Additional check in case $result is not explicitly true
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Fetch results
     *
     * @param [type] $query
     * @param array $data
     * @return void
     */
    public function fetch_result($query, $data = array())
    {
        // Old Approach
        /* $stmt = self::$con->prepare($query);
        $result = $stmt->execute($data);


        if ($result) {
            $row = $stmt->fetch();
            if ($row) {
                return $row;
            }
        }
        return false; */

        // New Approach
        try {
            $stmt = self::$con->prepare($query);
            $result = $stmt->execute($data);

            if ($result) {
                $row = $stmt->fetch();
                if ($row) {
                    return $row;
                }
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
