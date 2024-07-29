document.addEventListener("DOMContentLoaded", function() {
    const profileIcon = document.getElementById("profileIcon");
    const options = document.getElementById("options");

    profileIcon.addEventListener("click", function() {
        options.style.display = options.style.display === "block" ? "none" : "block";
    });

    document.querySelectorAll('.options a').forEach(option => {
        option.addEventListener('click', () => {
            console.log(`${option.textContent} option clicked`);
        });
    });


});

function showAlert(message, type) {
    const alertPlaceholder = document.getElementById('alert-placeholder');
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
    `;
    alertPlaceholder.appendChild(alertDiv);

    // Automatically remove the alert after 3 seconds
    setTimeout(() => {
        alertDiv.classList.remove('show');
        alertDiv.addEventListener('transitionend', () => {
            alertDiv.remove();
        });
    }, 3000);
}
$(document).ready(function() {
    const datePicker = $('#datePicker');
    const currentMonthYear = $('#currentMonthYear');
    const calendarDays = $('#calendarDays');
    const selectedRangeDisplay = $('#selectedRange');
    const dateRangeDisplay = $('#dateRangeDisplay'); // Selecting the element for date range display
    let startDate = null;
    let endDate = null;
    let selectedDateRangeText = ''; // Variable to store the selected date range text

    // Initialize date picker with current month and year
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    showCalendar(currentMonth, currentYear);

    // Show date picker on icon click
    $('.filter-icon').click(function() {
        datePicker.toggleClass('active');
    });

    // Handle previous month navigation
    $('#prevMonth').click(function() {
        currentYear = currentMonth === 0 ? currentYear - 1 : currentYear;
        currentMonth = currentMonth === 0 ? 11 : currentMonth - 1;
        showCalendar(currentMonth, currentYear);
    });

    // Handle next month navigation
    $('#nextMonth').click(function() {
        currentYear = currentMonth === 11 ? currentYear + 1 : currentYear;
        currentMonth = currentMonth === 11 ? 0 : currentMonth + 1;
        showCalendar(currentMonth, currentYear);
    });

    // Function to display calendar for a specific month and year
    function showCalendar(month, year) {
        let firstDay = new Date(year, month, 1);
        let lastDay = new Date(year, month + 1, 0);
        let daysInMonth = lastDay.getDate();
        let startDay = firstDay.getDay(); // Index of the starting day in the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
        let monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        currentMonthYear.text(monthNames[month] + ' ' + year);

        calendarDays.empty();

        // Create calendar days
        for (let i = 0; i < startDay; i++) {
            calendarDays.append('<div class="calendar-day"></div>');
        }

        for (let day = 1; day <= daysInMonth; day++) {
            let dateObj = new Date(year, month, day);
            let isActive = dateObj.toDateString() === currentDate.toDateString() ? 'active' : '';
            calendarDays.append('<div class="calendar-day ' + isActive + '">' + day + '</div>');
        }

        // Handle click on calendar day
        $('.calendar-day').click(function(event) {
            event.stopPropagation(); // Prevent closing when clicking on calendar days
            let clickedDate = new Date(year, month, parseInt($(this).text()));

            if (!startDate) {
                // Select start date
                startDate = clickedDate;
                $(this).addClass('selected-range');
                selectedRangeDisplay.text(formatDate(startDate) + ' to ');
            } else if (!endDate) {
                // Select end date
                endDate = clickedDate;
                // Ensure startDate is before endDate
                if (startDate > endDate) {
                    let temp = startDate;
                    startDate = endDate;
                    endDate = temp;
                }
                // Mark all days in the range as selected
                $('.calendar-day').each(function() {
                    let currentDate = new Date(year, month, parseInt($(this).text()));
                    if (currentDate >= startDate && currentDate <= endDate) {
                        $(this).addClass('selected-range');
                    }
                });
                // Display selected range
                selectedDateRangeText = formatDate(startDate) + ' to ' + formatDate(endDate); // Update selected date range text
                selectedRangeDisplay.text(selectedDateRangeText);
                // Update date range display
                dateRangeDisplay.text(selectedDateRangeText);
            } else {
                // Reset selection if a third day is clicked
                $('.calendar-day').removeClass('selected-range');
                startDate = clickedDate;
                $(this).addClass('selected-range');
                selectedRangeDisplay.text(formatDate(startDate) + ' to ');
                endDate = null;
                selectedDateRangeText = ''; // Reset selected date range text
                // Update date range display
                dateRangeDisplay.text(selectedDateRangeText);
            }
        });

    }

    // Format date as "dd-mm-yyyy"
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${day}-${month}-${year}`;
    }

    // Handle submit button click
    $('#submitRange').click(function() {
        if (startDate && endDate) {
            // Prepare data to send
            const data = {
                startDate: formatDate(startDate),
                endDate: formatDate(endDate)
            };

            // AJAX request to fetch data
            $.ajax({
                url: 'fetch_data.php',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        console.error('Error fetching data:', response.error);
                        // Handle error case
                    } else {
                        // Update UI with fetched data (assuming you have functions for this)
                        updateUIWithData(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    // Handle error case
                }
            });

            // Reset selection after submission
            $('.calendar-day').removeClass('selected-range');
            startDate = null;
            endDate = null;
            // Update date range display
            dateRangeDisplay.text('');
            datePicker.removeClass('active'); // Close date picker after submission
        } else {
            showAlert('Select a date range', 'danger');
        }
    });

    // Function to update UI with fetched data
    function updateUIWithData(data) {
        // Update charts, cards, or other UI elements here
        $('#carsWashed').text(data.carsWashed);
        $('#grossIncome').text(data.grossIncome !== null ? '$' + parseFloat(data.grossIncome).toFixed(2) : 'N/A');
        $('#netIncome').text(data.netIncome !== null ? '$' + parseFloat(data.netIncome).toFixed(2) : 'N/A');
        $('#numClients').text(data.numClients);

        // Update date range display if needed
        dateRangeDisplay.text(selectedDateRangeText); // Display selected date range text
    }

});
