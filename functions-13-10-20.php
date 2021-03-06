<?php
include("config.php");
class Functions
{

    function __construct()
    {
    }


    function validateKey($api_key)
    {
        $apikey = "enggnslkfjsdjkfal456556476";
        //echo $api_key = GREETING;
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
                $agentSql = sprintf('SELECT first_name FROM `uv_user` WHERE `id` = "' . $ticketRes['agent_id'] . '"', $conn);
                $agentSql = $conn->query($agentSql);
                $agentRes = $agentSql->fetch_assoc();


                // Ticket Group
                $groupSql = sprintf('SELECT name FROM `uv_support_group` WHERE `id` = "' . $ticketRes['group_id'] . '"', $conn);
                $groupSql = $conn->query($groupSql);
                $groupRes = $groupSql->fetch_assoc();

                $tmp["id"] = $ticketRes["id"];
                $tmp["id"] = $ticketRes["id"];
                $tmp["status"] = $statusRes["code"];
                $tmp["customer"] = $customerRes["email"];
                $tmp["group"] = $groupRes["name"];
                $tmp["agent"] = $agentRes["first_name"];
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
