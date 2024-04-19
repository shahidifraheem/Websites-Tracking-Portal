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
class Authenticate
{
    private $error = "";
    private $directory = "C:/laragon/www/tracking-portals/public/uploads/";

    /**
     * Manage User Registration
     *
     * @param [type] $POST
     * @return void
     */
    public function auth_register($POST)
    {
        $data = array();
        $DB = Database::newInstance();
        $data['name']       = validater($POST['name']);
        $data['email']      = $POST['email'];
        $data['password']   = validater($POST['password']);
        $password2          = validater($POST['password2']);

        if (empty($data['name']) || !preg_match('/[a-zA-Z]+$/', $data['name'])) {
            $this->error .= 'Please enter valid name <br>';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error .= 'Please enter a valid email address.<br>';
        }

        if ($data['password'] !== $password2) {
            $this->error .= 'Password do not match <br>';
        }
        if (strlen($data['password']) < 5) {
            $this->error .= 'Password must be atleast 5 characters long <br>';
        }

        /**
         * Check if email already exists
         * 
         */
        $sql = 'SELECT * FROM sh_users WHERE email = :email LIMIT 1';
        $arr['email'] = $data['email'];
        $check = $DB->read($sql, $arr);
        if (is_array($check)) {
            $this->error .= 'Email is already in use. <br>';
        }

        $data['url_address'] = $this->get_random_string_max(60);
        /**
         * Check for url_address
         * 
         */
        $arr = array();   //reseting array as its already in use for email
        $sql = 'SELECT * FROM sh_users WHERE url_address = :url_address LIMIT 1';
        $arr['url_address'] = $data['url_address'];
        $check = $DB->read($sql, $arr);
        if (is_array($check)) {
            $data['url_address'] = $this->get_random_string_max(60);
        }

        if ($this->error == '') {
            /**
             * Saving User data into the database
             * 
             */
            $data['rank'] = 'customer';
            $data['date'] = date('Y-m-d H:i:s');
            $data['password'] = hash('sha1', $data['password']);

            $query = 'INSERT INTO sh_users (`url_address`, `name`, `email`, `password`, `date`, `rank`) VALUES (:url_address, :name, :email, :password, :date, :rank)';
            $result = $DB->write($query, $data);
            if ($result) {
                header('Location: ' . ROOT . 'user/login');
                die;
            }
        }
        $_SESSION['error'] = $this->error;
    }

    /**
     * Manage User Login
     *
     * @param [type] $POST
     * @return void
     */
    public function auth_login($POST)
    {
        $data = array();
        $DB = Database::newInstance();
        $data['email']      = trim($POST['email']);
        $data['password']   = validater($POST['password']);

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error .= 'Please enter a valid email address.<br>';
        }

        if (strlen($data['password']) < 5) {
            $this->error .= 'Password must be atleast 5 characters long <br>';
        }

        if ($this->error == '') {
            /**
             * Checking User data inside the database
             * 
             */
            $data['password'] = hash('sha1', $data['password']);
            $sql = 'SELECT * FROM sh_users WHERE email = :email && password = :password LIMIT 1';
            $result = $DB->read($sql, $data);
            if (is_array($result)) {

                // $_SESSION['user_url'] = $result[0]['url_address'];   //return an error as $result[0] used as object
                if ($result[0]->email_2fa != "") {
                    $arr['email_2fa'] = $result[0]->email_2fa;
                    $arr['confirm_email_pass'] = validater($this->get_random_string_max(10));
                    $_SESSION['user_2fa_url'] = $result[0]->url_address;

                    $sql = 'UPDATE sh_users SET `confirm_email_pass` = :confirm_email_pass WHERE `email_2fa` = :email_2fa';
                    $check = $DB->write($sql, $arr);

                    if ($check) {
                        // Email details
                        $subject = 'Welcome ' . $result[0]->name . ' - ' . WEBSITE_TITLE;
                        $message = 'Dear ' . $result[0]->name . ',<br><br>Your two factor authentication code is:<br>' . $arr['confirm_email_pass'] . '<br><br>Best regards,<br>' . WEBSITE_TITLE;

                        // Headers
                        $headers = "From: " . WEBSITE_TITLE . " <" . NO_REPLY_EMAIL . ">\r\n";
                        $headers .= "Reply-To: " . NO_REPLY_EMAIL . "\r\n";
                        $headers .= "Content-type: text/html\r\n";
                        mail($result[0]->email_2fa, $subject, $message, $headers);

                        header('Location: ' . ROOT . "user/password2fa");
                        die;
                    }
                } else {
                    $_SESSION['user_url'] = $result[0]->url_address;
                    header('Location: ' . ROOT . "dashboard");
                    die;
                }
            }
            $this->error .= 'Wrong Email or Password <br>';
        }
        $_SESSION['error'] = $this->error;
    }

    /**
     * Manage 2FA  Student Login
     *
     * @param [type] $POST
     * @return void
     */
    public function login_2fa($POST)
    {
        $data = array();
        $DB = Database::newInstance();
        $data['password']   = validater($POST['password']);

        if (empty($data['password'])) {
            $this->error .= 'Password must not be blank. <br>';
        }

        if ($this->error == '') {
            /**
             * Checking User data inside the database
             * 
             */
            $sql = 'SELECT * FROM sh_users WHERE confirm_email_pass = :password LIMIT 1';
            $result = $DB->read($sql, $data);

            if ($result) {
                $arr['confirm_email_pass'] = "";
                $arr['url_address'] = $result[0]->url_address;

                $sql = 'UPDATE sh_users SET `confirm_email_pass` = :confirm_email_pass WHERE `url_address` = :url_address';
                $check = $DB->write($sql, $arr);

                if ($check) {
                    $_SESSION['user_url'] = $arr['url_address'];
                    if ($result[0]->rank == "admin" || $result[0]->rank == "editor") {
                        header('Location: ' . ROOT . "super_dashboard");
                        die;
                    } else {
                        header('Location: ' . ROOT . "dashboard");
                        die;
                    }
                }
            }
        }
        $_SESSION['error'] = $this->error;
    }

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
     * Checking user logged in
     *
     */
    public function check_login($redirect = false, $allowed = array())
    {
        /**
         * Checking logged in user rank
         * 
         * fetching user rank from the database as it preferred
         */
        $DB = Database::newInstance();

        if (count($allowed) > 0) {
            $arr['url'] = $_SESSION['user_url'];
            $query = 'SELECT * from sh_users WHERE url_address = :url LIMIT 1';
            $result = $DB->read($query, $arr);

            if (is_array($result)) {
                // Assuming $result is the array containing the stdClass objects
                if ($result[0]->properties) {
                    foreach ($result as $obj) {
                        // Split the properties string by comma and add each item as an array element
                        $obj->properties = explode(',', $obj->properties);
                    }
                }

                $result = $result[0];
                /**
                 * If allowed value match with the rank of user then user can access the result
                 * Otherwise redirected to the login
                 */
                if (in_array($result->rank, $allowed)) {
                    return $result;
                }
            }

            /**
             * Redirecting if user rank is not admin
             * 
             * @return - Login Page
             */
            if (in_array("admin", $allowed)) {
                if (in_array("student", $allowed)) {
                    header('Location: ' . ROOT . 'user/login');
                    die;
                }
                header('Location: ' . ROOT . 'user/login');
            } else {
                header('Location: ' . ROOT . 'user/login');
            }
            die;
        } else {
            /**
             * Fetcing user info if user_url session is set and return into the views as an object from array i.e $result[0]
             */
            if (isset($_SESSION['user_url'])) {
                $arr = array(); //reseting array as it already contains above condition data
                $arr['url'] = $_SESSION['user_url'];
                $query = 'SELECT * FROM sh_users WHERE url_address = :url LIMIT 1';
                $result = $DB->read($query, $arr);

                if (is_array($result)) {
                    return $result[0];
                }
            }
            if ($redirect) {
                header('Location: ' . ROOT . 'user/login');
                die;
            }
        }
        return false;
    }

    /**
     * Add new User Manually
     *
     * @return void
     */
    public function add_user($POST, $FILES)
    {
        $cols = "";
        $vals = "";
        $data = array();
        $DB = Database::newInstance();
        $data['url_address'] = $this->get_random_string_max(60);
        $data['name'] = isset($POST['name']) ? validater($POST['name']) : "";
        $data['email'] = isset($POST['email']) ? trim($POST['email']) : "";
        $data['password'] = isset($POST['password']) ? validater($POST['password']) : "";
        $data['rank'] = isset($POST['rank']) && $POST['rank'] == "admin" ? "admin" : "customer";
        $properties = isset($POST['property-access']) ? $POST['property-access'] : "";

        if (empty($data['name']) || !preg_match('/[a-zA-Z]+$/', $data['name'])) {
            $this->error .= 'Please enter valid name <br>';
        }

        if (empty($data['rank'])) {
            $this->error .= 'Please enter valid role <br>';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error .= 'Please enter a valid email address.<br>';
        }

        if (empty($properties) && is_array($properties)) {
            $this->error .= 'Please select at least one property.<br>';
        }

        if (isset($FILES["avatar"]) && $FILES["avatar"]["name"] != "") {
            $avatar = handle($data["name"]);
            $file_extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);

            $avatar_file = single_file_uploader($this->directory, "", "avatar", $avatar, array('jpg', 'png', 'jpeg'));

            $cols .= ", `avatar`";
            $vals .= ", :avatar";
            $data['avatar'] = $avatar . "." . $file_extension;

            if (!empty($avatar_file)) {
                $this->error .= $avatar_file;
            }
        }

        $data['properties'] = "";

        if (isset($properties)) {
            foreach ($properties as $property) {
                $data['properties'] .= "," . sanitize_url($property);
            }
        }

        $data['properties'] = ltrim($data['properties'], ",");

        /**
         * Check if email already exists
         * 
         */
        $sql = "SELECT * FROM sh_users WHERE email = :email LIMIT 1";
        $arr['email'] = $data['email'];
        $check = $DB->read($sql, $arr);
        if (is_array($check)) {
            $this->error .= "email is already in use. <br>";
        }

        $data['date'] = date("Y-m-d H:i:s a");
        $data['password'] = hash('sha1', $data['password']);

        if ($this->error == "") {
            $query = "INSERT INTO sh_users (`url_address`$cols, `name`, `rank`, `email`, `password`, `properties`, date) VALUES (:url_address$vals, :name, :rank, :email, :password, :properties, :date)";
            $result = $DB->write($query, $data);

            if ($result) {
                $_SESSION['success'] = $data['name'] . " user added successfully.";
                header("Location: " . ROOT . "dashboard/users");
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
    public function update_user($POST, $FILES, $user_id, $user_email, $user_password, $user_rank, $user_properties)
    {
        $update_cols = "";
        $data = array();
        $DB = Database::newInstance();
        $data['user_id'] = sanitize_int($user_id);
        $data['name'] = isset($POST['name']) ? validater($POST['name']) : "";
        $data['email'] = isset($POST['email']) ? trim($POST['email']) : "";
        $data['password'] = isset($POST['password']) ? validater($POST['password']) : "";
        $data['rank'] = isset($POST['rank']) && $POST['rank'] == "admin" ? "admin" : "customer";
        $properties = isset($POST['property-access']) ? $POST['property-access'] : "";

        if (empty($data['name']) || !preg_match('/[a-zA-Z0-9]+$/', $data['name'])) {
            $this->error .= 'Please enter valid name <br>';
        }

        if (empty($data['email'])) {
            $data['email'] = $user_email;
        } else {
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->error .= 'Please enter a valid email address.<br>';
            }
            /**
             * Check if email already exists
             * 
             */
            $sql = "SELECT * FROM sh_users WHERE email = :email LIMIT 1";
            $arr['email'] = $data['email'];
            $check = $DB->read($sql, $arr);
            if (is_array($check)) {
                $this->error .= "email is already in use. <br>";
            }
        }

        if (isset($FILES["avatar"]) && $FILES["avatar"]["name"] != "") {
            $avatar = handle($data["name"]);
            $file_extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);

            $avatar_file = single_file_uploader($this->directory, "", "avatar", $avatar, array('jpg', 'png', 'jpeg'));

            $update_cols .= ", `avatar` = :avatar";
            $data['avatar'] = $avatar . "." . $file_extension;

            if (!empty($avatar_file)) {
                $this->error .= $avatar_file;
            }
        }

        $data['properties'] = "";

        if (isset($properties) && $properties != "" && !isset($POST['profile'])) {
            foreach ($properties as $property) {
                $data['properties'] .= "," . sanitize_url($property);
            }
            $data['properties'] = ltrim($data['properties'], ",");
        } else {
            $data['properties'] = $user_properties;
        }

        $data['updated_at'] = date("Y-m-d H:i:s a");

        if (empty($data['password'])) {
            $data['password'] = $user_password;
        } else {
            $data['password'] = hash('sha1', $data['password']);
        }

        if ($this->error == "") {
            $query = "UPDATE sh_users SET `name` = :name$update_cols, `email` = :email, `properties` = :properties, `password` = :password, `updated_at` = :updated_at WHERE id = :user_id";
            $result = $DB->write($query, $data);

            if ($result) {
                $_SESSION['success'] = $data['name'] . " user updated successfully.";

                if (isset($POST['profile'])) {
                    header("Location: " . ROOT . "dashboard/profile");
                } else {
                    header("Location: " . ROOT . "dashboard/edit_user/" . $user_id);
                }
                die;
            }
        }
        $_SESSION['error'] = $this->error;
    }

    /**
     * Users - return all users
     * 
     * @return Users List
     */
    public function users()
    {
        $data = array();
        $data['email'] = "backup.me0256@gmail.com";
        $DB = Database::newInstance();
        $sql = 'SELECT * FROM sh_users WHERE email != :email ORDER BY id DESC';
        $result = $DB->read($sql, $data);
        if (is_array($result)) {
            return $result;
        }
    }

    /**
     * Users - return all users
     * 
     * @return Users List
     */
    public function get_user($id)
    {
        $data = array();
        $data["id"] = $id;
        $DB = Database::newInstance();
        $sql = 'SELECT * FROM sh_users WHERE id = :id LIMIT 1';
        $result = $DB->read($sql, $data);
        if (is_array($result)) {
            return $result;
        }
    }

    /**
     * Delete user
     * 
     * @return Users List
     */
    public function delete_user($id)
    {
        $DB = Database::newInstance();
        $DB->read("DELETE FROM sh_users WHERE id=:id", ['id' => $id]);
        header('Location: ' . ROOT . 'dashboard/users');
        die;
    }

    /**
     * Manage Lost Password
     *
     * @param [type] $POST
     * @return void
     */
    public function lost_password($POST)
    {
        $data = array();
        $DB = Database::newInstance();
        $data['pass_token'] = $this->get_random_string_max(50);
        $data['email'] = isset($POST['email']) ? trim($POST['email']) : "";
        $data['token_date'] = date("Y-m-d H:i:s a");
        $data['expire_token'] = 1;

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error .= 'Please enter a valid email address.<br>';
        }

        $arr['email'] = $data['email'];
        $sql = 'SELECT * FROM sh_users WHERE email = :email LIMIT 1';
        $result = $DB->read($sql, $arr);

        if (empty($result)) {
            if ($this->error == "") {
                $this->error .= 'Your email not found.<br>';
            }
        } else {
            $encrypted_token = hash('sha1', $data['pass_token']);
        }
        $encrypted_update_key = hash('sha1', "sp_password_update_key");


        if ($this->error == '') {
            $query = "UPDATE sh_users SET `pass_token` =:pass_token, `token_date` = :token_date, `expire_token` = :expire_token WHERE email = :email";
            $check = $DB->write($query, $data);

            if ($check && $result) {
                // Email details
                $subject = 'Password Update Alert - ' . WEBSITE_TITLE;
                $message = 'Dear ' . $result[0]->name . ',<br><br>You are trying to update the password. <a href="' . ROOT . 'user/updatepassword?authu=' . $result[0]->url_address . '&authk=' . $encrypted_token . '&authc=' . $encrypted_update_key . '">Click Here</a> to Update the Password withing 24 hours.<br><br>If you did not initiate this action, please <a href="' . ROOT . 'contact">contact us</a> immediately.<br><br>Best regards,<br>' . WEBSITE_TITLE;

                // Headers
                $headers = "From: " . WEBSITE_TITLE . " <" . NO_REPLY_EMAIL . ">\r\n";
                $headers .= "Reply-To: " . NO_REPLY_EMAIL . "\r\n";
                $headers .= "Content-type: text/html\r\n";

                $_SESSION['success'] = "We've sent you an password reset link to update the password.";
                // Send email
                mail($data['email'], $subject, $message, $headers);
                unset_cookie("sp_pass_update");

                header('Location: ' . ROOT . "user/lostpassword");
                die;
            } else {
                // Send email
                $_SESSION['error'] = "Failed to send password reset link.";
                header('Location: ' . ROOT . "user/lostpassword");
                die;
            }
        }
        $_SESSION['error'] = $this->error;
    }

    /**
     * Manage Lost Password
     *
     * @param [type] $POST
     * @return void
     */
    public function update_password($POST, $user_address, $user_token, $user_key)
    {
        $arr = array();
        $data = array();
        $DB = Database::newInstance();
        $data['user_address'] = $user_address;
        $data['password'] = isset($POST['password']) ? validater($POST['password']) : "";
        $cpassword = isset($POST['cpassword']) ? validater($POST['cpassword']) : "";
        $encrypted_update_key = hash('sha1', "sp_password_update_key");

        if ($user_key != $encrypted_update_key) {
            $this->error .= 'Invalid password update request.<br>';
        }

        if ($data['password'] != $cpassword) {
            if ($this->error == "") {
                $this->error .= 'Both passwords are different.<br>';
            }
        }

        if (strlen($data['password']) < 5) {
            if ($this->error == "") {
                $this->error .= 'Password must be atleast 5 characters long <br>';
            }
        }

        $data['password'] = hash('sha1', $data['password']);

        $arr['user_address'] = $user_address;
        $arr_sql = 'SELECT * FROM sh_users WHERE url_address = :user_address LIMIT 1';
        $arr_result = $DB->read($arr_sql, $arr);

        if ($arr_result) {
            if ($arr_result[0]->expire_token) {
                $data['expire_token'] = 0;
            } else {
                if ($this->error == "") {
                    $this->error .= 'Link Expired.<br>';
                }
            }

            $user_fetched_token = hash('sha1', $arr_result[0]->pass_token);
            if ($user_fetched_token != $user_token) {
                if ($this->error == "") {
                    $this->error .= 'Trying to update invalid customer data.<br>';
                }
            }
        } else {
            if ($this->error == "") {
                $this->error .= 'Trying to update invalid customer data.<br>';
            }
        }

        if ($this->error == '') {
            $query = "UPDATE sh_users SET `password` = :password, `expire_token` = :expire_token WHERE url_address = :user_address";
            $check = $DB->write($query, $data);

            if ($check && $arr_result) {
                // Email details
                $subject = 'Password Updated Alert - ' . WEBSITE_TITLE;
                $message = 'Dear ' . $arr_result[0]->name . ',<br><br>Your password has been updated. <a href="' . ROOT . 'user/login">Login to Dashboard</a>.<br><br>If you did not initiate this action, please <a href="' . ROOT . 'contact">contact us</a> immediately.<br><br>Best regards,<br>' . WEBSITE_TITLE;

                // Headers
                $headers = "From: " . WEBSITE_TITLE . " <" . NO_REPLY_EMAIL . ">\r\n";
                $headers .= "Reply-To: " . NO_REPLY_EMAIL . "\r\n";
                $headers .= "Content-type: text/html\r\n";

                $_SESSION['success'] = "Your password updated successfully.";
                // Send email
                mail($data['email'], $subject, $message, $headers);
                set_cookie_one_month("sp_pass_update", 1);

                header('Location: ' . ROOT . "user/login");
                die;
            } else {
                // Send email
                $_SESSION['error'] = "Failed to send password reset link.";
                header('Location: ' . ROOT . "user/login");
                die;
            }
        }
        $_SESSION['error'] = $this->error;
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
