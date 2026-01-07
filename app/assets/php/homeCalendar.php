<!-- Calendar Body -->
<div class="calendar-wrapper">
    <header>
        <p class="current-date"></p>
        <div class="icons">
            <span id="prev" class="material-symbols-rounded">chevron_left</span>
            <span id="next" class="material-symbols-rounded">chevron_right</span>
        </div>
    </header>
    <div class="calendar">
        <ul class="weeks">
            <li>Lun</li>
            <li>Mar</li>
            <li>Mer</li>
            <li>Jeu</li>
            <li>Ven</li>
            <li>Sam</li>
            <li>Dim</li>
        </ul>
        <ul class="days"></ul>
    </div>
</div>

<script>
    const daysTag = document.querySelector(".days"),
        currentDate = document.querySelector(".current-date"),
        prevNextIcon = document.querySelectorAll(".icons span");

    // Initialize current date, year, and month
    let date = new Date(),
        currYear = date.getFullYear(),
        currMonth = date.getMonth() + 1; // Months are zero-indexed in JavaScript

    // Define months array
    const months = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

    // Function to render the calendar
    const renderCalendar = () => {
        // Get the first day of the month, last date of the month, last day of the month, and last date of the previous month
        let firstDayofMonth = new Date(currYear, currMonth - 1, 1).getDay(), // Adjust month to be zero-indexed
            lastDateofMonth = new Date(currYear, currMonth, 0).getDate(),
            lastDayofMonth = new Date(currYear, currMonth - 1, lastDateofMonth).getDay(), // Adjust month to be zero-indexed
            lastDateofLastMonth = new Date(currYear, currMonth - 1, 0).getDate(),
            liTag = "";

        // Add li elements for the days of the previous month
        for (let i = firstDayofMonth; i > 0; i--) {
            liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
        }

        // Get absence dates for the current month
        const absenceDatesBetweenSet = new Set(<?= $absenceDatesBetweenJSON; ?>);

        // Add li elements for the days of the current month, highlighting the absence dates
        for (let i = 1; i <= lastDateofMonth; i++) {
            let isToday = i === date.getDate() && currMonth === (new Date().getMonth() + 1) && currYear === new Date().getFullYear() ? "active" : "";

            let monthString = currMonth < 10 ? `0${currMonth}` : `${currMonth}`;
            let dayString = i < 10 ? `0${i}` : `${i}`;

            let shouldHighlightBetween = absenceDatesBetweenSet.has(`${currYear}-${monthString}-${dayString}`);

            let highlightClass = shouldHighlightBetween ? "highlight" : "";

            let highlightUrl = shouldHighlightBetween ? `<a href="../../view/absences/list-absences.php" style="text-decoration:none; color:inherit;">${i}</a>` : i;

            let classNames = `${isToday}${highlightClass}`.trim();
            liTag += `<li class="${classNames}">${highlightUrl}</li>`;
        }

        // Add li elements for the days of the next month
        for (let i = lastDayofMonth + 1; i < 7; i++) {
            liTag += `<li class="inactive">${i - lastDayofMonth}</li>`;
        }

        // Set the current month and year in the calendar
        currentDate.innerText = `${months[currMonth - 1]} ${currYear}`; // Adjust month to be zero-indexed

        // Update the HTML content of the days container with the generated li elements
        daysTag.innerHTML = liTag;
    };

    // Call the renderCalendar function to initially render the calendar
    renderCalendar();

    // Add click event listeners to the previous and next icons for navigating between months
    prevNextIcon.forEach(icon => {
        icon.addEventListener("click", () => {
            currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1; // Decrement or increment the current month based on the clicked icon
            if (currMonth < 1) {
                currYear--;
                currMonth = 12;
            } else if (currMonth > 12) {
                currYear++;
                currMonth = 1;
            }
            renderCalendar(); // Render the calendar for the updated month and year
        });
    });

</script>