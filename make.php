<?php

$resourcesPath = __DIR__ . "/resources/";
$resultPath = __DIR__ . "/result/";
$linksPath = __DIR__ . "/links/";

$files = array_diff(
    scandir($resourcesPath), ['.', '..', '.gitignore', 'example.csv']
);

foreach ($files as $file) {
    $f = fopen($resourcesPath . $file, 'r');

    $firstRow = [];

    while ($filedata = fgetcsv($f)) {
        if (empty($firstRow)) {
            $firstRow = $filedata;

            $resultFile = str_replace(['-', '_', '.', '.', 'csv'], '', $file);
            file_put_contents($resultPath . $resultFile . ".csv",
                "id,client_uniq_id,phones_md5,emails_md5,order_status,create_date_time\n"
            );

            file_put_contents($linksPath . $resultFile . ".link.log",
            "id,client_id,phone,email,phone_md5,email_md5,status,create_date_time\n"
            );

        } else {
            $id = uniqid();
            $resultFileMD5 = md5($resultFile . $id);
            $date = date('d.m.Y H:i');
            $phone = $email = $phoneMD5 = $emailMD5 = "";

            foreach ($firstRow as $rowKey => $rowValue) {
                switch ($rowValue) {
                    case empty($filedata[$rowKey]) : {
                        break;
                    }
                    case 'phone' : {
                        $phone = $filedata[$rowKey];
                        $phoneMD5 = md5($phone);
                        break;
                    }
                    case 'email' : {
                        $email = $filedata[$rowKey];
                        $emailMD5 = md5($email);
                        break;
                    }
                }
            }


            $writedResult = false;

            while (!$writedResult) {
                $writedResult = file_put_contents($resultPath . $resultFile . ".csv",
                    "$id,$resultFile$resultFileMD5,$phoneMD5,$emailMD5,IN_PROGRESS,$date\n", FILE_APPEND);
            }

            $writedLink = false;

            while (!$writedLink) {
                $writedLink = file_put_contents($linksPath . $resultFile . ".link.log",
                    "$id,$resultFile$resultFileMD5,$phone,$email,$phoneMD5,$emailMD5,IN_PROGRESS,$date\n", FILE_APPEND);
            }
        }
    }

    fclose($f);
}

