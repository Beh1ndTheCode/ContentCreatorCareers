<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";
require "include/get_by_id.inc.php";

$main = new Template("frame");
$body = new Template("candidates_applied_jobs");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$id = ($profile_id->fetch_assoc()['id']);

$result = $mysqli->query("
    SELECT
        candidate.name AS name,
        candidate.surname AS surname
    FROM candidate
    WHERE candidate.id = '$id'
    ");
$data = $result->fetch_assoc();

$body->setContent("name", $data['name']);
$body->setContent("surname", $data['surname']);

$applied_jobs = get_applied_jobs($mysqli, $id);
$applied_jobs_html = '';
foreach ($applied_jobs as $applied_job) {
    $formatted_date = DateTime::createFromFormat('Y-m-d', $applied_job['date'])->format('F j, Y');
    $applied_jobs_html .= " <tr>
							    <td>
									<div class='table-list-title'>
										<i>{$applied_job['job_name']}</i><br />
										<span><i class='la la-map-marker'></i>{$applied_job['city']}, {$applied_job['country']}</span>
									</div>
								</td>
								<td>
									<div class='table-list-title'>
										<h3><a href='#' title=''>{$applied_job['emp_name']}</a></h3>
    								</div>
								</td>
								<td>
									<span>$formatted_date</span><br />
								</td>
								<td>
									<ul class='action_job'>
										<li><span>Delete</span><a href='#' title=''><i
											class='la la-trash-o'></i></a></li>
									</ul>
						    	</td>
							</tr>";
}
$body->setContent("applied_jobs", $applied_jobs_html);

$main->setContent("body", $body->get());

$main->close();
