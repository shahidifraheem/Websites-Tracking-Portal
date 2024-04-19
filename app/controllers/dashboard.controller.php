<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden - Direct access not allowed.";
    exit;
}

class Dashboard extends Controller
{
    public function index($url)
    {
        $userAuth = $this->load_model("Authenticate");
        $user_data = $userAuth->check_login(true, ["admin", "customer"]);

        $data['page_title'] = "Dashboard &#9866; " . $user_data->name;
        $data['user_data'] = $user_data;

        require_once '../app/api/vendor/autoload.php'; // Path to autoload.php in Google API PHP client library

        // Path to your service account credentials JSON file
        $data['api_path'] = '../app/api/tracking-api.json';

        $this->view("dashboard/index", $data);
    }

    public function properties()
    {
        $userAuth = $this->load_model("Authenticate");
        $user_data = $userAuth->check_login(true, ["admin"]);
        $portal = $this->load_model("Portal");
        $properties = $portal->properties();

        $data['page_title'] = "Dashboard &#9866; Properties";
        $data['user_data'] = $user_data;

        if (is_array($properties)) {
            $data['properties'] = $properties;
        }

        $this->view("dashboard/property/properties", $data);
    }

    public function add_property()
    {
        $userAuth = $this->load_model("Authenticate");
        $user_data = $userAuth->check_login(true, ["admin"]);

        $data['page_title'] = "Dashboard &#9866; Add Property";
        $data['user_data'] = $user_data;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $portal = $this->load_model("Portal");
            $portal->add_property($_POST);
        }

        $this->view("dashboard/property/add-property", $data);
    }

    public function edit_property($id)
    {
        $userAuth = $this->load_model("Authenticate");
        $user_data = $userAuth->check_login(true, ["admin"]);
        $portal = $this->load_model("Portal");
        $property = $portal->get_property($id);

        $data['page_title'] = "Dashboard &#9866; Add Property";
        $data['user_data'] = $user_data;

        if (is_array($property)) {
            $data['property'] = $property;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $portal->update_property($_POST, $id);
        }

        $this->view("dashboard/property/edit-property", $data);
    }

    public function delete_property($id)
    {
        $userAuth = $this->load_model("Authenticate");
        $userAuth->check_login(true, ["admin"]);
        $portal = $this->load_model("Portal");
        $portal->delete_property($id);
    }

    public function users()
    {
        $userAuth = $this->load_model("Authenticate");
        $user_data = $userAuth->check_login(true, ["admin"]);
        $users = $userAuth->users();

        $data['page_title'] = "Dashboard &#9866; Users";
        $data['user_data'] = $user_data;

        if (is_array($users)) {
            $data['users'] = $users;
        }

        $this->view("dashboard/user/users", $data);
    }

    public function add_user()
    {
        $userAuth = $this->load_model("Authenticate");
        $user_data = $userAuth->check_login(true, ["admin"]);
        $portal = $this->load_model("Portal");
        $properties = $portal->properties();
        $users = $userAuth->users();

        $data['page_title'] = "Dashboard &#9866; Users";
        $data['user_data'] = $user_data;


        if (is_array($properties)) {
            $data['properties'] = $properties;
        }

        if (is_array($users)) {
            $data['users'] = $users;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userAuth->add_user($_POST, $_FILES);
        }

        $this->view("dashboard/user/add-user", $data);
    }

    public function edit_user($id)
    {
        $userAuth = $this->load_model("Authenticate");
        $user_data = $userAuth->check_login(true, ["admin"]);
        $user = $userAuth->get_user($id);
        $portal = $this->load_model("Portal");
        $properties = $portal->properties();

        if (is_array($properties)) {
            $data['properties'] = $properties;
        }

        $data['page_title'] = "Dashboard &#9866; Edit User";
        $data['user_data'] = $user_data;

        if (is_array($user)) {
            $data['user'] = $user;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userAuth->update_user($_POST, $_FILES, $id, $user[0]->email, $user[0]->password, $user[0]->rank, $user[0]->properties);
        }

        $this->view("dashboard/user/edit-user", $data);
    }

    public function delete_user($id)
    {
        $userAuth = $this->load_model("Authenticate");
        $userAuth->check_login(true, ["admin"]);
        $userAuth->delete_user($id);
    }

    public function profile()
    {
        $userAuth = $this->load_model("Authenticate");
        $user_data = $userAuth->check_login(true, ["admin", "customer"]);

        $data['page_title'] = "Dashboard &#9866; Edit User";
        $data['user_data'] = $user_data;

        $properties = "";
        if ($user_data->properties) {
            foreach ($user_data->properties as $property) {
                $properties .= "," . sanitize_url($property);
            }
            $properties = ltrim($properties, ",");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userAuth->update_user($_POST, $_FILES, $user_data->id, $user_data->email, $user_data->password, $user_data->rank, $properties);
        }

        $this->view("dashboard/profile", $data);
    }

    public function analytics($property)
    {
        $userAuth = $this->load_model("Authenticate");
        $user_data = $userAuth->check_login(true, ["admin"]);

        $data['page_title'] = "Dashboard &#9866; Analytics";
        $data['user_data'] = $user_data;

        $this->redirect_view("dashboard/analytics", $property, $data);
    }
}
