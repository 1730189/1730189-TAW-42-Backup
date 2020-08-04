<div class="wrapper ">
  @include('layouts.navbars.sidebar')
  <div class="main-panel" id="aplic">
    @include('layouts.navbars.navs.auth')
    @yield('content')
    @include('layouts.footers.auth')
  </div>
</div>