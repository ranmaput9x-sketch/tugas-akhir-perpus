<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Anggota - DigiLib</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

        .card {
            width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="card mt-10">
        <h2 class="text-2xl font-bold text-center text-gray-800">Kartu Anggota Perpustakaan</h2>
        <div class="flex items-center justify-center mt-4">
            <img src="{{ Auth::user()->profile_photo_url }}" alt="Foto Profil"
                class="h-24 w-24 rounded-full border-2 border-indigo-600">
        </div>
        <div class="mt-4">
            <p><strong>ID Anggota:</strong> {{ Auth::user()->id }}</p>
            <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Tipe Keanggotaan:</strong> {{ Auth::user()->role }}</p>
            <p><strong>Status Keanggotaan:</strong> {{ Auth::user()->membership_status }}</p>
            <p><strong>Masa Berlaku Kartu:</strong> {{ Auth::user()->membership_validity }}</p>
        </div>
        <div class="mt-6 text-center">
            <button onclick="window.print()"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                Cetak Kartu
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2023 Perpustakaan Digilib. Semua Hak Dilindungi.</p>
        </div>
    </footer>
</body>

</html>
