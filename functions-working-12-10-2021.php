<?php
include("config.php");
class Functions
{

    function __construct()
    {
    }


    function validateKey($api_key)
    {
	$apikey="enggnslkfjsdjkfal456556476";
        //echo "apikey =".$apikey;
	//echo "api_key =".$api_key;die;
        $response = false;
        if ($api_key == $apikey) {
            $response = true;
        } else {
            $response = false;
        }
        return $response;
    }

    function validateMethod($method)
    {

        $methods = array(
            'getTickets',
	    'getCategory',
	    'getSubCategory',
            'getTicketsDetails',
	    'getTicketstype',
	    'replytickets',
	    'getTicketsdatamob',
	    'createtickets',
            'getthreadDetails',
            'ticketsDetails'
        );
        //echo "<pre>";print_r($method);die;
        if (in_array($method, $methods)) {
            return true;
        } else {
            return false;
        }
    }


    function callMethod($method, $params)
    {
        $res = $this->$method($params);
        return $res;
    }

    function getTickets($params)
    {
        global $conn;
        $email = $params['email'];
        $response = array();
        $userSql = sprintf('SELECT id FROM `uv_user` WHERE `email` = "' . $email . '"', $conn);
        $userSql = $conn->query($userSql);
        $response = array();
        if (!$userSql || mysqli_num_rows($userSql) == 0) {
            $response =  false;
            $ResMessage = "No User Found";
        } else {
            $userRes = $userSql->fetch_assoc();
            $ticketSql = sprintf('SELECT id,type_id,subject FROM `uv_ticket` WHERE `customer_id` = "' . $userRes['id'] . '"', $conn);
            $ticketSql = $conn->query($ticketSql);
            if (!$ticketSql || mysqli_num_rows($ticketSql) == 0) {
                $response =  false;
                $ResMessage = "No Ticket Found";
            } else {
                $response = array();
                while ($ticketRes = $ticketSql->fetch_assoc()) {
                    $typeSql = sprintf('SELECT code FROM `uv_ticket_type` WHERE `id` = "' . $ticketRes['type_id'] . '"', $conn);
                    $typeSql = $conn->query($typeSql);
                    $typeRes = $typeSql->fetch_assoc();
                    $tmp["id"] = $ticketRes["id"];
                    $tmp["ticket_type"] = $typeRes["code"];
                    $tmp["subject"] = $ticketRes["subject"];
                    array_push($response, $tmp);
                }
            }
        }
        $ResponseCode = 200;
        $response_array = array();
        $ResponseMessage = '';
        $ResponseSuccess = true;

        if ($response == true) {

            $ResponseCode = 200;
            $ResponseMessage = "Successfully";
            $ResponseSuccess = true;
            $response_array = $response;
        } else {
            $ResponseCode = 202;
            $ResponseMessage = $ResMessage;
            $ResponseSuccess = false;
            $response_array = '';
        }

        $res['ResponseCode'] = $ResponseCode;
        $res['Message'] = $ResponseMessage;
        $res['Success'] = $ResponseSuccess;
        $res['Response'] = $response_array;
        return $res;
    }


    function getCategory($params)
    {
        global $conn;
        $grouptype = $params['grouptype'];
        //die;
        $response = array();
        $tictype = sprintf('SELECT DISTINCT cname FROM `uv_ticket_type` WHERE `grouptype` = "' . $grouptype . '"', $conn);
        $tictype = $conn->query($tictype);
        $response = array();
        if (!$tictype || mysqli_num_rows($tictype) == 0) {
            $response =  false;
            $ResMessage = "No User Found";
        } else {
                while ($tictypeRes = $tictype->fetch_assoc()) {


                    $tmp["category"] = $tictypeRes["cname"];
                    $tmp["grouptype"] = $grouptype;
                    array_push($response, $tmp);

            }
        }
        $ResponseCode = 200;
        $response_array = array();
        $ResponseMessage = '';
        $ResponseSuccess = true;

        if ($response == true) {

            $ResponseCode = 200;
            $ResponseMessage = "Successfully";
            $ResponseSuccess = true;
            $response_array = $response;
        } else {
            $ResponseCode = 202;
            $ResponseMessage = $ResMessage;
            $ResponseSuccess = false;
            $response_array = '';
        }

        $res['ResponseCode'] = $ResponseCode;
        $res['Message'] = $ResponseMessage;
        $res['Success'] = $ResponseSuccess;
        $res['Response'] = $response_array;
        return $res;
    }




    function getSubCategory($params)
    {
        global $conn;
        $cat = $params['cat'];
        $grouptype = $params['grouptype'];
        //die;
        $response = array();
        $tictype = sprintf('SELECT * FROM `uv_ticket_type` WHERE `cname` = "' . $cat . '" and `grouptype` = "' .$grouptype. '"', $conn);

        $tictype = $conn->query($tictype);
        $response = array();
        if (!$tictype || mysqli_num_rows($tictype) == 0) {
            $response =  false;
            $ResMessage = "No User Found";
        } else {
                while ($tictypeRes = $tictype->fetch_assoc()) {

                    $tmp["id"] = $tictypeRes["id"];
                    $tmp["subcat"] = $tictypeRes["code"];
                    $tmp["grouptype"] = $tictypeRes["grouptype"];
                    array_push($response, $tmp);

            }
        }
        $ResponseCode = 200;
        $response_array = array();
        $ResponseMessage = '';
        $ResponseSuccess = true;

        if ($response == true) {

            $ResponseCode = 200;
            $ResponseMessage = "Successfully";
            $ResponseSuccess = true;
            $response_array = $response;
        } else {
            $ResponseCode = 202;
            $ResponseMessage = $ResMessage;
            $ResponseSuccess = false;
            $response_array = '';
        }

        $res['ResponseCode'] = $ResponseCode;
        $res['Message'] = $ResponseMessage;
        $res['Success'] = $ResponseSuccess;
        $res['Response'] = $response_array;
        return $res;
    }



    function getTicketsDetails($params)
    {
        global $conn;
        $email = $params['email'];
        $response = array();
        $userSql = sprintf('SELECT id FROM `uv_user` WHERE `email` = "' . $email . '"', $conn);
        $userSql = $conn->query($userSql);
        $response = array();
        if (!$userSql || mysqli_num_rows($userSql) == 0) {
            $response =  false;
            $ResMessage = "No User Found";
        } else {
            $userRes = $userSql->fetch_assoc();
            $ticketSql = sprintf('SELECT id,group_id,subject FROM `uv_ticket` WHERE `customer_id` = "' . $userRes['id'] . '"', $conn);
            $ticketSql = $conn->query($ticketSql);
            if (!$ticketSql || mysqli_num_rows($ticketSql) == 0) {
                $response =  false;
                $ResMessage = "No Ticket Found";
            } else {
                $response = array();
                while ($ticketRes = $ticketSql->fetch_assoc()) {


                    $userthreadSql = sprintf('SELECT * FROM `uv_thread` WHERE `ticket_id` = "' . $ticketRes['id'] . '"', $conn);
                    $userthreadSql = $conn->query($userthreadSql);
                    
                    if (!$userthreadSql || mysqli_num_rows($userthreadSql) == 0) {
                        $response =  false;
                        $ResMessage = "No Ticket Found";
                    } else {
                        while ($ticthreadRes = $userthreadSql->fetch_assoc()) {

                            $fileSql = sprintf('SELECT * FROM `uv_ticket_attachments` WHERE `thread_id` = "' . $ticthreadRes['id'] . '"', $conn);

                            $fileSql = $conn->query($fileSql);
                            $rowcount = mysqli_num_rows($fileSql);
                            
                            for($i=1; $i<=$rowcount; $i++){

                                $getfileattachments = $fileSql->fetch_array();

                                $filename[$i] = $getfileattachments['name'];
                                $filepath[$i] = $getfileattachments['path'];
                            }

                            
                            $typeSql = sprintf('SELECT name FROM `uv_support_group` WHERE `id` = "' . $ticketRes['group_id'] . '"', $conn);

                            $typeSql = $conn->query($typeSql);
                            $typeRes = $typeSql->fetch_assoc();


                                $tmp["id"] = $ticketRes["id"];
                                $tmp["business_unit"] = $typeRes["name"];
                                $tmp["threadid"] = $ticthreadRes["id"];
                                $tmp["date"] = $ticthreadRes["created_at"];
                                $tmp["subject"] = $ticketRes["subject"];
                                $tmp["message"] = $ticthreadRes["message"];
                            if($rowcount != 0){    
                                $tmp["filename"] = $filename;
                                $tmp["filepath"] = $filepath;
                            }
                            array_push($response, $tmp);
                            
                        }
                    }
                }
            }
        }
        $ResponseCode = 200;
        $response_array = array();
        $ResponseMessage = '';
        $ResponseSuccess = true;

        if ($response == true) {

            $ResponseCode = 200;
            $ResponseMessage = "Successfully";
            $ResponseSuccess = true;
            $response_array = $response;
        } else {
            $ResponseCode = 202;
            $ResponseMessage = $ResMessage;
            $ResponseSuccess = false;
            $response_array = '';
        }

        $res['ResponseCode'] = $ResponseCode;
        $res['Message'] = $ResponseMessage;
        $res['Success'] = $ResponseSuccess;
        $res['Response'] = $response_array;
        return $res;
    }




function getTicketstype($params)
    {
        global $conn;
        $grouptype = $params['grouptype'];
        //die;
        $response = array();
        $tictype = sprintf('SELECT * FROM `uv_ticket_type` WHERE `grouptype` = "' . $grouptype . '"', $conn);
        $tictype = $conn->query($tictype);
        $response = array();
        if (!$tictype || mysqli_num_rows($tictype) == 0) {
            $response =  false;
            $ResMessage = "No User Found";
        } else {
                while ($tictypeRes = $tictype->fetch_assoc()) {

                    $tmp["id"] = $tictypeRes["id"];
                    $tmp["ticket_type"] = $tictypeRes["code"];
                    $tmp["grouptype"] = $tictypeRes["grouptype"];
                    array_push($response, $tmp);

            }
        }
        $ResponseCode = 200;
        $response_array = array();
        $ResponseMessage = '';
        $ResponseSuccess = true;

        if ($response == true) {

            $ResponseCode = 200;
            $ResponseMessage = "Successfully";
            $ResponseSuccess = true;
            $response_array = $response;
        } else {
            $ResponseCode = 202;
            $ResponseMessage = $ResMessage;
            $ResponseSuccess = false;
            $response_array = '';
        }

        $res['ResponseCode'] = $ResponseCode;
        $res['Message'] = $ResponseMessage;
        $res['Success'] = $ResponseSuccess;
        $res['Response'] = $response_array;
        return $res;
    }



function replytickets($params)
    {
        global $conn;
        $id = $params['tic_id'];
        $response = array();

        $ticketSql = sprintf('SELECT * FROM `uv_ticket` WHERE `id` = "' . $id . '"', $conn);
        $ticketSql = $conn->query($ticketSql);

        $userSql = $conn->query($userSql);
        if (!$ticketSql || mysqli_num_rows($ticketSql) == 0) {
            $response =  false;
            $ResMessage = "No User Found";
        } else {
            $ticketSql = $ticketSql->fetch_assoc();

            $customer_id = $ticketSql['customer_id'];
            $source = 'mobileapp';
            $message = $params['message'];
            $ticket_id = $ticketSql['id'];

            $time = time();

            $todaydate = date("Y-m-d H:i:s");

            $checklength = $_FILES['filename']['tmp_name'];



                $threadinsertqry = "INSERT INTO `uv_thread` (`id`, `ticket_id`, `user_id`, `source`, `message_id`, `thread_type`, `created_by`, `cc`, `bcc`, `reply_to`, `delivery_status`, `is_locked`, `is_bookmarked`, `message`, `created_at`, `updated_at`, `agent_viewed_at`, `customer_viewed_at`) VALUES (NULL, '$ticket_id', '$customer_id', '$source', NULL, 'reply', 'customer', NULL, NULL, NULL, NULL, '0', '0', '$message', '$todaydate', '$todaydate', NULL, NULL)";

                if ($resinsquery = $conn->query($threadinsertqry) === TRUE) {


                    $getuserticketdetails = sprintf('SELECT * FROM `uv_thread` WHERE `user_id` = "' . $customer_id . '" ORDER BY `id` DESC LIMIT 0,1 ', $conn);

                    $getuserticketdetails = $conn->query($getuserticketdetails);
                    $userticketdetails = $getuserticketdetails->fetch_assoc();

                    $thread_ticket_id = $userticketdetails['id'];


                    if($checklength[0] != '')
                    {

                        $path = "../public/assets/threads/".$thread_ticket_id;
                        mkdir($path,0777,TRUE);

                        for($i=0; $i< count($checklength); $i++)
                        {

                            $filename = $_FILES['filename']['name'];

                            $filesize = $_FILES['filename']['size'];

                            $newfilename = $time."_".$filename[$i];
                            $newfilesize = $filesize[$i];

                            $targetfolder = "../public/assets/threads/".$thread_ticket_id."/".$newfilename;

                             move_uploaded_file($checklength[$i],$targetfolder);

                                $attachment =  "INSERT INTO `uv_ticket_attachments` (`id`, `thread_id`, `name`, `path`, `content_type`, `size`, `content_id`, `file_system`) VALUES (NULL, '$thread_ticket_id', '$newfilename', '$targetfolder', NULL, '$newfilesize', NULL, NULL)";

                                $insattachment = $conn->query($attachment);

                        }

                    }

                    $response = 'true';
                }else{
                    $response = 'false';
                }

        }


        if ($response == true) {

            $ResponseCode = 200;
            $ResponseMessage = "Successfully";
            $ResponseSuccess = true;
            $response_array = $response;
        } else {
            $ResponseCode = 202;
            $ResponseMessage = $ResMessage;
            $ResponseSuccess = false;
            $response_array = '';
        }

        $res['ResponseCode'] = $ResponseCode;
        $res['Message'] = $ResponseMessage;
        $res['Success'] = $ResponseSuccess;
        $res['Response'] = $response_array;
        return $res;
    }



function getTicketsdatamob($params)
    {
        global $conn;
        $email = $params['email'];
        $response = array();
        $userSql = sprintf('SELECT id FROM `uv_user` WHERE `email` = "' . $email . '"', $conn);
        $userSql = $conn->query($userSql);
        $response = array();
        if (!$userSql || mysqli_num_rows($userSql) == 0) {
            $response =  false;
            $ResMessage = "No User Found";
        } else {
            $userRes = $userSql->fetch_assoc();
            $ticketSql = sprintf('SELECT * FROM `uv_ticket` WHERE `customer_id` = "' . $userRes['id'] . '"', $conn);
            $ticketSql = $conn->query($ticketSql);
            if (!$ticketSql || mysqli_num_rows($ticketSql) == 0) {
                $response =  false;
                $ResMessage = "No Ticket Found";
            } else {
                $response = array();
                while ($ticketRes = $ticketSql->fetch_assoc()) {
                    $typeSql = sprintf('SELECT code FROM `uv_ticket_type` WHERE `id` = "' . $ticketRes['type_id'] . '"', $conn);
                    $typeSql = $conn->query($typeSql);
                    $typeRes = $typeSql->fetch_assoc();


                    //Ticket Status
                    $statusSql = sprintf('SELECT code FROM `uv_ticket_status` WHERE `id` = "' . $ticketRes['status_id'] . '"', $conn);
                    $statusSql = $conn->query($statusSql);
                    $statusRes = $statusSql->fetch_assoc();


                    // Ticket Group
                    $groupSql = sprintf('SELECT name FROM `uv_support_group` WHERE `id` = "' . $ticketRes['group_id'] . '"', $conn);
                    $groupSql = $conn->query($groupSql);
                    $groupRes = $groupSql->fetch_assoc();



                    //Ticket agent
                    $agentSql = sprintf('SELECT first_name, last_name FROM `uv_user` WHERE `id` = "' . $ticketRes['agent_id'] . '"', $conn);
                    $agentSql = $conn->query($agentSql);
                    $agentRes = $agentSql->fetch_assoc();


                    $userthreadSql = sprintf('SELECT * FROM `uv_thread` WHERE `ticket_id` = "' . $ticketRes['id'] . '"', $conn);
                    $userthreadSql = $conn->query($userthreadSql);

                    if (!$userthreadSql || mysqli_num_rows($userthreadSql) == 0) {
                        $response =  false;
                        $ResMessage = "No Ticket Found";
                    } else {
                        while ($ticthreadRes = $userthreadSql->fetch_assoc()) {


                            $fileSql = sprintf('SELECT * FROM `uv_ticket_attachments` WHERE `thread_id` = "' . $ticthreadRes['id'] . '"', $conn);

                            $fileSql1 = $conn->query($fileSql);
                            $rowcount = $fileSql1->num_rows;

                            if($rowcount > 0){

                                $fileresponse = array();

                                while($getfileattachment = $fileSql1->fetch_assoc()){
                                    $filename['file'] = $getfileattachment['name'];
                                    $filename['path'] = $getfileattachment['path'];
                                    array_push($fileresponse, $filename);
                                }

                            }else{

                                $fileresponse = '';

                            }


                                $tmp["id"] = $ticketRes["id"];
                                $tmp["ticket_type"] = $typeRes["code"];
                                $tmp["status"] = $statusRes["code"];
                                $tmp["subject"] = $ticketRes["subject"];
                                $tmp["staff"] = $agentRes["first_name"]." ".$agentRes["last_name"];
                                $tmp["group"] = $groupRes["name"];
                                $tmp["source"] = $ticketRes["source"];
                                $tmp["subject"] = $ticketRes["subject"];
                                $tmp["message_by"] = $ticthreadRes["created_by"];
                                $tmp["message"] = $ticthreadRes["message"];


                                $tmp['filename'] = $fileresponse;

                                $tmp["created_at"] = $ticthreadRes["created_at"];

                                array_push($response, $tmp);
                        }
                    }
                }
            }
        }
        $ResponseCode = 200;
        $response_array = array();
        $ResponseMessage = '';
        $ResponseSuccess = true;

        if ($response == true) {

            $ResponseCode = 200;
            $ResponseMessage = "Successfully";
            $ResponseSuccess = true;
            $response_array = $response;
        } else {
            $ResponseCode = 202;
            $ResponseMessage = $ResMessage;
            $ResponseSuccess = false;
            $response_array = '';
        }

        $res['ResponseCode'] = $ResponseCode;
        $res['Message'] = $ResponseMessage;
        $res['Success'] = $ResponseSuccess;
        $res['Response'] = $response_array;
        return $res;
    }



function createtickets($params)
    {
        global $conn;
        $email = $params['email'];
        $response = array();
        $userSql = sprintf('SELECT id FROM `uv_user` WHERE `email` = "' . $email . '"', $conn);
        $userSql = $conn->query($userSql);
        if (!$userSql || mysqli_num_rows($userSql) == 0) {
            $response =  false;
            $ResMessage = "No User Found";
        } else {
            $userRes = $userSql->fetch_assoc();

            $customer_id = $userRes['id'];
            $source = 'mobileapp';
            $subject = $params['subject'];
            $message = $params['message'];
            $category = $params['catname'];
            $tic_type_id = $params['tic_type_id'];
            $group_id = $params['group_id'];


            $time = time();

            $todaydate = date("Y-m-d H:i:s");          

            $checklength = $_FILES['filename']['tmp_name'];


                $gettictype = "SELECT code FROM `uv_ticket_type` WHERE `id` = '$tic_type_id'";
                $gettictype = $conn->query($gettictype);
                $tictypeRes = $gettictype->fetch_assoc();

                $ticketname = $tictypeRes['code'];

                $wftypeSql = "SELECT * FROM `uv_workflow` WHERE `name` = '$ticketname'";
                $wftypeSql = $conn->query($wftypeSql);
                $workflowRes = $wftypeSql->fetch_assoc();
                $workflowRes['actions'];

                $pos = strpos($workflowRes['actions'],"uvdesk.ticket.assign_team");
                $newlocation = $pos+43;
                 
                $finalres = substr($workflowRes['actions'],$newlocation,4);

                $getteamid = str_replace( array( '\'', '"', ',' , ';', '<', '>' ), '', $finalres); 



            $ticinsertquery = "INSERT INTO `uv_ticket` (`id`, `status_id`, `priority_id`, `type_id`, `customer_id`, `agent_id`, `group_id`, `source`, `mailbox_email`, `subject`, `reference_ids`, `is_new`, `is_replied`, `is_reply_enabled`, `is_starred`, `is_trashed`, `is_agent_viewed`, `is_customer_viewed`, `created_at`, `updated_at`, `subGroup_id`, `category`) VALUES (NULL, '1', '1', '$tic_type_id', '$customer_id', NULL, '$group_id', '$source', NULL, '$subject', NULL, '1', '0', '1', '0', '0', '0', '0', '$todaydate', '$todaydate', '$getteamid','$category')";


            if ($conn->query($ticinsertquery) === TRUE) {

                    $getuserticketdetails = sprintf('SELECT * FROM `uv_ticket` WHERE `customer_id` = "' . $customer_id . '" ORDER BY `id` DESC LIMIT 0,1 ', $conn);

                    $getuserticketdetails = $conn->query($getuserticketdetails);
                    $userticketdetails = $getuserticketdetails->fetch_assoc();

                    $ticket_id = $userticketdetails['id'];
                    

                     $threadinsertqry = "INSERT INTO `uv_thread` (`id`, `ticket_id`, `user_id`, `source`, `message_id`, `thread_type`, `created_by`, `cc`, `bcc`, `reply_to`, `delivery_status`, `is_locked`, `is_bookmarked`, `message`, `created_at`, `updated_at`, `agent_viewed_at`, `customer_viewed_at`) VALUES (NULL, '$ticket_id', '$customer_id', '$source', NULL, 'create', 'customer', NULL, NULL, NULL, NULL, '0', '0', '$message', '$todaydate', '$todaydate', NULL, NULL)";

                if ($resinsquery = $conn->query($threadinsertqry) === TRUE) {


                    $getuserticketdetails = sprintf('SELECT * FROM `uv_thread` WHERE `user_id` = "' . $customer_id . '" ORDER BY `id` DESC LIMIT 0,1 ', $conn);

                    $getuserticketdetails = $conn->query($getuserticketdetails);
                    $userticketdetails = $getuserticketdetails->fetch_assoc();

                    $thread_ticket_id = $userticketdetails['id'];


                    if($checklength[0] != '')
                    {

                        $path = "../public/assets/threads/".$thread_ticket_id;
                        mkdir($path,0777,TRUE);

                        for($i=0; $i< count($checklength); $i++)
                        {

                            $filename = $_FILES['filename']['name'];

                            $filesize = $_FILES['filename']['size'];

                            $newfilename = $time."_".$filename[$i];
                            $newfilesize = $filesize[$i];

                            $targetfolder = "../public/assets/threads/".$thread_ticket_id."/".$newfilename;

                             move_uploaded_file($checklength[$i],$targetfolder);

                                $attachment =  "INSERT INTO `uv_ticket_attachments` (`id`, `thread_id`, `name`, `path`, `content_type`, `size`, `content_id`, `file_system`) VALUES (NULL, '$thread_ticket_id', '$newfilename', '$targetfolder', NULL, '$newfilesize', NULL, NULL)";

                                $insattachment = $conn->query($attachment);
                                        
                        }

                    }

                    $response = 'true';
                }else{
                    $response = 'false';
                }              
            } else {
              $response = 'false';
            }

            
            
        }
        

        if ($response == true) {

            $ResponseCode = 200;
            $ResponseMessage = "Successfully";
            $ResponseSuccess = true;
            $response_array = $response;
        } else {
            $ResponseCode = 202;
            $ResponseMessage = $ResMessage;
            $ResponseSuccess = false;
            $response_array = '';
        }

        $res['ResponseCode'] = $ResponseCode;
        $res['Message'] = $ResponseMessage;
        $res['Success'] = $ResponseSuccess;
        $res['Response'] = $response_array;
        return $res;
    }




 function getthreadDetails($params)
    {
        global $conn;
        $id = $params['id'];
        $response = array();
        $userSql = sprintf('SELECT * FROM `uv_thread` WHERE `ticket_id` = "' . $id . '"', $conn);
        $userSql = $conn->query($userSql);
        $response = array();
        if (!$userSql || mysqli_num_rows($userSql) == 0) {
            $response =  false;
            $ResMessage = "No Ticket thread Found";
        } else {

            while ($ticketRes = $userSql->fetch_assoc()) {

		$typeSql = sprintf('SELECT first_name,last_name FROM `uv_user` WHERE `id` = "' . $ticketRes['user_id'] . '"', $conn);
                $typeSql = $conn->query($typeSql);
                $userRes = $typeSql->fetch_assoc();

                $tmp["date"] = $ticketRes["created_at"];
                $tmp["by"] = $ticketRes["created_by"];
		$tmp["name"] = $userRes["first_name"]." ".$userRes["last_name"];
                $tmp["message"] = $ticketRes["message"];
                array_push($response, $tmp);
            }
        }
        $ResponseCode = 200;
        $response_array = array();
        $ResponseMessage = '';
        $ResponseSuccess = true;

        if ($response == true) {

            $ResponseCode = 200;
            $ResponseMessage = "Successfully";
            $ResponseSuccess = true;
            $response_array = $response;
        } else {
            $ResponseCode = 202;
            $ResponseMessage = $ResMessage;
            $ResponseSuccess = false;
            $response_array = '';
        }

        $res['ResponseCode'] = $ResponseCode;
        $res['Message'] = $ResponseMessage;
        $res['Success'] = $ResponseSuccess;
        $res['Response'] = $response_array;
        return $res;
    }


    function ticketsDetails($params)
    {
        global $conn;
        $id = $params['id'];
        $response = array();

        $ticketSql = sprintf('SELECT id, status_id, type_id, customer_id, group_id, source, is_replied, subGroup_id, agent_id, created_at, updated_at, subject FROM `uv_ticket` WHERE `id` = "' . $id . '"', $conn);
        $ticketSql = $conn->query($ticketSql);
        if (!$ticketSql || mysqli_num_rows($ticketSql) == 0) {
            $response =  false;
            $ResMessage = "No Ticket Found";
        } else {
            $response = array();
            while ($ticketRes = $ticketSql->fetch_assoc()) {
                //Ticket Type
                $typeSql = sprintf('SELECT code FROM `uv_ticket_type` WHERE `id` = "' . $ticketRes['type_id'] . '"', $conn);
                $typeSql = $conn->query($typeSql);
                $typeRes = $typeSql->fetch_assoc();

                //Ticket Status
                $statusSql = sprintf('SELECT code FROM `uv_ticket_status` WHERE `id` = "' . $ticketRes['status_id'] . '"', $conn);
                $statusSql = $conn->query($statusSql);
                $statusRes = $statusSql->fetch_assoc();

                //Ticket Customer 
                $customerSql = sprintf('SELECT email FROM `uv_user` WHERE `id` = "' . $ticketRes['customer_id'] . '"', $conn);
                $customerSql = $conn->query($customerSql);
                $customerRes = $customerSql->fetch_assoc();

                // Ticket Agent 
                //$typeSql = sprintf('SELECT code FROM `uv_ticket_type` WHERE `id` = "' . $ticketRes['type_id'] . '"', $conn);
                //$typeSql = $conn->query($typeSql);
                //$typeRes = $typeSql->fetch_assoc();

                // Ticket subGroup
                $subgroupSql = sprintf('SELECT name FROM `uv_support_team` WHERE `id` = "' . $ticketRes['subGroup_id'] . '"', $conn);
                $subgroupSql = $conn->query($subgroupSql);
                $subgroupRes = $subgroupSql->fetch_assoc();


                //Ticket agent 
                $agentSql = sprintf('SELECT first_name, last_name FROM `uv_user` WHERE `id` = "' . $ticketRes['agent_id'] . '"', $conn);
                $agentSql = $conn->query($agentSql);
                $agentRes = $agentSql->fetch_assoc();

                // Ticket raised
                $raisedSql = sprintf('SELECT user_id FROM `uv_thread` WHERE `thread_type`="create" and `ticket_id` = "' . $ticketRes['id'] . '"', $conn);
                $raisedSql = $conn->query($raisedSql);
                $raisedRes = $raisedSql->fetch_assoc();


                //Ticket raisedby 
                $raisedbySql = sprintf('SELECT first_name, last_name FROM `uv_user` WHERE `id` = "' . $raisedRes['user_id'] . '"', $conn);
                $raisedbySql = $conn->query($raisedbySql);
                $raisedbyRes = $raisedbySql->fetch_assoc();



                // Ticket Group
                $groupSql = sprintf('SELECT name FROM `uv_support_group` WHERE `id` = "' . $ticketRes['group_id'] . '"', $conn);
                $groupSql = $conn->query($groupSql);
                $groupRes = $groupSql->fetch_assoc();

                $tmp["id"] = $ticketRes["id"];
                $tmp["id"] = $ticketRes["id"];
                $tmp["status"] = $statusRes["code"];
                $tmp["raisedby"] = $raisedbyRes["first_name"]." ".$raisedbyRes["last_name"];
                $tmp["customer"] = $customerRes["email"];
                $tmp["group"] = $groupRes["name"];
                $tmp["agent"] = $agentRes["first_name"]." ".$agentRes["last_name"];
                $tmp["team"] = $subgroupRes["name"];
                $tmp["source"] = $ticketRes["source"];
                $tmp["subject"] = $ticketRes["subject"];
                $tmp["reply"] = $ticketRes["is_replied"];
                $tmp["created_at"] = $ticketRes["created_at"];
                $tmp["updated_at"] = $ticketRes["updated_at"];
                array_push($response, $tmp);
            }
        }

        $ResponseCode = 200;
        $response_array = array();
        $ResponseMessage = '';
        $ResponseSuccess = true;

        if ($response == true) {

            $ResponseCode = 200;
            $ResponseMessage = "Successfully";
            $ResponseSuccess = true;
            $response_array = $response;
        } else {
            $ResponseCode = 202;
            $ResponseMessage = $ResMessage;
            $ResponseSuccess = false;
            $response_array = '';
        }

        $res['ResponseCode'] = $ResponseCode;
        $res['Message'] = $ResponseMessage;
        $res['Success'] = $ResponseSuccess;
        $res['Response'] = $response_array;
        return $res;
    }
    function echoResponse($response)
    {
        echo json_encode($response, JSON_UNESCAPED_SLASHES);
    }
    function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace("/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s", "", $string);
    }
}
