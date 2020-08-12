<?php
    print_r($_REQUEST);
    // writeToLog($_REQUEST, 'incoming');

/**
 * Write data to log file.
 *
 * @param mixed $data
 * @param string $title
 *
 * @return bool
 */

function writeToLog($data, $title = '') {
 $log = "\n------------------------\n";
 $log .= date("Y.m.d G:i:s") . "\n";
 $log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
 $log .= print_r($data, 1);
 $log .= "\n------------------------\n";
 file_put_contents(getcwd().'/hook.log', $log, FILE_APPEND);
 return true;
};

function getData($id) {
    $queryUrl = 'https://b24-srgzjh.bitrix24.com/rest/1/qlds7f7m0gwycig3/crm.contact.get.json';
        $queryData = http_build_query(array(
            'id'=> (int)$id
        ));
        $curl = curl_init(); //hỗ trợ việc gửi đi một request
        curl_setopt_array($curl, array(
                CURLOPT_SSL_VERIFYPEER => 0, // ????
                CURLOPT_POST => 1, // Post
                CURLOPT_HEADER => 0, // bao gồm tiêu đề
                CURLOPT_RETURNTRANSFER => 1, // trả về dạng chuỗi
                CURLOPT_URL => $queryUrl, // URI
                CURLOPT_POSTFIELDS => $queryData,
            ));

        $result = json_decode(curl_exec($curl));
        curl_close($curl);
        return $result;
}

$servername = "localhost";
$username = "web5_bitrix";
$password = "4BitATWcw";

// Create connection
$id = $_REQUEST['data']['FIELDS']['ID'];
$conn = new mysqli($servername, $username, $password,'web5_bitrix');
$conn->set_charset("utf8");
switch ($_REQUEST['event']) {
    case 'ONCRMCONTACTDELETE':
        $sql = "DELETE FROM `orders_tb` WHERE member_id = '$id'";
        $conn->query($sql);
        $sql = "DELETE FROM `contact_tb` WHERE id = '$id'";
        $conn->query($sql);
        break;
    case 'ONCRMCONTACTUPDATE':
        $result = getData($id);
        $name = $result->result->NAME.' '.$result->result->LAST_NAME;
        $phone  = $result->result->PHONE[0]->VALUE;
        $email = $result->result->EMAIL[0]->VALUE;
        $sql = "UPDATE contact_tb SET fullname='$name', phone='$phone', email='$email' WHERE id='$id'";
        $conn->query($sql);
        break;
    case 'ONCRMCONTACTADD':
        $result = getData($id);
        $name = $result->result->NAME.' '.$result->result->LAST_NAME;
        $phone  = $result->result->PHONE['VALUE'];
        $email = $result->result->EMAIL['VALUE'];
        $sql = "INSERT INTO `contact_tb` (`id`,`fullname`,`phone`,`email`) VALUES ('$id','$name','$phone','$email')";
        $conn->query($sql);
        break;
    case 'deleteData':
        $queryUrl = 'https://b24-srgzjh.bitrix24.com/rest/1/qlds7f7m0gwycig3/crm.contact.delete.json';
        $queryData = http_build_query(array(
            'id'=> (int)$_REQUEST['ID']
        ));
        // writeToLog($_REQUEST, 'incoming');
        $curl = curl_init(); //hỗ trợ việc gửi đi một request
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0, // ????
            CURLOPT_POST => 1, // Post
            CURLOPT_HEADER => 0, // bao gồm tiêu đề
            CURLOPT_RETURNTRANSFER => 1, // trả về dạng chuỗi
            CURLOPT_URL => $queryUrl, // URI
            CURLOPT_POSTFIELDS => $queryData,
        ));
        curl_exec($curl);
        curl_close($curl);
        break;
}
$conn->close();
?>
<a href="https://demo5103.seomarketing.edu.vn/bitrix/index.php"> Trở về</a>