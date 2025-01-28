var tablinks = document.getElementsByClassName("tab-links");
        var tabcontents = document.getElementsByClassName("tab-contents");

        function opentab(tabname) {
            for (tablink of tablinks) {
                tablink.classList.remove("active-link");
            }
            for (tabcontent of tabcontents) {
                tabcontent.classList.remove("active-tab");
            }
            event.currentTarget.classList.add("active-link");
            document.getElementById(tabname).classList.add("active-tab");
        }
// JavaScript form validation

// Wait for the DOM to load
window.onload = function() {
    const form = document.querySelector('.fr');

    form.addEventListener('submit', function(event) {
        let isValid = true;
        const name = form.name.value.trim();
        const email = form.email.value.trim();
        const message = form.message.value.trim();

        // Clear previous error messages
        const errorMessages = form.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());

        // Name validation
        if (name === '') {
            isValid = false;
            displayError(form.name, 'Name is required.');
        }

        // Email validation
        if (email === '') {
            isValid = false;
            displayError(form.email, 'Email is required.');
        } else if (!isValidEmail(email)) {
            isValid = false;
            displayError(form.email, 'Please enter a valid email address.');
        }

        // Message validation
        if (message === '') {
            isValid = false;
            displayError(form.message, 'Message is required.');
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        } else {
            event.preventDefault(); // Prevent actual form submission for demo purposes
            showSuccessMessage('Your message has been sent successfully!');
            resetForm();
        }
    });

    // Helper function to validate email format
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Helper function to display error messages
    function displayError(inputElement, message) {
        const error = document.createElement('div');
        error.classList.add('error-message');
        error.style.color = 'red';
        error.style.fontSize = '0.9em';
        error.textContent = message;
        inputElement.parentNode.insertBefore(error, inputElement.nextSibling);
    }

    // Helper function to show success message
    function showSuccessMessage(message) {
        const successMessage = document.createElement('div');
        successMessage.classList.add('success-message');
        successMessage.textContent = message;
        successMessage.style.position = 'fixed';
        successMessage.style.bottom = '20px';
        successMessage.style.right = '20px';
        successMessage.style.backgroundColor = '#4CAF50';
        successMessage.style.color = 'white';
        successMessage.style.padding = '10px 20px';
        successMessage.style.borderRadius = '5px';
        successMessage.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.2)';
        successMessage.style.zIndex = '1000';
        
        document.body.appendChild(successMessage);

        // Remove the success message after 3 seconds
        setTimeout(() => {
            successMessage.remove();
        }, 3000);
    }

 function resetForm() {
        form.reset();
    }
};