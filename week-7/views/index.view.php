<?php require BASE_PATH . "views/partials/head.php"; ?>
<?php require BASE_PATH . "views/partials/nav.php"; ?>
<?php require BASE_PATH . "views/partials/banner.php"; ?>

<main>
    <div class="container mx-auto max-w-7xl px-4 py-1 sm:px-6 lg:px-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Expense Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Spent</p>
                        <p class="text-3xl font-bold mt-2">₹<?= $totalExpense['total_expense'] ?? 0; ?></p>
                    </div>
                    <svg class="w-12 h-12 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Max Expense Card -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Biggest Purchase</p>
                        <p class="text-2xl font-bold mt-2 text-gray-800">₹<?= $maxExpense['amount'] ?? 0; ?></p>
                        <p class="text-sm text-gray-500 mt-1"><?= $maxExpense['name'] ?? 'N/A'; ?></p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Monthly Summary Card -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <div class="flex flex-col">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Breakdown</h3>
                    <?php foreach ($monthlyExpense as $month) : ?>
                        <div class="mb-3">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600"><?= $month['month']; ?></span>
                                <span class="font-medium text-gray-700">₹<?= $month['total_monthly_expense']; ?></span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500" style="width: <?= ($month['total_monthly_expense'] / $totalExpense['total_expense']) * 100 ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Expenses Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Expenses -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Expenses</h3>
                <div class="space-y-4">
                    <?php foreach ($groups as $group) : ?>
                        <?php if ($group['expense_id']) : ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div>
                                    <p class="font-medium text-gray-800"><?= htmlspecialchars($group['expense_name']); ?></p>
                                    <p class="text-sm text-gray-500"><?= htmlspecialchars($group['group_name']); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-medium text-gray-800">₹<?= htmlspecialchars($group['amount']); ?></p>
                                    <p class="text-sm text-gray-500"><?= htmlspecialchars($group['date']); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Group Summary -->
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Group Analysis</h3>
                <div class="space-y-6">
                    <?php
                    $groupedExpenses = [];
                    foreach ($groups as $group) {
                        if (!isset($groupedExpenses[$group['group_name']])) {
                            $groupedExpenses[$group['group_name']] = [
                                'total' => 0,
                                'max' => 0
                            ];
                        }
                        $groupedExpenses[$group['group_name']]['total'] += $group['amount'] ?? 0;
                        $groupedExpenses[$group['group_name']]['max'] = max($groupedExpenses[$group['group_name']]['max'], $group['amount'] ?? 0);
                    }
                    ?>
                    <?php foreach ($groupedExpenses as $groupName => $data) : ?>
                        <div class="group-analysis-item">
                            <div class="flex justify-between mb-2">
                                <span class="font-medium text-gray-700"><?= htmlspecialchars($groupName); ?></span>
                                <span class="text-sm text-gray-500">Max: ₹<?= htmlspecialchars($data['max']); ?></span>
                            </div>
                            <div class="relative pt-1">
                                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-gray-100">
                                    <div style="width:<?= ($data['total'] / $totalExpense['total_expense']) * 100 ?>%"
                                        class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-purple-500"></div>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Total spent</span>
                                    <span class="font-medium text-gray-700">₹<?= htmlspecialchars($data['total']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require BASE_PATH . "views/partials/footer.php"; ?>