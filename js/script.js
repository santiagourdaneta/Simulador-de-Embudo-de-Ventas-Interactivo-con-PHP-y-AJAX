document.addEventListener('DOMContentLoaded', function() {
    const simulateButton = document.getElementById('simulate-button');
    const salesFunnelForm = document.getElementById('sales-funnel-form');
    const simulationResults = document.getElementById('simulation-results');

    simulateButton.addEventListener('click', function(event) {
        event.preventDefault();

        const formData = new FormData(salesFunnelForm);

        fetch(window.location.href, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            simulationResults.innerHTML = data;
        })
        .catch(error => {
            console.error('Error en la simulación:', error);
            simulationResults.innerHTML = '<div class="notification notification-error">Error al simular el embudo.</div>';
        });
    });
});