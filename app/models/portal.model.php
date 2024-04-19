<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header('HTTP/1.1 403 Forbidden');
    echo '403 Forbidden - Direct access not allowed.';
    exit;
}

/**
 * Manage users data
 */
class Portal
{
    private $error = "";

    private function get_random_string_max($length)
    {
        $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $text = '';
        $length = rand(8, $length);

        for ($i = 0; $i < $length; $i++) {
            $random = rand(0, 61);
            $text .= $array[$random];
        }
        return $text;
    }

    /**
     * Add new property
     *
     * @return void
     */
    public function add_property($POST)
    {
        $data = array();
        $DB = Database::newInstance();
        $data['url']   = isset($POST['url']) && $POST['url'] != "" ? sanitize_url($POST['url']) : "";
        $data['date'] = date("Y-m-d H:i:s a");

        if (empty($data['url'])) {
            $this->error .= 'Please enter valid url <br>';
        }

        /**
         * Check if url already exists
         * 
         */
        $sql = "SELECT * FROM sh_properties WHERE url = :url LIMIT 1";
        $arr['url'] = $data['url'];
        $check = $DB->read($sql, $arr);
        if (is_array($check)) {
            $this->error .= "Property is already exist. <br>";
        }

        if ($this->error == "") {
            $query = "INSERT INTO sh_properties (`url`, date) VALUES (:url, :date)";
            $result = $DB->write($query, $data);

            if ($result) {
                $_SESSION['success'] = $data['url'] . " property added successfully";
                header("Location: " . ROOT . "dashboard/properties");
                die;
            }
        }

        $_SESSION['error'] = $this->error;
    }

    /**
     * Update User
     *
     * @return void
     */
    public function update_property($POST, $id)
    {
        $data = array();
        $data['id'] = sanitize_int($id);
        $DB = Database::newInstance();
        $data['url']   = isset($POST['url']) && $POST['url'] != "" ? sanitize_url($POST['url']) : "";
        $data['updated_at'] = date("Y-m-d H:i:s a");

        if (empty($data['url'])) {
            $this->error .= 'Please enter valid url <br>';
        }

        /**
         * Check if url already exists
         * 
         */
        $sql = "SELECT * FROM sh_properties WHERE url = :url LIMIT 1";
        $arr['url'] = $data['url'];
        $check = $DB->read($sql, $arr);
        if (is_array($check)) {
            $this->error .= "Property is already exist. <br>";
        }

        if ($this->error == "") {
            $query = "UPDATE sh_properties SET url = :url, updated_at = :updated_at WHERE id = :id";
            $result = $DB->write($query, $data);

            if ($result) {
                $_SESSION['success'] = $data['url'] . " property updated successfully";
                header("Location: " . ROOT . "dashboard/properties");
                die;
            }
        }
        $_SESSION['error'] = $this->error;
    }


    /**
     * properties - return all properties
     * 
     * @return properties List
     */
    public function properties()
    {
        $data = array();
        $DB = Database::newInstance();
        $sql = 'SELECT * FROM sh_properties ORDER BY id DESC';
        $result = $DB->read($sql, $data);
        if (is_array($result)) {
            return $result;
        }
    }


    /**
     * property - return all property
     * 
     * @return property List
     */
    public function get_property($id)
    {
        $data = array();
        $data["id"] = sanitize_int($id);
        $DB = Database::newInstance();
        $sql = 'SELECT * FROM sh_properties WHERE id = :id LIMIT 1';
        $result = $DB->read($sql, $data);

        if (is_array($result)) {
            return $result;
        }
    }

    /**
     * Delete property
     * 
     * @return property List
     */
    public function delete_property($id)
    {
        $DB = Database::newInstance();
        $id = sanitize_int($id);
        $DB->read("DELETE FROM sh_properties WHERE id=:id", ['id' => $id]);
        header('Location: ' . ROOT . 'dashboard/properties');
        die;
    }

    /**
     * Logout - remove current user from the sessions
     * 
     * @return Website
     */
    public function logout()
    {
        if (isset($_SESSION['user_url'])) {
            unset($_SESSION['user_url']);
        }
        header('Location: ' . ROOT);
        die;
    }
}
