<?php require base_path("views/partials/head.php"); ?>
<?php require base_path("views/partials/nav.php"); ?>
<?php require base_path("views/partials/banner.php"); ?>
<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Group</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td class="px-6 py-4 text-gray-800"><?= htmlspecialchars($expense['name']) ?></td>
                            <td class="px-6 py-4 text-gray-700">₹<?= htmlspecialchars($expense['amount']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($expense['date']) ?></td>
                            <td class="px-6 py-4"><?= htmlspecialchars($expense['category'] ?? 'Unknown') ?></td>
                            <td class="px-6 py-4">
                                <a href="/expenses/edit?id=<?= $expense['id'] ?>"
                                    class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <a href="/expenses/create">
                <button class="mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Add Expense
                </button>
            </a>
        </div>
    </div>
</main>
<?php require base_path("views/partials/footer.php"); ?>