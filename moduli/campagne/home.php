<?php
include_once('../../config/connDB.php');
include_once(BASE_ROOT . 'config/confAccesso.php');
require_once(BASE_ROOT.'config/confPermessi.php');

//RECUPERO LA VARIABILE POST DAL FORM defaultrange

if(isset($_POST['id_agente']) && $_POST['id_agente']>0){
    $whereCommerciale = "AND id_agente='".$_POST['id_agente'] ."'";
    $whereCommercialeAll = "AND lp.id_agente='".$_POST['id_agente'] ."'";
    $whereCommercialeCal = "AND id_agente='".$_POST['id_agente'] ."'";
    $whereCommercialeCalAll = "AND ca.id_agente='".$_POST['id_agente'] ."'";
    $whereCommercialeFatt = "AND lista_fatture.id_agente='".$_POST['id_agente'] ."'";
    $whereCommercialeFattAll = "AND lf.id_agente='".$_POST['id_agente'] ."'";
}else{
    $whereCommerciale = "";
    $whereCommercialeAll = "";
    $whereCommercialeCal = "";
    $whereCommercialeCalAll = "";
    $whereCommercialeFatt = "";
    $whereCommercialeFattAll = "";
}

if(isset($_POST['id_campagna']) && $_POST['id_campagna']>0){
    $whereCampagnaId = "AND id='".$_POST['id_campagna'] ."'";
    $whereCampagna = "AND id_campagna='".$_POST['id_campagna'] ."'";
    $whereCampagnaAll = "AND lp.id_campagna='".$_POST['id_campagna'] ."'";
    $whereCampagnaCal = "AND id_campagna='".$_POST['id_campagna'] ."'";
    $whereCampagnaCalAll = "AND cp.id_campagna='".$_POST['id_campagna'] ."'";
    $whereCampagnaFatt = "AND lista_fatture.id_campagna='".$_POST['id_campagna'] ."'";
    $whereCampagnaFattAll = "AND lf.id_campagna='".$_POST['id_campagna'] ."'";
}else{
    $whereCampagna = "";
    $whereCampagnaId = "";
    $whereCampagnaAll = "";
    $whereCampagnaCal = "";
    $whereCampagnaCalAll = "";
    $whereCampagnaFatt = "";
    $whereCampagnaFattAll = "";
}

if(isset($_POST['id_tipo_marketing']) && $_POST['id_tipo_marketing']>0){
    $whereTipoMarketing = "AND ag.id_tipo_marketing='".$_POST['id_tipo_marketing'] ."'";
    $whereTipoMarketingAll = "AND lp.id_tipo_marketing='".$_POST['id_tipo_marketing'] ."'";
    $whereTipoMarketingCal = "AND ca.id_tipo_marketing='".$_POST['id_tipo_marketing'] ."'";
    $whereTipoMarketingCalAll = "AND cp.id_tipo_marketing='".$_POST['id_tipo_marketing'] ."'";
    $whereTipoMarketingFatt = "AND lista_fatture.id_tipo_marketing='".$_POST['id_tipo_marketing'] ."'";
    $whereTipoMarketingFattAll = "AND lf.id_tipo_marketing='".$_POST['id_tipo_marketing'] ."'";
}else{
    $whereTipoMarketing = "";
    $whereTipoMarketingAll = "";
    $whereTipoMarketingCal = "";
    $whereTipoMarketingCalAll = "";
    $whereTipoMarketingFatt = "";
    $whereTipoMarketingFattAll = "";
}

 if(isset($_POST['id_prodotto']) && $_POST['id_prodotto']>0){
    
    $whereProdotto = "AND id IN (SELECT lpd.id_preventivo FROM lista_preventivi_dettaglio AS lpd WHERE lpd.id_prodotto='".$_POST['id_prodotto'] ."' GROUP BY lpd.id_preventivo)";
    $whereProdottoAll = "AND lp.id IN (SELECT lpd.id_preventivo FROM lista_preventivi_dettaglio AS lpd WHERE lpd.id_prodotto='".$_POST['id_prodotto'] ."' GROUP BY lpd.id_preventivo)";
    $whereProdottoCal = "AND id_prodotto='".$_POST['id_prodotto'] ."'";
    $whereProdottoCalAll = "AND ca.id_prodotto='".$_POST['id_prodotto'] ."'";
    $whereProdottoFatt = "AND lista_fatture.id IN (SELECT lfd.id_fattura FROM lista_fatture_dettaglio AS lfd WHERE lfd.id_prodotto='".$_POST['id_prodotto'] ."' GROUP BY lfd.id_fattura)";
    $whereProdottoFattAll = "AND lf.id IN (SELECT lfd.id_fattura FROM lista_fatture_dettaglio AS lfd WHERE lfd.id_prodotto='".$_POST['id_prodotto'] ."' GROUP BY lfd.id_fattura)";
    
}else{
    $whereProdotto = "";
    $whereProdottoAll = "";
    $whereProdottoCal = "";
    $whereProdottoCalAll = "";
    $whereProdottoFatt = "";
    $whereProdottoFattAll = "";
}

if(isset($_POST['intervallo_data'])) {
    $intervallo_data = $_POST['intervallo_data'];
    $data_in = before(' al ', $intervallo_data);
    $data_out = after(' al ', $intervallo_data);
    
    /*$tmp_in = explode("-",$data_in);
    $tmp_out = explode("-",$data_out);
    $setDataCalIn = $tmp_in[1]."/".$tmp_in[2]."/".$tmp_in[0];
    $setDataCalOut = $tmp_out[1]."/".$tmp_out[2]."/".$tmp_out[0];
    */
    $setDataCalIn = $data_in;
    $setDataCalOut = $data_out;
    
    if("01-".date("m-Y")." al ".date("t-m-Y") == $intervallo_data){
        $titolo_intervallo = " del mese in corso";
    }else if(date("d-m-Y", strtotime("-29 days"))." al ".date('d-m-Y') == $intervallo_data) {
        $titolo_intervallo = " utlimi 30 gioni";
    }else if(date("d-m-Y", strtotime("-6 days"))." al ".date('d-m-Y') == $intervallo_data) {
        $titolo_intervallo = " utlimi 7 gioni";
    }else if(date("d-m-Y", strtotime("-1 days"))." al ".date('d-m-Y', strtotime("-1 days")) == $intervallo_data) {
        $titolo_intervallo = " ieri";
    }elseif(date("d-m-Y")." al ".date('d-m-Y') == $intervallo_data) {
        $titolo_intervallo = " oggi";
    }else{
        $titolo_intervallo = " dal  " . $data_in . " al  " . $data_out . "";
    }
    
    if($data_in == $data_out){
        $where_intervallo_tot = " $whereCommerciale $whereCampagna $whereProdotto AND datainsert =  '" . GiraDataOra($data_in) . "' ";
        $where_intervallo = " $whereCommerciale $whereCampagna $whereProdotto AND dataagg =  '" . GiraDataOra($data_in) . "' ";
        $where_intervallo_all = " $whereCommercialeAll $whereCampagnaAll $whereProdottoAll AND lp.data_firma =  '" . GiraDataOra($data_in) . "' ";
        $where_intervallo_negativo_all = " $whereCommercialeAll $whereCampagnaAll $whereProdottoAll AND lp.data_firma =  '" . GiraDataOra($data_in) . "' ";
        $where_intervallo_calenario = " $whereCommercialeCal $whereCampagnaCal $whereProdottoCal AND dataagg =  '" . GiraDataOra($data_in) . "' ";
        $where_intervallo_calendario_all = " $whereCommercialeCalAll $whereCampagnaCalAll $whereProdottoCalAll AND lp.dataagg =  '" . GiraDataOra($data_in) . "' ";
        $where_intervallo_fatture = " $whereCommercialeFatt $whereCampagnaFatt $whereProdottoFatt AND lista_fatture.data_creazione =  '" . GiraDataOra($data_in) . "' ";
        $where_intervallo_fatture_all = " $whereCommercialeFattAll $whereCampagnaFattAll $whereProdottoFattAll AND lf.data_creazione =  '" . GiraDataOra($data_in) . "' ";
    }else{
        $where_intervallo_tot = " $whereCommerciale $whereCampagna $whereProdotto AND datainsert BETWEEN  '" . GiraDataOra($data_in) . "' AND  '" . GiraDataOra($data_out) . "'";
        $where_intervallo = " $whereCommerciale $whereCampagna $whereProdotto AND dataagg BETWEEN  '" . GiraDataOra($data_in) . "' AND  '" . GiraDataOra($data_out) . "'";
        $where_intervallo_all = " $whereCommercialeAll $whereCampagnaAll $whereProdottoAll AND lp.data_firma BETWEEN  '" . GiraDataOra($data_in) . "' AND  '" . GiraDataOra($data_out) . "'";
        $where_intervallo_negativo_all = " $whereCommercialeAll $whereCampagnaAll $whereProdottoAll AND lp.data_firma BETWEEN  '" . GiraDataOra($data_in) . "' AND  '" . GiraDataOra($data_out) . "'";
        $where_intervallo_calenario = " $whereCommercialeCal $whereCampagnaCal $whereProdottoCal AND dataagg BETWEEN  '" . GiraDataOra($data_in) . "' AND  '" . GiraDataOra($data_out) . "'";
        $where_intervallo_calendario_all = " $whereCommercialeCalAll $whereCampagnaCalAll $whereProdottoCalAll AND lp.dataagg BETWEEN  '" . GiraDataOra($data_in) . "' AND  '" . GiraDataOra($data_out) . "'";
        $where_intervallo_fatture = " $whereCommercialeFatt $whereCampagnaFatt $whereProdottoFatt AND lista_fatture.data_creazione BETWEEN  '" . GiraDataOra($data_in) . "' AND  '" . GiraDataOra($data_out) . "'";
        $where_intervallo_fatture_all = " $whereCommercialeFattAll $whereCampagnaFattAll $whereProdottoFattAll AND lf.data_creazione BETWEEN  '" . GiraDataOra($data_in) . "' AND  '" . GiraDataOra($data_out) . "'";
    }
    //echo '<h1>$intervallo_data = '.$intervallo_data.'</h1>';
} else {
    $where_intervallo_tot = " $whereCommerciale $whereCampagna $whereProdotto AND YEAR(datainsert)=YEAR(CURDATE()) AND MONTH(datainsert)=MONTH(CURDATE())";
    $where_intervallo = " $whereCommerciale $whereCampagna $whereProdotto AND YEAR(dataagg)=YEAR(CURDATE()) AND MONTH(dataagg)=MONTH(CURDATE())";
    $where_intervallo_all = " $whereCommercialeAll $whereCampagnaAll $whereProdottoAll AND YEAR(lp.data_firma)=YEAR(CURDATE()) AND MONTH(lp.data_firma)=MONTH(CURDATE())";
    $where_intervallo_negativo_all = " $whereCommercialeAll $whereCampagnaAll $whereProdottoAll AND YEAR(lp.data_firma)=YEAR(CURDATE()) AND MONTH(lp.data_firma)=MONTH(CURDATE())";
    $where_intervallo_calenario = " $whereCommercialeCal $whereCampagnaCal $whereProdottoCal AND YEAR(dataagg)=YEAR(CURDATE()) AND MONTH(dataagg)=MONTH(CURDATE())";
    $where_intervallo_calendario_all = " $whereCommercialeCalAll $whereCampagnaCalAll $whereProdottoAll AND YEAR(lp.dataagg)=YEAR(CURDATE()) AND MONTH(lp.dataagg)=MONTH(CURDATE())";
    $where_intervallo_fatture = "  $whereCommercialeFatt $whereCampagnaFatt $whereProdottoFatt AND YEAR(lista_fatture.data_creazione)=YEAR(CURDATE()) AND MONTH(lista_fatture.data_creazione)=MONTH(CURDATE())";
    $where_intervallo_fatture_all = " $whereCommercialeFattAll $whereCampagnaFattAll $whereProdottoFattAll AND YEAR(lf.data_creazione)=YEAR(CURDATE()) AND MONTH(lf.data_creazione)=MONTH(CURDATE())";
    
    $titolo_intervallo = " del mese in corso";
    $_POST['intervallo_data'] = "01-".date("m-Y")." al ".date("t-m-Y");
    $setDataCalIn = date("d-m-Y");
    $setDataCalOut = date("t-m-Y");
    
    $_POST['id_campagna'] = "";
    $_POST['id_prodotto'] = "";
    $_POST['id_tipo_marketing'] = "";
    $_POST['id_agente'] = "";
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title><?php echo $site_name; ?> |</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css">
        <link href="<?= BASE_URL ?>/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?= BASE_URL ?>/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?= BASE_URL ?>/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= BASE_URL ?>/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?= BASE_URL ?>/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-sidebar-fixed">
        <!-- BEGIN HEADER -->
        <?php include(BASE_ROOT . '/assets/header_risultatiRicerca.php'); ?>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <?php include(BASE_ROOT . '/assets/sidebar.php'); ?>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN THEME PANEL TODO DA CANCELLARE -->

                    <!-- END THEME PANEL -->
                    <!-- BEGIN PAGE BAR -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <form action="?" class="form-horizontal form-bordered" method="POST" id="formIntervallo" name="formIntervallo">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="control-label col-md-1">Intervallo </label>
                                        <div class="col-md-5">
                                            <div class="input-group" id="dataRangeHome" name="dataRangeHome">
                                                <input type="text" class="form-control" id="intervallo_data" name="intervallo_data" value="<?=$_POST['intervallo_data']?>">
                                                <span class="input-group-btn">
                                                    <button class="btn default date-range-toggle" type="submit">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            <center><small>Risultati <?= $titolo_intervallo; ?></small></center>
                                        </div>
                                        <div class="col-md-6">
                                            <?=print_select2("SELECT id AS valore, nome FROM lista_campagne WHERE 1 ORDER BY nome ASC", "id_campagna", $_POST['id_campagna'], "", false, 'tooltips select_campagna-allow-clear', 'data-container="body" data-placement="top" data-original-title="SELEZIONA CAMPAGNA"') ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?=print_select2("SELECT id_prodotto AS valore, nome_prodotto AS nome FROM lista_preventivi_dettaglio WHERE id_prodotto > 0 GROUP BY id_prodotto ORDER BY nome_prodotto ASC", "id_prodotto", $_POST['id_prodotto'], "", false, 'tooltips select_prodotto-allow-clear', 'data-container="body" data-placement="top" data-original-title="SELEZIONA PRODOTTO"') ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?=print_select2("SELECT id as valore, CONCAT(cognome,' ', nome) as nome FROM lista_password WHERE stato='Attivo' AND livello LIKE 'commerciale' ORDER BY cognome, nome ASC", "id_agente", $_POST['id_agente'], "", false, 'tooltips select_commerciale-allow-clear', 'data-container="body" data-placement="top" data-original-title="SELEZIONA COMMERCIALE"') ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <?=print_select2("SELECT id AS valore, nome AS nome FROM lista_tipo_marketing WHERE 1 ORDER BY nome ASC", "id_tipo_marketing", $_POST['id_tipo_marketing'], "", false, 'tooltips select_tipo_marketing-allow-clear', 'data-container="body" data-placement="top" data-original-title="SELEZIONA TIPO MARKETING"') ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row" style="display: none;">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_001 = "SELECT COUNT(*) AS conteggio FROM calendario WHERE (stato LIKE 'Mai Contattato' OR stato LIKE 'Richiamare') $whereCommercialeCal $whereProdottoCal $whereCampagnaCal";
                            $titolo = 'Totale Richiami/Mai Contattati<br>Ancora da Gestire';
                            $icona = 'fa fa-line-chart';
                            $colore = 'yellow-lemon';
                            $link = BASE_URL.'/moduli/calendario/index.php?whrStato=ed59fefc520e30eacbb5fd110761555b&idMenu=36';
                            stampa_dashboard_stat_v2($sql_001, $titolo, $icona, $colore, $link)
                            ?>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_001 = "SELECT COUNT(*) AS conteggio FROM calendario WHERE (stato LIKE 'In Attesa di Controllo') $whereCommercialeCal $whereProdottoCal $whereCampagnaCal ";
                            $titolo = 'Totale In Attesa di Controllo<br>Ancora da Gestire';
                            $icona = 'fa fa-line-chart';
                            $colore = 'yellow-casablanca';
                            $link = BASE_URL.'/moduli/calendario/index.php?whrStato=a7d7ab5bee5f267d23e0ff28a162bafb&idMenu=36';
                            stampa_dashboard_stat_v2($sql_001, $titolo, $icona, $colore, $link)
                            ?>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_004 = "SELECT COUNT(*) AS conteggio FROM calendario WHERE (stato LIKE 'Venduto') $where_intervallo_calenario ";
                            $titolo = 'Totale Iscritti<br>'.$titolo_intervallo;
                            $icona = 'fa fa-line-chart';
                            $colore = 'blue-steel';
                            $link = BASE_URL.'/moduli/calendario/index.php?whrStato=0dcf93d17feb1a4f6efe62d5d2f270b2&idMenu=36';
                            stampa_dashboard_stat_v2($sql_004, $titolo, $icona, $colore, $link)
                            ?>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_004 = "SELECT COUNT(*) AS conteggio FROM calendario WHERE (stato LIKE 'Negativo') $where_intervallo_calenario ";
                            $titolo = 'Totale Negativi<br>'.$titolo_intervallo;
                            $icona = 'fa fa-line-chart';
                            $colore = 'red-flamingo';
                            $link = BASE_URL.'/moduli/calendario/index.php?whrStato=31aa0b940088855f8a9b72946dc495ab&idMenu=36';
                            stampa_dashboard_stat_v2($sql_004, $titolo, $icona, $colore, $link)
                            ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row" style="display: none;">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_002 = "SELECT SUM(imponibile) AS conteggio FROM lista_preventivi WHERE (stato LIKE 'Venduto' OR stato LIKE 'Chiuso') " . $where_intervallo;
                            $titolo = 'Totale Ordini Iscritti<br>' . $titolo_intervallo;
                            $icona = 'fa fa-area-chart';
                            $colore = 'blue';
                            $link = '#';
                            stampa_dashboard_stat_v2($sql_002, $titolo, $icona, $colore, $link)
                            ?>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_003 = "SELECT SUM(imponibile) AS conteggio FROM lista_fatture WHERE (stato LIKE 'In Attesa' OR stato LIKE 'Pagata') " . $where_intervallo_fatture;
                            $titolo = 'Totale Ordini Fatturati<br>' . $titolo_intervallo;
                            $icona = 'fa fa-area-chart';
                            $colore = 'green-jungle';
                            $link = '#';
                            stampa_dashboard_stat_v2($sql_003, $titolo, $icona, $colore, $link)
                            ?>
                        </div>
                        
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_003 = "SELECT SUM(imponibile) AS conteggio FROM lista_preventivi WHERE (stato LIKE 'Negativo') " . $where_intervallo;
                            $titolo = 'Totale Ordini Negativi<br>' . $titolo_intervallo;
                            $icona = 'fa fa-area-chart';
                            $colore = 'red-flamingo';
                            $link = '#';
                            stampa_dashboard_stat_v2($sql_003, $titolo, $icona, $colore, $link)
                            ?>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <?php
                            $sql_003 = "SELECT SUM(imponibile) AS conteggio FROM lista_preventivi WHERE (stato LIKE 'In Attesa') " . $where_intervallo;
                            $titolo = 'Totale Ordini in Trattativa<br>' . $titolo_intervallo;
                            $icona = 'fa fa-area-chart';
                            $colore = 'yellow-lemon';
                            $link = '#';
                            stampa_dashboard_stat_v2($sql_003, $titolo, $icona, $colore, $link)
                            ?>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php
                            
                            $sql_0028 = "CREATE TEMPORARY TABLE stat_marketing_home_2 (SELECT  
                            (SELECT nome FROM lista_tipo_marketing WHERE id = ag.id_tipo_marketing) AS tipo_marketing,
                            SUM((SELECT COUNT(*) AS conteggio FROM calendario AS ca WHERE etichetta='Nuova Richiesta' AND ca.id_campagna=ag.id $where_intervallo_tot $whereProdottoCal $whereCampagnaCal $whereTipoMarketingCal)) AS Tutte_Richieste,
                            SUM((SELECT COUNT(*) AS conteggio FROM calendario AS ca WHERE etichetta='Nuova Richiesta' AND (ca.stato LIKE 'Mai Contattato' OR ca.stato LIKE 'Richiamare') AND ca.id_campagna=ag.id)) AS Richiami,
                            SUM((SELECT COUNT(*) AS conteggio FROM calendario AS ca WHERE etichetta='Nuova Richiesta' AND (ca.stato LIKE 'Mai Contattato' OR ca.stato LIKE 'Richiamare') AND ca.id_campagna=ag.id $where_intervallo_tot)) AS Tel_Richiami,
                            SUM((SELECT COUNT(*) AS conteggio_gestite FROM lista_preventivi AS lp WHERE (lp.stato LIKE 'Negativo') AND lp.id_campagna=ag.id $where_intervallo_negativo_all)) AS Negativo,
                            SUM((SELECT COUNT(*) AS conteggio_venduti FROM lista_preventivi AS lp WHERE (lp.stato LIKE 'Chiuso') AND lp.id_campagna=ag.id $where_intervallo_all)) AS Confermati,
                            SUM((SELECT IF(SUM(lp.imponibile)>0, SUM(lp.imponibile), 0) AS conteggio_preventivi FROM lista_preventivi AS lp WHERE (lp.stato LIKE 'Chiuso') AND lp.id_campagna=ag.id $where_intervallo_all)) AS Ordinato_Lordo,
                            SUM((SELECT IF(SUM(ABS(lf.imponibile))>0, SUM(ABS(lf.imponibile)), 0) AS conteggio_annullate FROM lista_fatture AS lf WHERE (lf.stato LIKE 'Nota di Credito%') AND lf.tipo LIKE 'Nota di Credito%' AND lf.id_campagna=ag.id $where_intervallo_fatture_all)) AS Fatture_Annullate,
                            SUM((SELECT IF(SUM(ABS(lf.imponibile))>0, SUM(ABS(lf.imponibile)), 0) AS conteggio_annullate FROM lista_fatture AS lf WHERE (lf.stato LIKE 'In Attesa' OR lf.stato LIKE 'Pagata%') AND lf.tipo LIKE 'Fattura%' AND lf.id_campagna=ag.id $where_intervallo_fatture_all)) AS Fatturato
                            FROM lista_campagne AS ag WHERE 1 $whereCampagnaId $whereTipoMarketing GROUP BY ag.id_tipo_marketing);";
                            $dblink->query($sql_0028, true);
                            
                            $sql_0029 = "CREATE TEMPORARY TABLE stat_marketing_home_totale_tmp (SELECT tipo_marketing, Richiami, Tutte_Richieste, Tel_Richiami+Negativo+Confermati AS 'Tel_Gestite',"
                                    . " Confermati, Negativo, Ordinato_Lordo, Fatture_Annullate, (Ordinato_Lordo-Fatture_Annullate) AS Ordinato_Netto, "
                                    . " Fatturato AS Fatturato_Lordo, (Fatturato-Fatture_Annullate) AS Fatturato_Netto, IF(Confermati>0,ROUND((Confermati*100)/(Negativo+Confermati), 2),0) AS Realizzato,"
                                    . " IF(Ordinato_Lordo>0, ROUND((Ordinato_Lordo-Fatture_Annullate)/Confermati,2), 0) AS Media_part_su_Ordinato,"
                                    . " (Richiami+Tutte_Richieste+Tel_Richiami+Negativo+Confermati+Negativo) AS elimina_vuote"
                                    . " FROM stat_marketing_home_2);";
                            $dblink->query($sql_0029, true);
                            
                            $sql_0030 = "CREATE TEMPORARY TABLE stat_marketing_home_totale_tot_tmp (SELECT 'TOTALE', SUM(Richiami) AS Richiami, SUM(Tutte_Richieste) AS Tutte_Richieste, SUM(Tel_Richiami+Negativo+Confermati) AS 'Tel_Gestite', SUM(Confermati) AS Confermati,"
                                    . " SUM(Negativo) AS Negativo, SUM(Ordinato_Lordo) AS Ordinato_Lordo, SUM(Fatture_Annullate) AS Fatture_Annullate, SUM((Ordinato_Lordo-Fatture_Annullate)) AS Ordinato_Netto,"
                                    . " SUM(Fatturato) AS Fatturato_Lordo, SUM((Fatturato-Fatture_Annullate)) AS Fatturato_Netto, 0 AS Realizzato,"
                                    . " 0 AS Media_part_su_Fattura FROM stat_marketing_home_2);";
                            $dblink->query($sql_0030, true);
                            
                            $sql_0031 = "CREATE TEMPORARY TABLE stat_marketing_home_totale_tot (SELECT '<b>TOTALE</b>', Richiami, Tutte_Richieste, Tel_Gestite,"
                                    . " Confermati, Negativo, Ordinato_Lordo, Fatture_Annullate, Ordinato_Netto, Fatturato_Lordo, Fatturato_Netto, ROUND((Confermati*100)/(Negativo+Confermati), 2) AS Realizzato,"
                                    . " IF(Ordinato_Lordo>0, ROUND((Ordinato_Lordo-Fatture_Annullate)/Confermati,2), 0) AS Media_part_su_Fattura"
                                    . " FROM stat_marketing_home_totale_tot_tmp);";
                            $dblink->query($sql_0031, true);
                            
                            $sql_0032 = "CREATE TEMPORARY TABLE stat_marketing_home_totale (SELECT tipo_marketing, Richiami, Tutte_Richieste, Tel_Gestite,"
                                    . " Confermati, Negativo, Ordinato_Lordo, Fatture_Annullate, Ordinato_Netto, "
                                    . " Fatturato_Lordo, Fatturato_Netto, Realizzato,"
                                    . " Media_part_su_Ordinato"
                                    . " FROM stat_marketing_home_totale_tmp WHERE elimina_vuote > 0);";
                            $dblink->query($sql_0032, true);
                            
                            $sql_0036 = "CREATE TEMPORARY TABLE stat_marketing_home_no_id_tmp (SELECT 
                            'Nessun Tipo Marketing' AS tipo_marketing,
                            (SELECT COUNT(*) AS conteggio FROM calendario AS ca WHERE etichetta='Nuova Richiesta' AND ca.id_campagna='0' $where_intervallo_tot $whereProdottoCal $whereCampagnaCal $whereTipoMarketingCal) AS Tutte_Richieste,
                            (SELECT COUNT(*) AS conteggio FROM calendario AS ca WHERE etichetta='Nuova Richiesta' AND (ca.stato LIKE 'Mai Contattato' OR ca.stato LIKE 'Richiamare') AND ca.id_campagna='0') AS Richiami,
                            (SELECT COUNT(*) AS conteggio FROM calendario AS ca WHERE etichetta='Nuova Richiesta' AND (ca.stato LIKE 'Mai Contattato' OR ca.stato LIKE 'Richiamare') AND ca.id_campagna='0' $where_intervallo_tot) AS Tel_Richiami,
                            (SELECT COUNT(*) AS conteggio_gestite FROM lista_preventivi AS lp WHERE (lp.stato LIKE 'Negativo') AND lp.id_campagna='0' $where_intervallo_negativo_all) AS Negativo,
                            (SELECT COUNT(*) AS conteggio_venduti FROM lista_preventivi AS lp WHERE (lp.stato LIKE 'Chiuso') AND lp.id_campagna='0' $where_intervallo_all) AS Confermati,
                            (SELECT IF(SUM(lp.imponibile)>0, SUM(lp.imponibile), 0) AS conteggio_preventivi FROM lista_preventivi AS lp WHERE (lp.stato LIKE 'Chiuso') AND lp.id_campagna='0' $where_intervallo_all) AS Ordinato_Lordo,
                            (SELECT IF(SUM(ABS(lf.imponibile))>0, SUM(ABS(lf.imponibile)), 0) AS conteggio_annullate FROM lista_fatture AS lf WHERE (lf.stato LIKE 'Nota di Credito%') AND lf.tipo LIKE 'Nota di Credito%' AND lf.id_campagna='0' $where_intervallo_fatture_all) AS Fatture_Annullate,
                            (SELECT IF(SUM(ABS(lf.imponibile))>0, SUM(ABS(lf.imponibile)), 0) AS conteggio_annullate FROM lista_fatture AS lf WHERE (lf.stato LIKE 'In Attesa' OR lf.stato LIKE 'Pagata%') AND lf.tipo LIKE 'Fattura%' AND lf.id_campagna='0' $where_intervallo_fatture_all) AS Fatturato
                            WHERE 1);";
                            $dblink->query($sql_0036, true);
                            
                            $sql_0037 = "CREATE TEMPORARY TABLE stat_marketing_home_no_id (SELECT tipo_marketing, Richiami, Tutte_Richieste, Tel_Richiami+Negativo+Confermati AS 'Tel_Gestite',"
                                    . " Confermati, Negativo, Ordinato_Lordo, Fatture_Annullate, (Ordinato_Lordo-Fatture_Annullate) AS Ordinato_Netto, "
                                    . " Fatturato AS Fatturato_Lordo, (Fatturato-Fatture_Annullate) AS Fatturato_Netto, IF(Confermati>0,ROUND((Confermati*100)/(Negativo+Confermati), 2),0) AS Realizzato,"
                                    . " IF(Ordinato_Lordo>0, ROUND((Ordinato_Lordo-Fatture_Annullate)/Confermati,2), 0) AS Media_part_su_Ordinato"
                                    . " FROM stat_marketing_home_no_id_tmp);";
                            $dblink->query($sql_0037, true);
                            
                            stampa_table_datatables_responsive("SELECT * FROM stat_marketing_home_no_id UNION SELECT * FROM stat_marketing_home_totale UNION SELECT * FROM stat_marketing_home_totale_tot;", "Statistiche per TIPO MARKETING".$titolo_intervallo, "tabella_base1");
                            
                            ?>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php
                            $sql_0024 = "CREATE TEMPORARY TABLE stat_campagna_home_2 (SELECT 
                            nome as Nome_Campagna,
                            (SELECT nome FROM lista_tipo_marketing WHERE id = ag.id_tipo_marketing) AS tipo_marketing,
                            (SELECT COUNT(*) AS conteggio FROM calendario AS ca WHERE etichetta='Nuova Richiesta' AND ca.id_campagna=ag.id $where_intervallo_tot $whereProdottoCal $whereCampagnaCal $whereTipoMarketingCal) AS Tutte_Richieste,
                            (SELECT COUNT(*) AS conteggio FROM calendario AS ca WHERE etichetta='Nuova Richiesta' AND (ca.stato LIKE 'Mai Contattato' OR ca.stato LIKE 'Richiamare') AND ca.id_campagna=ag.id) AS Richiami,
                            (SELECT COUNT(*) AS conteggio FROM calendario AS ca WHERE etichetta='Nuova Richiesta' AND (ca.stato LIKE 'Mai Contattato' OR ca.stato LIKE 'Richiamare') AND ca.id_campagna=ag.id $where_intervallo_tot) AS Tel_Richiami,
                            (SELECT COUNT(*) AS conteggio_gestite FROM lista_preventivi AS lp WHERE (lp.stato LIKE 'Negativo') AND lp.id_campagna=ag.id $where_intervallo_negativo_all) AS Negativo,
                            (SELECT COUNT(*) AS conteggio_venduti FROM lista_preventivi AS lp WHERE (lp.stato LIKE 'Chiuso') AND lp.id_campagna=ag.id $where_intervallo_all) AS Confermati,
                            (SELECT IF(SUM(lp.imponibile)>0, SUM(lp.imponibile), 0) AS conteggio_preventivi FROM lista_preventivi AS lp WHERE (lp.stato LIKE 'Chiuso') AND lp.id_campagna=ag.id $where_intervallo_all) AS Ordinato_Lordo,
                            (SELECT IF(SUM(ABS(lf.imponibile))>0, SUM(ABS(lf.imponibile)), 0) AS conteggio_annullate FROM lista_fatture AS lf WHERE (lf.stato LIKE 'Nota di Credito%') AND lf.tipo LIKE 'Nota di Credito%' AND lf.id_campagna=ag.id $where_intervallo_fatture_all) AS Fatture_Annullate,
                            (SELECT IF(SUM(ABS(lf.imponibile))>0, SUM(ABS(lf.imponibile)), 0) AS conteggio_annullate FROM lista_fatture AS lf WHERE (lf.stato LIKE 'In Attesa' OR lf.stato LIKE 'Pagata%') AND lf.tipo LIKE 'Fattura%' AND lf.id_campagna=ag.id $where_intervallo_fatture_all) AS Fatturato
                            FROM lista_campagne AS ag WHERE 1 $whereCampagnaId $whereTipoMarketing);";
                            $dblink->query($sql_0024, true);
                            
                            $sql_0025 = "CREATE TEMPORARY TABLE stat_campagna_home_totale_tmp (SELECT Nome_Campagna, tipo_marketing, Richiami, Tutte_Richieste, Tel_Richiami+Negativo+Confermati AS 'Tel_Gestite',"
                                    . " Confermati, Negativo, Ordinato_Lordo, Fatture_Annullate, (Ordinato_Lordo-Fatture_Annullate) AS Ordinato_Netto, "
                                    . " Fatturato AS Fatturato_Lordo, (Fatturato-Fatture_Annullate) AS Fatturato_Netto, IF(Confermati>0,ROUND((Confermati*100)/(Negativo+Confermati), 2),0) AS Realizzato,"
                                    . " IF(Ordinato_Lordo>0, ROUND((Ordinato_Lordo-Fatture_Annullate)/Confermati,2), 0) AS Media_part_su_Ordinato,"
                                    . " (Richiami+Tutte_Richieste+Tel_Richiami+Negativo+Confermati+Negativo) AS elimina_vuote"
                                    . " FROM stat_campagna_home_2);";
                            $dblink->query($sql_0025, true);
                            
                            $sql_0026 = "CREATE TEMPORARY TABLE stat_campagna_home_totale_tot_tmp (SELECT 'TOTALE', 'TUTTI', SUM(Richiami) AS Richiami, SUM(Tutte_Richieste) AS Tutte_Richieste, SUM(Tel_Richiami+Negativo+Confermati) AS 'Tel_Gestite', SUM(Confermati) AS Confermati,"
                                    . " SUM(Negativo) AS Negativo, SUM(Ordinato_Lordo) AS Ordinato_Lordo, SUM(Fatture_Annullate) AS Fatture_Annullate, SUM((Ordinato_Lordo-Fatture_Annullate)) AS Ordinato_Netto,"
                                    . " SUM(Fatturato) AS Fatturato_Lordo, SUM((Fatturato-Fatture_Annullate)) AS Fatturato_Netto, 0 AS Realizzato,"
                                    . " 0 AS Media_part_su_Fattura FROM stat_campagna_home_2);";
                            $dblink->query($sql_0026, true);
                            
                            $sql_0027 = "CREATE TEMPORARY TABLE stat_campagna_home_totale_tot (SELECT '<b>TOTALE</b>', 'TUTTI', Richiami, Tutte_Richieste, Tel_Gestite,"
                                    . " Confermati, Negativo, Ordinato_Lordo, Fatture_Annullate, Ordinato_Netto, Fatturato_Lordo, Fatturato_Netto, ROUND((Confermati*100)/(Negativo+Confermati), 2) AS Realizzato,"
                                    . " IF(Ordinato_Lordo>0, ROUND((Ordinato_Lordo-Fatture_Annullate)/Confermati,2), 0) AS Media_part_su_Fattura"
                                    . " FROM stat_campagna_home_totale_tot_tmp);";
                            $dblink->query($sql_0027, true);
                            
                            $sql_0033 = "CREATE TEMPORARY TABLE stat_campagna_home_totale (SELECT Nome_Campagna, tipo_marketing, Richiami, Tutte_Richieste, Tel_Gestite,"
                                    . " Confermati, Negativo, Ordinato_Lordo, Fatture_Annullate, Ordinato_Netto, "
                                    . " Fatturato_Lordo, Fatturato_Netto, Realizzato,"
                                    . " Media_part_su_Ordinato"
                                    . " FROM stat_campagna_home_totale_tmp WHERE elimina_vuote > 0);";
                            $dblink->query($sql_0033, true);
                            
                            $sql_0034 = "CREATE TEMPORARY TABLE stat_campagna_home_no_id_tmp (SELECT 
                            'Nessuna Campagna' as Nome_Campagna,
                            'Nessun Tipo Marketing' AS tipo_marketing,
                            (SELECT COUNT(*) AS conteggio FROM calendario AS ca WHERE etichetta='Nuova Richiesta' AND ca.id_campagna='0' $where_intervallo_tot $whereProdottoCal $whereCampagnaCal $whereTipoMarketingCal) AS Tutte_Richieste,
                            (SELECT COUNT(*) AS conteggio FROM calendario AS ca WHERE etichetta='Nuova Richiesta' AND (ca.stato LIKE 'Mai Contattato' OR ca.stato LIKE 'Richiamare') AND ca.id_campagna='0') AS Richiami,
                            (SELECT COUNT(*) AS conteggio FROM calendario AS ca WHERE etichetta='Nuova Richiesta' AND (ca.stato LIKE 'Mai Contattato' OR ca.stato LIKE 'Richiamare') AND ca.id_campagna='0' $where_intervallo_tot) AS Tel_Richiami,
                            (SELECT COUNT(*) AS conteggio_gestite FROM lista_preventivi AS lp WHERE (lp.stato LIKE 'Negativo') AND lp.id_campagna='0' $where_intervallo_negativo_all) AS Negativo,
                            (SELECT COUNT(*) AS conteggio_venduti FROM lista_preventivi AS lp WHERE (lp.stato LIKE 'Chiuso') AND lp.id_campagna='0' $where_intervallo_all) AS Confermati,
                            (SELECT IF(SUM(lp.imponibile)>0, SUM(lp.imponibile), 0) AS conteggio_preventivi FROM lista_preventivi AS lp WHERE (lp.stato LIKE 'Chiuso') AND lp.id_campagna='0' $where_intervallo_all) AS Ordinato_Lordo,
                            (SELECT IF(SUM(ABS(lf.imponibile))>0, SUM(ABS(lf.imponibile)), 0) AS conteggio_annullate FROM lista_fatture AS lf WHERE (lf.stato LIKE 'Nota di Credito%') AND lf.tipo LIKE 'Nota di Credito%' AND lf.id_campagna='0' $where_intervallo_fatture_all) AS Fatture_Annullate,
                            (SELECT IF(SUM(ABS(lf.imponibile))>0, SUM(ABS(lf.imponibile)), 0) AS conteggio_annullate FROM lista_fatture AS lf WHERE (lf.stato LIKE 'In Attesa' OR lf.stato LIKE 'Pagata%') AND lf.tipo LIKE 'Fattura%' AND lf.id_campagna='0' $where_intervallo_fatture_all) AS Fatturato
                            WHERE 1);";
                            $dblink->query($sql_0034, true);
                            
                            $sql_0035 = "CREATE TEMPORARY TABLE stat_campagna_home_no_id (SELECT Nome_Campagna, tipo_marketing, Richiami, Tutte_Richieste, Tel_Richiami+Negativo+Confermati AS 'Tel_Gestite',"
                                    . " Confermati, Negativo, Ordinato_Lordo, Fatture_Annullate, (Ordinato_Lordo-Fatture_Annullate) AS Ordinato_Netto, "
                                    . " Fatturato AS Fatturato_Lordo, (Fatturato-Fatture_Annullate) AS Fatturato_Netto, IF(Confermati>0,ROUND((Confermati*100)/(Negativo+Confermati), 2),0) AS Realizzato,"
                                    . " IF(Ordinato_Lordo>0, ROUND((Ordinato_Lordo-Fatture_Annullate)/Confermati,2), 0) AS Media_part_su_Ordinato"
                                    . " FROM stat_campagna_home_no_id_tmp);";
                            $dblink->query($sql_0035, true);
                            
                            stampa_table_datatables_responsive("SELECT * FROM stat_campagna_home_no_id UNION SELECT * FROM stat_campagna_home_totale UNION SELECT * FROM stat_campagna_home_totale_tot;", "Statistiche per CAMPAGNA".$titolo_intervallo, "tabella_base_home");
                           ?>
                        </div>
                    </div>
                    
                </div>
            </div>
            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->
        <!-- BEGIN QUICK SIDEBAR -->
        <!-- END QUICK SIDEBAR -->
    </div>
    <!-- END CONTAINER -->
    <?=pageFooterCopy();?>
    <!--[if lt IE 9]>
    <script src="<?= BASE_URL ?>/assets/global/plugins/respond.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/excanvas.min.js"></script>
    <![endif]-->
    <!-- BEGIN CORE PLUGINS -->
    <script src="<?= BASE_URL ?>/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="<?= BASE_URL ?>/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="//www.google.com/jsapi" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="<?= BASE_URL ?>/assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS
    <script src="<?= BASE_URL ?>/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>-->
    
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataRangeHome').daterangepicker({
                opens: (App.isRTL() ? 'left' : 'right'),
                format: 'DD-MM-YYYY',
                separator: ' al ',
                startDate: '<?=$setDataCalIn?>',
                endDate: '<?=$setDataCalOut?>',
                ranges: {
                    'Oggi': [moment(), moment()],
                    'Ieri': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Ultimi 7 giorni': [moment().subtract(6, 'days'), moment()],
                    'Ultimi 30 giorni': [moment().subtract(29, 'days'), moment()],
                    'Questo mese': [moment().startOf('month'), moment().endOf('month')],
                    'Scorso Mese': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    format: 'DD-MM-YYYY',
                    separator: ' al ',
                    applyLabel: 'Filtra',
                    cancelLabel: 'Resetta',
                    fromLabel: 'Dal',
                    toLabel: 'Al',
                    customRangeLabel: 'Date Personalizzate',
                    daysOfWeek: ['Do', 'Lu', 'Ma', 'Me', 'Gi', 'Ve', 'Sa'],
                    monthNames: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
                    firstDay: 1
                },
                minDate: '07/01/2017',
            },
                function (startDate, endDate) {
                    $('#intervallo_data').val(startDate.format('DD-MM-YYYY') + ' al ' + endDate.format('DD-MM-YYYY'));
                    //$('#defaultrange input').val(startDate.format('YYYY-MM-DD') + '|' + endDate.format('YYYY-MM-DD'));
                    //$('#defaultrange input').html(startDate.format('DD-MM-YYYY') + ' a ' + endDate.format('DD-MM-YYYY'));
                }
            );
            $('#dataRangeHome').on('apply.daterangepicker', function(ev, picker) {
                //console.log(picker.startDate.format('YYYY-MM-DD'));
                //console.log(picker.endDate.format('YYYY-MM-DD'));
                document.formIntervallo.submit();
            }); 
            
            $('#id_agente, #id_prodotto, #id_campagna, #id_tipo_marketing').on('change', function(ev, picker) {
                document.formIntervallo.submit();
            });
            
            $('#intervallo_data').on('change', function(ev, picker) {
                document.formIntervallo.submit();
            });
            
        });
    </script>
    <script src="<?= BASE_URL ?>/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/pages/scripts/ui-toastr.min.js" type="text/javascript"></script>
    <!--<script src="<?= BASE_URL ?>/moduli/preventivi/scripts/funzioni.js" type="text/javascript"></script>-->
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="<?= BASE_URL ?>/assets/apps/scripts/php.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/apps/scripts/utility.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
    <script src="<?= BASE_URL ?>/moduli/campagne/scripts/funzioni.js" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->
</body>
</html>
