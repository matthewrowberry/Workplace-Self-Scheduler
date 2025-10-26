tab = document.getElementById("table");

let seconds = 2;


//constants needed: days array - array with days of week in order you want, 
// dayStart - index of day you want calendar to start, 
// dayEnd - index of day you want calendar to end, 
// timeStart - hour of day you want time slots to start,
//  timeEnd - hour of day you want time slots to end, 
// stations - number of stations available per time slot


let row = document.createElement("tr");
let cell = document.createElement("td");
row.id = "headerRow";



tab.appendChild(row)


const headerRow = document.getElementById("headerRow");
cell.id = "corner"
cell.innerHTML = "Week of " + dateString(sunday);
headerRow.appendChild(cell);

//Create header cells for each day and station
for (let i = dayStart; i <= dayEnd; i++) {
    for (let p = 0; p < stations; p++) {
        let cell = document.createElement("td");
        cell.id = days[i] + p;
        cell.innerHTML = days[i];
        headerRow.appendChild(cell);
    }
}
//Create rows for each half hour time slot
for (let i = timeStart; i <= timeEnd; i += interval) {
    let row = document.createElement("tr");
    let cell = document.createElement("td");
    row.id = "row" + i;

    tab.appendChild(row);
    const timeRow = document.getElementById("row" + i);

    //Create time cell with accurate time labels
    cell.id = "time" + i;
    if (i > 12.5) {
        if (i % 1 == 0) {
            cell.innerHTML = i - 12 + ":00 PM";
        }
        else {
            cell.innerHTML = i - 12 - .5 + ":30 PM";
        }
    }
    else if (i == 12 || i == 12.5) {
        if (i % 1 == 0) {
            cell.innerHTML = i + ":00 PM";
        }
        else {
            cell.innerHTML = i - .5 + ":30 PM";
        }
    }
    else {
        if (i % 1 == 0) {
            cell.innerHTML = i + ":00 AM";
        }
        else {
            cell.innerHTML = i - .5 + ":30 AM";
        }
    }

    timeRow.appendChild(cell);

    //Create time slots for each day and station
    for (let j = dayStart; j <= dayEnd; j++) {
        for (let p = 0; p < stations; p++) {
            let cell = document.createElement("td");
            cell.className = "timeSlot";
            cell.id = j + "-" + i + "-" + p;
            cell.addEventListener("click", addName);

            timeRow.appendChild(cell);
        }
    }




}




function addName() {
    let id = this.id;
    let position = parseInt(id.slice(id.lastIndexOf("-")));
    let ok = true;

    //check if already reserved for same day
    for (let i = 0; i < stations; i++) {
        if (position != i) {
            let cell = document.getElementById(id.slice(0, id.lastIndexOf("-")) + "-" + i);
            if (cell.innerHTML == username) {
                ok = false;
            }
        }
    }

    if (this.innerHTML == "" && ok) {
        this.innerHTML = username;
        this.classList.add('user');
        removedReservations = removedReservations.filter(item => item !== this.id);
        newReservations.push(this.id);
    }
    else if (this.innerHTML == "") {
        alert("You have already signed up for this time slot.");
    }
    else if (this.innerHTML == username) {
        newReservations = newReservations.filter(item => item !== this.id);
        this.classList.remove('user');
        removedReservations.push(this.id);
        this.innerHTML = "";
    }

}

function changeWeek(direction) {
    let confirmChange = true;
    if (newReservations.length > 0) {
        confirmChange = confirm("You have unsaved newReservations. Are you sure you want to change weeks and lose those newReservations?");
    }
    if (!confirmChange) {
        return;
    }
    clearCalendar();

    let newDate = new Date(date);

    newDate.setDate(newDate.getDate() + (direction * 7));

    sunday = new Date(newDate);
    sunday.setDate(newDate.getDate() - newDate.getDay());

    date = newDate;

    let headerCell = document.getElementById("corner");
    console.log(date.getDay());
    headerCell.innerHTML = "Week of " + dateString(sunday);
    getReservations();
}

function dateString(date) {
    return (date.getMonth() + 1) + "/" + date.getDate() + "/" + date.getFullYear();
}

function sqlDateString(date) {
    return (date.getFullYear() + "-" + String(date.getMonth() + 1).padStart(2, '0') + "-" + String(date.getDate()).padStart(2, '0'));
}

function formatTime(hour) {
    if (hour % 1 == 0) {
        return String(hour).padStart(2, '0') + ":00:00";
    }
    else {
        console.log(hour);
        console.log(hour - .5);
        return String(hour - .5).padStart(2, '0') + ":30:00";

    }
}

function getElementsByInnerHTML(tagName, value) {
    return Array.from(document.getElementsByTagName(tagName)).filter(element => element.innerHTML === value);
}

async function sendReservations() {
    let cells = getElementsByInnerHTML('td', username);
    //day-time-position
    data = "[";
    for (let i = 0; i < newReservations.length; i++) {
        let cellId = newReservations[i];
        data += "{\"id\": " + id + ", \"name\": \"" + username + "\",";

        let day = new Date(sunday);
        console.log("0" + day.toDateString());
        console.log(".5" + parseInt(cellId.slice(0, cellId.indexOf("-"))));
        day.setDate(sunday.getDate() + parseInt(cellId.slice(0, cellId.indexOf("-"))));
        console.log("1" + day.toDateString());
        data += "\"date\": \"" + sqlDateString(day) + "\",";
        let time = cellId.slice(cellId.indexOf("-") + 1, cellId.lastIndexOf("-"));
        console.log(time);
        data += "\"start\": \"" + formatTime(parseFloat(time)) + "\",";
        console.log(time);
        time = parseFloat(time) + 0.5;
        console.log(time);
        data += "\"end\": \"" + formatTime(parseFloat(time)) + "\",";
        data += "\"station\": " + cellId.slice(cellId.lastIndexOf("-") + 1) + "}";

        if (i < newReservations.length - 1) {
            data += ",";
        }


    }

    data += "]"




    fetch(url + "addReservation/index.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: data
    }).then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        console.log(response);
        return response.json();

    }).then(data => {
        console.log('Success:', data);
        newReservations = [];

        document.getElementById("saved").innerHTML = "newReservations saved!";
    })

    data = "[";
    for (let i = 0; i < removedReservations.length; i++) {
        let cellId = removedReservations[i];
        data += "{\"id\": " + id + ", \"name\": \"" + username + "\",";

        let day = new Date(sunday);
        console.log("0" + day.toDateString());
        console.log(".5" + parseInt(cellId.slice(0, cellId.indexOf("-"))));
        day.setDate(sunday.getDate() + parseInt(cellId.slice(0, cellId.indexOf("-"))));
        console.log("1" + day.toDateString());
        data += "\"date\": \"" + sqlDateString(day) + "\",";
        let time = cellId.slice(cellId.indexOf("-") + 1, cellId.lastIndexOf("-"));
        console.log(time);
        data += "\"start\": \"" + formatTime(parseFloat(time)) + "\",";
        console.log(time);
        time = parseFloat(time) + 0.5;
        console.log(time);
        data += "\"end\": \"" + formatTime(parseFloat(time)) + "\",";
        data += "\"station\": " + cellId.slice(cellId.lastIndexOf("-") + 1) + "}";

        if (i < removedReservations.length - 1) {
            data += ",";
        }


    }

    data += "]"

    fetch(url + "removeReservation/index.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: data
    }).then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        console.log(response);
        return response.json();

    }).then(data => {
        console.log('Success:', data);
        removedReservations = [];

        document.getElementById("saved").innerHTML = "newReservations saved!";
        getReservations();
    })

}

function getReservations() {

    let sat = new Date(sunday);
    sat.setDate(sat.getDate() - 1);
    let endsun = new Date(sat);
    endsun.setDate(endsun.getDate() + 8);

    fetch(url + "getReservations/index.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: "{\"id\": \"" + id + "\", \"sat\": \"" + sqlDateString(sat) + "\", \"sun\": \"" + sqlDateString(endsun) + "\"}"
    }).then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        console.log(response);
        return response.json();

    }).then(data => {
        console.log('Success:', data);
        reservations = data
        console.log(reservations);
        updateCalendar();
    })


}

function updateCalendar() {
    console.log("updating calendar");

    // Convert HTMLCollection to an array to avoid live collection issues
    const officials = Array.from(document.getElementsByClassName('official'));

    for (const item of officials) {
        item.classList.remove('official');
        item.innerHTML = ''; // or item.textContent = '' for safer clearing
    }

    for (let i = 0; i < reservations.length; i++) {
        let res = reservations[i];
        let day = new Date(res.date);
        let dayIndex = day.getDay() + dayStart;
        let starttimeHour = parseInt(res.start.slice(0, 2));
        let starttimeMin = parseInt(res.start.slice(3, 5));
        let endTimeHour = parseInt(res.end.slice(0, 2));
        let endTimeMin = parseInt(res.end.slice(3, 5));
        let startTimeIndex = starttimeHour + (starttimeMin == 30 ? .5 : 0);
        let endTimeIndex = endTimeHour + (endTimeMin == 30 ? .5 : 0);




        console.log((dayIndex) + "-" + startTimeIndex + "-" + res.station);
        document.getElementById((dayIndex) + "-" + startTimeIndex + "-" + res.station).innerHTML = res.name;
        document.getElementById((dayIndex) + "-" + startTimeIndex + "-" + res.station).classList.add('official');
        document.getElementById((dayIndex) + "-" + startTimeIndex + "-" + res.station).classList.remove('user');
        //in future add ability to show multi-slot reservations/wrong interval reservations


    }
}

function clearCalendar() {
    document.querySelectorAll('.timeSlot').forEach(element => {
        element.innerHTML = "";
        element.classList.remove("user");
        element.classList.remove("official");
    })
}

updateCalendar();

let counter = 0;
const stopwatch = setInterval(() => {

    if (counter >= seconds) {
        counter = 0;
        console.log("load");
        getReservations();

        if (newReservations.length > 0 || removedReservations.length > 0) {
            sendReservations();
        }

    }
    counter++;
}, 1000);