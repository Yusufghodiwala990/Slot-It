"use strict";

window.addEventListener("DOMContentLoaded", () => {
    /* Get the text field */
    var copyText = document.getElementsByClassName("button");
  
    for (let i = 0; i < copyText.length; i++) {
    copyText[i].addEventListener("click",function(){
        var text = "https://loki.trentu.ca/~daudjusab/3420/project/3420-Project/scripts" + copyText[i].value;
        var copy = document.createElement("input");
        document.body.appendChild(copy);
        copy.value = text;
        copy.select();
        document.execCommand("copy");
        document.body.removeChild(copy);

  
    /* Alert the copied text */
    var alert = document.createElement("p");
    alert.appendChild(document.createTextNode("Copied"));
    copyText[i].insertBefore(alert,copyText[i].nextElementSibling);
    setTimeout(function(){ alert.remove() }, 1500);
    })
}
  })