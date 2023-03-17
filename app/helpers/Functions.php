<?php

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use scMVC\Controllers\BaseController;
use scMVC\Models\Coupons;
use scMVC\Models\Sweepstakes;

date_default_timezone_set('America/Sao_Paulo');

    function check_session()
    {
        return isset ($_SESSION['user']);
    }

//-----------------------------------------------------------------
//--------------------       LOGS        --------------------------
//-----------------------------------------------------------------

    function logger ($message = '', $level = 'info')
    {

        // create a log channel
        $log = new Logger('app_logs');
        $log->pushHandler(new StreamHandler(LOGS_PATH));

            match ($level) {
                'info' => $log->info($message),
                'notice' => $log->notice($message),
                'warning' => $log->warning($message),
                'error' => $log->error($message),
                'critical' => $log->critical($message),
                'alert' => $log->alert($message),
                'emergency' => $log->emergency($message),

                default => $log->info($message)
            };
    }

//-----------------------------------------------------------------
//--------------------      CUPONS       --------------------------
//-----------------------------------------------------------------

    function coupon_form_validation($params)
    {

        $resultFieldValidation = fields_validation($params);

        if(!$resultFieldValidation['status']){

            return [
                'status' => false,
                'errors' => $resultFieldValidation['errors']
            ];
        }

        $validationParams = [
            'user_id' => $_SESSION['user']->id,
            'code' => $params['code'],
            'cpf' => $params['cpf']
        ];

        $model = new Coupons();
        return $model->coupon_and_cpf_validation($validationParams);
    }

    function fields_validation($params)
    {
        $validation_errors = [];
        foreach($params as $fieldKey => $fieldValue){

            switch ($fieldKey) {
                case 'code':
                    if(empty($fieldValue)){
                        $validation_errors[] = 'O campo código está vazio';
                    }
                    break;
                case 'cpf':
                    if(empty($fieldValue)){
                        $validation_errors[] = 'O campo CPF está vazio';
                    }
                    break;
                case 'valor':
                        if(empty($fieldValue)){
                            $validation_errors[] = 'O campo valor está vazio';
                        }
                    break;
                case 'store':
                    if(empty($fieldValue)){
                        $validation_errors[] = 'O campo loja está vazio';
                    }
                    break;
                case 'date_time':
                    if(empty($fieldValue)){
                        $validation_errors[] = 'O campo data/hora está vazio';
                    }
                    break;
                case 'status':
                    if(empty($fieldValue)){
                        $validation_errors[] = 'O campo status está vazio';
                    }
                    break;
                default:
                    $validation_errors[] = 'Campo não encontrado';
                    break;
            }
        }

        /*  printData($validation_errors); */
        if(!empty($validation_errors)){
            return [
                'status' => false,
                'errors' => $validation_errors
            ];
        }else{
            return [
                'status' => true
            ];
        }

    }

//-----------------------------------------------------------------
//--------------------        SORTEIO        ----------------------
//-----------------------------------------------------------------

    //REALIZA O SORTEIO (SORTEIO TEÓRICO - PODE SER REALIZADO UM NOVO SORTEIO SEM DETRIMENTO DOS COUPONS E DOS NUMEROS DA SORTE)
    function raffle()
    {
        $model = new Sweepstakes();
        $result = $model->get_all_hashs();

        if($result->affected_rows <= 1){
            return [
                'status' => false,
                'message' => 'Não há números a serem sorteados!'
            ];
        }

        if(!empty($result->results) AND $result->results > 1){
            
            $hashList = array();
            foreach($result->results as $key=>$value){
                foreach ($value as $key2 => $value2) {
                    array_push($hashList, $value2);
                }
            }

            $raffled = array_rand($hashList, 2);

            $resultWinner = $model->search_for_hash_owner($hashList[$raffled[0]]);

            $winnerName = $resultWinner->results[0]->name;
            $winnerNumber = $hashList[$raffled[0]];
            $_SESSION['winnerName'] = $winnerName;
            $_SESSION['winnerNumber'] = $winnerNumber;

            return [
                'status' => true,
                'message' => "Nome do ganhador: $winnerName <br> Número da Sorte: $winnerNumber"
            ];

        }else {
            return [
                'status' => false,
                'message' => 'Não há números a serem sorteados!'
            ];
        }
    }    

    function announce_sweepstake_winner()
    {
        try {

            $model = new Sweepstakes();

            $model->delete_luck_numbers();
            //SALVA NO LOG
            logger("Todos números da sorte foram apagados. ", 'info');

            //DESATIVA TODOS OS CUPONS
            $model->deactivate_all_coupons();
            logger("Todos os cupons foram desativados. ", 'info');

            //MUDA O STATUS DO SORTEIO PARA ATIVO
            $model->activate_sweepstake();
            logger("Status do sorteio para realizado. ", 'info');

            return [
                "status" => true,
                "message" => 'Vencedor anunciado!'
            ];

        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }
    } 

    function enable_sweepstake()
    {
        try {

            $model = new Sweepstakes();

            //MUDA O STATUS DO SORTEIO PARA ATIVO
            $model->clean_all_coupons();
            logger("Todos os cupons foram apagados. ", 'info');

            //MUDA O STATUS DO SORTEIO PARA INATIVO
            $model->deactivate_sweepstake();
            //SALVA NO LOG
            logger("Sistema habilitado para novo sorteio. ", 'info');

            return [
                "status" => true,
                "message" => 'Sistema habilitado para novo sorteio.'
            ];

        } catch (Exception $e) {
            return [
                "status" => false,
                "message" => $e->getMessage()
            ];
        }

    }  

//-----------------------------------------------------------------
//--------------------      HASHES       ----------------------
//-----------------------------------------------------------------

    // GERA O GUID
    function guidv4($data = null) 
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);
        
        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
            
        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


//-----------------------------------------------------------------
//-----------------------      VIEWS       -------------------------
//-----------------------------------------------------------------

class TraitViews extends BaseController
{
    function trait_views($viewName, $data)
    {
        $this->view('layouts/html_header');
        $this->view('navbar', $data);
        $this->view($viewName, $data);
        $this->view('footer');
        $this->view('layouts/html_footer');
    }
}

    // TRATA O RETORNO DAS VIEWS

//-----------------------------------------------------------------
//-----------------------      DATA       -------------------------
//-----------------------------------------------------------------

    function printData($data, $die=true)
    {
        echo "<pre>";
        
        if(is_object($data) || is_array($data)){
            print_r($data);
        }else{
            echo $data;
        }
        
        if($die){
            die('<br>Fim<br>');
        }
        
    }

    // EXPORTA UM ARRAY
    function export_csv($dataArray, $headersArray, $fileName)
    {

        $values = array_map('array_values', $dataArray);

        // adiciona o array de valores em um novo array com a primeira linha (headers)
        $data = array_merge(array($headersArray), $values);
        
        $dateNow = str_replace(" ", "_", date("Y-m-d h:i:s"));

        // Cabeçalho do arquivo CSV
        $header = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='. $fileName . '-' . $dateNow . '.csv'
        );

        // Cria o arquivo CSV
        $csv = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');

        // Escreve os dados no arquivo CSV
        foreach ($data as $row) {
            fputcsv($csv, $row);
        }

        // Volta o ponteiro para o início do arquivo CSV
        rewind($csv);

        // Lê o conteúdo do arquivo CSV
        $csv_data = stream_get_contents($csv);

        // Fecha o arquivo CSV
        fclose($csv);

        // Envia os headers para o navegador
        foreach ($header as $key => $value) {
            header("$key: $value");
        }

        // Envia o conteúdo do arquivo CSV para o navegador
        echo $csv_data;
        exit;

    }

    //
    function export_ofx($data)
    {

           // Example array
           $transactions = $data;
        
           // Build the OFX file contents
           $ofx = <<<OFX
           OFXHEADER:100
           DATA:OFXSGML
           VERSION:102
           SECURITY:NONE
           ENCODING:USASCII
           CHARSET:1252
           COMPRESSION:NONE
           OLDFILEUID:NONE
           NEWFILEUID:NONE
           
           <OFX>
             <SIGNONMSGSRSV1>
               <SONRS>
                 <STATUS>
                   <CODE>0</CODE>
                   <SEVERITY>INFO</SEVERITY>
                   <MESSAGE>Successful Sign On</MESSAGE>
                 </STATUS>
                 <DTSERVER>20220316000000</DTSERVER>
                 <LANGUAGE>ENG</LANGUAGE>
               </SONRS>
             </SIGNONMSGSRSV1>
             <BANKMSGSRSV1>
               <STMTTRNRS>
                 <TRNUID>1234567890</TRNUID>
                 <STATUS>
                   <CODE>0</CODE>
                   <SEVERITY>INFO</SEVERITY>
                   <MESSAGE>Successful Request</MESSAGE>
                 </STATUS>
                 <STMTRS>
                   <CURDEF>USD</CURDEF>
                   <BANKACCTFROM>
                     <BANKID>12345678</BANKID>
                     <ACCTID>12345678901234</ACCTID>
                     <ACCTTYPE>CHECKING</ACCTTYPE>
                   </BANKACCTFROM>
                   <BANKTRANLIST>
                     <DTSTART>20220101000000</DTSTART>
                     <DTEND>20220228000000</DTEND>
                     <STMTTRN>
           OFX;
           
           foreach ($transactions as $transaction) {
               $date = date('Ymd', strtotime($transaction['date']));
               $amount = number_format($transaction['amount'], 2, '.', '');
               $description = htmlentities($transaction['description'], ENT_QUOTES, 'UTF-8');
               $ofx .= <<<OFX
                       <TRNTYPE>OTHER</TRNTYPE>
                       <DTPOSTED>{$date}000000</DTPOSTED>
                       <TRNAMT>{$amount}</TRNAMT>
                       <FITID>1234567890</FITID>
                       <NAME>{$description}</NAME>
                       <MEMO>{$description}</MEMO>
                     </STMTTRN>
           OFX;
           }
           
           $ofx .= <<<OFX
                   </BANKTRANLIST>
                 </STMTRS>
               </STMTTRNRS>
             </BANKMSGSRSV1>
           </OFX>
           OFX;
           
           // Set the headers to force the download
           header('Content-Type: application/force-download');
           header('Content-Disposition: attachment; filename="transactions.ofx"');
           header('Content-Length: ' . strlen($ofx));
           
           // Output the file contents to the browser
           echo $ofx;
           exit();
    }
