<?php

declare(strict_types=1);

require_once 'Request.php';

use Psr\Http\Message\ResponseInterface;

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
  private const BGCOLOR = '#FFFFFF';

  /**
   * Action type for displaying contracts.
   *
   * @var int
   */
  private const ACTION_DISPLAY_CONTRACTS = 5;

  /**
   * Superglobal request.
   *
   * @var Request
   */
  private Request $request;

  private ResponseInterface $response;

  /**
   * An instance of Request to manage and sanitize superglobal data.
   *
   * @param PDO $db
   *   Database connection instance.
   */
  public function __construct(PDO $db, ResponseInterface $response) {
    $this->db = $db;
    $this->request = new Request();
    $this->response = $response;
  }

  /**
   * Display contracts based on action parameter.
   *
   * @return void
   */
  public function showContracts(): void {
    $action = $this->request->getGet('akcja');
    $id = $this->request->getGet('id');
    $sort = $this->request->getGet('sort');

    if ($action === ACTION_DISPLAY_CONTRACTS) {
      $this->displayContracts((int)$id, (int)$sort);
    }
  }

  /**
   * Fetch and display contracts with sorting.
   *
   * @param int $id
   *   Contract ID to filter the contracts.
   * @param int $sort
   *   Sort option for ordering contracts.
   *
   * @return void
   */
  private function displayContracts(int $id, int $sort): void {
    $orderBy = $this->determineOrder($sort);
    $sql = "SELECT * FROM contracts WHERE id = :id AND kwota > 10 {$orderBy}";
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
    return match ($sort) {
      1 => "ORDER BY column_name_2, column_name_4 DESC",
      2 => "ORDER BY column_name_10",
      default => ""
    };
  }

  /**
   * Renders contracts as HTML.
   *
   * @param array $contracts 
   *   Array of contract data to be displayed.
   * 
   * @return ResponseInterface
   *   Response with rendered HTML.
   */
  private function renderContracts(array $contracts): ResponseInterface {
    $html = "<html><body bgcolor='" . self::BGCOLOR . "'><br><table width='95%'>";
    foreach ($contracts as $contract) {
        $id = htmlspecialchars($contract['id']);
        $businessName = htmlspecialchars($contract['nazwa_przedsiebiorcy']);
        $amount = htmlspecialchars($contract['kwota']);
        $html .= "<tr><td>{$id}</td><td>{$businessName} ";
        if ($contract['kwota'] > 5) {
            $html .= "{$amount}";
        }
        $html .= "</td></tr>";
    }
    $html .= "</table></body></html>";

    $this->response->getBody()->write($html);
    return $this->response;
}
  
}
