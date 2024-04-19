<?php
// Disable direct file access
if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    header("HTTP/1.1 403 Forbidden");
    echo "<h1>403 Forbidden - Direct access not allowed.</h1>";
    exit;
}

$this->include_dashboard('header', $data);
$this->include_dashboard('sidebar', $data);

// Initialize an array to store the dates
$previousDates = array();

// Get today's date
$currentDate = strtotime('today');

// Loop through the last 7 days
for ($i = 0; $i < 7; $i++) {
    // Calculate the date for the current iteration
    $date = date('Y-m-d', strtotime("-$i days", $currentDate));

    // Add the date to the array
    $previousDates[] = $date;
}

// Reverse the array to get the dates in chronological order
$previousDates = array_reverse($previousDates);


// Create Google client object
$client = new Google_Client();

// Set service account credentials
$client->setAuthConfig($api_path);

// Set scopes
$client->addScope(Google_Service_Webmasters::WEBMASTERS_READONLY);

// Create service object
$service = new Google_Service_Webmasters($client);

// Define parameters for the request
$siteUrl = isset($_GET['property']) && $_GET['property'] != "" ? sanitize_url($_GET['property']) : "";

check_error();
check_success();
?>

<?php if (!empty($user_data->properties) && isset($_GET['property'])) :

    if (!in_array($siteUrl, $user_data->properties)) {
        $_SESSION['error'] = "Invalid property.";
        header("Location: " . ROOT . "dashboard");
        die;
    }
?>
    <div class="title-header">
        <h1 class="title">Website Performance Data:</h1>
        <div class="input-box">
            <select id="property">
                <?php foreach ($user_data->properties as $property) : ?>
                    <option value="<?= $property ?>" <?= $property == $siteUrl ? "selected" : "" ?>><?= $property ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>


    <table>
        <thead>
            <tr>
                <th colspan="6">Analytics</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $index = -1;
            // Loop through each date and fetch data
            foreach ($previousDates as $date) :
                $index++;
                // Define parameters for the request
                $request = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
                $request->setStartDate($date);
                $request->setEndDate($date);
                $request->setDimensions(['query', 'page', 'date', 'country']);
                $request->setRowLimit(10); // Number of rows to retrieve, adjust as needed

                // Execute request
                $response = $service->searchanalytics->query($siteUrl, $request);

                // Process response
                $rows = $response->getRows();

                // Initialize an array to store the unique date data
                $uniqueData = array();

                // Output data for each row
                if (!empty($rows)) {

                    // Loop through each row of data
                    foreach ($rows as $row) {
                        $keyword = $row->getKeys()[0]; // Get the keyword
                        $url = $row->getKeys()[1]; // Get the URL
                        $date = $row->getKeys()[2]; // Get the date
                        $country = $row->getKeys()[3]; // Get the country code
                        $position = $row->getPosition(); // Get the position

                        // Check if the unique key already exists in the uniqueData array
                        if (array_key_exists($keyword, $uniqueData)) {
                            // Unique key already exists, update the clicks, impressions, and add the date if it's not already present
                            $uniqueData[$keyword]['clicks'] += $row->getClicks();
                            $uniqueData[$keyword]['impressions'] += $row->getImpressions();
                            if (!in_array($date, $uniqueData[$keyword]['dates'])) {
                                $uniqueData[$keyword]['dates'][] = $date; // Append the date to the existing date
                            }
                            // Update the position
                            $uniqueData[$keyword]['position'] = $position;
                        } else {
                            // Unique key does not exist, initialize the entry
                            $uniqueData[$keyword] = array(
                                'clicks' => $row->getClicks(),
                                'impressions' => $row->getImpressions(),
                                'dates' => array($date), // Add the date as an array
                                'url' => $url, // Add the URL
                                'country' => $country, // Add the country code
                                'position' => $position // Add the position
                            );
                        }
                    }

                    // Output the uniqueData array
                    // show($uniqueData);
                } else {
                    // Date does not exist, initialize the entry
                    $uniqueData[$date] = array(
                        'clicks' => 0,
                        'impressions' => 0,
                        'keywords' => ""
                    );
                }
            ?>
                <tr class="thead">
                    <th>Keywords</th>
                    <th>Page</th>
                    <!-- <th>Country</th> -->
                    <th>Clicks</th>
                    <th>Impressions</th>
                    <th>Position</th>
                    <th>Date</th>
                </tr>
                <?php foreach ($uniqueData as $key => $data) : ?>
                    <tr>
                        <td style="white-space: pre;"><?= $key == $date ? "N/A" : $key ?></td>
                        <td><a href="<?= isset($data['url']) ? $data['url'] : "" ?>" target="_blank"><?= isset($data['url']) ? $data['url'] : "N/A" ?></a></td>
                        <!-- <td><?= isset($data['country']) ? strtoupper($data['country']) : "N/A" ?></td> -->
                        <td><?= isset($data['clicks']) ? $data['clicks'] : "N/A" ?></td>
                        <td><?= isset($data['impressions']) ? $data['impressions'] : "N/A" ?></td>
                        <td><?= isset($data['position']) ? $data['position'] : "N/A" ?></td>
                        <td><?= isset($data['dates'][0]) ? $data['dates'][0] : $date ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
<?php else : ?>
    <h1 class="title">Your Properties</h1>
    <?php if (!empty($user_data->properties)) : ?>
        <div class="card-grids">
            <?php foreach ($user_data->properties as $property) : ?>
                <a href="<?= ROOT . "dashboard?property=" . $property ?>" class="card"><?= $property ?></a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php $this->include_dashboard('footer', $data); ?>