<nav class="menu">
    <ul class="items">
        <a href="/messageapp/home">
            <li class="item">
                <i class="fa fa-commenting" aria-hidden="true"></i>
            </li>
        </a>
        
        <a href="/messageapp/user/contacts/saved">
            <li class="item">
                <i class="fa-solid fa-phone" aria-hidden="true"></i>
            </li>
        </a>

        <a href="/messageapp/user/<?= $logged_user_id ?>/profile">
            <li class="item">
                <i class="fa fa-user" aria-hidden="true"></i>
            </li>
        </a>

        <a href="/messageapp/logout">
            <li class="item">
                <i class="fa-solid fa-right-from-bracket logout-button"></i>
            </li>
        </a>
    </ul>
</nav>
