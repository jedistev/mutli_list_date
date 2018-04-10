<?php
/**
 *  '\r\n' Parses and verifies the doc comments for files.
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Steven Smith <stevenpetersmi@gmail.com>
 * @copyright 2018 Nope Ltd Steven (NOPE)
 * @license   https://github.com/ BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
if ((include_once 'config/config.php') == true) {
    print('<p hidden>SQL OK</p>');
}

require_once('PtcDebug.php');
$_GET['debug'] = true;        // turn on the debug
//$_GET['debug_off']=true;    // turn off debug
PtcDebug::load();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        if ((include 'nav/meta.php') == true) {
            print('<p hidden>Meta OK</p>');
        }
        if ((include 'nav/css.php') == true) {
            print('<p hidden>CSS OK</p>');
        }
        ?>

    </head>

    <body id="page-top">
        <div class="wrapper">
            <!-- Page Content Holder -->
            <div id="content">
                <?php
                if ((include 'nav/nav.php') == true) {
                    printf('<p hidden>Nav OK</p>');
                }
                if ((include 'nav/header.php') == true) {
                    printf('<p hidden>header OK</p>');
                }
                ?>
                <!--SELECT Caluationon -->
                <!--Each Settlement goes there -->
                <div class="container">
                    <br>
                    <br>
                    <?php

                    //sku unit model

                    function fill_settlement($conn) {
                        $outputData = '';
                        $sqlDropDownSettlementID = "SELECT  *  FROM `settlements` GROUP BY settlement_id DESC ORDER BY settlement_start_date DESC;";
                        $DropDownSettlementID = mysqli_query($conn, $sqlDropDownSettlementID);
                        while ($row = mysqli_fetch_array($DropDownSettlementID)) {
                            $outputData .= '<option value="' . $row["settlement_id"] . ' ' . $row["settlement_start_date"] . ' ' . $row["settlement_end_date"] . '">' . $row["settlement_id"] . '&nbsp &nbsp' . $row["settlement_start_date"] . '&nbsp-&nbsp' . $row["settlement_end_date"] . '</option>';
                        }
                        return $outputData;
                    }
                    ?>  
                    <div class="container">  
                        <label class="col-sm-3 control-label">UK Settlement ID &amp; Date</label>
                        <div class="col-sm-9">

                            <select id="selproduct">
                                <option value=""> Settlement ID &amp; Date</option>  
                                <?php echo fill_settlement($conn); ?>  
                            </select> 
                            <button class="btn btn-primary left-space-button each-settlement-button-margin-left" id="getSettlementbutton">Settlement details</button>  
                        </div>
                        <br>
                        <div id="presentprod">
                        </div> 
                        <br><br> 
                    </div>  
                </div>
                <!-- Each Settlement End Here-->
                <?php
                if ((include 'nav/footer.php') == true) {
                    printf('<p hidden>Footer OK</p>');
                }
                ?>

            </div>
        </div>
        <div class="overlay"></div>

        <?php
        if ((include 'nav/script.php') == true) {
            printf('<p hidden>Script OK</p>');
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#getSettlementbutton").click(function () {
                    var prodname = $('#selproduct :selected').text();
                    $.get("getsingleprod.php", {SettlementID: prodname}, function (getresult) {
                        $("#presentprod").html(getresult);
                    });
                });
            });
            function searchSkuFunction() {
                // Declare variables 
                var input, filter, table, tr, td, i;
                input = document.getElementById("skuInput");
                filter = input.value.toUpperCase();
                table = document.getElementById("skuTable");
                tr = table.getElementsByTagName("tr");

                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[0];
                    if (td) {
                        if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        </script>
    </body>
</html>