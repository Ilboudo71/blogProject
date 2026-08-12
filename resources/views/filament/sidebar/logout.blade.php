<form
    action="{{ filament()->getLogoutUrl() }}"
    method="post"
    class="fi-sidebar-logout"
>
    @csrf
    <button type="submit" class="fi-sidebar-logout-btn">
        <svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12h9m0 0-3-3m3 3-3 3" />
        </svg>
        <span>Déconnexion</span>
    </button>
</form>
