<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden - Direct access not allowed.";
    exit;
}

class User extends Controller
{
    public function index()
    {
        $data['page_title'] = "Profile";
        $User = $this->load_model("Authenticate");
        $users_list = $User->users();

        if (is_object($users_list)) {
            $data['users_list'] = $users_list;
        }

        $this->view("login", $data);
    }

    /**
     * Return Login Form
     *
     * @return View
     */
    public function login($slug)
    {
        $data['page_title'] = "Login";
        $User = $this->load_model("Authenticate");

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $userResponse = isset($_POST['captcha']) ? $_POST['captcha'] : null;
            $hashedAnswer = isset($_POST['hash']) ? $_POST['hash'] : null;

            // Verify the user's response
            if (password_verify($userResponse, $hashedAnswer)) {
                $User->auth_login($_POST);
            } else {
                $_SESSION['error'] = "Invalid captcha response.";
            }
        }

        $user_data = $User->check_login();

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        $this->redirect_view("login", $slug, $data);
    }

    /**
     * Return Register Form
     *
     * @return View
     */
    public function register($slug)
    {
        $data['page_title'] = "Register";
        $User = $this->load_model("Authenticate");

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $userResponse = isset($_POST['captcha']) ? $_POST['captcha'] : null;
            $hashedAnswer = isset($_POST['hash']) ? $_POST['hash'] : null;

            // Verify the user's response
            if (password_verify($userResponse, $hashedAnswer)) {
                $User->auth_register($_POST);
            } else {
                $_SESSION['error'] = "Invalid captcha response.";
            }
        }

        $user_data = $User->check_login();

        if (is_object($user_data)) {
            $data['user_data'] = $user_data;
        }

        $this->redirect_view("register", $slug, $data);
    }

    /**
     * Return Lost Password Form
     *
     * @return View
     */
    public function lostpassword($slug)
    {
        $data['page_title'] = "Lost Password";
        $Customer = $this->load_model("Authenticate");

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $userResponse = isset($_POST['captcha']) ? $_POST['captcha'] : null;
            $hashedAnswer = isset($_POST['hash']) ? $_POST['hash'] : null;

            // Verify the user's response
            if (password_verify($userResponse, $hashedAnswer)) {
                $Customer->lost_password($_POST);
            } else {
                $_SESSION['error'] = "Invalid captcha response.";
            }
        }

        $this->redirect_view("lost-password", $slug, $data);
    }

    /**
     * Return Lost Password Form
     *
     * @return View
     */
    public function updatepassword($slug)
    {
        $data['page_title'] = "Update Password";
        $Customer = $this->load_model("Authenticate");

        if (isset($_GET['authk']) && $_GET['authk'] != "" && isset($_GET['authc']) && $_GET['authc'] != "" && isset($_GET['authu']) && $_GET['authu'] != "" && !isset($_COOKIE["sp_pass_update"])) {
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $userResponse = isset($_POST['captcha']) ? $_POST['captcha'] : null;
                $hashedAnswer = isset($_POST['hash']) ? $_POST['hash'] : null;

                // Verify the user's response
                if (password_verify($userResponse, $hashedAnswer)) {
                    $Customer->update_password($_POST, $_GET['authu'], $_GET['authk'], $_GET['authc']);
                } else {
                    $_SESSION['error'] = "Invalid captcha response.";
                }
            }

            $this->redirect_view("update-password", $slug, $data);
        } else {
            header('Location: ' . ROOT . "user/lostpassword");
            die;
        }
    }

    /**
     * Return Register Form
     *
     * @return View
     */
    public function logout()
    {
        $User = $this->load_model("Authenticate");
        $User->logout();
    }

    /**
     * This function is required for the 404
     *
     * @return 404
     */
    public function not_found()
    {
        $data['page_title'] = "404 - Not Found ";

        $this->view("404", $data);
    }
}
