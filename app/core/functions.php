<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "403 Forbidden - Direct access not allowed.";
    exit;
}

/**
 * Data view function
 *
 * @param [array] $data
 * @return Data
 */
function show($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * Session Error
 *
 * @return Error
 */
function check_error()
{
    if (isset($_SESSION["error"]) && $_SESSION["error"] != "") {
        echo '<div class="error-box">';
        echo $_SESSION["error"];
        echo '</div>';
        unset($_SESSION["error"]);
    }
}

/**
 * Session Success
 *
 * @return Success
 */
function check_success()
{
    if (isset($_SESSION["success"]) && $_SESSION["success"] != "") {
        echo '<div class="success-box">';
        echo $_SESSION["success"];
        echo '</div>';
        unset($_SESSION["success"]);
    }
}

/**
 * Special characters remover from data
 *
 * @param [string] $data
 * @return Data
 */
function esc($data)
{
    if ($data) {
        $data = addslashes($data);
    }
    return $data;
}

/**
 * Input Validater
 * 
 * @param [string] $data
 * @return Data
 */
function validater($data)
{
    if ($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
    }
    return $data;
}

/**
 * Escape data with full of special characters
 * 
 * @param [string] $data
 * @return Data
 */
function escaper($data)
{
    if ($data) {
        $mysqli_con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $data = trim($data);
        $data = mysqli_real_escape_string($mysqli_con, $data);
        $mysqli_con->close();
    }
    return $data;
}

/**
 * Entity to HTML for view on frontend
 * 
 * @param [Entity Code] $data
 *@return HTML
 */
function html($data)
{
    if ($data) {
        $data = trim($data);
        $data = html_entity_decode($data);
    }
    return $data;
}

/**
 * Sanitizing the URL
 *
 * @param [url] $data
 * @return Url
 */
function sanitize_url($data)
{
    if ($data) {
        $data = trim($data);
        $data = filter_var($data, FILTER_SANITIZE_URL);
    }
    return $data;
}
/**
 * Text Cleaner
 * 
 * @param [string] $data
 * @return string
 */
function cleaner($data)
{
    $data = str_replace("\\", "", $data);
    return $data;
}

/**
 * Clean Title Maker
 * 
 * @param [string] $data
 * @return string
 */
function title($data)
{
    if ($data) {
        $data = str_replace("_", " ", $data);
        $data = str_replace("-", " ", $data);
        $data = ucwords($data);
    }
    return $data;
}

/**
 * Handle Maker
 * 
 * @param [string] $data
 * @return handle
 */
function handle($data)
{
    if ($data) {
        $data = preg_replace('~[^\\pL0-9_]+~u', '-', $data);
        $data = trim($data, "-");
        $data = iconv('UTF-8', 'ASCII//IGNORE//TRANSLIT', $data);
        $data = strtolower($data);
        $data = preg_replace('~[^-a-z0-9_]+~', '', $data);
    }
    return $data;
}


/**
 * Sanitize and Validate the integer
 *
 * @param [type] $input
 * @return void
 */
function sanitize_int($integer)
{
    if ($integer) {
        $integer = $integer;
        // Sanitize the input by removing non-integer characters
        $sanitizedInteger = trim(filter_var($integer, FILTER_SANITIZE_NUMBER_INT));

        // Validate the sanitized input to ensure it's a valid integer
        $validInteger = filter_var($sanitizedInteger, FILTER_VALIDATE_INT);
        return (int)$validInteger;
    }

    return $integer;
}

/**
 * Sanitize and Validate the username
 *
 * @param [type] $input
 * @return void
 */
function sanitize_username($username)
{
    if ($username) {
        $username = trim($username);
        // Remove spaces from start, end, and mid
        $username = preg_replace('/\s+/', '', $username);

        // Remove special characters except '-' and '_'
        $username = preg_replace('/[^A-Za-z0-9-_]/', '', $username);
    }

    return $username;
}

function clean_string($str)
{
    if ($str) {
        $str = trim($str);
        // Remove everything except letters and spaces
        $clean_str = preg_replace('/[^a-z-_]/', '', $str);
    }

    return $clean_str;
}

function sanitize_hex_code($hexCode)
{
    if ($hexCode && str_contains($hexCode, "#")) {
        $hexCode = ltrim($hexCode, '#');

        if (preg_match('/^[a-zA-Z0-9]{6}$/', $hexCode)) {
            return '#' . strtoupper($hexCode);
        } else {
            return '#5764f1';
        }
    } else {
        return '#5764f1';
    }
}

/**
 * Excerpt maker
 *
 * @param [type] $string
 * @param integer $length
 * @return void
 */
function excerpt($string, $length = 40)
{
    // Check if the string length is greater than the desired length
    if (strlen($string) > $length) {
        // Trim the string to the desired length and add ellipsis
        $trimmedString = substr($string, 0, $length) . '...';
        return $trimmedString;
    } else {
        // If the string length is already less than or equal to the desired length, return the original string
        return $string;
    }
}
/**
 * Single file uploader
 *
 * @param string $directory     -> Root directory path with slash e.i public/
 * @param string $sub_directory -> Create a new sub folder to store files
 * @param string $file_id       -> Name attribute of the input field
 * @param string $filename      -> Type filename to override the default filename i.e filename.png|jpg|jpeg|pdf
 * @param string $file_types    -> pdf|png|gif|jpg|jpeg...
 * @return void
 */
function single_file_uploader($root_directory, $sub_directory, $file_id, $filename, $file_types = [], $file_width = "", $file_height = "")
{
    $error = "";
    $max_file_size = 1 * 1024 * 1024; // 1 MB in bytes

    if (isset($_FILES[$file_id]) && $_FILES[$file_id]["name"] != "") {

        if ($_FILES[$file_id]["size"] > $max_file_size) {
            $error .= " File size exceeds 1 MB.";
        } else {
            // Get file extension
            $file_extension = pathinfo($_FILES[$file_id]["name"], PATHINFO_EXTENSION);

            if (in_array(strtolower($file_extension), $file_types)) {
                if ($filename == "") {
                    $filename = handle(str_replace("." . $file_extension, "", basename($_FILES[$file_id]["name"])));
                    $filename = $filename . "." . $file_extension;
                } else {
                    if ($sub_directory == "") {
                        $filename = $filename . "." . $file_extension;
                    } else {
                        $filename = $sub_directory . "/" . $filename . "." . $file_extension;
                    }
                }

                // Create subdirectory
                if ($sub_directory != "") {
                    $new_directory = $root_directory . $sub_directory;

                    // Check if the directory exists, and create it if it doesn't
                    if (!file_exists($new_directory)) {
                        if (!mkdir($new_directory, 0755, true)) {
                            $error .= " Sub directory not created, check your directory name.<br>";
                        }
                    }
                }

                $file_full_path = $root_directory . $filename;

                if (move_uploaded_file($_FILES[$file_id]["tmp_name"], $file_full_path)) {
                    if ($file_width && $file_height) {
                        // Resize and compress the image
                        $image_optimized = resize_and_compress_image($file_full_path, $file_width, $file_height);

                        if ($image_optimized != "") {
                            $error .= $image_optimized;
                        }
                    }
                    return;
                } else {
                    $error .= " There is an issue with the paths you provided.<br>";
                }
            } else {
                $error .= " Please upload a valid file.<br>";
            }
        }
    } else {
        $error .= " Please select a file.<br>";
    }
    return $error;
}

/**
 * Multiple file uploader
 *
 * @param string $root_directory -> Root directory path with slash e.g., public/
 * @param string $sub_directory  -> Create a new subfolder to store files
 * @param string $file_id        -> Name attribute of the input field
 * @param array  $file_types     -> Allowed file types, e.g., ['pdf', 'png', 'gif', 'jpg', 'jpeg']
 * @return array                 -> An array of error messages or an empty array if successful
 */
function multiple_file_uploader($root_directory, $sub_directory, $file_id, $filename, $file_types = [], $file_width = "", $file_height = "")
{
    $error = "";

    if (isset($_FILES[$file_id]) && $_FILES[$file_id]["name"] != "") {

        for ($i = 0; $i < count($_FILES[$file_id]['name']); $i++) {
            // Get file extension
            $file_extension = pathinfo($_FILES[$file_id]["name"][$i], PATHINFO_EXTENSION);

            if (in_array(strtolower($file_extension), $file_types)) {
                if ($filename == "") {
                    $filename = handle(str_replace("." . $file_extension, "", basename($_FILES[$file_id]["name"][$i])));
                    $filename = $filename . "." . $file_extension;
                } else {
                    if ($sub_directory == "") {
                        $filename = $filename;
                    } else {
                        $filename = $sub_directory . "/" . $filename;
                    }
                }

                // Create subdirectory
                if ($sub_directory != "") {
                    $new_directory = $root_directory . $sub_directory;

                    // Check if the directory exists, and create it if it doesn't
                    if (!file_exists($new_directory)) {
                        if (!mkdir($new_directory, 0755, true)) {
                            $error .= "Sub directory not created, check your directory name.<br>";
                        }
                    }
                }

                $file_full_path = $root_directory . $filename;

                if (move_uploaded_file($_FILES[$file_id]["tmp_name"][$i], $file_full_path)) {
                    if ($file_width && $file_height) {
                        // Resize and compress the image
                        $image_optimized = resize_and_compress_image($file_full_path, $file_width, $file_height);

                        if ($image_optimized != "") {
                            $error .= $image_optimized;
                        }
                    }
                    return;
                } else {
                    $error .= "There is an issue with the paths you provided.<br>";
                }
            } else {
                $error .= "Please upload a valid file.<br>";
            }
        }
    } else {
        $error .= "Please select a file.<br>";
    }
    return $error;
}


/**
 * Resize and Compress image files
 *
 * @param [type] $file_path
 * @return void
 */
function resize_and_compress_image($file_path, $file_width = "", $file_height = "")
{
    // Define maximum dimensions
    $maxWidth = $file_width != "" ? $file_width : 650;
    $maxHeight = $file_height != "" ? $file_height : 400;

    // Define maximum file size
    $maxFileSize = 1024 * 1024; // 1 MB in bytes

    // Get file extension
    $fileExt = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

    // Check if the file is an image
    if (in_array($fileExt, ['jpg', 'jpeg', 'png'])) {
        // Create image resource
        if ($fileExt === 'jpg' || $fileExt === 'jpeg') {
            $image = @imagecreatefromjpeg($file_path);
        } elseif ($fileExt === 'png') {
            $image = @imagecreatefrompng($file_path);
        }

        // Check if image creation was successful
        if ($image !== false) {
            // Get original dimensions
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // Calculate new dimensions
            if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
                $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
                $newWidth = $originalWidth * $ratio;
                $newHeight = $originalHeight * $ratio;
            } else {
                $newWidth = $originalWidth;
                $newHeight = $originalHeight;
            }

            // Create new image with resized dimensions
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            // Compress the image and save
            imagejpeg($newImage, $file_path, 80); // 80% quality

            // Free up memory
            imagedestroy($image);
            imagedestroy($newImage);
        } else {
            // Handle invalid image file
            return " Unable to create image from file.";
        }
    } else {
        // Handle unsupported file type
        return " Unsupported file type.";
    }
}


/**
 * Site Status to enable/disable maintenance
 *
 * @return boolean
 */
function site_status()
{
    $mysqli_con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check for a connection error
    if ($mysqli_con->connect_error) {
        die("Connection failed: " . $mysqli_con->connect_error);
    }

    // Perform a SQL query to fetch the 'maintenance' column value
    $sql = "SELECT maintenance FROM ct503_settings";
    $result = $mysqli_con->query($sql);

    // Check if the query was successful
    if ($result) {
        $row = $result->fetch_assoc();
        $maintenanceValue = $row['maintenance'];

        // Close the database connection
        $mysqli_con->close();

        // Return the maintenance value
        return $maintenanceValue;
    } else {
        // Handle any query errors
        echo "Error: " . $mysqli_con->error;
    }
}

/**
 * Current page url
 *
 * @return string
 */
function current_url()
{
    $currentURL = "http";
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        $currentURL .= "s";
    }
    $currentURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $currentURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $currentURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }

    return $currentURL;
}

function get_custom_file_content($url)
{
    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL session and get the API content
    $file_content = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo curl_error($ch) . " Error fetching content from the API.";
    }

    // Close cURL session
    curl_close($ch);

    return $file_content;
}


/**
 * Document Document Minifier
 *
 * @param [type] $document
 * @return void
 */
function minify_document($document)
{
    // Minify HTML
    $search_html = array(
        '/<!--.*?-->/s',       // Remove HTML comments
        '/\>[^\S]+/s',         // Strip whitespaces after tags
        '/[^\S]+\</s',         // Strip whitespaces before tags
        '/(\s)+/s'             // Collapse multiple consecutive spaces and line breaks
    );

    $replace_html = array('', '>', '<', '\\1');

    $document = preg_replace($search_html, $replace_html, $document);

    // Minify internal CSS
    $search_css = array(
        '/\/\*.*?\*\//s',      // Remove CSS comments
        '/\s*([:;{}])\s*/s',   // Remove spaces around colons, semicolons, and braces
    );

    $replace_css = array('', '\\1');

    $document = preg_replace($search_css, $replace_css, $document);

    // Minify internal JavaScript
    $search_js = array(
        '/\/\*.*?\*\//s',              // Remove JS comments
        '/\s*([\{\}\(\)\;\:])\s*/s',  // Remove spaces around braces, parentheses, semicolons, and colons
    );

    $replace_js = array('', '\\1');

    $document = preg_replace($search_js, $replace_js, $document);

    return $document;
}

/**
 * Delete directory files
 *
 * @param [type] $dir_path
 * @return void
 */
function delete_dir_files($dir_path)
{
    $message = "";
    // Check if the folder exists
    if (!is_dir($dir_path)) {
        $message = "Invalid folder path.";
        return $message;
    }

    // Get all files in the folder
    $files = glob($dir_path . '/*');

    // Loop through each file and delete it
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
            $file_array = explode("cache/", $file);
            $message .= "Deleted: $file_array[1]<br>";
        }
    }
    return $message;
}

/**
 * Get all images files from directories
 *
 * @param [type] $directory
 * @param [type] $imageExtensions
 * @return void
 */
function get_files($directory, $imageExtensions)
{
    // Get all files in the directory
    $files = scandir($directory);

    // Filter out only the image files
    return array_filter($files, function ($file) use ($imageExtensions) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        return in_array($extension, $imageExtensions);
    });
}

/**
 * Generating Captcha
 *
 * @return void
 */
function generateMathProblem()
{
    $num1 = rand(1, 50);
    $num2 = rand(1, 50);
    $operator = rand(0, 1) ? '+' : '-';
    $problem = "$num1 $operator $num2";
    $answer = ($operator === '+') ? $num1 + $num2 : $num1 - $num2;
    return array('problem' => $problem, 'answer' => $answer);
}

function set_cookie_one_month($cookie_name, $cookie_value)
{
    // Calculate the expiration time for one month (in seconds)
    $expiration_time = time() + 30 * 24 * 60 * 60; // 30 days * 24 hours * 60 minutes * 60 seconds

    // Set the cookie
    setcookie($cookie_name, $cookie_value, $expiration_time, "/");
}

function unset_cookie($cookie_name)
{
    // Set the cookie expiration time to a past time (1 second ago)
    $expiration_time = time() - 1;

    // Set the cookie with the past expiration time to delete it
    setcookie($cookie_name, "", $expiration_time, "/");
}
