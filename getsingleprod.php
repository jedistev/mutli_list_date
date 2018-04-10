<div class="col-5">
    <div class="form-group">
        <div class="input-group">
            <span  class="input-group-addon">Search Sku</span>
            <input type="text" id="skuInput" onkeyup="searchSkuFunction()" placeholder="Search for SKU" class="form-control">
        </div>
    </div>
</div>



<div class="row">
    <div class="col-sm-2"></div>
   <div class="col-sm-8">
        <div id="result"></div>
    </div>
    <div class="col-sm-2"></div>
</div>

<div class="table-responsive" id="skuTable">
    <table class="table table bordered">
        <tr>
            <th style="width:20%">Product SKU</th>
            <th style="width:13%" class="OTY-color-bg">Total Order</th>
            <th style="width:13%" class="OTY-color-bg">QTY Order</th>
            <th style="width:13%" class="QTY-color-bg-reim">Total Reimbursment</th>
            <th style="width:13%" class="QTY-color-bg-reim">QTY Reimbursment</th>
            <th style="width:13%" class="QTY-color-bg-refund">Total Refund</th>                            
            <th style="width:13%" class="QTY-color-bg-refund">QTY Refund</th>
        </tr>
 <?php
        $allsettlementreport = $_GET['SettlementID'];
        if ((include_once 'config/config.php') == true) {
            print('<p hidden>Config OK</p>');
        }

//Getting setltlement name sent by Post method
        $sql_dropdownlist = "select 
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
    
    where settlement_id  = '" . $allsettlementreport . "'
    GROUP BY sku 
    HAVING sku IS NOT NULL AND LENGTH(sku) > 0
    ORDER BY sku
    ";

        $alldropdownlist = mysqli_query($conn, $sql_dropdownlist);

        if (!$alldropdownlist) {
            die('Could not retrieve data: ' . mysql_error());
        }

//Displaying fetched records to HTML table 
// Using mysql_fetch_array() to get the next row until end of table rows
        while ($row = mysqli_fetch_array($alldropdownlist)) {

            // Print out the contents of each row into a table



            echo"<tr>";

            echo"<td style='width:13%'>";
            echo $row['sku'];
            echo"</td>";

            echo"<td style='width:13%' class='OTY-color-bg'>";
            echo $row['Amount_total_Order'];
            echo"</td>";

            echo"<td style='width:13%' class='OTY-color-bg'>";
            echo $row['Total_Order_Quantity'];
            echo"</td>";

            echo"<td style='width:13%' class='QTY-color-bg-reim'>";
            echo $row['Amount_total_reimbursement'];
            echo"</td>";

            echo"<td style='width:13%' class='QTY-color-bg-reim'>";
            echo $row['Total_Reimbursement_Quantity'];
            echo"</td>";

            echo"<td style='width:13%' class='QTY-color-bg-refund'>";
            echo $row['Total_refund_cost'];
            echo"</td>";

            echo"<td style='width:13%' class='QTY-color-bg-refund'>";
            echo $row['Total_Refund_Quantity'];
            echo"</td>";



            echo"</tr>";
        }
        ?>

    </table>
    
    
    <script>

</script>
    
  