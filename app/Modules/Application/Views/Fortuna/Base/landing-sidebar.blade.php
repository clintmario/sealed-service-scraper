<!-- AdminSideBar::START -->
<style type="text/css">
    @media only screen and (min-width: 1280px) {
        .resp-vtabs ul.resp-tabs-list {
            width: 50%;
        }
    }
</style>
<div class="clearfix vertical  minimal_style resp-vtabs" style="display: block; width: 100%; margin: 0px; opacity: 1;">
    <ul class="resp-tabs-list" style="width:100%">
        <li onclick="location.href='/library';" @if(request()->path() == 'library') style="background: #005ed3;" @endif>
            <a href="/library" style="text-decoration: none;">
                <span class="icon icon-notebook" @if(request()->path() == 'library') style="color: #fff;" @else style="color: #003366" @endif></span>
                <span>
                    <b @if(request()->path() == 'library') style="color: #fff;" @else style="color: #003366" @endif>&nbsp;&nbsp;&nbsp;&nbsp;Subjects</b></span>
            </a>
        </li>
        <li onclick="location.href='/courses';" @if(request()->path() == 'courses') style="background: #005ed3;" @endif>
            <a href="/courses" style="text-decoration: none;">
                <span class="icon icon-book-open" @if(request()->path() == 'courses') style="color: #fff;" @else style="color: #003366" @endif></span>
                <span>
                    <b @if(request()->path() == 'courses') style="color: #fff" @else style="color: #003366" @endif>&nbsp;&nbsp;&nbsp;&nbsp;Courses</b></span>
            </a>
        </li>
        <li onclick="location.href='/tags';" @if(request()->path() == 'tags') style="background: #005ed3;" @endif>
            <a href="/tags" style="text-decoration: none;">
                <span class="icon icon-ribbon" @if(request()->path() == 'tags') style="color: #fff;" @else style="color: #003366" @endif></span>
                <span>
                    <b @if(request()->path() == 'tags') style="color: #fff" @else style="color: #003366" @endif>&nbsp;&nbsp;&nbsp;&nbsp;Tags</b></span>
            </a>
        </li>
        <li onclick="location.href='/assignments';" @if(request()->path() == 'assignments') style="background: #005ed3;" @endif>
            <a href="/assignments" style="text-decoration: none;">
                <span class="icon icon-note2" @if(request()->path() == 'assignments') style="color: #fff;" @else style="color: #003366" @endif></span>
                <span>
                    <b @if(request()->path() == 'assignments') style="color: #fff" @else style="color: #003366" @endif>&nbsp;&nbsp;&nbsp;&nbsp;Assignments</b></span>
            </a>
        </li>
        <li onclick="location.href='/tracks';" @if(request()->path() == 'tracks') style="background: #005ed3;" @endif>
            <a href="/tracks" style="text-decoration: none;">
                <span class="icon icon-photo-gallery" @if(request()->path() == 'tracks') style="color: #fff;" @else style="color: #003366" @endif></span>
                <span>
                    <b @if(request()->path() == 'tracks') style="color: #fff" @else style="color: #003366" @endif>&nbsp;&nbsp;&nbsp;&nbsp;Tracks</b></span>
            </a>
        </li>
        <li onclick="location.href='/lessons';" @if(request()->path() == 'lessons') style="background: #005ed3;" @endif>
            <a href="/lessons" style="text-decoration: none;">
                <span class="icon icon-note" @if(request()->path() == 'lessons') style="color: #fff;" @else style="color: #003366" @endif></span>
                <span>
                    <b @if(request()->path() == 'lessons') style="color: #fff" @else style="color: #003366" @endif>&nbsp;&nbsp;&nbsp;&nbsp;Lessons</b></span>
            </a>
        </li>
    </ul>
</div>
<!-- AdminSideBar::END -->
