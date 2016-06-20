<?PHP
class leads
{
var $activity_name = array();//list activity lead type
var $lead_status = array();// list status of lead
var $stage_name = array(); // list stage of opportunities
var $blue_alert;  // light alert 
var $read_alert;  // stop alert


function complete_lead($LeadID,$system_id) {
$dh = new database;
$results = $dh->query("select v.LeadID,v.system_id,v.AppNames,v.LastName,v.FirstName,v.Social,
v.DOB,v.Email,v.HomePhone,v.Created,v.Modified,v.Reminder,v.Notes,i.WorkPhone,i.CelPhone,
i.FaxPhone,i.Employer,i.Employer_Len,i.Empl_Self,i.Income,i.Co_LastName,i.Co_FirstName,
i.Co_Social,i.Co_Email,i.Co_HomePhone,i.Co_WorkPhone,i.Co_CelPhone,i.Co_Employer,i.Co_Empl_LEN,
i.Co_Empl_Self,i.Co_income,i.Mail_Street,i.Mail_City,i.Mail_State,i.Mail_Zip,i.Prop_Street,
i.Prop_City,i.Prop_State,i.Prop_Zip,i.Prop_County,i.Homeowner,i.Bankruptcy,i.Best_Time,i.Credit_Rating,i.Liquid_Cash,i.Gross_Income	,i.Gross_Balances,i.Gross_Payments,i.Housing_Ratio,i.Debt_Ratio,i.Prop_Type,i.Prop_Acquired,i.Prop_Purchase,i.Prop_Value,i.Prop_LTV	 from lead as v left join lead_addon as i on v.LeadID = i.LeadID where v.LeadID = $LeadID and v.system_id =$system_id");
foreach($results->results() as $row) {
$complete_lead[ ] = $row;
}
return $complete_lead;
}
	
 function get_LeadIDS($system_id) {
	$dh = new database;
	$results = $dh->query("select distinct LeadID, concat(LastName, ' ,',FirstName) as displayname from lead where system_id =$system_id order by LastName asc")->results();
	return $results;
}

function get_Names($system_id) {
	$dh = new database;
	$results = $dh->query("select uid,name from client_auth_users where system_id = $system_id")->results();
	if ($results) {
	foreach ($results as $myResults){
		$data[] = $myResults;
	}
	return $data;
	}
}

 function partial_lead($LeadID,$system_id) {
	$dh = new database;
	$results = $dh->query("select * from lead where LeadID = $LeadID and system_id = $system_id")->results();
	foreach ($results as $row) {
		$partial_lead[] = $row;
	}
	return $partial_lead;
}

 function opport($LeadID,$system_id) {
	$dh = new database;
	$results = $dh->query("select * from opportunity where LeadID = $LeadID and system_id = $system_id")->results();
	foreach ($results as $row){
		$opport[] = $row;
	}
	return $opport;
}

 function lead_status($system_id) {
	$dh = new database;
	$results = $dh->query("select v.LeadID,v.system_id,v.AppNames,v.LastName,v.FirstName,v.Social,
v.DOB,v.email,v.HomePhone,s.status_name from lead as v left join lead_status as s on v.LeadID = s.LeadID where system_id =$system_id order by s.status_name desc")->results();
	foreach($results as $row) {
		$lead_status[] = $row;
	}
	return $lead_status;
}

 function lead_activity($leadID,$system_id) {
	   $date = time();
	   $month =date("m",$date);
	   $year = date("Y",$date);
	   $to_date =date("Y-m-d",$date);
	$dh= new database;
	$results = $dh->query("select v.LeadID,v.system_id,v.AppNames,v.LastName,v.FirstName,s.id as aid,s.activity_name,s.date from lead as v left join lead_activity as s on s.uid = v.uid where v.LeadID = '$leadID' and v.system_id = '$system_id'and s.activity_name is not null order by date(s.date) desc limit 50")->results();
	foreach($results as $row) {
		$rows[] = $row;
	 }
	 return $rows;
}

function opp_activity($leadID,$system_id) {
	$dh= new database;
	$sql = "SELECT v.LEADID,v.uid,v.activity_name,v.date,c.name as person,o.name as company from opp_activity as v LEFT JOIN client_auth_users as c on c.uid = v.uid left join opportunity as o 
	on o.LeadID = v.LeadID where v.LeadID = $leadID and v.system_id =$system_id order by date(v.date) desc limit 50";
	$results = $dh->query($sql)->results();
	foreach($results as $row) {
		$rows[] = $row;
	 }
	 return $rows;
}
	

function all_lead_activity($uid,$system_id) {
 $date = time();
 $month =date("m",$date);
 $year = date("Y",$date);
 $to_date =date("Y-m-d",$date);
 $dh= new database;
 $results = $dh->query("select v.LeadID,v.system_id,v.AppNames,v.LastName,v.FirstName,s.activity_name,s.date,c.l_name,c.f_name from lead as v left join lead_activity as s on v.LeadID = s.LeadID left join client_auth_users as c on c.uid = s.uid where s.system_id = $system_id and s.activity_name is not null and Year(s.date)= '$year'")->results();
 foreach($results as $row) {
		$rows[] = $row;
	 }
	 return $rows;
}
 
 function show_opportunity($system_id) {
$dh= new database;
	$results = $dh->query("select v.LeadID,v.system_id,v.AppNames,v.LastName,v.FirstName, s.name,s.amount,s.probability,s.source,s.description,s.notes from lead as v left join opportunity as s on v.LeadID = s.LeadID where  v.system_id = $system_id order by s.create_date desc")->results();
	foreach($results as $row) {
		$show_opportunity[] = $row;
	}
	return $show_opportunity;
}	

 function show_opportunity_stage($system_id) {
	$dh = new database;
	$results = $dh->query("select v.LeadID,v.system_id,v.AppNames,v.LastName,v.FirstName, s.name,s.amount,s.probability,s.source,s.description,t.stage_name from lead as v left join opportunity as s on v.LeadID = s.LeadID left join opportunity_stage as t on t.LeadID = s.LeadID where v.system_id = $system_id order by t.create_date desc")->results();
	foreach($results as $row) {
		$show_opportunity_stage[] = $row;
	}
	return $show_opportunity_stage;
}	

 function activity_names() {
	$activity_name = array("email","telephone","face-to-face","meeting","conference call","lunch","dinner","party","video chat");
	return $activity_name;
}

 function lead_status_names() {
	$lead_status = array("do not contact","closed lost","closed won","in process","dead","new");
	return $lead_status;
	
}


 function opportunity_status_names() {

	$lead_status = array("closed won","closed losed","verbal","qualification","dead");
	return $lead_status;
}

function error($text) {
$msg = <<<EOT
<div class="alert alert-danger alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <i class="fa fa-remove pr10"></i>
  Oh snap! 
  <a href="#" class="alert-link">$text</a> 
</div
EOT;
echo $msg;
return $msg;

}

function warning($text) {
if ($text =="") {
$msg ="";
}else {
$msg = <<<EOT
<div class="alert alert-warning alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <i class="fa fa-warning pr10"></i>
  Warning! 
  <a href="#" class="alert-link">$text</a>
</div>

EOT;
echo $msg;
return $msg;
}
}


function alert($text) {
if ($text =="") {
$msg ="";
}else {
$msg = <<<EOT
<div class="alert alert-info alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <i class="fa fa-info pr10"></i>
  Heads up! 
  <a href="#" class="alert-link">$text</a></div>
EOT;
echo $msg;
return $msg;
}
}

function success($text) {
if ($text =="") {
$msg ="";
}else {
$msg = <<<EOT
<div class="alert alert-success alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <i class="fa fa-check pr10"></i>
  Well done! 
  <a href="#" class="alert-link">$text</a>
</div>
EOT;
echo $msg;
return $msg;
}
}


function flag($text) {
if ($text =="") {
$msg ="";
}else {
$msg = <<<EOT
<span class="flag">$text</span>
EOT;
echo $msg;
return $msg;
}
}

 function get_notes($LeadID,$system_id) {
$dh = new database;
$results = $dh->query("select id,system_id,uid,note,created_date from notes where LeadID = $LeadID and system_id = $system_id order by created_date asc")->results();
print'<table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Company Name or</th>
                      <th>Last Name</th>
                      <th>Note</th>
                      <th>Note by:</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                 <tr>';
foreach($results as $row) {
$id = $row['id'];
$uid= $row['uid'];
$system_id = $row['system_id'];
$note = $row['note'];
$date = $row['created_date'];
$name = get_others_name($uid,$system_id);
print"<td></td><td><input type='checkbox' id='checked[]' value=$id></td><td>$note</td><td>by: $name</td><td>$date</td>
</tr><tr>";
}
print"</tr></td></table>";
}


function delete_notes($id) {
if (isarray($id)) {
$dh = new database;
foreach($id as $value) {
$results = $dh->insert_query("delete from notes where id = $value");
$this->alert("successfully deleted");
}
} else {
$dh = new database;
$results = $dh->insertu_query("delete from notes where id = $id");
$this->alert("successfully deleted");
}
}

function insert_notes($acct_id,$system_id,$uid,$note) {
$note = cleanup_text($note);
$date =time();
$time = date("Y-m-d",$date); 
$dh = new database;
$results = $dh->insert_query("INSERT INTO `notes` VALUES (null,'$acct_id','$system_id','$uid','$note','$time')");
return $results;
}

function get_activity($acct,$system_id) {
$dh = new database;
$query = $dh->query("select id,system_id,uid,activity_name,comments,date from lead_activity where LeadID = $acct and system_id = $system_id order by create_date asc")->results();
print"<table class='table'>";
foreach($query as $row) {
$id = $row['id'];
$uid =$row['uid'];
$system_id = $row['system_id'];
$activity_name = $row['activity_name'];
$comments = $row['comments'];
$results = $row['results'];
$date = $row['date'];
$name = get_others_name($uid,$system_id);
print"<td><input type='checkbox' id='checked[]' value=$id></td><td>$date</td><td>$activity_name</td>
<td>by:$name</td><tr>";
}
print"</tr></td></table>";
}


function delete_activity($id) {
$dh = new database;
$results = $dh->insert_query("DELETE FROM `lead_activity` WHERE `id` = $id");
if ($results) {
$msg ="successfully deleted";
}else {
$msg ="unable to delete, please contact system administrator";
}
return $msg;
}

function insert_lead($LeadID,$system_id,$appnames,$lastname,$firstname,$social,$dob,$email,$homephone,$reminder,$notes,$uid,$status) {
$date =time();
$time = date("Y-m-d H:i:s",$date); 
$dh = new database;
$LeadID = cleanup_text($LeadID);
$uid = $uid;
$status = cleanup_text($status);
$system_id = $system_id;
$appnames = cleanup_text($appnames);
$lastname = cleanup_text($lastname);
$firstname = cleanup_text($firstname);
$social = cleanup_text($social);
$dob = cleanup_text($dob);
$email = cleanup_text($email);
$homephone = cleanup_text($homephone);
$reminder = cleanup_text($reminder);
$notes = cleanup_text($notes);
$results = $dh->insert_query("INSERT INTO `lead`(`id`, `LeadID`, `system_id`, `AppNames`, `LastName`, `FirstName`, `Social`, `DOB`, `Email`, `HomePhone`,`Created`,`Modified`,`Reminder`,`Notes`,uid) VALUES (null,'$LeadID','$system_id','$appnames','$lastname','$firstname','$social','$dob','$email','$homephone',now(),now(),'$reminder','$notes','$uid')");
if ($results) {
$query = $dh->insert_query("INSERT INTO lead_status VALUES ('$LeadID','$system_id','$status','$time','$time','$uid')");
$this->alert("successfully updated");
} else {
$this->error("system error unable to update");
}
}

function update_lead($LeadID,$system_id,$appnames,$lastname,$firstname,$social,$dob,$email,$homephone,$reminder,$notes) {
$date =time();
$time = date("Y-m-d H:i:s",$date); 
$dh = new database;
$LeadID = $LeadID;
$uid = $uid;
$system_id = $system_id;
$appnames = $appnames;
$lastname = $lastname;
$firstname = $firstname;
$social = $social;
$dob = $dob;
$email =$email;
$homephone = $homephone;
$reminder =$reminder;
$results = $dh->insert_query("UPDATE `lead` SET `LeadID`=\"$LeadID\",`system_id`=\"$system_id\",`Appnames`=\"$appnames\",`Lastname`=\"$lastname\",`Firstname`=\"$firstname\",`Social`=\"$social\",`DOB`=\"$dob\",`Email`=\"$email\",`Homephone`=\"$homephone\",`Modified`=\"$time\",`Reminder`=\"$reminder\",`Notes` =\"$notes\" WHERE `LeadID`=$LeadID and `system_id` = $system_id ");
if($results) {
	$this->alert("successfully updated");
}else {
	$this->error("system error unable to update");
	
}
}

function get_related_contacts($system_id,$LeadID) {
$dh= new database;
$result = $dh->query("select * from contact where system_id = $system_id and LeadID = $LeadID order by l_name ")->results();
if($result) {
print"<table>";
foreach($result as $row)
{
$id = $row['id'];
$title =$row['title'];
$first  =$row['f_name'];
$last =$row['l_name'];
$address = $row['address'];
$telephone = $row['telephone'];
$email = $row['email'];
print "<td>$title</td><td><a href='contact_list.php?stamp=$t_timestamp&master_id=$id' title='click to edit'>$first</a></td><td><a href='contact_list.php?stamp=$t_timestamp&master_id=$id' title='click to edit'>$last</a></td><td>$telephone</td><td>$email</td><tr><td colspan='5'><hr /></td><tr>";
}
print"</tr></td></table><br /><br />";
}
}

function num_Leads($system_id){
$dh = new database;
$query = $dh->query("select count(LeadID) as number from lead where system_id = $system_id")->results();
$numberofleads = $query[0]['number'];
return $numberofleads;
}

function num_Opp($system_id){
$dh = new database;
$squery = $dh->query("select count(LeadID) as leadnumber from opportunity where system_id = $system_id")->results();
$numberofopport = $squery[0]['leadnumber'];
return $numberofopport;
}


function num_Cont($system_id){ 
$dh = new database;
$nquery = $dh->query("select count(id) as countme from contact where system_id = $system_id")->results();
$numberofcontact= $nquery[0]['countme'];
return $numberofcontact;
}

function contDeadleads($system_id) {
$dh = new database;
$wquery = $dh->query("select count(LeadID) as mycount from lead_status where system_id = $system_id and status_name in ('closed won','closed lose','dead')")->results();
$deadleads = $wquery[0]['mycount'];
return $deadleads;
}
}
?>

