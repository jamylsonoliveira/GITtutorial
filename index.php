<?php
ob_start();
@session_start();

if (isset($_GET['add_empresa_ref'])) {
    $empresa_sel = $_GET['add_empresa_ref'];
    $_SESSION['empresa_selecionada'] = $empresa_sel;
    header("Location:index.php?resp=<b>Empresa Selecionada com sucesso!</b><br> Suas ações afetarão a empresa selecionada a partir de agora.&tipo_r=p");
} else {
    $empresa_selecionada = $_SESSION['empresa_selecionada'];
}

require 'conexao.php';
$email = $_SESSION['email_liberado'];
$senha = $_SESSION['senha_liberado'];
$nivel_u = $_SESSION['nivel'];
$empresa_selecionada = $_SESSION['empresa_selecionada'];

if (!isset($_SESSION['email_liberado']) and !isset($_SESSION['senha_liberado'])) {
    header("Location:login.php?resp=Área Segura. <b>Faça o login para continuar</b>!");
}

//pegando dados do usuario
$e = mysql_query("SELECT * FROM usuarios WHERE email='$email' AND senha='$senha'");
while ($l = mysql_fetch_array($e)) {
    $id = $l['id'];
    $nome = $l['nome'];
    $cpf = $l['cpf'];
    $rg = $l['rg'];
    $cargo = $l['cargo'];
    $setor = $l['setor'];
    $nivel = $l['nivel'];
    $telefone = $l['telefone'];
    $plano_qtd_empresas = $l['plano_qtd_empresas'];
    $ativo = $l['ativo'];
}


if ($empresa_selecionada != '') {
    $sql_sec = "SELECT * FROM empresa WHERE ref = '$empresa_selecionada' AND id = '$id'";
    $e_sec = mysql_query($sql_sec);
    $qtd_sec = mysql_num_rows($e_sec);
    if ($qtd_sec != 1) {
        header("Location:login.php?resp=Notamos um erro de autoridade. Por favor acesse somente empresas criadas por você. Nós iremos registrar a ocorrência. Caso tenha sido um engano não se preocupe nossos especialistas analisam todas as ações suspeitas para maior segurança. Obrigado pela compreensão.</b>!");
    }
}

//coletando dados da empresa selecionada
$sql_sec = "SELECT * FROM empresa WHERE ref = '$empresa_selecionada' AND id = '$id'";
$e_sec = mysql_query($sql_sec);
while ($li = mysql_fetch_array($e_sec)) {
    $id_e_atual = $li['id_e'];
    $empresa_atual = $li['empresa'];
    $cnpj_atual = $li['cnpj'];
    $img_empresa_atual = $li['img_empresa'];
}

$primeiro_nome = explode(' ', $nome);

$data_reg = date('Y-m-d H:i:s');

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Avant Data Protection - Bem vindo!</title>
    <link rel="shortcut icon" type="image/x-icon" href="img/Favicon_Bayrrow.png" />
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <style>
        .heading-box h2 {
            width: 100%;
            color: #eb1c24;
        }

        .text-box {
            position: absolute;
            top: 50%;
            left: 15%;
            right: 15%;
            color: #fff;
            text-align: center;
            transform: translateY(-50%);
        }

        #tb_1 {
            padding: 30px;
            width: 100%;
            height: 100%;
            min-width: 100%;
            min-height: 100%;
            border: 0px solid #CCC;
            border-radius: 20px;
            background: #23bcff;
            color: #FFF !important;
            cursor: pointer;
            display: inline-block;
            font-size: 20px;
            font-weight: 500;
            line-height: 22px;
            display: flex;
            align-items: center;
            box-shadow: 0 10px 20px -10px rgb(3 15 23 / 59%);
        }

        .fa-3x {
            font-size: 3em;
            padding: 5px;
        }

        .table {
            background-color: #FFF;
            color: #000;
        }

        .table-bordered {
            border: 0px solid #fff;
        }

        .table>tbody>tr>td {
            color: #000;
        }

        .pedra {
            border: solid 1px #fff;
            border-radius: 100%;
            background-color: #f5f5f5;
            min-width: 120px;
            max-width: 120px;
            min-height: 120px;
            max-height: 120px;
            padding: 10px;
            /* The magic are those 2 lines: */
            border-radius: 100%;
            box-shadow: inset 0 0 1em rgb(0, 0, 0, 0.5);
        }

        .table td,
        .table th,
        .table thead th {
            border: 0px;
            border-color: #f5f5f5 !important;
            background: #f5f5f5;
        }

        .carousel-control-next,
        .carousel-control-prev {
            width: 30%;
        }

        #carouselExampleControls {
            width: 100%;
        }


        /*@media screen and (min-width: 800px) and (max-width: 1900px) {*/
        .carousel-control-prev {
            position: absolute;
            top: 0;
            bottom: 0;
            z-index: 1;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: left;
            align-items: left;
            -ms-flex-pack: left;
            justify-content: left;
            width: 15%;
            color: #fff;
            text-align: left;
            opacity: .5;
            transition: opacity .15s ease;
        }

        .carousel-control-next {
            position: absolute;
            top: 0;
            bottom: 0;
            z-index: 1;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: right;
            align-items: right;
            -ms-flex-pack: right;
            justify-content: right;
            width: 15%;
            color: #fff;
            text-align: right;
            opacity: .5;
            transition: opacity .15s ease;
            padding-left: 50px;
        }

        /*}*/
    </style>

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <link rel="stylesheet" href="//cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.8.55/css/materialdesignicons.min.css">

    <script data-print src='https://cdn.jsdelivr.net/npm/chart.js'></script>


</head>

<body>

    <?php
    include("inc/menu_topo.php");
    ?>

    <!--  BEGIN NAVBAR  -->
    <div class="sub-header-container">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg></a>

            <ul class="navbar-nav flex-row">
                <li>
                    <div class="page-header">

                        <nav class="breadcrumb-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="javascript:void(0);">Início</a>
                                </li>
                                <?php include "inc/painel_topo.php"; ?>
                            </ol>
                        </nav>

                    </div>
                </li>
            </ul>
            <ul class="navbar-nav flex-row ml-auto ">
                <li class="nav-item more-dropdown">
                    <div class="dropdown  custom-dropdown-icon">
                        <a class="dropdown-toggle btn" href="#" role="button" id="customDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>Configurações</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg></a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="customDropdown">
                            <a class="dropdown-item" data-value="Ainda Indisponível" href="javascript:void(0);">Ainda Indisponível</a>
                            <!-- <a class="dropdown-item" data-value="Mail" href="javascript:void(0);">Mail</a>
                            <a class="dropdown-item" data-value="Print" href="javascript:void(0);">Print</a>
                            <a class="dropdown-item" data-value="Download" href="javascript:void(0);">Download</a>
                            <a class="dropdown-item" data-value="Share" href="javascript:void(0);">Share</a>-->
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <?php

        include "inc/menu-esquerdo.php";

        ?>

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">

            <?php

            if ($_GET['resp'] != '') {

                $tipo = $_GET['tipo_r'];
                if ($tipo == 'n') {
                    $balao = 'danger';
                } else {
                    $balao = 'success';
                }

                echo '<div class="alert alert-' . $balao . '" role="alert">
 ' . $_GET['resp'] . '</div>';
            }



            ?>

            <!--slider-->

            <!--   <section>
                <div id="slider-animation" class="carousel slide" data-ride="carousel">

                    <!-- Indicators -->
            <!--      <ul class="carousel-indicators">
                        <li data-target="#slider-animation" data-slide-to="0" class="active"></li>
                        <li data-target="#slider-animation" data-slide-to="1"></li>
                        <li data-target="#slider-animation" data-slide-to="2"></li>
                    </ul>

                    <!-- The slideshow -->
            <!--       <div class="carousel-inner" style="max-width:100%;">
                        <div class="carousel-item active">
                            <img src="slides/banner02.png" style="width:100%;max-width:100%;">
                            <div class="text-box">
                                <!-- <h2 class="wow slideInRight" data-wow-duration="2s">This is Obitope text</h2>
            <p class="wow slideInLeft" data-wow-duration="2s">There is now an abundance of readable dummy texts. These are usually used when a text is required purely to fill a space. </p>
      -->
            <!--            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="slides/banner01.png" style="width:100%;max-width:100%;">
                            <div class="text-box">
                                <!--   <h2 class="wow fadeInUp" data-wow-duration="4s">This is Airborne text</h2>
            <p class="wow fadeInUp" data-wow-duration="2s">There is now an abundance of readable dummy texts. These are usually used when a text is required purely to fill a space. </p>
      -->
            <!--          </div>
                        </div>
                    </div>

                    <!-- Left and right controls -->
            <!--  <a class="carousel-control-prev" href="#slider-animation" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#slider-animation" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>

                </div>

            </section>
            <script type="text/javascript">
                wow = new WOW({
                    animateClass: 'animated',
                    offset: 100,
                    callback: function(box) {
                        console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
                    }
                });
                wow.init();
                document.getElementById('moar').onclick = function() {
                    var section = document.createElement('section');
                    section.className = 'section--purple wow fadeInDown';
                    this.parentNode.insertBefore(section, this);
                };
            </script>

            <!--fim slider-->



            <div class="layout-px-spacing">
                <br>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-one" style="background-color:#FFF;color:#000;">
                        <center>
                            <h4 style="color:#000;">Seja bem vindo ao seu Avant Data Protection!</h4>
                    </div>
                </div>




                <style data-print>
                    @import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');

                    .charts {
                        display: flex;
                        flex-wrap: wrap;
                        justify-content: center;
                        margin-top: 1rem;
                    }

                    .chart,
                    .number-info {
                        width: 200px;
                        background: #fffdfe;
                        padding: .25rem;
                        border-radius: 20px;
                        margin: 0.5rem;
                        -webkit-box-align: center;
                        -webkit-box-shadow: 0 3px 6px 0 rgb(0 0 0 / 16%);
                        box-shadow: 0 3px 6px 0 rgb(0 0 0 / 16%);
                        -webkit-box-sizing: border-box;

                    }

                    .number-info {
                        height: 300px;

                        font-family: 'Open Sans', sans-serif;
                        font-size: 1rem;

                        display: flex;
                        flex-direction: column;
                        align-items: center;

                    }

                    .number-info header {
                        width: 100%;
                        margin-top: 1rem;
                        margin-bottom: 1rem;

                        font-weight: bold;
                        font-size: 1rem;
                        text-align: center;
                    }

                    .number-info main span {
                        color: #002247;
                        font-size: 4rem;

                        margin-top: 2rem;
                    }

                    .number-info footer {
                        width: 100%;
                    }

                    .number-info footer.right-number {
                        display: flex;
                        justify-content: flex-end;
                        font-size: 1.1rem;
                        margin-top: 3.5rem;
                        margin-right: 3rem;
                    }
                </style>
                <div <?php

                        if (is_null($id_e_atual)) {
                            echo "style='display: none;'";
                        }

                        ?> class="charts" data-print>

                    <?php

                    function getTotalProcessos($empresa_id)
                    {
                        $total = mysql_query(
                            "SELECT COUNT(*) FROM `processos` 
                        WHERE id_e = $empresa_id;"
                        );
                        $total = mysql_fetch_assoc(
                            $total
                        )['COUNT(*)'];

                        return $total;
                    }

                    echo "
                    <div class='number-info'>
                        <header> Total de processos criados </header>
                        
                        <main>
                            <span>" . getTotalProcessos($id_e_atual) . "</span>
                        </main>
                    
                    </div>
                ";

                    ?>

                    <?php

                    function getDataProcessosDPIAToJs($empresa_id)
                    {
                        $quantidadeTotal = getTotalProcessos($empresa_id);

                        $sofremDPIA = mysql_query(
                            "SELECT COUNT(*) FROM `processos` 
                        WHERE 
                            id_e = $empresa_id AND
                            processo_DPIA_logico = 'SIM';"
                        );
                        $sofremDPIA = mysql_fetch_assoc(
                            $sofremDPIA
                        )['COUNT(*)'];


                        $naoSofremDPIA = $quantidadeTotal - $sofremDPIA;

                        return "[$sofremDPIA, $naoSofremDPIA]";
                    }

                    echo "
                    <div class='chart'>
                        <canvas id='processosDPIA'></canvas>
                    </div>
                
                    <script>
                        const processosDPIAChart = new Chart(
                            document.querySelector('canvas#processosDPIA'),
                            {
                                type: 'doughnut',
                                data: {
                                    labels: [
                                        'SOFREM DPIA',
                                        'NÃO SOFREM DPIA'
                                    ],
                                    datasets: [
                                        {
                                            label: '',
                                            data: " . getDataProcessosDPIAToJs($id_e_atual) . ",
                                            backgroundColor: [
                                                '#002247',
                                                '#fe0000'
                                            ],
                                            borderWidth: 0
                                        }
                                    ] 
                                },
                                options: {
                                    plugins: {
                                        title: {
                                            display: true,
                                            text:  [
                                                'Total de processos (" . getTotalProcessos($id_e_atual) . ")', 
                                                'x', 
                                                'Processos com DPIA'
                                            ] 
                                        }
                                    }
                                },
                                // plugins: [backgroundPlugin],
                            }
                        );
                    </script>
                ";
                    ?>

                    <?php

                    function getDataProcessosBaseToJs($empresa_id)
                    {
                        $total = getTotalProcessos($empresa_id);

                        $semBase = mysql_query(
                            "SELECT COUNT(*) FROM processos 
                        WHERE 
                            id_e = $empresa_id AND 
                            id_base = 23;"
                        );
                        $semBase = mysql_fetch_assoc(
                            $semBase
                        )['COUNT(*)'];

                        $comBase = $total - $semBase;

                        return "[$comBase, $semBase]";
                    }

                    echo "
                    <div class='chart'>
                        <canvas id='processosBase'></canvas>
                    </div>

                    <script>
                        const processosBaseChart = new Chart(
                            document.querySelector('canvas#processosBase'),
                            {
                                type: 'doughnut',
                                data: {
                                    labels: [
                                        'COM BASE LEGAL',
                                        'SEM BASE LEGAL'
                                    ],
                                    datasets: [
                                        {
                                            label: '',
                                            data: " . getDataProcessosBaseToJs($id_e_atual) . ",
                                            backgroundColor: [
                                                '#002247',
                                                '#fe0000'
                                            ],
                                            borderWidth: 0
                                        }
                                    ] 
                                },
                                options: {
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: [
                                                'Total de processos (" . getTotalProcessos($id_e_atual) . ")', 
                                                'x', 
                                                'Base legal'
                                            ]
                                        }
                                    }
                                },
                                // plugins: [backgroundPlugin],
                            }
                        );
                    </script>

                ";
                    ?>

                    <?php

                    function getDataProcessosInternacionalToJs($empresa_id)
                    {
                        $total = getTotalProcessos($empresa_id);

                        $envia = mysql_query(
                            "SELECT COUNT(*) FROM processos 
                        WHERE 
                            id_e = $empresa_id AND 
                            dados_env_terceiros_logico = 'SIM';"
                        );
                        $envia = mysql_fetch_assoc(
                            $envia
                        )['COUNT(*)'];

                        $naoEnvia = $total - $envia;

                        return "[$naoEnvia, $envia]";
                    }

                    echo "
                    <div class='chart'>
                        <canvas id='processosInternacional'></canvas>
                    </div>

                    <script>
                        const processosInternacionalChart = new Chart(
                            document.querySelector('canvas#processosInternacional'),
                            {
                                type: 'doughnut',
                                data: {
                                    labels: [
                                        'COM TRANSFERÊNCIA',
                                        'SEM TRANSFERÊNCIA'
                                    ],
                                    datasets: [
                                        {
                                            label: '',
                                            data: " . getDataProcessosInternacionalToJs($id_e_atual) . ",
                                            backgroundColor: [
                                                '#002247',
                                                '#fe0000'
                                            ],
                                            borderWidth: 0
                                        }
                                    ] 
                                },
                                options: {
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: [
                                                'Total de processos criados (" . getTotalProcessos($id_e_atual) . ")', 
                                                'x',
                                                'Tranferência internacional de dados'
                                            ]
                                        }
                                    }
                                },
                                // plugins: [backgroundPlugin],
                            }
                        );
                    </script>
            
                ";

                    ?>

                    <?php

                    function buildArrayOfString($string, $token = ',')
                    {
                        $array = explode($token, $string);
                        $array = array_map(function ($value) {
                            return trim($value);
                        }, $array);
                        $array = array_filter($array, function ($value) {
                            return $value != "";
                        });

                        return array_values($array);
                    }

                    function rand_color()
                    {
                        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                    }

                    function getRandomColorArrayToJs($len = 0)
                    {

                        $colors = array();

                        for ($i = 0; $i < $len; $i++) {
                            array_push(
                                $colors,
                                rand_color()
                            );
                        }

                        $colors = array_map(function ($color) {
                            return "'$color'";
                        }, $colors);
                        $colors = implode(", ", $colors);

                        return "[$colors]";
                    }

                    function getLabelsProcessosSetoresToJs($empresa_id)
                    {
                        $result = mysql_query(
                            "SELECT id_setor FROM `processos` 
                        WHERE id_e = $empresa_id;"
                        );

                        $setores = array();

                        while ($row = mysql_fetch_assoc($result)) {

                            $setores_id = buildArrayOfString($row['id_setor']);

                            foreach ($setores_id as $setor_id) {
                                $setor = mysql_query(
                                    "SELECT area FROM areas 
                                WHERE id_a = $setor_id"
                                );
                                $setor = mysql_fetch_assoc(
                                    $setor
                                )['area'];

                                if (!in_array($setor, $setores)) {
                                    array_push($setores, $setor);
                                }
                            }
                        }

                        $setores = array_map(
                            function ($setor) {
                                return "'$setor'";
                            },
                            $setores
                        );
                        $setores = implode(",", $setores);

                        return "[$setores]";
                    }

                    function getDataProcessosSetoresToJs($empresa_id)
                    {
                        $result = mysql_query(
                            "SELECT id_setor FROM `processos` 
                        WHERE id_e = $empresa_id;"
                        );

                        $setores = array();

                        while ($row = mysql_fetch_assoc($result)) {

                            $setores_id = buildArrayOfString($row['id_setor']);

                            foreach ($setores_id as $setor_id) {
                                $setor = mysql_query(
                                    "SELECT area FROM areas 
                                WHERE id_a = $setor_id"
                                );
                                $setor = mysql_fetch_assoc(
                                    $setor
                                )['area'];

                                if (!key_exists($setor, $setores)) {
                                    $setores[$setor] = 1;
                                    continue;
                                }

                                $setores[$setor]++;
                            }
                        }

                        $setores = array_values($setores);
                        $setores = implode(",", $setores);

                        return "[$setores]";
                    }

                    echo "
                    <div class='chart extra-height'>
                        <canvas id='processosSetores'></canvas>
                    </div>

                    <script>
                        const processosSetoresChart = new Chart(
                            document.querySelector('canvas#processosSetores'),
                            {
                                type: 'doughnut',
                                data: {
                                    labels: ";
                    $labels = getLabelsProcessosSetoresToJs($id_e_atual);
                    $countCommas = substr_count($labels, ",") + 1;

                    echo $labels;
                    echo ",
                                    datasets: [
                                        {
                                            data: " . getDataProcessosSetoresToJs($id_e_atual) . ",
                                            backgroundColor: " . getRandomColorArrayToJs($countCommas) . ",
                                            borderWidth: 0
                                        }
                                    ] 
                                },
                                options: {
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: [
                                                'Total de processos (" . getTotalProcessos($id_e_atual) . ")',
                                                'x',
                                                'Setores/Areas'
                                            ]
                                        }
                                    }
                                },
                                // plugins: [backgroundPlugin],
                            }
                        );
                    </script>
                ";

                    ?>

                    <?php

                    function getDataProcessosRiscosToJs($empresa_id)
                    {
                        $riscos = array(
                            'baixo' => 0,
                            'medio' => 0,
                            'alto' => 0,
                            'altissimo' => 0,
                        );

                        $result = mysql_query(
                            "SELECT COUNT(*) 
                        FROM processos 
                        WHERE 
                            id_e = $empresa_id AND 
                            (risco_valor = 1 OR risco_valor = 2);"
                        );

                        $riscos['baixo'] = mysql_fetch_assoc($result)['COUNT(*)'];

                        $result = mysql_query(
                            "SELECT COUNT(*) 
                        FROM processos 
                        WHERE 
                            id_e = $empresa_id AND 
                            risco_valor = 4;"
                        );

                        $riscos['medio'] = mysql_fetch_assoc($result)['COUNT(*)'];

                        $result = mysql_query(
                            "SELECT COUNT(*) 
                        FROM processos 
                        WHERE 
                            id_e = $empresa_id AND 
                            (risco_valor = 6 OR 
                            risco_valor = 8 OR 
                            risco_valor = 9);"
                        );

                        $riscos['alto'] = mysql_fetch_assoc($result)['COUNT(*)'];

                        $result = mysql_query(
                            "SELECT COUNT(*) 
                        FROM processos 
                        WHERE 
                            id_e = $empresa_id AND 
                            (risco_valor = 12 OR risco_valor = 16);"
                        );

                        $riscos['altissimo'] = mysql_fetch_assoc($result)['COUNT(*)'];

                        $riscos = array_values($riscos);
                        $riscos = implode(",", $riscos);

                        return "[$riscos]";
                    }


                    echo "
                    <div class='chart'>
                        <canvas id='processosRiscos'></canvas>
                    </div>

                    <script>
                        const processosRiscosChart = new Chart(
                            document.querySelector('canvas#processosRiscos'),
                            {
                                type: 'doughnut',
                                data: {
                                    labels: [
                                        'BAIXO',
                                        'MÉDIO',
                                        'ALTO',
                                        'ALTÍSSIMO'
                                    ],
                                    datasets: [
                                        {
                                            label: '',
                                            data: " . getDataProcessosRiscosToJs($id_e_atual) . ",
                                            backgroundColor: [
                                                '#009f6b',
                                                '#ffd300',
                                                '#fe0000',
                                                '#6b269f'
                                            ],
                                            borderWidth: 0
                                        }
                                    ] 
                                },
                                options: {
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: [
                                                'Total de processos (" . getTotalProcessos($id_e_atual) . ")',
                                                'x',
                                                 'Risco do processo'
                                            ]
                                        }
                                    }
                                },
                                // plugins: [backgroundPlugin],
                            }
                        );
                    </script>

                ";

                    ?>

                    <?php

                    function getTotalIncidentes($empresa_id)
                    {
                        $total = mysql_query(
                            "SELECT COUNT(*) FROM `gestoes_incidentes` 
                        WHERE id_emp = $empresa_id;"
                        );
                        $total = mysql_fetch_assoc(
                            $total
                        )['COUNT(*)'];

                        return $total;
                    }

                    echo "
                    <div class='number-info'>
                        <header> Total de incidentes sofridos </header>
                    
                        <main>
                            <span>" . getTotalIncidentes($id_e_atual) . "</span>
                        </main>
                    
                    </div>
                ";

                    ?>

                    <?php

                    function getTotalPlanos($empresa_id)
                    {
                        $total = mysql_query(
                            "SELECT COUNT(*) FROM `planos` 
                        WHERE id_emp = $empresa_id;"
                        );
                        $total = mysql_fetch_assoc(
                            $total
                        )['COUNT(*)'];

                        return $total;
                    }

                    echo "
                    <div class='number-info'>
                        <header> Total de planos criados </header>

                        <main>
                            <span>" . getTotalPlanos($id_e_atual) . "</span>
                        </main>

                    </div>
                ";
                    ?>

                    <?php

                    function getTotalTimes($empresa_id)
                    {
                        $total = mysql_query(
                            "SELECT COUNT(*) FROM `times` 
                        WHERE id_emp = $empresa_id;"
                        );
                        $total = mysql_fetch_assoc(
                            $total
                        )['COUNT(*)'];

                        return $total;
                    }

                    function getTotalMembrosTimes($empresa_id)
                    {
                        $total = mysql_query(
                            "SELECT 
                            COUNT(DISTINCT(colaboradores.id_colab)) as total
                        FROM `times` 
                            INNER JOIN membros_time 
                            INNER JOIN responsaveis 
                            INNER JOIN colaboradores
                        WHERE times.id_emp = $empresa_id"
                        );

                        $total = mysql_fetch_assoc(
                            $total
                        )['total'];

                        return $total;
                    }

                    echo "
                    <div class='number-info'>
                        <header> Total de times criados </header>

                        <main>
                            <span>" . getTotalTimes($id_e_atual) . "</span>
                        </main>

                        <!-- <footer class='right-number'>
                            <span>" . getTotalMembrosTimes($id_e_atual) . " colaboradores</span>
                        </footer> -->

                    </div>
                ";
                    ?>

                    <?php

                    function getTotalComunicacoes($empresa_id)
                    {
                        $totalComunicacaoExternaI = mysql_query(
                            "SELECT COUNT(*) 
                        FROM comunicacao_externa_interessados_operadores
                        WHERE id_emp = $empresa_id;"
                        );

                        $totalComunicacaoExternaI = mysql_fetch_assoc(
                            $totalComunicacaoExternaI
                        )['COUNT(*)'];


                        $totalComunicacaoExternaII = mysql_query(
                            "SELECT COUNT(*) 
                        FROM comunicacao_externa_publico
                        WHERE id_emp = $empresa_id;"
                        );

                        $totalComunicacaoExternaII = mysql_fetch_assoc(
                            $totalComunicacaoExternaII
                        )['COUNT(*)'];


                        $totalComunicacaoInternaI = mysql_query(
                            "SELECT COUNT(*) 
                        FROM comunicacao_interna_empresa
                        WHERE id_emp = $empresa_id;"
                        );

                        $totalComunicacaoInternaI = mysql_fetch_assoc(
                            $totalComunicacaoInternaI
                        )['COUNT(*)'];


                        $totalComunicacaoInternaII = mysql_query(
                            "SELECT COUNT(*) 
                        FROM comunicacao_interna_funcionario
                        WHERE id_emp = $empresa_id;"
                        );

                        $totalComunicacaoInternaII = mysql_fetch_assoc(
                            $totalComunicacaoInternaII
                        )['COUNT(*)'];

                        return
                            $totalComunicacaoExternaI +
                            $totalComunicacaoExternaII +
                            $totalComunicacaoInternaI +
                            $totalComunicacaoInternaII;
                    }



                    echo "
                    <div class='number-info'>
                        <header> Total de comunicações criadas </header>
                    
                        <main>
                            <span>" . getTotalComunicacoes($id_e_atual) . "</span>
                        </main>
                    
                    </div>
                ";
                    ?>

                    <?php

                    function getDataIncidentesViolacaoToJs($empresa_id)
                    {
                        $quantidadeTotal = getTotalIncidentes($empresa_id);

                        $comViolacao = mysql_query(
                            "SELECT COUNT(*) FROM gestoes_incidentes
                        WHERE 
                            id_emp = $empresa_id AND 
                            violocao_logico = 'sim';"
                        );
                        $comViolacao = mysql_fetch_assoc(
                            $comViolacao
                        )['COUNT(*)'];

                        $semViolacao = $quantidadeTotal - $comViolacao;

                        return "[$comViolacao, $semViolacao]";
                    }


                    echo "
                    <div class='chart'>
                        <canvas id='incidentesViolocao'></canvas>
                    </div>

                    <script>
                        const incidentesViolocaoChart = new Chart(
                            document.querySelector('canvas#incidentesViolocao'),
                            {
                                type: 'doughnut',
                                data: {
                                    labels: [
                                        'COM VIOLAÇÃO DE DADOS',
                                        'SEM VIOLAÇÃO DE DADOS'
                                    ],
                                    datasets: [
                                        {
                                            label: '',
                                            data: " . getDataIncidentesViolacaoToJs($id_e_atual) . ",
                                            backgroundColor: [
                                                '#002247',
                                                '#fe0000'
                                            ],
                                            borderWidth: 0
                                        }
                                    ] 
                                },
                                options: {
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: [
                                                'Total de Incidentes (" . getTotalIncidentes($id_e_atual) . ")',
                                                'x',
                                                'Violação de dados'
                                            ]
                                        }
                                    }
                                },
                                // plugins: [backgroundPlugin],
                            }
                        );
                
                    </script>

                ";
                    ?>

                    <?php

                    function getDataIncidentesRiscosToJs($empresa_id)
                    {
                        $riscos = array(
                            'baixo' => 0,
                            'medio' => 0,
                            'alto' => 0,
                            'altissimo' => 0,
                            'N/A' => 0
                        );

                        $result = mysql_query(
                            "SELECT plano_logico, plano_id, impacto 
                        FROM gestoes_incidentes 
                        WHERE id_emp = $empresa_id"
                        );

                        while ($gestao = mysql_fetch_assoc($result)) {
                            if ($gestao['plano_logico'] == 'sim') {
                                $impacto = $gestao['impacto'];

                                $probabilidade = $gestao['plano_id'];
                                $probabilidade = mysql_query("
                                SELECT id_pro 
                                FROM planos 
                                WHERE id_pla = $probabilidade;
                            ");

                                $probabilidade = mysql_fetch_assoc($probabilidade)['id_pro'];
                                $probabilidade = mysql_query("
                                SELECT valor 
                                FROM probabilidade 
                                WHERE id_prob = $probabilidade;
                            ");
                                $probabilidade = mysql_fetch_assoc($probabilidade)['valor'];

                                $risco = $probabilidade * $impacto;

                                in_array($risco, array(1, 2)) ? $riscos['baixo']++ : '';
                                in_array($risco, array(4)) ? $riscos['medio']++ : '';
                                in_array($risco, array(6, 8, 9)) ? $riscos['alto']++ : '';
                                in_array($risco, array(12, 16)) ? $riscos['altissimo']++ : '';

                                continue;
                            }

                            $riscos['N/A']++;
                        }

                        return "[
                        " . $riscos['baixo'] . ",
                        " . $riscos['medio'] . ",
                        " . $riscos['alto'] . ",
                        " . $riscos['altissimo'] . ",
                        " . $riscos['N/A'] . "
                    ]";
                    }

                    echo "
                    <div class='chart'>
                        <canvas id='incidentesRiscos'></canvas>
                    </div>

                    <script>
                        const incidentesRiscosChart = new Chart(
                            document.querySelector('canvas#incidentesRiscos'),
                            {
                                type: 'doughnut',
                                data: {
                                    labels: [
                                        'BAIXO',
                                        'MÉDIO',
                                        'ALTO',
                                        'ALTÍSSIMO',
                                        'N/A'
                                    ],
                                    datasets: [
                                        {
                                            label: '',
                                            data: " . getDataIncidentesRiscosToJs($id_e_atual) . ",
                                            backgroundColor: [
                                                '#009f6b',
                                                '#ffd300',
                                                '#fe0000',
                                                '#6b269f',
                                                'rgb(225, 225, 225)'
                                            ],
                                            borderWidth: 0
                                        }
                                    ] 
                                },
                                options: {
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: [
                                                'Total de incidentes (" . getTotalIncidentes($id_e_atual) . ")', 
                                                'x', 
                                                'Risco do incidentes'
                                            ]
                                        }
                                    }
                                },
                                // plugins: [backgroundPlugin],
                            }
                        );
                
                    </script>

                ";

                    ?>

                    <?php

                    function getLabelsincidentesNomesToJs($empresa_id)
                    {
                        $result = mysql_query(
                            "SELECT DISTINCT(nome) 
                        FROM gestoes_incidentes 
                        WHERE id_emp = $empresa_id"
                        );

                        $labels = array();

                        while ($nome = mysql_fetch_assoc($result)) {
                            array_push(
                                $labels,
                                $nome['nome']
                            );
                        }

                        $labels = array_map(function ($label) {
                            return "'$label'";
                        }, $labels);
                        $labels = implode(", ", $labels);

                        return "[$labels]";
                    }

                    function getDataincidentesNomesToJs($empresa_id)
                    {
                        $result = mysql_query(
                            "SELECT DISTINCT(nome) 
                        FROM gestoes_incidentes 
                        WHERE id_emp = $empresa_id"
                        );

                        $ocorrencias = array();

                        while ($row = mysql_fetch_assoc($result)) {

                            $nome = $row['nome'];

                            if (!array_key_exists($nome, $ocorrencias)) {
                                $ocorrencias[$nome] = 1;
                                continue;
                            }

                            $ocorrencias[$nome]++;
                        }

                        $ocorrencias = array_values($ocorrencias);
                        $ocorrencias = implode(",", $ocorrencias);

                        return "[$ocorrencias]";
                    }


                    echo "
                    <div class='chart'>
                        <canvas id='incidentesNome'></canvas>
                    </div>

                    <script>
                        const incidentesNomeChart = new Chart(
                            document.querySelector('canvas#incidentesNome'),
                            {
                                type: 'doughnut',
                                data: {
                                    labels:";
                    $labels = getLabelsincidentesNomesToJs($id_e_atual);

                    $countCommas = substr_count($labels, ",") + 1;

                    echo $labels;
                    echo ",
                                    datasets: [
                                        {
                                            label: '',
                                            data: " . getDataincidentesNomesToJs($id_e_atual) . ",
                                            backgroundColor: " . getRandomColorArrayToJs($countCommas) . ",
                                            borderWidth: 0
                                        }
                                    ] 
                                },
                                options: {
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: [
                                                'Total de incidentes (" . getTotalIncidentes($id_e_atual) . ")', 
                                                'x',
                                                'Nome do incidente'
                                            ]
                                        }
                                    }
                                },
                                // plugins: [backgroundPlugin],
                            }
                        );
                
                    </script>

                ";

                    ?>

                    <?php

                    function getLabelsIncidentesSetoresToJs($empresa_id)
                    {

                        $result = mysql_query(
                            "SELECT DISTINCT(setor) FROM gestoes_incidentes 
                        WHERE id_emp = $empresa_id"
                        );

                        $setores = array();

                        while ($incidente = mysql_fetch_assoc($result)) {
                            $setor = $incidente['setor'];

                            $setor = mysql_query(
                                "SELECT area FROM areas 
                            WHERE id_a = $setor"
                            );
                            $setor = mysql_fetch_assoc($setor)['area'];

                            if (!in_array($setor, $setores)) {
                                array_push($setores, $setor);
                            }
                        }

                        $setores = array_map(
                            function ($setor) {
                                return "'$setor'";
                            },
                            $setores
                        );
                        $setores = implode(",", $setores);

                        return "[$setores]";
                    }

                    function getDataIncidentesSetoresToJs($empresa_id)
                    {

                        $result = mysql_query(
                            "SELECT setor FROM gestoes_incidentes 
                        WHERE id_emp = $empresa_id"
                        );

                        $setores = array();

                        while ($incidente = mysql_fetch_assoc($result)) {
                            $setor = $incidente['setor'];

                            $setor = mysql_query(
                                "SELECT area FROM areas 
                            WHERE id_a = $setor"
                            );
                            $setor = mysql_fetch_assoc($setor)['area'];

                            if (!key_exists($setor, $setores)) {
                                $setores[$setor] = 1;
                                continue;
                            }

                            $setores[$setor]++;
                        }

                        $setores = array_values($setores);
                        $setores = implode(",", $setores);

                        return "[$setores]";
                    }

                    echo "
                    <div class='chart'>
                        <canvas id='incidentesSetores'></canvas>
                    </div>

                    <script>
                        const incidentesSetoresChart = new Chart(
                            document.querySelector('canvas#incidentesSetores'),
                            {
                                type: 'doughnut',
                                data: {
                                    labels:";
                    $labels = getLabelsIncidentesSetoresToJs($id_e_atual);
                    $countCommas = substr_count($labels, ",") + 1;

                    echo $labels;
                    echo ",
                                    datasets: [
                                        {
                                            data: " . getDataIncidentesSetoresToJs($id_e_atual) . ",
                                            backgroundColor: " . getRandomColorArrayToJs($countCommas) . ",
                                            borderWidth: 0
                                        }
                                    ] 
                                },
                                options: {
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: [
                                                'Total de incidentes (" . getTotalIncidentes($id_e_atual) . ")',
                                                'x',
                                                'Setores/Areas'
                                            ]
                                        }
                                    }
                                },
                                // plugins: [backgroundPlugin],
                            }
                        );
                    </script>
                ";

                    ?>

                </div>

                <button <?php

                        if (is_null($id_e_atual)) {
                            echo "style='display: none;'";
                        }

                        ?> onclick="printVisao360()" class="btn btn-warning">Imprimir Visão 360</button>
                <script>
                    class PrintPage {
                        constructor(content) {
                            this.content = content;
                            this.page = window.open(
                                "", "Your diagram"
                            );

                            this.build();
                        }

                        build() {
                            this.page.document.write(
                                this.content
                            );

                            return this;
                        }

                        update(newContent) {
                            this.content = newContent;
                            this.build();

                            return this;
                        }

                        print() {
                            this.page.print();

                            return this;
                        }

                        close() {
                            this.page.close();
                        }

                    }

                    function printVisao360() {

                        const elementsPrint = [...document.querySelectorAll("[data-print]")];

                        const pageContent = elementsPrint
                            .map(({
                                outerHTML
                            }) => outerHTML)
                            .join('');

                        const printPage = new PrintPage(pageContent);

                        setTimeout(() => {
                            printPage
                                .print()
                                .close();
                        }, 500);


                    }
                </script>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <center>
                            <h4 style="color:#1c3771;">Selecione um negócio para gerenciar.</h4>
                        </center>
                    </div>
                </div>
                <br>
                <div <?php

                        if (is_null($id_e_atual)) {
                            echo "style='display: none;'";
                        }

                        ?> class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-chart-one" style="background-color:#FFF;color:#000;">
                        <center>
                            <h4 style="color:#000;">Selecione suas empresas</h4>
                    </div>
                </div>

                <div class="row layout-top-spacing">

                    <div class="layout-px-spacing" style="height:auto;min-height: calc(100vh - 95vh)!important;">

                        <?php
                        $cont_emp = 2;
                        echo '<div class="row layout-top-spacing">';

                        echo ' <div style="width: 600px;max-width:100%;" class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
              <a href="nova_empresa.php" id="tb_1" class="btn btn-success" style=""><i class="fas fa-plus fa-3x"></i> Nova Empresa</a>
            </div>';

                        $sql = "SELECT * FROM `empresa` WHERE id ='$id'";
                        $e = mysql_query($sql);
                        while ($l = mysql_fetch_array($e)) {
                            $id_e = $l['id_e'];
                            $empresa = $l['empresa'];
                            $cnpj = $l['cnpj'];
                            $img_empresa  = $l['img_empresa'];
                            $ref = $l['ref'];

                            $cod_hexa_cor_op = '#' . substr(md5(uniqid(time())), 0, 6);



                            $cont_emp++;

                            if ($cont_emp == 2) {
                                echo '<div class="row layout-top-spacing">';
                            }

                            echo ' <div style="width: 600px;max-width:100%;" class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
              <a title="SELECIONAR ESSA EMPRESA" href="index.php?add_empresa_ref=' . $ref . '" id="tb_1" class="btn btn-success" style=""><div style="background-color:#FFF;border-radius:20px;padding:0.2rem;"><img src="fotos_empresas/' . $img_empresa . '" style="opacity:0.9;width:100px;height:100px;margin-right:2rem;border:2px solid #fff;border-radius:10%;object-fit: contain;backgound-color:#FFF;"/></div> ' . $empresa . '</a>
            </div>';

                            if ($cont_emp == 4) {
                                echo '</div>';
                                $cont_emp = 1;
                            }
                        }

                        if (($cont_emp != 1)) {
                            echo '</div>';
                        }

                        ?>

                        <!--fim bloco 02-->

                    </div>


                    <!--fim do codigo segundo botao-->
                </div>

                <div class="row">
                    <div class="col-md-12" style="padding-bottom:2rem;">
                        <center><a href="empresas.php" class="btn btn-info">Editar suas empresas</a></center>
                    </div>
                </div>


            </div>

        </div>





        <?php

        include "inc/rodape.php";

        ?>

    </div>
    <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="plugins/apex/apexcharts.min.js"></script>
    <script src="assets/js/dashboard/dash_1.js"></script>
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

</body>


</html>