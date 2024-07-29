<?php include 'dash.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sparkle Wash</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/admin.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="charts.js"></script>
</head>
<body>
    <div id="alert-placeholder"></div>
    <div class="content" id="content">
        <div class="filter-container">
            Filter<br>
            <i class="fas fa-calendar-alt filter-icon"></i>
            <div class="date-picker" id="datePicker">
                <div class="month-navigation">
                    <i class="fas fa-chevron-left" id="prevMonth"></i>
                    <span id="currentMonthYear"></span>
                    <i class="fas fa-chevron-right" id="nextMonth"></i>
                </div>
                <div class="date-range-container">
                    <span id="selectedRange"></span>
                </div>
                <div class="calendar-days" id="calendarDays">
                    <!-- Calendar days will be dynamically generated here -->
                </div>
                <button id="submitRange">Submit</button>
            </div>
        </div>
        <div class="container">
        <!-- Your existing HTML content here -->
        <h2 id="dateRangeDisplay">Companie's Total Records</h2> <!-- Date range display -->
    </div>
               <div class="card-container">
            <div class="card">
                <div class="card-content">
                    <h2 class="card-title">Cars Washed</h2>
                    <p class="card-description" id="carsWashed">Loading...</p>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <h2 class="card-title">Gross Income</h2>
                    <p class="card-description" id="grossIncome">Loading...</p>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <h2 class="card-title">Net Income</h2>
                    <p class="card-description" id="netIncome">Loading...</p>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <h2 class="card-title">No of Clients</h2>
                    <p class="card-description" id="numClients">Loading...</p>
                </div>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="combinedChart"></canvas>
            <canvas id="pieChart"></canvas>
        </div>
    </div>

    <script src="../js/admin.js"></script>
</body>
</html>
