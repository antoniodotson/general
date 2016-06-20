<?php
require_once("header.php");
?>
<script src="leads.js"></script>
<script>
$(document).ready(function() {

	$('.popup-with-move-anim').magnificPopup({
		type: 'inline',

		fixedContentPos: false,
		fixedBgPos: true,

		overflowY: 'auto',

		closeBtnInside: true,
		preloader: false,
		
		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-slide-bottom'
	});
});
</script>

<?php
function nav($offset=0, $this_script='leads.php', $limit=DEFAULT_LIMIT)
{
	$offset= (int)$offset;
	$limit = (int)$limit;
	$t_timestamp = key;
	if (empty($this_script) or
	dirname(realpath(_FILE_)) !=dirname(realpath($this_script))
	)
	{
	$this_script = $_SERVER['PHP_SELF'];
	}
	
	$dh= new database;
	$results= $dh->query("select count(LeadID) as count from lead where system_id =SYSTEM_ID")->results();
	$total_rows = $results['count'];
	
		print "<p>\n";
	if ($offset > 0) 
	{
	$poffset = $offset - $limit < 0 ? 0 : $offset - $limit;
	print"<a class='ybutton' href='leads.php?stamp=$t_timestamp&offset=$poffset'>Previous</a>&nbsp";
	}
	if ($offset + $limit < $total_rows)
	{
	$noffset = $offset + $limit;
	print "<a class='ybutton' href='leads.php?stamp=key&offset=$noffset'>Next</a>&nbsp";
	}
	print "</p>\n";
}

?>


<?php 
function select_entries ($offset=0,$system_id, $limit=DEFAULT_LIMIT) 
{
$limit = (int)$limit;
$offset =(int)$offset;
$dh= new database;
$results = $dh->query("select v.id,v.LeadID,v.system_id,v.AppNames,v.LastName,v.FirstName,v.HomePhone,v.uid,s.status_name from lead as v left join lead_status as s on v.LeadID = s.LeadID where s.status_name != 'close' and s.status_name != 'dead' and s.status_name !='do not call' and v.system_id =$system_id  order by s.status_name,LastName asc limit $offset, $limit")->results();
return $results;
}
?>
<?php
   if (isset($_GET['message'])) {
	$msg = $_GET['message'];
	print "<script>alert('$msg')</script>";
}
?>
<section id="content" class="animated fadeIn">
 <div class="tray tray-center">

          <div class="mw1200 center-block">


<div class="panel">
  <div class="panel-heading panel-visible">
    <span class="panel-title">Leads</span>
    <div class="widget-menu pull-right mr10">
      <div class="btn-group">
      <a class="popup-with-move-anim" href="#small-dialog">
        <button type="button" class="btn btn-xs btn-primary">
          <span class="glyphicon glyphicon-warning-sign fs11 mr5"></span>Help</button>
        </a>
      </div>
      <div class="btn-group">
        <button type="button" class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
          <span class="glyphicon glyphicon-user mr5"></span>Action</button>
        <ul class="dropdown-menu checkbox-persist pull-right text-left" role="menu">
          <li><a href="#" class="btn-success" onclick="create()">Create Lead</a></li>
          <li><a href="#" class="btn-info" onclick="view_account()">Account Details</a></li>
          <li><a href="#" class="btn-warning" onclick="update()">Update</a></li>
          <li><a href="#"class="btn-danger" onclick="deleteAcct()">Delete</a></li>
        </ul>
      </div>
    </div>
  </div>
<div class="panel-body">

<?php 
define('DEFAULT_LIMIT',50);
$offset = array_key_value($_REQUEST,'offset',0);
$result = select_entries($offset,$system_id);
?>
<?php
print' <div class="panel">

            <div class="panel-menu p12 admin-form theme-primary">

              <div class="row">

                <div class="col-md-5">

                  <label class="field select">

                    <select id="filter-category" name="filter-category">

                      <option value="0">Filter by Category</option>

                      
                    </select>

                    <i class="arrow"></i>

                  </label>

                </div>

                <div class="col-md-5">

                  <label class="field select">

                    <select name="sort" id="myselect">
					<option value="0">Sort by Status</option>
					<option>do not call</option><option>closed won</option><option>closed lost</option><option>in process</option><option>new</option><option>dead</option>
                    </select>

                    <i class="arrow"></i>

                  </label>

                </div>

                

              </div>

            </div>

            <div class="panel-body pn">

              <div class="table-responsive">

                <table class="table admin-form theme-warning tc-checkbox-1 fs13">

                  <thead>

                    <tr class="bg-light">
                      
                      <th class=""><input type="checkbox" id="echeck" ></th>
                      
                      <th class="">Account</th>

                      <th class="">Company Name</th>

                      <th class="">Name</th>

                      <th class="">Phone</th>

                     
                      <th class=""><a class="popup-with-move-anim" href="#small-form">Owner</a></th><th>';
					  if ($result)
{
print'
<form id="aForm" name="aForm">
<label>Status</label></th><th><select name="status">';
$lead = new leads;
$rows = $lead->lead_status_names();
foreach($rows as $value) {
print"<option>$value</option>";
}
print'
</select></th><th class="text-right">
<a href="#" class="ybutton" onclick="statusME()">Change Status</a></th>
                      



                    </tr>

                  </thead>

                  <tbody>

                    <tr>';

foreach($result as $row)
{
$id = $row['id'];
$acct_id =$row['LeadID'];
$name =$row['AppNames'];
$lastname =$row['LastName'];
$firstname = $row['FirstName'];
$system_id = $row['system_id'];
$homephone = $row['HomePhone'];
$status = $row['status_name'];
$uid = $row['uid'];
$owner =  get_others_name($uid,$system_id);
print "<td>&nbsp;</td><td><input type='checkbox' name='acctnumber[]' id='acct' class='clickon' value=$acct_id >$acct_id</td><td><a href='complete_leads.php?stamp=$t_timestamp&acct_id=$acct_id' title='click to see more'>$name</a></td><td><a href='complete_leads.php?stamp=$t_timestamp&acct_id=$acct_id' title='click to see more'>$lastname $firstname</a></td><td>$homephone</td><td>$owner</td><td><button class='btn btn-xs btn-dark'>$status</button></td><tr>";
}
print <<<EOT
   <input type="hidden" name="sform" value="random"></form></tr></td></table><br /><br /><div id="small-dialog" class="zoom-anim-dialog mfp-hide">
	<h3>Information Dialog</h3>
	<p>Any information under the Leads Tab are considered leads. If the status of the lead changes to dead, closed won, closed loss or do not contact, the Lead will be transferred to the Account Tab. In order to status, update,delete or sort a Lead, please select it by clicking the checkbox and selecting either status, update, sort or delete button.</p>
</div>
EOT;
print "<div id=\"small-form\" class=\"zoom-anim-dialog mfp-hide\">";
print "<form method=\"post\" name=\"changeForm\" id=\"changeForm\">";
print '<div class="panel panel-primary">
  <div class="panel-heading">
    <span class="panel-title">Change Lead or Assign Lead</span>
	</div><div class="panel-body"><ul>';
$system_id = SYSTEM_ID;
$rows = get_connections($system_id);
if (is_array($rows)) {
foreach ($rows as $row => $k) {
	extract($k);
print"<br /><li><input type=\"checkbox\" name=\"number\" class=\"nameofchange\" value=$uid> ".$names."</li><br />";
}
print'</ul></form>';
print'<button class="btn btn-success" onclick="changeME()">Change</button>';
print'</div></div>';
nav($offset);
}
else
{
$lead = new leads;
$lead->alert("Contact information has not been entered");
} 
}else {
}
?>


</div></div></section>

<?php require_once("footer.php"); ?>
