<!DOCTYPE html>
<html lang="en">
<head>
	@include('layouts.layout-sub')
	<link rel="stylesheet" href="/css/new/layout.css">
	<link rel="stylesheet" href="/css/font-awesome.min.css">
  <script>
    $(function() {
      $("#btn-open-sidenav").click(function() {
        $("#wrapper").toggleClass("sidenav-open");
      })
    })
  </script>
</head>
<body>
	<div id="wrapper-wrapper">
    <div id="wrapper">
      @include('layouts.sidenav')
      <div id="content-wrapper">
        <div id="topnav">
			    <ul class="right-menu">
            <li class="dropdown-toggle">
              <a href="#">
                <span class="numberbox">{{Auth::user()->admissionyear}}</span>
                <span class="name">{{Auth::user()->realname}}</span>
                <img src="/image/no_profile.gif" class="profile">
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="/user/modify">Settings</a>
                </li>
                <li>
                  <a href="/logout">Logout</a>
                </li>
              </ul>
            </li>
          </ul>
          <div class="show-mobile open-sidenav-wrapper">
            <button id="btn-open-sidenav">
              <i class="fa fa-bars"></i>
            </button>
          </div>
        </div>
        <div id="content">
          @yield('content')
        </div>
        <div id="footer">
          Copyright &copy; 디닷컴 all rights reserved.
        </div>
      </div>
    </div>
  </div>
</body>
</html>


