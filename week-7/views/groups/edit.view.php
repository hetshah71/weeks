<?php require BASE_PATH . "views/partials/head.php"; ?>
<?php require BASE_PATH . "views/partials/nav.php"; ?>
<?php require BASE_PATH . "views/partials/banner.php"; ?>
<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div id="message-container"></div>
        <form class="max-w-sm mx-auto" method="POST" action="/groups" id="editGroupForm">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="id" value="<?= htmlspecialchars($group['id']) ?>">
            <div class="mb-5">
                <label for="groupName" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Group Name</label>
                <input type="text" 
                    id="groupName" 
                    name="name" 
                    value="<?= htmlspecialchars($group['name']) ?>"
                    class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light"
                />
            </div>
            <div class="bg-gray-50 px-4 py-3 text-right sm:px-6 flex gap-x-4 justify-end items-center">
                <button type="button"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                    onclick="document.querySelector('#delete-form').submit()">
                    Delete
                </button>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Update Group
                </button>
            </div>
        </form>

        <form id="delete-form" class="hidden" method="POST" action="/groups">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="id" value="<?= htmlspecialchars($group['id']) ?>">
        </form>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        // Clear previous messages
        function clearMessages() {
            $("#message-container").empty();
            $(".error-message").remove();
        }

        $("#editGroupForm").validate({
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
            errorClass: "text-red-500 text-xs",
            submitHandler: function(form) {
                clearMessages();
                const formData = $(form).serializeArray();
                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        try {
                            const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
                            $("#message-container").html('<p class="text-green-500 text-sm mb-4">' + (jsonResponse.message || 'Group updated successfully!') + '</p>');
                            
                            // Optionally redirect after successful update
                            setTimeout(() => {
                                window.location.href = '/groups';
                            }, 1000);
                        } catch (e) {
                            $("#message-container").html('<p class="text-red-500 text-sm mb-4">An error occurred while processing the response.</p>');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            for (let key in errors) {
                                $("#" + key).after('<p class="text-red-500 text-xs error-message mt-1">' + errors[key] + '</p>');
                            }
                        } else {
                            $("#message-container").html('<p class="text-red-500 text-sm mb-4">An error occurred. Please try again.</p>');
                        }
                    }
                });
                return false;
            }
        });
    });
</script>

<?php require BASE_PATH . "views/partials/footer.php"; ?>