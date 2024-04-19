<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden - Direct access not allowed.";
    exit;
}

class Not_Found extends Controller
{
    /**
     * Website 404
     *
     * @return void
     */
    public function index()
    {
        $data['page_title'] = "404 - Not Found";

        $this->view("404", $data);
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
