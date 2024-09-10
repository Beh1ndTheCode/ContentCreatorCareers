* **Address** (<ins>profile_id</ins>, country, postcode, city, street, civic) <br>
* **Application** (<ins>candidate_id</ins>, <ins>job_offer_id</ins>, date) <br>
* **Candidate** (**<ins>id</ins>**, name, surname, age, <ins>language_id</ins>) <br>
* **Content** (<ins>service_id</ins>, **name**, title, subtitle, body, image)
* **Employer** (**<ins>id</ins>**, name, since) <br>
* **Expertise** (**<ins>id</ins>**, **title**) <br>
* **Image** (**<ins>id</ins>**, <ins>profile_id</ins>, label, **path**) <br>
* **Job** (**<ins>id</ins>**, <ins>employer_id</ins>, <ins>candidate_id</ins>, name, type, start, end, description) <br>
* **Job_offer** (**<ins>id</ins>**, <ins>employer_id</ins>, name, salary, type, <ins>language_id</ins>, quantity,
  description, date) <br>
* **Language** (**<ins>id</ins>**, **name**)
* **Profile** (**<ins>id</ins>**, <ins>user_id</ins>, email, phone, description) <br>
* **Profile_expertise** (<ins>profile_id</ins>, <ins>expertise_id</ins>, experience) <br>
* **Requirement** (<ins>job_offer_id</ins>, name, level, description) <br>
* **Role** (**<ins>id</ins>**, <ins>name</ins>, description) <br>
* **Role_service** (<ins>role_id</ins>, <ins>service_id</ins>) <br>
* **Service** (**<ins>id</ins>**, **name**, description, script) <br>
* **Skill** (<ins>candidate_id</ins>, **name**, level, description) <br>
* **Social_account** (<ins>profile_id</ins>, **name**, uri) <br>
* **User** (**<ins>id</ins>**, **username**, **email**, password) <br>
* **User_role** (<ins>username</ins>, <ins>role_id</ins>) <br>

|      Attributo      |    Significato     |
|:-------------------:|:------------------:|
| **<ins>Nome</ins>** |  Chiave primaria   | 
|      **Nome**       |  Attributo chiave  |
|   <ins>Nome</ins>   |   Chiave esterna   |
|        Nome         | Attributo semplice |