<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden - Direct access not allowed.";
    exit;
}

class App
{
    protected $controller = "home";
    protected $method = "index";
    protected $params;

    public function __construct()
    {
        $url = $this->parse_Url();

        if (file_exists("../app/controllers/" . strtolower(($url[0]) . ".controller.php"))) {
            $this->controller = strtolower($url[0]);
            unset($url[0]);
        } else {
            // Return 404 for main sub pages
            $this->controller = "not_found";
            unset($url[0]);
        }

        require "../app/controllers/" . $this->controller . ".controller.php";

        $this->controller = new $this->controller;

        if (isset($url[1])) {
            $url[1] = strtolower($url[1]);
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                if (isset($url[0])) {
                    unset($url[1]);
                } else {
                    unset($url[1]);
                }
            } else {
                if (isset($url[0])) {
                    // Return 404 for inner pages of sub pages
                    $this->method = "not_found";
                    unset($url[1]);
                }
            }
        }

        if (isset($url[0]) && isset($url[1])) {
            unset($url[0]);
            unset($url[1]);
        } else {
            $this->params = (count($url) > 0) ? $url : ["home"];
        }

        try {
            // Attempt the operation that might throw an exception
            call_user_func_array([$this->controller, $this->method], $this->params);
        } catch (Throwable $e) { // Catch all exceptions and errors
            // echo 'An error occurred: ' . $e->getMessage();
            header("HTTP/1.1 403 Forbidden");
            echo "403 Forbidden - Direct access not allowed.";
            exit;
        }
    }

    /**
     * Breaking url into string and remove end slash
     *
     * @return Url
     */
    private function parse_Url()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : "home";

        return explode("/", filter_var(trim($url, "/"), FILTER_SANITIZE_URL));
    }
}
