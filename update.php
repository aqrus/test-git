<?php
    $queryUrl = 'https://b24-srgzjh.bitrix24.com/rest/1/qlds7f7m0gwycig3/crm.contact.update.json';
    $queryData = http_build_query(array(
        'id' =>  $_POST['ID'],
        'fields' => [
            'NAME' => $_POST['NAME'],
            'LAST_NAME' => $_POST['LAST_NAME'],
            'PHONE' => array(array("VALUE" => $_POST['PHONE'])),
            'EMAIL' => array(array("VALUE" => $_POST['EMAIL'])),
        ]
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
    if($result->result == 1){
        echo 'Update Thành công';
        echo '<br>';
    }
    else {
        echo 'Lỗi <br>';
        echo $result->error_description;
    }
    if(!empty($_POST['Order'])) {
        $servername = "localhost";
        $username = "web5_bitrix";
        $password = "4BitATWcw";
        $conn = new mysqli($servername, $username, $password,'web5_bitrix');
        $conn->set_charset("utf8");
        $name = $_POST['Order'];
        $id = $_POST['ID'];
    // Create connection
        $sql = "INSERT INTO `orders_tb` (`id`,`comment`,`member_id`) VALUES ('','$name','$id')";
        $result = $conn->query($sql);
        if ($result) {
            echo "Thêm thành công <br>";
        } else {
            echo "0 results";
        }
        $conn->close();
    }


?>
<a href="https://demo5103.seomarketing.edu.vn/bitrix/index.php"> Trở về</a>