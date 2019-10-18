<!DOCTYPE html>
<html>
<head>

    <title>
        @section('title')
            ticketsApp ::
        @show
    </title>

    <!--Meta-->
    @include('Shared.Partials.GlobalMeta')
   <!--/Meta-->

    <!--JS-->
    {!! HTML::script('/js/jquery.min.js') !!}
    <!--/JS-->

    <!--Style-->
    {!! HTML::style('/assets/stylesheet/application.css') !!}
    {!! HTML::style('/css/style.css') !!}
    <!--/Style-->

    @yield('head')
</head>
<body class="attendize">

<header id="header" class="navbar">

    <div class="navbar-header">
        <a class="navbar-brand" href="javascript:void(0);">
            <img class="logo" alt="tickets" src="{{asset('assets/images/digital-logo.png')}}"/>
        </a>
    </div>

    <div class="navbar-toolbar clearfix">
        @yield('top_nav')

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown profile">
                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                    <span class="meta"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="{{route('logout')}}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();"><span class="icon ico-exit"></span>Sign Out</a>
                             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                 {{ csrf_field() }}
                             </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>
<aside class="sidebar sidebar-left sidebar-menu">
    <section class="content">
        <h5 class="heading">Main Menu</h5>

        <ul id="nav" class="topmenu">
            <li class="{{ Request::is('*dashboard*') ? 'active' : '' }}">
                <a href="{{route('showDashboard')}}">
                    <span class="figure"><i class="ico-calendar"></i></span>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="{{ Request::is('*order*') ? 'active' : '' }}">
                <a href="{{route('showOrders')}}">
                    <span class="figure"><i class="ico-cart"></i></span>
                    <span class="text">My Orders</span>
                </a>
            </li>
            <li class="{{ Request::is('*reports*') ? 'active' : '' }}">
              <a href="#" data-modal-id="CreateReport" data-href="{{route('showReports')}}" class="loadModal">
                <span class="figure"><i class="ico-book2"></i></span>
                <span class="text">Reports</span></a>

            </li>

        </ul>
		@permission('ticket_administration')
		<h5 class="heading">Ticket Administration</h5>
		<ul id="nav3" class="topmenu">			
			<li>
				<a href="/admin/ticket-allowance">
				<span class="figure"><i class="ico-calendar"></i></span>
				<span class="text">Ticket Allowance</span></a>
			</li>		
		</ul>
		@endpermission
        @permission('edit_user')
        <h5 class="heading">User Administration</h5>
        @foreach($laravelAdminMenus->menus as $section)
          @if($section->items)
            <ul id="nav2" class="topmenu">
              @foreach($section->items as $menu)
              <li class="{{ Request::is('*users*') ? 'active' : '' }}">
                <a href="{{ url($menu->url) }}">
                <span class="figure"><i class="ico-calendar"></i></span>
                <span class="text">{{ $menu->title }}</span>
              </a>
              </li>
              @endforeach
            </ul>
          @endif
       @endforeach
       @endpermission
    </section>
</aside>
<!--Main Content-->
<section id="main" role="main">
    <div class="container-fluid">
        <div class="page-title">
            <h1 class="title"></h1>
        </div>
        @if(array_key_exists('page_header', View::getSections()))
        <!--  header -->
        <div class="page-header page-header-block row">
            <div class="row">
                @yield('page_header')
            </div>
        </div>
        <!--/  header -->
        @endif

        <!--Content-->
        
		<div class="container login-outer">
			<div class="row">
				<div class="col-md-12 col-xs-12 col-sm-12 login-content-head text-left">
					<h3>The page you are trying to access could not be found. Please click <a href="http://tickets.3apps.ie">here</a> to navigate to the main page.</h3>
				</div>
			</div>
		</div>

        <!--/Content-->
    </div>

    <!--To The Top-->
    <a href="#" style="display:none;" class="totop"><i class="ico-angle-up"></i></a>
    <!--/To The Top-->

</section>
<!--/Main Content-->

@yield('foot')

</body>
</html>