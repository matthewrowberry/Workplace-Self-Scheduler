coumns = "";



const tableBody = document.getElementById("startRestrictionsBody");

function edit(id) {
    console.log(id);

    const specificRestriction = startTimes.find(obj => obj.id === id);

    body = document.body;
    div = document.createElement("div");
    div.id = "editor";

    form = document.createElement("form");

    idNum = document.createElement("input");
    idNum.type = "text";
    idNum.value = id;

    nameLabel = document.createElement("h4");
    nameLabel.innerHTML = idToName.get(String(id)) ?? "Default";
    form.appendChild(nameLabel);
    idNum.disabled = true;
    idNum.id = "editorID";
    idNum.style.display = "none";
    form.appendChild(idNum);

    startFromWeekdayLabel = document.createElement("label");
    startFromWeekdayLabel.for = "editorStartFromWeekday";
    startFromWeekdayLabel.innerHTML = "Start from Day";
    form.appendChild(startFromWeekdayLabel);


    startFromWeekday = document.createElement("input");
    startFromWeekday.type = "number";
    startFromWeekday.id = "editorStartFromWeekday";
    startFromWeekday.value = specificRestriction.startFromWeekday;
    form.appendChild(startFromWeekday);


    prevDayOffsetLabel = document.createElement("label");
    prevDayOffsetLabel.for = "editorPrevDayOffset";
    prevDayOffsetLabel.innerHTML = "Previous Days Offset";
    form.appendChild(prevDayOffsetLabel);

    prevDayOffset = document.createElement("input");
    prevDayOffset.type = "number";
    prevDayOffset.id = "editorPrevDayOffset";
    prevDayOffset.value = specificRestriction.prevDaysOffset;
    form.appendChild(prevDayOffset);

    hoursLabel = document.createElement("label");
    hoursLabel.for = "editorHour";
    hoursLabel.innerHTML = "Hour";
    form.appendChild(hoursLabel);

    hours = document.createElement("input");
    hours.type = "number";
    hours.id = "editorHour";
    hours.value = specificRestriction.hour;
    form.appendChild(hours);

    minutesLabel = document.createElement("label");
    minutesLabel.for = "editorMinute";
    minutesLabel.innerHTML = "Minutes";
    form.appendChild(minutesLabel);

    minutes = document.createElement("input");
    minutes.type = "number";
    minutes.id = "editorMinute";
    minutes.value = specificRestriction.minute;
    form.appendChild(minutes);

    submit = document.createElement("input");
    submit.type = "submit";
    submit.id = "editorSubmit";
    form.appendChild(submit);


    form.onsubmit = function (e) {
        e.preventDefault();

        id = Number(document.getElementById("editorID").value);
        weekdayStart = Number(document.getElementById("editorStartFromWeekday").value);
        offsetPrev = Number(document.getElementById("editorPrevDayOffset").value);
        hour = Number(document.getElementById("editorHour").value);
        minute = Number(document.getElementById("editorMinute").value);

        fetch(url + "admin/edit.php", {
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

    div.appendChild(form);

    body.appendChild(div);
}

function remove(id) {
    console.log(id);
    fetch(url + "admin/removeRestriction.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: "{\"id\": " + id + "}"
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

        const editbtn = document.createElement("button");
        const removebtn = document.createElement("button");

        editbtn.type = "button";
        removebtn.type = "button";

        editbtn.id = restriction.id + "_edit";
        removebtn.id = restriction.id + "_remove";

        editbtn.innerHTML = "Edit";
        removebtn.innerHTML = "Delete";

        editbtn.addEventListener('click', () => edit(restriction.id));
        removebtn.addEventListener('click', () => remove(restriction.id));


        const options = document.createElement("td");
        options.appendChild(editbtn);
        options.appendChild(removebtn);

        newRow.appendChild(options);



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