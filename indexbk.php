<?php
/**
 * Multi Select box for Test demo
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Steven Smith <stevenpetersmi@gmail.com>
 * @copyright 2018 Ishka Ltd (nope)
 * @license   https://github.com/ BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */


if ((include 'sql/vat_queries_sql.php') == true) {
    print('<p hidden>SQL OK</p>');
}
//require_once('PtcDebug.php');
//$_GET['debug'] = true;        // turn on the debug
//$_GET['debug_off']=true;    // turn off debug
//PtcDebug::load();


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
        }?>

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
                }?>
                <!--SELECT Caluationon -->
                <!--Each Settlement goes there -->
                <?php 
                if ((include './views/select/select_each.php') == true) {
                    printf('OK');
                }?>
                <!-- Each Settlement End Here-->
                <?php 
                if ((include 'nav/footer.php') == true) {
                    printf('<p hidden>Footer OK</p>');
                }?>

            </div>
        </div>
        <div class="overlay"></div>

        <?php 
        if ((include 'nav/script.php') == true) {
            printf('<p hidden>Script OK</p>');
        }?>
        <script type="text/javascript">
            //dropdown list
            $(document).ready(function () {
                $("#getSettlementbutton").click(function () {
                    var prodname = $('#SKUproduct :selected').text();
                    $.get("getlist/gets_list_sku.php", {
                        listSKUID: prodname
                    }, function (getresult) {
                        $("#presentsku").html(getresult);
                    });
                });
            });

            $(document).ready(function () {
                $("#buttonaddsku").click(function () {
                    $("#SKUproduct > option:selected").each(function () {
                        $(this).remove().appendTo("#SKUproduct2");
                    });
                });

                $("#buttonremovesku").click(function () {
                    $("#SKUproduct2 > option:selected").each(function () {
                        $(this).remove().appendTo("#SKUproduct");
                    });
                });
            });
        </script>
    </body>
</html>