<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Makanan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('foods.update', $food->id) }}" method="POST" enctype="multipart/form-data" 
                  class="bg-white p-6 rounded-lg shadow-md border">
                @csrf
                @method('PUT')

                {{-- Nama Makanan --}}
                <div class="mb-4">
                    <label class="block font-medium mb-1">Nama Makanan</label>
                    <input type="text" name="name" value="{{ old('name', $food->name) }}" 
                           class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                           required>
                </div>

                {{-- Kategori --}}
                <div class="mb-4">
                    <label class="block font-medium mb-1">Kategori</label>
                    <select name="category" 
                            class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                            required>
                        <option value="Makanan" {{ $food->category == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                        <option value="Minuman" {{ $food->category == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                        <option value="Cemilan" {{ $food->category == 'Cemilan' ? 'selected' : '' }}>Cemilan</option>
                    </select>
                </div>

                {{-- Harga --}}
                <div class="mb-4">
                    <label class="block font-medium mb-1">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $food->price) }}" 
                           class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                           min="0" required>
                </div>

                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label class="block font-medium mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" 
                              class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                              required>{{ old('description', $food->description) }}</textarea>
                </div>

                {{-- Gambar Lama + Upload Baru --}}
                <div class="mb-4">
                    <label class="block font-medium mb-1">Gambar Baru (Opsional)</label>
                    
                    @if($food->image)
                        <div class="mb-2">
                            <p class="text-sm text-gray-600 mb-1">Gambar Saat Ini:</p>
                            <img src="{{ asset('storage/' . $food->image) }}" 
                                 class="w-32 h-32 object-cover rounded border">
                        </div>
                    @endif

                    <input type="file" name="image" 
                           class="w-full border rounded p-2 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin ganti gambar</p>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded font-semibold">
                        Update Data
                    </button>
                    <a href="{{ route('foods.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded font-semibold">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>