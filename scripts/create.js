window.addEventListener("DOMContentLoaded", () => {

    const createForm = document.querySelector("#createSheet");

    createForm.addEventListener('submit', (ev) => {
        let error = false;

        const sheetName = document.querySelector("#name");
        const sheetError = sheetName.nextElementSibling;

        const description = document.querySelector("#description");
        const descriptionError = description.nextElementSibling;

        const startDate = document.querySelector("#start-date");
        const startDateError = startDate.nextElementSibling;
        const nullDateError = startDate.nextElementSibling.nextElementSibling;

        const startTime = document.querySelector("#start-time");
        const duration = document.getElementById("slotDuration");
        const durationValue = duration.options[duration.selectedIndex].value;
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
        var todaysDate = new Date();
        var startDateObj = new Date(startDateArr[0], startDateArr[1] - 1, startDateArr[2]); //load values

        if (startDateArr[0] == "")
        {
            error = true;
            nullDateError.classList.remove("hidden");
        }
        else {
            nullDateError.classList.add("hidden");
        }
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

        startTimeObj.setHours(startTimeJArr[0], startTimeJArr[1],00);  //load array values into date objects to compare
        endTimeObj.setHours(endTimeJArr[0], endTimeJArr[1],00);

        if (startTimeObj > endTimeObj)
        {
            error = true;
            timeError.classList.remove("hidden");
        }
        else {
            timeError.classList.add("hidden");
        }

       
        var startTimeUnix = Math.round(startTimeObj.getTime() / 1000);
        var endTimeUnix = Math.round(endTimeObj.getTime() / 1000);


        const durationError = document.getElementById("duration-error");
        durationInt = parseInt(durationValue);
        if(durationInt > (endTimeUnix - startTimeUnix)){
            error = true;
            durationError.classList.remove("hidden");
        }
        else{
            durationError.classList.add("hidden");
        }
        
        if(error)
        ev.preventDefault();
            
    });
});