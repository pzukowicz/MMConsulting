<?php

require_once 'Request.php';

/**
 * Controller for handling contract data.
 */
class ContractController {

  /**
   * Database connection instance.
   * 
   * @var PDO
   */
  private PDO $db;

  /**
   * Background color for HTML output.
   * 
   * @var string
   */
  private string $bgcolor;

  /**
   * Superglobal request.
   * 
   * @var Request
   */
  private Request $request; 

  /**
   * An instance of Request to manage and sanitize superglobal data.
   * 
   * @param PDO $db 
   *   Database connection instance.
   */
  public function __construct(PDO $db) {
      $this->db = $db;
      $this->bgcolor = '#FFFFFF';
      $this->request = new Request();
  }

  /**
   * Display contracts based on action parameter.
   */
  public function showContracts() {
    $action = $this->request->getGet('akcja');  // Używanie metody getGet z klasy Request
    $id = $this->request->getGet('i');
    $sort = $this->request->getGet('sort');

    if ($action == 5) {
        $this->displayContracts($id, $sort);
    }
}

  /**
   * Fetch and display contracts with sorting.
   * 
   * @param int $id 
   *   Contract ID to filter the contracts.
   * @param int $sort 
   *   Sort option for ordering contracts.
   */
  private function displayContracts(int $id, int $sort) {
      $orderBy = $this->determineOrder($sort);
      $sql = "SELECT * FROM contracts WHERE id = :id AND kwota > 10 $orderBy";
      $stmt = $this->db->prepare($sql);
      $stmt->execute(['id' => $id]);
      $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $this->renderContracts($contracts);
  }

  /**
   * Determine SQL order clause from sort param.
   * 
   * @param int $sort 
   *   Numeric value indicating sort method.
   * 
   * @return string 
   *   SQL ORDER BY clause.
   */
  private function determineOrder(int $sort): string {
      switch ($sort) {
          case 1: return "ORDER BY 2, 4 DESC";
          case 2: return "ORDER BY 10";
          default: return "";
      }
  }

  /**
   * Render HTML for displaying contracts.
   * 
   * @param array $contracts 
   *   Array of contract data to be displayed.
   */
  private function renderContracts(array $contracts) {
      echo "<html><body bgcolor='{$this->bgcolor}'><br><table width='95%'>";
      foreach ($contracts as $contract) {
          echo "<tr><td>{$contract['id']}</td><td>{$contract['nazwa_przedsiebiorcy']} ";
          if ($contract['kwota'] > 5) {
              echo "{$contract['kwota']}";
          }
          echo "</td></tr>";
      }
      echo "</table></body></html>";
  }
}
