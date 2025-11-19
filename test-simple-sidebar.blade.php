<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Simple Sidebar</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- TEST 1: Sidebar WITHOUT Alpine.js -->
    <div class="fixed top-0 left-0 z-50 h-screen w-64 bg-slate-900 text-white p-4">
        <h2 class="text-xl font-bold mb-4">Test Sidebar</h2>
        <p class="text-gray-300">If you see this, Tailwind works!</p>
        <div class="mt-4 space-y-2">
            <div class="bg-blue-600 p-2 rounded">Menu 1</div>
            <div class="bg-blue-600 p-2 rounded">Menu 2</div>
            <div class="bg-blue-600 p-2 rounded">Menu 3</div>
        </div>
    </div>

    <!-- Content with margin -->
    <div class="ml-64 p-8">
        <h1 class="text-3xl font-bold mb-4">Test Page</h1>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-2">Sidebar Test</h2>
            <p class="mb-4">Jika Anda melihat sidebar DARK di kiri, berarti Tailwind CSS bekerja.</p>
            
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4">
                <strong>✅ SUCCESS:</strong> Static sidebar muncul!
            </div>

            <div class="mt-6">
                <h3 class="font-bold mb-2">Debugging Info:</h3>
                <ul class="list-disc list-inside text-sm text-gray-600">
                    <li>Tailwind CSS: Loaded via CDN</li>
                    <li>Alpine.js: Loaded via CDN</li>
                    <li>Sidebar: Fixed position, z-50</li>
                    <li>Content: ml-64 (margin left 256px)</li>
                </ul>
            </div>

            <div class="mt-6 bg-blue-50 p-4 rounded">
                <h3 class="font-bold text-blue-800 mb-2">Alpine.js Test:</h3>
                <div x-data="{ count: 0 }" class="space-y-2">
                    <p>Counter: <span x-text="count" class="font-bold text-blue-600">0</span></p>
                    <button @click="count++" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Click Me
                    </button>
                    <p class="text-sm text-gray-600">If counter increases when clicked, Alpine.js works!</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
