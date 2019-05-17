<!-- AdminSideBar::START -->
<ul class="side_bar_menu">
    <li>
        <a href="/admin/report_queries"><span class="hover_span"></span><span class="link_span" @if(Request::is('admin')) style="color:#003366;" @else style="color:#7B8887;" @endif>Show Queries</span></a>
    </li>
    <li>
        <a href="/admin/force_login"><span class="hover_span"></span><span class="link_span" @if(Request::is('admin/force_login')) style="color:#003366;" @else style="color:#7B8887;" @endif>Force Login</span></a>
    </li>
    <li>
        <a href="/admin/error_emails"><span class="hover_span"></span><span class="link_span" @if(Request::is('admin/error_emails')) style="color:#003366;" @else style="color:#7B8887;" @endif>Error Emails</span></a>
    </li>
</ul>
<!-- AdminSideBar::END -->
