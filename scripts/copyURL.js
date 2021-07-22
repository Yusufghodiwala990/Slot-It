"use strict";

window.addEventListener("DOMContentLoaded", () => {
    /* Get the text field */
    var copyText = document.getElementsByClassName("button");
  
    for (let i = 0; i < copyText.length; i++) {
    copyText[i].addEventListener("click",function(){
        var text = "https://loki.trentu.ca/~daudjusab/3420/project/3420-Project/scripts" + copyText[i].value;
        var dummy = document.createElement("input");
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);

  
    /* Alert the copied text */
    alert("Copied the link to the Signupsheet");
    })
}
  })