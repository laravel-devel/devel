<header class="header">
    <div class="left"></div>

    <div class="right">
        <a href="#" onclick="document.getElementById('logout').submit()">Logout</a>

        <form id="logout"
            action="{{ route('dashboard.auth.logout') }}"
            method="POST">@csrf</form>
    </div>
</header>