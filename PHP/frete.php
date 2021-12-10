<?php
    class frete {

        public $valorFrete;
        public $entregaFrete;

        public function calcularFrete($CEP) {
            include("PHP/conn.php");

            $SQL = $conn->prepare('SELECT * FROM filial WHERE IDFilial=? LIMIT 1');
            $SQL->execute(['1']);
            $SQL = $SQL->fetch();

            $CEPDestino = str_replace('.', '', $CEP);
            $CEPDestino = str_replace('-', '', $CEPDestino);

            $CEPOrigem  = str_replace('.', '', $SQL['CEP']);
            $CEPOrigem  = str_replace('-', '', $CEPOrigem);

            $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?";
            $url .= "nCdEmpresa=";
            $url .= "&sDsSenha=";
            $url .= "&sCepOrigem=" . $CEPOrigem;
            $url .= "&sCepDestino=" . $CEPDestino;

            $altura = 0;
            $largura = 0;
            $peso = 0;
            $comprimento = 0;

            // foreach ($_SESSION['BAG'] as $IDProduto => $QNTD) {
            //     $SQLProd = $conn->prepare('SELECT * FROM produtos WHERE IDProduto=? LIMIT 1');
            //     $SQLProd->execute([$_SESSION['BAG'][$IDProduto]['IDProduto']]);
            //     $SQLProd = $SQLProd->fetch();

            //     $altura = $altura + $SQLProd['Altura'];
            //     $largura = $largura + $SQLProd['Largura'];
            //     $peso = $peso + $SQLProd['Peso'];
            //     $comprimento = $comprimento + $SQLProd['Comprimento'];
            // }

            $altura = 16;
            $largura = 21;
            $peso = 0.60;
            $comprimento = 15;

            $url .= "&nVlAltura=" . number_format($altura, 2, ',', '.');
            $url .= "&nVlLargura=" . number_format($largura, 2, ',', '.');
            $url .= "&nVlPeso=" . number_format($peso, 2, ',', '.');
            $url .= "&nVlComprimento=" . number_format($comprimento, 2, ',', '.');;
            $url .= "&nVlDiametro=0";
            
            $url .= "&nVlValorDeclarado=0";
            $url .= "&nCdFormato=1";
            $url .= "&nCdServico=41106";
            $url .= "&sCdMaoProria=S";
            $url .= "&sCdAvisoRecebimento=n";

            $url .= "&StrRetorno=xml";

            $xml = simplexml_load_file($url);

            $Node = $xml->cServico;

            $this->valorFrete   = $Node->Valor;
            $this->entregaFrete = $Node->PrazoEntrega;
        }
    }
?>