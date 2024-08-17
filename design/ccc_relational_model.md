* **Service** (**<ins>id</ins>**, <ins>name</ins>, description, script) <br>
* **Role** (**<ins>id</ins>**, <ins>name</ins>, description) <br>
* **Role_service** (<ins>role_id</ins>, <ins>service_id</ins>) <br>
* **User** (**<ins>id</ins>**, <ins>username</ins>, email) <br>
* **User_role** (<ins>user_id</ins>, <ins>role_id</ins> <br>
* **Profile** (**<ins>id</ins>**, <ins>user_id</ins>, phone, description) <br>
* **Address** (<ins>profile_id</ins>, country, postal_code, city, street, civic) <br>
* **Social_account** (<ins>profile_id</ins>, <ins>name</ins>, uri) <br>
* **Expertise** (**<ins>id</ins>**, <ins>title</ins>) <br>
* **Profile_expertise** (<ins>profile_id</ins>, <ins>expertise_id</ins>) <br>
* **Image** (<ins>profile_id</ins>, label, path) <br>
* **Candidate** (**<ins>profile_id</ins>**, name, surname, age) <br>
* **Skill** (<ins>candidate_id</ins>, <ins>name</ins>, level, description) <br>
* **Employer** (****<ins>profile_id</ins>**, name) <br>
* **Job_offer** (**<ins>id</ins>**, <ins>employer_id</ins>, <ins>name</ins>, salary, description) <br>
* **Requirement** (<ins>job_offer_id</ins>, name, level, description) <br>
* **Application** (<ins>candidate_id</ins>, <ins>job_offer_id</ins>, date) <br>
* **Job** (<ins>employer_id</ins>, <ins>candidate_id</ins>, type, period) <br>
