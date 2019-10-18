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
	<!--Script-->
	 @include('Shared.Layouts.ViewJavascript')
	<!--/Script-->
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
                    <span class="meta">
                        <span class="text ">{{ Auth::user()->Firstname . ' ' . Auth::user()->Lastname }} logged in</span>
                        <span class="arrow"></span>
                    </span>
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

@yield('menu')

<!--Main Content-->
<section id="main" role="main">
    <div class="container-fluid">
        <div class="page-title">
            <h1 class="title">@yield('page_title')</h1>
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
        @yield('content')
        <!--/Content-->
    </div>

    <!--To The Top-->
    <a href="#" style="display:none;" class="totop"><i class="ico-angle-up"></i></a>
    <!--/To The Top-->

</section>
<!--/Main Content-->
{!! HTML::script('assets/javascript/backend.js') !!}
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': "<?php echo csrf_token() ?>"
            }
        });
    });

    @if(!Auth::user()->first_name)
      setTimeout(function () {
        $('.editUserModal').click();
    }, 1000);
    @endif

</script>
<!--/JS-->

@yield('foot')

</body>
</html>
