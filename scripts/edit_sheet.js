window.addEventListener("DOMContentLoaded", () => {

    const editForm = document.querySelector("#editForm");


    editForm.addEventListener('submit', (ev) => {
        let error = false;

        const sheetName = document.querySelector("#name");
        const sheetError = sheetName.nextElementSibling.nextElementSibling;

        const description = document.querySelector("#description");
        const descriptionError = description.nextElementSibling;

        const startDate = document.querySelector("#start");
        const startDateError = startDate.nextElementSibling.nextElementSibling;

        const duration = document.getElementById("slotDuration");
        const durationValue = duration.options[duration.selectedIndex].value;

       
        const startTime = document.querySelector("#start-time");

        const endTime = document.querySelector("#end-time");
        const timeError = document.querySelector("#time-error");

        if (sheetName.value != "")
        {
            sheetError.classList.add("hidden");
        }
        else
        {
            error = true;
            sheetError.classList.remove("hidden");
        }
        
        if (description.value != "")
        {
            descriptionError.classList.add("hidden");
        }
        else
        {
            error = true;
            descriptionError.classList.remove("hidden");
        }

        //date comparison to ensure it isn't before today's date.

        var startDateArr = startDate.value.split("-"); //split year/month/day into array
        var startDateObj = new Date(startDateArr[0], startDateArr[1] - 1, startDateArr[2]);  //load values 
        var todaysDate = new Date(); 

        if (startDateObj < todaysDate)
        {
            error = true;
            startDateError.classList.remove("hidden");
        }
        else
        {
            startDateError.classList.add("hidden");
        }

        //time comparison
        var startTimeJArr = startTime.value.split(":"); //starttime in an array
        var endTimeJArr = endTime.value.split(":"); //endtime in an array

        var startTimeObj = new Date();
        var endTimeObj = new Date();

        startTimeObj.setHours(startTimeJArr[0], startTimeJArr[1],00); //load array values into date objects to compare
        endTimeObj.setHours(endTimeJArr[0], endTimeJArr[1],00);

        if (startTimeObj > endTimeObj)
        {
            error = true;
            timeError.classList.remove("hidden");
        }
        else {
            timeError.classList.add("hidden");
        }


         // validation for duration being longer than
        // the specified start and end time


        // getting epoch time
        var startTimeUnix = Math.round(startTimeObj.getTime() / 1000);
        var endTimeUnix = Math.round(endTimeObj.getTime() / 1000);

        // getting the duration time
        const durationError = document.getElementById("duration-error");
        durationInt = parseInt(durationValue);

        // checking if duration is greater
        if(durationInt > (endTimeUnix - startTimeUnix)){
            error = true;
            durationError.classList.remove("hidden");
        }
        else{
            durationError.classList.add("hidden");
        }
        
        if (error)
        {
            ev.preventDefault();
            }
    });
});