<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://app.whatsgw.com.br/api/WhatsGw/GroupInviteCode',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "apikey": "f8c401b5-5f5e-4f22-b978-7457ea9f6434",
  "phone_number": "5561998138142"
}',
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;