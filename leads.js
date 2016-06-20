// Antonio Dotson 2016 - general javascript for leads.php

$(document).ready(function () {
	"use strict";
  $("#myselect").change(function () {
 var sort = $( "#myselect option:selected" ).text();
 window.location.assign('sort.php?stamp=key&sort='+sort+'');
});
$("#echeck").on("click", function(e) {
	  if(this.checked) {
      // Iterate each checkbox
      $('input:checkbox').each(function() {
          this.checked = true;
      });
  }
  else {
    $('input:checkbox').each(function() {
          this.checked = false;
      });
  }
  });
});

function create() {
window.location.assign("create_lead.php?stamp=key");
}

 function update() {
	var clicked = $(".clickon:checkbox:checked").val();
          if (clicked) { 	
	        window.location.assign('update_status.php?stamp=key&acctnumber='+clicked +'');
	         } else {//
	      alert("Please select an account number");
	}
 }

 function view_account() {
	var clicked = $(".clickon:checkbox:checked").val();
          if (clicked) { 	
	        window.location.assign('complete_leads.php?stamp=key&acct_id='+clicked +'');
	         } else {//
	      alert("Please select an account number");
	}
 }

 
function showHint(val) {
var q = val;
$.ajax({
  url: "search_accounts.php?stamp=<?php echo $t_timestamp; ?>",
  data:{q: q},
}).done(function( result ) {
 $("#txtHint").html( result );
 });
}

function reloadME() {
location.reload();
}

function deleteAcct() {
	var confirmME = confirm("This action will permanently delete Lead(s)");
	if(confirmME) {
	      $.post( 
               "delete_account.php?stamp=<?php echo $t_timestamp; ?>",
                $("#aForm").serialize(),
	            function(data) {
                $('.response').html(data);
				$('.response').fadeOut(10000);
				setTimeout("reloadME()", 1000); 
			   
				
            }
         );
	}
 }

function statusME() {
	var clicked = $(".clickon:checkbox:checked").val();
          if (clicked) { 
	     $.post( 
               "status_account.php?stamp=key",
                $("#aForm").serialize(),
	             function(data) {
              $('.response').html(data);
			 $('.response').fadeOut(10000);
			 $('input:checkbox').prop("checked", false);
			setTimeout("reloadME()", 1000); 
			   
				
            }
       );
	 } else {//
	      alert("Please select an account number");
	}
 }

function changeME() {
	var clicked = $(".nameofchange:checkbox:checked").val();
    var accountID = $(".clickon:checkbox:checked").val();
	if (accountID) {
	$.ajax({
        type: "POST",
        url: "change_owner.php?stamp=key",
        data: {uid:clicked, account:accountID,},
				})
        .done(function(result) {
 $(".response").html(result);
   $('.response').fadeOut(3000); 
   setTimeout("reloadME()", 1000);   
		});
	} else {
		alert("please select an account to change");
	}

	//     $.post( 
    //           "change_owner.php?stamp=key",
    //            $("#changeForm").serialize(),
	//             function(data) {
    //          $('.response').html(data);
	//		  $('.response').fadeOut(3000);
	//		setTimeout("reloadME()", 1000); 
			   
			
//            }
 //      );
	
	 }


