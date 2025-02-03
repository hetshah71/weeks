$(document).ready(function () {
  let groups = JSON.parse(localStorage.getItem("groups")) || [];
  let expenses = JSON.parse(localStorage.getItem("expenses")) || [];
  function updateLocalStorage() {
    localStorage.setItem("groups", JSON.stringify(groups));
    localStorage.setItem("expenses", JSON.stringify(expenses));
  }
  function renderGroups() {
    $("#groupList").empty();
    $("#expenseGroup").empty();
    groups.forEach((group) => {
      $("#groupList").append(
        `<li class='p-2 border-b'>${group.name} (Created: ${group.createdAt}, Updated: ${group.updatedAt}) <button class='edit-group bg-yellow-500 text-white px-2 py-1 rounded' data-name='${group.name}'>Edit</button> <button class='delete-group bg-red-500 text-white px-2 py-1 rounded' data-name='${group.name}'>Delete</button></li>`
      );
      $("#expenseGroup").append(
        `<option value='${group.name}'>${group.name}</option>`
      );
    });
  }

  $("#addGroup").click(function () {
    let groupName = $("#groupName").val().trim();
    if (groupName === "") {
      alert("Group name cannot be empty");
      return;
    }
    if (
      groups.some(
        (group) => group.name.toLowerCase() === groupName.toLowerCase()
      )
    ) {
      alert("Group already exists");
      return;
    }
    let now = moment().format("YYYY-MM-DD HH:mm:ss");
    groups.push({ name: groupName, createdAt: now, updatedAt: now });
    updateLocalStorage();
    renderGroups();
    $("#groupName").val("");
  });
  $("#addExpense").click(function () {
    let expenseName = $("#expenseName").val().trim();
    let expenseAmount = parseFloat($("#expenseAmount").val());
    let expenseGroup = $("#expenseGroup").val();
    let expenseDate = $("#expenseDate").val();
    if (
      expenseName === "" ||
      isNaN(expenseAmount) ||
      expenseGroup === "" ||
      !expenseDate
    ) {
      alert("Please enter valid expense details");
      return;
    }
    expenses.push({
      name: expenseName,
      amount: expenseAmount,
      group: expenseGroup,
      date: expenseDate,
    });

    groups.forEach((group) => {
      if (group.name === expenseGroup) {
        group.updatedAt = moment().format("YYYY-MM-DD HH:mm:ss");
      }
    });
    updateLocalStorage();
    renderExpenses();
    renderGroups();
    $("#expenseName, #expenseAmount, #expenseDate").val("");
  });
  $(document).on("click", ".edit-group", function () {
    let groupName = $(this).data("name");
    let newGroupName = prompt("Edit group name:", groupName);
    if (newGroupName && newGroupName.trim() !== "") {
      groups = groups.map((group) => {
        if (group.name === groupName) {
          group.name = newGroupName;
          group.updatedAt = moment().format("YYYY-MM-DD HH:mm:ss");
        }
        return group;
      });
      expenses = expenses.map((exp) => {
        if (exp.group === groupName) {
          exp.group = newGroupName;
        }
        return exp;
      });
      updateLocalStorage();
      renderGroups();
      renderExpenses();
    }
  });
  $(document).on("click", ".delete-group", function () {
    let groupName = $(this).data("name");
    if (confirm(`Are you sure you want to delete the group "${groupName}"?`)) {
      groups = groups.filter((group) => group.name !== groupName);
      expenses = expenses.filter((exp) => exp.group !== groupName);
      updateLocalStorage();
      renderGroups();
      renderExpenses();
    }
  });

  $(document).on("click", ".edit-expense", function () {
    let index = $(this).data("index");
    let expense = expenses[index];

    let modal = $(
      `<div class="modal bg-gray-500 bg-opacity-50 fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-6 rounded shadow-md w-96">
                    <h2 class="text-lg font-semibold mb-4">Edit Expense</h2>
                    <label class="block mb-2">Expense Name</label>
                    <input type="text" id="editExpenseName" class="border p-2 rounded w-full mb-2" value="${
                      expense.name
                    }">
                    <label class="block mb-2">Amount</label>
                    <input type="number" id="editExpenseAmount" class="border p-2 rounded w-full mb-2" value="${
                      expense.amount
                    }">
                    <label class="block mb-2">Group</label>
                    <select id="editExpenseGroup" class="border p-2 rounded w-full mb-2">
                        ${groups.map(
                          (group) =>
                            `<option value="${group.name}" ${
                              group.name === expense.group ? "selected" : ""
                            }>${group.name}</option>`
                        )}
                    </select>
                    <label class="block mb-2">Date</label>
                    <input type="date" id="editExpenseDate" class="border p-2 rounded w-full mb-2" value="${
                      expense.date
                    }">
                    <div class="flex justify-end gap-2">
                        <button id="saveExpenseEdit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
                        <button id="cancelExpenseEdit" class="bg-red-500 text-white px-4 py-2 rounded">Cancel</button>
                    </div>
                </div>
            </div>`
    );
    $("body").append(modal);

    $("#saveExpenseEdit").click(function () {
      let newName = $("#editExpenseName").val().trim();
      let newAmount = parseFloat($("#editExpenseAmount").val());
      let newGroup = $("#editExpenseGroup").val();
      let newDate = $("#editExpenseDate").val();

      if (
        newName &&
        newName !== "" &&
        !isNaN(newAmount) &&
        newGroup &&
        newDate
      ) {
        expenses[index] = {
          name: newName,
          amount: newAmount,
          group: newGroup,
          date: newDate,
        };

        groups.forEach((group) => {
          if (group.name === newGroup) {
            group.updatedAt = moment().format("YYYY-MM-DD HH:mm:ss");
          }
        });
        updateLocalStorage();
        renderExpenses();
        renderGroups();
        modal.remove();
      } else {
        console.error("Please enter valid details.");
      }
    });

    $("#cancelExpenseEdit").click(function () {
      modal.remove();
    });
  });

  $(document).on("click", ".delete-expense", function () {
    let index = $(this).data("index");
    let modal = $(
      `<div class="modal bg-gray-500 bg-opacity-50 fixed inset-0 flex items-center justify-center">
                <div class="bg-white p-6 rounded shadow-md w-80">
                    <h2 class="text-lg font-semibold mb-4">Confirm Deletion</h2>
                    <p class="mb-4">Are you sure you want to delete this expense?</p>
                    <div class="flex justify-end gap-2">
                        <button id="confirmDeleteExpense" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                        <button id="cancelDeleteExpense" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                    </div>
                </div>
            </div>`
    );

    $("body").append(modal);

    $("#confirmDeleteExpense").click(function () {
      expenses.splice(index, 1);
      updateLocalStorage();
      renderExpenses();
      modal.remove();
    });

    $("#cancelDeleteExpense").click(function () {
      modal.remove();
    });
  });

  function renderMonthlyExpenses(selectedMonth) {
    let filteredExpenses = expenses.filter(
      (exp) => moment(exp.date).format("YYYY-MM") === selectedMonth
    );
    let monthlyExpenseList = $("#monthlyExpenseList");
    let monthlyTotal = 0;

    monthlyExpenseList.empty();

    if (filteredExpenses.length === 0) {
      monthlyExpenseList.append(
        "<li class='p-2'>No expenses for the selected month.</li>"
      );
      $("#monthlyExpense").text(`₹0`);
    } else {
      filteredExpenses.forEach((exp) => {
        monthlyExpenseList.append(
          `<li class='p-2 border-b flex justify-between'>
                    ${exp.name} - ₹${exp.amount} (${exp.group}, Date: ${exp.date})
                </li>`
        );
        monthlyTotal += exp.amount;
      });

      // Update the monthly total display
      $("#monthlyExpense").text(`₹${monthlyTotal}`);
    }
  }
  // Event listener for the month selector
  $("#monthSelector").change(function () {
    let selectedMonth = $(this).val();
    if (selectedMonth) {
      renderMonthlyExpenses(selectedMonth);
    }
  });
  renderGroups();
  renderExpenses();
});
