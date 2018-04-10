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
if (include 'config/config_test.php') {
    print('<p hidden>SQL OK</p>');
}
$output = '';
if (isset($_POST["query"])) {
    $search       = mysqli_real_escape_string($conn, $_POST["query"]);
    $queryskulist = "
    SELECT
        settlement_id,
        sku,
        SUM(IF(transaction_type = 'order'
            AND transaction_type = 'order'
            AND amount_description IN ('Principal' , 'Shipping',
            'FBAPerUnitFulfillmentFee',
            'Commission',
            'ShippingChargeback',
            'FBA transportation fee',
            'FBAPerOrderFulfillmentFe',
            'ShippingHB') AND amount_type IN ('ItemPrice' , 'ItemFees', 'Promotion'), amount, 0)) AS 'Amount_total_Order',
        SUM(IF(amount_type = 'FBA Inventory Reimbursement'
            AND amount_description = 'REVERSAL_REIMBURSEMENT',
        amount,0)) AS 'Amount_total_reimbursement',
        SUM(IF (transaction_type = 'order'AND amount_description = 'Principal' AND amount_type = 'ItemPrice', quantity_purchased, 0)) AS 'Total_Order_Quantity',
        SUM(IF( amount_type = 'FBA Inventory Reimbursement' AND amount_description = 'REVERSAL_REIMBURSEMENT', quantity_purchased, 0)) AS 'Total_Reimbursement_Quantity',
        SUM((IF( transaction_type = 'order'AND amount_description = 'Principal' AND amount_type = 'ItemPrice', quantity_purchased, 0)) + (IF( amount_type = 'FBA Inventory Reimbursement' AND amount_description = 'REVERSAL_REIMBURSEMENT', quantity_purchased, 0))) AS 'Total_Quantity',
        SUM(IF(transaction_type = 'refund'
            AND transaction_type = 'refund'
            AND amount_description IN ('Principal' , 'Shipping',
            'FBAPerUnitFulfillmentFee',
            'Commission',
            'RefundCommission',
            'ShippingChargeback',
            'FBA transportation fee',
            'FBAPerOrderFulfillmentFe',
            'ShippingHB')
            AND amount_type IN ('ItemPrice' , 'ItemFees', 'Promotion'),
        amount,
        0)) AS 'Total_refund_cost',
        SUM((transaction_type = 'Refund'
            AND amount_description = 'Principal'
            AND amount_type = 'ItemPrice')) AS Total_Refund_Quantity
    FROM settlements
    WHERE sku LIKE '%" . $search . "%'
      
    GROUP BY sku 
    HAVING sku IS NOT NULL AND LENGTH(sku) > 0
    ORDER BY sku
    ";
} else {
    $queryskulist = "
    SELECT 
        settlement_id,
        sku,
        SUM(IF(transaction_type = 'order'
            AND transaction_type = 'order'
            AND amount_description IN ('Principal' , 'Shipping',
            'FBAPerUnitFulfillmentFee',
            'Commission',
            'ShippingChargeback',
            'FBA transportation fee',
            'FBAPerOrderFulfillmentFe',
            'ShippingHB') AND amount_type IN ('ItemPrice' , 'ItemFees', 'Promotion'), amount, 0)) AS 'Amount_total_Order',
       SUM(IF(amount_type = 'FBA Inventory Reimbursement'
            AND amount_description = 'REVERSAL_REIMBURSEMENT',
        amount,0)) AS 'Amount_total_reimbursement',       
        SUM(IF (transaction_type = 'order'AND amount_description = 'Principal' AND amount_type = 'ItemPrice', quantity_purchased, 0)) AS 'Total_Order_Quantity',
        SUM(IF( amount_type = 'FBA Inventory Reimbursement' AND amount_description = 'REVERSAL_REIMBURSEMENT', quantity_purchased, 0)) AS 'Total_Reimbursement_Quantity',
        SUM(IF(transaction_type = 'refund'
            AND transaction_type = 'refund'
            AND amount_description IN ('Principal' , 'Shipping',
            'FBAPerUnitFulfillmentFee',
            'Commission',
            'RefundCommission',
            'ShippingChargeback',
            'FBA transportation fee',
            'FBAPerOrderFulfillmentFe',
            'ShippingHB')
            AND amount_type IN ('ItemPrice' , 'ItemFees', 'Promotion'),
        amount,
        0)) AS 'Total_refund_cost',
        SUM((transaction_type = 'Refund'
            AND amount_description = 'Principal'
            AND amount_type = 'ItemPrice')) AS Total_Refund_Quantity
    FROM settlements 
    GROUP BY sku 
    HAVING sku IS NOT NULL AND LENGTH(sku) > 0
    ORDER BY sku  ";
}
$resultskulist = mysqli_query($conn, $queryskulist);
if (mysqli_num_rows($resultskulist) > 0) {
    $output .= '<div class="table-responsive">
                    <table class="table table bordered">
                        <tr>
                            <th style="width:20%">Product SKU</th>
                            <th style="width:13%" class="OTY-color-bg">Total Order</th>
                            <th style="width:13%" class="OTY-color-bg">QTY Order</th>
                            <th style="width:13%" class="QTY-color-bg-reim">Total Reimbursment</th>
                            <th style="width:13%" class="QTY-color-bg-reim">QTY Reimbursment</th>
                            <th style="width:13%" class="QTY-color-bg-refund">Total Refund</th>                            
                            <th style="width:13%" class="QTY-color-bg-refund">QTY Refund</th>
                        </tr>';
    while ($row = mysqli_fetch_array($resultskulist)) {
        $output .= '
            <tr>
                <td style="width:20%">' . $row["sku"] . '</td>
                <td style="width:13%" class="OTY-color-bg">£' . $row["Amount_total_Order"] . '</td>
                <td style="width:13%" class="OTY-color-bg">' . $row["Total_Order_Quantity"] . '</td>
                <td style="width:13%" class="QTY-color-bg-reim">£' . $row["Amount_total_reimbursement"] . '</td>
                <td style="width:13%" class="QTY-color-bg-reim">' . $row["Total_Reimbursement_Quantity"] . '</td>
                <td style="width:13%" class="QTY-color-bg-refund">£' . $row["Total_refund_cost"] . '</td> 
                <td style="width:13%" class="QTY-color-bg-refund">' . $row["Total_Refund_Quantity"] . '</td>
            </tr>
        ';
    }
    echo $output;
} else {
    echo 'Data Not Found';
}
?>