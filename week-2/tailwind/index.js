  const menuToggle = document.getElementById('menu-toggle');
  const menu = document.getElementById('menu');

  // Toggle the visibility of the menu when the button is clicked
  menuToggle.addEventListener('click', () => {
    menu.classList.toggle('hidden'); // Toggle the "hidden" class
  });

  // Optional: Close the menu when a link is clicked (useful for single-page apps)
  menu.addEventListener('click', (event) => {
    if (event.target.tagName === 'A' && window.innerWidth < 768) {
      menu.classList.add('hidden'); // Close the menu on link click
    }
  });



function opentab(tabName) {
    // Hide all tab contents
    var tabContents = document.querySelectorAll('.tab-contents');
    tabContents.forEach(function(content) {
        content.style.display = 'none'; // Hide all tabs
    });

    // Show the clicked tab's content
    var activeTab = document.getElementById(tabName);
    activeTab.style.display = 'block'; // Show the selected tab

}
document.addEventListener('DOMContentLoaded', () => {
            opentab('skills');
            let clr= document.getElementsByClassName('skil');
            console.log(clr);
            clr.style.textcolor='red';
        });
// JavaScript form validation

// Wait for the DOM to load
window.onload = function () {
    const form = document.querySelector('.fri');
    const submitButton = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function (event) {
        let isValid = validateForm();

        if (!isValid) {
            event.preventDefault(); // Prevent form submission if validation fails
        } else {
            event.preventDefault(); // For demo purposes, prevent actual submission
            showSuccessMessage('Your message has been sent successfully!');
            resetForm();
        }
    });

    // Add hover effect to the submit button
    submitButton.addEventListener('mouseenter', function () {
        let isValid = validateForm();

        if (!isValid) {
            // Make the button float
            submitButton.style.position = 'relative';
            const randomX = Math.random() * 500 - 10; // Random movement between -10 and 10px
            const randomY = Math.random() * 10 - 5;  // Random movement between -5 and 5px
            submitButton.style.transform = `translate(${randomX}px, ${randomY}px)`;
        } else {
            // Stop the button from floating
            submitButton.style.position = 'static';
            submitButton.style.transform = 'none';
        }
    });

    // Function to validate the form
    function validateForm() {
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
        return isValid;
    }
    // Helper function to validate email format
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    // Function to display error message
    function displayError(inputElement, message) {
        const error = document.createElement('div');
        error.classList.add('error-message', 'text-red-500', 'text-sm', 'mt-1');
        error.textContent = message;
        inputElement.parentNode.insertBefore(error, inputElement.nextSibling);
    }
    // Helper function to show success message
    function showSuccessMessage(message) {
        const successMessage = document.createElement('div');
        successMessage.classList.add(
            'success-message',
            'fixed',
            'bottom-5',
            'right-5',
            'bg-green-500',
            'text-white',
            'px-4',
            'py-2',
            'rounded',
            'shadow-md',
            'z-50'
        );
        successMessage.textContent = message;
        document.body.appendChild(successMessage);

        // Remove the success message after 3 seconds
        setTimeout(() => {
            successMessage.remove();
        }, 3000);
    }

    // Function to reset the form
    function resetForm() {
        form.reset();
    }
};


