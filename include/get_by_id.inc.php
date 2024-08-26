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
        job.name AS name,
        job.first_work_date AS start,
        job.last_work_date AS end,
        job.description AS description,
        employer.name AS emp_name
    FROM `job`
        JOIN `profile` ON profile.id = job.candidate_id 
        JOIN `employer` ON employer.id = job.employer_id 
        WHERE profile.id = '$profile_id'
    ");
    while ($row = $sql_job->fetch_assoc()) {
        if ($row['end'] == null)
            $row['end'] = 'now';
        $jobs[] = [
            'name' => $row['name'],
            'emp_name' => $row['emp_name'],
            'start' => $row['start'],
            'end' => $row['end'],
            'description' => $row['description']
        ];
    }
    return $jobs;
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
        JOIN `job_offer` ON employer.id = job_offer.employer_id
        JOIN `address` ON employer.id = address.profile_id
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
    $sql_imgs = $mysqli->query("SELECT image.path FROM `image` WHERE image.profile_id = '$profile_id' and image.type = 'portfolio'");
    while ($row = $sql_imgs->fetch_assoc()) {
        $imgs[] = $row['path'];
    }
    return $imgs;
}
