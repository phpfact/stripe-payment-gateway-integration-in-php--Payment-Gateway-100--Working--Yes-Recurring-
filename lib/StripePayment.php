<?php
namespace Phppot;

use Phppot\DataSource;

class StripePayment
{
    private $ds;

    function __construct()
    {
        require_once __DIR__ . "/../lib/DataSource.php";
        $this->ds = new DataSource();
    }
    public function insertCustomer($customer)
    {
        $insertQuery = "INSERT INTO tbl_customer(customer_id, email) VALUES (?, ?) ";

        $paramValue = array(
            $customer["customer_id"],
            $customer["customer_email"],
        );

        $paramType = "ss";
        $this->ds->insert($insertQuery, $paramType, $paramValue);
    }

    public function insertSubscription($subscription)
    {
        $insertQuery = "INSERT INTO tbl_subscription(subscription_id, customer_id, subscription_plan, subscription_interval, current_period_start, current_period_end, subscription_status, subscription_created_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ";

        $paramValue = array(
            $subscription["id"],
            $subscription["customer_id"],
            $subscription["subscription_plan"],
            $subscription["subscription_interval"],
            $subscription["current_period_start"],
            $subscription["current_period_end"],
            $subscription["subscription_status"],
            $subscription["subscription_created_date"],
        );

        $paramType = "ssssssss";
        $this->ds->insert($insertQuery, $paramType, $paramValue);
    }

    public function insertInvoice($invoice)
    {
        $insertQuery = "INSERT INTO tbl_invoice(invoice_number, subscription_id, invoice_id, customer_id, billing_email, currency, invoice_status, invoice_created_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ";

        $paramValue = array(
            $invoice["invoice_number"],
            $invoice["subscription"],
            $invoice["id"],
            $invoice["customer_id"],
            $invoice["billing_email"],
            $invoice["currency"],
            $invoice["invoice_status"],
            $invoice["invoice_created_date"],
        );

        $paramType = "ssssssss";
        $inserId = $this->ds->insert($insertQuery, $paramType, $paramValue);
        if(!empty($inserId))
        {
            $this->insertInvoiceItem($invoice["invoice_items"], $inserId);
        }
    }

    public function insertInvoiceItem($invoiceItem, $invoiceMasterId)
    {
        $insertQuery = "INSERT INTO tbl_invoice_items(invoice_master_id, description, quantity, amount, currency) VALUES (?, ?, ?, ?, ?) ";

        $paramValue = array(
            $invoiceMasterId,
            $invoiceItem[0]["description"],
            $invoiceItem[0]["quantity"],
            $invoiceItem[0]["amount"],
            $invoiceItem[0]["currency"]
        );

        $paramType = "issss";
        $this->ds->insert($insertQuery, $paramType, $paramValue);
    }

    public function updateInvoice($invoice)
    {
        $query = "UPDATE tbl_invoice SET invoice_finalized_date = ?, invoice_status = ? WHERE invoice_id = ?";

        $paramValue = array(
            $invoice["invoice_finalized_date"],
            $invoice["invoice_status"],
            $invoice["id"]
        );

        $paramType = "sss";
        $this->ds->execute($query, $paramType, $paramValue);
    }

    public function updateInvoiceStatus($invoice)
    {
        $query = "UPDATE tbl_invoice SET invoice_status = ? WHERE invoice_id = ?";

        $paramValue = array(
            $invoice["invoice_status"],
            $invoice["id"]
        );

        $paramType = "ss";
        $this->ds->execute($query, $paramType, $paramValue);
    }

    public function updateSubscription($subscription)
    {
        $query = "UPDATE tbl_subscription SET subscription_status = ? WHERE subscription_id = ?";

        $paramValue = array(
            $subscription["subscription_status"],
            $subscription["id"]
        );

        $paramType = "ss";
        $this->ds->execute($query, $paramType, $paramValue);
    }
}
?>