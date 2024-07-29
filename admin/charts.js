$(document).ready(function() {
    var combinedChart;
    var pieChart;
    var startDate = null;
    var endDate = null;

    function initializeCharts() {
        var ctxCombined = document.getElementById('combinedChart').getContext('2d');
        combinedChart = new Chart(ctxCombined, {
            type: 'bar',
            data: {
                labels: ['Cars Washed', 'Gross Income', 'Net Income', 'Number of Clients'],
                datasets: [{
                    label: 'Data Overview',
                    backgroundColor: ['#4CAF50', '#1976D2', '#FFC107', '#FF5722'],
                    data: [0, 0, 0, 0]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        });

        var ctxPie = document.getElementById('pieChart').getContext('2d');
        pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Cars Washed', 'Gross Income', 'Net Income', 'Number of Clients'],
                datasets: [{
                    label: 'Pie Chart',
                    backgroundColor: ['#4CAF50', '#1976D2', '#FFC107', '#FF5722'],
                    data: [0, 0, 0, 0]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + (typeof tooltipItem.raw === 'number' ? tooltipItem.raw.toFixed(2) : tooltipItem.raw);
                            }
                        }
                    }
                }
            }
        });
    }

    function updatePieChartWithData(data) {
        if (data) {
            // Update dataset values
            pieChart.data.datasets[0].data = [
                data.carsWashed,
                data.grossIncome !== null ? parseFloat(data.grossIncome) : 0,
                data.netIncome !== null ? parseFloat(data.netIncome) : 0,
                data.numClients
            ];
            // Update chart
            pieChart.update();
        }
    }

    function updateChartWithData(data) {
        if (data) {
            // Update dataset values for combined chart
            combinedChart.data.datasets[0].data = [
                data.carsWashed,
                data.grossIncome !== null ? parseFloat(data.grossIncome) : 0,
                data.netIncome !== null ? parseFloat(data.netIncome) : 0,
                data.numClients
            ];
            // Update chart
            combinedChart.update();

            // Update pie chart data
            updatePieChartWithData(data);
        }
    }

    function fetchDataAndUpdateCharts() {
        var requestData = {};
        if (startDate && endDate) {
            requestData.startDate = startDate;
            requestData.endDate = endDate;
        }

        $.ajax({
            url: 'fetch_data.php',
            type: 'POST',
            dataType: 'json',
            data: requestData,
            success: function(data) {
                if (data.error) {
                    console.error(data.error);
                    // Handle error case
                    $('#carsWashed').text('Error: ' + data.error);
                    $('#grossIncome').text('Error: ' + data.error);
                    $('#netIncome').text('Error: ' + data.error);
                    $('#numClients').text('Error: ' + data.error);
                } else {
                    // Update card values
                    $('#carsWashed').text(data.carsWashed);
                    $('#grossIncome').text(data.grossIncome !== null ? '$' + parseFloat(data.grossIncome).toFixed(2) : 'N/A');
                    $('#netIncome').text(data.netIncome !== null ? '$' + parseFloat(data.netIncome).toFixed(2) : 'N/A');
                    $('#numClients').text(data.numClients);

                    // Update charts with new data
                    updateChartWithData(data);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ', error);
                // Handle error case
                $('#carsWashed').text('Error fetching data');
                $('#grossIncome').text('Error fetching data');
                $('#netIncome').text('Error fetching data');
                $('#numClients').text('Error fetching data');
            }
        });
    }

    // Initialize charts when the document is ready
    initializeCharts();

    // Fetch data and update charts on document ready
    fetchDataAndUpdateCharts();

    // Event listener for submit button click
    $('#submitRange').click(function() {
        // Retrieve selected date range from date picker
        startDate = $('#startDate').val();
        endDate = $('#endDate').val();

        // Fetch data and update charts
        fetchDataAndUpdateCharts();
    });
});
