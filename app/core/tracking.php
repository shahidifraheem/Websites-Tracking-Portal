<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden - Direct access not allowed.";
    exit;
}

class Tracking
{
    /**
     * Constructor: Tracks visitors by IP Address and performs data cleanup.
     */
    public function __construct()
    {
        // Check if the URL contains "dashboard" or "admin" keywords
        $url = $_SERVER['REQUEST_URI'];
        if (str_contains($url, 'dashboard') || str_contains($url, 'admin')) {
            return; // Don't track if the URL contains keywords
        }

        // Get the visitor's IP address
        $visitor_ip = $_SERVER['REMOTE_ADDR'];

        // Check if the IP address already exists
        if (!$this->ipExists($visitor_ip)) {
            // Insert the IP address into the database
            $this->insertVisitorIp($visitor_ip);
        }

        // Perform data cleanup by deleting records older than 2 months
        $this->cleanupData();
    }

    /**
     * Check if the IP address already exists in the database.
     *
     * @param string $ip The visitor's IP address
     * @return bool True if the IP address exists, false otherwise
     */
    private function ipExists($ip)
    {
        $data = array();
        $db = Database::newInstance();
        $query = 'SELECT id FROM gcs_visitors WHERE ip_address = :ip_address LIMIT 1';
        $data = [':ip_address' => $ip];
        $result = $db->read($query, $data);

        return !empty($result);
    }

    /**
     * Insert the visitor's IP address into the database.
     *
     * @param string $ip The visitor's IP address
     */
    private function insertVisitorIp($ip)
    {
        $data = array();
        $db = Database::newInstance();
        $query = 'INSERT INTO gcs_visitors (`ip_address`, `date`) VALUES (:ip_address, :date)';
        $data = [
            ':ip_address' => $ip,
            ':date' => date('Y-m-d h:i:s a')
        ];
        $db->write($query, $data);
    }

    /**
     * Perform data cleanup by deleting records older than 2 months.
     */
    private function cleanupData()
    {
        $data = array();
        $db = Database::newInstance();

        $twoMonthsAgo = date('Y-m-d H:i:s', strtotime("-" . TRACKING_HISTORY . " months"));
        $query = 'DELETE FROM gcs_visitors WHERE date < :history_date';
        $data = [':history_date' => $twoMonthsAgo];
        $db->write($query, $data);
    }
}

// Create an instance of the Tracking class
$tracking = new Tracking();
