<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex justify-between items-center">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Pesanan Masuk') }}
            </h2>

            <div class="flex items-center gap-3">

                <a href="{{ route('foods.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition">
                    Kelola Menu
                </a>

                <a href="{{ route('customer.index') }}"
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 shadow-sm transition">
                    Lihat Menu Customer
                </a>

            </div>

        </div>
    </x-slot>


    {{-- CONTENT --}}
    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif


            {{-- ERROR --}}
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif


            {{-- VALIDATION ERROR --}}
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">

                    <ul class="list-disc list-inside">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>
            @endif


            {{-- TABLE --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">

                <div class="p-6 text-gray-900 overflow-x-auto">

                    <table class="w-full text-left border-collapse">

                        {{-- HEADER TABLE --}}
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">

                            <tr>

                                <th class="p-4 border-b">
                                    # ID
                                </th>

                                <th class="p-4 border-b">
                                    Pelanggan
                                </th>

                                <th class="p-4 border-b">
                                    No. Meja
                                </th>

                                <th class="p-4 border-b">
                                    Rincian Pesanan
                                </th>

                                <th class="p-4 border-b">
                                    Total Harga
                                </th>

                                <th class="p-4 border-b">
                                    Status
                                </th>

                                <th class="p-4 border-b text-center">
                                    Aksi Status
                                </th>

                            </tr>

                        </thead>


                        {{-- BODY TABLE --}}
                        <tbody class="divide-y text-sm">

                            @forelse($orders as $order)

                                <tr class="hover:bg-gray-50 transition">


                                    {{-- ID --}}
                                    <td class="p-4 font-bold text-gray-700">
                                        #{{ $order->id }}
                                    </td>


                                    {{-- CUSTOMER --}}
                                    <td class="p-4 font-medium">
                                        {{ $order->customer_name }}
                                    </td>


                                    {{-- TABLE --}}
                                    <td class="p-4">

                                        <span class="bg-blue-100 text-blue-800 font-bold px-3 py-1 rounded-full text-xs">
                                            Meja {{ $order->table_number }}
                                        </span>

                                    </td>


                                    {{-- DETAIL PESANAN --}}
                                    <td class="p-4">

                                        @if($order->orderDetails && $order->orderDetails->count())

                                            <ul class="list-disc list-inside space-y-1 text-gray-600">

                                                @foreach($order->orderDetails as $detail)

                                                    <li>

                                                        <strong>
                                                            {{ $detail->food->name ?? 'Menu Dihapus' }}
                                                        </strong>

                                                        x{{ $detail->quantity }}

                                                        <span class="text-xs text-gray-400">
                                                            (Rp {{ number_format($detail->subtotal, 0, ',', '.') }})
                                                        </span>

                                                    </li>

                                                @endforeach

                                            </ul>

                                        @else

                                            <span class="text-gray-400 italic">
                                                Tidak ada rincian item
                                            </span>

                                        @endif

                                    </td>


                                    {{-- TOTAL --}}
                                    <td class="p-4 font-bold text-green-600">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>


                                    {{-- STATUS --}}
                                    <td class="p-4">

                                        @if($order->status === 'pending')

                                            <span class="inline-block bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded">
                                                PENDING
                                            </span>

                                        @elseif($order->status === 'diproses')

                                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded">
                                                DIPROSES
                                            </span>

                                        @elseif($order->status === 'selesai')

                                            <span class="inline-block bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded">
                                                SELESAI
                                            </span>

                                        @else

                                            <span class="inline-block bg-gray-100 text-gray-800 text-xs font-bold px-3 py-1 rounded">
                                                {{ strtoupper($order->status) }}
                                            </span>

                                        @endif

                                    </td>


                                    {{-- UPDATE STATUS --}}
                                    <td class="p-4 text-center">

                                        <form
                                            action="{{ route('admin.orders.updateStatus', $order->id) }}"
                                            method="POST"
                                        >

                                            @csrf

                                            @method('PATCH')


                                            <select
                                                name="status"
                                                onchange="this.form.submit()"
                                                class="text-xs border border-gray-300 rounded-md p-2 bg-white shadow-sm font-semibold cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                            >

                                                {{-- PENDING --}}
                                                <option
                                                    value="pending"
                                                    {{ $order->status === 'pending' ? 'selected' : '' }}
                                                >
                                                    Pending
                                                </option>


                                                {{-- DIPROSES --}}
                                                <option
                                                    value="diproses"
                                                    {{ $order->status === 'diproses' ? 'selected' : '' }}
                                                >
                                                    Diproses
                                                </option>


                                                {{-- SELESAI --}}
                                                <option
                                                    value="selesai"
                                                    {{ $order->status === 'selesai' ? 'selected' : '' }}
                                                >
                                                    Selesai / Lunas
                                                </option>

                                            </select>

                                        </form>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="p-10 text-center text-gray-500"
                                    >

                                        <div class="flex flex-col items-center justify-center">

                                            <div class="text-5xl mb-4">
                                                📦
                                            </div>

                                            <p class="font-medium">
                                                Belum ada pesanan masuk.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
