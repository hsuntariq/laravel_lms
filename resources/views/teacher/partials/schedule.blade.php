<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar Card</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
    .calendar-card {
        background: lightgray border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        padding: 20px;
        width: 100%;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #000;
        margin: 20px auto;
        transition: transform 0.3s ease;
    }

    .calendar-card:hover {
        transform: scale(1.02);
    }

    .calendar-header {
        text-align: center;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .calendar-header h2 {
        margin: 0;
        font-size: 1.8em;
        color: #000;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
    }

    .nav-button {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #ccc;
        font-size: 1.2em;
        padding: 5px 10px;
        border-radius: 50%;
        cursor: pointer;
        transition: background 0.3s;
    }

    .nav-button:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }

    .calendar-day-name {
        text-align: center;
        font-weight: 600;
        color: #000;
        padding: 8px 0;
        text-transform: uppercase;
        font-size: 0.9em;
    }

    .calendar-day {
        text-align: center;
        padding: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        clip-path: circle();
    }

    .calendar-day:hover {
        background-color: orangered;
        transform: scale(1.1);
        color: white;
    }

    .current-day {
        background: green;
        color: #fff;
        font-weight: bold;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow: hidden;
    }

    .current-day.animated-gradient {
        background: linear-gradient(270deg, #8338eb, #ff6f61, #f8e1a2, #8338eb);
        background-size: 600% 600%;
        animation: gradientMove 2s linear infinite;
        color: #fff;
    }

    @keyframes gradientMove {
        0% {
            background-position: 0% 50%
        }

        50% {
            background-position: 100% 50%
        }

        100% {
            background-position: 0% 50%
        }
    }

    .selected-day {
        background: #8338eb;
        color: white;
        font-weight: bold;
    }

    .other-month {
        color: #9575cd;
        opacity: 0.7;
    }
    </style>
</head>

<body>
    <div class="card my-4  border-0">

        <div class="calendar-card shadow-lg rounded-3">
            <div class="calendar-header">
                <button class="nav-button  text-white" style="background:#8338eb;" onclick="changeMonth(-1)"><i
                        class="bi bi-arrow-left-short"></i></button>
                <h2 id="month-year">August 2025</h2>
                <button class="nav-button  text-white" style="background:#8338eb;" onclick="changeMonth(1)"><i
                        class="bi bi-arrow-right-short"></i></button>
            </div>
            <div class="calendar-grid" id="calendar-grid">
                <div class="calendar-day-name">Sun</div>
                <div class="calendar-day-name">Mon</div>
                <div class="calendar-day-name">Tue</div>
                <div class="calendar-day-name">Wed</div>
                <div class="calendar-day-name">Thu</div>
                <div class="calendar-day-name">Fri</div>
                <div class="calendar-day-name">Sat</div>
            </div>
        </div>
    </div>

    <script>
    let currentMonth = 7; // August (0-based)
    let currentYear = 2025;
    let highlightDays = [];
    let highlightStart = null;
    let highlightEnd = null;

    function renderCalendar(month, year) {
        const calendarGrid = document.getElementById('calendar-grid');
        const monthYear = document.getElementById('month-year');
        const today = new Date();
        const sysMonth = today.getMonth();
        const sysYear = today.getFullYear();
        const sysDay = today.getDate();

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay();

        monthYear.textContent = new Date(year, month).toLocaleString('default', {
            month: 'long',
            year: 'numeric'
        });

        // Clear existing days
        while (calendarGrid.children.length > 7) {
            calendarGrid.removeChild(calendarGrid.lastChild);
        }

        let dayCount = 1;
        for (let i = 0; i < 6; i++) { // Max 6 weeks
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < startingDay) {
                    const emptyDay = document.createElement('div');
                    emptyDay.classList.add('calendar-day', 'other-month');
                    calendarGrid.appendChild(emptyDay);
                } else if (dayCount <= daysInMonth) {
                    const dayElement = document.createElement('div');
                    dayElement.classList.add('calendar-day');
                    dayElement.textContent = dayCount;

                    // Highlight current day
                    let isCurrentDay = false;
                    if (month === sysMonth && year === sysYear && dayCount === sysDay) {
                        dayElement.classList.add('current-day');
                        isCurrentDay = true;
                    }

                    // Highlight batch days between start and end date
                    let isSelectedDay = false;
                    if (highlightDays.length && highlightStart && highlightEnd) {
                        const thisDate = new Date(year, month, dayCount);
                        if (thisDate >= highlightStart && thisDate <= highlightEnd) {
                            const dayName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                                'Saturday'
                            ][j];
                            if (highlightDays.includes(dayName)) {
                                dayElement.classList.add('selected-day');
                                isSelectedDay = true;
                            }
                        }
                    }

                    // Animated gradient on hover for current day
                    if (isCurrentDay) {
                        dayElement.addEventListener('mouseenter', function() {
                            dayElement.classList.add('animated-gradient');
                        });
                        dayElement.addEventListener('mouseleave', function() {
                            dayElement.classList.remove('animated-gradient');
                        });
                    }

                    // Clickable selected day (only current date)
                    // if (isSelectedDay && isCurrentDay) {
                    dayElement.style.cursor = 'pointer';
                    dayElement.addEventListener('click', function() {
                        let currentDate = this.innerHTML;
                        // Calculate the correct day name using the j index, adjusted by startingDay
                        const adjustedDayIndex = (j - startingDay + 5) % 7; // Normalize to 0-6 range
                        const clickedDayName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday',
                            'Friday', 'Saturday'
                        ][adjustedDayIndex];

                        const clickedDate = new Date(year, month, dayCount); // Use current dayCount
                        const classLink = window._classLinks ? window._classLinks[clickedDayName] : null;
                        const batchStartTime = window._batchStartTime;
                        console.log(clickedDayName, window._classLinks[clickedDayName], batchStartTime);

                        if (!classLink) {
                            alert('No class link available for this day.');
                            return;
                        }
                        if (!batchStartTime) {
                            alert('No class start time set.');
                            return;
                        }

                        // Get today's day name
                        const today = new Date();
                        const todayDayName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
                            'Saturday'
                        ][today.getDay()];

                        // AJAX to backend to check if current time >= batch start time
                        fetch('/dashboard/teacher/get-batch-schedule-days', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        ?.content || ''
                                },
                                body: JSON.stringify({
                                    start_time: batchStartTime,
                                    course_id: window._courseId,
                                    batch_no: window._batchNo
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log(todayDayName); // Log today's day for debugging
                                // Check if clicked day matches today's day and can_start is true
                                if (clickedDayName === todayDayName && data.can_start && currentDate ==
                                    new Date().getDate()) {
                                    window.open(classLink, '_blank');
                                } else {
                                    alert(
                                        'You can only start the class on the current day at or after the scheduled time.'
                                    );
                                }
                            });
                    });

                    calendarGrid.appendChild(dayElement);
                    dayCount++;
                } else {
                    break;
                }
            }
            if (dayCount > daysInMonth) break;
        }
    }

    function changeMonth(delta) {
        currentMonth += delta;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        } else if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentMonth, currentYear);
    }

    // Expose for header.blade.php
    window.updateCalendarDays = function(start, end, daysArr, classLinks, startTime, courseId, batchNo) {
        highlightDays = daysArr || [];
        highlightStart = start ? new Date(start) : null;
        highlightEnd = end ? new Date(end) : null;
        window._classLinks = classLinks || {};
        window._courseId = courseId || null;
        window._batchNo = batchNo || null;
        window._batchStartTime = startTime || null;
        // Set calendar to start month/year if available
        if (highlightStart) {
            currentMonth = highlightStart.getMonth();
            currentYear = highlightStart.getFullYear();
        }

        renderCalendar(currentMonth, currentYear);
        getClassLinkForToday(); // Update today's class link if available
    }

    // Expose function to check time and redirect
    window.getClassLinkForToday = function() {
        // Get today's day name
        const today = new Date();
        const dayName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][today
            .getDay()
        ];
        // Get class link for today
        const classLink = window._classLinks ? window._classLinks[dayName] : null;
        console.log(window._classLinks[dayName]);
        console.log(classLink);
        const batchStartTime = window._batchStartTime;
        console.log(batchStartTime);

        if (!classLink || !batchStartTime) return;
        // AJAX to backend to check if current time >= batch start time
        fetch('/dashboard/teacher/check-class-start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    start_time: batchStartTime
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data)
                if (data.can_start) {
                    window.open(classLink, '_blank');
                } else {
                    alert('You can only start the class at or after the scheduled time.');
                }
            });
    }


    // Initial render
    renderCalendar(currentMonth, currentYear);
    </script>
</body>

</html>
