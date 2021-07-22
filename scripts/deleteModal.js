"use strict";

window.addEventListener("DOMContentLoaded", () => {

    var modal = document.getElementById("ModalWindow");
    var deletion = document.getElementById("delete");
    var no = document.getElementById("no");


// When the user clicks on the button, open the modal
deletion.addEventListener("click", function() {
  modal.style.display = "block";
})

// When the user clicks on <span> (x), close the modal
no.addEventListener("click", function() {
  modal.style.display = "none";
})

// When the user clicks anywhere outside of the modal, close it
modal.addEventListener("click", function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
})
})