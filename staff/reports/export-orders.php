<?php
ob_start();
session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../../auth");
    exit();
}

require_once '../../includes/connection.php';
$config = require_once '../../includes/info.php';

// Define allowed tables and their queries
$allowedTables = [
    'orders' => [
        'date_column' => 'order_date',
        'query'       => "SELECT full_name, email, customers.phone, customers.address, total_price, status, order_date
                          FROM orders
                          JOIN customers ON customers.id = orders.customer_id
                          WHERE status = ? AND order_date BETWEEN ? AND ?"
    ],
    'prescriptions' => [
        'date_column' => 'date',
        'query'       => "SELECT fullName, email, phone, address, status, date
                          FROM prescriptions
                          WHERE status = ? AND date BETWEEN ? AND ?"
    ]
];

// Retrieve and trim input parameters (supports both POST and GET)
$table      = trim(filter_input(INPUT_POST, 'table') ?? filter_input(INPUT_GET, 'table'));
$status     = trim(filter_input(INPUT_POST, 'status') ?? filter_input(INPUT_GET, 'status'));
$start_date = trim(filter_input(INPUT_POST, 'start_date') ?? filter_input(INPUT_GET, 'start_date'));
$end_date   = trim(filter_input(INPUT_POST, 'end_date') ?? filter_input(INPUT_GET, 'end_date'));
$format     = trim(filter_input(INPUT_POST, 'format') ?? filter_input(INPUT_GET, 'format'));

// Validate table selection
if (!array_key_exists($table, $allowedTables)) {
    die("Invalid table selected.");
}

// Validate dates (expects YYYY-MM-DD)
$dateStartObj = DateTime::createFromFormat('Y-m-d', $start_date);
$dateEndObj   = DateTime::createFromFormat('Y-m-d', $end_date);
if (!$dateStartObj || !$dateEndObj || $dateStartObj->format('Y-m-d') !== $start_date || $dateEndObj->format('Y-m-d') !== $end_date) {
    die("Invalid date format. Please use YYYY-MM-DD.");
}

$date_column = $allowedTables[$table]['date_column'];
$query       = $allowedTables[$table]['query'];

// Prepare and execute the query using prepared statements
$stmt = $dsn->prepare($query);
if (!$stmt) {
    die("Error preparing statement: " . $dsn->error);
}
$stmt->bind_param("sss", $status, $start_date, $end_date);
if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($data)) {
    die("No records found for the specified criteria.");
}

// --- Excel Export Branch ---
if (strtolower($format) === 'excel') {
    require_once '../../../vendor/autoload.php';
    
    // Use fully qualified names directly instead of inline "use" statements.
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // --- Add Company Logo if available ---
    $logoPath = $_SERVER['DOCUMENT_ROOT'] . "/cosmeticsstore/images/logo.png"; // Adjust path as needed
    if (file_exists($logoPath)) {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath($logoPath);
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
    }

    // --- Header Section (Rows 1-2) ---
    // Row 1: Report Title (merged cells B1:F1)
    $reportTitle = $config['companyName'] . ' ' . (($table === 'orders') ? "Order" : "Prescriptions Order") . " Report";
    $sheet->mergeCells('B1:F1');
    $sheet->setCellValue('B1', $reportTitle);
    $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Row 2: Status (left) and Period (right)
    $statusText = "Status: " . ucwords($status);
    $periodText = "Period: From " . $start_date . " to " . $end_date;
    $sheet->mergeCells('B2:D2');
    $sheet->setCellValue('B2', $statusText);
    $sheet->getStyle('B2')->getFont()->setBold(true);
    $sheet->mergeCells('E2:F2');
    $sheet->setCellValue('E2', $periodText);
    $sheet->getStyle('E2')->getFont()->setBold(true);
    $sheet->getStyle('E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

    // Apply borders to header section (Rows 1-2)
    $headerStyle = [
        'borders' => [
            'bottom' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                'color' => ['argb' => 'FF0D6EFD'] // Bootstrap primary blue
            ]
        ]
    ];
    $sheet->getStyle('A1:F2')->applyFromArray($headerStyle);

    // --- Table Header (Row 4) ---
    $tableHeaders = ($table === 'orders') ?
        ['#', 'Client Name', 'Email', 'Phone', 'Address', 'Total Amount', 'Date'] :
        ['#', 'Client Name', 'Email', 'Phone', 'Address', 'Date'];
    $startRow = 4;
    $col = 'A';
    foreach ($tableHeaders as $header) {
        $sheet->setCellValue($col . $startRow, $header);
        $col++;
    }
    // Apply border and bold style to table header row
    $tableHeaderStyle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => 'FF000000']
            ]
        ],
        'font' => [
            'bold' => true
        ]
    ];
    $endCol = ($table === 'orders') ? 'G' : 'F';
    $sheet->getStyle("A$startRow:$endCol$startRow")->applyFromArray($tableHeaderStyle);

    // --- Data Rows (starting from Row 5) ---
    $currentRow = $startRow + 1;
    $counter = 1;
    foreach ($data as $row) {
        $col = 'A';
        if ($table === 'orders') {
            $formattedDate = date("Y-m-d", strtotime($row[$date_column]));
            $rowData = [
                $counter,
                $row['full_name'],
                $row['email'],
                $row['phone'],
                $row['address'],
                $row['total_price'],
                $formattedDate
            ];
        } else {
            $formattedDate = date("Y-m-d", strtotime($row['date']));
            $rowData = [
                $counter,
                $row['fullName'],
                $row['email'],
                $row['phone'],
                $row['address'],
                $formattedDate
            ];
        }
        foreach ($rowData as $cell) {
            $sheet->setCellValue($col . $currentRow, $cell);
            $col++;
        }
        // Apply border to the data row
        $sheet->getStyle("A$currentRow:" . ($table === 'orders' ? 'G' : 'F') . "$currentRow")
              ->applyFromArray([
                  'borders' => [
                      'allBorders' => [
                          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                          'color' => ['argb' => 'FF000000']
                      ]
                  ]
              ]);
        $currentRow++;
        $counter++;
    }

    // Auto-size columns based on the table type
    $lastColumn = ($table === 'orders') ? 'G' : 'F';
    foreach (range('A', $lastColumn) as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    ob_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . ucwords($status) . ' ' . ucwords($table) . ' Report ' . date('Y-m-d') . '.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo ucfirst($table); ?> Report Preview</title>
  <link rel="icon" href="/cosmeticsstore/images/logo.png" type="image/x-icon">
  <!-- Bootstrap CSS -->
  <link href="/cosmeticsstore/assets/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons CSS -->
  <link rel="stylesheet" href="/cosmeticsstore/assets/bootstrap-icons/bootstrap-icons.css">
  <!-- html2pdf.js for PDF generation -->
  <script async src="/cosmeticsstore/assets/js/html2pdf.bundle.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    /* Report header */
    .report-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
      padding-bottom: 10px;
      border-bottom: 3px solid #0d6efd;
    }
    .report-header .left {
      display: flex;
      align-items: center;
    }
    .report-header .left img {
      height: 50px;
      margin-right: 20px;
    }
    /* Status section */
    .report-status {
      margin-top: 10px;
      margin-bottom: 10px;
      padding-bottom: 10px;
      border-bottom: 3px solid #198754;
    }
    /* Table hover color */
    /* Custom hover color */
    
    .table-hover tbody tr:hover td {
      background-color:rgb(147, 195, 244) !important; /* Light blue */
      color: #fff;
    }
    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #1931ff !important;
      padding: 12px 3px !important;
      text-align: left;
    }
    th {
      background-color: #1031ff !important;
      color: #ffffff !important;
    }
    /* Repeat thead on each page */
    thead {
      display: table-header-group;
    }
    table tr {
      page-break-inside: avoid !important;
    }
    /* Ensure tfoot is not repeated on every printed page */
    tfoot {
      display: table-row-group !important;
    }
    /* Hide .no-print elements when printing */
    @media print {
      .no-print { display: none !important; }
    }
    @page {
      margin: 0.5in 0.5in !important;
    }
  </style>
</head>
<body>
  <!-- Main container -->
  <div class="container" id="reportContent">
    <!-- Report Header (only on first page visually) -->
    <div class="report-header">
      <div class="left">
        <img src="/cosmeticsstore/images/logo.png" alt="Logo">
        <h2>
          <?php echo $config['companyName']; ?> 
          <?php echo ($table === 'orders') ? 'Order' : 'Prescriptions Order'; ?> Report
        </h2>
      </div>
    </div>

    <!-- Report Status -->
    <div class="report-status">
      <div class="d-flex justify-content-between align-items-center">
        <div class="text-start">
          <h4 class="text-success">
            Status: <?php echo ucwords($status); ?>
          </h4>
        </div>
        <div class="text-end">
          <h5 class="text-primary">
            Period: From <?php echo $start_date; ?> to <?php echo $end_date; ?>
          </h5>
          <!-- Generated date/time (first page only) -->
          <small class="text-muted">
            Generated on: <?php echo gmdate("Y-m-d g:i:s A", time() + 2 * 3600) . ' CAT'; ?>
          </small>
        </div>
      </div>
    </div>

    <!-- Table with repeated thead and a footer that will appear only at the end -->
    <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <?php if ($table === 'orders') { ?>
            <th>#</th>
            <th>Client Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Date</th>
          <?php } else { ?>
            <th>#</th>
            <th>Client Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Status</th>
            <th>Date</th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php
          $counter = 1;
          // For demonstration, repeating the data multiple times
          
            foreach ($data as $row) {
              echo '<tr>';
              if ($table === 'orders') {
                $formattedDate = date("Y-m-d", strtotime($row[$date_column]));
                echo '<td>' . $counter . '</td>';
                echo '<td>' . htmlspecialchars($row['full_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                echo '<td>' . htmlspecialchars($row['address']) . '</td>';
                echo '<td>' . htmlspecialchars($row['total_price']) . '</td>';
                echo '<td>' . ucwords(htmlspecialchars($row['status'])) . '</td>';
                echo '<td>' . htmlspecialchars($formattedDate) . '</td>';
              } else {
                $formattedDate = date("Y-m-d", strtotime($row['date']));
                echo '<td>' . $counter . '</td>';
                echo '<td>' . htmlspecialchars($row['fullName']) . '</td>';
                ?>
                <td> <?php if($row['email'] === ""){
                  echo "No Email";
                  
                } else{
                  echo htmlspecialchars($row['email']);
                }
                echo '</td>';
                echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                echo '<td>' . htmlspecialchars($row['address']) . '</td>';
                echo '<td>' . ucwords(htmlspecialchars($row['status'])) . '</td>';
                echo '<td>' . htmlspecialchars($formattedDate) . '</td>';
              }
              echo '</tr>';
              $counter++;
            }
          
        ?>
      </tbody>
      <tfoot>
        <?php if ($table === 'orders') { ?>
          <tr>
            <th colspan="8" class="text-center">End of orders report</th>
          </tr>
        <?php } else { ?>
          <tr>
            <th colspan="7" class="text-center">End of prescription orders report</th>
          </tr>
        <?php } ?>
      </tfoot>
    </table>
  </div>

  <!-- Action buttons (excluded from PDF/print) -->
  <div class="container mt-3 no-print">
    <div id="pagination" class="mt-3 mb-3"></div>
    <button class="btn btn-secondary" onclick="printReport()">
      <i class="bi bi-printer me-1"></i> Print Report
    </button>
    <button class="btn btn-primary" id="downloadPDF">
      <i class="bi bi-download me-1"></i> Download PDF
    </button>
    <a class="btn btn-dark" href="./">
      <i class="bi bi-arrow-left me-1"></i> Go Back
    </a>
  </div>

  <script>
  function printReport() {
    // Remove any inline style that might be hiding rows (e.g. due to pagination)
    var rows = document.querySelectorAll("table tbody tr");
    rows.forEach(function(row) {
      row.style.display = ""; // Show all rows for printing
    });
    
    // Trigger the browser's native print dialog (Ctrl+P behavior)
    window.print().save();
  }
</script>


  <script>
    document.getElementById("downloadPDF").addEventListener("click", function() {
      var element = document.getElementById("reportContent");
      
      // Clone the element to avoid modifying the paginated table
      var clone = element.cloneNode(true);
      
      // Make all rows visible in the cloned table for PDF export
      var rows = clone.querySelectorAll("table tbody tr");
      rows.forEach(function(row) {
        row.style.display = "";
      });
      
      // Inject CSS to prevent table row splitting & repeat headers
      var extraStyle = document.createElement('style');
      extraStyle.innerHTML = `
        table, th, td {
          border: 0.5px solid #1931ff !important;
          border-collapse: collapse !important;
        }
        thead { display: table-header-group !important; }
        tfoot { display: table-row-group !important; }
        tr { page-break-inside: avoid !important; }
      `;
      clone.prepend(extraStyle);
      
      var opt = {
        margin: 0.5,
        filename: '<?php echo ucwords($status) . " " . ucwords($table) . " Report " . date("Y-m-d") . ".pdf"; ?>',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'A4', orientation: 'landscape' },
        pagebreak: { mode: ['css', 'legacy'] }
      };
      
      html2pdf().set(opt).from(clone).toPdf().get('pdf').then(function (pdf) {
        var totalPages = pdf.internal.getNumberOfPages();
        var pageWidth  = pdf.internal.pageSize.getWidth();
        var pageHeight = pdf.internal.pageSize.getHeight();
        
        for (var i = 1; i <= totalPages; i++) {
          pdf.setPage(i);
          pdf.setFontSize(12);
          
          pdf.setLineWidth(0.025);
          pdf.setDrawColor(0, 50, 250);
          pdf.line(0.5, pageHeight - 0.45, pageWidth - 0.5, pageHeight - 0.45);
          // Setting report source color
          pdf.setTextColor(0, 50, 202);
          pdf.text(
            "Report Source: http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>",
            0.5,
            pageHeight - 0.2
          );
          
          // Set color for page numbers
          pdf.setTextColor(0, 0, 0);
          pdf.text(
            'Page ' + i + ' of ' + totalPages,
            pageWidth - 0.5,
            pageHeight - 0.2,
            { align: 'right' }
          );
        }
      }).save();
    });
  </script>

  <!-- Pagination -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const rowsPerPage = 10; // Adjust as needed
      const tbody = document.querySelector("#reportContent table tbody");
      if (!tbody) return;
      
      const rows = Array.from(tbody.querySelectorAll("tr"));
      const totalRows = rows.length;
      const totalPages = Math.ceil(totalRows / rowsPerPage);
      let currentPage = 1;
      
      function showPage(page) {
        rows.forEach((row, index) => {
          row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? "" : "none";
        });
      }
      
      function renderPagination() {
        const paginationDiv = document.getElementById("pagination");
        paginationDiv.innerHTML = "";
        
        const prevBtn = document.createElement("button");
        prevBtn.textContent = "Previous";
        prevBtn.classList.add("btn", "btn-outline-success", "me-2", "px-5");
        prevBtn.disabled = (currentPage === 1);
        prevBtn.addEventListener("click", function() {
          if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
            renderPagination();
          }
        });
        paginationDiv.appendChild(prevBtn);
        
        for (let i = 1; i <= totalPages; i++) {
          const pageBtn = document.createElement("button");
          pageBtn.textContent = i;
          pageBtn.classList.add("btn", "btn-outline-primary", "me-2");
          if (i === currentPage) {
            pageBtn.classList.remove("btn-outline-primary");
            pageBtn.classList.add("btn-primary");
            pageBtn.disabled = true;
          }
          pageBtn.addEventListener("click", function() {
            currentPage = i;
            showPage(currentPage);
            renderPagination();
          });
          paginationDiv.appendChild(pageBtn);
        }
        
        const nextBtn = document.createElement("button");
        nextBtn.textContent = "Next";
        nextBtn.classList.add("btn", "btn-outline-success", "me-2", "px-5");
        nextBtn.disabled = (currentPage === totalPages);
        nextBtn.addEventListener("click", function() {
          if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
            renderPagination();
          }
        });
        paginationDiv.appendChild(nextBtn);
      }
      
      showPage(currentPage);
      renderPagination();
    });
  </script>

  <!-- Bootstrap JS Bundle -->
   <!-- Bootstrap5 Js -->
  <script src="/cosmeticsstore/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
