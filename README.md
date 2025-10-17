# L3-1.1
TEAM MEMBERS : DJEDJIG Yasmine - HELLAL Sara - LACHI Maram - MEKLATI Kenza - MOUFOUKI Warda 

Log in : via the access point (a website page) each person can see and search different clinics, when a clinic is chosen a log in button appears, in case the person doesn’t have an account he will be directed to a form where he can fill his info to create one, but if he already has an account he will be asked about his role (doctor / assistant / or patient) if the person is a doctor or an assistant he will have to authenticate using the clinic ID and password then if approved he’ll authenticate for the 2nd time using his email and password, if the person is a patient he’ll authenticate using his email and password only.

Functionalities :
. Super admin : he can create/delete/ modify a clinic and manage subscription, each clinic will have its history of subscriptions saved in a table, in case the clinic stopped subscribing the system will archive the information of the clinic for 30 days in case the clinic subscribed again otherwise it’ll be deleted automatically after 30 days, for a clinic to upgrade or downgrade it subscription it’ll have to send a request which will be then accepted or refused by the super admin depending on the payement policy 
.admin doctor: in addition to the normal doctor functionalities, an admin doctor can add/modify/delete a doctor or an assistant, he can also send a subscription request and can have a global overlook of the clinic through reports
. the patient: -Book an appointment: an interface where there's a table containing days and times,available days(green) ,first come first served (orange), and fully booked days(red) If the patient doesn't find an available day he'll try the next day ,the calendar will refresh the day's availability after 24h. This table will be attached to the doctor's calendar. After selecting the appointment the patient will fill a confirmation form to confirm his booked appointment. After this the new appointment will appear on his upcoming appointments page and he will be added automatically to the list of requests.

-upcoming appointments: an interface where he'll find his booked and confirmed appointments(in case of he wants to cancel the appointment he has to call the assistant or the doctor).

-history of consultations: an interface containing the list of his consultations containing everything the doctor wrote+the amount paid +a link that goes to the referral letters page where he'll find all the tests/medical certificate..ect required. -info:an interface where he'll find everything about the medical office (name,phone number, doctor's name, doctor's email,...ect) Profile:personal information:name,phone number, email,password..ect of the patient. He can modify his information or change his password and to do that he will click the link forgot password and that will land him on a page that will ask him to put his email in order for him to receive a confirmation code and after finihing this step and clicking on the ok button another page will pop up so he can input his new password and verify it if it's correct then the confirmation button will work if not the he will have to correct

Disconnect button: to log out from his account and go back to the login page. ii.for the assistant:

-Calendar: the assistant can access the Calendar to see the available days but she can't modify it .

-manage appointments: this function will open the upcoming appointments lists ,the assistant can : ▪add an appointment:
1.if the patient exists:the upcoming appointments page will show and we'll add the patient and there is two cases if the patient exists the assistant will add him right up if not then the assistant will click on a click that will land him on the add new patient then he will add said patient and then after clicking the okay button the patient will added to the data base and to the waiting list

▪Cancel an appointment: In the appointments list the assistant searches the name of the patient selects it and then he will click on the button cancel if the patient wants to modify his next appointment then the assistant will have to cancel the appointment and add a new one .

-Requests: the assistant can confirm or reject requests depending on the doctor's availability. After clicking on the request link he will land on the request list next to every patient there's two buttons confirm and refuse.

-Patients: the assistant can: ▪see the list of patients .

-Add a patient:as a shortcut button in the home interface. This button will take us to another interface to fill the patient's information form. If the patient is a minor we fill both his form and his parent's form. ▪search for a patient: by filters name,age..ect.

▪modify patient: through selecting a patient then clicking the modify button in the patient list, the assistant can acces and edit the info form

-Waiting list: contains the list of today's appointments. In the open days (orange), if the patient doesnt have an account then the assistant will have to create an account for him and add his name to the waiting list by clicking the button ok which lead to the appointment list.But if said patient is already registered then the assistant will simply click the button add the appointment. -Profile: the assistant has access to his profile through the dashboard that will help him modify his information with the status data (active not active)

. the doctor: -Calendar:this table gives the doctor the hand to input how many patients he wants to see that specific day for the week. on his off days the input will be a symbol to refuse appointments that day. -Manage appointment:after clicking on it the link will take the doctor to the upcoming patients list there he will find two buttons: .add appointment: this would be in case of an emergency appointment and the doctor needs to see that specific patient later on that week or so ,after clicking on it the list of patients will pop up and he will look for the patient's name selects them then this patient will automaticaly be added to the list of appointments but before that the calendar will show up and the doctor will have to choose the date and approves it.(the number will decrease that day) .cancel: this would give the doctor the hand to the doctor to delete a certain an appointment, he can also send and recieve a patient's file from another doctor from the same clinic.

-Patients and history : Contains the list of patients, the ability to delete, add or modify one by selecting it, plus a button "view" to either see a patient's history consultations or his personal information, in the history page each consultation has its details in another page which by itself can lead to reffral letters pages the doctor might write, we can add a new consultation through the history page. The info page is a read only and can't be modified.

-Requests: A list containing the patients names and the time they chose to have an appointment at, if the appointment is confirmed the row is automatically transfered to the upcoming appointments list (doctor's and patient's) then deleted from the requests, the number of appointments in the calender will consequently decrease, if the appointment is refused the row will be deleted. The patient will be notified in both cases with the response. Finance: The doctor will manage both patients payments and assistant payments, for the patients a table with five columns containing the date of the consultation, the patient's name, the total amount and the amount paid by him and a status which will be automatically filled with either "paid" or "unpaid" depending on the difference between the total amount and the amount paid. We can add a payment (row) and search a patient, and the total of earnings will also be calculated and displayed. For the assistant a table of 4 columns containing the month, assistant name, salary and status will be filled by the doctor, a new payment can be added.




