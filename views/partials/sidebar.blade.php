<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset ("/bower_components/AdminLTE/dist/img/user2-160x160.jpg")}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>管理员</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">菜单</li>
            {{--@foreach($menu as $item)--}}
                {{--@if(!isset($item['children']))--}}
                {{--<li class="{!! isset($item['active']) ?'active':''; !!}"><a href="{{$item['url']}}"><i class="fa {{$item['icon']}}"></i> <span>{{$item['title']}}</span></a></li>--}}
                {{--@else--}}
                    {{--<li class="treeview {!! isset($item['active']) ?'active':''; !!}">--}}
                        {{--<a href="#"><i class="fa {{$item['icon']}}"></i> <span>{{$item['title']}}</span> <i class="fa fa-angle-left pull-right"></i></a>--}}
                        {{--<ul class="treeview-menu">--}}
                            {{--@foreach($item['children'] as $item)--}}
                            {{--<li class="{!! isset($item['active']) ?'active':''; !!}"><a href="{{$item['url']}}"><i class="fa {{$item['icon']}}"></i> <span>{{$item['title']}}</span></a></li>--}}
                            {{--@endforeach--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                {{--@endif--}}
            {{--@endforeach--}}

            <!-- Optionally, you can add icons to the links -->
            <li><a href="/admin"><i class="fa fa-link"></i> <span>首页</span></a></li>
            <li><a href="/admin/users"><i class="fa fa-users"></i> <span>用户管理</span></a></li>

            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>项目管理</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="/admin/projects"><i class="fa fa-user"></i> <span>项目管理</span></a></li>
                    <li><a href="/admin/categories"><i class="fa fa-user"></i> <span>项目分类</span></a></li>
                    <li><a href="/admin/projects/recommend"><i class="fa fa-user"></i> <span>推荐众筹</span></a></li>
                </ul>
            </li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>