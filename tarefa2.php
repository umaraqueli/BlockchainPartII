<?php


$pathMsg = "Mensagens/";
$diretorioMsg = dir($pathMsg);
$mensagem = "";
$textoSave="";

while($arquivoMsg = $diretorioMsg -> read()){

            $pathChaves = "Chaves/";
            $diretorioChaves = dir($pathChaves);

            while($arquivoChaves = $diretorioChaves -> read()){
                
                    $fp=fopen($pathChaves.$arquivoChaves,"r");
                    $privateKey=fread($fp,8192);
                    fclose($fp);

                    $res = openssl_get_privatekey($privateKey);
                    $fp=fopen($pathMsg.$arquivoMsg,"r");
                    $encrypted=fread($fp,8192);
                    $encrypted=base64_decode($encrypted);
                    fclose($fp);

                    if (openssl_private_decrypt($encrypted, $decrypted, $res)) {
                        //echo $decrypted;//                        
                        $textoSave= $textoSave."Mensagem Origem = ".$arquivoMsg."\n"."Chave = ".$arquivoChaves."\n"."Mensagem Decifrada = ".$decrypted."\n\n";
                       
                        $mensagem =$mensagem."\n".$decrypted;
                    }
               
            }
        
        $diretorioChaves -> close();
}
$diretorioMsg -> close();

$arquivoResposta = "Respostas.txt";
if (file_exists ($arquivoResposta)) {
      unlink($arquivoResposta);
}
$fp = fopen($arquivoResposta, "a+");
fwrite($fp, $textoSave);
fclose($fp);
echo $mensagem;
?>