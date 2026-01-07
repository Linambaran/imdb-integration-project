<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('marketing.dashboard') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-cyan-400 transition font-medium text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Marketing Dashboard
                </a>
            </div>

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-100">Daftar Kampanye</h2>
                
                <a href="{{ route('campaigns.create') }}" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg flex items-center gap-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Kampanye Baru
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-600 text-white p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full text-left text-sm font-light">
                    <thead class="border-b font-medium border-gray-700 bg-gray-700">
                        <tr>
                            <th class="px-6 py-4">Nama Kampanye</th>
                            <th class="px-6 py-4">Budget</th>
                            <th class="px-6 py-4">Target Audience</th>
                            <th class="px-6 py-4">Periode</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaigns as $camp)
                        <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                            <td class="whitespace-nowrap px-6 py-4 font-bold">{{ $camp->campaign_name }}</td>
                            <td class="whitespace-nowrap px-6 py-4">Rp{{ number_format($camp->budget) }}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{ $camp->target_audience }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                {{ $camp->start_date->format('d M') }} - {{ $camp->end_date->format('d M Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 flex gap-2">
                                <a href="{{ route('campaigns.edit', $camp->id) }}" class="text-blue-400 hover:text-blue-300">Edit</a>
                                <form action="{{ route('campaigns.destroy', $camp->id) }}" method="POST" onsubmit="return confirm('Hapus kampanye ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 ml-2">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>