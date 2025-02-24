<?php require base_path("views/partials/head.php"); ?>
<?php require base_path("views/partials/nav.php"); ?>
<?php require base_path("views/partials/banner.php"); ?>
<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div id="message-container"></div>
        <form class="max-w-sm mx-auto mb-5" method="POST" action="/expenses" id="editExpenseForm">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="id" value="<?= htmlspecialchars($expense['id']) ?>">
            
            <div class="mb-4">
                <label for="expenseName" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Expense Name</label>
                <input type="text" id="expenseName" name="name" value="<?= htmlspecialchars($expense['name']) ?>"
                    class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" />
            </div>

            <div class="mb-4">
                <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Amount</label>
                <input type="number" id="amount" name="amount" value="<?= htmlspecialchars($expense['amount']) ?>" step="0.01"
                    class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" />
            </div>

            <div class="mb-4">
                <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Date of Expense</label>
                <input type="date" id="date" name="date" value="<?= htmlspecialchars($expense['date']) ?>"
                    class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light" />
            </div>

            <div class="mb-4">
                <label for="group_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-black">Group</label>
                <select id="group_id" name="group_id"
                    class="shadow-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-xs-light">
                    <option value="">Select Group</option>
                    <?php foreach ($groups as $group): ?>
                        <option value="<?= $group['id'] ?>" <?= $group['id'] == $expense['group_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($group['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex justify-between items-center mt-6">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Update Expense
                </button>

                <button type="button" id="deleteExpenseBtn"
                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Delete Expense
                </button>
            </div>
        </form>

        <!-- Hidden delete form -->
        <form id="deleteExpenseForm" class="hidden" method="POST" action="/expenses">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="id" value="<?= htmlspecialchars($expense['id']) ?>">
        </form>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        // Set max date as today
        const today = new Date().toISOString().split('T')[0];
        $('#date').attr('max', today);

        // Clear previous messages
        function clearMessages() {
            $("#message-container").empty();
            $(".error-message").remove();
        }

        // Handle delete button click
        $("#deleteExpenseBtn").click(function() {
            if (confirm('Are you sure you want to delete this expense?')) {
                clearMessages();
                const formData = $('#deleteExpenseForm').serialize();
                
                $.ajax({
                    url: '/expenses',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        try {
                            const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
                            
                            if (jsonResponse.error) {
                                $("#message-container").html('<p class="text-red-500 text-sm mb-4">' + jsonResponse.error + '</p>');
                                return;
                            }
                            
                            $("#message-container").html('<p class="text-green-500 text-sm mb-4">' + (jsonResponse.message || 'Expense deleted successfully!') + '</p>');
                            
                            // Disable form controls
                            $("#editExpenseForm :input").prop("disabled", true);
                            $("#deleteExpenseBtn").prop("disabled", true);
                            
                            // Redirect after successful delete
                            setTimeout(() => {
                                window.location.href = '/expenses';
                            }, 1000);
                        } catch (e) {
                            $("#message-container").html('<p class="text-red-500 text-sm mb-4">An error occurred while processing the response.</p>');
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON && xhr.responseJSON.error 
                            ? xhr.responseJSON.error 
                            : 'An error occurred while deleting the expense.';
                        
                        $("#message-container").html('<p class="text-red-500 text-sm mb-4">' + message + '</p>');
                    }
                });
            }
        });

        $("#editExpenseForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                amount: {
                    required: true,
                    number: true,
                    min: 0.01
                },
                date: {
                    required: true,
                    date: true
                },
                group_id: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Please enter an expense name",
                    minlength: "Expense name must be at least 3 characters long"
                },
                amount: {
                    required: "Please enter an amount",
                    number: "Please enter a valid number",
                    min: "Amount must be greater than 0"
                },
                date: {
                    required: "Please select a date",
                    date: "Please enter a valid date"
                },
                group_id: {
                    required: "Please select a group"
                }
            },
            errorClass: "text-red-500 text-xs",
            errorPlacement: function(error, element) {
                error.insertAfter(element);
                error.addClass("error-message mt-1");
            },
            submitHandler: function(form) {
                clearMessages();
                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(response) {
                        try {
                            const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
                            $("#message-container").html('<p class="text-green-500 text-sm mb-4">' + (jsonResponse.message || 'Expense updated successfully!') + '</p>');
                            
                            // Redirect after successful update
                            setTimeout(() => {
                                window.location.href = '/expenses';
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

<?php require base_path("views/partials/footer.php"); ?>