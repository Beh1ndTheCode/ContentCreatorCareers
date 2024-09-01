<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";

$main = new Template("frame");
$body = new Template("job_single");

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
} else {
    die ("Invalid request, missing required parameters.");
}

$result = $mysqli->query("
    SELECT
	    job_offer.name AS job_name, 
	    job_offer.type AS job_type,
	    job_offer.description AS job_description,
	    job_offer.salary AS job_salary,
	    job_offer.date AS job_date,
	    requirement.name AS requirement_name,
	    requirement.level AS requirement_level,
	    requirement.description AS requirement_description,
		employer.name AS employer_name,
		profile.phone AS employer_number, 
        profile.email AS employer_email, 
        social_account.uri AS employer_website,
        image.path AS employer_image,
		address.city AS city, 
		address.country AS country,
		address.postal_code AS postal_code,
		address.street AS street,
		address.civic AS civic
	FROM 
	    job_offer
    LEFT JOIN 
	    requirement ON requirement.job_offer_id = job_offer.id
	JOIN 
        employer ON job_offer.employer_id = employer.id
    JOIN 
        profile ON profile.id = employer.id
    LEFT JOIN 
        social_account ON employer.id = social_account.profile_id AND social_account.name = 'Website'
    LEFT JOIN 
        image ON image.profile_id = employer.id AND image.type = 'profilo'
	LEFT JOIN 
        address ON employer.id = address.profile_id
    WHERE 
        job_offer.id = $id 
	");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

if ($result->num_rows === 0) {
    die("Job Offer not found.");
}

$data = $result->fetch_assoc();
$image = $data['employer_image'] ?? 'skins/jobhunt/images/profile.png';
$website = $data['employer_website'] ?? 'N/A';
$phone_num = $data['employer_number'] ?? 'N/A';
$email = $data['employer_email'] ?? 'N/A';
$date = DateTime::createFromFormat('Y-m-d', $data['job_date'])->format('F j, Y');
$type = match ($data['job_type']) {
    "Full time" => 'ft',
    "Part time" => 'pt',
    default => 'fl',
};
$city = $data['city'] ?? 'Unknown city';
$country = $data['country'] ?? 'Unknown country';
$postcode = $data['postal_code'] ?? false;
$street = $data['street'] ?? false;
$civic = $data['civic'] ?? false;
if (!$postcode || !$street || !$civic) {
    $body->setContent("address", 'Address not found');
} else {
    $address = $street . ' ' . $civic . ', ' . $postcode;
    $body->setContent("address", $address);
}

$body->setContent("job_name", $data['job_name']);
$body->setContent("type", $type);
$body->setContent("job_type", $data['job_type']);
$body->setContent("job_description", $data['job_description']);
$body->setContent("requirement_name", $data['requirement_name']);
$body->setContent("requirement_level", $data['requirement_level']);
$body->setContent("requirement_description", $data['requirement_description']);
$body->setContent("job_salary", $data['job_salary']);
$body->setContent("job_date", $date);
$body->setContent("employer_name", $data['employer_name']);
$body->setContent("employer_number", $phone_num);
$body->setContent("employer_email", $email);
$body->setContent("employer_website", $website);
$body->setContent("employer_image", $image);
$body->setContent("city", $city);
$body->setContent("country", $country);

$requirements = $mysqli->query("
    SELECT requirement.name, requirement.level, requirement.description
    FROM requirement
    JOIN job_offer ON job_offer.id = $id");
$requirements_html = '';
while ($requirement = $requirements->fetch_assoc()) {
    $requirement_name = $requirement['name'];
    $requirement_level = $requirement['level'];
    $requirement_description = $requirement['description'] ?? 'No description provided';
    $requirements_html .= "<li>
                                <strong>$requirement_name</strong> - $requirement_level/10
                                <p>$requirement_description</p>
                            </li>";
}
$body->setContent("requirements", $requirements_html);

$main->setContent("body", $body->get());

$main->close();
