document.querySelector('.toggle-password').addEventListener('click', function() {
    const passwordInput = document.querySelector(this.getAttribute('toggle'));
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        this.textContent = 'visibility';
    } else {
        passwordInput.type = 'password';
        this.textContent = 'visibility_off';
    }
});
// Get the button and popup elements
var viewProfileBtn = document.getElementById("viewProfileBtn");
var profilePopup = document.getElementById("profilePopup");

// Add click event listener to the button
viewProfileBtn.addEventListener("click", function() {
    // Show the popup
    profilePopup.style.display = "block";
});

// Get the close button element
var closeBtn = document.querySelector(".close");

// Add click event listener to the close button
closeBtn.addEventListener("click", function() {
    // Hide the popup
    profilePopup.style.display = "none";
});
