<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca CNPJ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Consultar CNPJ</h1>
        <form action="" method="post" class="mb-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" name="cnpj" placeholder="Adicione o CNPJ">
                        <button type="submit"class="btn btn-primary">Gerar</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php

                // if($_SERVER['REQUEST_METHOD'] ==='POST'){
                //     echo "Ok";
                // }
                $post = filter_input(INPUT_POST,'cnpj', FILTER_SANITIZE_SPECIAL_CHARS);
                if(isset($post)){
                    function consultarCNPJ($cnpj){

                        // remover caracteres e deixa so numeros
                        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

                        if(mb_strlen($cnpj) !== 14){
                            return "CNPJ invalido";
                        }

                        $url = "https://www.receitaws.com.br/v1/cnpj/{$cnpj}";
                        
                        $ch = curl_init();

                        curl_setopt($ch, CURLOPT_URL, $url);

                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                        $response = curl_exec($ch);

                        curl_close($ch);

                        $data = json_decode($response, true);

                        if(isset($data['status']) && $data['status'] === 'ERROR'){
                            return "Erro na consulta: " . $data['message'];
                        }

                        return $data;                        
                    }

                    $cnpj = $post;
                    $resultado = consultarCNPJ($cnpj);

                    if(is_array($resultado)){
                        echo '
                        <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">'.$resultado['nome'].'</h5>
                            <p><strong>Nome Fantasia: </strong>'.$resultado['fantasia'].'</p>
                            <p><strong>CNPJ: </strong>'.$resultado['cnpj'].'</p>
                            <p><strong>Abertura: </strong>'.$resultado['abertura'].'</p>
                            <p><strong>Atividade Principal: </strong>'.$resultado['atividade_principal'][0]['text'].'</p>
                            <p><strong>UF: </strong>'.$resultado['uf'].'</p>
                            <p><strong>Telefone: </strong>'.$resultado['telefone'].'</p>
                            <p><strong>E-mail: </strong>'.$resultado['email'].'</p>
                            <p><strong>Status: </strong>'.$resultado['situacao'].'</p>
                        </div>
                        </div>
                        
                        ';

                    }
                }



            ?>
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>