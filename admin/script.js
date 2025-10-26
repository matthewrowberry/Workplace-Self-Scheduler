coumns = "";



const tableBody = document.getElementById("startRestrictionsBody");

function addExistingRestrictions() {
    for (i = 0; i < startTimes.length; i++) {
        console.log(i);
        console.log(startTimes[i]);

        let restriction = startTimes[i];
        console.log(restriction.startFromWeekday);
        console.log(restriction.prevDayOffset);
        console.log(restriction.hour);
        console.log(restriction.minute);
        let name = idToName.get(String(restriction.id)) ?? "Default";
        const newRow = document.createElement("tr");
        const nameCell = document.createElement("td");
        nameCell.innerHTML = name;
        newRow.appendChild(nameCell);
        const weekStartCell = document.createElement("td");
        weekStartCell.innerHTML = restriction.startFromWeekday;
        newRow.appendChild(weekStartCell);
        const prevDaysOffset = document.createElement("td");
        prevDaysOffset.innerHTML = restriction.prevDaysOffset;
        newRow.appendChild(prevDaysOffset);
        const hour = document.createElement("td");
        hour.innerHTML = restriction.hour;
        newRow.appendChild(hour);
        const minute = document.createElement("td");
        minute.innerHTML = restriction.minute;
        newRow.appendChild(minute);
        tableBody.appendChild(newRow);
    }
}

addExistingRestrictions();


function addRow() {

    const newRowRow = document.getElementById("new");

    const newRow = document.createElement("tr");
    newRow.id = "newRow";


    const tableHeaderRow = document.getElementById("tableHeaderRow");


    for (const child of tableHeaderRow.children) {
        const newCell = document.createElement("td");


        if (child.innerHTML == "Name") {
            newCell.innerHTML = dropdown;
        } else {
            newCell.innerHTML = "<input type=\"number\" id=\"" + child.innerHTML.replaceAll(" ", "_") + "\">";
        }
        newRow.appendChild(newCell);
    }
    const newCell = document.createElement("td");
    newCell.innerHTML = "<input type=\"submit\">";
    newRow.appendChild(newCell);

    tableBody.appendChild(newRow);

}



function addTimeBlock(event) {

    event.preventDefault();
    id = Number(document.getElementById("restrictionDropdown").value);
    weekdayStart = Number(document.getElementById("Start_from_Weekday").value);
    offsetPrev = Number(document.getElementById("Previous_days_offset").value);
    hour = Number(document.getElementById("Hours").value);
    minute = Number(document.getElementById("Minutes").value);

    fetch(url + "admin/addRow.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: "{\"id\": " + id + ", \"startFromWeekday\": " + weekdayStart + ", \"prevDaysOffset\": " + offsetPrev + ", \"hour\": " + hour + ", \"minute\": " + minute + "}"
    }).then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        console.log(response);
        return response.json();

    }).then(data => {
        console.log('Success:', data);


    })


}