<?php require BASE_PATH . "views/partials/head.php"; ?>
<?php require BASE_PATH . "views/partials/nav.php"; ?>
<?php require BASE_PATH . "views/partials/banner.php"; ?>

<main>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-4 border-b text-left text-gray-700">Group Name</th>
                    <th class="py-3 px-4 border-b text-left text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groups as $group): ?>

                    <tr class="border-b hover:bg-gray-100">
                        <td class="py-3 px-4"> <?= htmlspecialchars($group['name']) ?> </td>
                        <td class="py-3 px-4">
                            <a href="/groups/edit?id=<?= $group['id'] ?>"
                                class="inline-flex items-center justify-center rounded-md bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Edit
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="mt-4">
            <a href="/groups/create"
                class="inline-flex items-center justify-center rounded-md bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Add Group
            </a>
        </div>
    </div>
</main>

<?php require BASE_PATH . "views/partials/footer.php"; ?>