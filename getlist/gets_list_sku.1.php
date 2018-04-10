
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
function fill_date($conn) {
    $outputData = '';
    $sqlDropDowntransactionID = "SELECT  *  FROM `settlements` GROUP BY settlement_id DESC ORDER BY settlement_start_date DESC;";
    $DropDowntransactionID = mysqli_query($conn, $sqlDropDowntransactionID);
    while ($row = mysqli_fetch_array($DropDowntransactionID)) {
        $outputData .= '<option value="' . $row["settlement_id"] . ' ' . $row["settlement_start_date"] . ' ' . $row["settlement_end_date"] . '">' . $row["settlement_id"] . '&nbsp &nbsp' . $row["settlement_start_date"] . '&nbsp-&nbsp' . $row["settlement_end_date"] . '</option>';
        }
    return $outputData;
}
?>
<p>This list of SKU table goes here </p>
    <div class="col-sm-3">
        <label class="control-label">Select a Date</label>
        <div>
            <div class="input-group">
                <select class="form-control input-sm" id="selproduct">
                    <?php echo fill_date($conn); ?>
                </select>
                <span class="input-group-btn">
                    <button type="button" 
                        class="btn btn-secondary" 
                        data-toggle="dropdown" 
                        aria-haspopup="true" 
                        aria-expanded="false" 
                        id="getSettlementbutton2">
                            <i class="fa fa-filter"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>
    <BR><BR>
    <table class='table table-bordered table-striped table-hover table-condensed table-responsive'>
        <thead>
            <tr>
                <!--                <th style="width:10%"></th> -->
                <td style="width:30%">Product SKU</td>
                <td style="width:15%" class="OTY-color-bg">Order</td>
                <td style="width:15%" class="QTY-color-bg-reim">Reimbursment</td>
                <td style="width:15%" class="QTY-color-bg-refund">Refund</td>
            </tr>
        </thead>
    </table>

    <?php
$allsettlementreport = $_GET['listSKUID'];
//$allsettlementreport = print_r($_GET['listSKUID']);
//$allsettlementreport = file_put_contents('debug.txt', print_r($_GET['listSKUID'], 1));

include ('../sql/europeeachsettlementsql.php');
//Getting setltlement name sent by Post method
$sql_multiplelist = "
Select 
    settlement_id,
    sku, 
    SUM(IF (transaction_type = 'order'AND amount_description = 'Principal' AND amount_type = 'ItemPrice', quantity_purchased, 0)) AS 'Total_Order_Quantity',
    SUM(IF( amount_type = 'FBA Inventory Reimbursement' AND amount_description = 'REVERSAL_REIMBURSEMENT', quantity_purchased, 0)) AS 'Total_Reimbursement_Quantity', 
    SUM((IF( transaction_type = 'order'AND amount_description = 'Principal' AND amount_type = 'ItemPrice', quantity_purchased, 0)) + (IF( amount_type = 'FBA Inventory Reimbursement' AND amount_description = 'REVERSAL_REIMBURSEMENT', quantity_purchased, 0))) AS 'Total_Quantity',
    SUM((transaction_type = 'Refund'
        AND amount_description = 'Principal'
        AND amount_type = 'ItemPrice')) AS Total_Refund_Quantity

FROM settlements
Where sku  = '" . $allsettlementreport . "'
GROUP BY sku
HAVING sku IS NOT NULL AND LENGTH(sku) > 0
Order by sku ASC";
$multiplelist = mysqli_query($conn, $sql_multiplelist);

if (!$multiplelist) {
    die('Could not retrieve data: ' . mysqli_error());
}

//Displaying fetched records to HTML table 
// Using mysql_fetch_array() to get the next row until end of table rows
while ($row = mysqli_fetch_array($multiplelist)) {

    // Print out the contents of each row into a table

    echo "<table class='table table-bordered table-striped table-hover table-condensed table-responsive'>";
    echo "<thead>";
    echo "<tr> ";
    echo "<td style='width:30%'>";
    echo $row['sku'];
    echo "</td><td  style='width:15%'class='table-smaller-text OTY-color-bg'>";
    echo $row['Total_Order_Quantity'];
    echo "</td><td style='width:15%' class='table-smaller-text QTY-color-bg-reim'>";
    echo $row['Total_Reimbursement_Quantity'];

    echo "</td><td  style='width:15%' class='table-smaller-text QTY-color-bg-refund'>";
    echo $row['Total_Refund_Quantity'];
    echo "</td></tr>";
    echo "</tr></thead>";
    echo "</table>";
}
?>