# Set up a Google Cloud Platform Project:
Go to the Google Cloud Console (https://console.cloud.google.com/).
Create a new project or select an existing one.
Enable the Google Search Console API for your project.

# Create Credentials:
Navigate to the "Credentials" section in the Google Cloud Console.
Create a new service account key and download the JSON file containing your credentials.

# Install Google API Client Library via Composer:
Create a composer.json file in your project directory.
> app/api

Add "google/apiclient": "^2.0" to the require section.

Run to install the library and its dependencies.
```
composer install
```

# Compile Tailwind to CSS
Run this command to compile website css
```
npm run build
```

Run this command to compile dashobard css
```
npm run dash
```