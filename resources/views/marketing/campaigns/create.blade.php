<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen text-white">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6">Buat Kampanye Baru</h2>

            <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                <form action="{{ route('campaigns.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-400 mb-2">Nama Kampanye</label>
                        <input type="text" name="campaign_name" class="w-full bg-gray-700 border-none rounded text-white p-2 focus:ring-2 focus:ring-yellow-500" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-400 mb-2">Budget (Rupiah)</label>
                            <input type="number" name="budget" class="w-full bg-gray-700 border-none rounded text-white p-2" required>
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-2">Target Audience</label>
                            <input type="text" name="target_audience" placeholder="Contoh: Gen Z, Action Fans" class="w-full bg-gray-700 border-none rounded text-white p-2" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-400 mb-2">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="w-full bg-gray-700 border-none rounded text-white p-2" required>
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-2">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="w-full bg-gray-700 border-none rounded text-white p-2" required>
                        </div>
                    </div>

                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold py-2 px-6 rounded">
                        Simpan Kampanye
                    </button>
                    <a href="{{ route('campaigns.index') }}" class="ml-4 text-gray-400 hover:text-white">Batal</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>