@forelse($inventories as $index => $inventory)
<tr>
    <td class="w-[50px] p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
        <span class="text-xs font-semibold leading-tight text-slate-400">{{ $inventories->firstItem() + $index }}</span>
    </td>
    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
        <span class="text-xs font-semibold leading-tight text-slate-400">{{ $inventory->category }}</span>
    </td>
    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
        <span class="text-xs font-semibold leading-tight text-slate-400">{{ $inventory->name }}</span>
    </td>
    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
        <span class="text-xs font-semibold leading-tight text-slate-400">{{ $inventory->description }}</span>
    </td>
    <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
        <span class="text-xs font-semibold leading-tight text-slate-400">{{ $inventory->total_quantity }}</span>
    </td>
    <td class="p-2 align-middle text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
        <div class="flex justify-center items-center">
            <a href="{{ route('inventories.show', $inventory->id) }}" class="text-blue-500 hover:text-blue-800 text-base transition duration-200 transform hover:scale-110 mr-2" title="Show">
                <i class="fas fa-eye align-middle"></i>
            </a>
            <a href="{{ route('inventories.edit', $inventory->id) }}" class="text-yellow-500 hover:text-yellow-600 text-base transition duration-200 transform hover:scale-110 mr-2" title="Edit">
                <i class="fas fa-pen-to-square"></i>
            </a>
            <form action="{{ route('inventories.destroy', $inventory->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $inventory->name }}?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-600 text-base transition duration-200 transform hover:scale-110" title="Delete">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="12" class="text-sm text-slate-400 italic text-center">No inventory found.</td>
</tr>
@endforelse