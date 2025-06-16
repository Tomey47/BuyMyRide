@extends('admin.layouts.admin')

@section('content')
<div x-data="adminUsersData()" class="bg-white overflow-hidden border border-gray-200 sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Lietotāju Pārvaldība</h1>
            <div class="flex space-x-4">
                <form method="GET" action="{{ route('admin.users') }}" class="w-64">
                    <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                            placeholder="Meklēt lietotājus..."
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-custom-darkyellow focus:border-custom-darkyellow transition"
                        >
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nr.</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Lietotājs
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            E-pasts
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Loma
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pievienojās
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Darbības
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr>
                        <td class="py-4 whitespace-nowrap text-sm text-gray-500">{{ ($loop->iteration + ($users->currentPage() - 1) * $users->perPage()) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-profile.png') }}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $user->username }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_admin ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $user->is_admin ? 'Administrators' : 'Lietotājs' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d.m.Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex justify-between items-center">
                                <button
                                    type="button"
                                    @click="openDeleteModal({{ $user->id }})"
                                    class="text-red-600 hover:text-red-900 cursor-pointer"
                                >
                                    Dzēst
                                </button>
                                @if(!$user->is_admin)
                                <button
                                    type="button"
                                    @click="openMakeAdminModal({{ $user->id }}, '{{ $user->username }}')"
                                    class="text-green-600 hover:text-green-900 cursor-pointer"
                                >
                                    Padarīt par Administratoru
                                </button>
                                @else
                                <button
                                    type="button"
                                    @click="openRemoveAdminModal({{ $user->id }}, '{{ $user->username }}')"
                                    class="text-yellow-600 hover:text-yellow-900 cursor-pointer"
                                >
                                    Noņemt Administratora Tiesības
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
    </div>
</div>

    <!-- Delete Modal -->
    <div x-show="deleteModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center" 
         style="background: rgba(0,0,0,0.4);">
        <div @click.away="deleteModalOpen = false" class="bg-white rounded-xl border border-gray-200 w-full max-w-md p-6">
        <h2 class="text-xl font-bold mb-4">Dzēst lietotāju</h2>
        <p class="text-gray-600 mb-6">Vai tiešām vēlaties dzēst šo lietotāju? Šo darbību nevarēs atsaukt.</p>
        <div class="flex justify-end gap-2">
            <button
                @click="deleteModalOpen = false"
                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer"
            >
                Atcelt
            </button>
            <form :action="'{{ url('admin/users') }}/' + userToDelete" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 cursor-pointer"
                >
                    Dzēst
                </button>
            </form>
        </div>
    </div>
</div>

    <!-- Make Admin Modal -->
    <div x-show="makeAdminModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center" 
         style="background: rgba(0,0,0,0.4);">
        <div @click.away="makeAdminModalOpen = false" class="bg-white rounded-xl border border-gray-200 w-full max-w-md p-6">
            <h2 class="text-xl font-bold mb-4">Padarīt par Administratoru</h2>
            <p class="text-gray-600 mb-6">Vai tiešām vēlaties piešķirt administratora tiesības lietotājam <span x-text="selectedUsername" class="font-semibold"></span>?</p>
            <div class="flex justify-end gap-2">
                <button
                    @click="makeAdminModalOpen = false"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer"
                >
                    Atcelt
                </button>
                <form :action="'{{ url('admin/users') }}/' + userToModify + '/make-admin'" method="POST" class="inline">
                    @csrf
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 cursor-pointer"
                    >
                        Apstiprināt
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Remove Admin Modal -->
    <div x-show="removeAdminModalOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center" 
         style="background: rgba(0,0,0,0.4);">
        <div @click.away="removeAdminModalOpen = false" class="bg-white rounded-xl border border-gray-200 w-full max-w-md p-6">
            <h2 class="text-xl font-bold mb-4">Noņemt Administratora Tiesības</h2>
            <p class="text-gray-600 mb-6">Vai tiešām vēlaties noņemt administratora tiesības lietotājam <span x-text="selectedUsername" class="font-semibold"></span>?</p>
            <div class="flex justify-end gap-2">
                <button
                    @click="removeAdminModalOpen = false"
                    class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-custom-gray cursor-pointer"
                >
                    Atcelt
                </button>
                <form :action="'{{ url('admin/users') }}/' + userToModify + '/remove-admin'" method="POST" class="inline">
                    @csrf
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-yellow-600 text-white hover:bg-yellow-700 cursor-pointer"
                    >
                        Apstiprināt
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function adminUsersData() {
    return {
        deleteModalOpen: false,
        makeAdminModalOpen: false,
        removeAdminModalOpen: false,
        userToDelete: null,
        userToModify: null,
        selectedUsername: '',
        openDeleteModal(userId) {
            this.userToDelete = userId;
            this.deleteModalOpen = true;
        },
        openMakeAdminModal(userId, username) {
            this.userToModify = userId;
            this.selectedUsername = username;
            this.makeAdminModalOpen = true;
        },
        openRemoveAdminModal(userId, username) {
            this.userToModify = userId;
            this.selectedUsername = username;
            this.removeAdminModalOpen = true;
        }
    }
}
</script>
@endsection 