<?php
function get_img($mysqli, $profile_id)
{
    $img = $mysqli->query("SELECT image.path FROM `image` WHERE image.profile_id = '$profile_id' and image.type = 'profilo'");
    //$img = a immagine profilo se esiste, altrimenti è impostata su un placeholder
    if ($img->num_rows == 0)
        return "skins/jobhunt/images/profile.png";
    return ($img->fetch_array())[0];
}

function get_skills($mysqli, $profile_id)
{
    $skills = [];
    $sql_skills = $mysqli->query("SELECT skill.name,skill.level FROM `skill` WHERE skill.candidate_id = '$profile_id'");
    while ($row = $sql_skills->fetch_assoc())
        $skills[] = ['name' => $row['name'], 'level' => $row['level']]; // Aggiungi ogni skill all'array
    return $skills;
}

function get_socials($mysqli, $profile_id)
{
    $socials = [];
    $sql_social = $mysqli->query("SELECT social_account.name,social_account.uri FROM `social_account` JOIN `profile` ON profile.id = social_account.profile_id WHERE profile_id = '$profile_id'");
    while ($row = $sql_social->fetch_assoc())
        $socials[] = ['name' => $row['name'], 'uri' => $row['uri']];
    return $socials;
}
function get_jobs($mysqli, $profile_id)
{
    $sql_job = $mysqli->query("
    SELECT
        job.id AS id,
        job.name AS name,
        job.type AS type,
        job.first_work_date AS start,
        job.last_work_date AS end,
        job.description AS description,
        employer.name AS emp_name
    FROM `job`
        JOIN `profile` ON profile.id = job.candidate_id 
        JOIN `employer` ON employer.id = job.employer_id 
        WHERE profile.id = '$profile_id'
    ");
    $jobs = [];
    while ($row = $sql_job->fetch_assoc()) {
        if ($row['type'] == 'current')
            $row['end'] = 'now';
        $jobs[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'emp_name' => $row['emp_name'],
            'start' => $row['start'],
            'end' => $row['end'],
            'description' => $row['description']
        ];
    }
    return $jobs;
}

function get_current_job($mysqli, $profile_id)
{
    $sql_job = $mysqli->query("
    SELECT
        job.name AS name,
        job.type AS type,
        job.first_work_date AS start,
        job.description AS description,
        employer.name AS emp_name
    FROM `job`
        JOIN `profile` ON profile.id = job.candidate_id 
        JOIN `employer` ON employer.id = job.employer_id 
        WHERE profile.id = '$profile_id'
        AND job.type = 'current';
    ");
    if ($sql_job->num_rows == 0) {
        $job = [
            'name' => '',
            'emp_name' => '',
            'start' => '',
            'type' => '',
            'description' => ''
        ];
        return $job;
    }
    return $sql_job->fetch_assoc();
}
function get_job_offers_employer($mysqli, $employer_id)
{
    $sql_job_offer = $mysqli->query("
    SELECT
        job_offer.id AS id,
        job_offer.name AS name,
        job_offer.type AS type,
        employer.name AS emp_name,
        address.city AS city,
        address.country AS country
    FROM `employer`
        LEFT JOIN `job_offer` ON employer.id = job_offer.employer_id
        LEFT JOIN `address` ON employer.id = address.profile_id
        WHERE employer.id = '$employer_id'
    ");
    while ($row = $sql_job_offer->fetch_assoc()) {
        $job_offers[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'type' => $row['type'],
            'emp_name' => $row['emp_name'],
            'city' => $row['city'],
            'country' => $row['country']
        ];
    }
    return $job_offers;
}

function get_portfolio($mysqli, $profile_id)
{
    $imgs = [];
    $sql_imgs = $mysqli->query("SELECT image.id, image.path FROM `image` WHERE image.profile_id = '$profile_id' and image.type = 'portfolio'");
    while ($row = $sql_imgs->fetch_assoc()) {
        $imgs[] = [
            'id' => $row['id'],
            'path' => $row['path']
            ];
    }
    return $imgs;
}

function get_applied_jobs($mysqli, $profile_id)
{
    $applied_jobs = [];
    $sql_applied_jobs = $mysqli->query("
        SELECT 
            application.date AS date,
            job_offer.id AS job_id,
            job_offer.name AS job_name,
            employer.name AS emp_name,
            address.city AS city,
            address.country AS country
        FROM candidate
        LEFT JOIN application ON application.candidate_id = candidate.id
        LEFT JOIN job_offer ON job_offer.id = application.job_offer_id
        LEFT JOIN employer ON employer.id = job_offer.employer_id
        LEFT JOIN address ON address.profile_id = employer.id
        WHERE candidate.id = '$profile_id'
    ");
    while ($row = $sql_applied_jobs->fetch_assoc()) {
        $applied_jobs[] = [
            'date' => $row['date'],
            'job_id' => $row['job_id'],
            'job_name' => $row['job_name'],
            'emp_name' => $row['emp_name'],
            'city' => $row['city'],
            'country' => $row['country']
        ];
    }
    return $applied_jobs;
}