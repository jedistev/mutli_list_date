<?php
/**
 * Parses and verifies the doc comments for files.
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

if ((include_once '../config/config.php') == true) {
    print('<p hidden>Config OK</p>');
}
?>
    <p>This list of SKU table goes here </p>
    <table class='table 
                table-bordered 
                table-striped 
                table-hover 
                table-condensed 
                table-responsive'>
        <thead>
            <tr>
                <td style="width:30%">Product SKU</td>
            </tr>
        </thead>
    </table>
    <?php
    $allsettlementreport = $_GET['select_sku_list'];

 

    if ((include_once '../config/config.php') == true) {
        print('<p hidden>Connect Database OK</p>');
    }
    
    $sql_multiplelist = "
    Select 
        *
    FROM settlements
    Where sku  = '" . $allsettlementreport . "'
    GROUP BY sku
    HAVING sku IS NOT NULL AND LENGTH(sku) > 0
    Order by sku ASC";
    $multiplelist = mysqli_query($conn, $sql_multiplelist);
    if (!$multiplelist) {
        die('Could not retrieve data: ' . mysqli_error());
    }
    while ($row = mysqli_fetch_array($multiplelist)) {

        echo "<table class='table table-bordered 
                            table-striped table-hover table-responsive'>";
        echo "<thead>";
        echo "<tr> ";
        echo "<td style='width:30%'>";
        echo $row['sku'];
        echo "</td></tr>";
        echo "</tr></thead>";
        echo "</table>";
    }
?>