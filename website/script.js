document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    
    loginForm.addEventListener('submit', (event) => {
        event.preventDefault(); // Prevent the form from submitting the default way
        // Perform validation or other logic here if needed
        // Redirect to another page
        window.location.href = 'home.html'; // Replace with your target page URL
    });
});
