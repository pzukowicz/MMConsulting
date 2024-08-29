<?php

/**
 * Generates financial reports.
 */
class ReportGenerator {

  /**
   * Database connection instance.
   * 
   * @var PDO
   */
  private PDO $db;

  /**
   * Constructor with PDO connection.
   * 
   * @param PDO $db 
   *   DB connection.
   */
  public function __construct(PDO $db) {
      this->db = $db;
  }

  /**
   * Gets list of overpaid invoices.
   * 
   * @return array 
   *   Overpayments data.
   */
  public function getOverpayments() {
      $sql = "SELECT c.company_name, i.id, SUM(p.amount) - i.total_amount AS overpaid
              FROM payments p
              JOIN invoices i ON p.invoice_id = i.id
              JOIN clients c ON i.client_id = c.id
              GROUP BY i.id
              HAVING overpaid > 0";
      $stmt = this->db->query($sql);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Gets list of underpaid invoices.
   * 
   * @return array 
   *   Underpayments data.
   */
  public function getUnderpayments() {
      $sql = "SELECT c.company_name, i.id, i.total_amount - SUM(p.amount) AS underpaid
              FROM payments p
              RIGHT JOIN invoices i ON p.invoice_id = i.id
              JOIN clients c ON i.client_id = c.id
              GROUP BY i.id
              HAVING underpaid > 0";
      $stmt = this->db->query($sql);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Gets invoices past due without payments.
   * 
   * @return array 
   *   Unsettled invoices data.
   */
  public function getUnsettledInvoices() {
      $sql = "SELECT c.company_name, i.id, i.payment_due_date
              FROM invoices i
              LEFT JOIN payments p ON i.id = p.invoice_id
              JOIN clients c ON i.client_id = c.id
              WHERE i.payment_due_date < CURDATE() AND p.id IS NULL";
      $stmt = this->db->query($sql);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
