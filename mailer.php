<?php
    // My modifications to mailer script from:
    // http://blog.teamtreehouse.com/create-ajax-contact-form
    // Added input sanitizing to prevent injection
    require '../../vendor/autoload.php';
    require '../../app/ProcessForm.php';

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mailArray = [];
        // Get the form fields and remove whitespace.
        $name = htmlentities(trim($_POST["name"]));
		$mailArray['name'] = str_replace(array("\r","\n"),array(" "," "),$name);
        $mailArray['email'] = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $mailArray['message'] = htmlentities(trim($_POST["message"]));

        // Check that data was sent to the mailer.
        if ( empty($mailArray['name']) OR empty($mailArray['message'])) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Name and Comment are required. Please complete the form and try again.";
            exit;
        }

        $actor = new ProcessForm;
        $locmail = $actor->setSmtpData();
        $actor->processEntry($locmail, $mailArray);

    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>
