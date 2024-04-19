<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden - Direct access not allowed.";
    exit;
}

class Controller
{

    /**
     * Return file from views folder to run
     *
     * @param [string] $path
     * @param array $data
     * @return View
     */
    public function view($path, $data = [])
    {
        // Extracting array to make data accessible using its keys
        if (is_array($data)) {
            extract($data);
        }

        // strpos($path, 'dashboard/') !== false    // For less than PHP V 8.0 
        if (str_contains($path, 'dashboard/')) {
            if (file_exists("../app/views/" . $path . ".php")) {
                include "../app/views/" . $path . ".php";
            } else {
                include "../app/views/404.php";
            }
        } else {
            if (file_exists("../app/views/" . THEME . $path . ".php")) {
                include "../app/views/" . THEME . $path . ".php";
            } else {
                include "../app/views/" . $path . ".php";
            }
        }
    }

    /**
     * Include the files from include folder inside theme
     *
     * @param [string] $path
     * @param array $data
     * @return Include_View
     */
    public function include_theme($path, $data = [])
    {
        //Extracting Data Array to make it accessible using its keys name
        if (is_array($data)) {
            extract($data);
        }
        if (file_exists("../app/views/" . THEME . "include/" . $path . ".inc.php")) {
            include "../app/views/" . THEME . "include/" . $path . ".inc.php";
        } else {
            include "../app/views/404-include.php";
        }
    }

    /**
     * Include the files from include folder inside dashboard
     *
     * @param [string] $path
     * @param array $data
     * @return Include_View
     */
    public function include_dashboard($path, $data = [])
    {
        //Extracting Data Array to make it accessible using its keys name
        if (is_array($data)) {
            extract($data);
        }

        if (file_exists("../app/views/" . DASHBOARD . "include/" . $path . ".inc.php")) {
            include "../app/views/" . DASHBOARD . "include/" . $path . ".inc.php";
        } else {
            include "../app/views/404-include.php";
        }
    }

    /**
     * Loads the model from models folder
     *
     * @param [string] $model
     * @return Model
     */
    public function load_model($model)
    {
        if (file_exists("../app/models/" . strtolower($model) . ".model.php")) {
            include "../app/models/" . strtolower($model) . ".model.php";
            return $model = new $model();
        }
        return false;
    }

    /**
     * Return view or 404 on invalid url
     *
     * @param [string] $view_name
     * @param [string] $url
     * @param [array] $data
     * @return View
     */
    public function redirect_view($view_name, $url, $data)
    {
        if ($url == "home") {
            $this->view($view_name, $data);
        } else {
            $this->view("404", $data);
        }
    }
}
