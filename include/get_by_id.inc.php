<?php
function get_img($mysqli, $profile_id)
{
    $img = $mysqli->query("SELECT image.path FROM `image` WHERE image.profile_id = '$profile_id'");
    //$img = a immagine profilo se esiste, altrimenti è impostata su un placeholder
    if ($img->num_rows == 0)
        return "skins/jobhunt/images/profile.png";
    return ($img->fetch_array())[0];
}

function get_skills($mysqli, $profile_id)
{
    $sql_skills = $mysqli->query("SELECT skill.name FROM `skill` WHERE skill.candidate_id = '$profile_id'");
    while ($row = $sql_skills->fetch_assoc())
        $skills[] = $row['name']; // Aggiungi ogni skill all'array
    return $skills;
}

function get_socials($mysqli, $profile_id)
{
    $sql_social = $mysqli->query("SELECT social_account.name,social_account.uri FROM `social_account` JOIN `profile` ON profile.id = social_account.profile_id WHERE profile_id = '$profile_id'");
    while ($row = $sql_social->fetch_assoc())
        $socials[] = ['name' => $row['name'], 'uri' => $row['uri']];
    return $socials;
}
?>