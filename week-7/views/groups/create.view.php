<?php require BASE_PATH . "views/partials/head.php"; ?>
<?php require BASE_PATH . "views/partials/nav.php"; ?>
<?php require BASE_PATH . "views/partials/banner.php"; ?>
<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div id="message-container"></div>
        <form class="max-w-sm mx-auto" method="POST" action="/groups" id="groupForm">
            <div class="mb-5">
                <label for="groupName" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Group Name</label>
                <input type="text" id="groupName" name="name"
                    class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" />
                <div id="name-error" class="text-red-500 text-xs mt-1"></div>
            </div>
            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Add Group
            </button>
        </form>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    //Document Ready Function
    $(document).ready(function() {
        // Clear previous messages when starting a new submission
        function clearMessages() {
            $("#message-container").empty();
            $("#name-error").empty();
            $(".error-message").remove();
        }
        //Form Validation Setup
        $("#groupForm").validate({
            //Rules for Validation
            rules: {
                name: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                name: {
                    required: "Please enter a group name",
                    minlength: "Group name must be at least 3 characters long"
                }
            },
            errorClass: "text-red-500 text-xs", //Error Message Styling
            //Custom Error Placement
            errorPlacement: function(error, element) {
                if (element.attr("name") === "name") {
                    $("#name-error").html(error);
                } else {
                    error.insertAfter(element);
                }
                error.addClass("error-message");
            },
            submitHandler: function(form) {
                clearMessages();
                //Disable Submit Button
                const submitButton = $(form).find('button[type="submit"]');
                submitButton.prop('disabled', true);

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(response) {
                        try {
                            const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
                            if (jsonResponse.error) {
                                $("#name-error").html(jsonResponse.error);
                                submitButton.prop('disabled', false);
                                return;
                            }

                            $("#message-container").html('<p class="text-green-500 text-sm mb-4">' + (jsonResponse.message || 'Group added successfully!') + '</p>');

                            // Disable form controls
                            $("#groupForm :input").prop("disabled", true);

                            // Redirect after successful creation
                            setTimeout(() => {
                                window.location.href = '/groups';
                            }, 1000);
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            $("#message-container").html('<p class="text-red-500 text-sm mb-4">An error occurred while processing the response.</p>');
                            submitButton.prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            $("#name-error").html(xhr.responseJSON.error);
                        } else {
                            $("#message-container").html('<p class="text-red-500 text-sm mb-4">An error occurred while adding the group. Please try again.</p>');
                        }
                        submitButton.prop('disabled', false);
                    }
                });
                return false;
            }
        });
    });
</script>

<?php require BASE_PATH . "views/partials/footer.php"; ?>