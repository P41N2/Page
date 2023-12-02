
<?php
$email_address = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

// Check for empty fields
if (empty($_POST['name']) ||
    empty($_POST['email']) ||
    empty($_POST['phone']) ||
    empty($_POST['message']) ||
    !$email_address) {
    echo "No arguments Provided!";
    return false;
}

$name = $_POST['name'];
$phone = $_POST['phone'];
$message = $_POST['message'];

// Set Formspree form endpoint URL
$formspreeEndpoint = 'https://formspree.io/f/xbjvvbaq';

// Prepare Formspree submission data
$formData = [
    'name' => $name,
    'email' => $email_address,
    'phone' => $phone,
    'message' => $message
];

// Encode form data to JSON
$jsonData = json_encode($formData);

// Send POST request to Formspree API
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $formspreeEndpoint);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($curl);
$curlErrno = curl_errno($curl);
curl_close($curl);

if ($curlErrno) {
    echo "Error sending to Formspree: " . curl_error($curl);
    return false;
}

$httpResponseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ($httpResponseCode !== 200) {
    echo "Formspree submission failed with HTTP code: " . $httpResponseCode;
    return false;
}

echo "Form submitted successfully!";
return true;

