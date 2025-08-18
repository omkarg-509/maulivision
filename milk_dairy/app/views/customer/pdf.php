<?php


        // Start output buffering to prevent headers already sent errors
        if (ob_get_level() == 0) ob_start();

        // Load TCPDF library
        require_once __DIR__ . '/../lib/tcpdf/tcpdf.php';

        // Define TCPDF constants if not already defined
        if (!defined('PDF_CREATOR')) define('PDF_CREATOR', 'TCPDF');
        if (!defined('PDF_FONT_NAME_MAIN')) define('PDF_FONT_NAME_MAIN', 'helvetica');
        if (!defined('PDF_FONT_SIZE_MAIN')) define('PDF_FONT_SIZE_MAIN', 10);
        if (!defined('PDF_FONT_NAME_DATA')) define('PDF_FONT_NAME_DATA', 'helvetica');
        if (!defined('PDF_FONT_SIZE_DATA')) define('PDF_FONT_SIZE_DATA', 8);

        // Fetch customer data
        $customerModel = $this->model('Customer');
        $customers = $customerModel->getAll();

        // Create new PDF document
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Milk Dairy');
        $pdf->SetTitle('Customer List');
        $pdf->SetHeaderData('', 0, 'Customer List', '');

        // Set default header/footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // Set font
        $pdf->SetFont('dejavusans', '', 10);

        // Add a page
        $pdf->AddPage();

        // Build HTML table
        $html = '<h2>Customer List</h2>
        <table border="1" cellpadding="4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bill ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>';
        foreach ($customers as $customer) {
            $html .= '<tr>
                <td>' . htmlspecialchars($customer['id']) . '</td>
                <td>' . htmlspecialchars($customer['bill_id']) . '</td>
                <td>' . htmlspecialchars($customer['name']) . '</td>
                <td>' . htmlspecialchars($customer['mobile']) . '</td>
                <td>' . htmlspecialchars($customer['address']) . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';

        // Output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF to browser
        $pdf->Output('customer_list.pdf', 'I');
        // End output buffering and clean up
        if (ob_get_level() > 0) ob_end_flush();
        exit;
        ?>