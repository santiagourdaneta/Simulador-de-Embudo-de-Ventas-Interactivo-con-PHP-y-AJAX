<?php

$errors = [];
$funnelData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'simulate') {
    // Validaciones del lado del servidor
    $visitors = isset($_POST['visitors']) ? filter_var($_POST['visitors'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) : null;
    $leadsPercentage = isset($_POST['leads_percentage']) ? filter_var($_POST['leads_percentage'], FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 100]]) : null;
    $opportunitiesPercentage = isset($_POST['opportunities_percentage']) ? filter_var($_POST['opportunities_percentage'], FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 100]]) : null;
    $conversionRatePercentage = isset($_POST['conversion_rate_percentage']) ? filter_var($_POST['conversion_rate_percentage'], FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0, 'max_range' => 100]]) : null;
    $averageDealValue = isset($_POST['average_deal_value']) ? filter_var($_POST['average_deal_value'], FILTER_VALIDATE_FLOAT, ['options' => ['min_range' => 0]]) : null;

    if ($visitors === null || $visitors === false) {
        $errors['visitors'] = 'Por favor, introduce un número válido de visitantes.';
    }
    if ($leadsPercentage === null || $leadsPercentage === false) {
        $errors['leads_percentage'] = 'Por favor, introduce un porcentaje válido de leads (0-100).';
    }
    if ($opportunitiesPercentage === null || $opportunitiesPercentage === false) {
        $errors['opportunities_percentage'] = 'Por favor, introduce un porcentaje válido de oportunidades (0-100).';
    }
    if ($conversionRatePercentage === null || $conversionRatePercentage === false) {
        $errors['conversion_rate_percentage'] = 'Por favor, introduce una tasa de conversión válida (0-100).';
    }
    if ($averageDealValue === null || $averageDealValue === false) {
        $errors['average_deal_value'] = 'Por favor, introduce un valor promedio de trato válido.';
    }

    if (empty($errors)) {
        $leads = $visitors * ($leadsPercentage / 100);
        $opportunities = $leads * ($opportunitiesPercentage / 100);
        $conversions = $opportunities * ($conversionRatePercentage / 100);
        $revenue = $conversions * $averageDealValue;

        $funnelData = [
            'visitors' => round($visitors),
            'leads' => round($leads),
            'opportunities' => round($opportunities),
            'conversions' => round($conversions),
            'revenue' => round($revenue, 2),
        ];

        // Enviar respuesta como HTML para actualizar la visualización
        echo '<div class="results">';
        echo '<p><strong>Visitantes:</strong> ' . htmlspecialchars(round($visitors)) . '</p>';
        echo '<p><strong>Leads:</strong> ' . htmlspecialchars(round($leads)) . '</p>';
        echo '<p><strong>Oportunidades:</strong> ' . htmlspecialchars(round($opportunities)) . '</p>';
        echo '<p><strong>Conversiones:</strong> ' . htmlspecialchars(round($conversions)) . '</p>';
        echo '<p><strong>Ingresos Estimados:</strong> $' . htmlspecialchars(round($revenue, 2)) . '</p>';
        echo '</div>';
        exit;
    } else {
        // Enviar errores como HTML
        echo '<div class="notification notification-error">';
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador de Embudo de Ventas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css"  crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <div class="container">
        <h1>Simulador de Embudo de Ventas Interactivo</h1>
        <form id="sales-funnel-form">
            <input type="hidden" name="action" value="simulate">
            <fieldset>
                <label for="visitors">Visitantes Mensuales:</label>
                <input type="number" id="visitors" name="visitors" placeholder="Ej: 1000">
                <label for="leads_percentage">Tasa de Conversión a Lead (%):</label>
                <input type="number" id="leads_percentage" name="leads_percentage" placeholder="Ej: 10">
                <label for="opportunities_percentage">Tasa de Conversión a Oportunidad (%):</label>
                <input type="number" id="opportunities_percentage" name="opportunities_percentage" placeholder="Ej: 20">
                <label for="conversion_rate_percentage">Tasa de Cierre (%):</label>
                <input type="number" id="conversion_rate_percentage" name="conversion_rate_percentage" placeholder="Ej: 5">
                <label for="average_deal_value">Valor Promedio del Trato ($):</label>
                <input type="number" id="average_deal_value" name="average_deal_value" placeholder="Ej: 500">
                <button type="button" id="simulate-button">Simular Embudo</button>
            </fieldset>
        </form>
        <div id="simulation-results" class="mt-2">
            </div>
    </div>
</body>
</html>