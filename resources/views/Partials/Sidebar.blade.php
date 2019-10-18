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
                    <span class="text">@if(Auth::user()->roles->first()->id == 0) All @else My @endif Orders</span>
                </a>
            </li>
			@if(Auth::user()->roles->first()->id == 0)
				<li class="{{ Request::is('*reports*') ? 'active' : '' }}">
				  <a href="#" data-modal-id="CreateReport" data-href="{{route('showReports')}}" class="loadModal">
					<span class="figure"><i class="ico-book2"></i></span>
					<span class="text">Reports</span></a>
				</li>
			@endif
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
        @if(Auth::user()->roles->first()->id == 0)
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
       @endif
    </section>
</aside>
