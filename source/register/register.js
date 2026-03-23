console.log("Viewport Width:", window.innerWidth);
console.log("Viewport Height:", window.innerHeight);

let toastElList = [].slice.call(document.querySelectorAll('.toast'))
let toastList = toastElList.map(function (toastEl) {
    return new bootstrap.Toast(toastEl, { delay: 3000 });
});
toastList.forEach(toast => toast.show());


//display password
const passwordField = document.getElementById("password");
const confirmPasswordField = document.getElementById("confirm_password");
const form = document.querySelector("form");

function displayPass() {
    const icon = document.getElementById("eyeIcon");

    if (passwordField.type === "password" && confirmPasswordField.type === "password") {
        passwordField.type = "text";
        confirmPasswordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        confirmPasswordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

//check password = confirm password
function checkMatch() {
    if(confirmPasswordField.value && confirmPasswordField !== passwordField) {
        confirm.setCustomValidity("Passwords do not match"); 
    } else {
        confirm.setCustomValidity("");
    }
}

confirmPasswordField.addEventListener("input", checkMatch);
passwordField.addEventListener("input", checkMatch);

form.addEventListener("submit", (e) => {
    checkMatch();
}) 