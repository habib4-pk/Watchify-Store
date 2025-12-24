
var myInput = document.getElementById("password");

var length = document.getElementById("length");
var char = document.getElementById("char");
var message = document.getElementById("message");


myInput.onfocus = function() {
  message.style.display = "block";
}

myInput.onblur = function() {
  message.style.display = "none";
}

myInput.onkeyup = function() {
 

  
  
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }

  var specialChars = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;
  if(myInput.value.match(specialChars)) {  
    char.classList.remove("invalid");
    char.classList.add("valid");
  } else {
    char.classList.remove("valid");
    char.classList.add("invalid");
  }
}