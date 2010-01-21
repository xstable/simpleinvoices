<?php
/*
* Script: email.php
* 	Email invoice page
*
* License:
*	 GPL v3 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$biller_id = $_GET['biller_id'];
$customer_id = $_GET['customer_id'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];
$show_only_unpaid = $_GET['show_only_unpaid'];
$get_format = $_GET['format'];
$get_file_type = $_GET['filetype'];

#create PDF name
$attachment = file_get_contents('./tmp/cache/statement_' . $biller_id .'_' . $customer_id . '.pdf');
      
if ($_GET['stage'] == 2 ) {

	#echo $block_stage2;
	

	#get the invoice id
	$export = new export();
	$export -> format = $get_format;
	$export -> file_type = $get_file_type;
	$export -> file_location = 'download';
	$export -> module = 'statement';
	$export -> biller_id = $biller_id;
	$export -> customer_id = $customer_id;
	$export -> start_date = $start_date;
	$export -> end_date = $end_date;
	$export -> show_only_unpaid = $show_only_unpaid;
	$export -> execute();

	$email = new email();
	$email -> format = 'invoice';
	$email -> notes = $_POST['email_notes'];
	$email -> from = $_POST['email_from'];
	$email -> from_friendly = $biller['name'];
	$email -> to = $_POST['email_to'];
	$email -> bcc = $_POST['email_bcc'];
	$email -> subject = $_POST['email_subject'];
	$email -> attachment = $attachment;
	$message = $email -> send ();

}

//stage 3 = assemble email and send
else if ($_GET['stage'] == 3 ) {
	$message = "How did you get here :)";
}

$smarty -> assign('message', $message);
$smarty -> assign('biller',$biller);
$smarty -> assign('customer',$customer);
$smarty -> assign('invoice',$invoice);
$smarty -> assign('preferences',$preference);

$smarty -> assign('pageActive', 'invoice');
$smarty -> assign('active_tab', '#money');
?>
